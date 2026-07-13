<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($validated, $post) {
            Comment::create([
                'post_id' => $post->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'website' => $validated['website'],
                'content' => $validated['content'],
                'status' => true, // Auto-approve by default for simplicity, can moderate in admin panel
            ]);
        });

        return redirect()
            ->back()
            ->with('status', 'Your comment has been posted successfully!');
    }
}
