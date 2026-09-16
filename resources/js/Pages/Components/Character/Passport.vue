<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/20/solid';

const props = defineProps({ character: { type: Object, required: true } });
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
                    <p class="border-b border-cthulhu-green-900/25 pb-3 text-xs italic text-cthulhu-green-700">Valid for travel into the unknown. Return passage not guaranteed.</p>
                </div>
                <!-- Measure the complete optional block, even while hidden, so long names and rotation cannot crowd out the passport. -->
                <div :class="roomForAmmo ? '' : 'invisible absolute inset-x-0 top-0 h-0 overflow-hidden pointer-events-none'">
                    <section ref="ammunition" class="w-full pt-5" :aria-hidden="!roomForAmmo">
                        <template v-if="guns.length">
                            <h3 class="eyebrow">Customs declaration · bullets in your gun</h3>
                            <dl class="mt-2 flex flex-col gap-2">
                                <div v-for="weapon in guns" :key="weapon.pivot?.id ?? weapon.id" class="flex items-baseline justify-between gap-4 border-b border-cthulhu-green-900/20 pb-2">
                                    <dt class="min-w-0 break-words text-sm">{{ weapon.name }}</dt>
                                    <dd class="tabular shrink-0 text-right text-xl font-semibold">{{ weapon.pivot?.ammo ?? '—' }} <span class="text-xs font-normal text-cthulhu-green-700">/ {{ weapon.magazine_capacity }}</span></dd>
                                </div>
                            </dl>
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
.passport-picture { width: clamp(105px, 20.25dvh, 210px); max-width: 100%; aspect-ratio: 3 / 4; }
.passport-photo { width: 100%; height: 100%; object-fit: cover; }
@media (min-width: 640px) {
    .passport { padding: max(1.5rem, env(safe-area-inset-top)) max(2rem, env(safe-area-inset-right)) max(1.5rem, env(safe-area-inset-bottom)) max(2rem, env(safe-area-inset-left)); }
    .passport-identity { grid-template-columns: minmax(0, 2fr) minmax(0, 3fr); }
    .passport-picture { width: 100%; max-width: min(100%, 39.75dvh); }
}
</style>
