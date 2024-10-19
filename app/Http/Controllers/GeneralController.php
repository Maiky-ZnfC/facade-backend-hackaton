<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralController extends Controller {
    private $products = null;

    public function getProductsJson(Request $request) {
        try {
            // Check if the file exists
            if (!Storage::exists('private/products.json')) {
                return response()->json([
                    'status' => false,
                    'error' => 'Archivo no encontrado.',
                ], 404);
            }

            // Get the contents of the file
            $json = Storage::get('private/products.json');

            // Decode the JSON data
            $data = json_decode($json, true);

            return response()->json(['products' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error al obtener el archivo.',
            ], 500);
        }
    }

    public function get_products() {
        if (is_null($this->products)) {
            // Check if the file exists
            if (!Storage::exists('private/products.json')) {
                throw new Exception('Archivo no encontrado.');
            }

            // Get the contents of the file
            $json = Storage::get('private/products.json');

            // Decode the JSON data
            $data = json_decode($json, true);

            $this->products = array_map(function ($row) {
                return new \App\Models\Product($row);
            }, $data);
        }

        return $this->products;
    }
}
