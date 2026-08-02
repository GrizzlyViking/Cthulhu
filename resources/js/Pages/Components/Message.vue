<template>
    <form class="flex flex-col gap-3" @submit.prevent="sendMessage">
        <label for="message_content" class="sr-only">Message</label>
        <textarea
            id="message_content"
            v-model="messagePayload.content"
            rows="3"
            class="field resize-none"
            placeholder="Whisper something into the void…"
        />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                <span v-if="!page.props.auth.listOfMessageUsers.length" class="text-xs text-cthulhu-green-500">
                    Select players on the dashboard to address them.
                </span>
                <span
                    v-for="recipient in page.props.auth.listOfMessageUsers"
                    :key="recipient.id"
                    class="chip"
                >
                    {{ recipient.name.replace(/\s.*$/, '') }}
                </span>
            </div>

            <button
                type="submit"
                class="btn-primary"
                :disabled="messagePayload.processing || !messagePayload.content"
            >
                {{ messagePayload.processing ? 'Sending…' : 'Send' }}
            </button>
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
