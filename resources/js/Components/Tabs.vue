<script setup>
import { ref, useId, watch } from 'vue';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    tabs: { type: Array, required: true },
});

const activeTabIndex = ref(0);
const selectId = `sheet-section-${useId()}`;

watch(() => props.tabs.length, (length) => {
    activeTabIndex.value = Math.min(activeTabIndex.value, Math.max(0, length - 1));
});
</script>

<template>
    <TabGroup v-if="tabs.length" :selected-index="activeTabIndex" as="div" class="space-y-3" @change="activeTabIndex = $event">
        <div class="relative sm:hidden">
            <label :for="selectId" class="sr-only">Select a section</label>
            <select :id="selectId" v-model.number="activeTabIndex" class="field appearance-none pe-9 font-semibold">
                <option v-for="(tab, index) in tabs" :key="tab.name" :value="index">{{ tab.name }}</option>
            </select>
            <ChevronDownIcon class="pointer-events-none absolute end-3 top-1/2 size-5 -translate-y-1/2 text-cthulhu-green-500" aria-hidden="true" />
        </div>

        <TabList class="hidden gap-1 border-b border-cthulhu-yellow-500/30 sm:flex" aria-label="Sheet sections">
            <Tab v-for="tab in tabs" :key="tab.name" v-slot="{ selected }" as="template">
                <button
                    type="button"
                    class="group inline-flex min-w-0 flex-1 items-center justify-center gap-2 rounded-t-md border-b-2 px-3 py-3 text-sm font-semibold transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500"
                    :class="selected ? 'border-cthulhu-yellow-500 bg-parchment-100 text-cthulhu-green-900' : 'border-transparent text-cthulhu-green-200 hover:bg-cthulhu-green-800 hover:text-parchment-100'"
                >
                    <component :is="tab.icon" v-if="tab.icon" class="size-5 shrink-0" aria-hidden="true" />
                    {{ tab.name }}
                </button>
            </Tab>
        </TabList>

        <TabPanels>
            <TabPanel v-for="tab in tabs" :key="tab.name" class="focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-cthulhu-yellow-500">
                <slot :name="tab.name" :tab="tab" />
            </TabPanel>
        </TabPanels>
    </TabGroup>
</template>
