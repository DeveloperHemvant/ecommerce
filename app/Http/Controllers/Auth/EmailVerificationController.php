<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Show the "check your inbox" notice for an unverified customer.
     */
    public function notice(): View|RedirectResponse
    {
        if (Auth::user()?->hasVerifiedEmail()) {
            return redirect()->route('collections');
        }

        return view('auth.verify-email');
    }

    /**
     * Handle the signed verification link from the email.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->fulfill();
        }

        return redirect()->route('collections')->with('success', 'Your email address is verified.');
    }

    /**
     * Resend the verification email.
     */
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('collections');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }
}
