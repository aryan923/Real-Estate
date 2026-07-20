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
            ->addColumn('status_label', function (Post $post): string {
                return $post->status
                    ? '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #10b981; color: #ffffff;">Active</span>'
                    : '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #6b7280; color: #ffffff;">Inactive</span>';
            })
            ->addColumn('action', function (Post $post): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.posts.edit', ['post' => $post->id]) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.posts.destroy', ['post' => $post->id]) . '" class="inline-block" data-confirm="Delete this post?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['image_preview', 'action', 'status_label'])
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
