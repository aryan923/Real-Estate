<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index(): View
    {
        // Compute high-level dashboard metrics
        $totalReceivables = Payment::sum('amount_due');
        $totalReceived = Payment::where('status', 'paid')->sum('amount_paid');
        
        // Pending = total due of pending/overdue items
        $totalPending = Payment::whereIn('status', ['pending', 'overdue'])->sum('amount_due') 
            - Payment::whereIn('status', ['pending', 'overdue'])->sum('amount_paid');

        return view('admin.pages.payments.index', compact(
            'totalReceivables', 'totalReceived', 'totalPending'
        ));
    }

    public function data(Request $request)
    {
        $query = Payment::query()->with(['booking.customer', 'booking.property.project']);

        return DataTables::eloquent($query)
            ->addColumn('receipt_number', fn(Payment $payment): string => $payment->receipt_no ?: 'N/A')
            ->addColumn('booking_number', fn(Payment $payment): string => $payment->booking?->booking_no ?: 'N/A')
            ->addColumn('customer_name', fn(Payment $payment): string => $payment->booking?->customer?->name ?: 'N/A')
            ->addColumn('project_name', fn(Payment $payment): string => $payment->booking?->project?->name ?: 'N/A')
            ->addColumn('stage_label', fn(Payment $payment): string => str_replace('_', ' ', ucfirst($payment->installment_stage)))
            ->addColumn('due', fn(Payment $payment): string => '₹' . number_format($payment->amount_due, 2))
            ->addColumn('paid', fn(Payment $payment): string => '₹' . number_format($payment->amount_paid, 2))
            ->addColumn('due_date_formatted', fn(Payment $payment): string => $payment->due_date ? $payment->due_date->format('d M Y') : 'N/A')
            ->addColumn('action', function (Payment $payment): string {
                $actions = '<div class="flex flex-wrap gap-2">';
                
                if ($payment->status === 'paid' && $payment->receipt_no) {
                    $actions .= '<a href="' . route('admin.payments.receipt', $payment) . '" class="inline-flex items-center justify-center rounded-lg border border-brand-200 bg-brand-50 px-2 py-1.5 text-xs font-semibold text-brand-700 hover:bg-brand-100 dark:border-brand-600/30 dark:bg-brand-500/10 dark:text-brand-400" title="Download Receipt PDF">'
                        . 'Receipt PDF'
                        . '</a>';
                }

                $actions .= '<a href="' . route('admin.payments.edit', $payment) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.payments.destroy', $payment) . '" class="inline-block" data-confirm="Delete this installment payment record?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
                return $actions;
            })
            ->editColumn('status', function (Payment $payment): string {
                $status = $payment->status;
                $label = ucfirst($status);

                switch ($status) {
                    case 'paid':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">' . $label . '</span>';
                    case 'overdue':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400">' . $label . '</span>';
                    default: // 'pending'
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">' . $label . '</span>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function create(Request $request): View
    {
        $selectedBookingId = $request->input('booking_id');
        
        return view('admin.pages.payments.form', [
            'mode' => 'create',
            'payment' => null,
            'bookings' => Booking::orderBy('booking_no')->get(),
            'selectedBookingId' => $selectedBookingId
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'installment_stage' => 'required|in:token,down_payment,slab_1,slab_2,possession',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:100',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        // Generate receipt number if status is paid
        if ($validated['status'] === 'paid' && $validated['amount_paid'] > 0) {
            $validated['receipt_no'] = 'REC-' . date('Ymd') . '-' . rand(1000, 9999);
        }

        Payment::create($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Payment record added successfully.');
    }

    public function edit(Payment $payment): View
    {
        return view('admin.pages.payments.form', [
            'mode' => 'edit',
            'payment' => $payment,
            'bookings' => Booking::orderBy('booking_no')->get()
        ]);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'installment_stage' => 'required|in:token,down_payment,slab_1,slab_2,possession',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:100',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        // Auto-generate receipt number if switching to paid and not present
        if ($validated['status'] === 'paid' && $validated['amount_paid'] > 0 && !$payment->receipt_no) {
            $validated['receipt_no'] = 'REC-' . date('Ymd') . '-' . rand(1000, 9999);
        }

        $payment->update($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Payment record updated successfully.');
    }

    public function generateMilestones(Booking $booking): RedirectResponse
    {
        if ($booking->payments()->exists()) {
            return redirect()->back()->with('error', 'Milestone installments have already been generated for this booking.');
        }

        $value = $booking->total_agreement_value;
        $date = $booking->booking_date;

        $slabs = [
            ['stage' => 'token', 'pct' => 0.10, 'days' => 0],
            ['stage' => 'down_payment', 'pct' => 0.15, 'days' => 30],
            ['stage' => 'slab_1', 'pct' => 0.25, 'days' => 90],
            ['stage' => 'slab_2', 'pct' => 0.25, 'days' => 180],
            ['stage' => 'possession', 'pct' => 0.25, 'days' => 360],
        ];

        foreach ($slabs as $slab) {
            $dueAmount = $value * $slab['pct'];
            $paidAmount = 0.00;
            $status = 'pending';
            $paidDate = null;
            $receiptNo = null;

            if ($slab['stage'] === 'token') {
                $paidAmount = min($booking->booking_amount, $dueAmount);
                if ($paidAmount >= $dueAmount) {
                    $status = 'paid';
                    $paidDate = $date;
                    $receiptNo = 'REC-' . date('Ymd') . '-' . rand(1000, 9999);
                }
            }

            Payment::create([
                'booking_id' => $booking->id,
                'installment_stage' => $slab['stage'],
                'amount_due' => $dueAmount,
                'amount_paid' => $paidAmount,
                'due_date' => $date->copy()->addDays($slab['days']),
                'paid_date' => $paidDate,
                'status' => $status,
                'receipt_no' => $receiptNo,
                'payment_method' => ($slab['stage'] === 'token' && $paidAmount > 0) ? 'Bank Transfer' : null,
            ]);
        }

        return redirect()->back()->with('success', 'Milestone installment schedule generated successfully.');
    }

    public function generateReceipt(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.property.project']);
        
        $pdf = Pdf::loadView('admin.pages.payments.receipt_pdf', compact('payment'));
        
        return $pdf->download('Receipt-' . $payment->receipt_no . '.pdf');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment record deleted successfully.');
    }
}
