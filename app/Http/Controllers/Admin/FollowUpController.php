<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use App\Models\Enquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FollowUpController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.enquiries.index');
    }

    public function data(Request $request)
    {
        $query = FollowUp::query()->with(['enquiry', 'creator']);

        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        return DataTables::eloquent($query)
            ->addColumn('enquiry_name', function (FollowUp $followUp): string {
                if (!$followUp->enquiry) return 'N/A';
                return '<a href="' . route('admin.enquiries.show', $followUp->enquiry_id) . '" class="text-brand-600 hover:text-brand-700 dark:text-brand-400 font-medium">' . e($followUp->enquiry->name) . '</a>';
            })
            ->addColumn('enquiry_phone', fn(FollowUp $followUp): string => $followUp->enquiry?->phone ?: 'N/A')
            ->addColumn('scheduled_time', fn(FollowUp $followUp): string => $followUp->scheduled_at ? $followUp->scheduled_at->format('d M Y, h:i A') : 'N/A')
            ->addColumn('action', function (FollowUp $followUp): string {
                $actions = '<div class="flex flex-wrap gap-2">';
                
                if ($followUp->status === 'pending') {
                    $actions .= '<button type="button" @click="openLogModal(' . $followUp->id . ')" class="inline-flex items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 px-2 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:border-emerald-600/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20" title="Log Outcome">'
                        . 'Log Outcome'
                        . '</button>';
                }

                $actions .= '<a href="' . route('admin.follow-ups.edit', $followUp) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.follow-ups.destroy', $followUp) . '" class="inline-block" data-confirm="Delete this follow-up scheduled event?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
                return $actions;
            })
            ->editColumn('status', function (FollowUp $followUp): string {
                $status = $followUp->status;
                $label = ucfirst($status);

                switch ($status) {
                    case 'completed':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">' . $label . '</span>';
                    case 'cancelled':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400">' . $label . '</span>';
                    default: // 'pending'
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">' . $label . '</span>';
                }
            })
            ->editColumn('outcome', function (FollowUp $followUp): string {
                if (!$followUp->outcome) return '<span class="text-gray-400 text-xs">Pending call</span>';
                return '<span class="text-xs font-semibold capitalize text-gray-700 dark:text-gray-300">' . str_replace('_', ' ', $followUp->outcome) . '</span>';
            })
            ->rawColumns(['enquiry_name', 'action', 'status', 'outcome'])
            ->toJson();
    }

    public function create(Request $request): View
    {
        $selectedEnquiryId = $request->input('enquiry_id');
        
        return view('admin.pages.follow-ups.form', [
            'mode' => 'create',
            'followUp' => null,
            'enquiries' => Enquiry::orderBy('name')->get(),
            'selectedEnquiryId' => $selectedEnquiryId
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enquiry_id' => 'required|exists:enquiries,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
            'reminder_before_minutes' => 'required|integer|min:0',
            'status' => 'required|in:pending,completed,cancelled',
            'call_notes' => 'nullable|string',
            'outcome' => 'nullable|in:interested,need_more_time,not_interested,booked_visit,no_response',
        ]);

        $validated['created_by'] = auth()->id();

        $followUp = FollowUp::create($validated);

        if ($followUp->enquiry_id) {
            return redirect()->route('admin.enquiries.show', $followUp->enquiry_id)->with('success', 'Follow-up scheduled successfully.');
        }

        return redirect()->route('admin.enquiries.index')->with('success', 'Follow-up scheduled successfully.');
    }

    public function edit(FollowUp $followUp): View
    {
        return view('admin.pages.follow-ups.form', [
            'mode' => 'edit',
            'followUp' => $followUp,
            'enquiries' => Enquiry::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, FollowUp $followUp): RedirectResponse
    {
        $validated = $request->validate([
            'enquiry_id' => 'required|exists:enquiries,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
            'reminder_before_minutes' => 'required|integer|min:0',
            'status' => 'required|in:pending,completed,cancelled',
            'call_notes' => 'nullable|string',
            'outcome' => 'nullable|in:interested,need_more_time,not_interested,booked_visit,no_response',
        ]);

        $followUp->update($validated);

        if ($followUp->enquiry_id) {
            return redirect()->route('admin.enquiries.show', $followUp->enquiry_id)->with('success', 'Follow-up updated successfully.');
        }

        return redirect()->route('admin.enquiries.index')->with('success', 'Follow-up updated successfully.');
    }

    public function logOutcome(Request $request, FollowUp $followUp): JsonResponse
    {
        $validated = $request->validate([
            'outcome' => 'required|in:interested,need_more_time,not_interested,booked_visit,no_response',
            'call_notes' => 'nullable|string',
            'next_scheduled_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        $followUp->update([
            'outcome' => $validated['outcome'],
            'call_notes' => $validated['call_notes'],
            'status' => 'completed',
        ]);

        if (!empty($validated['next_scheduled_at'])) {
            FollowUp::create([
                'enquiry_id' => $followUp->enquiry_id,
                'scheduled_at' => $validated['next_scheduled_at'],
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Follow-up logged successfully.'
        ]);
    }

    public function destroy(FollowUp $followUp): RedirectResponse
    {
        $enquiryId = $followUp->enquiry_id;
        $followUp->delete();

        if ($enquiryId) {
            return redirect()->route('admin.enquiries.show', $enquiryId)->with('success', 'Follow-up deleted successfully.');
        }

        return redirect()->route('admin.enquiries.index')->with('success', 'Follow-up deleted successfully.');
    }
}
