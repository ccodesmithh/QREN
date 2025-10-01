<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Attendance;

class NewAttendanceNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attendance;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcasting on a private channel for the specific teacher
        return new PrivateChannel('guru.' . $this->attendance->qrcode->guru_id);
    }

    public function broadcastWith()
    {
        return [
            'attendance_id' => $this->attendance->id,
            'student_name' => $this->attendance->siswa->name,
            'scanned_at' => $this->attendance->scanned_at->toDateTimeString(),
        ];
    }
}
