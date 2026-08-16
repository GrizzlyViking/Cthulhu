<script setup>
/**
 * Characteristics by hand: the player has already done the arithmetic — dice,
 * age modifiers and all — and simply types the finished percentages in.
 */
import { computed, reactive, ref, watchEffect } from 'vue';
import NumberField from '@/Pages/Components/Wizard/NumberField.vue';
import HelpDisclosure from '@/Pages/Components/Wizard/HelpDisclosure.vue';
import { CHARACTERISTICS, LUCK } from '@/Pages/Components/Wizard/wizardData.js';

const finalsModel = defineModel('finals', { type: Object, required: true });
const readyModel = defineModel('ready', { type: Boolean, required: true });

const MAX_LUCK = 90;

const values = reactive(
    Object.fromEntries(CHARACTERISTICS.map((c) => [c.key, null]))
);
const luck = ref(null);

const within = (value, max) => value !== null && value >= 1 && value <= max;

const valid = (key) => within(values[key], 99);

const finals = computed(() => ({ ...values, luck: luck.value }));

const ready = computed(
    () => CHARACTERISTICS.every((c) => valid(c.key)) && within(luck.value, MAX_LUCK)
);

watchEffect(() => {
    finalsModel.value = finals.value;
    readyModel.value = ready.value;
});
</script>

<template>
    <div class="panel p-4 sm:p-6">
        <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
            Your characteristics
        </h2>
        <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
            Type each finished value between 1 and 99.
        </p>

        <ul class="mt-5 flex flex-col gap-4">
            <li
                v-for="c in CHARACTERISTICS"
                :key="c.key"
                class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-cthulhu-green-300/60"
            >
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-cthulhu-green-900">
                            {{ c.abbr }}
                            <span class="font-medium text-cthulhu-green-700">— {{ c.name }}</span>
                        </p>
                    </div>
                    <NumberField
                        v-model="values[c.key]"
                        :min="1"
                        :max="99"
                        placeholder="—"
                        width-class="w-20"
                    />
                </div>
                <p
                    v-if="values[c.key] !== null && !valid(c.key)"
                    class="mt-1 text-xs text-cthulhu-blood-400"
                >
                    Enter a value between 1 and 99.
                </p>
                <div class="mt-2">
                    <HelpDisclosure :label="`About ${c.abbr}`">
                        <p>{{ c.description }}</p>
                        <p class="mt-2 font-semibold">What the numbers mean</p>
                        <ul class="mt-1 flex flex-col gap-0.5">
                            <li v-for="[value, meaning] in c.ladder" :key="value" class="flex gap-2">
                                <span class="w-8 shrink-0 text-right font-mono">{{ value }}</span>
                                <span>{{ meaning }}</span>
                            </li>
                        </ul>
                    </HelpDisclosure>
                </div>
            </li>

            <!-- Luck -->
            <li class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-cthulhu-green-300/60">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-bold text-cthulhu-green-900">
                        LUCK <span class="font-medium text-cthulhu-green-700">— Luck</span>
                    </p>
                    <NumberField
                        v-model="luck"
                        :min="1"
                        :max="MAX_LUCK"
                        placeholder="—"
                        width-class="w-20"
                    />
                </div>
                <p
                    v-if="luck !== null && !within(luck, MAX_LUCK)"
                    class="mt-1 text-xs text-cthulhu-blood-400"
                >
                    Enter a value between 1 and {{ MAX_LUCK }}.
                </p>
                <div class="mt-2">
                    <HelpDisclosure label="About Luck">
                        <p>{{ LUCK.description }}</p>
                    </HelpDisclosure>
                </div>
            </li>
        </ul>
    </div>
</template>
