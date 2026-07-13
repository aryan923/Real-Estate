<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ImageuploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.posts.index');
    }

    public function data(Request $request)
    {
        $query = Post::query();

        if ($request->filled('status_filter') && in_array($request->string('status_filter')->toString(), ['active', 'inactive'], true)) {
            $query->where('status', $request->string('status_filter')->toString() === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('image_preview', function (Post $post): string {
                if (! $post->image) {
                    return '<div class="flex h-10 w-10 items-center justify-center rounded border border-dashed border-gray-300 text-xs font-semibold text-gray-400 dark:border-gray-700 dark:text-gray-500">N/A</div>';
                }
                $imageUrl = e(ImageuploadHelper::getUploadedFileUrl($post->image));
                $alt = e($post->title);
                return '<img src="' . $imageUrl . '" alt="' . $alt . '" class="h-10 w-16 rounded border border-gray-200 object-cover dark:border-gray-700" />';
            })
            ->addColumn('status_label', fn (Post $post): string => $post->status ? 'Active' : 'Inactive')
            ->addColumn('action', function (Post $post): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.posts.edit', ['post' => $post->id]) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.posts.destroy', ['post' => $post->id]) . '" class="inline-block" data-confirm="Delete this post?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['image_preview', 'action'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pages.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);

        $post = DB::transaction(function () use ($validated, $request) {
            return Post::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'category' => $validated['category'],
                'image' => $this->storeImage($request),
                'excerpt' => $validated['excerpt'],
                'content' => $validated['content'],
                'author' => $validated['author'] ?? 'admin',
                'status' => $validated['status'],
                'date' => $validated['date'] ?? now()->format('M d, Y'),
            ]);
        });

        return redirect()
            ->route('admin.posts')
            ->with('status', "Post '{$post->title}' created successfully.");
    }

    public function edit(Post $post): View
    {
        return view('admin.pages.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $this->validatePost($request, $post);

        DB::transaction(function () use ($validated, $request, $post) {
            $payload = [
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'category' => $validated['category'],
                'excerpt' => $validated['excerpt'],
                'content' => $validated['content'],
                'author' => $validated['author'] ?? 'admin',
                'status' => $validated['status'],
                'date' => $validated['date'] ?? $post->date,
            ];

            if ($request->hasFile('image')) {
                $this->deleteImage($post->image);
                $payload['image'] = $this->storeImage($request);
            }

            $post->update($payload);
        });

        return redirect()
            ->route('admin.posts')
            ->with('status', "Post '{$post->title}' updated successfully.");
    }

    public function destroy(Post $post): RedirectResponse
    {
        $title = $post->title;
        DB::transaction(function () use ($post) {
            $this->deleteImage($post->image);
            $post->delete();
        });

        return redirect()
            ->route('admin.posts')
            ->with('status', "Post '{$title}' deleted successfully.");
    }

    private function validatePost(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
            'date' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return ImageuploadHelper::upload($request->file('image'), 'posts');
    }

    private function deleteImage(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://', '/'])) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
