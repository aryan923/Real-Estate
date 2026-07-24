<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $selectedProjectId = $request->input('project_id', $projects->first()?->id);
        
        $selectedProject = null;
        $towers = collect();
        $properties = collect();

        if ($selectedProjectId) {
            $selectedProject = Project::find($selectedProjectId);
            if ($selectedProject) {
                $properties = $selectedProject->properties()->get();
                $towers = $properties->pluck('tower')->filter()->unique()->values();
            }
        }

        $selectedTower = $request->input('tower');
        if (empty($selectedTower) || !$towers->contains($selectedTower)) {
            $selectedTower = $towers->first();
        }

        $statusCounts = [
            'total' => $properties->count(),
            'available' => $properties->where('inventory_status', 'available')->count(),
            'sold' => $properties->where('inventory_status', 'sold')->count(),
            'reserved' => $properties->where('inventory_status', 'reserved')->count(),
            'blocked' => $properties->where('inventory_status', 'blocked')->count(),
        ];

        return view('admin.pages.inventory.index', compact(
            'projects', 'selectedProjectId', 'selectedProject', 'towers', 'selectedTower', 'properties', 'statusCounts'
        ));
    }

    public function showUnit(Property $unit): JsonResponse
    {
        $unit->load(['project', 'categories']);
        
        $booking = $unit->bookings()->with('customer')->orderBy('created_at', 'desc')->first();

        return response()->json([
            'success' => true,
            'unit' => [
                'id' => $unit->id,
                'title' => $unit->title,
                'unit_number' => $unit->unit_number,
                'tower' => $unit->tower,
                'floor' => $unit->floor,
                'inventory_status' => $unit->inventory_status,
                'price' => $unit->price,
                'formatted_price' => is_numeric($unit->price) ? format_price($unit->price) : ($unit->price ?: 'Contact for price'),
                'size' => $unit->size,
                'facing' => $unit->facing,
                'parking_slots' => $unit->parking_slots,
                'bedrooms' => $unit->bedrooms,
                'bathrooms' => $unit->bathrooms,
                'project_name' => $unit->project?->name ?: 'Standalone Property',
                'category_name' => $unit->categories->pluck('name')->first() ?: 'N/A',
            ],
            'booking' => $booking ? [
                'id' => $booking->id,
                'customer_name' => $booking->customer?->name,
                'customer_id' => $booking->customer_id,
                'booking_date' => $booking->booking_date ? $booking->booking_date->format('d M Y') : null,
                'status' => $booking->status,
            ] : null
        ]);
    }

    public function reserveUnit(Request $request, Property $unit): JsonResponse
    {
        $action = $request->input('action');

        if ($action === 'reserve') {
            $unit->update(['inventory_status' => 'reserved']);
            return response()->json(['success' => true, 'message' => 'Unit reserved successfully.']);
        } elseif ($action === 'release' || $action === 'unblock') {
            $unit->update(['inventory_status' => 'available']);
            return response()->json(['success' => true, 'message' => 'Unit status updated to available.']);
        } elseif ($action === 'block') {
            $unit->update(['inventory_status' => 'blocked']);
            return response()->json(['success' => true, 'message' => 'Unit blocked successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid action.']);
    }
}
