<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Pages/Components/Admin/Pagination.vue';
import { DocumentIcon, ArrowDownTrayIcon, XMarkIcon } from '@heroicons/vue/20/solid';
import { shrink } from '@/Pages/Composables/prepareImage.js';

const props = defineProps({ games: Array, game: Object, resources: Object });
const current = computed(() => props.games.find(game => game.active));
const previous = computed(() => props.games.filter(game => !game.active));
const uploading = ref(false);
const progress = ref('');
const errors = ref([]);
const preview = ref(null);
const fileInput = ref(null);
const fileUrl = (resource, download = false) => route('resources.file', { resource: resource.id, ...(download ? { download: 1 } : {}) });
const sizeLabel = (size) => size >= 1048576 ? `${(size / 1048576).toFixed(1)} MB` : `${Math.max(1, Math.round(size / 1024))} KB`;

async function upload(files) {
    if (uploading.value || !props.game || !files?.length) return;
    // Bind the batch to the game that was selected when the player chose it.
    const gameId = props.game.id;
    uploading.value = true;
    errors.value = [];
    let completed = 0;
    try {
        for (const original of Array.from(files)) {
            progress.value = `Uploading ${++completed} of ${files.length}: ${original.name}`;
            try {
                // Keep handouts readable; PNGs and GIFs retain transparency and animation.
                const file = ['image/jpeg', 'image/heic', 'image/heif'].includes(original.type) ? await shrink(original, 3200) : original;
                if (file.size > 15 * 1024 * 1024) throw new Error('This file is larger than 15 MB. Save a smaller copy and try again.');
                const data = new FormData();
                data.append('file', file);
                await axios.post(route('resources.store', { game: gameId }), data);
            } catch (error) {
                errors.value.push(`${original.name}: ${error.response?.data?.errors?.file?.[0] ?? error.response?.data?.message ?? error.message ?? 'The upload did not finish. Please try again.'}`);
            }
        }
    } finally {
        uploading.value = false;
        progress.value = '';
        if (fileInput.value) fileInput.value.value = '';
        router.reload({ only: ['resources', 'games'] });
    }
}
function paste(event) {
    const files = event.clipboardData?.files;
    if (files?.length) {
        event.preventDefault();
        upload(files);
    }
}
</script>

<template>
    <Head title="Resources" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Resources</h1>
            <span v-if="game" class="chip-brass">{{ game.name }}</span>
        </template>
        <div class="page">
            <nav aria-label="Campaign resources" class="flex flex-col gap-3">
                <Link v-if="current" :href="route('resources.index')" class="btn-ghost-on-dark self-start" :aria-current="game?.id === current.id ? 'page' : undefined">{{ current.name }} · Current campaign</Link>
                <details v-if="previous.length" :open="game && !game.active" class="text-cthulhu-green-200">
                    <summary class="cursor-pointer rounded py-2 text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">Previous games <span class="tabular">({{ previous.length }})</span></summary>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <Link v-for="item in previous" :key="item.id" :href="route('resources.index', { game: item.id })" class="btn-ghost-on-dark" :aria-current="game?.id === item.id ? 'page' : undefined">{{ item.name }} <span class="tabular">({{ item.count }})</span></Link>
                    </div>
                </details>
            </nav>
            <template v-if="game">
                <section class="panel p-4 sm:p-5">
                    <h2 class="text-base font-semibold text-cthulhu-green-900">{{ game.active ? 'Share something with the table' : `Resources from ${game.name}` }}</h2>
                    <p class="field-hint">Images, PDF and Word documents belong to this campaign, and everyone in the group can open them.</p>
                    <div class="mt-4 rounded-md border-2 border-dashed border-parchment-400 p-5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-green-800" tabindex="0" aria-label="Drop files or paste an image here" @dragover.prevent @drop.prevent="upload($event.dataTransfer.files)" @paste="paste">
                        <label for="resource-files" class="field-label">Choose images or documents</label>
                        <input id="resource-files" ref="fileInput" type="file" multiple accept="image/jpeg,image/png,image/gif,image/webp,image/avif,image/heic,image/heif,.pdf,.doc,.docx" :disabled="uploading" class="mt-3 block w-full min-w-0 text-sm file:mr-3 file:rounded file:border-0 file:bg-cthulhu-green-800 file:px-4 file:py-2.5 file:font-semibold file:text-parchment-100" @change="upload($event.target.files)" />
                        <p class="field-hint mt-3">Choose from your phone, drop files here, or focus this box and paste an image copied from a message. Up to 15 MB per file; large phone photos are reduced before uploading.</p>
                    </div>
                    <p v-if="uploading" role="status" class="mt-3 break-words text-sm text-cthulhu-green-700">{{ progress }}</p>
                    <ul v-if="errors.length" role="alert" class="mt-3 flex flex-col gap-2 text-sm text-cthulhu-blood-400"><li v-for="error in errors" :key="error" class="break-words">{{ error }}</li></ul>
                </section>
                <section class="panel p-4 sm:p-5">
                    <div class="mb-4 flex items-baseline justify-between gap-2">
                        <h2 class="text-base font-semibold text-cthulhu-green-900">Campaign files</h2>
                        <span class="tabular text-sm text-cthulhu-green-500">{{ resources.total }} {{ resources.total === 1 ? 'file' : 'files' }}</span>
                    </div>
                    <ul v-if="resources.data.length" class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                        <li v-for="resource in resources.data" :key="resource.id" class="card flex min-w-0 flex-col !p-0 overflow-hidden">
                            <button v-if="resource.mime_type.startsWith('image/')" type="button" class="block aspect-[4/3] w-full bg-parchment-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-green-800" :aria-label="`Open ${resource.name}`" @click="preview = resource">
                                <img :src="fileUrl(resource)" alt="" loading="lazy" class="size-full object-contain" />
                            </button>
                            <div v-else class="flex aspect-[4/3] items-center justify-center bg-parchment-200"><DocumentIcon class="size-12 text-cthulhu-green-500" aria-hidden="true" /></div>
                            <div class="flex flex-1 flex-col gap-2 p-3">
                                <h3 class="break-words text-sm font-semibold text-cthulhu-green-900">{{ resource.name }}</h3>
                                <p class="tabular text-xs text-cthulhu-green-500">{{ sizeLabel(resource.size) }}</p>
                                <!-- The response is a file, not an Inertia page. -->
                                <a :href="fileUrl(resource, true)" class="btn-ghost btn-sm mt-auto self-start"><ArrowDownTrayIcon class="size-4" aria-hidden="true" />Download<span class="sr-only"> {{ resource.name }}</span></a>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="py-6">
                        <h3 class="display text-lg text-cthulhu-green-900">No files in this campaign yet</h3>
                        <p class="field-hint">Upload a clue, a map, or a photograph to keep it with the game.</p>
                    </div>
                    <Pagination :paginator="resources" />
                </section>
            </template>
            <section v-else class="panel p-5">
                <h2 class="display text-lg text-cthulhu-green-900">No current campaign</h2>
                <p class="field-hint">An admin can start a campaign on the Group page. Files from earlier campaigns remain under Previous games above.</p>
            </section>
        </div>
        <Modal :show="!!preview" max-width="2xl" @close="preview = null">
            <div v-if="preview" class="panel p-4">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h2 class="min-w-0 break-words text-base font-semibold">{{ preview.name }}</h2>
                    <button type="button" class="btn-ghost shrink-0" aria-label="Close image" @click="preview = null"><XMarkIcon class="size-5" aria-hidden="true" /></button>
                </div>
                <img :src="fileUrl(preview)" :alt="preview.name" class="mx-auto max-h-[75vh] max-w-full object-contain" />
                <a :href="fileUrl(preview, true)" class="btn-secondary mt-3">Download image</a>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
