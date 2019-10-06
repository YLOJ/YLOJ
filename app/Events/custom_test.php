<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class custom_test implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


	public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
	public function __construct($message){
	    $this->message = $message;
	}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('Submission');
    }

	public function broadcastAs()
	{
	    return 'submission.custom_test';
	}
	
	public function broadcaseWith(){
		return ['message' => $this->message];
	}
}
