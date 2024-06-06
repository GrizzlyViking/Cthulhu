<template>
    <form action="#" class="relative">
        <div class="overflow-hidden rounded-lg border border-gray-300 shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
            <textarea
                rows="2"
                v-model="message.content"
                class="p-3 block w-full resize-none border-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm leading-6" placeholder="Write a description..." />

            <!-- Spacer element to match the height of the toolbar -->
            <div aria-hidden="true" class="bg-white">
                <div class="py-2">
                    <div class="h-9" />
                </div>
                <div class="h-px" />
                <div class="py-2">
                    <div class="py-px">
                        <div class="h-9" />
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute inset-x-px bottom-0">
            <!-- Actions: These are just examples to demonstrate the concept, replace/wire these up however makes sense for your project. -->
            <div class="flex flex-nowrap justify-end space-x-2 px-2 py-2 sm:px-3">
                <div class="flex items-center gap-6">
                        <span v-for="recipient in selectedForMessage" :key="recipient.id" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ recipient.name }}</span>
                </div>
            </div>
            <div class="flex items-center justify-between space-x-3 border-t border-gray-200 px-2 py-2 sm:px-3">
                <div class="flex">
                    <button type="button" class="hidden group -my-2 -ml-2 inline-flex items-center rounded-full px-3 py-2 text-left text-gray-400">
                        <PaperClipIcon class="-ml-1 mr-2 h-5 w-5 group-hover:text-gray-500" aria-hidden="true" />
                        <span class="text-sm italic text-gray-500 group-hover:text-gray-600">Attach a file</span>
                    </button>
                </div>
                <div class="flex-shrink-0">
                    <button type="submit" class="inline-flex items-center rounded-md bg-cthulhu-green-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-400">Send</button>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue'
import { PaperClipIcon } from '@heroicons/vue/20/solid'
import {useForm} from "@inertiajs/vue3";

let prop = defineProps({selectedForMessage: Object})

const message = useForm({
    recipients: prop.selectedForMessage,
    content: ''
});

const assignees = [
    { name: 'Unassigned', value: null },
    {
        name: 'Wade Cooper',
        value: 'wade-cooper',
        avatar:
            'https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    },
    // More items...
]


const assigned = ref(assignees[0])
</script>
