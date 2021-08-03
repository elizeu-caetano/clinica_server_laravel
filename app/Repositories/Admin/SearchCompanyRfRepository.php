<?php

namespace App\Repositories\Admin;

use App\Http\Resources\Admin\SearchCompanyRfResource;
use App\Repositories\Admin\Contracts\SearchCompanyRfRepositoryInterface;
use Illuminate\Support\Facades\Http;

class SearchCompanyRfRepository implements SearchCompanyRfRepositoryInterface
{

    public function getCompanyByCnpj($cnpj)
    {
        try {
            $result = Http::withHeaders([
                'Authorization' => env('CNPJ_JA_KEY'),
            ])->get('https://api.cnpja.com.br/companies/' . $cnpj);

            if (isset($result->json()['error'])) {
                return ['status' => false, 'message' => $this->returnErrors($result->json()['error'])];
            } else {
                return ['status' => true, 'data' => new SearchCompanyRfResource($result->json())];
            }

        } catch (\Throwable $th) {
            $this->resultado = ['status' => false, 'message' => 'Não foi possível carregar os dados da Empresa!.', 'error' => $th->getMessage()];
        }
    }

    private function returnErrors($error)
    {
            if ($error == 400) {
                return 'CNPJ inválido';
            }  else if ($error == 401) {
                return 'Erro: 401 - Não foi possível autenticar sua requisição';
            } else if ($error == 404) {
                return 'O CNPJ não está registrado na Receita Federal';
            } else if ($error == 429) {
                return 'Erro: 401 - Seus créditos acabaram';
            } else {
                return 'Sistema de consulta insdisponível, prencha manualmente.';
            }

    }
}
