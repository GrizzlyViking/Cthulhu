<?php

namespace App\Http\Controllers;

use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Http\Requests\Wizard\WizardBackstoryRequest;
use App\Http\Requests\Wizard\WizardCharacteristicsRequest;
use App\Http\Requests\Wizard\WizardOccupationRequest;
use App\Http\Requests\Wizard\WizardProfileRequest;
use App\Http\Requests\Wizard\WizardSkillsRequest;
use App\Misc\CharacterCreation;
use App\Misc\Wealth;
use App\Models\Character;
use App\Models\Occupation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CharacterWizardController extends Controller
{
    use AuthorizesRequests;

    /**
     * The create character page. Serves both a fresh wizard (no draft) and
     * resumption of the user's most recent draft.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', Character::class);

        $era = $this->resolveEra($request->user());

        $draft = Character::query()
            ->where('user_id', $request->user()->id)
            ->where('status', CharacterStatus::Draft)
            ->latest('updated_at')
            ->first();

        $occupations = Occupation::all()
            ->filter(fn (Occupation $occupation): bool => in_array($era->value, $occupation->eras, true))
            ->map(fn (Occupation $occupation): array => [
                ...$occupation->toArray(),
                'formula_label' => $occupation->formulaLabel(),
            ])
            ->values();

        $occupationPoints = null;
        $personalPoints   = null;
        $wealth           = null;

        if ($draft?->occupation_id !== null && $draft?->occupationDetails !== null) {
            $occupationPoints = $draft->occupationDetails->skillPointsFor($draft);
            $personalPoints   = $draft->intelligence * 2;

            if ($draft->creditRating() > 0) {
                $wealth = Wealth::for($draft->creditRating(), $era);
            }
        }

        return Inertia::render('Character/Create', [
            'draft'            => $draft,
            'occupations'      => $occupations,
            'era'              => $era->value,
            'occupationPoints' => $occupationPoints,
            'personalPoints'   => $personalPoints,
            'wealth'           => $wealth,
        ]);
    }

    /**
     * Step 0 — profile: creates the draft and attaches all skills at base value.
     */
    public function store(WizardProfileRequest $request): RedirectResponse
    {
        $this->authorize('create', Character::class);

        $validated = $request->validated();

        $character = Character::create([
            ...$validated,
            'slug'        => Str::slug($validated['name']),
            'user_id'     => $request->user()->id,
            'status'      => CharacterStatus::Draft,
            'wizard_step' => 1,
        ]);

        $character->addAllSkills();

        return to_route('character.create');
    }

    /**
     * Step 0 (revisited) — re-edit the profile fields of an existing draft.
     */
    public function profile(WizardProfileRequest $request, Character $character): RedirectResponse
    {
        $this->authorizeDraft($request, $character);

        $validated = $request->validated();

        $character->fill($validated);
        $character->slug        = Str::slug($validated['name']);
        $character->wizard_step = max($character->wizard_step, 1);
        $character->save();

        return to_route('character.create');
    }

    /**
     * Step 1 — characteristics: stores the final rolled percentage values and
     * computes every derived stat.
     */
    public function characteristics(WizardCharacteristicsRequest $request, Character $character): RedirectResponse
    {
        $this->authorizeDraft($request, $character);

        $character->fill($request->validated());

        $character->hit_points   = CharacterCreation::hitPoints($character);
        $character->sanity       = CharacterCreation::sanity($character);
        $character->magic_points = CharacterCreation::magicPoints($character);
        $character->dodge        = CharacterCreation::dodge($character);
        $character->build        = CharacterCreation::build($character);
        $character->damage_bonus = CharacterCreation::damageBonus($character);
        $character->move_rate    = CharacterCreation::moveRate($character);
        $character->wizard_step  = max($character->wizard_step, 2);
        $character->save();

        return to_route('character.create');
    }

    /**
     * Step 2 — occupation.
     */
    public function occupation(WizardOccupationRequest $request, Character $character): RedirectResponse
    {
        $this->authorizeDraft($request, $character);

        $occupation = Occupation::findOrFail($request->validated('occupation_id'));

        $character->occupation_id = $occupation->id;
        $character->occupation    = $occupation->name;
        $character->wizard_step   = max($character->wizard_step, 3);
        $character->save();

        return to_route('character.create');
    }

    /**
     * Step 3 — skill point allocation across the occupation and personal pools.
     */
    public function skills(WizardSkillsRequest $request, Character $character): RedirectResponse
    {
        $this->authorizeDraft($request, $character);

        $occupationPool = $request->allocations('occupation');
        $personalPool   = $request->allocations('personal');

        $slugs = $occupationPool->keys()
            ->merge($personalPool->keys())
            ->push('language_own', 'dodge')
            ->unique();

        Skill::whereIn('slug', $slugs)
            ->get()
            ->each(function (Skill $skill) use ($character, $occupationPool, $personalPool): void {
                $base = match ($skill->slug) {
                    'language_own' => $character->education,
                    'dodge'        => CharacterCreation::dodge($character),
                    default        => $skill->starting_value,
                };

                $value = min(99, $base + $occupationPool->get($skill->slug, 0) + $personalPool->get($skill->slug, 0));

                $character->skills()->updateExistingPivot($skill->id, ['value' => $value]);
            });

        $character->wizard_step = max($character->wizard_step, 4);
        $character->save();

        return to_route('character.create');
    }

    /**
     * Step 4 — backstory and gear, merged into the backstory json blob.
     */
    public function backstory(WizardBackstoryRequest $request, Character $character): RedirectResponse
    {
        $this->authorizeDraft($request, $character);

        $character->backstory   = array_merge($character->backstory ?? [], $request->validated());
        $character->wizard_step = max($character->wizard_step, 5);
        $character->save();

        return to_route('character.create');
    }

    /**
     * Step 5 — completion: promotes the draft to a playable character.
     */
    public function complete(Request $request, Character $character): RedirectResponse
    {
        $this->authorizeDraft($request, $character);

        abort_unless($character->wizard_step >= 5, 422, 'Finish the previous steps before completing the character.');

        $character->status = CharacterStatus::Complete;
        $character->save();

        return to_route('character.show', $character->slug)
            ->with('success', "{$character->name} is ready to face the Mythos.");
    }

    private function authorizeDraft(Request $request, Character $character): void
    {
        $this->authorize('update', $character);

        abort_unless($character->user_id === $request->user()->id, 403);
        abort_unless($character->status === CharacterStatus::Draft, 403);
    }

    private function resolveEra(User $user): Era
    {
        return $user->groups()->first()?->era ?? Era::Twenties;
    }
}
