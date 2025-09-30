<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageGenController extends Controller
{
    public function generate(Request $request)
    {
        $prompt = $request->input('prompt');

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3', // or "dall-e-3" if gpt-image-1 isn't available
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
            ]);

        return response()->json($response->json());
    }
}
