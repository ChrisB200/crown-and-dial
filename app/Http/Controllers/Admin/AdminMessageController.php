<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $messages = Message::query()
            ->with('user')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('subject', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%")
                        ->orWhere('guest_email', 'like', "%{$q}%")
                        ->orWhere('guest_name', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('email', 'like', "%{$q}%")
                                ->orWhere('name', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.messages.index', compact('messages', 'q'));
    }

    public function show(Message $message)
    {
        $message->load('user');
        if (! $message->read_by_admin) {
            $message->read_by_admin = true;
            $message->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:10000'],
        ]);

        $message->admin_reply = $validated['admin_reply'];
        $message->replied_at = now();
        $message->save();

        return redirect()
            ->route('admin.messages.show', $message)
            ->with('status', 'Your reply has been saved. The customer can see it in their account messages.');
    }
}
