<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\QrCode;

class GeolocationUpdateNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $qrcode;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\QrCode  $qrcode
     * @return void
     */
    public function __construct(QrCode $qrcode)
    {
        $this->qrcode = $qrcode;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcasting on a private channel for the specific teacher
        return new PrivateChannel('guru.' . $this->qrcode->guru_id);
    }

    public function broadcastWith()
    {
        return [
            'ajar_id' => $this->qrcode->ajar_id,
            'ajar_name' => $this->qrcode->ajar->mapel->nama_mapel ?? 'Unknown',
            'updated_at' => now()->toDateTimeString(),
            'lat' => $this->qrcode->teacher_lat,
            'lng' => $this->qrcode->teacher_lng,
        ];
    }
}
