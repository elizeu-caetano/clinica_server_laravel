<?php

use App\Mail\AuthMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email-test', function () {
    $email = Mail::send('mail.test', ['curso' => 'Eloquent'], function ($message) {
        $message->subject('Teste');
        $message->from('elizeucaetano@outlook.com', 'Elizeu Caetano');
        $message->to('elizeucaetanos@gmail.com', 'Caetano Silva');
        $message->cc('elizeu@paxreal.com.br', 'Pax Real');
    });

    return 'res' .$email;
});


Route::get('send-email', function () {
    $user = new stdClass();
    $user->name = "Elizeu Caetano";
    $user->email = "elizeucaetanos@gmail.com";
    $user->password = "102030";

    Mail::send(new AuthMail($user));

    //return new AuthMail($user);
});
