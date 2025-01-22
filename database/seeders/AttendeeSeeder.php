<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Attendee;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //fetch all the users and events from the database
        $users = \App\Models\User::all();
        $events = \App\Models\Event::all();

        //assign users to random events
        foreach ($users as $user){
            $eventsToAttend = $events->random(rand(1,3));

            foreach($eventsToAttend as $event){
                \App\Models\Attendee::create([
                    //create an attendee record
                    'user_id' => $user->id,
                    'event_id' =>$event->id
                ]);
            }
            
        }
    }
}
