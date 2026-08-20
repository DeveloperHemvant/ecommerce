<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display the customer's orders list.
     */
    public function orders(): View
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->latest()->paginate(10);

        return view('account.orders', compact('user', 'orders'));
    }

    /**
     * Display single customer order tracking & invoice view.
     */
    public function orderDetail(string $orderNumber): View
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with(['items.product'])
            ->firstOrFail();

        return view('account.order-detail', compact('user', 'order'));
    }

    /**
     * Display the customer profile & settings.
     */
    public function profile(): View
    {
        $user = Auth::user();

        return view('account.profile', compact('user'));
    }

    /**
     * Update customer profile details & password.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if (! empty($validated['new_password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match our records.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->save();

        return back()->with('success', 'Your profile details have been updated.');
    }
}
