<template>
            <form @submit.prevent="sendMessage" class="relative">
                <div class="bg-white overflow-hidden rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-indigo-600">
                    <label for="comment" class="sr-only">Add your comment</label>
                    <textarea rows="3" v-model="messagePayload.content" class="block w-full resize-none border-0 bg-transparent py-1.5 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="Add your comment..." />

                    <!-- Spacer element to match the height of the toolbar -->
                    <div class="py-2" aria-hidden="true">
                        <!-- Matches height of button in toolbar (1px border + 36px content height) -->
                        <div class="py-px">
                            <div class="h-9" />
                        </div>
                    </div>
                </div>

                <div class="absolute inset-x-0 bottom-0 flex justify-between py-2 pl-3 pr-2">
                    <div class="flex items-center space-x-5">
                        <div class="flex items-center">
                            <div class="flex items-center gap-6">
                                <span v-for="recipient in page.props.auth.listOfMessageUsers" :key="recipient.id" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ recipient.name.replace(/\s.*$/, '') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Post</button>
                    </div>
                </div>
            </form>
</template>

<script setup>
import {useForm, usePage} from "@inertiajs/vue3";

let page = usePage();

const messagePayload = useForm({
    recipients: [],
    content: ''
});

const sendMessage = () => {
    messagePayload.recipients = page.props.auth.listOfMessageUsers.map(item => item.id)
    messagePayload.post(route('message.send'), {
        preserveScroll: true,
        onSuccess: () => {
            messagePayload.reset('content')
            page.props.auth.listOfMessageUsers.splice(0)
        },
    });
}
</script>
