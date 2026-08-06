<script setup>
import { ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Pages/Components/Admin/Pagination.vue';
import { ArrowUturnLeftIcon, PencilSquareIcon, PlusIcon, TrashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    weapons: { type: Object, required: true },
    categories: { type: Array, required: true },
    eras: { type: Array, required: true },
    skills: { type: Array, required: true },
    filters: { type: Object, required: true },
    editable: { type: Boolean, required: true },
    counts: { type: Object, required: true },
});

const search = ref(props.filters.search);
const category = ref(props.filters.category);
const era = ref(props.filters.era);

let debounce = null;

const reload = (overrides = {}, delay = 0) => {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
        const query = {
            search: search.value,
            category: category.value,
            era: era.value,
            trashed: props.filters.trashed,
            ...overrides,
        };

        router.get(
            route('admin.weapons.index'),
            Object.fromEntries(Object.entries(query).filter(([, value]) => value !== '' && value !== false)),
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, delay);
};

watch(search, () => reload({}, 300));
watch([category, era], () => reload());

// ---- Create / edit ------------------------------------------------------

const blank = {
    name: '',
    category: props.categories[0],
    skill: props.skills[0]?.slug ?? '',
    damage: '',
    base_range: '',
    uses_per_round: '1',
    bullets_in_mag: '',
    cost: '',
    malfunction: '',
    era: '',
    impale: false,
};

const editing = ref(null);
const showForm = ref(false);

const form = useForm({ ...blank });

const openForm = (weapon) => {
    editing.value = weapon;
    form.defaults(weapon === null ? { ...blank } : {
        name: weapon.name,
        category: weapon.category ?? props.categories[0],
        skill: weapon.skill,
        damage: weapon.damage,
        base_range: weapon.base_range,
        uses_per_round: weapon.uses_per_round,
        bullets_in_mag: weapon.bullets_in_mag ?? '',
        cost: weapon.cost,
        malfunction: weapon.malfunction ?? '',
        era: weapon.era ?? '',
        impale: weapon.impale,
    });
    form.reset();
    form.clearErrors();
    showForm.value = true;
};

const submit = () => {
    const options = { preserveScroll: true, onSuccess: () => (showForm.value = false) };

    if (editing.value) {
        form.put(route('admin.weapons.update', { weapon: editing.value.id }), options);
    } else {
        form.post(route('admin.weapons.store'), options);
    }
};

// ---- Retire / restore ---------------------------------------------------

const retire = (weapon) => {
    if (confirm(`Retire ${weapon.name}? It leaves every sheet carrying it, ammunition and all, and comes back if you restore it.`)) {
        router.delete(route('admin.weapons.destroy', { weapon: weapon.id }), { preserveScroll: true });
    }
};

const restore = (weapon) => {
    router.put(route('admin.weapons.restore', { id: weapon.id }), {}, { preserveScroll: true });
};
</script>

<template>
    <Head title="Weapons" />

    <AdminLayout title="Weapons">
        <template #actions>
            <button v-if="editable && !filters.trashed" type="button" class="btn-primary btn-sm" @click="openForm(null)">
                <PlusIcon class="size-4" aria-hidden="true" />
                New weapon
            </button>
        </template>

        <section class="panel p-5 sm:p-6">
            <div class="card-marked">
                <p class="text-sm text-cthulhu-green-900">
                    <template v-if="editable">
                        This is the Investigator Handbook weapons table (pp. 250–254), shared by every group
                        on this server. Adding a weapon here adds it to all of them; retiring one takes it
                        off the sheets that carry it, and restoring puts it back with its ammunition.
                    </template>
                    <template v-else>
                        Editing is switched off on this server, because more than one group plays here and
                        the armoury is shared by all of them. New weapons belong in
                        <code class="text-xs">App\Misc\WeaponTable</code>, which the seeder and migrations read from.
                    </template>
                </p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div>
                    <label for="weapon-search" class="sr-only">Search weapons</label>
                    <input
                        id="weapon-search"
                        v-model="search"
                        type="search"
                        class="field"
                        placeholder="Search by name, skill or damage"
                    />
                </div>

                <div>
                    <label for="weapon-category" class="sr-only">Category</label>
                    <select id="weapon-category" v-model="category" class="field">
                        <option value="">All categories</option>
                        <option v-for="option in categories" :key="option" :value="option">{{ option }}</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <label for="weapon-era" class="sr-only">Era</label>
                    <select id="weapon-era" v-model="era" class="field">
                        <option value="">All eras</option>
                        <option v-for="option in eras" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>

                    <button
                        v-if="editable"
                        type="button"
                        class="btn-sm whitespace-nowrap"
                        :class="filters.trashed ? 'btn-primary' : 'btn-secondary'"
                        @click="reload({ trashed: !filters.trashed })"
                    >
                        Retired
                        <span class="tabular">({{ counts.retired }})</span>
                    </button>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-parchment-300">
                            <th scope="col" class="py-2 pr-3 eyebrow">Weapon</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Skill</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Damage</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Range</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Attacks</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Mag</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Malf</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Cost</th>
                            <th v-if="editable" scope="col" class="py-2 eyebrow text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-parchment-300">
                        <tr v-for="weapon in weapons.data" :key="weapon.id">
                            <td class="py-2.5 pr-3">
                                <span class="font-semibold text-cthulhu-green-900">{{ weapon.name }}</span>
                                <span class="block text-xs text-cthulhu-green-500">
                                    {{ weapon.category }}<template v-if="weapon.era"> · {{ weapon.era }}</template>
                                    <template v-if="weapon.impale"> · impales</template>
                                </span>
                            </td>
                            <td class="py-2.5 pr-3 text-cthulhu-green-700">
                                {{ weapon.skills?.display_name ?? weapon.skill }}
                            </td>
                            <td class="py-2.5 pr-3 tabular text-cthulhu-green-900">{{ weapon.damage }}</td>
                            <td class="py-2.5 pr-3 tabular text-cthulhu-green-700">{{ weapon.base_range }}</td>
                            <td class="py-2.5 pr-3 tabular text-cthulhu-green-700">{{ weapon.uses_per_round }}</td>
                            <td class="py-2.5 pr-3 tabular text-cthulhu-green-700">{{ weapon.bullets_in_mag }}</td>
                            <td class="py-2.5 pr-3 tabular text-cthulhu-green-700">{{ weapon.malfunction }}</td>
                            <td class="py-2.5 pr-3 text-xs text-cthulhu-green-700">{{ weapon.cost }}</td>
                            <td v-if="editable" class="py-2.5 text-right">
                                <div class="flex justify-end gap-1.5">
                                    <button
                                        v-if="filters.trashed"
                                        type="button"
                                        class="btn-secondary btn-sm"
                                        @click="restore(weapon)"
                                    >
                                        <ArrowUturnLeftIcon class="size-4" aria-hidden="true" />
                                        Restore
                                    </button>
                                    <template v-else>
                                        <button type="button" class="btn-secondary btn-sm" @click="openForm(weapon)">
                                            <PencilSquareIcon class="size-4" aria-hidden="true" />
                                            Edit
                                        </button>
                                        <button type="button" class="btn-danger btn-sm" @click="retire(weapon)">
                                            <TrashIcon class="size-4" aria-hidden="true" />
                                            Retire
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="weapons.data.length === 0">
                            <td :colspan="editable ? 9 : 8" class="py-6 text-center text-sm text-cthulhu-green-500">
                                <template v-if="filters.trashed">No weapon has been retired.</template>
                                <template v-else>No weapon matches those filters.</template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :paginator="weapons" />
        </section>

        <!-- Create / edit -->
        <Modal :show="showForm" max-width="2xl" @close="showForm = false">
            <form class="panel flex flex-col gap-4 p-6" @submit.prevent="submit">
                <h2 class="display text-lg text-cthulhu-green-900">
                    {{ editing ? `Edit ${editing.name}` : 'New weapon' }}
                </h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="w-name" class="field-label">Name</label>
                        <input id="w-name" v-model="form.name" type="text" class="field mt-1" required />
                        <p v-if="form.errors.name" class="field-error">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label for="w-category" class="field-label">Category</label>
                        <select id="w-category" v-model="form.category" class="field mt-1" required>
                            <option v-for="option in categories" :key="option" :value="option">{{ option }}</option>
                        </select>
                        <p v-if="form.errors.category" class="field-error">{{ form.errors.category }}</p>
                    </div>

                    <div>
                        <label for="w-skill" class="field-label">Skill</label>
                        <select id="w-skill" v-model="form.skill" class="field mt-1" required>
                            <option v-for="option in skills" :key="option.slug" :value="option.slug">
                                {{ option.display_name }}
                            </option>
                        </select>
                        <p v-if="form.errors.skill" class="field-error">{{ form.errors.skill }}</p>
                    </div>

                    <div>
                        <label for="w-damage" class="field-label">Damage</label>
                        <input id="w-damage" v-model="form.damage" type="text" class="field mt-1" placeholder="1D10" required />
                        <p v-if="form.errors.damage" class="field-error">{{ form.errors.damage }}</p>
                    </div>

                    <div>
                        <label for="w-range" class="field-label">Base range</label>
                        <input id="w-range" v-model="form.base_range" type="text" class="field mt-1" placeholder="15 yards" required />
                        <p v-if="form.errors.base_range" class="field-error">{{ form.errors.base_range }}</p>
                    </div>

                    <div>
                        <label for="w-uses" class="field-label">Attacks per round</label>
                        <input id="w-uses" v-model="form.uses_per_round" type="text" class="field mt-1" placeholder="1 (3)" required />
                        <p v-if="form.errors.uses_per_round" class="field-error">{{ form.errors.uses_per_round }}</p>
                    </div>

                    <div>
                        <label for="w-mag" class="field-label">Bullets in gun (mag)</label>
                        <input id="w-mag" v-model="form.bullets_in_mag" type="text" class="field mt-1" placeholder="6" />
                        <p class="field-hint">The book's free text — “6”, “20/30/32”, “Varies”. The magazine size is read out of it.</p>
                        <p v-if="form.errors.bullets_in_mag" class="field-error">{{ form.errors.bullets_in_mag }}</p>
                    </div>

                    <div>
                        <label for="w-cost" class="field-label">Cost</label>
                        <input id="w-cost" v-model="form.cost" type="text" class="field mt-1" placeholder="$25/$190" required />
                        <p class="field-hint">The 1920s and modern prices as the book prints them.</p>
                        <p v-if="form.errors.cost" class="field-error">{{ form.errors.cost }}</p>
                    </div>

                    <div>
                        <label for="w-malf" class="field-label">Malfunction</label>
                        <input id="w-malf" v-model="form.malfunction" type="text" class="field mt-1" placeholder="100" />
                        <p v-if="form.errors.malfunction" class="field-error">{{ form.errors.malfunction }}</p>
                    </div>

                    <div>
                        <label for="w-era" class="field-label">Era</label>
                        <input id="w-era" v-model="form.era" type="text" class="field mt-1" placeholder="1920s, Modern" />
                        <p class="field-hint">The availability cell, verbatim: “1920s”, “1920s, Modern”, “WWII, Later”, “Rare”.</p>
                        <p v-if="form.errors.era" class="field-error">{{ form.errors.era }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="flex cursor-pointer items-center gap-2.5">
                            <input
                                v-model="form.impale"
                                type="checkbox"
                                class="size-4 rounded border-parchment-400 bg-parchment-50 text-cthulhu-green-800 focus:ring-cthulhu-green-600"
                            />
                            <span class="text-sm font-medium text-cthulhu-green-900">Impales</span>
                        </label>
                        <p v-if="form.errors.impale" class="field-error">{{ form.errors.impale }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" class="btn-ghost" @click="showForm = false">Cancel</button>
                    <button type="submit" class="btn-primary" :disabled="form.processing">
                        {{ editing ? 'Save' : 'Add weapon' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AdminLayout>
</template>
