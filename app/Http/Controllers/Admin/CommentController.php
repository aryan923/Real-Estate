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
            ->addColumn('status_label', fn (Comment $comment): string => $comment->status ? 'Approved' : 'Pending')
            ->addColumn('action', function (Comment $comment): string {
                $statusBtnText = $comment->status ? 'Disapprove' : 'Approve';
                $statusBtnClass = $comment->status 
                    ? 'rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2 text-sm font-medium text-yellow-700 hover:bg-yellow-100 dark:border-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-200'
                    : 'rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:border-green-600 dark:bg-green-500/10 dark:text-green-200';

                return '<div class="flex flex-wrap gap-2">'
                    . '<form method="POST" action="' . route('admin.comments.toggle', ['comment' => $comment->id]) . '" class="inline-block">'
                    . csrf_field()
                    . '<button type="submit" class="' . $statusBtnClass . '">' . $statusBtnText . '</button>'
                    . '</form>'
                    . '<form method="POST" action="' . route('admin.comments.destroy', ['comment' => $comment->id]) . '" class="inline-block" data-confirm="Delete this comment?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action'])
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
