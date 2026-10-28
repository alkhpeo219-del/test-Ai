<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('dashboard', function ($user) {
    return $user->can('dashboard.view') ? [$user->id => ['name' => $user->name]] : false;
});
