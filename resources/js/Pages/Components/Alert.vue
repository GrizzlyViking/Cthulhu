<template>
    <TransitionRoot as="template" :show="open">
        <Dialog class="relative z-10" @close="open = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-cthulhu-green-950/75 backdrop-blur-sm transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative transform overflow-hidden rounded-2xl bg-cthulhu-green-800 px-4 pb-4 pt-5 text-left shadow-raised ring-1 ring-cthulhu-yellow-500/30 transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                            <div>
                                <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-cthulhu-green-900 ring-1 ring-cthulhu-yellow-500/40">
                                    <ApplicationLogo class="size-7 fill-current text-cthulhu-yellow-400" />
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <DialogTitle as="h3" class="display text-lg leading-6 text-cthulhu-yellow-400">Message from the void</DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-sm text-parchment-100">{{ message.content }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <button
                                    type="button"
                                    @click="messageRead"
                                    class="btn-secondary w-full">
                                    Read
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import axios from "axios";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

let prop = defineProps({message: Object, open: Boolean});
let emit = defineEmits(['mark_read']);

const messageRead = () => {
    axios.put(route('message.read'), {
        message_id: prop.message.id,
    }).then(() => {
        prop.message.content = '';
        prop.message.sender.name = '';
        emit('mark_read');
    });
}
</script>
