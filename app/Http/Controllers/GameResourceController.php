<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class GameResourceController extends Controller
{
    public function index(Request $request): Response
    {
        $group    = $request->user()->group;
        $games    = $group?->games()->withCount('resources')->orderByDesc('id')->get() ?? collect();
        $selected = $request->query('game', $group?->active_game_id);
        $game     = $selected === null ? null : $games->firstWhere('id', $selected);
        abort_if($selected !== null && $game === null, 404);

        return Inertia::render('Resources', [
            'games' => $games->map(fn (Game $item): array => [
                'id'     => $item->id,
                'name'   => $item->name,
                'active' => $item->id === $group?->active_game_id,
                'count'  => $item->resources_count,
            ]),
            'game'      => $game === null ? null : ['id' => $game->id, 'name' => $game->name, 'active' => $game->id === $group?->active_game_id],
            'resources' => $game?->resources()->latest('id')->paginate(24)->withQueryString(),
        ]);
    }

    public function store(Request $request, Game $game): JsonResponse
    {
        $this->requireGroup($request, $game);
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,avif,pdf,doc,docx', 'max:15360'],
        ], [
            'file.mimes' => 'Choose an image, PDF or Word document (DOC or DOCX).',
            'file.max'   => 'That file is larger than 15 MB. Save a smaller copy and try again.',
        ]);

        $file = $request->file('file');
        $path = $file->store('game-resources/'.$game->id, 'local');
        abort_if($path === false, 500, 'The file could not be stored. Please try again.');

        try {
            $resource = $game->resources()->create([
                'name'      => mb_substr($file->getClientOriginalName(), 0, 255),
                'path'      => $path,
                'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
                'size'      => $file->getSize(),
            ]);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return response()->json(['id' => $resource->id], 201);
    }

    public function file(Request $request, GameResource $resource): StreamedResponse
    {
        $this->requireGroup($request, $resource->game);
        abort_unless(Storage::disk('local')->exists($resource->path), 404);

        // Only raster images are embedded. Documents download instead of executing in the app's origin.
        $inline = str_starts_with($resource->mime_type, 'image/') && ! $request->boolean('download');

        return Storage::disk('local')->response($resource->path, $resource->name, [
            'Content-Type'            => $resource->mime_type,
            'Cache-Control'           => 'private, no-store',
            'X-Content-Type-Options'  => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; sandbox",
        ], $inline ? 'inline' : 'attachment');
    }

    private function requireGroup(Request $request, Game $game): void
    {
        abort_unless($request->user()->group_id !== null && $request->user()->group_id === $game->group_id, 404);
    }
}
