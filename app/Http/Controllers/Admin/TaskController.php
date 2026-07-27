<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'enquiry_id' => 'nullable|exists:enquiries,id',
            'follow_up_id' => 'nullable|exists:follow_ups,id',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'description' => 'nullable|string',
        ]);

        $status = $validated['status'] ?? 'pending';
        $completedAt = $status === 'completed' ? now() : null;

        $task = Task::create([
            'title' => $validated['title'],
            'enquiry_id' => $validated['enquiry_id'] ?? null,
            'follow_up_id' => $validated['follow_up_id'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? auth()->id(),
            'created_by' => auth()->id(),
            'due_date' => $validated['due_date'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'status' => $status,
            'description' => $validated['description'] ?? null,
            'completed_at' => $completedAt,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task, 'message' => 'Task created successfully.']);
        }

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'description' => 'nullable|string',
        ]);

        $status = $validated['status'] ?? $task->status;
        $completedAt = $task->completed_at;
        if ($status === 'completed' && $task->status !== 'completed') {
            $completedAt = now();
        } elseif ($status !== 'completed') {
            $completedAt = null;
        }

        $task->update([
            'title' => $validated['title'],
            'assigned_to' => $validated['assigned_to'] ?? $task->assigned_to,
            'due_date' => $validated['due_date'] ?? $task->due_date,
            'priority' => $validated['priority'] ?? $task->priority,
            'status' => $status,
            'description' => $validated['description'] ?? $task->description,
            'completed_at' => $completedAt,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task, 'message' => 'Task updated successfully.']);
        }

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function toggle(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $newStatus = $task->status === 'completed' ? 'pending' : 'completed';
        $completedAt = $newStatus === 'completed' ? now() : null;

        $task->update([
            'status' => $newStatus,
            'completed_at' => $completedAt,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task, 'message' => 'Task status toggled.']);
        }

        return redirect()->back()->with('success', 'Task status updated.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $task->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Task deleted.']);
        }

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
