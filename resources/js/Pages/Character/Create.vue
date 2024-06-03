<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const page = usePage()

const character = useForm({
    name: '',
    user_id: 1,
    occupation: '',
    age: 0,
    gender: '',
    residence: '',
    birthplace: '',
})

const step = {
    first: true,
    second: false,
    third: false,
    fourth: false,
}

const genders = [
    'Male',
    'Female',
    'Other'
]

const submitProfile = () => {
    character.post(route('create.step.first'))
}
</script>

<template>
    <Head title="Character"/>

    <AuthenticatedLayout>
        <template #header>
            Character Create
        </template>

        <div class="m-3" v-if="step.first">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm rounded-lg">
                        <form class="px-12 py-8">
                            <div class="space-y-12">

                                <div class="border-b border-gray-900/10 pb-12">
                                    <h2 class="text-base font-semibold leading-7 text-gray-900">Character information</h2>
                                    <p class="mt-1 text-sm leading-6 text-gray-600">Please provide the initial information to setup character.</p>

                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">Full name</label>
                                            <div class="mt-2">
                                                <input type="text" v-model="character.name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <div v-if="$page.props.errors.name" v-text="$page.props.errors.name" class="text-red-500 text-xs mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="user" class="block text-sm font-medium leading-6 text-gray-900">User</label>
                                            <div class="mt-2">
                                                <select v-model="character.user_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                    <option value="">choose user</option>
                                                    <option v-for="user in page.props.auth.users" :key="user.id" :value="user.id">{{ user.name }}</option>
                                                </select>
                                                <div v-if="$page.props.errors.user_id" v-text="$page.props.errors.user_id" class="text-red-500 text-xs mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="occupation" class="block text-sm font-medium leading-6 text-gray-900">Occupation</label>
                                            <div class="mt-2">
                                                <input type="text" v-model="character.occupation" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <div v-if="$page.props.errors.occupation" v-text="$page.props.errors.occupation" class="text-red-500 text-xs mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="residence" class="block text-sm font-medium leading-6 text-gray-900">Residence</label>
                                            <div class="mt-2">
                                                <input type="text" v-model="character.residence" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <div v-if="$page.props.errors.residence" v-text="$page.props.errors.residence" class="text-red-500 text-xs mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="birthplace" class="block text-sm font-medium leading-6 text-gray-900">Birthplace</label>
                                            <div class="mt-2">
                                                <input type="text" v-model="character.birthplace" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <div v-if="$page.props.errors.birthplace" v-text="$page.props.errors.birthplace" class="text-red-500 text-xs mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="user" class="block text-sm font-medium leading-6 text-gray-900">Gender</label>
                                            <div class="mt-2">
                                                <select v-model="character.gender" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                    <option value="">choose...</option>
                                                    <option v-for="gender in genders" :key="gender">{{ gender }}</option>
                                                </select>
                                                <div v-if="$page.props.errors.gender" v-text="$page.props.errors.gender" class="text-red-500 text-xs mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">Age</label>
                                            <div class="mt-2">
                                                <input type="number" v-model="character.age" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <div v-if="$page.props.errors.age" v-text="$page.props.errors.age" class="text-red-500 text-xs mt-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                                <button type="button" @click.prevent="submitProfile" class="rounded-md bg-cthulhu-green-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                            </div>


                        </form>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>
