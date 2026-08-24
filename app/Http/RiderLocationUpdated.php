<?php

namespace App\Http;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiderLocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public array $location;

    public function __construct(int $userId, array $location)
    {
        $this->userId = $userId;
        $this->location = $location;
    }

    public function broadcastOn()
    {
        return new Channel('rider.' . $this->userId);
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'latitude' => $this->location['latitude'],
            'longitude' => $this->location['longitude'],
            'heading' => $this->location['heading'] ?? null,
            'speed' => $this->location['speed'] ?? null,
            'accuracy' => $this->location['accuracy'] ?? null,
            'recorded_at' => $this->location['recorded_at'] ?? now()->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'RiderLocationUpdated';
    }
}
