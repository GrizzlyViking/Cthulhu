import { expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import Dropdown from '@/Components/Dropdown.vue';

it('returns keyboard focus to its trigger when Escape dismisses the links', async () => {
    const wrapper = mount(Dropdown, {
        attachTo: document.body,
        slots: { trigger: '<button>Investigators</button>', content: '<a href="#sheet">Open sheet</a>' },
    });
    await wrapper.get('button').trigger('click');
    wrapper.get('a').element.focus();
    await wrapper.get('a').trigger('keydown', { key: 'Escape' });
    expect(document.activeElement).toBe(wrapper.get('button').element);
    expect(wrapper.get('a').isVisible()).toBe(false);
    wrapper.unmount();
});
