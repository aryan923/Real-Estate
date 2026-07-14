<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.currencies.index');
    }

    public function data(Request $request)
    {
        $query = Currency::query();

        if ($request->filled('status_filter') && in_array($request->string('status_filter')->toString(), ['active', 'inactive'], true)) {
            $query->where('status', $request->string('status_filter')->toString() === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('default_label', fn (Currency $currency): string => $currency->is_default ? 'Yes (Base)' : 'No')
            ->addColumn('status_label', fn (Currency $currency): string => $currency->status ? 'Active' : 'Inactive')
            ->addColumn('action', function (Currency $currency): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.currencies.edit', $currency) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.currencies.destroy', $currency) . '" class="inline-block" data-confirm="Delete this currency?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pages.currencies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCurrency($request);

        $isDefault = (bool)($validated['is_default'] ?? false);
        $exchangeRate = $isDefault ? 1.0 : (float)($validated['exchange_rate'] ?? 1.0);

        if ($isDefault) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        $currency = Currency::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'symbol' => $validated['symbol'],
            'exchange_rate' => $exchangeRate,
            'is_default' => $isDefault,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.currencies')
            ->with('status', "Currency '{$currency->name}' created successfully.");
    }

    public function edit(Currency $currency): View
    {
        return view('admin.pages.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $validated = $this->validateCurrency($request, $currency);

        $isDefault = (bool)($validated['is_default'] ?? false);
        $exchangeRate = $isDefault ? 1.0 : (float)($validated['exchange_rate'] ?? 1.0);

        if ($isDefault) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        $currency->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'symbol' => $validated['symbol'],
            'exchange_rate' => $exchangeRate,
            'is_default' => $isDefault,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.currencies')
            ->with('status', "Currency '{$currency->name}' updated successfully.");
    }

    public function destroy(Currency $currency): RedirectResponse
    {
        if ($currency->is_default) {
            return redirect()
                ->route('admin.currencies')
                ->with('error', "Cannot delete the default base currency.");
        }

        $name = $currency->name;
        $currency->delete();

        return redirect()
            ->route('admin.currencies')
            ->with('status', "Currency '{$name}' deleted successfully.");
    }

    private function validateCurrency(Request $request, ?Currency $currency = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('currencies', 'code')->ignore($currency?->id),
            ],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'is_default' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);
    }
}
