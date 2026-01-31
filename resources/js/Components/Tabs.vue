<template>
    <div>
        <div class="grid grid-cols-1 sm:hidden">
            <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
            <select
                aria-label="Select a tab"
                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
                @change="onSelectChange($event)"
            >
                <option v-for="(tab, index) in tabs" :key="tab.name" :value="index" :selected="index === activeTabIndex">{{ tab.name }}</option>
            </select>
            <ChevronDownIcon class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500" aria-hidden="true" />
        </div>
        <div class="hidden sm:block">
            <div class="border-none border-cthulhu-green-300 px-3 sm:px-6 lg:px-8">
                <nav class="-mb-px flex space-x-2" aria-label="Tabs">
                    <a
                        v-for="(tab, index) in tabs"
                        :key="tab.name"
                        href="#"
                        @click.prevent="activeTabIndex = index"
                        :class="[
                            index === activeTabIndex
                                ? 'border-cthulhu-green-300 bg-cthulhu-green-200 text-cthulhu-green-900 border-b-cthulhu-green-200'
                                : 'border-transparent text-cthulhu-green-600 hover:border-cthulhu-green-300 hover:text-cthulhu-green-800',
                            'group inline-flex items-center border-t border-l border-r px-4 py-2 text-sm font-medium rounded-t-md'
                        ]"
                        :aria-current="index === activeTabIndex ? 'page' : undefined"
                    >
                        <component :is="tab.icon" :class="[index === activeTabIndex ? 'text-cthulhu-green-700' : 'text-cthulhu-green-400 group-hover:text-cthulhu-green-500', '-ml-0.5 mr-2 size-5']" aria-hidden="true" />
                        <span>{{ tab.name }}</span>
                    </a>
                </nav>
            </div>
        </div>

        <div>
            <slot :name="tabs[activeTabIndex].name" :tab="tabs[activeTabIndex]">
                <!-- Fallback content if no slot matches -->
            </slot>
        </div>
    </div>
</template>

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
