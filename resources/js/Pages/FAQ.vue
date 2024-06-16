<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
</script>

<template>
    <Head title="FAQ"/>

    <AuthenticatedLayout>
        <div class="bg-gray-900">
            <div class="relative isolate overflow-hidden pt-14">
                <img src="images/cthulhu_man_reading.jpeg" alt=""
                     class="absolute inset-0 -z-10 h-full w-full object-cover"/>
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                     aria-hidden="true">
                    <div
                        class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                        style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"/>
                </div>
                <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                    <div class="bg-cthulhu-green-800">
                        <h1 class="text-4xl limelight-regular tracking-tight text-white sm:text-6xl">Ages of Madness</h1>

                        <div class="bg-white">
                            <div class="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8">
                                <h2 class="text-2xl font-bold leading-10 tracking-tight text-gray-900">Frequently asked questions</h2>
                                <p class="mt-6 max-w-2xl text-base leading-7 text-gray-600">Have a different question and can’t find the answer you’re looking for? Reach out to our support team by <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">sending us an email</a> and we’ll get back to you as soon as we can.</p>
                                <div class="mt-20">
                                    <dl class="space-y-16 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:gap-y-16 sm:space-y-0 lg:grid-cols-3 lg:gap-x-10">
                                        <div v-for="faq in faqs" :key="faq.id">
                                            <dt class="text-base font-semibold leading-7 text-gray-900">{{ faq.question }}</dt>
                                            <dd class="mt-2 text-base leading-7 text-gray-600">{{ faq.answer }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <ul class="mt-6 text-lg leading-8 text-gray-300">
                            <li>Rapid deployment of the page.</li>
                            <li>Note that due to the quick development, some features may seem unconventional or uniquely intuitive.</li>
                            <li>The interface is designed for optimal use on smartphones.</li>
                        </ul>
                        <p class="mt-6 text-lg leading-8 text-gray-300">I have created a simple app/webpage for managing character sheets quickly and efficiently. The main goals were:

                        </p>
                        <p class="mt-6 text-lg leading-8 text-gray-300">Key Features:</p>

                            <ul class="mt-6 text-lg leading-8 text-gray-300">
                                <li>Character Sheet: Track character details like skills and attributes. Click on a skill name to gain one
                                    experience
                                    point. As you accumulate points, the color changes, indicating readiness for a roll. To reset points,
                                    simply
                                    click on the number with the surrounding circle.
                                </li>
                                <li>Changing Values: Utilize the edit slider in the top left corner to adjust skill levels, names,
                                    birthplaces,
                                    etc.
                                    Additionally, there are buttons at the bottom, mostly for infrequent actions like uploading a new image.
                                </li>
                                <li>Interactive Elements: Certain sections remain editable even when not in edit mode, such as Hit Points,
                                    Sanity, Luck,
                                    Magic Points.
                                </li>
                                <li>Dashboard: Consider renaming it to "Keepers Page" for exclusive access by Søren. For transparency, it is
                                    currently
                                    visible to all users.
                                </li>
                                <li>Messages: Søren can communicate with other users by selecting one or more and sending messages.</li>
                                <li>Roll Function: A proof of concept where users can roll hidden checks against selected characters without
                                    revealing
                                    the outcome, allowing for uncertainty and surprise in gameplay.
                                </li>
                            </ul>
                    </div>
                </div>
                <div
                    class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
                    aria-hidden="true">
                    <div
                        class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
                        style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"/>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>

</template>

<style scoped>
.limelight-regular {
    font-family: "Limelight", sans-serif;
    font-weight: 400;
    font-style: normal;
}
</style>
