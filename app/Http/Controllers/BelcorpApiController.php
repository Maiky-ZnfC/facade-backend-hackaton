<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BelcorpApiController extends Controller {
    public function obtenerToken() {
        $client = new Client();

        // Define tus credenciales
        $usuario = 'AKIAYYJJSXGFRRAOLFX5';
        $contraseña = 'HilujchRC0jUt+U+9Hj8ctIaQnWPRZnegEEOOVmD0JE=';

        // Codifica las credenciales en base64
        $credenciales = base64_encode("{$usuario}:{$contraseña}");

        try {
            $response = $client->post('https://api-qa.belcorp.biz/oauth/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . $credenciales,
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

            // Decodifica la respuesta JSON
            $data = json_decode($response->getBody(), true);

            return response()->json([
                'status' => true,
                'token' => $data['access_token'], // Obtén el token de acceso
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $error = json_decode($e->getResponse()->getBody(), true);
            return response()->json([
                'status' => false,
                'error' => 'No se pudo obtener el token.',
                'error_original' => $error,
            ], 500);
        }
    }
}
