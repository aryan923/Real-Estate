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
            return $this->adminDashboard($user);
        }
    }

    private function adminDashboard($user): View
    {
        // 1. KPI Cards data
        $totalLeads = Enquiry::count();
        $todayFollowups = FollowUp::whereDate('scheduled_at', Carbon::today())->count();
        $todayVisits = SiteVisit::whereDate('scheduled_at', Carbon::today())->count();
        $totalBookings = Booking::count();
        $revenue = Payment::where('status', 'paid')->sum('amount_paid');
        
        $pendingPayments = Payment::whereIn('status', ['pending', 'overdue'])->sum('amount_due') 
            - Payment::whereIn('status', ['pending', 'overdue'])->sum('amount_paid');

        // 2. Charts / Analytics summaries
        // Monthly Sales (last 6 months)
        $monthlySales = Booking::selectRaw('DATE_FORMAT(booking_date, "%b %Y") as month, sum(total_agreement_value) as total')
            ->groupBy('month')
            ->orderByRaw('min(booking_date) asc')
            ->limit(6)
            ->get();

        // Leads by source (Website, Facebook, etc.) - mock distributions or read from settings
        $leadsBySource = [
            ['source' => 'Website', 'count' => Enquiry::where('message', 'like', '%website%')->count() ?: 12],
            ['source' => 'Facebook Ads', 'count' => 25],
            ['source' => 'Google search', 'count' => 18],
            ['source' => 'Referrals', 'count' => 8]
        ];

        // Today's tasks (Followups)
        $tasks = FollowUp::with('enquiry')
            ->whereDate('scheduled_at', Carbon::today())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Recent activity feed
        $activities = collect([
            ['icon' => 'user-plus', 'color' => 'blue', 'title' => 'New Lead Added', 'time' => '10 mins ago', 'desc' => 'Lead Ananya Sen was registered.'],
            ['icon' => 'check-circle', 'color' => 'emerald', 'title' => 'Booking Confirmed', 'time' => '2 hrs ago', 'desc' => 'Unit 101 Booking confirmed for Rahul Sharma.'],
            ['icon' => 'currency-dollar', 'color' => 'green', 'title' => 'Payment Received', 'time' => '4 hrs ago', 'desc' => 'Token payment received for Unit 101.'],
            ['icon' => 'calendar', 'color' => 'amber', 'title' => 'Site Visit Scheduled', 'time' => '1 day ago', 'desc' => 'Site tour scheduled for green heights.']
        ]);

        return view('admin.pages.dashboard', compact(
            'totalLeads', 'todayFollowups', 'todayVisits', 'totalBookings', 'revenue', 'pendingPayments',
            'monthlySales', 'leadsBySource', 'tasks', 'activities', 'user'
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
