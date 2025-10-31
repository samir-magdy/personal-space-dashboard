<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarEventController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()->calendarEvents()->orderBy('start')->get();

        // Format for FullCalendar
        $formatted = $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start->format('Y-m-d'),
                'allDay' => $event->all_day,
                'backgroundColor' => $event->background_color,
                'borderColor' => $event->border_color,
            ];
        });

        return response()->json($formatted);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'allDay' => 'boolean',
            'backgroundColor' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'borderColor' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Map camelCase to snake_case
        $data = [
            'title' => $validated['title'],
            'start' => $validated['start'],
            'all_day' => $validated['allDay'] ?? true,
            'background_color' => $validated['backgroundColor'] ?? '#7c3aed',
            'border_color' => $validated['borderColor'] ?? '#7c3aed',
        ];

        $event = $request->user()->calendarEvents()->create($data);

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start->format('Y-m-d'),
            'allDay' => $event->all_day,
            'backgroundColor' => $event->background_color,
            'borderColor' => $event->border_color,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $event = CalendarEvent::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'start' => 'sometimes|date',
            'allDay' => 'sometimes|boolean',
            'backgroundColor' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'borderColor' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $data = [];
        if (isset($validated['title'])) $data['title'] = $validated['title'];
        if (isset($validated['start'])) $data['start'] = $validated['start'];
        if (isset($validated['allDay'])) $data['all_day'] = $validated['allDay'];
        if (isset($validated['backgroundColor'])) $data['background_color'] = $validated['backgroundColor'];
        if (isset($validated['borderColor'])) $data['border_color'] = $validated['borderColor'];

        $event->update($data);

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start->format('Y-m-d'),
            'allDay' => $event->all_day,
            'backgroundColor' => $event->background_color,
            'borderColor' => $event->border_color,
        ]);
    }

    public function destroy(string $id)
    {
        $event = CalendarEvent::where('user_id', auth()->id())->findOrFail($id);
        $event->delete();

        return response()->json(null, 204);
    }
}
