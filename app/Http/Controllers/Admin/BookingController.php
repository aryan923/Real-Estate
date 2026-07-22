<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Property;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.bookings.index');
    }

    public function data(Request $request)
    {
        $query = Booking::query()->with(['customer', 'property', 'project']);

        return DataTables::eloquent($query)
            ->addColumn('booking_number', fn(Booking $booking): string => $booking->booking_no)
            ->addColumn('customer_name', function (Booking $booking): string {
                if (!$booking->customer) return 'N/A';
                return '<a href="' . route('admin.customers.show', $booking->customer_id) . '" class="text-brand-600 hover:text-brand-700 dark:text-brand-400 font-medium">' . e($booking->customer->name) . '</a>';
            })
            ->addColumn('project_name', fn(Booking $booking): string => $booking->project?->name ?: 'N/A')
            ->addColumn('unit_number', fn(Booking $booking): string => $booking->property?->unit_number ?: 'N/A')
            ->addColumn('amount', fn(Booking $booking): string => '₹' . number_format($booking->booking_amount, 2))
            ->addColumn('date', fn(Booking $booking): string => $booking->booking_date ? $booking->booking_date->format('d M Y') : 'N/A')
            ->addColumn('action', function (Booking $booking): string {
                $actions = '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.bookings.show', $booking) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="View Details">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>'
                    . '</a>';

                if ($booking->status === 'pending_approval') {
                    $actions .= '<a href="' . route('admin.bookings.edit', $booking) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                        . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                        . '</a>'
                        . '<form method="POST" action="' . route('admin.bookings.cancel', $booking) . '" class="inline-block" data-confirm="Cancel this booking?">'
                        . csrf_field()
                        . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Cancel Booking">'
                        . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                        . '</button>'
                        . '</form>';
                }

                $actions .= '</div>';
                return $actions;
            })
            ->editColumn('status', function (Booking $booking): string {
                $status = $booking->status;
                $label = str_replace('_', ' ', ucfirst($status));

                switch ($status) {
                    case 'confirmed':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">' . $label . '</span>';
                    case 'cancelled':
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400">' . $label . '</span>';
                    default: // 'pending_approval'
                        return '<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">' . $label . '</span>';
                }
            })
            ->rawColumns(['customer_name', 'action', 'status'])
            ->toJson();
    }

    public function create(Request $request): View
    {
        $selectedPropertyId = $request->input('property_id');
        $selectedProperty = $selectedPropertyId ? Property::find($selectedPropertyId) : null;
        $selectedProjectId = $selectedProperty ? $selectedProperty->project_id : null;

        // Fetch properties that are available or reserved
        $properties = Property::whereIn('inventory_status', ['available', 'reserved'])
            ->orWhere('id', $selectedPropertyId)
            ->orderBy('unit_number')
            ->get();

        return view('admin.pages.bookings.form', [
            'mode' => 'create',
            'booking' => null,
            'customers' => Customer::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'properties' => $properties,
            'selectedPropertyId' => $selectedPropertyId,
            'selectedProjectId' => $selectedProjectId
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'property_id' => 'required|exists:properties,id',
            'project_id' => 'required|exists:projects,id',
            'booking_amount' => 'required|numeric|min:0',
            'total_agreement_value' => 'required|numeric|min:0',
            'booking_date' => 'required|date',
            'status' => 'required|in:pending_approval,confirmed,cancelled',
            'notes' => 'nullable|string',
            'agreement_file' => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:5120',
        ]);

        // Generate Booking No
        $validated['booking_no'] = 'BK-' . date('Ymd') . '-' . rand(1000, 9999);

        // Upload agreement file if present
        if ($request->hasFile('agreement_file')) {
            $path = $request->file('agreement_file')->store('agreements', 'public');
            $validated['agreement_file_path'] = '/storage/' . $path;
        }

        // Create booking
        $booking = Booking::create($validated);

        // Update property inventory status
        $property = Property::find($validated['property_id']);
        if ($property) {
            if ($validated['status'] === 'confirmed') {
                $property->update(['inventory_status' => 'sold']);
            } elseif ($validated['status'] === 'pending_approval') {
                $property->update(['inventory_status' => 'reserved']);
            }
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking recorded successfully.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['customer', 'property', 'project', 'payments']);
        return view('admin.pages.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $properties = Property::whereIn('inventory_status', ['available', 'reserved'])
            ->orWhere('id', $booking->property_id)
            ->orderBy('unit_number')
            ->get();

        return view('admin.pages.bookings.form', [
            'mode' => 'edit',
            'booking' => $booking,
            'customers' => Customer::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'properties' => $properties
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'property_id' => 'required|exists:properties,id',
            'project_id' => 'required|exists:projects,id',
            'booking_amount' => 'required|numeric|min:0',
            'total_agreement_value' => 'required|numeric|min:0',
            'booking_date' => 'required|date',
            'status' => 'required|in:pending_approval,confirmed,cancelled',
            'notes' => 'nullable|string',
            'agreement_file' => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:5120',
        ]);

        $oldPropertyId = $booking->property_id;
        $oldStatus = $booking->status;

        // Upload agreement file if present
        if ($request->hasFile('agreement_file')) {
            // Delete old file if exists
            if ($booking->agreement_file_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $booking->agreement_file_path));
            }
            $path = $request->file('agreement_file')->store('agreements', 'public');
            $validated['agreement_file_path'] = '/storage/' . $path;
        }

        $booking->update($validated);

        // Manage property inventory status
        // Release old property if changed
        if ($oldPropertyId != $validated['property_id']) {
            $oldProperty = Property::find($oldPropertyId);
            if ($oldProperty) {
                $oldProperty->update(['inventory_status' => 'available']);
            }
        }

        // Apply new status to active property
        $property = Property::find($validated['property_id']);
        if ($property) {
            if ($validated['status'] === 'confirmed') {
                $property->update(['inventory_status' => 'sold']);
            } elseif ($validated['status'] === 'pending_approval') {
                $property->update(['inventory_status' => 'reserved']);
            } elseif ($validated['status'] === 'cancelled') {
                $property->update(['inventory_status' => 'available']);
            }
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => 'cancelled']);

        if ($booking->property) {
            $booking->property->update(['inventory_status' => 'available']);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking cancelled successfully. Unit released.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        if ($booking->agreement_file_path) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $booking->agreement_file_path));
        }

        if ($booking->property) {
            $booking->property->update(['inventory_status' => 'available']);
        }

        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
