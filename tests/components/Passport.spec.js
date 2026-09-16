import { mount, flushPromises } from '@vue/test-utils';
import { afterEach, expect, it, vi } from 'vitest';
import Passport from '@/Pages/Components/Character/Passport.vue';

let wrapper;
afterEach(() => { wrapper?.unmount(); vi.unstubAllGlobals(); });

it('keeps zero hit points and empty guns visible, and drops ammunition when the viewport cannot fit it', async () => {
    const observers = [];
    vi.stubGlobal('ResizeObserver', class {
        constructor(callback) { this.callback = callback; this.elements = []; observers.push(this); }
        observe(element) { this.elements.push(element); }
        disconnect() {}
    });
    wrapper = mount(Passport, { attachTo: document.body, props: { character: {
        name: 'Ada Marsh', avatar: 'ada.jpg', hit_points: 0, age: 34,
        weapons: [
            { id: 1, name: 'Revolver', magazine_capacity: 6, pivot: { ammo: 0 } },
            { id: 2, name: 'Knife', magazine_capacity: null },
        ],
    } } });
    await flushPromises();
    const observer = observers.find(item => item.elements.length === 3);
    expect(observer).toBeDefined();
    const [page, identity, ammunition] = observer.elements;
    Object.defineProperty(page, 'clientHeight', { configurable: true, value: 700 });
    Object.defineProperty(identity, 'offsetHeight', { value: 500 });
    Object.defineProperty(ammunition, 'offsetHeight', { value: 100 });
    observer.callback();
    await flushPromises();
    expect(ammunition.getAttribute('aria-hidden')).toBe('false');
    expect(ammunition.textContent).toContain('Revolver');
    expect(ammunition.textContent).toContain('0');
    expect(ammunition.textContent).not.toContain('Knife');
    expect(identity.textContent).toContain('Hit points');
    expect(identity.textContent).toContain('0');
    Object.defineProperty(page, 'clientHeight', { value: 550 });
    observer.callback();
    await flushPromises();
    expect(ammunition.getAttribute('aria-hidden')).toBe('true');
    document.querySelector('[aria-label="Close passport"]').click();
    expect(wrapper.emitted('close')).toHaveLength(1);
});
