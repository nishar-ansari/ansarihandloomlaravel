<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function showContact()
    {
        return view('cms.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        ContactInquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your inquiry has been submitted successfully! We will get back to you soon.');
    }

    public function showAbout()
    {
        return view('cms.about');
    }

    public function showFAQ()
    {
        return view('cms.faq');
    }
}
