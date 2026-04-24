<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $apiKey = config('services.omdb.key', 'thewdb');
        $baseUrl = config('services.omdb.url', 'https://www.omdbapi.com/');

        $response = Http::timeout(10)->get($baseUrl, [
            'apikey' => $apiKey,
            's' => $validated['title'],
            'type' => 'movie',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'OMDb ist aktuell nicht erreichbar.',
            ], 502);
        }

        $payload = $response->json();

        if (($payload['Response'] ?? 'False') === 'False') {
            return response()->json([]);
        }

        return response()->json($payload['Search'] ?? []);
    }

    public function details(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'imdbID' => ['required', 'string', 'max:20'],
        ]);

        $apiKey = config('services.omdb.key', 'thewdb');
        $baseUrl = config('services.omdb.url', 'https://www.omdbapi.com/');

        $response = Http::timeout(10)->get($baseUrl, [
            'apikey' => $apiKey,
            'i' => $validated['imdbID'],
            'plot' => 'full',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'OMDb ist aktuell nicht erreichbar.',
            ], 502);
        }

        $payload = $response->json();

        if (($payload['Response'] ?? 'False') === 'False') {
            return response()->json([
                'message' => $payload['Error'] ?? 'Film nicht gefunden.',
            ], 404);
        }

        return response()->json($payload);
    }
}
