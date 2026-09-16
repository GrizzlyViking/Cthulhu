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

vi.mock('axios', () => ({ default: { post: vi.fn() } }));

async function armedPassport(canEdit = true) {
    const weapon = { id: 1, name: 'Revolver', magazine_capacity: 6, pivot: { id: 42, ammo: 2, ammo_reserve: 12 } };
    wrapper = mount(Passport, { attachTo: document.body, props: {
        canEdit, character: { slug: 'ada-marsh', name: 'Ada Marsh', avatar: 'ada.jpg', weapons: [weapon] },
    } });
    await flushPromises();
    return { weapon, button: document.querySelector('.passport-ammo') };
}

it('saves one shot per tap, blocks pending taps, and animates only the saved shot', async () => {
    const { default: axios } = await import('axios');
    let finish;
    axios.post.mockReset().mockImplementation(() => new Promise(resolve => { finish = resolve; }));
    const { weapon, button } = await armedPassport();
    button.click();
    button.click();
    expect(axios.post).toHaveBeenCalledTimes(1);
    expect(axios.post).toHaveBeenCalledWith(route('fire.weapon', { character: 'ada-marsh', equipable: 42 }));
    expect(weapon.pivot.ammo).toBe(2);
    expect(document.querySelector('.passport-shot')).toBeNull();
    finish({ data: { ammo: 1, ammo_reserve: 12 } });
    await flushPromises();
    expect(weapon.pivot.ammo).toBe(1);
    expect(document.querySelector('.passport-shot')).not.toBeNull();
    expect(button.disabled).toBe(false);
    axios.post.mockResolvedValueOnce({ data: { ammo: 0, ammo_reserve: 12 } });
    button.click();
    await flushPromises();
    expect(weapon.pivot.ammo).toBe(0);
    expect(button.disabled).toBe(true);
});

it('does not spend ammunition or animate when saving fails', async () => {
    const { default: axios } = await import('axios');
    axios.post.mockReset().mockRejectedValue(new Error('Offline'));
    const { weapon, button } = await armedPassport();
    button.click();
    await flushPromises();
    expect(weapon.pivot.ammo).toBe(2);
    expect(document.querySelector('.passport-shot')).toBeNull();
    expect(document.querySelector('[role="alert"]').textContent).toContain('could not be saved');
    expect(button.disabled).toBe(false);
});

it('does not allow a read-only sheet to fire', async () => {
    const { default: axios } = await import('axios');
    axios.post.mockReset();
    const { button } = await armedPassport(false);
    expect(button.disabled).toBe(true);
    button.click();
    expect(axios.post).not.toHaveBeenCalled();
});
