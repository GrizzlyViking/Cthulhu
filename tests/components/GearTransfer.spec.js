import { mount, flushPromises } from '@vue/test-utils';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { afterEach, describe, expect, it, vi } from 'vitest';
import GearTransfer from '@/Pages/Components/Character/GearTransfer.vue';

vi.mock('axios', () => ({ default: { get: vi.fn() } }));
const catalogue = { equipment: [{ id: 1, name: 'Lantern' }], weapon: [{ id: 2, name: 'Revolver' }], skills: [] };
const mountTransfer = (gear = 'Lantern; service revolver\nLucky stone') => mount(GearTransfer, {
    props: { character: { slug: 'investigator' }, gear },
    global: { stubs: { Modal: { props: ['show'], template: '<div v-if="show"><slot /></div>' } } },
});
afterEach(() => vi.restoreAllMocks());

describe('gear transfer dialog', () => {
    it('submits corrected weapon selections and quantities, excluding unchecked rows', async () => {
        axios.get.mockResolvedValue({ data: catalogue });
        const post = vi.spyOn(router, 'post').mockImplementation(() => {});
        const wrapper = mountTransfer();
        await wrapper.get('button').trigger('click');
        await flushPromises();
        await wrapper.get('#gear-type-1').setValue('weapon');
        await wrapper.get('#gear-match-1').setValue('2');
        await wrapper.get('#gear-quantity-0').setValue('3');
        await wrapper.get('#gear-include-2').setValue(false);
        await wrapper.get('form').trigger('submit');
        expect(post).toHaveBeenCalledOnce();
        const [, payload, options] = post.mock.calls[0];
        expect(payload.items).toHaveLength(2);
        expect(payload.items[0]).toMatchObject({ catalogue_id: 1, quantity: 3 });
        expect(payload.items[1]).toMatchObject({ type: 'weapon', catalogue_id: 2 });
        options.onSuccess({ props: { character: { backstory: { gear: 'Lucky stone' } } } });
        await flushPromises();
        expect(wrapper.find('form').exists()).toBe(false);
        expect(wrapper.emitted('transferred')[0]).toEqual(['Lucky stone']);
    });
    it('sends the omitted words as the editable remainder of a shortened name', async () => {
        axios.get.mockResolvedValue({ data: catalogue });
        const post = vi.spyOn(router, 'post').mockImplementation(() => {});
        const wrapper = mountTransfer('Worn tweed suit and travelling coat');
        await wrapper.get('button').trigger('click');
        await flushPromises();
        await wrapper.get('#gear-name-0').setValue('traveling coat');
        expect(wrapper.get('#gear-remaining-0').element.value).toBe('Worn tweed suit');
        await wrapper.get('form').trigger('submit');
        expect(post.mock.calls[0][1]).toMatchObject({
            gear: 'Worn tweed suit and travelling coat',
            items: [{ name: 'traveling coat', remaining: 'Worn tweed suit', source_index: 0 }],
        });
    });
    it('reports a catalogue failure and allows retrying without losing the source', async () => {
        axios.get.mockRejectedValueOnce(new Error('offline')).mockResolvedValueOnce({ data: catalogue });
        const wrapper = mountTransfer();
        await wrapper.get('button').trigger('click');
        await flushPromises();
        expect(wrapper.text()).toContain('The catalogue could not be loaded');
        await wrapper.get('button').trigger('click');
        await flushPromises();
        expect(wrapper.get('#gear-name-1').element.value).toBe('service revolver');
    });
});
