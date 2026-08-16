// tests/Pages/Components/Wizard/Occupation.spec.js
//
// The occupation step, and the modal that lets a player write one the book has
// no name for. What is written there joins the shared list, so the form has to
// send the whole occupation — not just a name on this one sheet.
//
// The modal teleports to <body>, so its contents are reached through the
// component tree (findComponent) rather than through the step's own markup.
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'

const posted = vi.fn()

vi.mock('@inertiajs/vue3', async () => {
    const { reactive } = await import('vue')

    return {
        router: { put: vi.fn() },
        usePage: () => ({ props: { errors: {} } }),
        // A useForm faithful enough for the fields: reactive data, the helpers
        // the components call, and a post() that records what it was given.
        useForm: (initial) =>
            reactive({
                ...initial,
                errors: {},
                processing: false,
                defaults(next) {
                    Object.assign(this, next)
                },
                reset() {},
                clearErrors() {
                    this.errors = {}
                },
                post(url, options = {}) {
                    posted(url, JSON.parse(JSON.stringify(this)))
                    options.onSuccess?.()
                },
            }),
    }
})

import StepOccupation from '@/Pages/Components/Wizard/StepOccupation.vue'
import OccupationFields from '@/Components/OccupationFields.vue'
import SkillMultiSelect from '@/Components/SkillMultiSelect.vue'
import EraPicker from '@/Components/EraPicker.vue'

const draft = (overrides = {}) => ({
    id: 1,
    slug: 'harvey-walters',
    name: 'Harvey Walters',
    occupation_id: null,
    education: 70,
    strength: 40,
    dexterity: 60,
    intelligence: 80,
    skills: [],
    ...overrides,
})

const eras = [
    { value: '1920s', label: 'The Roaring Twenties', short: '1920s' },
    { value: 'modern', label: 'Modern Day', short: 'Modern' },
]

const skillOptions = [
    { slug: 'listen', label: 'Listen' },
    { slug: 'spot-hidden', label: 'Spot Hidden' },
    { slug: 'charm', label: 'Charm' },
    { slug: 'persuade', label: 'Persuade' },
]

const characteristics = {
    strength: 'STR',
    constitution: 'CON',
    size: 'SIZ',
    dexterity: 'DEX',
    appearance: 'APP',
    intelligence: 'INT',
    power: 'POW',
    education: 'EDU',
}

const antiquarian = {
    id: 7,
    name: 'Antiquarian',
    description: 'Delights in the timeless excellence of design.',
    is_custom: false,
    eras: ['1920s', 'modern'],
    formula_label: 'EDU × 4',
    credit_rating_min: 30,
    credit_rating_max: 70,
    skill_points_formula: [{ multiplier: 4, options: ['education'] }],
    skills: ['listen'],
}

const lighthouseKeeper = { ...antiquarian, id: 8, name: 'Lighthouse Keeper', is_custom: true }

const mountStep = (props = {}) =>
    mount(StepOccupation, {
        props: {
            draft: draft(),
            occupations: [antiquarian],
            eras,
            skillOptions,
            characteristics,
            ...props,
        },
        attachTo: document.body,
    })

const button = (wrapper, text) =>
    wrapper.findAll('button').find((candidate) => candidate.text().includes(text))

/** The form inside the teleported modal. */
const modalForm = (wrapper) => wrapper.findComponent(OccupationFields)

const openModal = async (wrapper) => {
    await button(wrapper, 'Custom occupation').trigger('click')
}

beforeEach(() => {
    posted.mockClear()
})

describe('the occupation list', () => {
    it('marks what a player wrote apart from the book', () => {
        const wrapper = mountStep({ occupations: [antiquarian, lighthouseKeeper] })

        expect(wrapper.text()).toContain('Player-written')
    })

    it('offers writing your own when nothing fits', () => {
        expect(button(mountStep(), 'Custom occupation')).toBeDefined()
    })

    it('does not open the form until asked', () => {
        expect(modalForm(mountStep()).exists()).toBe(false)
    })
})

describe('writing an occupation', () => {
    it('opens the form with both eras ticked, since most belong to both', async () => {
        const wrapper = mountStep()
        await openModal(wrapper)

        const ticked = modalForm(wrapper)
            .findComponent(EraPicker)
            .findAll('input[type="checkbox"]')
            .filter((box) => box.element.checked)

        expect(ticked).toHaveLength(2)
    })

    it('shows the pool the formula would give this investigator', async () => {
        const wrapper = mountStep()
        await openModal(wrapper)

        // The blank form starts at EDU × 2, and the draft has EDU 70.
        expect(modalForm(wrapper).text()).toContain('EDU × 2')
        expect(modalForm(wrapper).text()).toContain('140 pts for you')
    })

    it('recomputes the pool as the formula is changed', async () => {
        const wrapper = mountStep()
        await openModal(wrapper)

        const fields = modalForm(wrapper)

        // Tick DEX alongside EDU: "EDU or DEX", the higher of which is EDU 70.
        const dex = fields.findAll('label').find((label) => label.text().trim() === 'DEX')
        await dex.find('input').setValue(true)

        expect(fields.text()).toContain('EDU or DEX × 2')
        expect(fields.text()).toContain('140 pts for you')
    })

    it('sends the whole occupation to the shared list', async () => {
        const wrapper = mountStep()
        await openModal(wrapper)

        const fields = modalForm(wrapper)

        await fields.find('#occupation-name').setValue('Lighthouse Keeper')
        await fields.find('#occupation-description').setValue('Alone with the lamp.')

        // The first skill picker is the occupation's own skill list.
        const skills = fields.findAllComponents(SkillMultiSelect)[0]
        const boxes = skills.findAll('input[type="checkbox"]')

        await boxes[0].setValue(true) // Listen
        await boxes[1].setValue(true) // Spot Hidden

        await fields.element.closest('form').dispatchEvent(new Event('submit'))

        expect(posted).toHaveBeenCalledTimes(1)

        const [url, payload] = posted.mock.calls[0]

        expect(url).toContain('character.wizard.occupation.store')
        expect(payload.name).toBe('Lighthouse Keeper')
        expect(payload.description).toBe('Alone with the lamp.')
        expect(payload.eras).toEqual(['1920s', 'modern'])
        expect(payload.skill_points_formula).toEqual([{ multiplier: 2, options: ['education'] }])
        expect(payload.skills).toEqual(['listen', 'spot-hidden'])
    })

    it('closes once the occupation is saved', async () => {
        const wrapper = mountStep()
        await openModal(wrapper)

        const fields = modalForm(wrapper)
        await fields.element.closest('form').dispatchEvent(new Event('submit'))
        await wrapper.vm.$nextTick()

        expect(modalForm(wrapper).exists()).toBe(false)
    })

    it('follows the draft when the server chooses the new occupation', async () => {
        const wrapper = mountStep()

        expect(button(wrapper, 'Save occupation').attributes('disabled')).toBeDefined()

        await wrapper.setProps({
            draft: draft({ occupation_id: 8 }),
            occupations: [antiquarian, lighthouseKeeper],
        })

        // Being selected is what un-disables the step's own save button.
        expect(button(wrapper, 'Save occupation').attributes('disabled')).toBeUndefined()
    })
})
