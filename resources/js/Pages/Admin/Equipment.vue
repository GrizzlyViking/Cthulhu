<script setup>
import { ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import EraChips from '@/Components/EraChips.vue';
import EraPicker from '@/Components/EraPicker.vue';
import Pagination from '@/Pages/Components/Admin/Pagination.vue';
import { ArrowUturnLeftIcon, PencilSquareIcon, PlusIcon, TrashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    items: { type: Object, required: true },
    sections: { type: Array, required: true },
    eras: { type: Array, required: true },
    locations: { type: Array, required: true },
    filters: { type: Object, required: true },
    editable: { type: Boolean, required: true },
    counts: { type: Object, required: true },
});

const search = ref(props.filters.search);
const section = ref(props.filters.section);
const era = ref(props.filters.era);

const allEras = props.eras.map((option) => option.value);

let debounce = null;

const reload = (overrides = {}, delay = 0) => {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
        const query = {
            search: search.value,
            section: section.value,
            era: era.value,
            trashed: props.filters.trashed,
            custom: props.filters.custom,
            ...overrides,
        };

        router.get(
            route('admin.equipment.index'),
            Object.fromEntries(Object.entries(query).filter(([, v]) => v !== '' && v !== false)),
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, delay);
};

watch(search, () => reload({}, 300));
watch([section, era], () => reload());

// ---- Catalogue item form ------------------------------------------------

const editing = ref(null);
const showForm = ref(false);

const blank = { name: '', section: '', cost: '', eras: [...allEras] };

const form = useForm({ ...blank });

const openForm = (item) => {
    editing.value = item;
    form.defaults(item === null ? { ...blank } : {
        name: item.name,
        section: item.section ?? '',
        cost: item.cost ?? '',
        eras: [...(item.eras ?? allEras)],
    });
    form.reset();
    form.clearErrors();
    showForm.value = true;
};

const submit = () => {
    const options = { preserveScroll: true, onSuccess: () => (showForm.value = false) };

    if (editing.value) {
        form.put(route('admin.equipment.update', { equipment: editing.value.slug }), options);
    } else {
        form.post(route('admin.equipment.store'), options);
    }
};

const retire = (item) => {
    const warning = item.characters_count > 0
        ? `${item.name} is on ${item.characters_count} sheet(s). Retiring takes it off all of them; restoring puts it back where each investigator kept it. Continue?`
        : `Retire ${item.name}?`;

    if (confirm(warning)) {
        router.delete(route('admin.equipment.destroy', { equipment: item.slug }), { preserveScroll: true });
    }
};

const restore = (item) => {
    router.put(route('admin.equipment.restore', { slug: item.slug }), {}, { preserveScroll: true });
};

// ---- Storage locations --------------------------------------------------

const locationForm = useForm({ name: '' });

const addLocation = () => {
    locationForm.post(route('admin.locations.store'), {
        preserveScroll: true,
        onSuccess: () => locationForm.reset(),
    });
};

const removeLocation = (location) => {
    if (confirm(`Remove “${location.name}”?`)) {
        router.delete(route('admin.locations.destroy', { location: location.slug }), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Equipment" />

    <AdminLayout title="Equipment">
        <template #actions>
            <button v-if="editable && !filters.trashed" type="button" class="btn-primary btn-sm" @click="openForm(null)">
                <PlusIcon class="size-4" aria-hidden="true" />
                New item
            </button>
        </template>

        <!-- Catalogue -->
        <section class="panel p-5 sm:p-6">
            <div class="card-marked">
                <p class="text-sm text-cthulhu-green-900">
                    <template v-if="editable">
                        The 1920s equipment lists from the Investigator Handbook (pp. 238–246), shared by every
                        group. Anything a player typed that the catalogue did not have lands here flagged
                        <em>added by players</em> — filter to those to tidy up after a session.
                    </template>
                    <template v-else>
                        Editing is switched off on this server, because more than one group plays here and this
                        catalogue is shared by all of them. New items belong in
                        <code class="text-xs">App\Misc\EquipmentTable</code>.
                    </template>
                </p>
            </div>

            <div class="mt-5 flex flex-wrap items-center gap-3">
                <div class="min-w-0 flex-auto">
                    <label for="eq-search" class="sr-only">Search equipment</label>
                    <input id="eq-search" v-model="search" type="search" class="field" placeholder="Search by name or section" />
                </div>

                <div>
                    <label for="eq-section" class="sr-only">Section</label>
                    <select id="eq-section" v-model="section" class="field w-auto">
                        <option value="">All sections</option>
                        <option v-for="option in sections" :key="option" :value="option">{{ option }}</option>
                    </select>
                </div>

                <div>
                    <label for="eq-era" class="sr-only">Era</label>
                    <select id="eq-era" v-model="era" class="field w-auto">
                        <option value="">All eras</option>
                        <option v-for="option in eras" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>

                <button
                    type="button"
                    class="btn-sm whitespace-nowrap"
                    :class="filters.custom ? 'btn-primary' : 'btn-secondary'"
                    @click="reload({ custom: !filters.custom })"
                >
                    Added by players <span class="tabular">({{ counts.custom }})</span>
                </button>

                <button
                    v-if="editable"
                    type="button"
                    class="btn-sm whitespace-nowrap"
                    :class="filters.trashed ? 'btn-primary' : 'btn-secondary'"
                    @click="reload({ trashed: !filters.trashed })"
                >
                    Retired <span class="tabular">({{ counts.retired }})</span>
                </button>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-parchment-300">
                            <th scope="col" class="py-2 pr-3 eyebrow">Item</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Section</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Era</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Cost</th>
                            <th scope="col" class="py-2 pr-3 eyebrow text-right">On sheets</th>
                            <th v-if="editable" scope="col" class="py-2 eyebrow text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-parchment-300">
                        <tr v-for="item in items.data" :key="item.id">
                            <td class="py-2.5 pr-3">
                                <span class="font-semibold text-cthulhu-green-900">{{ item.name }}</span>
                                <span v-if="item.is_custom" class="chip-brass ml-2">Added by a player</span>
                            </td>
                            <td class="py-2.5 pr-3 text-cthulhu-green-700">{{ item.section ?? '—' }}</td>
                            <td class="py-2.5 pr-3">
                                <EraChips :eras="item.eras" :options="eras" always />
                            </td>
                            <td class="py-2.5 pr-3 tabular text-cthulhu-green-700">{{ item.cost ?? '—' }}</td>
                            <td class="py-2.5 pr-3 text-right tabular text-cthulhu-green-700">{{ item.characters_count }}</td>
                            <td v-if="editable" class="py-2.5 text-right">
                                <div class="flex justify-end gap-1.5">
                                    <button v-if="filters.trashed" type="button" class="btn-secondary btn-sm" @click="restore(item)">
                                        <ArrowUturnLeftIcon class="size-4" aria-hidden="true" />
                                        Restore
                                    </button>
                                    <template v-else>
                                        <button type="button" class="btn-secondary btn-sm" @click="openForm(item)">
                                            <PencilSquareIcon class="size-4" aria-hidden="true" />
                                            Edit
                                        </button>
                                        <button type="button" class="btn-danger btn-sm" @click="retire(item)">
                                            <TrashIcon class="size-4" aria-hidden="true" />
                                            Retire
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="items.data.length === 0">
                            <td :colspan="editable ? 6 : 5" class="py-6 text-center text-sm text-cthulhu-green-500">
                                Nothing matches those filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :paginator="items" />
        </section>

        <!-- Storage locations -->
        <section class="panel p-5 sm:p-6">
            <h2 class="display text-lg text-cthulhu-green-900">Places to keep things</h2>
            <p class="field-hint">
                Offered on every character sheet. Players can add their own from the Equipment tab.
            </p>

            <form v-if="editable" class="mt-4 flex flex-wrap items-start gap-3" @submit.prevent="addLocation">
                <div class="min-w-0 flex-auto">
                    <label for="new-place" class="sr-only">Name</label>
                    <input id="new-place" v-model="locationForm.name" type="text" class="field" placeholder="Saddlebag" required />
                    <p v-if="locationForm.errors.name" class="field-error">{{ locationForm.errors.name }}</p>
                </div>
                <button type="submit" class="btn-primary" :disabled="locationForm.processing">Add place</button>
            </form>

            <ul role="list" class="mt-4 flex flex-col gap-2">
                <li
                    v-for="location in locations"
                    :key="location.id"
                    class="flex flex-wrap items-center justify-between gap-3 card"
                >
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold text-cthulhu-green-900">{{ location.name }}</p>
                        <p class="text-xs text-cthulhu-green-500 tabular">
                            {{ location.equipables_count }} thing{{ location.equipables_count === 1 ? '' : 's' }} kept here
                        </p>
                    </div>
                    <button
                        v-if="editable"
                        type="button"
                        class="btn-danger btn-sm"
                        :disabled="location.equipables_count > 0"
                        :title="location.equipables_count > 0 ? 'Move what is in it first' : ''"
                        @click="removeLocation(location)"
                    >
                        <TrashIcon class="size-4" aria-hidden="true" />
                        Remove
                    </button>
                </li>
            </ul>
        </section>

        <!-- Create / edit -->
        <Modal :show="showForm" max-width="xl" @close="showForm = false">
            <form class="panel flex flex-col gap-4 p-6" @submit.prevent="submit">
                <h2 class="display text-lg text-cthulhu-green-900">
                    {{ editing ? `Edit ${editing.name}` : 'New equipment' }}
                </h2>

                <div>
                    <label for="eq-name" class="field-label">Name</label>
                    <input id="eq-name" v-model="form.name" type="text" class="field mt-1" required />
                    <p v-if="form.errors.name" class="field-error">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="eq-form-section" class="field-label">Section</label>
                    <input
                        id="eq-form-section"
                        v-model="form.section"
                        type="text"
                        class="field mt-1"
                        list="equipment-sections"
                        placeholder="Leave empty for personal effects"
                    />
                    <datalist id="equipment-sections">
                        <option v-for="option in sections" :key="option" :value="option"></option>
                    </datalist>
                    <p v-if="form.errors.section" class="field-error">{{ form.errors.section }}</p>
                </div>

                <div>
                    <label for="eq-cost" class="field-label">Cost</label>
                    <input id="eq-cost" v-model="form.cost" type="text" class="field mt-1" placeholder="$1.35" />
                    <p class="field-hint">The book's cell verbatim — ranges and cent signs are fine.</p>
                    <p v-if="form.errors.cost" class="field-error">{{ form.errors.cost }}</p>
                </div>

                <EraPicker
                    v-model="form.eras"
                    :eras="eras"
                    legend="Offered to"
                    hint="Which tables can buy it. The catalogue is the 1920s lists, but most of what is in
                          it — rope, a compass, a first aid case — would serve a modern investigator just as
                          well; untick Modern Day for the things that would not."
                    :error="form.errors.eras"
                />

                <div class="flex items-center justify-end gap-2">
                    <button type="button" class="btn-ghost" @click="showForm = false">Cancel</button>
                    <button type="submit" class="btn-primary" :disabled="form.processing">
                        {{ editing ? 'Save' : 'Add item' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AdminLayout>
</template>
