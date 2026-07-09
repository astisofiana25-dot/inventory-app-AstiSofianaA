<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/_debug/me', function () {
    $user = auth()->user();

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role_id' => $user->role_id,
        'role' => $user->role ? $user->role->name : null,
    ]);
});
