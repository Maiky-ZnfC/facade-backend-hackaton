<?php

namespace App\Http\Controllers;

use App\Models\DetailPrompt;
use App\Models\Prompt;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromptController extends Controller {
    private $IA_ENDPOINT = 'https://2155-45-186-255-38.ngrok-free.app/chat';
    private $system_messages;

    public function __construct() {
        $this->system_messages = [
            [
                'role' => 'system',
                'content' => 'Eres un asistente de belleza. Debes recomendar productos de Belcorp.'
            ],
            [
                'role' => 'system',
                'content' => 'Belcorp es una organización con estas 3 marcas: L\'bel, esika y cyzone.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el protector solar porque la exposicion al sol aumenta en esta temporada, por lo que el protector solar es esencial para prevenir daños por rayos UV y envejecimiento prematuro. Se recomienda un protector solar de amplio espectro (FPS 30 o más) para proteger la piel.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el hidrantes ligero porque Durante el invierno, la piel tiende a volverse más seca debido al clima frío. En primavera, un hidratante ligero es ideal para equilibrar la humedad sin sobrecargar la piel. Formulaciones en gel o lociones a base de agua funcionan bien.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el BB/CC Creams porque Estas cremas multifuncionales ofrecen cobertura ligera, hidratación, protección solar y a menudo beneficios adicionales como antioxidantes. Son perfectas para el clima más cálido, donde una base pesada puede resultar incómoda.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el Exfoliantes suaves porque La primavera es un buen momento para exfoliar la piel y eliminar las células muertas acumuladas durante el invierno. Un exfoliante suave, físico o químico (como aquellos con ácido glicólico o láctico), ayuda a renovar la piel sin irritarla.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el Sérums antioxidantes porque Los antioxidantes, como la vitamina C, ayudan a combatir el daño causado por los radicales libres debido a la mayor exposición al sol y la contaminación. También ayudan a iluminar la piel y a prevenir manchas.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el Maquillaje de colores frescos porque En primavera, tonos más frescos y ligeros como los pasteles, corales o rosados son populares y reflejan la transición hacia una estética más natural y luminosa. Sombras de ojos suaves, labiales brillantes o coloretes en tonos rosados complementan el ambiente primaveral.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el Spray o bruma facial refrescante porque A medida que las temperaturas aumentan, una bruma facial refrescante ayuda a mantener la piel hidratada y fresca durante el día. Ingredientes como el agua de rosas o el aloe vera aportan hidratación ligera y alivio instantáneo.'
            ],
            [
                'role' => 'system',
                'content' => 'En primavera es bueno el Mascarillas de arcilla o carbón porque En primavera, la piel tiende a producir más aceite debido al cambio de clima. Las mascarillas de arcilla o carbón ayudan a absorber el exceso de grasa, limpiando los poros y evitando los brotes.'
            ],
        ];
    }

    public function store(Request $request) {
        $prompt = Prompt::create();

        return response()->json([
            'status' => true,
            'id_prompt' => $prompt->id
        ]);
    }

    private function get_ia_reply($id_prompt) {
        $detailPrompts = DetailPrompt::query()
            ->select('role', 'content')
            ->where('id_prompt', $id_prompt)
            ->orderBy('id', 'asc')
            ->get();

        // Convert $detailPrompts to an array
        $detailPromptArray = $detailPrompts->toArray();

        // Merge $detailPromptArray with $this->system_messages
        $mergedMessages = array_merge($this->system_messages, $detailPromptArray);

        $client = new Client();
        // TODO: 1. Put the true API url
        // TODO: 2. Verify is 'query' is the right key
        $response = $client->post($this->IA_ENDPOINT, [
            'json' => [
                'message' => $mergedMessages,
            ],
        ]);

        $apiResponse = json_decode($response->getBody(), true);

        // TODO: Verificar
        $response = $apiResponse['reply'];

        // $response = 'AI response';

        return $response;
    }

    private function return_ia_error_response($user_id_prompt) {
        return response()->json([
            'status' => false,
            'ia_error' => true,
            'user_id_prompt' => $user_id_prompt,
            'error' => 'Error al obtener la respuesta de la IA.',
        ], 500);
    }

    public function send_prompt(Request $request) {
        $id_prompt = $request->input('id_prompt');
        $content = $request->input('content');

        $data = compact('id_prompt', 'content');

        // print_r($data);

        $validator = Validator::make($data, [
            'id_prompt' => 'required|integer|exists:prompts,id',
            'content' => 'required|string',
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'id_prompt.integer' => 'El campo id_prompt debe ser un número entero.',
            'id_prompt.exists' => 'El campo id_prompt no existe en la tabla de prompts.',
            'content.string' => 'El campo content debe ser una cadena de texto.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->messages()->first(),
            ], 422);
        }

        // TODO: 1. Maybe check if the last prompt is 'user' or 'assistant'
        // TODO: if it's 'user', then an error happen last time user sent a prompt
        // TODO: 1.1. Maybe don't send this last prompt (even delete)

        $detailPrompt = new DetailPrompt();
        $detailPrompt->id_prompt = $id_prompt;
        $detailPrompt->role = 'user';
        $detailPrompt->content = $content;
        $detailPrompt->save();

        try {
            $reply = $this->get_ia_reply($id_prompt, $content);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // TODO: Maybe add a boolean to DetailPrompt
            // TODO: to indicate that message wasn't sent
            return $this->return_ia_error_response($detailPrompt->id);
        }

        $detailPrompt = new DetailPrompt();
        $detailPrompt->id_prompt = $id_prompt;
        $detailPrompt->role = 'assistant';
        $detailPrompt->content = $reply;
        $detailPrompt->save();

        return response()->json([
            'status' => true,
            'reply' => $reply,
            'date' => now()->setTimezone('America/Lima')->toDateTimeString(),
        ]);
    }

    public function retry_prompt(Request $request) {
        $user_id_prompt = $request->input('user_id_prompt');

        $data = compact('user_id_prompt');

        $validator = Validator::make($data, [
            'user_id_prompt' => 'required|integer|exists:detail_prompts,id',
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'user_id_prompt.integer' => 'El campo user_id_prompt debe ser un número entero.',
            'user_id_prompt.exists' => 'El campo user_id_prompt no existe en la tabla de detail_prompts.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->messages()->first(),
            ], 422);
        }

        $detailPrompt = DetailPrompt::find($user_id_prompt);

        if ($detailPrompt->role !== 'user') {
            return response()->json([
                'status' => false,
                'error' => 'El prompt al que quiere acceder no es del usuario.',
            ], 422);
        }

        $id_prompt = $detailPrompt->id_prompt;

        try {
            $reply = $this->get_ia_reply($id_prompt, $detailPrompt->content);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return $this->return_ia_error_response($user_id_prompt);
        }

        $detailPrompt = new DetailPrompt();
        $detailPrompt->id_prompt = $id_prompt;
        $detailPrompt->role = 'assistant';
        $detailPrompt->content = $reply;
        $detailPrompt->save();

        return response()->json([
            'status' => true,
            'reply' => $reply,
            'date' => now()->setTimezone('America/Lima')->toDateTimeString(),
        ]);
    }
}
