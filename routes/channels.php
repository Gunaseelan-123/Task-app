<?php

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Broadcast;

// Define broadcast channel authorization. Adjust logic for production security.
Broadcast::channel('rider.{id}', function ($user, $id) {
    // For development/testing: allow any authenticated user to listen.
    // TODO: Replace with authorization rules so only permitted customers/admins can subscribe.
    return true;
});
