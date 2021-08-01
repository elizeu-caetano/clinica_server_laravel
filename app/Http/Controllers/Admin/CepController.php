<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CepResource;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function getAddress($cep)
    {
        try {
            $result = Http::get('https://viacep.com.br/ws/' . $cep . '/json');

            if (isset($result['erro'])) {
                return ['status' => false, 'message' => 'CEP não encontrado.'];
            } else {
                return ['status' => true, 'data' => new CepResource($result)];
            }

        } catch (\Throwable $th) {
            $this->resultado = ['status' => false, 'message' => 'Não foi possível carregar os dados da Empresa!.', 'error' => $th->getMessage()];
        }
    }
}
