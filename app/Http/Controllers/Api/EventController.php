<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Http\Traits\CanLoadRelationships; // Corrected namespace
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use CanLoadRelationships;

    /**
     * Display a listing of the resource.
     */

     public function __construct()
     {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
     }
    public function index()
    {
        $relations = ['user', 'attendees', 'attendees.user']; // List of relationships to optionally include

        $query = $this->loadRelationships(Event::query()); // Pass relations to loadRelationships

        return EventResource::collection($query->latest()->paginate()); // Retrieve events, sort by latest, and paginate
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate and create a new event
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string', // Removed trailing pipe
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => $request->user()->id
        ]);
    
        // Load the user relationship and return the resource
        return new EventResource($event->load('user', 'attendees'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request) 
    {
        $event = Event::findOrFail($id);
         // Load relationships based on the request
        $event = $this->loadRelationships($event, ['user', 'attendees']);

        return new EventResource($event);}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {

        if (Gate::denies('update-event', $event)){
            abort(403, 'You are not authorized to update this event');  
        }

        // $this->authorize('update-event', $event);

        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string', // Removed trailing pipe
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        return new EventResource($this->loadRelationships($event)); // Eager-load relationships 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }
}