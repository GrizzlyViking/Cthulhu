<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import {
    BoltIcon,
    EllipsisVerticalIcon,
    MagnifyingGlassIcon,
    PlusIcon,
} from '@heroicons/vue/20/solid';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

const prop = defineProps({
    character: Object,
    canEdit: Boolean,
    storageLocations: { type: Array, default: () => [] },
});

/*
 * Everything the investigator owns, weapons included. Both kinds are rows in
 * the same `equipables` pivot, so both know where they are kept — the point of
 * listing them together is being able to see that a revolver is on the person
 * while its spare box of rounds is in the travel chest.
 */
const possessions = computed(() => {
    const weapons = (prop.character.weapons ?? []).map((weapon) => ({
        key: `w${weapon.pivot.id}`,
        pivotId: weapon.pivot.id,
        name: weapon.name,
        detail: weapon.skills?.display_name ?? weapon.skill,
        section: weapon.category ?? 'Weapons',
        isWeapon: true,
        quantity: weapon.pivot.quantity ?? 1,
        notes: weapon.pivot.notes,
        locationId: weapon.pivot.storage_location_id,
    }));

    const equipment = (prop.character.equipment ?? []).map((item) => ({
        key: `e${item.pivot.id}`,
        pivotId: item.pivot.id,
        name: item.name,
        detail: null,
        section: item.section ?? 'Personal effects',
        isWeapon: false,
        quantity: item.pivot.quantity ?? 1,
        notes: item.pivot.notes,
        locationId: item.pivot.storage_location_id,
    }));

    return [...weapons, ...equipment];
});

/** Possessions bucketed by where they are kept, in the locations' own order. */
const byLocation = computed(() => {
    const buckets = prop.storageLocations.map((location) => ({
        id: location.id,
        name: location.name,
        items: possessions.value.filter((p) => p.locationId === location.id),
    }));

    const known = new Set(prop.storageLocations.map((l) => l.id));
    const loose = possessions.value.filter((p) => !known.has(p.locationId));

    if (loose.length) {
        buckets.push({ id: null, name: 'Not stored anywhere', items: loose });
    }

    return buckets.filter((bucket) => bucket.items.length > 0);
});

const totalCarried = computed(() => possessions.value.reduce((sum, p) => sum + (p.quantity ?? 1), 0));

// ---- Adding ------------------------------------------------------------

const isAddOpen = ref(false);
const query = ref('');
const results = ref([]);
const searching = ref(false);
const chosen = ref(null);
const quantity = ref(1);
const locationId = ref(prop.storageLocations[0]?.id ?? null);
const addError = ref('');

let debounce = null;

watch(query, (value) => {
    chosen.value = null;
    clearTimeout(debounce);
    debounce = setTimeout(async () => {
        searching.value = true;
        try {
            const { data } = await axios.get(route('equipment.search'), { params: { search: value } });
            results.value = data.items;
        } finally {
            searching.value = false;
        }
    }, 250);
});

/** Search hits grouped under the book's section headings. */
const grouped = computed(() => {
    const groups = new Map();

    results.value.forEach((item) => {
        const section = item.is_custom ? 'Added by players' : (item.section ?? 'Other');
        if (!groups.has(section)) groups.set(section, []);
        groups.get(section).push(item);
    });

    return [...groups.entries()].map(([section, items]) => ({ section, items }));
});

/*
 * True when what has been typed matches nothing in the catalogue — the moment
 * the player is allowed to invent something rather than pick it.
 */
const isNovel = computed(() =>
    query.value.trim() !== ''
    && !searching.value
    && !results.value.some((item) => item.name.toLowerCase() === query.value.trim().toLowerCase())
);

const openAdd = () => {
    query.value = '';
    results.value = [];
    chosen.value = null;
    quantity.value = 1;
    addError.value = '';
    locationId.value = prop.storageLocations[0]?.id ?? null;
    isAddOpen.value = true;
};

const choose = (item) => {
    chosen.value = item;
    query.value = item.name;
};

const submit = () => {
    if (chosen.value === null && query.value.trim() === '') {
        addError.value = 'Choose something from the list, or type a name for it.';
        return;
    }

    router.post(
        route('equipment.store', { character: prop.character.slug }),
        {
            equipment_item_id: chosen.value?.id ?? null,
            name: chosen.value ? null : query.value.trim(),
            storage_location_id: locationId.value,
            quantity: quantity.value,
        },
        { preserveScroll: true, onSuccess: () => (isAddOpen.value = false) }
    );
};

// ---- Editing a line ----------------------------------------------------

const move = (entry, storage_location_id) => {
    router.put(
        route('equipment.update', { character: prop.character.slug, equipable: entry.pivotId }),
        { storage_location_id, quantity: entry.quantity, notes: entry.notes },
        { preserveScroll: true }
    );
};

const setQuantity = (entry, value) => {
    const quantity = Math.max(1, Number(value) || 1);

    router.put(
        route('equipment.update', { character: prop.character.slug, equipable: entry.pivotId }),
        { storage_location_id: entry.locationId, quantity, notes: entry.notes },
        { preserveScroll: true }
    );
};

const remove = (entry) => {
    if (confirm(`Remove ${entry.name}?`)) {
        router.delete(
            route('equipment.destroy', { character: prop.character.slug, equipable: entry.pivotId }),
            { preserveScroll: true }
        );
    }
};

// ---- Adding a place to keep things -------------------------------------

const isLocationOpen = ref(false);
const newLocation = ref('');
const locationError = ref('');

const addLocation = () => {
    router.post(
        route('storage.location.store', { character: prop.character.slug }),
        { name: newLocation.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                isLocationOpen.value = false;
                newLocation.value = '';
                locationError.value = '';
            },
            onError: (errors) => (locationError.value = errors.name ?? 'That name will not do.'),
        }
    );
};
</script>

<template>
    <section class="panel p-4 sm:p-5">
        <header class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-base font-semibold text-cthulhu-green-900">Equipment</h2>
                <span v-if="totalCarried" class="chip tabular">{{ totalCarried }} carried</span>
            </div>

            <div v-if="canEdit" class="flex flex-wrap items-center gap-2">
                <button type="button" class="btn-ghost btn-sm" @click="isLocationOpen = true">
                    Add a place
                </button>
                <button type="button" class="btn-secondary btn-sm" @click="openAdd">
                    <PlusIcon class="size-4" aria-hidden="true" />
                    Add equipment
                </button>
            </div>
        </header>

        <p
            v-if="byLocation.length === 0"
            class="rounded-lg bg-parchment-50 py-8 text-center text-sm text-cthulhu-green-500 ring-1 ring-parchment-300"
        >
            Nothing carried and nothing packed.
        </p>

        <div v-else class="flex flex-col gap-4">
            <div v-for="bucket in byLocation" :key="bucket.id ?? 'loose'">
                <p class="eyebrow mb-2">{{ bucket.name }}</p>

                <ul role="list" class="flex flex-col gap-1.5">
                    <li
                        v-for="entry in bucket.items"
                        :key="entry.key"
                        class="flex flex-wrap items-center gap-x-3 gap-y-2 rounded-lg bg-parchment-50 px-3 py-2 ring-1 ring-parchment-300"
                    >
                        <div class="min-w-0 flex-auto">
                            <p class="flex flex-wrap items-center gap-x-2 text-sm font-semibold text-cthulhu-green-900">
                                <BoltIcon
                                    v-if="entry.isWeapon"
                                    class="size-4 shrink-0 text-cthulhu-green-500"
                                    aria-hidden="true"
                                />
                                {{ entry.name }}
                                <span v-if="entry.quantity > 1" class="chip tabular">×{{ entry.quantity }}</span>
                            </p>
                            <p class="text-xs text-cthulhu-green-500">
                                {{ entry.section }}<template v-if="entry.detail"> · {{ entry.detail }}</template>
                                <template v-if="entry.notes"> · {{ entry.notes }}</template>
                            </p>
                        </div>

                        <div v-if="canEdit" class="flex shrink-0 items-center gap-2">
                            <label :for="`qty-${entry.key}`" class="sr-only">Quantity</label>
                            <input
                                :id="`qty-${entry.key}`"
                                type="number"
                                min="1"
                                class="field tabular w-16 py-1 text-xs"
                                :value="entry.quantity"
                                @change="setQuantity(entry, $event.target.value)"
                            />

                            <label :for="`loc-${entry.key}`" class="sr-only">Stored in</label>
                            <select
                                :id="`loc-${entry.key}`"
                                class="field w-auto py-1 text-xs"
                                :value="entry.locationId ?? ''"
                                @change="move(entry, $event.target.value ? Number($event.target.value) : null)"
                            >
                                <option value="">Nowhere in particular</option>
                                <option v-for="location in storageLocations" :key="location.id" :value="location.id">
                                    {{ location.name }}
                                </option>
                            </select>

                            <!-- Weapons are removed from the weapons list above,
                                 so there is only ever one way to drop one. -->
                            <Menu v-if="!entry.isWeapon" as="div" class="relative flex-none">
                                <MenuButton
                                    class="rounded-md p-1 text-cthulhu-green-500 transition hover:bg-cthulhu-green-900/10 hover:text-cthulhu-green-900 focus:outline-none"
                                >
                                    <span class="sr-only">Options for {{ entry.name }}</span>
                                    <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
                                </MenuButton>
                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-32 origin-top-right overflow-hidden rounded-lg bg-parchment-50 py-1 shadow-raised ring-1 ring-cthulhu-green-900/20 focus:outline-none"
                                    >
                                        <MenuItem v-slot="{ active }">
                                            <button
                                                type="button"
                                                :class="[active ? 'bg-parchment-200' : '', 'block w-full px-3 py-2 text-left text-sm text-cthulhu-blood-400']"
                                                @click="remove(entry)"
                                            >
                                                Remove
                                            </button>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Add equipment -->
        <Modal :show="isAddOpen" max-width="xl" @close="isAddOpen = false">
            <div class="panel flex max-h-[80vh] flex-col overflow-hidden">
                <div class="border-b border-parchment-300 p-5">
                    <h3 class="display text-lg text-cthulhu-green-900">Add equipment</h3>

                    <div class="relative mt-3">
                        <MagnifyingGlassIcon
                            class="pointer-events-none absolute inset-y-0 start-3 my-auto size-4 text-cthulhu-green-500"
                            aria-hidden="true"
                        />
                        <input
                            v-model="query"
                            type="search"
                            aria-label="Search equipment"
                            placeholder="Search the catalogue, or type something of your own…"
                            class="field ps-9"
                            autofocus
                        />
                    </div>

                    <p v-if="addError" class="field-error">{{ addError }}</p>
                </div>

                <div class="flex-1 overflow-y-auto p-5">
                    <div v-for="group in grouped" :key="group.section" class="mb-4 last:mb-0">
                        <p class="eyebrow mb-2">{{ group.section }}</p>
                        <ul role="list" class="flex flex-col gap-1.5">
                            <li v-for="item in group.items" :key="item.id">
                                <button
                                    type="button"
                                    class="flex w-full flex-wrap items-baseline justify-between gap-x-3 gap-y-1 rounded-lg px-3 py-2 text-left ring-1 transition"
                                    :class="chosen?.id === item.id
                                        ? 'bg-cthulhu-yellow-200/50 ring-cthulhu-yellow-500'
                                        : 'bg-parchment-50 ring-parchment-300 hover:bg-parchment-200'"
                                    @click="choose(item)"
                                >
                                    <span class="text-sm font-semibold text-cthulhu-green-900">{{ item.name }}</span>
                                    <!-- The price is only ever shown while choosing.
                                         Once it is theirs, what it cost stops mattering. -->
                                    <span v-if="item.cost" class="tabular text-xs text-cthulhu-green-500">{{ item.cost }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <p v-if="searching" class="py-6 text-center text-sm text-cthulhu-green-500">Searching…</p>

                    <div v-else-if="isNovel" class="card-marked">
                        <p class="text-sm text-cthulhu-green-900">
                            Nothing in the catalogue is called “{{ query.trim() }}”. Add it anyway and it becomes
                            available to everyone.
                        </p>
                    </div>

                    <p
                        v-else-if="query.trim() === '' && results.length === 0"
                        class="py-6 text-center text-sm text-cthulhu-green-500"
                    >
                        Start typing to search the 1920s catalogue.
                    </p>
                </div>

                <div class="flex flex-wrap items-end justify-between gap-3 border-t border-parchment-300 p-5">
                    <div class="flex items-end gap-3">
                        <div>
                            <label for="add-qty" class="field-label text-xs">How many</label>
                            <input id="add-qty" v-model.number="quantity" type="number" min="1" class="field tabular mt-1 w-20" />
                        </div>
                        <div>
                            <label for="add-loc" class="field-label text-xs">Kept in</label>
                            <select id="add-loc" v-model="locationId" class="field mt-1 w-auto">
                                <option :value="null">Nowhere in particular</option>
                                <!-- v-model handles the null binding here; the
                                     per-row selects above are :value-bound and
                                     need an empty string instead. -->
                                <option v-for="location in storageLocations" :key="location.id" :value="location.id">
                                    {{ location.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-ghost" @click="isAddOpen = false">Cancel</button>
                        <button type="button" class="btn-primary" @click="submit">
                            {{ chosen ? `Add ${chosen.name}` : 'Add' }}
                        </button>
                    </div>
                </div>
            </div>
        </Modal>

        <!-- Add a storage location -->
        <Modal :show="isLocationOpen" max-width="md" @close="isLocationOpen = false">
            <form class="panel flex flex-col gap-4 p-6" @submit.prevent="addLocation">
                <h3 class="display text-lg text-cthulhu-green-900">Add a place to keep things</h3>
                <p class="field-hint">
                    A saddlebag, a hotel safe, the boot of the Ford. Places are shared with everyone on this server.
                </p>

                <div>
                    <label for="new-location" class="field-label">Name</label>
                    <input id="new-location" v-model="newLocation" type="text" class="field mt-1" required />
                    <p v-if="locationError" class="field-error">{{ locationError }}</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" class="btn-ghost" @click="isLocationOpen = false">Cancel</button>
                    <button type="submit" class="btn-primary">Add</button>
                </div>
            </form>
        </Modal>
    </section>
</template>
