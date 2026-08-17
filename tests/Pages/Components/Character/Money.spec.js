// tests/Pages/Components/Character/Money.spec.js
//
// Buying things. The handbook's price arrives filled in, the player is free to
// type over it, and whichever purse they name is what the server is told to
// take it out of.
import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'

const armoury = [
    {
        id: 1,
        name: '.38 Revolver',
        category: 'Handguns',
        skill: 'firearms_handgun',
        damage: '1D10',
        base_range: '15 yards',
        eras: ['1920s'],
        magazine_capacity: 6,
        prices: { '1920s': 25, modern: 400 },
    },
]

// Hoisted, because vi.mock's factory runs before anything else in this file.
const { post } = vi.hoisted(() => ({ post: vi.fn() }))

vi.mock('@inertiajs/vue3', () => ({
    router: { post, put: vi.fn(), delete: vi.fn() },
    usePage: () => ({ props: { auth: { equipment: armoury }, errors: {} } }),
    useForm: (fields) => ({
        ...fields,
        errors: {},
        processing: false,
        put: vi.fn(),
        post: vi.fn(),
        clearErrors: vi.fn(),
        reset: vi.fn(),
    }),
    Link: { template: '<a><slot /></a>' },
}))

vi.mock('axios', () => ({
    default: {
        get: vi.fn(() => Promise.resolve({
            data: {
                items: [
                    { id: 10, name: 'Flashlight', section: 'Tools', cost: '$2', price: 2, eras: ['1920s'], is_custom: false },
                ],
            },
        })),
        post: vi.fn(() => Promise.resolve({ data: {} })),
        put: vi.fn(() => Promise.resolve({ data: {} })),
    },
}))

import Weapons from '@/Pages/Components/Character/Weapons.vue'
import EquipmentList from '@/Pages/Components/Character/EquipmentList.vue'
import Wealth from '@/Pages/Components/Character/Wealth.vue'

const wealth = {
    living_standard: 'Average',
    description: 'A reasonable level of comfort.',
    spending_level: 10,
    cash: 60,
    assets: 1500,
    settled: false,
}

const character = {
    id: 1,
    slug: 'harvey-walters',
    strength: 50,
    size: 60,
    skills: [{ slug: 'credit_rating', pivot: { value: 30 } }],
    weapons: [],
    equipment: [],
    wealth,
}

const priceField = (id) => document.getElementById(`${id}-price`)
const purseField = (id) => document.getElementById(`${id}-purse`)

beforeEach(() => {
    document.body.innerHTML = ''
})

afterEach(() => {
    vi.clearAllMocks()
})

describe('the wealth panel', () => {
    it('prints what is in the purse, and says where the figures come from', () => {
        const wrapper = mount(Wealth, { props: { character, canEdit: true } })

        expect(wrapper.text()).toContain('$60')
        expect(wrapper.text()).toContain('$1,500')
        expect(wrapper.text()).toContain('Average')
        expect(wrapper.text()).toContain('Credit Rating 30')
        // Nothing has been spent yet, so the figures are still the band's.
        expect(wrapper.text()).toContain('follow your Credit Rating')

        wrapper.unmount()
    })

    it('marks a purse that has been overspent', () => {
        const wrapper = mount(Wealth, {
            props: { character: { ...character, wealth: { ...wealth, cash: -12, settled: true } }, canEdit: true },
        })

        expect(wrapper.get('.text-cthulhu-blood-400').text()).toBe('-$12')
        expect(wrapper.text()).not.toContain('follow your Credit Rating')

        wrapper.unmount()
    })
})

describe('buying a weapon', () => {
    const open = async () => {
        const wrapper = mount(Weapons, {
            props: { character, editable: true, canEdit: true, era: '1920s', eras: [] },
            attachTo: document.body,
        })

        await wrapper.find('button.btn-secondary').trigger('click')
        await flushPromises()

        return wrapper
    }

    it('fills in this era’s price when a weapon is chosen', async () => {
        const wrapper = await open()

        const revolver = [...document.body.querySelectorAll('button')]
            .find((button) => button.textContent.includes('.38 Revolver'))

        revolver.click()
        await flushPromises()

        expect(priceField('add-weapon').value).toBe('25')
        expect(document.body.textContent).toContain('Add .38 Revolver')

        wrapper.unmount()
    })

    it('pays whatever the player typed, out of the purse they named', async () => {
        const wrapper = await open()

        const revolver = [...document.body.querySelectorAll('button')]
            .find((button) => button.textContent.includes('.38 Revolver'))
        revolver.click()
        await flushPromises()

        const price = priceField('add-weapon')
        price.value = '5'
        price.dispatchEvent(new Event('input'))

        const purse = purseField('add-weapon')
        purse.value = 'assets'
        purse.dispatchEvent(new Event('change'))
        await flushPromises()

        document.body.querySelector('button.btn-primary').click()
        await flushPromises()

        expect(post).toHaveBeenCalledTimes(1)
        expect(post.mock.calls[0][1]).toMatchObject({ weapon_id: 1, price: 5, paid_from: 'assets' })

        wrapper.unmount()
    })

    it('will not arm anybody until something has been chosen', async () => {
        const wrapper = await open()

        expect(document.body.querySelector('button.btn-primary').disabled).toBe(true)

        wrapper.unmount()
    })
})

describe('buying equipment', () => {
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

    it('fills in the catalogue price for as many as are being bought', async () => {
        const wrapper = await open()

        const heading = [...document.body.querySelectorAll('button[aria-expanded]')]
            .find((button) => button.textContent.includes('Tools'))
        heading.click()
        await flushPromises()

        const flashlight = [...document.body.querySelectorAll('button')]
            .find((button) => button.textContent.includes('Flashlight'))
        flashlight.click()
        await flushPromises()

        expect(priceField('add-equipment').value).toBe('2')

        const quantity = document.getElementById('add-qty')
        quantity.value = '3'
        quantity.dispatchEvent(new Event('input'))
        await flushPromises()

        expect(priceField('add-equipment').value).toBe('6')

        wrapper.unmount()
    })
})
