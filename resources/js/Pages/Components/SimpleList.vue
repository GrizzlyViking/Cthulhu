<template>
    <ul role="list" class="divide-y divide-gray-100">
        <li v-for="item in props.list" :key="item.id" class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ item.name }}</p>
                    <p class="mt-1 text-xs leading-5 text-gray-500">{{ item.content }}</p>
                </div>
            </div>
            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="text-sm leading-6 text-gray-900">{{ item.role }}</p>
                <p v-if="item.sentRelative" class="mt-1 text-xs leading-5 text-gray-500">
                    Sent <time :datetime="item.created_at">{{ item.sentRelative }}</time>
                </p>
                <div v-else class="mt-1 flex items-center gap-x-1.5">
                    <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                        <div v-if="item.isOnline" class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                        <div v-else class="h-1.5 w-1.5 rounded-full bg-red-500" />
                    </div>
                    <p v-if="item.isOnline" class="text-xs leading-5 text-gray-500">Online</p>
                    <p v-else class="text-xs leading-5 text-gray-500">Offline</p>
                </div>
            </div>
        </li>
    </ul>
</template>

<script setup>
const props = defineProps({list: Object})
</script>
