<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use App\Models\Attendee;
use App\Http\Resources\AttendeeResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{   

    use CanLoadRelationships;

    private array $relations = ['user'];
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        //

        //fetch attendees associated with the event
        
        $attendees = $event->attendees()->latest();

        $attendees = $this->loadRelationships($event->attendees()->latest());
        //AttendeeResource::collection - converts the attendee models into a resource collection with customizes the JSON response format
        //return a pagination collection of attendees as a resource
        return AttendeeResource::collection(
            $attendees->pagination()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        //adding a new attendee
        $attendee = $event->attendees()->create([
            'user_id'=>1
        ]);

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        //
        return new AttendeeResource($this->loadRelationships($attendee));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event, Attendee $attendee)
    {
        //

        $attendee->delete();
        return response(status: 204);
    }
}
