<?php

Route::get('/user', function () {
    return response()->json(['name' => 'John Doe', 'email' => 'john@example.com']);
});
