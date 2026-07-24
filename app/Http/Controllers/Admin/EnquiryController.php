<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class EnquiryController extends Controller
{
    public function index(): View
    {
        $baseQuery = Enquiry::query();
        if (!auth()->user()->can('view_all_leads')) {
            $baseQuery->where('agent_id', auth()->id());
        }

        $totalCount = (clone $baseQuery)->count();
        $newCount = (clone $baseQuery)->where('status', 'new')->count();
        $qualifiedCount = (clone $baseQuery)->whereIn('status', ['interested', 'follow_up', 'site_visit', 'negotiation'])->count();
        $wonCount = (clone $baseQuery)->where('status', 'won')->count();
        $conversionRate = $totalCount > 0 ? round(($wonCount / $totalCount) * 100, 2) : 0;

        $stats = [
            'total' => $totalCount,
            'new' => $newCount,
            'qualified' => $qualifiedCount,
            'converted' => $wonCount,
            'conversion_rate' => $conversionRate,
        ];

        $statusCounts = [
            'all' => $totalCount,
            'new' => $newCount,
            'contacted' => (clone $baseQuery)->where('status', 'contacted')->count(),
            'interested' => (clone $baseQuery)->where('status', 'interested')->count(),
            'qualified' => $qualifiedCount,
            'site_visit' => (clone $baseQuery)->where('status', 'site_visit')->count(),
            'negotiation' => (clone $baseQuery)->where('status', 'negotiation')->count(),
            'won' => $wonCount,
              'lost' => (clone $baseQuery)->where('status', 'lost')->count(),
        ];

        $agents = User::where('status', 'active')->orderBy('name')->get();
        
        $sources = Enquiry::whereNotNull('lead_source')
            ->distinct()
            ->pluck('lead_source')
            ->toArray();
        if (empty($sources)) {
            $sources = ['Website', 'Google Ads', 'Referral', 'Facebook Ad', 'Justdial', 'Walk-in', 'Contact Form'];
        }

        $locations = Enquiry::whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->values()
            ->toArray();
        if (empty($locations)) {
            $locations = ['Gurgaon', 'Noida', 'Delhi', 'Greater Noida', 'Mumbai', 'Bangalore'];
        }

        return view('admin.pages.enquiries.index', compact('stats', 'statusCounts', 'agents', 'sources', 'locations'));
    }

    public function data(Request $request)
    {
        $query = Enquiry::with(['property', 'agent', 'actionBy']);

        if (auth()->check() && !auth()->user()->can('view_all_leads')) {
            $query->where('agent_id', auth()->id());
        }

        if (auth()->check() && !auth()->user()->can('view_won')) {
            $query->where('status', '!=', 'won');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('lead_source')) {
            $query->where('lead_source', $request->lead_source);
        }

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('search_query')) {
            $search = $request->search_query;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        return DataTables::eloquent($query)
            ->addColumn('property_title', function (Enquiry $enquiry) {
                return $enquiry->property?->title ?: ($enquiry->type === 'tour' ? 'Schedule Tour' : ($enquiry->type === 'info' ? 'Request Info' : 'Property Lead'));
            })
            ->addColumn('agent_name', function (Enquiry $enquiry) {
                return $enquiry->agent?->name ?: 'Unassigned';
            })
            ->addColumn('agent_initials', function (Enquiry $enquiry) {
                if (!$enquiry->agent) return 'U';
                $words = explode(' ', $enquiry->agent->name);
                return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            })
            ->addColumn('lead_initials', function (Enquiry $enquiry) {
                $words = explode(' ', $enquiry->name);
                return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            })
            ->addColumn('relative_time', function (Enquiry $enquiry) {
                $time = $enquiry->updated_at ?: $enquiry->created_at;
                return $time->diffForHumans();
            })
            ->addColumn('show_url', function (Enquiry $enquiry) {
                return route('admin.enquiries.show', $enquiry);
            })
            ->addColumn('edit_url', function (Enquiry $enquiry) {
                return route('admin.enquiries.edit', $enquiry);
            })
            ->addColumn('destroy_url', function (Enquiry $enquiry) {
                return route('admin.enquiries.destroy', $enquiry);
            })
            ->addColumn('csrf_token', function () {
                return csrf_token();
            })
            ->toJson();
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $name = trim($row[0] ?? '');
            $email = trim($row[1] ?? '');
            $phone = trim($row[2] ?? '');
            $message = trim($row[3] ?? '');
            $source = trim($row[4] ?? 'Website');
            $status = trim($row[5] ?? 'new');
            $location = trim($row[6] ?? '');

            if (empty($name) || empty($email)) continue;

            Enquiry::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
                'lead_source' => $source,
                'type' => 'info',
                'status' => in_array($status, ['new','contacted','interested','site_visit','negotiation','won','lost']) ? $status : 'new',
                'location' => $location,
                'agent_id' => Enquiry::getNextRoundRobinAgentId(),
            ]);
            $imported++;
        }
        fclose($handle);

        return response()->json([
            'success' => true,
            'message' => "Successfully imported {$imported} leads.",
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:enquiries,id',
            'action' => 'required|in:delete,update_status,assign_agent',
            'value' => 'nullable|string',
        ]);

        $query = Enquiry::whereIn('id', $validated['ids']);

        if (!auth()->user()->can('view_all_leads')) {
            $query->where('agent_id', auth()->id());
        }

        if ($validated['action'] === 'delete') {
            $query->delete();
            $msg = 'Selected leads deleted successfully.';
        } elseif ($validated['action'] === 'update_status') {
            $query->update(['status' => $validated['value']]);
            $msg = 'Status updated for selected leads.';
        } elseif ($validated['action'] === 'assign_agent') {
            $query->update(['agent_id' => $validated['value']]);
            $msg = 'Agent assigned for selected leads.';
        }

        return response()->json([
            'success' => true,
            'message' => $msg ?? 'Bulk action completed.',
        ]);
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
            'followUps.creator',
            'siteVisits.assignedExecutive',
            'siteVisits.project',
            'siteVisits.property',
        ]);

        $agents = User::where('status', 'active')->orderBy('name')->get();

        // Build Unified Activity Timeline
        $timelineEvents = collect();

        // 1. Initial Enquiry Creation Event
        $timelineEvents->push([
            'type' => 'enquiry_created',
            'title' => 'Inquiry Registered',
            'description' => 'Lead inquiry registered via ' . ($enquiry->type === 'contact' ? 'Contact Form' : ($enquiry->type === 'info' ? 'Info Request' : ($enquiry->type === 'tour' ? 'Schedule Tour' : ucfirst($enquiry->type)))),
            'badge' => 'Initial Lead',
            'badge_color' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-400',
            'icon_bg' => 'bg-blue-500',
            'timestamp' => $enquiry->created_at,
            'author' => 'System',
        ]);

        // 2. Follow-up Events
        foreach ($enquiry->followUps as $fu) {
            $timelineEvents->push([
                'type' => 'follow_up',
                'title' => 'Follow-up Call ' . ($fu->status === 'completed' ? '(' . str_replace('_', ' ', ucfirst($fu->outcome ?: 'Completed')) . ')' : '(Scheduled)'),
                'description' => $fu->call_notes ?: ($fu->note ?: 'Follow-up interaction logged.'),
                'badge' => 'Follow-up ' . ucfirst($fu->status),
                'badge_color' => $fu->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400',
                'icon_bg' => $fu->status === 'completed' ? 'bg-emerald-500' : 'bg-amber-500',
                'timestamp' => $fu->scheduled_at ?? $fu->created_at,
                'author' => $fu->creator?->name ?: 'Staff',
            ]);
        }

        // 3. Site Visit Events
        foreach ($enquiry->siteVisits as $sv) {
            $siteTitle = $sv->project?->name ?: ($sv->property?->title ?: ($enquiry->property?->title ?: 'Property'));
            $timelineEvents->push([
                'type' => 'site_visit',
                'title' => 'Site Tour ' . ucfirst($sv->status),
                'description' => $sv->feedback ?: ($sv->notes ?? 'Site visit scheduled for ' . $siteTitle),
                'badge' => 'Site Visit ' . ucfirst($sv->status),
                'badge_color' => $sv->status === 'visited' ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-400' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-400',
                'icon_bg' => 'bg-cyan-500',
                'timestamp' => $sv->scheduled_at ?? $sv->created_at,
                'author' => $sv->assignedExecutive?->name ?: 'Executive',
            ]);
        }

        // 4. Bookings & Payments (matching customer email/phone)
        if ($enquiry->email || $enquiry->phone) {
            $customer = \App\Models\Customer::where('email', $enquiry->email)
                ->orWhere('phone', $enquiry->phone)
                ->first();

            if ($customer) {
                $bookings = \App\Models\Booking::where('customer_id', $customer->id)->with(['unit', 'payments'])->get();
                foreach ($bookings as $b) {
                    $timelineEvents->push([
                        'type' => 'booking',
                        'title' => 'Booking Confirmed - Unit ' . ($b->unit?->unit_number ?? '#' . $b->id),
                        'description' => 'Agreed Price: ₹' . number_format($b->agreed_price) . ' | Status: ' . ucfirst($b->status),
                        'badge' => 'Booking ' . ucfirst($b->status),
                        'badge_color' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-400',
                        'icon_bg' => 'bg-purple-500',
                        'timestamp' => $b->created_at,
                        'author' => 'Accounts / Sales',
                    ]);

                    foreach ($b->payments as $p) {
                        $timelineEvents->push([
                            'type' => 'payment',
                            'title' => 'Payment Receipt Issued - ₹' . number_format($p->amount),
                            'description' => 'Receipt #' . $p->receipt_number . ' paid via ' . strtoupper($p->payment_method) . ' (' . ucfirst($p->status) . ')',
                            'badge' => 'Payment ' . ucfirst($p->status),
                            'badge_color' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400',
                            'icon_bg' => 'bg-emerald-600',
                            'timestamp' => $p->payment_date ? \Carbon\Carbon::parse($p->payment_date) : $p->created_at,
                            'author' => 'Accounts',
                        ]);
                    }
                }
            }
        }

        // Sort chronologically ascending for the horizontal graph
        $chronoEvents = $timelineEvents->sortBy('timestamp')->values();
        $totalCount = $chronoEvents->count();

        if ($totalCount > 0) {
            $chronoEvents->transform(function ($item, $key) use ($totalCount) {
                if ($totalCount === 1) {
                    $item['percent'] = 50;
                } else {
                    $item['percent'] = round(10 + ($key * (80 / ($totalCount - 1))), 2);
                }
                return $item;
            });
        }

        // Collect All Historical Notes
        $allNotes = collect();

        if ($enquiry->note) {
            $allNotes->push([
                'source' => 'Lead Status Note',
                'source_badge' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-400',
                'note' => $enquiry->note,
                'author' => $enquiry->actionBy?->name ?: 'Staff',
                'timestamp' => $enquiry->performed_at ?: $enquiry->updated_at,
            ]);
        }

        if ($enquiry->message) {
            $allNotes->push([
                'source' => 'Initial Inquiry Message',
                'source_badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-400',
                'note' => $enquiry->message,
                'author' => $enquiry->name,
                'timestamp' => $enquiry->created_at,
            ]);
        }

        foreach ($enquiry->followUps as $fu) {
            if (!empty($fu->call_notes) || !empty($fu->note)) {
                $allNotes->push([
                    'source' => 'Follow-up Call (' . str_replace('_', ' ', ucfirst($fu->status)) . ')',
                    'source_badge' => $fu->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400',
                    'note' => $fu->call_notes ?: $fu->note,
                    'author' => $fu->creator?->name ?: 'Staff',
                    'timestamp' => $fu->updated_at ?: ($fu->scheduled_at ?: $fu->created_at),
                ]);
            }
        }

        $allNotes = $allNotes->sortByDesc('timestamp')->values();

        $activityTimeline = $timelineEvents->sortByDesc('timestamp')->values();
        $graphTimeline = $chronoEvents;

        return view('admin.pages.enquiries.show', compact('enquiry', 'agents', 'activityTimeline', 'graphTimeline', 'allNotes'));
    }

    public function edit(Enquiry $enquiry): View
    {
        $this->authorizeEnquiry($enquiry);

        $agents = \App\Models\User::has('agentMeta')->where('status', 'active')->orderBy('name', 'asc')->get();
        return view('admin.pages.enquiries.edit', compact('enquiry', 'agents'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $this->authorizeEnquiry($enquiry);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'lead_source' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'budget' => 'nullable|string|max:100',
            'message' => 'nullable|string',
            'note' => 'nullable|string',
            'agent_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:draft,new,contacted,interested,follow_up,site_visit,negotiation,won,lost',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'draft') {
            $validated['agent_id'] = null;
        }

        $validated['action_by'] = auth()->id();
        $validated['performed_at'] = now();

        $enquiry->update(array_filter($validated, function ($val) { return !is_null($val); }));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead updated successfully.',
                'data' => $enquiry,
            ]);
        }

        return redirect()
            ->route('admin.enquiries.show', $enquiry)
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
            'type' => 'nullable|in:contact,info,tour,agent',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'lead_source' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'budget' => 'nullable|string|max:100',
            'property_id' => 'nullable|exists:properties,id',
            'agent_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:draft,new,contacted,interested,follow_up,site_visit,negotiation,won,lost',
        ]);

        $validated['type'] = $validated['type'] ?? 'info';
        $validated['status'] = $validated['status'] ?? 'new';
        $validated['lead_source'] = $validated['lead_source'] ?? 'Contact Form';

        if (empty($validated['agent_id'])) {
            $validated['agent_id'] = Enquiry::getNextRoundRobinAgentId();
        }

        $enquiry = Enquiry::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully.',
                'data' => $enquiry,
            ]);
        }

        return redirect()->route('admin.enquiries.show', $enquiry)->with('success', 'Lead created successfully.');
    }
}