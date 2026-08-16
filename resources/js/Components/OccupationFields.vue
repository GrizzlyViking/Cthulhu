<script setup>
/*
 * Everything an occupation is, as one set of fields.
 *
 * The wizard's "Write your own occupation" modal and the admin Occupations page
 * are the same form — a player contributing one and an admin tidying it are
 * editing the same shared row — so the fields live here and both mount it.
 *
 * `form` is an Inertia useForm and is written to directly; the parent owns the
 * submit and the route.
 */
import { computed } from 'vue';
import EraPicker from '@/Components/EraPicker.vue';
import SkillMultiSelect from '@/Components/SkillMultiSelect.vue';
import { formulaLabel, formulaPool } from '@/Components/occupationForm.js';
import { PlusIcon, TrashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    form: { type: Object, required: true },
    /** [{ value, label, short }] as the server sends them. */
    eras: { type: Array, required: true },
    /** [{ slug, label }] — the whole skill list. */
    skillOptions: { type: Array, required: true },
    /** { columnName: 'ABBR' } — what a formula component may draw on. */
    characteristics: { type: Object, required: true },
    /**
     * The characteristics of the investigator being made, when there is one:
     * the pool the formula would give them is worth seeing before saving.
     */
    stats: { type: Object, default: null },
});

const characteristicList = computed(() =>
    Object.entries(props.characteristics).map(([key, abbr]) => ({ key, abbr }))
);

const preview = computed(() => formulaLabel(props.form.skill_points_formula, props.characteristics));

const pool = computed(() =>
    props.stats ? formulaPool(props.form.skill_points_formula, props.stats) : null
);

const addComponent = () => {
    props.form.skill_points_formula = [
        ...props.form.skill_points_formula,
        { multiplier: 2, options: [] },
    ];
};

const removeComponent = (index) => {
    props.form.skill_points_formula = props.form.skill_points_formula.filter((_, i) => i !== index);
};

/**
 * A component may allow more than one characteristic ("STR or DEX"), in which
 * case the highest of them counts.
 */
const toggleCharacteristic = (index, key) => {
    props.form.skill_points_formula = props.form.skill_points_formula.map((component, i) => {
        if (i !== index) return component;

        const options = component.options.includes(key)
            ? component.options.filter((entry) => entry !== key)
            : [...component.options, key];

        return { ...component, options };
    });
};

const isChosen = (index, key) =>
    (props.form.skill_points_formula[index]?.options ?? []).includes(key);

const addChoice = () => {
    props.form.choices = [...props.form.choices, { count: 1, options: [], label: '' }];
};

const removeChoice = (index) => {
    props.form.choices = props.form.choices.filter((_, i) => i !== index);
};

const setChoiceOptions = (index, options) => {
    props.form.choices = props.form.choices.map((choice, i) =>
        i === index ? { ...choice, options } : choice
    );
};

/** Errors on nested fields arrive as `choices.0.options`, so ask by path. */
const errorAt = (path) => props.form.errors[path] ?? '';
</script>

<template>
    <div class="flex flex-col gap-5">
        <div>
            <label for="occupation-name" class="field-label">Name</label>
            <input
                id="occupation-name"
                v-model="form.name"
                type="text"
                class="field mt-1"
                placeholder="Lighthouse Keeper"
                required
            />
            <p v-if="form.errors.name" class="field-error">{{ form.errors.name }}</p>
        </div>

        <div>
            <label for="occupation-description" class="field-label">Description</label>
            <textarea
                id="occupation-description"
                v-model="form.description"
                rows="3"
                class="field mt-1"
                placeholder="What this person does, and what kind of investigator it makes."
                required
            ></textarea>
            <p class="field-hint">
                Everyone choosing an occupation reads this, so write it for the next player rather
                than for your own sheet.
            </p>
            <p v-if="form.errors.description" class="field-error">{{ form.errors.description }}</p>
        </div>

        <EraPicker
            v-model="form.eras"
            :eras="eras"
            hint="Which eras the occupation makes sense in. Most make sense in both — a Computer
                  Programmer does not belong in 1925, but an Antiquarian belongs anywhere."
            :error="form.errors.eras"
        />

        <!-- Skill points ------------------------------------------------- -->
        <fieldset>
            <legend class="field-label">Occupation skill points</legend>
            <p class="field-hint">
                The pool of points spent on this occupation's skills. Each part multiplies one
                characteristic; tick more than one and the highest of them counts, which is how the
                book writes “EDU × 2 + STR or DEX × 2”.
            </p>

            <div class="mt-2 flex flex-col gap-3">
                <div
                    v-for="(component, index) in form.skill_points_formula"
                    :key="index"
                    class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-parchment-300"
                >
                    <div class="flex items-center justify-between gap-3">
                        <label class="flex items-center gap-2 text-sm text-cthulhu-green-900">
                            <span class="eyebrow">Multiplier ×</span>
                            <input
                                v-model.number="component.multiplier"
                                type="number"
                                min="1"
                                max="4"
                                class="field tabular w-20"
                            />
                        </label>

                        <button
                            v-if="form.skill_points_formula.length > 1"
                            type="button"
                            class="btn-ghost btn-sm"
                            @click="removeComponent(index)"
                        >
                            <TrashIcon class="size-4" aria-hidden="true" />
                            Remove
                        </button>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-2">
                        <label
                            v-for="characteristic in characteristicList"
                            :key="characteristic.key"
                            class="inline-flex cursor-pointer items-center gap-1.5 text-sm text-cthulhu-green-900"
                        >
                            <input
                                type="checkbox"
                                class="size-4 rounded border-parchment-400 text-cthulhu-green-600 focus:ring-cthulhu-green-600"
                                :checked="isChosen(index, characteristic.key)"
                                @change="toggleCharacteristic(index, characteristic.key)"
                            />
                            {{ characteristic.abbr }}
                        </label>
                    </div>

                    <p v-if="errorAt(`skill_points_formula.${index}.options`)" class="field-error">
                        {{ errorAt(`skill_points_formula.${index}.options`) }}
                    </p>
                    <p v-if="errorAt(`skill_points_formula.${index}.multiplier`)" class="field-error">
                        {{ errorAt(`skill_points_formula.${index}.multiplier`) }}
                    </p>
                </div>
            </div>

            <button
                v-if="form.skill_points_formula.length < 3"
                type="button"
                class="btn-secondary btn-sm mt-2"
                @click="addComponent"
            >
                <PlusIcon class="size-4" aria-hidden="true" />
                Add a part
            </button>

            <p v-if="preview" class="mt-2 text-sm text-cthulhu-green-800">
                <span class="eyebrow">Formula</span>
                <span class="ms-2 font-semibold">{{ preview }}</span>
                <span v-if="pool !== null" class="ms-2 chip-brass tabular">{{ pool }} pts for you</span>
            </p>
            <p v-if="form.errors.skill_points_formula" class="field-error">
                {{ form.errors.skill_points_formula }}
            </p>
        </fieldset>

        <!-- Credit Rating ------------------------------------------------ -->
        <fieldset>
            <legend class="field-label">Credit Rating range</legend>
            <p class="field-hint">
                How well the work pays. Credit Rating is bought out of the occupation pool and has to
                land inside this range, so it decides what the investigator can afford.
            </p>

            <div class="mt-1 grid grid-cols-2 gap-4">
                <div>
                    <label for="occupation-cr-min" class="field-hint">Minimum</label>
                    <input
                        id="occupation-cr-min"
                        v-model.number="form.credit_rating_min"
                        type="number"
                        min="0"
                        max="99"
                        class="field mt-1 tabular"
                        required
                    />
                    <p v-if="form.errors.credit_rating_min" class="field-error">
                        {{ form.errors.credit_rating_min }}
                    </p>
                </div>

                <div>
                    <label for="occupation-cr-max" class="field-hint">Maximum</label>
                    <input
                        id="occupation-cr-max"
                        v-model.number="form.credit_rating_max"
                        type="number"
                        min="0"
                        max="99"
                        class="field mt-1 tabular"
                        required
                    />
                    <p v-if="form.errors.credit_rating_max" class="field-error">
                        {{ form.errors.credit_rating_max }}
                    </p>
                </div>
            </div>
        </fieldset>

        <!-- Occupation skills -------------------------------------------- -->
        <SkillMultiSelect
            v-model="form.skills"
            :options="skillOptions"
            label="Occupation skills"
            hint="The skills this work trains. Occupation points may only be spent on these, so it is
                  the heart of what the occupation is."
            :error="form.errors.skills"
        />

        <!-- Choices ------------------------------------------------------ -->
        <fieldset>
            <legend class="field-label">Choices</legend>
            <p class="field-hint">
                For skills that come as “one interpersonal skill” or “a firearms specialisation”:
                name a set and how many of it the player picks.
            </p>

            <div v-if="form.choices.length" class="mt-2 flex flex-col gap-3">
                <div
                    v-for="(choice, index) in form.choices"
                    :key="index"
                    class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-parchment-300"
                >
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <label class="flex items-center gap-2 text-sm text-cthulhu-green-900">
                            <span class="eyebrow">Pick</span>
                            <input
                                v-model.number="choice.count"
                                type="number"
                                min="1"
                                max="4"
                                class="field tabular w-20"
                            />
                        </label>

                        <button type="button" class="btn-ghost btn-sm" @click="removeChoice(index)">
                            <TrashIcon class="size-4" aria-hidden="true" />
                            Remove
                        </button>
                    </div>

                    <div class="mt-2">
                        <label class="field-hint" :for="`choice-label-${index}`">
                            How to describe it
                        </label>
                        <input
                            :id="`choice-label-${index}`"
                            v-model="choice.label"
                            type="text"
                            class="field mt-1"
                            placeholder="one interpersonal skill"
                        />
                    </div>

                    <div class="mt-3">
                        <SkillMultiSelect
                            :model-value="choice.options"
                            :options="skillOptions"
                            label="Chosen from"
                            :error="errorAt(`choices.${index}.options`)"
                            @update:model-value="setChoiceOptions(index, $event)"
                        />
                    </div>

                    <p v-if="errorAt(`choices.${index}.count`)" class="field-error">
                        {{ errorAt(`choices.${index}.count`) }}
                    </p>
                </div>
            </div>

            <button
                v-if="form.choices.length < 4"
                type="button"
                class="btn-secondary btn-sm mt-2"
                @click="addChoice"
            >
                <PlusIcon class="size-4" aria-hidden="true" />
                Add a choice
            </button>
        </fieldset>

        <!-- Free slots --------------------------------------------------- -->
        <fieldset>
            <legend class="field-label">Free slots</legend>
            <p class="field-hint">
                The book's “any one other skill as a personal or era specialty”. Occupation points
                may go on this many skills outside the list above.
            </p>

            <div class="mt-1 grid grid-cols-3 gap-4">
                <div>
                    <label for="occupation-any-count" class="field-hint">How many</label>
                    <input
                        id="occupation-any-count"
                        v-model.number="form.any_count"
                        type="number"
                        min="0"
                        max="4"
                        class="field mt-1 tabular"
                    />
                    <p v-if="form.errors.any_count" class="field-error">{{ form.errors.any_count }}</p>
                </div>

                <div class="col-span-2">
                    <label for="occupation-any-label" class="field-hint">How to describe them</label>
                    <input
                        id="occupation-any-label"
                        v-model="form.any_label"
                        type="text"
                        class="field mt-1"
                        placeholder="any one other skill as a personal specialty"
                        :disabled="!form.any_count"
                    />
                </div>
            </div>
        </fieldset>
    </div>
</template>
