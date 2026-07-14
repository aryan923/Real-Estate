<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $allPosts = Post::where('status', true)->latest()->get();

        $query = trim((string) $request->input('q', ''));
        if ($query !== '') {
            $posts = Post::where('status', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%")
                      ->orWhere('category', 'like', "%{$query}%");
                })
                ->latest()
                ->paginate(4)
                ->withQueryString();
        } else {
            $posts = Post::where('status', true)->latest()->paginate(4);
        }

        $recentPosts = $allPosts->take(4);
        $featuredPosts = $allPosts->slice(1, 2);

        return view('blog', compact('allPosts', 'posts', 'recentPosts', 'featuredPosts'));
    }

    public function show(string $slug): View
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $allPosts = Post::where('status', true)->latest()->get();
        $recentPosts = $allPosts->take(4);

        return view('blog-detail', [
            'post' => $post,
            'recentPosts' => $recentPosts,
        ]);
    }
}
