<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Show About Us page.
     */
    public function about(): View
    {
        return view('pages.about');
    }

    /**
     * Show Contact Us page.
     */
    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * Handle Contact form submission.
     */
    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        $adminEmails = User::where('role', 'admin')->pluck('email');
        foreach ($adminEmails as $adminEmail) {
            Mail::to($adminEmail)->send(new ContactMessageReceived($contactMessage));
        }

        return back()->with('success', 'Thank you for reaching out to Sonakshi Fashion Hub! Our bridal styling concierge will respond within 24 hours.');
    }

    /**
     * Show Shipping & Delivery Policy page.
     */
    public function shipping(): View
    {
        return view('pages.shipping-policy');
    }

    /**
     * Show Terms of Service page.
     */
    public function terms(): View
    {
        return view('pages.terms');
    }

    /**
     * Show Returns & Exchanges Policy page.
     */
    public function returns(): View
    {
        return view('pages.return-policy');
    }

    /**
     * Show Privacy Policy page.
     */
    public function privacy(): View
    {
        return view('pages.privacy-policy');
    }
}
