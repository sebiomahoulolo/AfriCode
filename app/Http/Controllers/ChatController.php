<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver', 'course'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id === Auth::id() 
                    ? $message->receiver_id 
                    : $message->sender_id;
            });

        return view('chat.index', compact('conversations'));
    }

    public function show(User $user, Course $course = null)
    {
        $messages = Message::where(function ($query) use ($user, $course) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user, $course) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        });

        if ($course) {
            $messages->where('course_id', $course->id);
        }

        $messages = $messages->orderBy('created_at', 'asc')->get();

        // Marquer les messages comme lus
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return view('chat.show', compact('messages', 'user', 'course'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string',
            'type' => 'required|in:text,file,image',
            'file' => 'required_if:type,file,image|file|max:10240'
        ]);

        $message = new Message([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'content' => $request->content,
            'type' => $request->type,
            'course_id' => $request->course_id
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('chat-files');
            
            $message->file_url = $path;
            $message->file_name = $file->getClientOriginalName();
            $message->file_size = $file->getSize();
        }

        $message->save();

        // Ici, vous pouvez implémenter la logique de notification en temps réel
        // Par exemple, avec Laravel Echo et Pusher

        return response()->json($message->load('sender'));
    }

    public function markAsRead(Message $message)
    {
        if ($message->receiver_id === Auth::id()) {
            $message->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(Message $message)
    {
        if ($message->sender_id === Auth::id()) {
            if ($message->file_url) {
                Storage::delete($message->file_url);
            }
            $message->delete();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
} 