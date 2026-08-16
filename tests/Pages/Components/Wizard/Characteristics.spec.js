// tests/Pages/Components/Wizard/Characteristics.spec.js
//
// The characteristics step offers three ways to reach the same eight numbers:
// typing them, rolling dice, or sharing out a pool of points.
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, afterEach } from 'vitest'

vi.mock('@inertiajs/vue3', () => ({
    router: {
        put: vi.fn((url, data, options = {}) => {
            if (typeof options.onSuccess === 'function') options.onSuccess()
        }),
    },
    usePage: () => ({ props: { errors: {} } }),
}))

import { router } from '@inertiajs/vue3'
import StepCharacteristics from '@/Pages/Components/Wizard/StepCharacteristics.vue'
import { CHARACTERISTICS, POINT_BUY } from '@/Pages/Components/Wizard/wizardData.js'

/** A draft that has reached the characteristics step, aged out of every modifier. */
const draft = (overrides = {}) => ({
    slug: 'harvey-walters',
    name: 'Harvey Walters',
    age: 25,
    wizard_step: 1,
    ...overrides,
})

const mountStep = (overrides = {}) =>
    mount(StepCharacteristics, { props: { draft: draft(overrides) } })

const methodButton = (wrapper, label) =>
    wrapper.findAll('button').find((button) => button.text().startsWith(label))

const choose = async (wrapper, label) => {
    await methodButton(wrapper, label).trigger('click')
}

const fields = (wrapper) => wrapper.findAll('input[type="number"]')

const saveButton = (wrapper) =>
    wrapper.findAll('button').find((button) => button.text().includes('Save characteristics'))

/** Types one number into each of the nine inputs (eight characteristics, then Luck). */
const fill = async (wrapper, values) => {
    const inputs = fields(wrapper)
    for (const [index, value] of values.entries()) {
        await inputs[index].setValue(String(value))
    }
}

afterEach(() => {
    vi.clearAllMocks()
})

describe('choosing a method', () => {
    it('starts on plain entry, with no dice instructions in sight', () => {
        const wrapper = mountStep()

        expect(methodButton(wrapper, 'Enter values').attributes('aria-pressed')).toBe('true')
        expect(wrapper.text()).not.toContain('Roll 3D6 and enter the total')
        expect(wrapper.text()).not.toContain('×5')
    })

    it('switches to the dice, which multiply by five', async () => {
        const wrapper = mountStep()

        await choose(wrapper, 'Roll the dice')

        expect(wrapper.text()).toContain('Roll 3D6 and enter the total')

        await fields(wrapper)[0].setValue('12')

        expect(wrapper.text()).toContain('×5')
        // The derived table shows the multiplied figure, not the raw roll.
        expect(wrapper.find('tbody').text()).toContain('60')
    })

    it('clears what was typed when the method changes', async () => {
        const wrapper = mountStep()

        await fields(wrapper)[0].setValue('55')
        expect(wrapper.find('tbody').text()).toContain('55')

        await choose(wrapper, 'Point Buy')

        expect(wrapper.find('tbody').text()).not.toContain('55')
    })
})

describe('entering values by hand', () => {
    it('saves exactly what was typed', async () => {
        const wrapper = mountStep()

        await fill(wrapper, [50, 60, 65, 70, 45, 75, 55, 80, 40])

        expect(saveButton(wrapper).attributes('disabled')).toBeUndefined()

        await saveButton(wrapper).trigger('click')

        expect(router.put).toHaveBeenCalledTimes(1)
        expect(router.put.mock.calls[0][1]).toEqual({
            strength: 50,
            constitution: 60,
            size: 65,
            dexterity: 70,
            appearance: 45,
            intelligence: 75,
            power: 55,
            education: 80,
            luck: 40,
        })
    })

    it('leaves the age modifiers to the player rather than applying them', async () => {
        const wrapper = mountStep({ age: 55 })

        await fill(wrapper, [50, 60, 65, 70, 45, 75, 55, 80, 40])

        expect(wrapper.text()).toContain('Apply these yourself')
        // APP is entered as 45 and stays 45 — the 50s bracket's −10 is not applied.
        expect(router.put).not.toHaveBeenCalled()
        await saveButton(wrapper).trigger('click')
        expect(router.put.mock.calls[0][1].appearance).toBe(45)
    })

    it('will not save an out-of-range value', async () => {
        const wrapper = mountStep()

        await fill(wrapper, [120, 60, 65, 70, 45, 75, 55, 80, 40])

        expect(wrapper.text()).toContain('Enter a value between 1 and 99')
        expect(saveButton(wrapper).attributes('disabled')).toBeDefined()
    })
})

describe('point buy', () => {
    const spread = [60, 60, 60, 60, 60, 60, 60, 60] // 480 — twenty over the pool

    it('holds out until the whole pool is spent', async () => {
        const wrapper = mountStep()
        await choose(wrapper, 'Point Buy')

        await fill(wrapper, [...spread, 40])

        expect(wrapper.text()).toContain('20 over')
        expect(saveButton(wrapper).attributes('disabled')).toBeDefined()

        await fields(wrapper)[0].setValue('40')

        expect(wrapper.text()).toContain(`${POINT_BUY.pool} points spent`)
        expect(saveButton(wrapper).attributes('disabled')).toBeUndefined()
    })

    it('warns once every characteristic is set and INT or SIZ is under the recommended minimum', async () => {
        const wrapper = mountStep()
        await choose(wrapper, 'Point Buy')

        const sizeIndex = CHARACTERISTICS.findIndex((c) => c.key === 'size')

        // SIZ below 40, but nothing else entered yet: no verdict to give.
        await fields(wrapper)[sizeIndex].setValue('15')
        expect(wrapper.text()).not.toContain('below the recommended minimum')

        await fill(wrapper, [80, 65, 15, 65, 65, 65, 65, 40, 40])

        expect(wrapper.text()).toContain('SIZ')
        expect(wrapper.text()).toContain('below the recommended minimum of 40')
        // A recommendation, not a rule — it still saves.
        expect(saveButton(wrapper).attributes('disabled')).toBeUndefined()
    })

    it('steps a characteristic in fives without overspending the pool', async () => {
        const wrapper = mountStep()
        await choose(wrapper, 'Point Buy')

        const plus = wrapper.find('button[aria-label="spend more points on STR"]')
        const minus = wrapper.find('button[aria-label="spend fewer points on STR"]')

        await minus.trigger('click')
        expect(fields(wrapper)[0].element.value).toBe('')

        await plus.trigger('click')
        expect(fields(wrapper)[0].element.value).toBe(String(POINT_BUY.min))

        await plus.trigger('click')
        expect(fields(wrapper)[0].element.value).toBe(String(POINT_BUY.min + POINT_BUY.step))

        // Spend the rest of the pool elsewhere, and STR cannot climb any further.
        await fields(wrapper)[1].setValue(String(POINT_BUY.pool - POINT_BUY.min - POINT_BUY.step))
        await plus.trigger('click')
        expect(fields(wrapper)[0].element.value).toBe(String(POINT_BUY.min + POINT_BUY.step))
    })
})
