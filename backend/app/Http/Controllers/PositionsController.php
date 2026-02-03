<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Position;

class PositionsController extends Controller
{
    public function allPositions() {
        // Logic to retrieve and return all positions
        $positions = Position::all();

        return response()->json([
            'message' => 'Positions retrieved successfully',
            'count' => $positions->count(),
            'positions' => $positions
        ], 200);
    }

    public function createPosition(Request $request) {
        // Logic to create a new position
        $data = $request->validate([
            'title' => 'required|string|unique:positions,title',
            'description' => 'nullable|string',
        ]);
        $position = Position::create($data);

        return response()->json([
            'message' => 'Position created successfully',
            'position' => $position
        ], 201);
    }
    public function deletePosition(int $id) {
        // Logic to delete a position by ID
        $position = Position::findOrFail($id);
        $position->delete();
        return response()->json([
            'message' => 'Position ' . $position->title . ' deleted successfully'
        ], 200);
    }
    public function editPosition(Request $request, int $id) {
        // Logic to edit a position by ID
        $position = Position::findOrFail($id);
        $data = $request->validate([
            'title' => 'string|unique:positions,title,' . $position->id,                
            'description' => 'nullable|string'
        ]);
        $position->update($data);
        return response()->json([
            'message' => 'Position ' . $position->title . ' updated successfully',
            'position' => $position
        ], 200);
    }
}
