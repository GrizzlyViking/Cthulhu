<script setup>
import { computed, ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import EraChips from '@/Components/EraChips.vue';
import OccupationFields from '@/Components/OccupationFields.vue';
import Pagination from '@/Pages/Components/Admin/Pagination.vue';
import { blankOccupationForm, occupationToForm } from '@/Components/occupationForm.js';
import { ArrowUturnLeftIcon, PencilSquareIcon, PlusIcon, TrashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    occupations: { type: Object, required: true },
    eras: { type: Array, required: true },
    skillOptions: { type: Array, required: true },
    characteristics: { type: Object, required: true },
    filters: { type: Object, required: true },
    editable: { type: Boolean, required: true },
    counts: { type: Object, required: true },
});

const search = ref(props.filters.search);
const era = ref(props.filters.era);

const allEras = props.eras.map((option) => option.value);

let debounce = null;

const reload = (overrides = {}, delay = 0) => {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
        const query = {
            search: search.value,
            era: era.value,
            trashed: props.filters.trashed,
            custom: props.filters.custom,
            ...overrides,
        };

        router.get(
            route('admin.occupations.index'),
            Object.fromEntries(Object.entries(query).filter(([, value]) => value !== '' && value !== false)),
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, delay);
};

watch(search, () => reload({}, 300));
watch(era, () => reload());

/* ------------------------------------------------------- create / edit -- */

// `editing` holds the occupation being changed, or null while creating one.
const editing = ref(null);
const showForm = ref(false);

const form = useForm(blankOccupationForm(allEras));

const openCreate = () => {
    editing.value = null;
    form.defaults(blankOccupationForm(allEras));
    form.reset();
    form.clearErrors();
    showForm.value = true;
};

const openEdit = (occupation) => {
    editing.value = occupation;
    form.defaults(occupationToForm(occupation, allEras));
    form.reset();
    form.clearErrors();
    showForm.value = true;
};

const submit = () => {
    const options = { preserveScroll: true, onSuccess: () => (showForm.value = false) };

    if (editing.value) {
        form.put(route('admin.occupations.update', { occupation: editing.value.id }), options);
    } else {
        form.post(route('admin.occupations.store'), options);
    }
};

/* ------------------------------------------------------ retire/restore -- */

const retire = (occupation) => {
    const warning = occupation.characters_count > 0
        ? `${occupation.characters_count} investigator(s) trained as a ${occupation.name}. Retiring stops it being offered — their sheets keep it, and restoring puts it back on the list. Continue?`
        : `Retire ${occupation.name}?`;

    if (confirm(warning)) {
        router.delete(route('admin.occupations.destroy', { occupation: occupation.id }), {
            preserveScroll: true,
        });
    }
};

const restore = (occupation) => {
    router.put(route('admin.occupations.restore', { id: occupation.id }), {}, { preserveScroll: true });
};

/* ---------------------------------------------------------- rendering -- */

const labelFor = (slug) =>
    props.skillOptions.find((option) => option.slug === slug)?.label ??
    slug.replace(/[-_]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());

/** The `skills` column read out as one list of phrases. */
const skillPhrases = (occupation) =>
    (occupation.skills ?? []).map((entry) =>
        typeof entry === 'string' ? labelFor(entry) : (entry.label ?? `${entry.count} skill(s)`)
    );

const emptyMessage = computed(() => {
    if (props.filters.trashed) return 'No occupation has been retired.';
    if (props.filters.search) return `No occupation matches “${props.filters.search}”.`;
    if (props.filters.custom) return 'No player has written their own occupation yet.';
    if (props.filters.era) return 'No occupation belongs to that era alone.';

    return 'There are no occupations yet.';
});
</script>

<template>
    <Head title="Occupations" />

    <AdminLayout title="Occupations">
        <section class="panel p-5 sm:p-6">
            <div class="card-marked">
                <p class="text-sm text-cthulhu-green-900">
                    <template v-if="editable">
                        Every group on this server picks from this one list, so a change here reaches
                        all of them — and the list grows from play: an occupation a player writes in
                        the wizard lands here marked <span class="font-semibold">player-written</span>.
                        Retiring one stops it being offered without touching the sheets that carry it.
                    </template>
                    <template v-else>
                        Editing is switched off on this server, because more than one group plays here
                        and this list is shared by all of them. Changes go through
                        <code class="text-xs">OccupationSeeder</code> or a migration — though players
                        may still write their own from the wizard.
                    </template>
                </p>
            </div>

            <div class="mt-5 flex flex-wrap items-center gap-3">
                <div class="min-w-0 flex-auto">
                    <label for="occupation-search" class="sr-only">Search occupations</label>
                    <input
                        id="occupation-search"
                        v-model="search"
                        type="search"
                        class="field"
                        placeholder="Search by name or description"
                    />
                </div>

                <div>
                    <label for="occupation-era" class="sr-only">Era</label>
                    <select id="occupation-era" v-model="era" class="field">
                        <option value="">All eras</option>
                        <option v-for="option in eras" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>

                <!-- What the players have contributed, for tidying or pruning. -->
                <button
                    type="button"
                    class="btn-sm"
                    :class="filters.custom ? 'btn-primary' : 'btn-secondary'"
                    @click="reload({ custom: !filters.custom })"
                >
                    Player-written
                    <span class="tabular">({{ counts.custom }})</span>
                </button>

                <button
                    v-if="editable"
                    type="button"
                    class="btn-sm"
                    :class="filters.trashed ? 'btn-primary' : 'btn-secondary'"
                    @click="reload({ trashed: !filters.trashed })"
                >
                    Retired
                    <span class="tabular">({{ counts.retired }})</span>
                </button>

                <button v-if="editable && !filters.trashed" type="button" class="btn-primary btn-sm" @click="openCreate">
                    <PlusIcon class="size-4" aria-hidden="true" />
                    New occupation
                </button>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-parchment-300">
                            <th scope="col" class="py-2 pr-3 eyebrow">Occupation</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Era</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Skill points</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Credit Rating</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Occupation skills</th>
                            <th scope="col" class="py-2 pr-3 eyebrow text-right">Sheets</th>
                            <th v-if="editable" scope="col" class="py-2 eyebrow text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-parchment-300">
                        <tr v-for="occupation in occupations.data" :key="occupation.id">
                            <td class="py-2.5 pr-3 align-top">
                                <p class="font-semibold text-cthulhu-green-900">{{ occupation.name }}</p>
                                <p v-if="occupation.is_custom" class="mt-0.5">
                                    <span class="chip">
                                        Player-written<template v-if="occupation.creator_name">
                                            · {{ occupation.creator_name }}</template>
                                    </span>
                                </p>
                                <p class="mt-0.5 text-xs text-cthulhu-green-700">
                                    {{ occupation.description }}
                                </p>
                            </td>
                            <td class="py-2.5 pr-3 align-top">
                                <EraChips :eras="occupation.eras" :options="eras" always />
                            </td>
                            <td class="py-2.5 pr-3 align-top text-cthulhu-green-800">
                                {{ occupation.formula_label }}
                            </td>
                            <td class="py-2.5 pr-3 align-top tabular text-cthulhu-green-800">
                                {{ occupation.credit_rating_min }}–{{ occupation.credit_rating_max }}
                            </td>
                            <td class="py-2.5 pr-3 align-top">
                                <ul class="flex flex-wrap gap-1">
                                    <li
                                        v-for="(phrase, index) in skillPhrases(occupation)"
                                        :key="index"
                                        class="chip"
                                    >
                                        {{ phrase }}
                                    </li>
                                </ul>
                            </td>
                            <td class="py-2.5 pr-3 align-top text-right tabular text-cthulhu-green-700">
                                {{ occupation.characters_count }}
                            </td>
                            <td v-if="editable" class="py-2.5 align-top text-right">
                                <div class="flex justify-end gap-1.5">
                                    <button
                                        v-if="filters.trashed"
                                        type="button"
                                        class="btn-secondary btn-sm"
                                        @click="restore(occupation)"
                                    >
                                        <ArrowUturnLeftIcon class="size-4" aria-hidden="true" />
                                        Restore
                                    </button>
                                    <template v-else>
                                        <button type="button" class="btn-secondary btn-sm" @click="openEdit(occupation)">
                                            <PencilSquareIcon class="size-4" aria-hidden="true" />
                                            Edit
                                        </button>
                                        <button type="button" class="btn-danger btn-sm" @click="retire(occupation)">
                                            <TrashIcon class="size-4" aria-hidden="true" />
                                            Retire
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="occupations.data.length === 0">
                            <td :colspan="editable ? 7 : 6" class="py-6 text-center text-sm text-cthulhu-green-500">
                                {{ emptyMessage }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :paginator="occupations" />
        </section>

        <!-- Create / edit -->
        <Modal :show="showForm" max-width="2xl" @close="showForm = false">
            <form class="panel flex max-h-[85vh] flex-col gap-4 overflow-y-auto p-6" @submit.prevent="submit">
                <h2 class="display text-lg text-cthulhu-green-900">
                    {{ editing ? `Edit ${editing.name}` : 'New occupation' }}
                </h2>

                <OccupationFields
                    :form="form"
                    :eras="eras"
                    :skill-options="skillOptions"
                    :characteristics="characteristics"
                />

                <div class="flex items-center justify-end gap-2">
                    <button type="button" class="btn-ghost" @click="showForm = false">Cancel</button>
                    <button type="submit" class="btn-primary" :disabled="form.processing">
                        {{ editing ? 'Save' : 'Add occupation' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AdminLayout>
</template>
