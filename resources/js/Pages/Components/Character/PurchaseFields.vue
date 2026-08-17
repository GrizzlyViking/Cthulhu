<script setup>
import { formatMoney, PURSES } from '@/Pages/Composables/useMoney.js';

/*
 * What something cost, and which purse it came out of.
 *
 * The price arrives filled in from the handbook, and then it is the player's:
 * they may have haggled it down, been given the thing, or lifted it off a body
 * in a cellar. Nothing here refuses a figure — not even one that empties the
 * purse — because the table is where that gets argued about, not the sheet.
 */
defineProps({
    /** The investigator's wealth, as `Character::wealth` hands it over. */
    wealth: { type: Object, default: null },
    /** Prefix for the field ids, since two of these can be on one page. */
    idPrefix: { type: String, default: 'purchase' },
});

const price = defineModel('price', { type: [Number, String], default: 0 });
const paidFrom = defineModel('paidFrom', { type: String, default: 'cash' });
</script>

<template>
    <div class="flex flex-wrap items-end gap-3">
        <div>
            <label :for="`${idPrefix}-price`" class="field-label text-xs">Paid</label>
            <input
                :id="`${idPrefix}-price`"
                v-model.number="price"
                type="number"
                min="0"
                step="0.01"
                inputmode="decimal"
                class="field tabular mt-1 w-24"
            />
        </div>

        <div>
            <label :for="`${idPrefix}-purse`" class="field-label text-xs">Out of</label>
            <select :id="`${idPrefix}-purse`" v-model="paidFrom" class="field mt-1 w-auto">
                <option v-for="purse in PURSES" :key="purse.value" :value="purse.value">
                    {{ purse.label }}
                </option>
            </select>
        </div>

        <p v-if="wealth" class="tabular pb-2 text-xs text-cthulhu-green-500">
            {{ formatMoney(wealth.cash) }} cash · {{ formatMoney(wealth.assets) }} assets
        </p>
    </div>
</template>
