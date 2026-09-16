<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import { parseGear, remainingGear } from './gearTransfer.js';

const props = defineProps({ character: Object, gear: String });
const emit = defineEmits(['transferred']);
const show = ref(false);
const loading = ref(false);
const error = ref('');
const catalogue = ref({ equipment: [], weapon: [] });
const form = useForm({ items: [], gear: '', saved_gear: '' });
const open = async () => {
    loading.value = true;
    error.value = '';
    try {
        const response = await axios.get(route('gear.catalogue', { character: props.character.slug }));
        catalogue.value = response.data;
        form.clearErrors();
        form.gear = props.gear ?? '';
        form.saved_gear = props.character.backstory?.gear ?? '';
        form.items = parseGear(props.gear ?? '', catalogue.value);
        show.value = true;
    } catch {
        error.value = 'The catalogue could not be loaded. Try again.';
    } finally {
        loading.value = false;
    }
};
const submit = () => {
    form.transform((data) => ({ gear: data.gear, saved_gear: data.saved_gear, items: data.items.filter((item) => item.include) }))
        .post(route('gear.transfer', { character: props.character.slug }), {
            preserveScroll: true,
            onSuccess: (page) => {
                emit('transferred', page.props.character.backstory?.gear ?? '');
                show.value = false;
            },
        });
};
const close = () => { if (!form.processing) show.value = false; };
</script>

<template>
    <button type="button" class="btn-secondary btn-sm" :disabled="loading || !gear?.trim()" @click="open">
        {{ loading ? 'Reading gear…' : 'Transfer to Equipment' }}
    </button>
    <p v-if="error" class="field-error">{{ error }}</p>
    <Modal :show="show" max-width="2xl" @close="close">
        <form class="panel flex max-h-[85vh] flex-col gap-4 overflow-y-auto p-4 sm:p-6" @submit.prevent="submit">
            <h2 class="display text-lg text-cthulhu-green-900">Transfer gear to Equipment</h2>
            <p class="field-hint">
                Each line or semicolon starts an item. Check the names, quantities and types before adding.
                New items join the shared catalogue for everyone to use. Transferred parts are removed from Gear &amp; possessions. Review what stays below.
                Items already on this sheet are skipped and stay in the text. No money is spent.
            </p>
            <div v-for="(item, index) in form.items" :key="index" class="card flex flex-col gap-3">
                <label :for="`gear-include-${index}`" class="field-label flex items-center gap-2">
                    <input :id="`gear-include-${index}`" v-model="item.include" type="checkbox" class="size-4 rounded border-parchment-400 text-cthulhu-green-600 focus:ring-cthulhu-green-600" />
                    Include item {{ index + 1 }}
                </label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label :for="`gear-name-${index}`" class="field-label">Name</label>
                        <input :id="`gear-name-${index}`" v-model="item.name" class="field mt-1" maxlength="255" :disabled="!item.include" @input="item.catalogue_id = null; item.remaining = remainingGear(item.source, item.name)" />
                    </div>
                    <div>
                        <label :for="`gear-type-${index}`" class="field-label">Type</label>
                        <select :id="`gear-type-${index}`" v-model="item.type" class="field mt-1" :disabled="!item.include" @change="item.catalogue_id = null">
                            <option value="equipment">Equipment</option>
                            <option value="weapon">Weapon</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label :for="`gear-match-${index}`" class="field-label">Catalogue entry</label>
                    <select :id="`gear-match-${index}`" v-model="item.catalogue_id" class="field mt-1" :disabled="!item.include">
                        <option :value="null">Use the name above; add it if missing</option>
                        <option v-for="entry in catalogue[item.type]" :key="entry.id" :value="entry.id">{{ entry.name }}</option>
                    </select>
                </div>
                <div>
                    <label :for="`gear-quantity-${index}`" class="field-label">Quantity</label>
                    <input :id="`gear-quantity-${index}`" v-model.number="item.quantity" type="number" min="1" max="9999" class="field tabular mt-1 w-24 text-right" :disabled="!item.include" />
                </div>
                <div>
                    <label :for="`gear-remaining-${index}`" class="field-label">Keep in Gear &amp; possessions</label>
                    <textarea :id="`gear-remaining-${index}`" v-model="item.remaining" rows="2" class="field mt-1" :disabled="!item.include"></textarea>
                    <p class="field-hint">Leave blank to remove this whole entry. If you add only part, keep the rest here.</p>
                </div>
                <template v-if="item.type === 'weapon' && !item.catalogue_id">
                    <p class="field-hint">For a new weapon, add any rules you know. Blank rules stay unspecified; an admin can fill them in through the catalogue later.</p>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label :for="`gear-skill-${index}`" class="field-label">Skill (optional)</label>
                            <select :id="`gear-skill-${index}`" v-model="item.skill" class="field mt-1" :disabled="!item.include">
                                <option value="">Unspecified</option>
                                <option v-for="skill in catalogue.skills" :key="skill.slug" :value="skill.slug">{{ skill.display_name }}</option>
                            </select>
                        </div>
                        <div>
                            <label :for="`gear-damage-${index}`" class="field-label">Damage (optional)</label>
                            <input :id="`gear-damage-${index}`" v-model="item.damage" class="field mt-1" maxlength="255" :disabled="!item.include" />
                        </div>
                    </div>
                </template>
            </div>
            <p v-if="!form.items.length" class="field-hint">Write an item in Gear &amp; possessions first.</p>
            <div v-if="Object.keys(form.errors).length" role="alert">
                <p v-for="(message, key) in form.errors" :key="key" class="field-error">{{ message }}</p>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="button" class="btn-ghost" :disabled="form.processing" @click="close">Cancel</button>
                <button type="submit" class="btn-primary" :disabled="form.processing || !form.items.some((item) => item.include)">
                    {{ form.processing ? 'Adding…' : 'Add to Equipment' }}
                </button>
            </div>
        </form>
    </Modal>
</template>
