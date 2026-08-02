<template>
    <Listbox as="div" v-model="selected" @update:model-value="emit('update:modelValue', selected)">
        <ListboxLabel class="field-label">Assigned to</ListboxLabel>
        <div class="relative mt-2">
            <ListboxButton class="field relative cursor-default pr-10 text-left">
                <span class="block truncate">{{ selected?.name ?? 'Unassigned' }}</span>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronUpDownIcon class="size-5 text-cthulhu-green-500" aria-hidden="true" />
                </span>
            </ListboxButton>

            <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <ListboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg bg-parchment-50 py-1 text-sm shadow-raised ring-1 ring-cthulhu-green-900/20 focus:outline-none">
                    <ListboxOption as="template" v-for="option in props.list" :key="option.id" :value="option" v-slot="{ active, selected }">
                        <li :class="[active ? 'bg-cthulhu-green-800 text-parchment-100' : 'text-cthulhu-green-900', 'relative cursor-default select-none py-2 pl-3 pr-9']">
                            <span :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{ option.name }}</span>
                            <span v-if="selected" :class="[active ? 'text-cthulhu-yellow-400' : 'text-cthulhu-green-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                                <CheckIcon class="size-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>

<script setup>
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import { ref } from "vue";

let props = defineProps({
    list: Object,
    open: Boolean,
    modelValue: Number,
    initiallySelected: {
        type: Number,
        default: null,
    }
});

const emit = defineEmits(['update:modelValue'])

/* `initiallySelected` is a user id, so match on id rather than array position. */
const selected = ref(
    Object.values(props.list ?? {}).find((option) => option.id === props.initiallySelected) ?? null
)
</script>
