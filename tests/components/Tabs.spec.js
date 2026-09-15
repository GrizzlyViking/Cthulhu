import { afterEach, describe, expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import Tabs from '@/Components/Tabs.vue';

let wrapper;
afterEach(() => wrapper?.unmount());

const activePanel = () => wrapper.get(`#${wrapper.get('[role="tab"][aria-selected="true"]').attributes('aria-controls')}`);

const render = () => {
    wrapper = mount(Tabs, {
        attachTo: document.body,
        props: { tabs: [{ name: 'Skills' }, { name: 'Equipment' }] },
        slots: { Skills: '<p>Skill values</p>', Equipment: '<p>Carried equipment</p>' },
    });
    return wrapper;
};

describe('sheet sections', () => {
    it('keeps the phone picker and desktop tabs on the same panel', async () => {
        render();
        await wrapper.get('select').setValue('1');
        expect(wrapper.get('[role="tab"][aria-selected="true"]').text()).toBe('Equipment');
        expect(activePanel().text()).toBe('Carried equipment');
        expect(wrapper.text()).not.toContain('Skill values');
        await wrapper.findAll('[role="tab"]')[0].trigger('click');
        expect(wrapper.get('select').element.value).toBe('0');
        expect(activePanel().text()).toBe('Skill values');
    });

    it('supports arrow keys and associates the selected tab with its panel', async () => {
        render();
        const first = wrapper.findAll('[role="tab"]')[0];
        first.element.focus();
        await first.trigger('keydown', { key: 'ArrowRight' });
        const selected = wrapper.get('[role="tab"][aria-selected="true"]');
        expect(selected.text()).toBe('Equipment');
        expect(selected.attributes('aria-controls')).toBe(activePanel().attributes('id'));
        expect(document.activeElement).toBe(selected.element);
    });

    it('recovers when the active section is removed and tolerates an empty list', async () => {
        render();
        await wrapper.get('select').setValue('1');
        await wrapper.setProps({ tabs: [{ name: 'Skills' }] });
        expect(activePanel().text()).toBe('Skill values');
        await wrapper.setProps({ tabs: [] });
        expect(wrapper.find('select').exists()).toBe(false);
    });
});
