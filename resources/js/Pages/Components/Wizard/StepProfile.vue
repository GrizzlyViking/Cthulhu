<script setup>
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    draft: { type: Object, default: null },
});

const emit = defineEmits(['advance']);

const page = usePage();

const genders = ['Male', 'Female', 'Other'];

const form = useForm({
    name: props.draft?.name ?? '',
    gender: props.draft?.gender ?? '',
    age: props.draft?.age ?? null,
    residence: props.draft?.residence ?? '',
    birthplace: props.draft?.birthplace ?? '',
});

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => emit('advance'),
    };

    if (props.draft) {
        form.put(route('character.wizard.profile', { character: props.draft.slug }), options);
    } else {
        form.post(route('character.wizard.store'), options);
    }
};

const inputClass =
    'block w-full rounded-md border-0 py-1.5 text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 placeholder:text-cthulhu-green-500 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600 sm:text-sm sm:leading-6';
const labelClass = 'block text-sm font-medium leading-6 text-cthulhu-green-900';
</script>

<template>
    <div class="panel p-4 sm:p-6">
        <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
            Who is your investigator?
        </h2>
        <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
            Before rolling any dice, talk to your Keeper: what era, what location, what premise?
            The more strongly you tie your investigator to the scenario — a debt, a missing
            colleague, a murdered friend — the better the game will be.
        </p>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="wizard-name" :class="labelClass">Full name</label>
                <div class="mt-2">
                    <input id="wizard-name" type="text" v-model="form.name" :class="inputClass" />
                    <p v-if="page.props.errors.name" class="mt-1 text-xs text-cthulhu-blood-400">{{ page.props.errors.name }}</p>
                </div>
            </div>

            <div>
                <label for="wizard-gender" :class="labelClass">Gender</label>
                <div class="mt-2">
                    <select id="wizard-gender" v-model="form.gender" :class="inputClass">
                        <option value="" disabled>choose…</option>
                        <option v-for="gender in genders" :key="gender" :value="gender">{{ gender }}</option>
                    </select>
                    <p v-if="page.props.errors.gender" class="mt-1 text-xs text-cthulhu-blood-400">{{ page.props.errors.gender }}</p>
                </div>
            </div>

            <div>
                <label for="wizard-age" :class="labelClass">Age (15–90)</label>
                <div class="mt-2">
                    <input
                        id="wizard-age"
                        type="number"
                        v-model.number="form.age"
                        inputmode="numeric"
                        min="15"
                        max="90"
                        :class="inputClass"
                    />
                    <p class="mt-1 text-xs text-cthulhu-green-700">
                        Age drives the modifiers applied in the Characteristics step — older
                        investigators lose vigor but gain education.
                    </p>
                    <p v-if="page.props.errors.age" class="mt-1 text-xs text-cthulhu-blood-400">{{ page.props.errors.age }}</p>
                </div>
            </div>

            <div>
                <label for="wizard-residence" :class="labelClass">Residence</label>
                <div class="mt-2">
                    <input id="wizard-residence" type="text" v-model="form.residence" :class="inputClass" />
                    <p v-if="page.props.errors.residence" class="mt-1 text-xs text-cthulhu-blood-400">{{ page.props.errors.residence }}</p>
                </div>
            </div>

            <div>
                <label for="wizard-birthplace" :class="labelClass">Birthplace</label>
                <div class="mt-2">
                    <input id="wizard-birthplace" type="text" v-model="form.birthplace" :class="inputClass" />
                    <p class="mt-1 text-xs text-cthulhu-green-700">
                        Lovecraft favoured New England, but your investigator may hail from
                        anywhere in the world.
                    </p>
                    <p v-if="page.props.errors.birthplace" class="mt-1 text-xs text-cthulhu-blood-400">{{ page.props.errors.birthplace }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="rounded-md bg-cthulhu-green-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800 disabled:opacity-50"
            >
                {{ form.processing ? 'Saving…' : draft ? 'Save profile' : 'Create draft' }}
            </button>
        </div>
    </div>
</template>
