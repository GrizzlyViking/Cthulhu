<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { BanknotesIcon } from '@heroicons/vue/20/solid';
import { formatMoney } from '@/Pages/Composables/useMoney.js';

/*
 * What the investigator has to spend, and what they are worth besides.
 *
 * Living standard and spending level come from Credit Rating and are a rating
 * rather than a balance — no amount of spending changes what can be bought
 * without counting. Cash and assets are the counted part: they follow the band
 * until something is bought or a figure typed over, and then they are the
 * player's own tally.
 */
const prop = defineProps({
    character: Object,
    canEdit: Boolean,
});

const wealth = computed(() => prop.character.wealth ?? null);

const creditRating = computed(
    () =>
        (prop.character.skills ?? []).find((skill) => skill.slug === 'credit_rating')
            ?.pivot?.value ?? 0
);

const editing = ref(false);

const form = useForm({
    cash: 0,
    assets: 0,
});

const open = () => {
    form.clearErrors();
    form.cash = wealth.value?.cash ?? 0;
    form.assets = wealth.value?.assets ?? 0;
    editing.value = true;
};

const save = () => {
    form.put(route('character.wealth.update', { character: prop.character.slug }), {
        preserveScroll: true,
        onSuccess: () => (editing.value = false),
    });
};

/* A purse in the red is a fact of play, not an error — it is simply marked. */
const toneOf = (amount) =>
    amount < 0 ? 'text-cthulhu-blood-400' : 'text-cthulhu-green-900';
</script>

<template>
    <section v-if="wealth" class="panel p-4 sm:p-5">
        <header class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="flex items-center gap-1.5 text-base font-semibold text-cthulhu-green-900">
                    <BanknotesIcon class="size-4 text-cthulhu-green-600" aria-hidden="true" />
                    Wealth
                </h2>
                <span class="chip">{{ wealth.living_standard }}</span>
                <span class="chip tabular">Credit Rating {{ creditRating }}</span>
            </div>

            <button v-if="canEdit && !editing" type="button" class="btn-secondary btn-sm" @click="open">
                Adjust
            </button>
        </header>

        <dl class="grid grid-cols-2 gap-3 lg:grid-cols-3">
            <div class="card">
                <dt class="eyebrow">Cash</dt>
                <dd class="tabular mt-1 text-2xl font-semibold" :class="toneOf(wealth.cash)">
                    {{ formatMoney(wealth.cash) }}
                </dd>
                <p class="field-hint">In a pocket, and spendable now.</p>
            </div>

            <div class="card">
                <dt class="eyebrow">Assets</dt>
                <dd class="tabular mt-1 text-2xl font-semibold" :class="toneOf(wealth.assets)">
                    {{ formatMoney(wealth.assets) }}
                </dd>
                <p class="field-hint">Property and investments, and slow to reach.</p>
            </div>

            <div class="card col-span-2 lg:col-span-1">
                <dt class="eyebrow">Spending level</dt>
                <dd class="tabular mt-1 text-2xl font-semibold text-cthulhu-green-900">
                    {{ formatMoney(wealth.spending_level) }}
                </dd>
                <p class="field-hint">Anything cheaper than this is simply owned — no counting.</p>
            </div>
        </dl>

        <p class="field-hint mt-3">{{ wealth.description }}</p>

        <p v-if="!wealth.settled" class="field-hint mt-1">
            These follow your Credit Rating until you buy something or write a figure of your own.
        </p>

        <!-- Money moves at a table for every reason but shopping: a wallet
             lifted, a fee collected, a horse sold. Both figures are typed over
             directly rather than adjusted by some ledger nobody would keep. -->
        <div v-if="editing" class="mt-4 flex flex-wrap items-end gap-3 border-t border-parchment-300 pt-4">
            <div>
                <label for="wealth-cash" class="field-label">Cash</label>
                <input
                    id="wealth-cash"
                    v-model.number="form.cash"
                    type="number"
                    step="0.01"
                    inputmode="decimal"
                    class="field tabular mt-1 w-32"
                />
            </div>

            <div>
                <label for="wealth-assets" class="field-label">Assets</label>
                <input
                    id="wealth-assets"
                    v-model.number="form.assets"
                    type="number"
                    step="0.01"
                    inputmode="decimal"
                    class="field tabular mt-1 w-32"
                />
            </div>

            <div class="flex items-center gap-2 pb-0.5">
                <button type="button" class="btn-ghost" @click="editing = false">Cancel</button>
                <button type="button" class="btn-primary" :disabled="form.processing" @click="save">
                    {{ form.processing ? 'Saving…' : 'Save' }}
                </button>
            </div>

            <p v-if="form.errors.cash" class="field-error w-full">{{ form.errors.cash }}</p>
            <p v-if="form.errors.assets" class="field-error w-full">{{ form.errors.assets }}</p>
        </div>
    </section>
</template>
