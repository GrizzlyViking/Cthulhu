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
    skills: { type: Object, required: true },
    eras: { type: Array, required: true },
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
        const query = { search: search.value, era: era.value, trashed: props.filters.trashed, ...overrides };

        router.get(
            route('admin.skills.index'),
            Object.fromEntries(Object.entries(query).filter(([, value]) => value !== '' && value !== false)),
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, delay);
};

watch(search, () => reload({}, 300));
watch(era, () => reload());

// ---- Create / edit ------------------------------------------------------

// `editing` holds the skill being changed, or null while creating one.
const editing = ref(null);
const showForm = ref(false);

const form = useForm({
    display_name: '',
    slug: '',
    description: '',
    starting_value: 1,
    order_by: null,
    eras: [...allEras],
});

const openCreate = () => {
    editing.value = null;
    form.defaults({
        display_name: '',
        slug: '',
        description: '',
        starting_value: 1,
        order_by: null,
        eras: [...allEras],
    });
    form.reset();
    form.clearErrors();
    showForm.value = true;
};

const openEdit = (skill) => {
    editing.value = skill;
    form.defaults({
        display_name: skill.display_name,
        slug: skill.slug,
        description: skill.description ?? '',
        starting_value: skill.starting_value,
        order_by: skill.order_by,
        eras: [...(skill.eras ?? allEras)],
    });
    form.reset();
    form.clearErrors();
    showForm.value = true;
};

const submit = () => {
    const options = { preserveScroll: true, onSuccess: () => (showForm.value = false) };

    if (editing.value) {
        form.put(route('admin.skills.update', { skill: editing.value.slug }), options);
    } else {
        form.post(route('admin.skills.store'), options);
    }
};

// ---- Retire / restore ---------------------------------------------------

const retire = (skill) => {
    const warning = skill.characters_count > 0
        ? `${skill.display_name} is on ${skill.characters_count} sheet(s). Retiring takes it off all of them — the values are kept and come back if you restore it. Continue?`
        : `Retire ${skill.display_name}?`;

    if (confirm(warning)) {
        router.delete(route('admin.skills.destroy', { skill: skill.slug }), { preserveScroll: true });
    }
};

const restore = (skill) => {
    router.put(route('admin.skills.restore', { slug: skill.slug }), {}, { preserveScroll: true });
};
</script>

<template>
    <Head title="Skills" />

    <AdminLayout title="Skills">
        <section class="panel p-5 sm:p-6">
            <!-- Skills are one list for the whole server, however many groups play here. -->
            <div class="card-marked">
                <p class="text-sm text-cthulhu-green-900">
                    <template v-if="editable">
                        Every group on this server draws from this one list, so a change here reaches all
                        of them. Retiring a skill takes it off the sheets that carry it without losing
                        their values — restore it and they come back.
                    </template>
                    <template v-else>
                        Editing is switched off on this server, because more than one group plays here and
                        this list is shared by all of them. Changes go through
                        <code class="text-xs">SkillSeeder</code> or a migration.
                    </template>
                </p>
            </div>

            <div class="mt-5 flex flex-wrap items-center gap-3">
                <div class="min-w-0 flex-auto">
                    <label for="skill-search" class="sr-only">Search skills</label>
                    <input
                        id="skill-search"
                        v-model="search"
                        type="search"
                        class="field"
                        placeholder="Search by name, slug or description"
                    />
                </div>

                <div>
                    <label for="skill-era" class="sr-only">Era</label>
                    <select id="skill-era" v-model="era" class="field">
                        <option value="">All eras</option>
                        <option v-for="option in eras" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>

                <!-- Retired skills are out of the way but never gone. -->
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
                    New skill
                </button>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-parchment-300">
                            <th scope="col" class="py-2 pr-3 eyebrow">Skill</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Slug</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Era</th>
                            <th scope="col" class="py-2 pr-3 eyebrow text-right">Base</th>
                            <th scope="col" class="py-2 pr-3 eyebrow text-right">On sheets</th>
                            <th scope="col" class="py-2 pr-3 eyebrow">Description</th>
                            <th v-if="editable" scope="col" class="py-2 eyebrow text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-parchment-300">
                        <tr v-for="skill in skills.data" :key="skill.id">
                            <td class="py-2.5 pr-3 font-semibold text-cthulhu-green-900">{{ skill.display_name }}</td>
                            <td class="py-2.5 pr-3 text-xs text-cthulhu-green-500">{{ skill.slug }}</td>
                            <td class="py-2.5 pr-3">
                                <EraChips :eras="skill.eras" :options="eras" always />
                            </td>
                            <td class="py-2.5 pr-3 text-right tabular text-cthulhu-green-900">
                                {{ skill.starting_value }}%
                            </td>
                            <td class="py-2.5 pr-3 text-right tabular text-cthulhu-green-700">
                                {{ skill.characters_count }}
                            </td>
                            <td class="py-2.5 pr-3 text-cthulhu-green-700">{{ skill.description }}</td>
                            <td v-if="editable" class="py-2.5 text-right">
                                <div class="flex justify-end gap-1.5">
                                    <button
                                        v-if="filters.trashed"
                                        type="button"
                                        class="btn-secondary btn-sm"
                                        @click="restore(skill)"
                                    >
                                        <ArrowUturnLeftIcon class="size-4" aria-hidden="true" />
                                        Restore
                                    </button>
                                    <template v-else>
                                        <button type="button" class="btn-secondary btn-sm" @click="openEdit(skill)">
                                            <PencilSquareIcon class="size-4" aria-hidden="true" />
                                            Edit
                                        </button>
                                        <button type="button" class="btn-danger btn-sm" @click="retire(skill)">
                                            <TrashIcon class="size-4" aria-hidden="true" />
                                            Retire
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="skills.data.length === 0">
                            <td :colspan="editable ? 7 : 6" class="py-6 text-center text-sm text-cthulhu-green-500">
                                <template v-if="filters.trashed">No skill has been retired.</template>
                                <template v-else-if="filters.search">No skill matches “{{ filters.search }}”.</template>
                                <template v-else-if="filters.era">No skill belongs to that era alone.</template>
                                <template v-else>There are no skills yet.</template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :paginator="skills" />
        </section>

        <!-- Create / edit -->
        <Modal :show="showForm" max-width="xl" @close="showForm = false">
            <form class="panel flex flex-col gap-4 p-6" @submit.prevent="submit">
                <h2 class="display text-lg text-cthulhu-green-900">
                    {{ editing ? `Edit ${editing.display_name}` : 'New skill' }}
                </h2>

                <div>
                    <label for="skill-name" class="field-label">Name</label>
                    <input id="skill-name" v-model="form.display_name" type="text" class="field mt-1" required />
                    <p v-if="form.errors.display_name" class="field-error">{{ form.errors.display_name }}</p>
                </div>

                <div>
                    <label for="skill-slug" class="field-label">Slug</label>
                    <input id="skill-slug" v-model="form.slug" type="text" class="field mt-1" placeholder="derived from the name" />
                    <p class="field-hint">
                        What characters and weapons refer to. Leave it empty when creating and it is derived
                        from the name; changing it later will orphan anything already pointing at the old one.
                    </p>
                    <p v-if="form.errors.slug" class="field-error">{{ form.errors.slug }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="skill-base" class="field-label">Base value</label>
                        <input
                            id="skill-base"
                            v-model.number="form.starting_value"
                            type="number"
                            min="0"
                            max="100"
                            class="field mt-1 tabular"
                            required
                        />
                        <p v-if="form.errors.starting_value" class="field-error">{{ form.errors.starting_value }}</p>
                    </div>

                    <div>
                        <label for="skill-order" class="field-label">Sort order</label>
                        <input
                            id="skill-order"
                            v-model.number="form.order_by"
                            type="number"
                            min="0"
                            max="255"
                            class="field mt-1 tabular"
                            placeholder="last"
                        />
                        <p v-if="form.errors.order_by" class="field-error">{{ form.errors.order_by }}</p>
                    </div>
                </div>

                <EraPicker
                    v-model="form.eras"
                    :eras="eras"
                    hint="Which eras have any use for the skill. Nearly all of them have both — a modern
                          table has no more use for Fighting (Chainsaw) in 1925 than a Twenties one does."
                    :error="form.errors.eras"
                />

                <div>
                    <label for="skill-description" class="field-label">Description</label>
                    <textarea id="skill-description" v-model="form.description" rows="3" class="field mt-1"></textarea>
                    <p v-if="form.errors.description" class="field-error">{{ form.errors.description }}</p>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" class="btn-ghost" @click="showForm = false">Cancel</button>
                    <button type="submit" class="btn-primary" :disabled="form.processing">
                        {{ editing ? 'Save' : 'Add skill' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AdminLayout>
</template>
