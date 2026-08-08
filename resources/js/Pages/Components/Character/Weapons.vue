<script setup>
import Modal from '@/Components/Modal.vue';
import RegularHalfFifth from '@/Pages/Components/RegularHalfFifth.vue';
import SuccessLegend from '@/Pages/Components/SuccessLegend.vue';
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import axios from 'axios';
import EraChips from '@/Components/EraChips.vue';
import CollapsibleSection from '@/Components/CollapsibleSection.vue';
import { belongsToEra } from '@/Pages/Composables/useEra.js';
import { useFoldedSections } from '@/Pages/Composables/useFoldedSections.js';
import {
    ArrowPathIcon,
    EllipsisVerticalIcon,
    MagnifyingGlassIcon,
    PlusIcon,
} from '@heroicons/vue/20/solid';

const prop = defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
    /** The group's era, and every era the server knows about. */
    era: { type: String, default: null },
    eras: { type: Array, default: () => [] },
});

const page = usePage();

const isWeaponModalOpen = ref(false);
const weaponQuery = ref('');

/*
 * The armoury holds both eras, and a 1920s table has no use for scrolling past
 * the Uzi to reach the Thompson. The picker opens on this era and keeps the
 * rest behind a switch rather than dropping them — the Keeper decides what an
 * investigator is allowed to be holding, not this list.
 */
const allEras = ref(false);

const inEra = (weapon) => belongsToEra(weapon, prop.era);

/** Transient per-weapon message, keyed by pivot id ("empty magazine" and friends). */
const notices = ref({});

/*
 * The armoury is over a hundred weapons long, which is no way to greet somebody
 * on a phone. It opens as a list of closed categories — pistols, rifles, the
 * blunt instruments — and a tap unfolds one.
 */
const folded = useFoldedSections();

const openModal = () => {
    weaponQuery.value = '';
    allEras.value = false;
    folded.closeAll();
    isWeaponModalOpen.value = true;
};

const closeModal = () => {
    isWeaponModalOpen.value = false;
};

const addWeapon = (weapon_id) => {
    router.post(route('equip.weapon', { character: prop.character.slug }), {
        weapon_id: weapon_id,
    }, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const remove = (weapon) => {
    router.delete(route('remove.weapon', {
        character: prop.character.slug,
        equipable: weapon.pivot.id,
    }), { preserveScroll: true });
};

/*
 * Ammunition. `pivot.ammo` is what sits in the magazine, `pivot.ammo_reserve`
 * is everything else the investigator is carrying for that weapon. The server
 * owns the arithmetic and answers with both figures, so a stale sheet cannot
 * drift out of step with what was actually fired.
 */
const capacityOf = (weapon) => weapon.magazine_capacity ?? null;

const usesAmmo = (weapon) => capacityOf(weapon) !== null;

const magazineRatio = (weapon) => {
    const capacity = capacityOf(weapon);

    if (!capacity) return 0;

    return Math.min(1, Math.max(0, weapon.pivot.ammo / capacity));
};

const canFire = (weapon) => prop.canEdit && weapon.pivot.ammo > 0;

const canReload = (weapon) =>
    prop.canEdit && weapon.pivot.ammo_reserve > 0 && weapon.pivot.ammo < capacityOf(weapon);

const applyAmmo = (weapon, data) => {
    weapon.pivot.ammo = data.ammo;
    weapon.pivot.ammo_reserve = data.ammo_reserve;
    notices.value = { ...notices.value, [weapon.pivot.id]: null };
};

const noticeFrom = (weapon, error) => {
    notices.value = {
        ...notices.value,
        [weapon.pivot.id]: error.response?.data?.message ?? 'That did not work.',
    };
};

const fire = (weapon) => {
    if (!canFire(weapon)) return;

    axios.post(route('fire.weapon', {
        character: prop.character.slug,
        equipable: weapon.pivot.id,
    }))
        .then(({ data }) => applyAmmo(weapon, data))
        .catch((error) => noticeFrom(weapon, error));
};

const reload = (weapon) => {
    axios.post(route('reload.weapon', {
        character: prop.character.slug,
        equipable: weapon.pivot.id,
    }))
        .then(({ data }) => applyAmmo(weapon, data))
        .catch((error) => noticeFrom(weapon, error));
};

const setAmmo = (weapon, payload) => {
    axios.put(route('weapon.ammo.update', {
        character: prop.character.slug,
        equipable: weapon.pivot.id,
    }), payload)
        .then(({ data }) => applyAmmo(weapon, data))
        .catch((error) => noticeFrom(weapon, error));
};

const setReserve = (weapon, value) => {
    const rounds = Math.max(0, parseInt(value) || 0);

    if (rounds === weapon.pivot.ammo_reserve) return;

    setAmmo(weapon, { ammo_reserve: rounds });
};

const setMagazine = (weapon, value) => {
    const rounds = Math.max(0, parseInt(value) || 0);

    if (rounds === weapon.pivot.ammo) return;

    setAmmo(weapon, { ammo: rounds });
};

/** The character's own rating in the skill a weapon is used with. */
const weaponSkill = computed(() => (slug) => {
    const match = (prop.character.skills ?? []).find((skill) => skill.slug === slug);

    return match ? match.pivot : null;
});

/** Damage bonus and build share the same STR+SIZ bands in Call of Cthulhu 7e. */
const strengthAndSize = computed(() => Number(prop.character.strength) + Number(prop.character.size));

const damageBonus = computed(() => {
    const total = strengthAndSize.value;

    if (total < 65) return '-2';
    if (total < 85) return '-1';
    if (total < 125) return 'none';
    if (total < 165) return '+1D4';
    if (total < 205) return '+1D6';
    return '+2D6';
});

const build = computed(() => {
    const total = strengthAndSize.value;

    if (total < 65) return '-2';
    if (total < 85) return '-1';
    if (total < 125) return 'none';
    if (total < 165) return '+1';
    if (total < 205) return '+2';
    return '+3';
});

const weaponDamage = computed(() => (wDmg, DB) => {
    switch (DB) {
        default:
            return wDmg.replace(/\+?half DB$/, `+half ${DB}`).replace(/\+?DB$/, DB);
        case 'none':
            return wDmg.replace(/\+?half DB$/, '').replace(/\+?DB$/, '');
    }
});

/** How many weapons the era is keeping out of the picker. */
const otherEraCount = computed(() =>
    (page.props.auth.equipment ?? []).filter((weapon) => !inEra(weapon)).length
);

/** The armoury, grouped the way the handbook prints it. */
const armoury = computed(() => {
    const needle = weaponQuery.value.trim().toLowerCase();
    const groups = new Map();

    (page.props.auth.equipment ?? [])
        .filter((weapon) => !needle || weapon.name.toLowerCase().includes(needle))
        .filter((weapon) => allEras.value || inEra(weapon))
        .forEach((weapon) => {
            const category = weapon.category ?? 'Other';

            if (!groups.has(category)) {
                groups.set(category, []);
            }

            groups.get(category).push(weapon);
        });

    return [...groups.entries()].map(([category, weapons]) => ({ category, weapons }));
});

const isFiltering = computed(() => weaponQuery.value.trim() !== '');

/*
 * A category is open when it has been tapped — but a search unfolds everything,
 * since hiding the hits behind another tap is precisely what a search is for
 * avoiding. A lone category is not worth folding either.
 */
const isSectionOpen = (category) =>
    isFiltering.value || armoury.value.length === 1 || folded.isOpen(category);

const allSectionsOpen = computed(() =>
    armoury.value.every((group) => isSectionOpen(group.category))
);

const toggleAllSections = () => {
    if (allSectionsOpen.value) {
        folded.closeAll();

        return;
    }

    folded.openAll(armoury.value.map((group) => group.category));
};

/** The first few names in a closed category, as a hint at what is inside. */
const previewOf = (weapons) =>
    weapons.slice(0, 3).map((weapon) => weapon.name).join(', ')
    + (weapons.length > 3 ? '…' : '');
</script>

<template>
    <section class="panel p-4 sm:p-5">
        <header class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-base font-semibold text-cthulhu-green-900">Weapons</h2>
                <span class="chip">Damage bonus: {{ damageBonus }}</span>
                <span class="chip">Build: {{ build }}</span>
                <SuccessLegend />
            </div>

            <button v-if="canEdit" type="button" class="btn-secondary btn-sm" @click="openModal">
                <PlusIcon class="size-4" aria-hidden="true" />
                Add weapon
            </button>
        </header>

        <p v-if="!prop.character.weapons?.length" class="rounded-lg bg-parchment-50 py-8 text-center text-sm text-cthulhu-green-500 ring-1 ring-parchment-300">
            No weapons carried.
        </p>

        <ul v-else role="list" class="flex flex-col gap-3">
            <li
                v-for="weapon in prop.character.weapons"
                :key="weapon.pivot.id"
                class="rounded-lg bg-parchment-50 p-3 ring-1 ring-parchment-300 sm:p-4"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            <h3 class="text-sm font-semibold text-cthulhu-green-900">{{ weapon.name }}</h3>
                            <span v-if="weapon.impale" class="chip-brass" title="Impales on an extreme success">Impaling</span>
                            <EraChips v-if="!inEra(weapon)" :eras="weapon.eras" :options="eras" />
                        </div>
                        <p class="mt-0.5 text-xs text-cthulhu-green-500">
                            {{ weapon.skills?.display_name ?? weapon.skill }}
                            <template v-if="weapon.category"> · {{ weapon.category }}</template>
                            <template v-if="weapon.era"> · {{ weapon.era }}</template>
                        </p>
                    </div>

                    <Menu v-if="canEdit" as="div" class="relative flex-none">
                        <MenuButton class="rounded-md p-1.5 text-cthulhu-green-500 transition hover:bg-cthulhu-green-900/10 hover:text-cthulhu-green-900 focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-green-800">
                            <span class="sr-only">Weapon options</span>
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
                            <MenuItems class="absolute right-0 z-10 mt-2 w-36 origin-top-right overflow-hidden rounded-lg bg-parchment-50 py-1 shadow-raised ring-1 ring-cthulhu-green-900/20 focus:outline-none">
                                <MenuItem v-slot="{ active }">
                                    <button
                                        type="button"
                                        :class="[active ? 'bg-parchment-200' : '', 'block w-full px-3 py-2 text-left text-sm text-cthulhu-blood-400']"
                                        @click="remove(weapon)"
                                    >
                                        Remove
                                    </button>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>

                <div class="mt-3 grid gap-3 lg:grid-cols-[minmax(0,1fr)_minmax(0,18rem)]">
                    <!-- Stats -->
                    <div class="flex flex-wrap items-start gap-x-6 gap-y-3">
                        <div>
                            <p class="eyebrow">Chance to hit</p>
                            <RegularHalfFifth
                                v-if="weaponSkill(weapon.skill)"
                                class="mt-1"
                                :skill-value="weaponSkill(weapon.skill).value"
                            />
                            <p v-else class="mt-1 text-sm text-cthulhu-green-500">
                                Skill not on this sheet
                            </p>
                        </div>

                        <dl class="grid grow grid-cols-2 gap-x-4 gap-y-2 sm:grid-cols-4">
                            <div>
                                <dt class="eyebrow">Damage</dt>
                                <dd class="tabular text-sm text-cthulhu-green-900">{{ weaponDamage(weapon.damage, damageBonus) }}</dd>
                            </div>
                            <div>
                                <dt class="eyebrow">Range</dt>
                                <dd class="tabular text-sm text-cthulhu-green-900">{{ weapon.base_range }}</dd>
                            </div>
                            <div>
                                <dt class="eyebrow">Uses/round</dt>
                                <dd class="tabular text-sm text-cthulhu-green-900">{{ weapon.uses_per_round }}</dd>
                            </div>
                            <div>
                                <dt class="eyebrow">Malfunction</dt>
                                <dd class="tabular text-sm text-cthulhu-green-900">{{ weapon.malfunction || '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Ammunition -->
                    <div v-if="usesAmmo(weapon)" class="card-marked flex flex-col gap-2">
                        <div class="flex items-baseline justify-between gap-2">
                            <p class="eyebrow">Magazine</p>
                            <p class="tabular text-sm font-semibold text-cthulhu-green-900">
                                {{ weapon.pivot.ammo }} / {{ capacityOf(weapon) }}
                            </p>
                        </div>

                        <div class="h-1.5 overflow-hidden rounded-full bg-parchment-300">
                            <div
                                class="h-full rounded-full transition-all"
                                :class="magazineRatio(weapon) <= 1 / 3 ? 'bg-cthulhu-blood-400' : 'bg-cthulhu-green-600'"
                                :style="{ width: `${magazineRatio(weapon) * 100}%` }"
                            ></div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                type="button"
                                class="btn-primary btn-sm"
                                :disabled="!canFire(weapon)"
                                :title="weapon.pivot.ammo > 0 ? 'Spend one round' : 'The magazine is empty'"
                                @click="fire(weapon)"
                            >
                                Shoot
                            </button>
                            <button
                                type="button"
                                class="btn-secondary btn-sm"
                                :disabled="!canReload(weapon)"
                                :title="canReload(weapon) ? 'Fill the magazine from the rounds carried' : 'Nothing to load'"
                                @click="reload(weapon)"
                            >
                                <ArrowPathIcon class="size-4" aria-hidden="true" />
                                Reload
                            </button>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-parchment-300 pt-2">
                            <label :for="`reserve_${weapon.pivot.id}`" class="text-xs text-cthulhu-green-500">
                                Rounds carried
                            </label>
                            <input
                                :id="`reserve_${weapon.pivot.id}`"
                                type="number"
                                min="0"
                                :value="weapon.pivot.ammo_reserve"
                                :disabled="!canEdit"
                                class="field tabular w-20 px-2 py-1 text-sm"
                                @change="setReserve(weapon, $event.target.value)"
                            />
                        </div>

                        <div v-if="editable && canEdit" class="flex flex-wrap items-center justify-between gap-2">
                            <label :for="`magazine_${weapon.pivot.id}`" class="text-xs text-cthulhu-green-500">
                                In magazine
                            </label>
                            <input
                                :id="`magazine_${weapon.pivot.id}`"
                                type="number"
                                min="0"
                                :max="capacityOf(weapon)"
                                :value="weapon.pivot.ammo"
                                class="field tabular w-20 px-2 py-1 text-sm"
                                @change="setMagazine(weapon, $event.target.value)"
                            />
                        </div>

                        <p v-if="notices[weapon.pivot.id]" class="text-xs text-cthulhu-blood-400">
                            {{ notices[weapon.pivot.id] }}
                        </p>
                    </div>

                    <div v-else class="card flex items-center justify-center text-center">
                        <p class="text-xs text-cthulhu-green-500">
                            <template v-if="weapon.bullets_in_mag && weapon.bullets_in_mag !== '-'">
                                Ammunition: {{ weapon.bullets_in_mag }}
                            </template>
                            <template v-else>Takes no ammunition</template>
                        </p>
                    </div>
                </div>
            </li>
        </ul>

        <Modal :show="isWeaponModalOpen" max-width="2xl" @close="closeModal">
            <div class="flex max-h-[80vh] flex-col bg-parchment-100">
                <div class="flex flex-col gap-3 border-b border-parchment-300 p-5">
                    <h2 class="text-base font-semibold text-cthulhu-green-900">Add a weapon</h2>
                    <div class="relative">
                        <MagnifyingGlassIcon
                            class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-cthulhu-green-500"
                            aria-hidden="true"
                        />
                        <input
                            v-model="weaponQuery"
                            type="search"
                            aria-label="Filter weapons"
                            placeholder="Filter the armoury…"
                            class="field ps-9"
                        />
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <label v-if="otherEraCount > 0" class="inline-flex items-center gap-2 text-sm text-cthulhu-green-800">
                            <input
                                v-model="allEras"
                                type="checkbox"
                                class="size-4 rounded border-parchment-400 text-cthulhu-green-600 focus:ring-cthulhu-green-600"
                            />
                            Show every era
                            <span class="tabular text-xs text-cthulhu-green-500">({{ otherEraCount }} more)</span>
                        </label>

                        <button
                            v-if="armoury.length > 1 && !isFiltering"
                            type="button"
                            class="btn-ghost btn-sm ms-auto"
                            @click="toggleAllSections"
                        >
                            {{ allSectionsOpen ? 'Fold them all up' : 'Open them all' }}
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 sm:p-5">
                    <div class="flex flex-col gap-2">
                        <CollapsibleSection
                            v-for="group in armoury"
                            :key="group.category"
                            :title="group.category"
                            :count="group.weapons.length"
                            :subtitle="isSectionOpen(group.category) ? null : previewOf(group.weapons)"
                            :open="isSectionOpen(group.category)"
                            @toggle="folded.toggle(group.category)"
                        >
                            <ul role="list" class="flex flex-col gap-1.5">
                                <li v-for="w in group.weapons" :key="w.id">
                                    <button
                                        type="button"
                                        class="flex w-full flex-wrap items-baseline justify-between gap-x-3 gap-y-1 rounded-lg bg-parchment-50 px-3 py-2.5 text-left ring-1 ring-parchment-300 transition hover:bg-parchment-200"
                                        @click="addWeapon(w.id)"
                                    >
                                        <span class="flex flex-wrap items-center gap-2">
                                            <span class="text-sm font-semibold text-cthulhu-green-900">{{ w.name }}</span>
                                            <EraChips v-if="!inEra(w)" :eras="w.eras" :options="eras" />
                                        </span>
                                        <span class="tabular text-xs text-cthulhu-green-500">
                                            {{ w.damage }} · {{ w.base_range }}
                                            <template v-if="w.magazine_capacity"> · mag {{ w.magazine_capacity }}</template>
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </CollapsibleSection>
                    </div>

                    <p v-if="armoury.length === 0" class="py-8 text-center text-sm text-cthulhu-green-500">
                        <template v-if="weaponQuery.trim()">
                            No weapons match “{{ weaponQuery }}”<template v-if="!allEras && otherEraCount > 0">
                            in this era — tick “Show every era” to widen the search</template>.
                        </template>
                        <template v-else>The armoury is empty.</template>
                    </p>
                </div>

                <div class="flex justify-end border-t border-parchment-300 p-5">
                    <button type="button" class="btn-secondary" @click="closeModal">Finished</button>
                </div>
            </div>
        </Modal>
    </section>
</template>
