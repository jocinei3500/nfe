<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NfeController extends Controller
{
    
    public function getNFeData(Request $request)
    {
        $nfeNumber = $request->input('nfe_number');

        if (!$nfeNumber) {
            return response()->json(['error' => 'Número da NF-e não informado.'], 400);
        }

        $apiUrl = 'https://www.receitaws.com.br/v1/nfe/' . $nfeNumber;
        $client = new Client();

        try {
            $response = $client->get($apiUrl);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(), true);

                // Verificar se a API retornou os dados corretamente
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    return response()->json(['error' => 'Não foi possível obter os dados da NF-e.'], 404);
                }

                // Aqui você pode tratar os dados conforme sua necessidade
                // Por exemplo, retornar um JSON com os dados da NF-e
                return response()->json($responseData, 200);
            } else {
                return response()->json(['error' => 'Não foi possível obter os dados da NF-e.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao consultar a API da ReceitaWS.'], 500);
        }
    }
}
