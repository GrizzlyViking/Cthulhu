import { expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import RegularHalfFifth from '@/Pages/Components/RegularHalfFifth.vue';

it('exposes editable characteristics as named buttons and keeps display-only figures passive', async () => {
    const wrapper = mount(RegularHalfFifth, { props: { skillValue: 75, interactive: true }, attrs: { 'aria-label': 'Adjust strength' } });
    expect(wrapper.get('button').attributes('type')).toBe('button');
    expect(wrapper.get('button').attributes('aria-label')).toBe('Adjust strength');
    expect(wrapper.findAll('span').map(cell => cell.text())).toEqual(['75', '38', '15']);
    await wrapper.setProps({ interactive: false });
    expect(wrapper.find('button').exists()).toBe(false);
    wrapper.unmount();
});
