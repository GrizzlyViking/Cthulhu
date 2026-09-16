<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import axios from 'axios';
import { XMarkIcon } from '@heroicons/vue/20/solid';

const props = defineProps({ character: { type: Object, required: true }, canEdit: Boolean });
const emit = defineEmits(['close']);
const page = ref(null);
const identity = ref(null);
const ammunition = ref(null);
const roomForAmmo = ref(false);
const guns = computed(() => (props.character.weapons ?? []).filter(weapon => weapon.magazine_capacity > 0));
const details = computed(() => [
    ['Occupation', props.character.occupation || 'Investigator'],
    ['Age', props.character.age ?? '—'],
    ['Birthplace', props.character.birthplace || 'Unrecorded'],
    ['Residence', props.character.residence || 'Unrecorded'],
]);
const firing = ref({});
const shots = ref({});
const notices = ref({});
const errors = computed(() => guns.value.filter(weapon => notices.value[weapon.pivot?.id]));
const canFire = (weapon) => props.canEdit && weapon.pivot?.ammo > 0 && !firing.value[weapon.pivot.id];

const fire = async (weapon) => {
    if (!canFire(weapon)) return;
    const id = weapon.pivot.id;
    firing.value[id] = true;
    notices.value[id] = null;
    try {
        const { data } = await axios.post(route('fire.weapon', {
            character: props.character.slug,
            equipable: id,
        }));
        weapon.pivot.ammo = data.ammo;
        weapon.pivot.ammo_reserve = data.ammo_reserve;
        shots.value[id] = (shots.value[id] ?? 0) + 1;
    } catch (error) {
        notices.value[id] = error.response?.data?.message ?? 'The shot could not be saved. Try again.';
    } finally {
        firing.value[id] = false;
    }
};
let observer;

onMounted(async () => {
    await nextTick();
    observer = new ResizeObserver(() => {
        roomForAmmo.value = Boolean(page.value && identity.value && ammunition.value
            && identity.value.offsetHeight + ammunition.value.offsetHeight + 24 <= page.value.clientHeight);
    });
    [page.value, identity.value, ammunition.value].filter(Boolean).forEach(element => observer.observe(element));
});
onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <Dialog :open="true" class="relative z-50" @close="emit('close')">
        <div class="fixed inset-0 bg-cthulhu-green-950" aria-hidden="true" />
        <DialogPanel class="passport fixed inset-0 flex flex-col overflow-y-auto bg-parchment-100 text-cthulhu-green-900">
            <header class="flex shrink-0 items-center justify-between gap-3 border-b border-cthulhu-green-900/25 pb-3">
                <div>
                    <p class="eyebrow">Bureau of questionable journeys</p>
                    <DialogTitle class="display text-2xl sm:text-3xl">Passport</DialogTitle>
                </div>
                <button type="button" class="btn-ghost min-h-11 min-w-11" aria-label="Close passport" @click="emit('close')">
                    <XMarkIcon class="size-5" aria-hidden="true" />
                </button>
            </header>

            <div ref="page" class="relative mx-auto mt-4 min-h-0 w-full max-w-5xl flex-1">
                <div ref="identity" class="flex flex-col gap-4">
                    <div class="passport-identity grid min-w-0 gap-5">
                        <div class="passport-picture relative justify-self-center self-start">
                            <img :src="'/storage/' + character.avatar" :alt="character.name" class="passport-photo max-w-full rounded-sm border border-cthulhu-green-900/30 object-cover object-top shadow-raised" />
                            <p class="absolute -bottom-2 right-2 rotate-[-8deg] border-2 border-cthulhu-green-700 bg-parchment-100/90 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-cthulhu-green-700">Probably human</p>
                        </div>
                        <div class="flex min-w-0 flex-col gap-4">
                            <div>
                                <p class="eyebrow">Bearer’s name</p>
                                <h2 class="display break-words text-2xl sm:text-4xl">{{ character.name }}</h2>
                            </div>
                            <dl class="grid grid-cols-2 gap-x-4 gap-y-3">
                                <div v-for="[label, value] in details" :key="label" class="min-w-0 border-b border-cthulhu-green-900/20 pb-2">
                                    <dt class="eyebrow">{{ label }}</dt>
                                    <dd class="break-words text-sm sm:text-base" :class="label === 'Age' ? 'tabular text-right' : ''">{{ value }}</dd>
                                </div>
                            </dl>
                            <div class="flex items-center justify-between gap-4 border-y-2 border-cthulhu-green-800 py-2">
                                <div>
                                    <p class="eyebrow">Hit points</p>
                                    <p class="text-xs text-cthulhu-green-700">Condition on arrival may vary.</p>
                                </div>
                                <p class="display tabular text-right text-4xl" :class="character.hit_points <= 0 ? 'text-cthulhu-blood-500' : ''">{{ character.hit_points ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                    <p v-for="weapon in errors" :key="weapon.pivot.id" class="field-error" role="alert">{{ weapon.name }}: {{ notices[weapon.pivot.id] }}</p>
                    <p class="border-b border-cthulhu-green-900/25 pb-3 text-xs italic text-cthulhu-green-700">Valid for travel into the unknown. Return passage not guaranteed.</p>
                </div>
                <!-- Measure the complete optional block, even while hidden, so long names and rotation cannot crowd out the passport. -->
                <div :class="roomForAmmo ? '' : 'invisible absolute inset-x-0 top-0 h-0 overflow-hidden pointer-events-none'">
                    <section ref="ammunition" class="w-full pt-5" :aria-hidden="!roomForAmmo">
                        <template v-if="guns.length">
                            <h3 class="eyebrow">Customs declaration · bullets in your gun</h3>
                            <div class="mt-2 flex flex-col gap-2">
                                <div v-for="weapon in guns" :key="weapon.pivot?.id ?? weapon.id">
                                    <button type="button" class="passport-ammo relative flex min-h-20 w-full items-center justify-between gap-4 overflow-hidden rounded border border-cthulhu-green-900/25 px-4 py-3 text-left focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800"
                                        :class="canFire(weapon) ? 'cursor-pointer hover:bg-parchment-200 active:bg-parchment-300' : 'cursor-default'"
                                        :disabled="!canFire(weapon)" :aria-label="`Fire ${weapon.name}. ${weapon.pivot?.ammo ?? 0} rounds remaining.`"
                                        :aria-busy="Boolean(firing[weapon.pivot?.id])" @click="fire(weapon)">
                                        <span class="min-w-0">
                                            <span class="block break-words text-sm font-semibold">{{ weapon.name }}</span>
                                            <span class="block text-xs text-cthulhu-green-700">{{ firing[weapon.pivot?.id] ? 'Firing…' : weapon.pivot?.ammo === 0 ? 'Empty' : canEdit ? 'Tap to fire one round' : 'Rounds loaded' }}</span>
                                        </span>
                                        <span class="tabular shrink-0 text-right text-3xl font-semibold" aria-live="polite">{{ weapon.pivot?.ammo ?? '—' }} <span class="text-sm font-normal text-cthulhu-green-700">/ {{ weapon.magazine_capacity }}</span></span>
                                        <span v-if="shots[weapon.pivot?.id]" :key="shots[weapon.pivot.id]" class="passport-shot pointer-events-none absolute left-4 top-1/2 h-2 w-6 rounded-l-sm rounded-r-full bg-cthulhu-yellow-600" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </section>
                </div>
            </div>
        </DialogPanel>
    </Dialog>
</template>

<style scoped>
.passport {
    padding: max(1rem, env(safe-area-inset-top)) max(1rem, env(safe-area-inset-right)) max(1rem, env(safe-area-inset-bottom)) max(1rem, env(safe-area-inset-left));
    height: 100dvh;
}
.passport-identity { grid-template-columns: minmax(0, 1fr); }
.passport-picture { width: clamp(150px, 26.25dvh, 300px); max-width: 100%; aspect-ratio: 3 / 4; }
.passport-photo { width: 100%; height: 100%; object-fit: cover; }
@media (min-width: 640px) {
    .passport { padding: max(1.5rem, env(safe-area-inset-top)) max(2rem, env(safe-area-inset-right)) max(1.5rem, env(safe-area-inset-bottom)) max(2rem, env(safe-area-inset-left)); }
    .passport-identity { grid-template-columns: minmax(0, 3fr) minmax(0, 2fr); }
    .passport-picture { width: 100%; max-width: min(100%, 48dvh); }
}
.passport-shot { animation: passport-fire 280ms ease-in forwards; }
@keyframes passport-fire {
    from { transform: translateX(0) rotate(-8deg); opacity: 1; }
    to { transform: translateX(80vw) rotate(-8deg); opacity: 0; }
}
@media (prefers-reduced-motion: reduce) {
    .passport-shot { animation: none; display: none; }
}
</style>
