<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-mail', function () {
    \Mail::raw('Test email', function ($msg) {
        $msg->to('channelytpop@gmail.com')
            ->subject('Test Gmail SMTP');
    });

    return 'Sent';
});
