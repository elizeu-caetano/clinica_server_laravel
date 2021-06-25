<?php

use Illuminate\Support\Facades\Http;
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


Route::get('/send-email', function () {

    $user = new stdClass();
    $user->name = "Elizeu Caetano da Silva";
    $user->email = "elizeu@paxreal.com.br";
    $user->password = "201045";

    $response = Http::withHeaders([
        'content-type' => 'application/json',
        'accept' => 'application/json',
        'api-key' => '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'
    ])->post('https://api.sendinblue.com/v3/smtp/email', [
        "subject" => "Email de Teste",
        "sender" => ["name" => "Elizeu Caetano", "email" => "elizeucaetano@outlook.com" ],
        "to" => [$user],
        "htmlContent" => "
            <!DOCTYPE html>
            <html lang='pt-br'>
            <head></head>
            <body style='margin: 0; auto; >
                <div style='margin-left: 50%; margin-right: 50%; background-color:#dddddd'>
                    <div style='background:#fff; padding: 50px;'>
                        <div style='align-items: center'>
                            <img src='https://2emktmessage.s3.amazonaws.com/2elogo.png' alt='2e - Sistemas' width='120px' height='80px'>
                        </div>
                        <div style='display: flex; flex-direction: row; justify-content: center;'>
                        <h2>Olá, <strong style='text-transform: uppercase;'>$user->name</strong></h2>
                        </div>
                        <div style='display: flex; flex-direction: row; justify-content: center;'>
                            <h3>Seja bem vindo a 2E Clínica!</h3>
                        </div>
                        <div style='display: flex; flex-direction: row; justify-content: center;'>
                            <h3>Sua senha de acesso é: $user->password. </h3>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        "
    ]);

    return $response;
});
