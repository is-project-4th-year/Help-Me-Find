<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $parts = explode('_', $chatId);
    if(count($parts) !== 2) return false;
    [$a, $b] = $parts;
    $a = (int) $a; $b = (int) $b;
    return $user->id === $a || $user->id === $b;
});
