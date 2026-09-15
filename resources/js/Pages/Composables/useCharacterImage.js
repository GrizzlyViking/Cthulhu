import { ref } from 'vue';
import { shrink } from './prepareImage.js';
import { router } from '@inertiajs/vue3';

/**
 * The longest edge each picture is kept at. These match App\Misc\CharacterImage's
 * boxes — the server scales to the same figures whatever arrives, so a browser
 * that cannot do the work below loses nothing but the upload time.
 */
const LONGEST_EDGE = { portrait: 1200, banner: 2000 };

/**
 * Uploading the two pictures a sheet carries.
 *
 * @param {import('vue').Ref<string>|{value: string}|string} slug
 */
export function useCharacterImage(slug) {
    /** Which shape is in flight, so its own card can say so. */
    const uploading = ref(null);

    /** @type {import('vue').Ref<Object<string, string|null>>} */
    const errors = ref({ portrait: null, banner: null });

    const character = () => (typeof slug === 'string' ? slug : slug.value);

    /**
     * @param {'portrait'|'banner'} shape
     * @param {File|undefined} file
     */
    const upload = async (shape, file) => {
        if (! file) {
            return;
        }

        errors.value = { ...errors.value, [shape]: null };
        uploading.value = shape;

        const image = await shrink(file, LONGEST_EDGE[shape]);

        router.post(
            route('character.image', { character: character(), shape }),
            { image },
            {
                forceFormData: true,
                preserveScroll: true,
                onError: (bag) => {
                    errors.value = { ...errors.value, [shape]: bag.image ?? null };
                },
                onFinish: () => {
                    uploading.value = null;
                },
            }
        );
    };

    /**
     * Take a picture back off the sheet.
     *
     * @param {'portrait'|'banner'} shape
     */
    const clear = (shape) => {
        errors.value = { ...errors.value, [shape]: null };

        router.delete(route('character.image.destroy', { character: character(), shape }), {
            preserveScroll: true,
        });
    };

    return { uploading, errors, upload, clear };
}
