<?php

namespace App\Http\Controllers;

use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Http\Requests\OccupationRequest;
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
     * resumption of the user's most recent draft in the game being played.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', Character::class);

        $era = $this->resolveEra($request->user());

        $draft = $this->resumableDraft($request->user());

        $occupations = Occupation::query()
            ->inEra($era)
            ->orderBy('name')
            ->get()
            ->map(fn (Occupation $occupation): array => $occupation->toWizardArray())
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
            'draft'       => $draft,
            'occupations' => $occupations,
            'era'         => $era->value,
            'eras'        => Era::options(),
            // For the custom occupation form. Deliberately not narrowed to the
            // era: the occupation being written is shared reference data of its
            // own, and may be marked for an era the group is not playing.
            'skillOptions' => Skill::query()
                ->whereNotIn('slug', Occupation::UNSELECTABLE_SKILLS)
                ->orderBy('display_name')
                ->get(['slug', 'display_name'])
                ->map(fn (Skill $skill): array => [
                    'slug'  => $skill->slug,
                    'label' => $skill->display_name,
                ]),
            'characteristics'  => Occupation::CHARACTERISTICS,
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
            'group_id'    => $request->user()->group_id,
            'status'      => CharacterStatus::Draft,
            'wizard_step' => 1,
        ]);

        $character->addAllSkills();

        // The new investigator joins the campaign the group is playing, so
        // they appear under Characters rather than in limbo.
        $activeGameId = $request->user()->group?->active_game_id;

        if ($activeGameId !== null) {
            $character->games()->syncWithoutDetaching([$activeGameId]);
        }

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
     * Step 2 (aside) — a player writing an occupation the book does not have.
     *
     * It joins the shared list rather than living on this one sheet, the same
     * way a skill a player adds does: the next investigator to reach this step
     * can pick it, and an admin can tidy or retire it afterwards. That reaches
     * every group on the server, which is the point — the lists grow from play.
     *
     * The new occupation is chosen for the draft straight away, since writing
     * one is how this player says what they are. The step is not advanced: they
     * still press "Save occupation", so the flow reads the same either way.
     */
    public function storeOccupation(OccupationRequest $request, Character $character): RedirectResponse
    {
        $this->authorizeDraft($request, $character);

        $occupation = Occupation::create([
            ...$request->occupationAttributes(),
            'is_custom'  => true,
            'created_by' => $request->user()->id,
        ]);

        $character->occupation_id = $occupation->id;
        $character->occupation    = $occupation->name;
        $character->wizard_step   = max($character->wizard_step, 3);
        $character->save();

        return to_route('character.create')
            ->with('success', "“{$occupation->name}” has been added to the occupations everyone can choose from.");
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

    /**
     * The era a new investigator is built for: that of the campaign their
     * group is playing, falling back to the group's default while it has none
     * and to the Twenties while they are ungrouped.
     */
    /**
     * The draft the wizard carries on with — the most recent one that is in
     * the campaign the group is playing.
     *
     * A draft left half-built in a finished campaign is not something to pick
     * up again: whoever arrives at the wizard is here to make someone for the
     * game that is on, and starts them from the profile step. While the group
     * plays nothing at all there is no campaign to be outside of, so the most
     * recent draft is resumed as it always was — otherwise a fresh draft would
     * be stranded the moment the page reloaded.
     */
    private function resumableDraft(User $user): ?Character
    {
        $drafts = Character::query()
            ->where('user_id', $user->id)
            ->where('status', CharacterStatus::Draft)
            // `in_active_game` reads both of these, and filtering happens in
            // PHP because it is an appended attribute rather than a column.
            ->with(['group', 'games'])
            ->latest('updated_at')
            ->orderByDesc('id')
            ->get();

        if ($user->group?->active_game_id === null) {
            return $drafts->first();
        }

        return $drafts->first(fn (Character $draft): bool => $draft->in_active_game);
    }

    private function resolveEra(User $user): Era
    {
        return $user->group?->activeGame?->era ?? $user->group?->era ?? Era::Twenties;
    }
}
