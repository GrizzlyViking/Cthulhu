<?php

namespace App\Http\Controllers;

use App\Enums\CharacterStatus;
use App\Misc\SkillCheck;
use App\Models\Character;
use App\Models\Game;
use App\Models\Skill;
use App\Models\Weapon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The Keeper's screen: the party the group is playing with right now, on one
 * page, and the secret rolls made against it.
 *
 * It shows current figures only — hit points, sanity, magic points, luck as
 * they stand. Maxima belong on the player's own sheet; what the Keeper needs
 * mid-scene is who is hurt, who is mad, and who is out of bullets.
 */
class KeeperController extends Controller
{
    public function index(Request $request): Response
    {
        $game  = $request->user()->group?->activeGame;
        $party = $this->party($game);

        return Inertia::render('Keeper', [
            'game' => $game === null ? null : [
                'id'   => $game->id,
                'name' => $game->name,
                'era'  => $game->era->shortLabel(),
            ],
            'party'         => $party->map(fn (Character $character): array => $this->summarise($character))->all(),
            'passiveSkills' => $this->passiveSkills()
                ->map(fn (Skill $skill): array => ['slug' => $skill->slug, 'name' => $skill->display_name])
                ->values()
                ->all(),
        ]);
    }

    /**
     * Roll a passive skill in secret against everyone the Keeper says is at
     * the table. Investigators outside the game being played are dropped
     * rather than refused — the same silence the roll has always kept.
     */
    public function roll(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'skill_slug'   => ['required', 'string', 'exists:skills,slug'],
            'characters'   => ['present', 'array'],
            'characters.*' => ['integer'],
        ]);

        $skill = Skill::where('slug', $validated['skill_slug'])->firstOrFail();
        $game  = $request->user()->group?->activeGame;

        $results = $this->party($game)
            ->whereIn('id', $validated['characters'])
            ->map(fn (Character $character): array => [
                'character_id' => $character->id,
                'name'         => $character->name,
                ...SkillCheck::against($this->skillValue($character, $skill)),
            ])
            ->values()
            ->all();

        return response()->json([
            'skill'   => $skill->display_name,
            'results' => $results,
        ]);
    }

    /**
     * The investigators in the campaign the Keeper's group is playing. Drafts
     * are left out — an unfinished sheet has no figures worth reading.
     *
     * @return EloquentCollection<int, Character>
     */
    private function party(?Game $game): EloquentCollection
    {
        if ($game === null) {
            return new EloquentCollection();
        }

        return $game->characters()
            ->where('status', CharacterStatus::Complete)
            ->orderBy('name')
            ->get();
    }

    /**
     * The skills configured as passive, in the order they are listed. Slugs
     * naming a skill this server does not have are skipped.
     *
     * @return Collection<int, Skill>
     */
    private function passiveSkills(): Collection
    {
        /** @var list<string> $slugs */
        $slugs = config('cthulhu.keeper.passive_skills', []);

        $skills = Skill::whereIn('slug', $slugs)->get()->keyBy('slug');

        return collect($slugs)
            ->map(fn (string $slug): ?Skill => $skills->get($slug))
            ->filter()
            ->values();
    }

    /**
     * What this investigator has in a skill. A sheet that somehow lacks it
     * rolls against the book's starting value rather than against nothing.
     */
    private function skillValue(Character $character, Skill $skill): int
    {
        $onSheet = $character->skills->firstWhere('id', $skill->id);

        return (int) ($onSheet?->pivot->value ?? $skill->starting_value);
    }

    /**
     * One investigator as the Keeper's screen wants them: current figures,
     * whatever is currently wrong with them, and what they are holding.
     *
     * @return array<string, mixed>
     */
    private function summarise(Character $character): array
    {
        return [
            'id'          => $character->id,
            'slug'        => $character->slug,
            'name'        => $character->name,
            'player'      => $character->player?->name,
            'hitPoints'   => $character->hit_points,
            'sanity'      => $character->sanity,
            'magicPoints' => $character->magic_points,
            'luck'        => $character->luck,
            'conditions'  => $this->conditions($character),
            'skills'      => $this->passiveSkills()
                ->mapWithKeys(fn (Skill $skill): array => [$skill->slug => $this->skillValue($character, $skill)])
                ->all(),
            'firearms' => $this->firearms($character),
        ];
    }

    /**
     * Everything currently wrong with an investigator, worst first — what the
     * Keeper needs to see before deciding how cruel to be.
     *
     * @return list<string>
     */
    private function conditions(Character $character): array
    {
        return collect([
            'Dying'               => $character->dying,
            'Unconscious'         => $character->unconscious,
            'Major wound'         => $character->major_wound,
            'Indefinite insanity' => $character->indefinite_insanity,
            'Temporary insanity'  => $character->temporary_insanity,
        ])->filter()->keys()->all();
    }

    /**
     * What is loaded. Only weapons that take ammunition at all appear: a knife
     * has no current figure worth a column, an empty revolver very much does.
     * `equipables.ammo` is never null — it is the weapon's printed magazine
     * that says whether rounds are a thing it has.
     *
     * @return list<array{name: string, ammo: int, reserve: int}>
     */
    private function firearms(Character $character): array
    {
        return $character->weapons
            ->filter(fn (Weapon $weapon): bool => $weapon->magazine_capacity !== null)
            ->map(fn (Weapon $weapon): array => [
                'name'    => $weapon->name,
                'ammo'    => (int) $weapon->pivot->ammo,
                'reserve' => (int) $weapon->pivot->ammo_reserve,
            ])
            ->values()
            ->all();
    }
}
