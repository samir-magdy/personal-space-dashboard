<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $bookmarks = $request->user()->bookmarks()->orderBy('created_at')->get();
        return response()->json($bookmarks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'url' => 'required|url',
        ]);

        $bookmark = $request->user()->bookmarks()->create($validated);

        return response()->json($bookmark, 201);
    }

    public function show(string $id)
    {
        $bookmark = Bookmark::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($bookmark);
    }

    public function update(Request $request, string $id)
    {
        $bookmark = Bookmark::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'url' => 'sometimes|url',
        ]);

        $bookmark->update($validated);

        return response()->json($bookmark);
    }

    public function destroy(string $id)
    {
        $bookmark = Bookmark::where('user_id', auth()->id())->findOrFail($id);
        $bookmark->delete();

        return response()->json(null, 204);
    }
}
