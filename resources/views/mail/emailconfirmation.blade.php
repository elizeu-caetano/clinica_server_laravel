@component('mail::message')

<h1>Olá {{ strtoupper($user->name) }},</h1>
<h3>Seja bem vindo a 2E Clínica!</h3>

<h4>Sua senha de acesso é: {{ $user->password }}. </h4>

<h4>Por favor, click no botão abaixo para confirmar seu email: {{ $user->email }}, para ter acesso ao sistema. </h4>

@component('mail::button', ['url' => $user->link])
Confirmar
@endcomponent

@endcomponent
