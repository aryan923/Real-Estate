<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CommentController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.comments.index');
    }

    public function data(Request $request)
    {
        $query = Comment::query()->with('post');

        if ($request->filled('status_filter') && in_array($request->string('status_filter')->toString(), ['active', 'inactive'], true)) {
            $query->where('status', $request->string('status_filter')->toString() === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('post_title', fn (Comment $comment): string => $comment->post ? $comment->post->title : 'N/A')
            ->addColumn('content_excerpt', fn (Comment $comment): string => \Illuminate\Support\Str::limit($comment->content, 80))
            ->addColumn('status_label', function (Comment $comment): string {
                return $comment->status
                    ? '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #10b981; color: #ffffff;">Approved</span>'
                    : '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #f59e0b; color: #ffffff;">Pending</span>';
            })
            ->addColumn('action', function (Comment $comment): string {
                if ($comment->status) {
                    $statusBtn = '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-yellow-200 bg-yellow-50 p-2 text-yellow-600 hover:bg-yellow-100 dark:border-yellow-600/30 dark:bg-yellow-500/10 dark:text-yellow-400 dark:hover:bg-yellow-500/20" title="Disapprove">'
                        . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>'
                        . '</button>';
                } else {
                    $statusBtn = '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-green-200 bg-green-50 p-2 text-green-600 hover:bg-green-100 dark:border-green-600/30 dark:bg-green-500/10 dark:text-green-400 dark:hover:bg-green-500/20" title="Approve">'
                        . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                        . '</button>';
                }

                return '<div class="flex flex-wrap gap-2">'
                    . '<form method="POST" action="' . route('admin.comments.toggle', ['comment' => $comment->id]) . '" class="inline-block">'
                    . csrf_field()
                    . $statusBtn
                    . '</form>'
                    . '<form method="POST" action="' . route('admin.comments.destroy', ['comment' => $comment->id]) . '" class="inline-block" data-confirm="Delete this comment?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action', 'status_label'])
            ->toJson();
    }

    public function toggle(Comment $comment): RedirectResponse
    {
        DB::transaction(function () use ($comment) {
            $comment->update([
                'status' => !$comment->status,
            ]);
        });

        $statusStr = $comment->status ? 'approved' : 'marked as pending';
        return redirect()
            ->route('admin.comments')
            ->with('status', "Comment by '{$comment->name}' has been successfully {$statusStr}.");
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $name = $comment->name;
        DB::transaction(function () use ($comment) {
            $comment->delete();
        });

        return redirect()
            ->route('admin.comments')
            ->with('status', "Comment by '{$name}' deleted successfully.");
    }
}
