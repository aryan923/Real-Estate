<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
use App\Models\Enquiry;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SiteVisitController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.site-visits.index');
    }

    public function data(Request $request)
    {
        $query = SiteVisit::query()->with(['enquiry', 'project', 'assignedExecutive']);

        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        return DataTables::eloquent($query)
            ->addColumn('enquiry_name', function (SiteVisit $siteVisit): string {
                if (!$siteVisit->enquiry) return 'N/A';
                return '<a href="' . route('admin.enquiries.show', $siteVisit->enquiry_id) . '" class="text-brand-600 hover:text-brand-700 dark:text-brand-400 font-medium">' . e($siteVisit->enquiry->name) . '</a>';
            })
            ->addColumn('project_name', fn(SiteVisit $siteVisit): string => $siteVisit->project?->name ?: 'N/A')
            ->addColumn('executive_name', fn(SiteVisit $siteVisit): string => $siteVisit->assignedExecutive?->name ?: 'N/A')
            ->addColumn('scheduled_time', fn(SiteVisit $siteVisit): string => $siteVisit->scheduled_at ? $siteVisit->scheduled_at->format('d M Y, h:i A') : 'N/A')
            ->addColumn('action', function (SiteVisit $siteVisit): string {
                $actions = '<div class="flex flex-wrap gap-2">';
                
                if ($siteVisit->status === 'scheduled') {
                    $actions .= '<button type="button" @click="openFeedbackModal(' . $siteVisit->id . ')" class="inline-flex items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 px-2 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:border-emerald-600/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20" title="Log Outcome">'
                        . 'Log Feedback'
                        . '</button>';
                }

                $actions .= '<a href="' . route('admin.site-visits.edit', $siteVisit) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.site-visits.destroy', $siteVisit) . '" class="inline-block" data-confirm="Delete this site visit event?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
                return $actions;
            })
            ->editColumn('status', function (SiteVisit $siteVisit): string {
                $status = $siteVisit->status;
                $label = ucfirst($status);

                switch ($status) {
                    case 'visited':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">' . $label . '</span>';
                    case 'cancelled':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400">' . $label . '</span>';
                    case 'rescheduled':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400">' . $label . '</span>';
                    default: // 'scheduled'
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">' . $label . '</span>';
                }
            })
            ->rawColumns(['enquiry_name', 'action', 'status'])
            ->toJson();
    }

    public function create(Request $request): View
    {
        $selectedEnquiryId = $request->input('enquiry_id');
        
        return view('admin.pages.site-visits.form', [
            'mode' => 'create',
            'siteVisit' => null,
            'enquiries' => Enquiry::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'executives' => User::orderBy('name')->get(),
            'selectedEnquiryId' => $selectedEnquiryId
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enquiry_id' => 'required|exists:enquiries,id',
            'project_id' => 'required|exists:projects,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
            'pickup_required' => 'required|boolean',
            'pickup_address' => 'nullable|required_if:pickup_required,1|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_number' => 'nullable|string|max:255',
            'assigned_executive_id' => 'required|exists:users,id',
            'status' => 'required|in:scheduled,visited,not_visited,rescheduled,cancelled',
            'feedback' => 'nullable|string',
        ]);

        SiteVisit::create($validated);

        return redirect()->route('admin.site-visits.index')->with('success', 'Site visit scheduled successfully.');
    }

    public function edit(SiteVisit $siteVisit): View
    {
        return view('admin.pages.site-visits.form', [
            'mode' => 'edit',
            'siteVisit' => $siteVisit,
            'enquiries' => Enquiry::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'executives' => User::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, SiteVisit $siteVisit): RedirectResponse
    {
        $validated = $request->validate([
            'enquiry_id' => 'required|exists:enquiries,id',
            'project_id' => 'required|exists:projects,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
            'pickup_required' => 'required|boolean',
            'pickup_address' => 'nullable|required_if:pickup_required,1|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_number' => 'nullable|string|max:255',
            'assigned_executive_id' => 'required|exists:users,id',
            'status' => 'required|in:scheduled,visited,not_visited,rescheduled,cancelled',
            'feedback' => 'nullable|string',
        ]);

        $siteVisit->update($validated);

        return redirect()->route('admin.site-visits.index')->with('success', 'Site visit updated successfully.');
    }

    public function logFeedback(Request $request, SiteVisit $siteVisit): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:visited,not_visited,cancelled',
            'feedback' => 'required|string',
        ]);

        $siteVisit->update([
            'status' => $validated['status'],
            'feedback' => $validated['feedback'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Site visit feedback logged successfully.'
        ]);
    }

    public function destroy(SiteVisit $siteVisit): RedirectResponse
    {
        $siteVisit->delete();
        return redirect()->route('admin.site-visits.index')->with('success', 'Site visit event deleted successfully.');
    }
}
