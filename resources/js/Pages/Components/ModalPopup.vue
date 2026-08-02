<template>
    <TransitionRoot appear :show="props.isOpen" as="template">
        <Dialog as="div" @close="emit('modal-close')" class="relative z-10" :initialFocus="props.initialFocus">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-cthulhu-green-950/70 backdrop-blur-sm"/>
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-parchment-100 p-6 text-left align-middle shadow-raised ring-1 ring-cthulhu-green-900/20 transition-all">
                            <DialogTitle as="h3" class="text-base font-semibold leading-6 text-cthulhu-green-900">
                                <slot name="title">Title</slot>
                            </DialogTitle>
                            <div class="mt-3 max-h-[60vh] overflow-y-auto text-sm text-cthulhu-green-800">
                                <slot name="default"></slot>
                            </div>
                            <slot name="buttons">
                                <div class="mt-4 flex justify-end gap-2">
                                    <button
                                        v-if="slots.response1 && $slots.response1().length"
                                        ref="closeBtnRef"
                                        type="button"
                                        class="btn-secondary btn-sm"
                                        @click="emit('response1')"
                                    >
                                        <slot name="response1"></slot>
                                    </button>
                                    <button
                                        v-if="slots.response2 && $slots.response2().length"
                                        type="button"
                                        class="btn-primary btn-sm"
                                        @click="emit('response2')"
                                    >
                                        <slot name="response2"></slot>
                                    </button>
                                </div>
                            </slot>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import {ref, useSlots} from "vue";
const closeBtnRef = ref(null);

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    initialFocus: {
        type: Object,
        default: null
    }
})

const emit = defineEmits([
    "modal-close",
    'response1',
    'response2'
]);

const slots = useSlots()
</script>
