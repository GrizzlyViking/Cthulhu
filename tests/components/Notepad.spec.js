import { describe, expect, it, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import { reactive } from 'vue';
import Notepad from '@/Pages/Components/Character/Notepad.vue';

const { save } = vi.hoisted(() => ({ save: vi.fn() }));
vi.mock('@inertiajs/vue3', () => ({
    useForm: (data) => {
        let transform = value => value;
        const form = reactive({ ...data, errors: {}, processing: false, recentlySuccessful: false,
            transform: (fn) => { transform = fn; return form; },
            put: (url) => save(url, transform({ notes: form.notes, notes_visibility: form.notes_visibility })),
        });
        return form;
    },
}));

function render(overrides = {}) {
    return mount(Notepad, {
        props: { characterSlug: 'patrick', notepad: { content: '<p>A clue</p>', visibility: 'everyone', canView: true, canEdit: true, canSetVisibility: true, ...overrides } },
        global: { stubs: { QuillEditor: { props: ['content', 'readOnly'], template: '<div data-test="editor" :data-readonly="readOnly">{{ content }}</div>' } } },
    });
}

describe('notepad privacy controls', () => {
    it('saves all three slider positions using their server values', async () => {
        const wrapper = render();
        for (const [index, value] of ['everyone', 'keeper', 'private'].entries()) {
            await wrapper.get('input[type="range"]').setValue(String(index));
            await wrapper.get('button').trigger('click');
            expect(save).toHaveBeenLastCalledWith(expect.any(String), { notes: '<p>A clue</p>', notes_visibility: value });
        }
        wrapper.unmount();
    });
    it('does not mount the editor when the notes are private', () => {
        const wrapper = render({ canView: false, canEdit: false, canSetVisibility: false, content: null });
        expect(wrapper.find('[data-test="editor"]').exists()).toBe(false);
        expect(wrapper.find('input').exists()).toBe(false);
        expect(wrapper.text()).toContain('These notes are private');
        wrapper.unmount();
    });
    it('lets a keeper save shared notes without changing privacy', async () => {
        const wrapper = render({ canSetVisibility: false, visibility: 'keeper' });
        expect(wrapper.find('input').exists()).toBe(false);
        await wrapper.get('button').trigger('click');
        expect(save).toHaveBeenLastCalledWith(expect.any(String), { notes: '<p>A clue</p>' });
        wrapper.unmount();
    });
    it('offers no save control to a reader', () => {
        const wrapper = render({ canEdit: false, canSetVisibility: false });
        expect(wrapper.find('button').exists()).toBe(false);
        expect(wrapper.get('[data-test="editor"]').attributes('data-readonly')).toBe('true');
        wrapper.unmount();
    });
});
