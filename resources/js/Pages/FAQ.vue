<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

/**
 * @type {{id: number, question: string, answer: string}[]}
 */
const faqs = [
    {
        id: 1,
        question: 'How do I edit my sheet?',
        answer: 'Flip the "Edit sheet" switch at the top of your character page. That unlocks the name, age, occupation and the rest of the identity fields, and reveals the sheet management tools at the bottom.',
    },
    {
        id: 2,
        question: 'What are the three numbers next to each skill?',
        answer: 'They are the success thresholds: the full skill value for a Regular success, half of it for a Hard success, and a fifth for an Extreme success. Roll equal to or under the one your Keeper asks for.',
    },
    {
        id: 3,
        question: 'How do experience checks work?',
        answer: 'A marker appears beside a skill once it has earned experience. It turns red when the checks reach a tenth of the skill value, meaning the skill is ready to improve. Tap the marker to clear it once you have rolled the improvement.',
    },
    {
        id: 4,
        question: 'Can I change hit points without entering edit mode?',
        answer: 'Yes. Hit points, Sanity, Luck and Magic points stay tappable at all times, because they change constantly during play. Tap the number and enter the new value.',
    },
    {
        id: 5,
        question: 'What is the Key Connection?',
        answer: 'It is the one backstory entry that gives your investigator meaning. It can help you recover Sanity, and the Keeper cannot take it away without giving you a chance to save it. You will find it at the top of the Backstory tab.',
    },
    {
        id: 6,
        question: 'What can the Keeper do?',
        answer: 'The Keeper can view and edit every sheet, send messages to selected players, and roll skills secretly against chosen investigators without revealing the outcome.',
    },
    {
        id: 7,
        question: 'Does this work on a phone?',
        answer: 'That is what it is built for. The sheet is designed to be readable and tappable on a phone at the table, with the sections collapsed behind a single section picker on small screens.',
    },
];
</script>

<template>
    <Head title="FAQ"/>

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Frequently asked questions</h1>
        </template>

        <div class="page">
            <section class="panel p-4 sm:p-8">
                <p class="max-w-2xl text-sm leading-relaxed text-cthulhu-green-700">
                    A quick guide to running your investigator. If something here is still unclear,
                    ask your Keeper at the table.
                </p>

                <dl class="mt-8 grid gap-x-8 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="faq in faqs" :key="faq.id">
                        <dt class="text-base font-semibold text-cthulhu-green-900">{{ faq.question }}</dt>
                        <dd class="mt-2 text-sm leading-relaxed text-cthulhu-green-700">{{ faq.answer }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
