<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\CartSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function __construct(protected CartSyncService $cartSync) {}

    /**
     * Display the customer login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('collections');
        }

        return view('auth.login');
    }

    /**
     * Handle a customer login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Check if login was with email or phone
        $loginField = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$loginField => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            $merged = $this->cartSync->mergeOnLogin(Auth::id(), session()->get('cart', []));
            session()->put('cart', $merged);
            $this->mergeWishlistOnLogin(Auth::id());

            return redirect()->intended(route('collections'))->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    /**
     * Display the customer registration form.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('collections');
        }

        return view('auth.register');
    }

    /**
     * Handle customer registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'customer',
            'password' => Hash::make($validated['password']),
        ]);

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        $request->session()->regenerate();

        $merged = $this->cartSync->mergeOnLogin($user->id, session()->get('cart', []));
        session()->put('cart', $merged);
        $this->mergeWishlistOnLogin($user->id);

        return redirect()->intended(route('collections'))->with('success', 'Account created successfully! Welcome to Sonakshi Fashion Hub.');
    }

    /**
     * Display the forgot password form.
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the given email address.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the reset password form.
     */
    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset the customer's password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Your password has been reset. Please sign in.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Merge a guest's session wishlist into their account, matching the cart's
     * mergeOnLogin behavior so favorited items aren't silently lost on login.
     */
    private function mergeWishlistOnLogin(int $userId): void
    {
        $sessionWishlist = session('wishlist', []);
        if (empty($sessionWishlist)) {
            return;
        }

        foreach ($sessionWishlist as $productId) {
            Wishlist::firstOrCreate([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
        }

        session()->forget('wishlist');
    }

    /**
     * Log out customer.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
