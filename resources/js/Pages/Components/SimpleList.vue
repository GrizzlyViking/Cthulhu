<template>
    <ul role="list" class="flex flex-col gap-2">
        <li
            v-for="item in props.list"
            :key="item.id"
            class="flex flex-wrap items-start justify-between gap-3 rounded-lg bg-parchment-50 p-3 ring-1 ring-parchment-300"
        >
            <div class="min-w-0 flex-auto">
                <p class="text-sm font-semibold text-cthulhu-green-900">{{ item.name }}</p>
                <p class="mt-1 text-sm text-cthulhu-green-700">{{ item.content }}</p>
            </div>

            <div class="flex shrink-0 flex-col items-end gap-1">
                <p v-if="item.role" class="chip">{{ item.role }}</p>
                <p v-if="item.sentRelative" class="text-xs text-cthulhu-green-500">
                    Sent <time :datetime="item.created_at">{{ item.sentRelative }}</time>
                </p>
                <p v-else class="flex items-center gap-1.5 text-xs text-cthulhu-green-500">
                    <span
                        class="size-2 rounded-full"
                        :class="item.isOnline ? 'bg-cthulhu-green-350' : 'bg-cthulhu-green-500/40'"
                    ></span>
                    {{ item.isOnline ? 'Online' : 'Offline' }}
                </p>
            </div>
        </li>
    </ul>

    <p v-if="!props.list || Object.keys(props.list).length === 0" class="py-8 text-center text-sm text-cthulhu-green-500">
        Nothing here yet.
    </p>
</template>

<script setup>
const props = defineProps({list: Object})
</script>
