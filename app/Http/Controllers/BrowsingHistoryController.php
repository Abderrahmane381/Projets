<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrowsingHistory;

class BrowsingHistoryController extends Controller
{

    

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'title' => 'required|string|max:255',
        ]);

        $history = BrowsingHistory::create([
            'url' => $validated['url'],
            'title' => $validated['title'],
            'visited_at' => now(),
        ]);

        return response()->json($history, 201);
    }

    public function destroy($id)
    {
        $history = BrowsingHistory::findOrFail($id);
        $history->delete();

        return response()->json(null, 204);
    }

    public function clear()
    {
        BrowsingHistory::truncate();
        return response()->json(null, 204);
    }
}
