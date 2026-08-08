// tests/Pages/Components/Character/Pickers.spec.js
//
// The weapon and equipment pickers open as a shelf of folded-up categories:
// nothing but headings until one is tapped, and everything at once while a
// search is running.
import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'

const armoury = [
    { id: 1, name: '.38 Revolver', category: 'Handguns', skill: 'firearms_handgun', damage: '1D10', base_range: '15 yards', eras: ['1920s'], magazine_capacity: 6 },
    { id: 2, name: '.45 Automatic', category: 'Handguns', skill: 'firearms_handgun', damage: '1D10+2', base_range: '15 yards', eras: ['1920s'], magazine_capacity: 7 },
    { id: 3, name: 'Axe', category: 'Melee Weapons', skill: 'fighting_axe', damage: '1D8+DB', base_range: 'touch', eras: ['1920s'], magazine_capacity: null },
]

vi.mock('@inertiajs/vue3', () => ({
    router: {
        post: vi.fn(),
        put: vi.fn(),
        delete: vi.fn(),
    },
    usePage: () => ({ props: { auth: { equipment: armoury } } }),
    Link: { template: '<a><slot /></a>' },
}))

vi.mock('axios', () => ({
    default: {
        get: vi.fn(() => Promise.resolve({
            data: {
                items: [
                    { id: 10, name: 'Flashlight', section: 'Tools', cost: '$2', eras: ['1920s'], is_custom: false },
                    { id: 11, name: 'Crowbar', section: 'Tools', cost: '$1', eras: ['1920s'], is_custom: false },
                    { id: 12, name: 'Overcoat', section: 'Clothing', cost: '$15', eras: ['1920s'], is_custom: false },
                ],
            },
        })),
        post: vi.fn(() => Promise.resolve({ data: {} })),
        put: vi.fn(() => Promise.resolve({ data: {} })),
    },
}))

import Weapons from '@/Pages/Components/Character/Weapons.vue'
import EquipmentList from '@/Pages/Components/Character/EquipmentList.vue'

const character = {
    id: 1,
    slug: 'harvey-walters',
    strength: 50,
    size: 60,
    skills: [],
    weapons: [],
    equipment: [],
}

/** Every fold-out heading in the open modal, which Modal teleports to body. */
const headings = () => [...document.body.querySelectorAll('button[aria-expanded]')]

const headingFor = (title) => headings().find((button) => button.textContent.includes(title))

const panelOf = (heading) => document.getElementById(heading.getAttribute('aria-controls'))

beforeEach(() => {
    document.body.innerHTML = ''
})

afterEach(() => {
    vi.clearAllMocks()
})

describe('the weapon picker', () => {
    const open = async () => {
        const wrapper = mount(Weapons, {
            props: { character, editable: true, canEdit: true, era: '1920s', eras: [] },
            attachTo: document.body,
        })

        await wrapper.find('button.btn-secondary').trigger('click')
        await flushPromises()

        return wrapper
    }

    it('opens on its categories, folded up, with what is inside each', async () => {
        await open()

        expect(headings()).toHaveLength(2)

        const handguns = headingFor('Handguns')

        expect(handguns.getAttribute('aria-expanded')).toBe('false')
        // The count and a peek at the contents, so a closed heading still says
        // what it is worth opening for.
        expect(handguns.textContent).toContain('2')
        expect(handguns.textContent).toContain('.38 Revolver, .45 Automatic')
    })

    it('unfolds the category that is tapped, and only that one', async () => {
        await open()

        const handguns = headingFor('Handguns')
        handguns.click()
        await flushPromises()

        expect(headingFor('Handguns').getAttribute('aria-expanded')).toBe('true')
        expect(headingFor('Melee Weapons').getAttribute('aria-expanded')).toBe('false')

        // The weapons themselves are only reachable once it is open.
        expect(panelOf(headingFor('Handguns')).textContent).toContain('.45 Automatic')
    })

    it('throws every category open while the armoury is being filtered', async () => {
        await open()

        const field = document.body.querySelector('input[type="search"]')
        field.value = 'revolver'
        field.dispatchEvent(new Event('input'))
        await flushPromises()

        // One category survives the filter, and it arrives open — a search that
        // hid its own hits behind another tap would be no search at all.
        expect(headings()).toHaveLength(1)
        expect(headings()[0].getAttribute('aria-expanded')).toBe('true')
        expect(panelOf(headings()[0]).textContent).toContain('.38 Revolver')
    })
})

describe('the equipment picker', () => {
    const open = async () => {
        const wrapper = mount(EquipmentList, {
            props: {
                character,
                canEdit: true,
                storageLocations: [{ id: 1, name: 'On their person' }],
                era: '1920s',
                eras: [],
            },
            attachTo: document.body,
        })

        await wrapper.find('button.btn-secondary').trigger('click')
        await flushPromises()

        return wrapper
    }

    it('fetches the catalogue as it opens and folds it into its sections', async () => {
        await open()

        const sections = headings().map((heading) => heading.textContent)

        expect(sections).toHaveLength(2)
        expect(sections[0]).toContain('Tools')
        expect(sections[1]).toContain('Clothing')
        expect(headings().every((heading) => heading.getAttribute('aria-expanded') === 'false')).toBe(true)
    })

    it('opens a section on a tap', async () => {
        await open()

        headingFor('Tools').click()
        await flushPromises()

        expect(headingFor('Tools').getAttribute('aria-expanded')).toBe('true')
        // The price is part of choosing, so it comes along with the section.
        expect(panelOf(headingFor('Tools')).textContent).toContain('$2')
    })

    it('marks what was chosen without folding the catalogue away underneath it', async () => {
        const wrapper = await open()

        headingFor('Tools').click()
        await flushPromises()

        const flashlight = [...panelOf(headingFor('Tools')).querySelectorAll('button')]
            .find((button) => button.textContent.includes('Flashlight'))

        flashlight.click()
        await flushPromises()

        expect(document.body.textContent).toContain('Add Flashlight')
        // Choosing leaves the search field — and so the sections either side of
        // the choice — exactly as they were.
        expect(document.body.querySelector('input[type="search"]').value).toBe('')
        expect(headings().map((heading) => heading.textContent.includes('Clothing'))).toContain(true)

        wrapper.unmount()
    })
})
