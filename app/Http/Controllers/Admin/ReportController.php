<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\SiteVisit;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        
        $selectedProjectId = $request->input('project_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Lead Query
        $leadQuery = Enquiry::query();
        // Sales/Booking Query
        $bookingQuery = Booking::query();
        // Payment Query
        $paymentQuery = Payment::query();
        // Property Query
        $propertyQuery = Property::query();
        // Site Visit Query
        $visitQuery = SiteVisit::query();

        // Apply filters
        if ($selectedProjectId) {
            $bookingQuery->where('project_id', $selectedProjectId);
            $propertyQuery->where('project_id', $selectedProjectId);
            $visitQuery->where('project_id', $selectedProjectId);
            
            // For payments, filter by associated booking's project
            $paymentQuery->whereHas('booking', function($q) use ($selectedProjectId) {
                $q->where('project_id', $selectedProjectId);
            });
        }

        if ($startDate) {
            $leadQuery->whereDate('created_at', '>=', $startDate);
            $bookingQuery->whereDate('booking_date', '>=', $startDate);
            $visitQuery->whereDate('scheduled_at', '>=', $startDate);
            $paymentQuery->whereDate('due_date', '>=', $startDate);
        }

        if ($endDate) {
            $leadQuery->whereDate('created_at', '<=', $endDate);
            $bookingQuery->whereDate('booking_date', '<=', $endDate);
            $visitQuery->whereDate('scheduled_at', '<=', $endDate);
            $paymentQuery->whereDate('due_date', '<=', $endDate);
        }

        // 1. Lead Report Metrics
        $totalLeads = $leadQuery->count();
        $leadsByStatus = $leadQuery->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // 2. Sales Report Metrics
        $totalSalesCount = $bookingQuery->count();
        $totalSalesValue = $bookingQuery->sum('total_agreement_value');
        $avgSalesValue = $bookingQuery->avg('total_agreement_value') ?: 0.00;

        // 3. Revenue Report Metrics
        $totalRevenueCollected = $paymentQuery->where('status', 'paid')->sum('amount_paid');
        $totalRevenuePending = $paymentQuery->whereIn('status', ['pending', 'overdue'])->sum('amount_due') 
            - $paymentQuery->whereIn('status', ['pending', 'overdue'])->sum('amount_paid');

        // 4. Inventory Report Metrics
        $totalInventoryCount = $propertyQuery->count();
        $inventoryByStatus = $propertyQuery->selectRaw('inventory_status, count(*) as count')
            ->groupBy('inventory_status')
            ->pluck('count', 'inventory_status');

        // 5. Site Visits Report Metrics
        $totalVisits = $visitQuery->count();
        $visitsByStatus = $visitQuery->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // 6. Conversion rates
        // Conversion = Site visit rate (site visits / leads), Booking conversion rate (bookings / leads)
        $siteVisitConversionRate = $totalLeads > 0 ? ($totalVisits / $totalLeads) * 100 : 0.0;
        $bookingConversionRate = $totalLeads > 0 ? ($totalSalesCount / $totalLeads) * 100 : 0.0;

        return view('admin.pages.reports.index', compact(
            'projects', 'selectedProjectId', 'startDate', 'endDate',
            'totalLeads', 'leadsByStatus',
            'totalSalesCount', 'totalSalesValue', 'avgSalesValue',
            'totalRevenueCollected', 'totalRevenuePending',
            'totalInventoryCount', 'inventoryByStatus',
            'totalVisits', 'visitsByStatus',
            'siteVisitConversionRate', 'bookingConversionRate'
        ));
    }

    public function export(Request $request, string $report)
    {
        // Placeholder for file downloads, outputs print sheet directly
        return response()->json([
            'success' => true,
            'message' => "Exporting the requested {$report} report format in Excel/PDF is triggered. Ready for export downloads."
        ]);
    }
}
