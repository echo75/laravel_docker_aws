<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMovie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserMovieController extends Controller
{
    public function indexWatchlist(User $user): JsonResponse
    {
        $movies = UserMovie::where('user_id', $user->id)
            ->where('type', 'watch')
            ->orderByDesc('id')
            ->get();

        return response()->json($this->enrichMovies($movies));
    }

    public function storeWatchlist(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'imdbID' => ['required', 'string', 'max:24'],
        ]);

        $entry = UserMovie::firstOrCreate([
            'user_id' => $user->id,
            'movie_id' => $validated['imdbID'],
            'type' => 'watch',
        ]);

        return response()->json([
            'id' => $entry->id,
            'user_id' => $entry->user_id,
            'movie_id' => $entry->movie_id,
            'type' => $entry->type,
        ], 201);
    }

    public function destroyWatchlist(User $user, string $imdbID): JsonResponse
    {
        UserMovie::where('user_id', $user->id)
            ->where('movie_id', $imdbID)
            ->where('type', 'watch')
            ->delete();

        return response()->json(null, 204);
    }

    public function moveToWatched(User $user, string $imdbID): JsonResponse
    {
        $entry = UserMovie::where('user_id', $user->id)
            ->where('movie_id', $imdbID)
            ->where('type', 'watch')
            ->first();

        if ($entry) {
            $entry->type = 'watched';
            $entry->save();
        } else {
            UserMovie::firstOrCreate([
                'user_id' => $user->id,
                'movie_id' => $imdbID,
                'type' => 'watched',
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function indexWatchedlist(User $user): JsonResponse
    {
        $movies = UserMovie::where('user_id', $user->id)
            ->where('type', 'watched')
            ->orderByDesc('id')
            ->get();

        return response()->json($this->enrichMovies($movies));
    }

    public function destroyWatchedlist(User $user, string $imdbID): JsonResponse
    {
        UserMovie::where('user_id', $user->id)
            ->where('movie_id', $imdbID)
            ->where('type', 'watched')
            ->delete();

        return response()->json(null, 204);
    }

    public function showReview(User $user, string $imdbID): JsonResponse
    {
        $entry = UserMovie::where('user_id', $user->id)
            ->where('movie_id', $imdbID)
            ->where('type', 'watched')
            ->first();

        return response()->json([
            'imdbID' => $imdbID,
            'review' => $entry?->review,
        ]);
    }

    public function storeReview(Request $request, User $user, string $imdbID): JsonResponse
    {
        $validated = $request->validate([
            'review' => ['required', 'string', 'min:5'],
        ]);

        $entry = UserMovie::updateOrCreate([
            'user_id' => $user->id,
            'movie_id' => $imdbID,
            'type' => 'watched',
        ], [
            'review' => $validated['review'],
        ]);

        return response()->json([
            'id' => $entry->id,
            'imdbID' => $entry->movie_id,
            'review' => $entry->review,
        ]);
    }

    public function indexReviews(string $imdbID): JsonResponse
    {
        $reviews = UserMovie::with('user:id,firstName,surName')
            ->where('movie_id', $imdbID)
            ->where('type', 'watched')
            ->whereNotNull('review')
            ->where('review', '!=', '')
            ->orderByDesc('id')
            ->get();

        return response()->json($reviews->map(function (UserMovie $entry) {
            return [
                '_id' => $entry->id,
                'text' => $entry->review,
                'author' => [
                    'firstName' => $entry->user?->firstName ?? 'Unknown',
                    'surName' => $entry->user?->surName ?? 'User',
                ],
            ];
        })->values()->all());
    }

    private function enrichMovies($movies): array
    {
        $apiKey = config('services.omdb.key', 'thewdb');
        $baseUrl = config('services.omdb.url', 'https://www.omdbapi.com/');

        return $movies->map(function (UserMovie $movie) use ($apiKey, $baseUrl) {
            $details = Http::timeout(10)->get($baseUrl, [
                'apikey' => $apiKey,
                'i' => $movie->movie_id,
            ])->json();

            return [
                'id' => $movie->id,
                'imdbID' => $movie->movie_id,
                'Title' => $details['Title'] ?? $movie->movie_id,
                'Year' => $details['Year'] ?? '',
                'Type' => $details['Type'] ?? 'movie',
                'Poster' => $details['Poster'] ?? 'N/A',
                'review' => $movie->review,
            ];
        })->values()->all();
    }
}
