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
            ->addColumn('status_label', function (Enquiry $enquiry) {
                return ucfirst(str_replace('_', ' ', $enquiry->status));
            })
            ->addColumn('action', function (Enquiry $enquiry) {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.enquiries.show', $enquiry) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700">View</a>'
                    . '<a href="' . route('admin.enquiries.edit', $enquiry) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700">Edit</a>'
                    . '<form method="POST" action="' . route('admin.enquiries.destroy', $enquiry) . '" class="inline-block" data-confirm="Delete this enquiry?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action'])
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