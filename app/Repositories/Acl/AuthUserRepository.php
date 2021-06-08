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

}
