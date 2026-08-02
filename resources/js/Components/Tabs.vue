<script setup>
import { ref } from 'vue'
import { ChevronDownIcon } from '@heroicons/vue/16/solid'

defineProps({
    tabs: {
        type: Array,
        required: true,
    },
})

const activeTabIndex = ref(0)

const onSelectChange = (event) => {
    activeTabIndex.value = parseInt(event.target.value)
}
</script>

<template>
    <div class="space-y-3">
        <!-- Mobile: a select is easier to hit than a row of tabs. -->
        <div class="relative sm:hidden">
            <select
                aria-label="Select a section"
                class="field appearance-none pe-9 font-semibold"
                @change="onSelectChange($event)"
            >
                <option v-for="(tab, index) in tabs" :key="tab.name" :value="index" :selected="index === activeTabIndex">
                    {{ tab.name }}
                </option>
            </select>
            <ChevronDownIcon
                class="pointer-events-none absolute end-3 top-1/2 size-5 -translate-y-1/2 text-cthulhu-green-500"
                aria-hidden="true"
            />
        </div>

        <div class="hidden sm:block">
            <nav class="flex flex-wrap gap-1 rounded-xl bg-cthulhu-green-900/60 p-1" aria-label="Tabs">
                <button
                    v-for="(tab, index) in tabs"
                    :key="tab.name"
                    type="button"
                    :class="[
                        'group inline-flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500',
                        index === activeTabIndex
                            ? 'bg-parchment-100 text-cthulhu-green-900 shadow-sm'
                            : 'text-cthulhu-green-200 hover:bg-cthulhu-green-800 hover:text-parchment-100',
                    ]"
                    :aria-current="index === activeTabIndex ? 'page' : undefined"
                    @click="activeTabIndex = index"
                >
                    <component
                        :is="tab.icon"
                        :class="[
                            'size-5',
                            index === activeTabIndex ? 'text-cthulhu-green-600' : 'text-cthulhu-green-300 group-hover:text-cthulhu-yellow-400',
                        ]"
                        aria-hidden="true"
                    />
                    <span>{{ tab.name }}</span>
                </button>
            </nav>
        </div>

        <div>
            <slot :name="tabs[activeTabIndex].name" :tab="tabs[activeTabIndex]" />
        </div>
    </div>
</template>
