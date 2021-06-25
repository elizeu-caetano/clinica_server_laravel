
@component('mail::message')

<h1>Olá {{ Str::title($user->name) }},</h1>
<h3>Seja bem vindo a 2E Clínica!</h3>

<h4>Sua senha de acesso é: {{ $user->password }}. </h4>

<h4>Para mais informações entre em contato com a Empresa Abaixo. </h4>

<div class="footer">
<img src="{{ env('AWS_URL').$contractor->logo}}" class="logo-signature">
<h2>{{ $contractor->fantasy_name }}</h2>
<h3>{{ $contractor->phone }}</h3>
</div>

@endcomponent
