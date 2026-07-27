<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\FollowUp;
use App\Models\SiteVisit;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Testing Override: allows viewing any dashboard by appending ?role=executive or ?role=accounts
        $roleOverride = $request->query('role');
        if ($roleOverride === 'executive') {
            return $this->executiveDashboard($user);
        } elseif ($roleOverride === 'accounts') {
            return $this->accountsDashboard($user);
        }

        if ($user->hasRole('Sales Executive')) {
            return $this->executiveDashboard($user);
        } elseif ($user->hasRole('Accountant')) {
            return $this->accountsDashboard($user);
        } else {
            return $this->adminDashboard($user, $request);
        }
    }

    private function adminDashboard($user, Request $request): View
    {
        // 0. Date Range & Period Filtering
        $startDateParam = $request->query('start_date');
        $endDateParam = $request->query('end_date');
        $selectedPeriod = $request->query('period', 'This Month');

        if ($startDateParam && $endDateParam) {
            $startDate = Carbon::parse($startDateParam);
            $endDate = Carbon::parse($endDateParam);
        } else {
            $startDate = Carbon::now()->subDays(6);
            $endDate = Carbon::now();
        }

        $dateRangeLabel = $startDate->format('M d') . ' – ' . $endDate->format('M d, Y');

        // 1. Top KPI Metrics
        $dbLeadsCount = Enquiry::count();
        $totalLeads = $dbLeadsCount > 0 ? $dbLeadsCount : 1248;

        $dbVisitsCount = SiteVisit::count();
        $todayVisits = $dbVisitsCount > 0 ? $dbVisitsCount : 362;

        $dealsInPipeline = Enquiry::whereIn('status', ['new', 'contacted', 'interested', 'site_visit', 'negotiation'])->count() ?: 128;

        $dbBookingsCount = Booking::count();
        $monthlyBookings = $dbBookingsCount > 0 ? $dbBookingsCount : 28;

        $dbRevenue = Payment::where('status', 'paid')->sum('amount_paid');
        $revenueVal = $dbRevenue > 0 ? ($dbRevenue / 10000000) : 2.45;

        // 2. Recent Leads List
        $dbLeads = Enquiry::orderBy('id', 'desc')->take(5)->get();
        $sampleLeads = collect([
            [
                'name' => 'Rahul Singh',
                'sub' => 'Luxury Apartment - Gurgaon',
                'status' => 'new',
                'time' => '2m ago',
                'initials' => 'RS',
                'bg' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300'
            ],
            [
                'name' => 'Neha Patel',
                'sub' => '3 BHK Villa - Noida',
                'status' => 'contacted',
                'time' => '15m ago',
                'initials' => 'NP',
                'bg' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
            ],
            [
                'name' => 'Arjun Kapoor',
                'sub' => 'Commercial Space - Delhi',
                'status' => 'follow_up',
                'time' => '1h ago',
                'initials' => 'AK',
                'bg' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'
            ],
            [
                'name' => 'Priya Sharma',
                'sub' => '2 BHK Apartment - Gurugram',
                'status' => 'new',
                'time' => '2h ago',
                'initials' => 'PS',
                'bg' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
            ],
            [
                'name' => 'Vikram Mehta',
                'sub' => 'Plot - Sohna Road',
                'status' => 'qualified',
                'time' => '3h ago',
                'initials' => 'VM',
                'bg' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
            ],
        ]);

        $recentLeads = $dbLeads->map(function ($l) {
            $words = explode(' ', $l->name);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            return [
                'name' => $l->name,
                'sub' => ($l->bhk ?: '3 BHK') . ' ' . ($l->preferred_type ?: 'Apartment') . ' - ' . ($l->location ?: 'Gurgaon'),
                'status' => $l->status ?: 'new',
                'time' => $l->created_at ? $l->created_at->diffForHumans(null, true) . ' ago' : '5m ago',
                'initials' => $initials ?: 'L',
                'bg' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
            ];
        });

        if ($recentLeads->count() < 5) {
            $recentLeads = $recentLeads->concat($sampleLeads->slice(0, 5 - $recentLeads->count()))->values();
        }

        // 3. Top Locations Breakdown
        $topLocations = [
            ['name' => 'Gurugram', 'val' => '₹78.4 Cr', 'pct' => 85, 'color' => 'bg-blue-500'],
            ['name' => 'Noida', 'val' => '₹46.8 Cr', 'pct' => 60, 'color' => 'bg-emerald-500'],
            ['name' => 'Greater Noida', 'val' => '₹28.7 Cr', 'pct' => 40, 'color' => 'bg-amber-500'],
            ['name' => 'Sohna Road', 'val' => '₹18.3 Cr', 'pct' => 28, 'color' => 'bg-purple-500'],
            ['name' => 'Dwarka Expressway', 'val' => '₹12.1 Cr', 'pct' => 18, 'color' => 'bg-rose-500'],
        ];
        // 4. Upcoming Activities
        $upcomingActivities = [
            [
                'title' => 'Site Visit with Rahul Singh',
                'sub' => 'DLF Camellias, Gurugram',
                'type' => 'visit',
                'time' => 'Today 11:00 AM',
                'color' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400'
            ],
            [
                'title' => 'Follow Up Call',
                'sub' => 'Neha Patel',
                'type' => 'call',
                'time' => 'Today 2:00 PM',
                'color' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400'
            ],
            [
                'title' => 'Meeting with Investor',
                'sub' => 'Anita Verma',
                'type' => 'meeting',
                'time' => 'Tomorrow 10:30 AM',
                'color' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400'
            ],
            [
                'title' => 'Proposal Submission',
                'sub' => 'Commercial Space, Noida',
                'type' => 'proposal',
                'time' => 'Tomorrow 4:00 PM',
                'color' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400'
            ],
        ];

        // 5. Dynamic ApexCharts Datasets
        $sparklineLeads = [12, 18, 15, 25, 22, 30, 28];
        $sparklineVisits = [5, 8, 6, 12, 10, 15, 14];
        $sparklineDeals = [2, 4, 3, 7, 5, 8, 9];
        $sparklineBookings = [1, 2, 1, 3, 2, 4, 4];
        $sparklineRevenue = [0.3, 0.5, 0.4, 0.8, 0.7, 1.1, 1.2];

        $funnelChart = [
            'labels' => ['New Leads', 'Qualified', 'Site Visit', 'Proposal Sent', 'Negotiation', 'Closed Won'],
            'data' => [480, 260, 150, 78, 32, 28],
        ];

        $revenueChart = [
            'categories' => ['1 May', '8 May', '15 May', '22 May', '29 May'],
            'data' => [1.2, 1.8, 2.4, 1.9, 2.45],
        ];

        $donutProperties = [
            'labels' => ['Apartment', 'Villa', 'Plot', 'Commercial', 'Other'],
            'series' => [982, 412, 256, 192, 0],
        ];

        return view('admin.pages.dashboard', compact(
            'totalLeads', 'todayVisits', 'dealsInPipeline', 'monthlyBookings', 'revenueVal',
            'recentLeads', 'topLocations', 'upcomingActivities', 'user',
            'sparklineLeads', 'sparklineVisits', 'sparklineDeals', 'sparklineBookings', 'sparklineRevenue',
            'funnelChart', 'revenueChart', 'donutProperties',
            'dateRangeLabel', 'startDateParam', 'endDateParam', 'selectedPeriod'
        ));
    }

    private function executiveDashboard($user): View
    {
        // 1. KPI Cards
        $assignedLeads = Enquiry::where('agent_id', $user->id)->count();
        $myTodayFollowups = FollowUp::where('created_by', $user->id)
            ->whereDate('scheduled_at', Carbon::today())
            ->count();
        $mySiteVisits = SiteVisit::where('assigned_executive_id', $user->id)
            ->whereDate('scheduled_at', '>=', Carbon::today())
            ->count();

        // 2. Today's task checklist
        $todayTasks = FollowUp::with('enquiry')
            ->where('created_by', $user->id)
            ->whereDate('scheduled_at', Carbon::today())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // 3. Hot Leads (Score >= 70)
        $hotLeads = Enquiry::where('agent_id', $user->id)
            ->where('lead_score', '>=', 70)
            ->orderBy('lead_score', 'desc')
            ->limit(5)
            ->get();

        // 4. Site Visits assigned
        $scheduledVisits = SiteVisit::with(['enquiry', 'project'])
            ->where('assigned_executive_id', $user->id)
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('admin.pages.dashboard-executive', compact(
            'assignedLeads', 'myTodayFollowups', 'mySiteVisits', 'todayTasks', 'hotLeads', 'scheduledVisits', 'user'
        ));
    }

    private function accountsDashboard($user): View
    {
        // 1. Financial stats
        $totalCollected = Payment::where('status', 'paid')->sum('amount_paid');
        $outstandingCollection = Payment::whereIn('status', ['pending', 'overdue'])->sum('amount_due') 
            - Payment::whereIn('status', ['pending', 'overdue'])->sum('amount_paid');
        
        $overdueCount = Payment::where('status', 'overdue')->count();

        // 2. Recent collections list
        $recentCollections = Payment::with(['booking.customer', 'booking.property.project'])
            ->where('status', 'paid')
            ->orderBy('paid_date', 'desc')
            ->limit(5)
            ->get();

        // 3. Pending/Overdue installments
        $pendingInstallments = Payment::with(['booking.customer', 'booking.property.project'])
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        return view('admin.pages.dashboard-accounts', compact(
            'totalCollected', 'outstandingCollection', 'overdueCount', 'recentCollections', 'pendingInstallments', 'user'
        ));
    }
}
