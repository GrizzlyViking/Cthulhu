import { ref } from 'vue';

/**
 * Which headings in a folded list are open.
 *
 * The pickers hold this rather than the sections themselves, so that searching
 * can throw the lot open and closing the modal can fold it all back up.
 */
export function useFoldedSections() {
    const open = ref([]);

    const isOpen = (name) => open.value.includes(name);

    const toggle = (name) => {
        open.value = isOpen(name)
            ? open.value.filter((other) => other !== name)
            : [...open.value, name];
    };

    const openAll = (names) => (open.value = [...names]);

    const closeAll = () => (open.value = []);

    return { open, isOpen, toggle, openAll, closeAll };
}
