<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.customers.index');
    }

    public function data(Request $request)
    {
        $query = Customer::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function (Customer $customer): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.customers.show', $customer) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="View Profile">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>'
                    . '</a>'
                    . '<a href="' . route('admin.customers.edit', $customer) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.customers.destroy', $customer) . '" class="inline-block" data-confirm="Delete this customer?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pages.customers.form', [
            'mode' => 'create',
            'customer' => null,
            'projects' => Project::orderBy('name')->get()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'aadhaar_number' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:255',
            'family_members' => 'nullable|array',
            'interested_projects' => 'nullable|array',
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        // Load customer relationships: bookings and documents
        $bookings = $customer->bookings()->with(['property', 'project'])->get();
        $documents = $customer->documents()->get();

        // Pluck interested projects info
        $interestedProjectIds = $customer->interested_projects ?? [];
        $interestedProjects = Project::whereIn('id', $interestedProjectIds)->get();

        return view('admin.pages.customers.show', compact('customer', 'bookings', 'documents', 'interestedProjects'));
    }

    public function edit(Customer $customer): View
    {
        return view('admin.pages.customers.form', [
            'mode' => 'edit',
            'customer' => $customer,
            'projects' => Project::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'aadhaar_number' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:255',
            'family_members' => 'nullable|array',
            'interested_projects' => 'nullable|array',
        ]);

        // Clean empty rows from family members array
        if (isset($validated['family_members'])) {
            $validated['family_members'] = array_filter($validated['family_members'], function ($member) {
                return !empty($member['name']) || !empty($member['relation']);
            });
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
