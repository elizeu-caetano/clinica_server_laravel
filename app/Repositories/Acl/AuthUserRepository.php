<?php

namespace App\Repositories\Acl;

use App\Http\Resources\Acl\AuthUserResource;
use App\Models\Acl\User;
use App\Repositories\Acl\Contracts\AuthUserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class AuthUserRepository implements AuthUserRepositoryInterface {

    public function auth($request)
    {
        try {

            $user = User::where('email', $request->email)->first();

            //define how many hours for the token to expire
            $hour = $request->timeToken ? $request->timeToken : 12;

            if (!$user || !Hash::check($request->password, $user->password)) {
                return ['status' => false, 'message' => 'Email ou Senha inválida.'];
            }

            Passport::personalAccessTokensExpireIn(now()->addHour($hour));
            $user->token = $user->createToken($request->device_name)->accessToken;

            $user->permissions = $this->permissions($user);

            return ['status' => true, 'data' => new AuthUserResource($user)];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível carregar os dados.', 'error' => $th->getMessage()];
        }
    }

    private function permissions($user)
    {
        $arrayPermissions = $user->permissions();

        $permissions = [];
        foreach ($arrayPermissions as $value) {
            $permissions[$value] = true;
        }

        return $permissions;
    }

    public function authorized()
    {
        try {
            return ['status'=>true, "message"=> "Autorizado!"];
        } catch (\Throwable $th) {
            return ['status'=>false, 'message'=> 'Não Autorizado.', 'error'=> $th->getMessage()];
        }
    }

    public function logout($request)
    {
        try {

            return ['status' => true, 'message' => 'Usuário deslogado'];

        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Usuário não foi deslogado.', 'error' => $th->getMessage()];
        }

    }

    public function emailConfirmation($uuid, $token)
    {
        try {

            $user = User::where('uuid', $uuid)->first();

            if ($user) {

                $contractor = $user->contractor;

                if ($user->active And $user->email_verified_at != null) {
                    return ['status' => true, 'message' => 'O Email já foi ativado em: ' . Carbon::make($user->email_verified_at)->format('d/m/Y H:i:s')];
                }

                if ($user->token == $token) {
                    $user->active = true;
                    $user->token = '';
                    $user->email_verified_at = now();
                    $user->save();
                    return ['status' => true, 'message' => 'O Email foi ativado!'];
                } else {
                    return ['status' => false, 'message' => 'O Email não foi ativado. Entre em contato com a ' . strtoupper($contractor->fantasy_name) . ' no telefone ' . $contractor->phone . '.'];
                }
            } else{
                return ['status' => false, 'message' => 'Não foi possível confirmar o email, entre em contato com sua empresa!'];
            }



        } catch (\Throwable $th) {

            return ['status' => false, 'message' => 'Não foi possível confirmar o email, entre em contato com sua empresa!', 'error' => $th->getMessage()];
        }
    }

}
