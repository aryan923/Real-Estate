<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class EnquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.enquiries.index');
    }

    public function data(Request $request)
    {
        $query = Enquiry::with(['property', 'actionBy']);

        if (!auth()->user()->can('view_all_leads')) {
            $query->where('agent_id', auth()->id());
        }

        if (!auth()->user()->can('view_won')) {
            $query->where('status', '!=', 'won');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)
            ->addColumn('property', function (Enquiry $enquiry) {
                return $enquiry->property?->title ?? '-';
            })
            ->addColumn('status', function (Enquiry $enquiry) {
                $status = $enquiry->status;
                $label = ucfirst(str_replace('_', ' ', $status));

                switch ($status) {
                    case 'won':
                        $color = '#10b981'; // green
                        break;
                    case 'lost':
                        $color = '#ef4444'; // red
                        break;
                    case 'new':
                        $color = '#3b82f6'; // blue
                        break;
                    case 'contacted':
                        $color = '#f59e0b'; // amber
                        break;
                    case 'interested':
                        $color = '#8b5cf6'; // purple
                        break;
                    case 'follow_up':
                        $color = '#6366f1'; // indigo
                        break;
                    case 'site_visit':
                        $color = '#06b6d4'; // cyan
                        break;
                    case 'negotiation':
                        $color = '#f97316'; // orange
                        break;
                    default: // 'draft'
                        $color = '#6b7280'; // gray
                        break;
                }

                return '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: ' . $color . '; color: #ffffff;">' . $label . '</span>';
            })
            ->addColumn('action', function (Enquiry $enquiry) {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.enquiries.show', $enquiry) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="View">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>'
                    . '</a>'
                    . '<a href="' . route('admin.enquiries.edit', $enquiry) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.enquiries.destroy', $enquiry) . '" class="inline-block" data-confirm="Delete this enquiry?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    private function authorizeEnquiry(Enquiry $enquiry)
    {
        if (!auth()->user()->can('view_all_leads') && $enquiry->agent_id !== auth()->id()) {
            abort(403, 'This action is unauthorized.');
        }

        if (!auth()->user()->can('view_won') && $enquiry->status === 'won') {
            abort(403, 'This action is unauthorized.');
        }
    }

    public function show(Enquiry $enquiry): View
    {
        $this->authorizeEnquiry($enquiry);

        $enquiry->load([
            'property',
            'actionBy',
            'agent',
        ]);

        return view('admin.pages.enquiries.show', compact('enquiry'));
    }

    public function edit(Enquiry $enquiry): View
    {
        $this->authorizeEnquiry($enquiry);

        $agents = \App\Models\User::has('agentMeta')->where('status', 'active')->orderBy('name', 'asc')->get();
        return view('admin.pages.enquiries.edit', compact('enquiry', 'agents'));
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $this->authorizeEnquiry($enquiry);

        $validated = $this->validateEnquiry($request);

        if ($validated['status'] === 'draft') {
            $validated['agent_id'] = null;
        }

        $validated['action_by'] = auth()->id();
        $validated['performed_at'] = now();

        $enquiry->update($validated);

        return redirect()
            ->route('admin.enquiries.index')
            ->with('status', 'Enquiry updated successfully.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $this->authorizeEnquiry($enquiry);

        $enquiry->delete();

        return redirect()
            ->route('admin.enquiries.index')
            ->with('status', 'Enquiry deleted successfully.');
    }

    private function validateEnquiry(Request $request): array
    {
        return $request->validate([
            'status' => [
                'required',
                'in:draft,new,contacted,interested,follow_up,site_visit,negotiation,won,lost'
            ],
            'note' => [
                'nullable',
                'string'
            ],
            'agent_id' => [
                'nullable',
                \Illuminate\Validation\Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
                    ->where('status', 'active')
                    ->whereIn('id', \App\Models\User::has('agentMeta')->pluck('id')->toArray()),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:contact,info,tour,agent',
            'enq_for' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'property_id' => 'nullable|exists:properties,id',
            'tour_schedule_time' => 'nullable|date',
            'tour_type' => 'nullable|in:in_person,video_chat',
            'agent_id' => [
                'nullable',
                \Illuminate\Validation\Rule::exists('users', 'id')
                    ->whereNull('deleted_at')
                    ->where('status', 'active')
                    ->whereIn('id', \App\Models\User::has('agentMeta')->pluck('id')->toArray()),
            ],
            'status' => 'nullable|in:draft,new,contacted,interested,follow_up,site_visit,negotiation,won,lost',
        ]);

        $status = $request->input('status', 'new');
        $validated['status'] = $status;

        if ($status === 'draft') {
            $validated['agent_id'] = null;
        } else {
            if ($request->filled('agent_id')) {
                $validated['agent_id'] = $request->input('agent_id');
            } else {
                $validated['agent_id'] = Enquiry::getNextRoundRobinAgentId();
            }
        }

        $enquiry = Enquiry::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Enquiry submitted successfully.',
            'data' => $enquiry,
        ]);
    }
}