<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("contact.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("contact.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:10000'],
        ];

        if ($request->user()) {
            $request->validate($rules);

            Message::create([
                'user_id' => $request->user()->id,
                'guest_name' => null,
                'guest_email' => null,
                'subject' => $request->subject,
                'content' => $request->content,
                'read_by_admin' => false,
            ]);

            return redirect()->route('account.messages.index')->with('success', 'Message sent successfully.');
        }

        $request->validate($rules + [
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
        ]);

        Message::create([
            'user_id' => null,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'subject' => $request->subject,
            'content' => $request->content,
            'read_by_admin' => false,
        ]);

        return redirect()->route('home')->with('success', 'Thank you — we have received your message and will get back to you soon.');
    }
}
