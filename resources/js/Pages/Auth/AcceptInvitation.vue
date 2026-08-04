<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    invalid: {
        type: Boolean,
        default: false,
    },
    token: {
        type: String,
        default: null,
    },
    email: {
        type: String,
        default: null,
    },
    groupName: {
        type: String,
        default: null,
    },
});

const form = useForm({
    name: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('invitation.store', props.token), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Accept invitation" />

        <div v-if="invalid" class="text-center">
            <h1 class="display text-xl text-cthulhu-green-900">This invitation is no longer valid</h1>

            <p class="mt-3 text-sm text-cthulhu-green-700">
                The link may have expired or already been used. Ask your Keeper to send you a new invitation.
            </p>

            <Link
                :href="route('login')"
                class="mt-4 inline-block underline text-sm text-cthulhu-green-700 hover:text-cthulhu-green-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cthulhu-green-600"
            >
                Back to login
            </Link>
        </div>

        <template v-else>
            <p class="eyebrow">Invitation</p>

            <h1 class="display mt-1 text-xl text-cthulhu-green-900">
                You've been invited to join {{ groupName }}
            </h1>

            <p class="mt-2 text-sm text-cthulhu-green-700">
                Choose a name and password to create your account.
            </p>

            <form class="mt-4" @submit.prevent="submit">
                <div>
                    <InputLabel value="Email" />

                    <p class="mt-1 text-sm text-cthulhu-green-700">{{ email }}</p>
                </div>

                <div class="mt-4">
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirm password" />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Join the group
                    </PrimaryButton>
                </div>
            </form>
        </template>
    </GuestLayout>
</template>
