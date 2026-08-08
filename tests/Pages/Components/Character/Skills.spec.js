// tests/Pages/Components/Character/Skills.spec.js
import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, vi, afterEach } from 'vitest'

// Mock @inertiajs/vue3 so we can intercept form and router calls
let lastForm
vi.mock('@inertiajs/vue3', () => {
    const useForm = vi.fn((initial) => {
        lastForm = {
            ...initial,
            processing: false,
            put: vi.fn((url, options = {}) => {
                if (typeof options.onSuccess === 'function') options.onSuccess()
            }),
            reset: vi.fn(),
        }
        return lastForm
    })
    const router = {
        put: vi.fn((url, data, options = {}) => {
            if (typeof options.onSuccess === 'function') options.onSuccess()
        }),
    }
    return { useForm, router }
})

// The experience endpoints answer with the figure they stored.
let experienceResponse = 0

vi.mock('axios', () => ({
    default: {
        get: vi.fn(() => Promise.resolve({})),
        put: vi.fn(() => Promise.resolve({})),
        post: vi.fn(() => Promise.resolve({ data: { experience: experienceResponse } })),
    },
}))

import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Skills from '@/Pages/Components/Character/Skills.vue'
import RegularHalfFifth from '@/Pages/Components/RegularHalfFifth.vue'
import TallyMarks from '@/Pages/Components/TallyMarks.vue'

describe('Skills.vue', () => {
    afterEach(() => {
        vi.clearAllMocks()
        window.localStorage.clear()
    })

    const makeCharacter = () => ({
        id: 123,
        slug: 'char-slug',
        skills: [
            {
                id: 1,
                slug: 'stealth',
                display_name: 'Stealth',
                description: 'Move without being seen.',
                starting_value: 20,
                pivot: { value: 40, experience: 0, show: true },
            },
            {
                id: 2,
                slug: 'spot-hidden',
                display_name: 'Spot Hidden',
                description: '',
                starting_value: 25,
                pivot: { value: 50, experience: 5, show: true },
            },
            {
                id: 3,
                slug: 'occult',
                display_name: 'Occult',
                description: '',
                starting_value: 10,
                pivot: { value: 5, experience: 0, show: false },
            },
            // Untouched but shown: still sitting on its starting value.
            {
                id: 4,
                slug: 'accounting',
                display_name: 'Accounting',
                description: '',
                starting_value: 5,
                pivot: { value: 5, experience: 0, show: true },
            },
            // Untouched but listed in the always-relevant config.
            {
                id: 5,
                slug: 'dodge',
                display_name: 'Dodge',
                description: '',
                starting_value: 30,
                pivot: { value: 30, experience: 0, show: true },
            },
        ],
    })

    const ERAS = [
        { value: '1920s', label: 'The Roaring Twenties', short: '1920s' },
        { value: 'modern', label: 'Modern Day', short: 'Modern' },
    ]

    const mountComponent = ({
        editable = false,
        canEdit = false,
        availableSkills = [],
        character = makeCharacter(),
        alwaysRelevantSkills = ['dodge'],
        relevantOnly = false,
        era = null,
        eras = ERAS,
    } = {}) => {
        // The toggle reads its stored state on mount; most cases here predate it.
        window.localStorage.setItem('cthulhu.skills.relevant-only', relevantOnly ? 'true' : 'false')

        return mount(Skills, {
            props: {
                character,
                editable,
                canEdit,
                availableSkills,
                alwaysRelevantSkills,
                era,
                eras,
            },
            global: {
                stubs: { teleport: true },
            },
        })
    }

    it('renders a value block per shown skill and hides hidden skills outside edit mode', () => {
        const wrapper = mountComponent()

        expect(wrapper.findAllComponents(RegularHalfFifth).length).toBe(4)
        expect(wrapper.text()).toContain('Stealth')
        expect(wrapper.text()).toContain('Spot Hidden')
        expect(wrapper.text()).not.toContain('Occult')
    })

    it('lists hidden skills again in edit mode, with a hint about them', () => {
        const wrapper = mountComponent({ editable: true })

        expect(wrapper.text()).toContain('Occult')
        expect(wrapper.text()).toContain('1 hidden skill is')
    })

    it('shows regular, half, and fifth values for a skill', () => {
        const wrapper = mountComponent()

        // Stealth is 40: half 20, fifth 8
        expect(wrapper.findAll('[title="Regular success"]')[0].text()).toBe('40')
        expect(wrapper.findAll('[title="Hard success (half)"]')[0].text()).toBe('20')
        expect(wrapper.findAll('[title="Extreme success (one fifth)"]')[0].text()).toBe('8')
    })

    it('filters the skill list through the search field', async () => {
        const wrapper = mountComponent()

        await wrapper.get('input[type="search"]').setValue('spot')

        expect(wrapper.findAllComponents(RegularHalfFifth).length).toBe(1)
        expect(wrapper.text()).toContain('Spot Hidden')
        expect(wrapper.text()).not.toContain('Stealth')
    })

    it('tallies experience checks beside the skill that earned them', () => {
        const wrapper = mountComponent()

        // Only Spot Hidden has checks, and its five draw as one struck group.
        const tallies = wrapper.findAllComponents(TallyMarks)
        expect(tallies.length).toBe(1)
        expect(tallies[0].props('count')).toBe(5)
        expect(tallies[0].find('svg').findAll('line').length).toBe(5)

        // Value 50 => ready at 5 checks, so the marks go blood red.
        expect(tallies[0].classes()).toContain('text-cthulhu-blood-500')
    })

    // One skill only, so every tally on screen belongs to it — the box's and
    // the editor's alike.
    const makeSoleSkillCharacter = (experience) => ({
        id: 123,
        slug: 'char-slug',
        skills: [
            {
                id: 1,
                slug: 'stealth',
                display_name: 'Stealth',
                description: 'Move without being seen.',
                starting_value: 20,
                pivot: { value: 40, experience, show: true },
            },
        ],
    })

    it('counts checks up and down from the skill editor, never below zero', async () => {
        const wrapper = mountComponent({ canEdit: true, character: makeSoleSkillCharacter(0) })

        await wrapper.get('[title="Adjust Stealth"]').trigger('click')

        // Stealth starts on no checks, so there is nothing to take away yet.
        const minus = wrapper.get('[aria-label="One experience check fewer"]')
        const plus = wrapper.get('[aria-label="One experience check more"]')
        expect(minus.attributes('disabled')).toBeDefined()

        experienceResponse = 1
        await plus.trigger('click')
        await flushPromises()

        expect(axios.post).toHaveBeenCalledTimes(1)
        expect(axios.post.mock.calls[0][0]).toContain('experience.increment')

        // The mark reaches both the skill's box and the editor.
        const tallies = wrapper.findAllComponents(TallyMarks)
        expect(tallies.length).toBe(2)
        expect(tallies.every((tally) => tally.props('count') === 1)).toBe(true)
        expect(wrapper.get('[aria-label="One experience check fewer"]').attributes('disabled')).toBeUndefined()

        experienceResponse = 0
        await wrapper.get('[aria-label="One experience check fewer"]').trigger('click')
        await flushPromises()

        expect(axios.post.mock.calls[1][0]).toContain('experience.decrement')
        expect(wrapper.findAllComponents(TallyMarks).length).toBe(0)
    })

    it('clears the checks from the skill editor once the improvement is rolled', async () => {
        // Value 40 => ready at 4 checks, which is where this one sits.
        const wrapper = mountComponent({ canEdit: true, character: makeSoleSkillCharacter(4) })

        await wrapper.get('[title="Adjust Stealth"]').trigger('click')
        expect(wrapper.text()).toContain('Ready to improve — roll for it')

        experienceResponse = 0
        const clear = wrapper.findAll('button').find((b) => b.text() === 'Clear')
        await clear.trigger('click')
        await flushPromises()

        expect(axios.post.mock.calls[0][0]).toContain('experience.reset')
        expect(wrapper.text()).toContain('No checks yet')
        expect(wrapper.findAllComponents(TallyMarks).length).toBe(0)
    })

    it('opens the edit modal from anywhere in the skill box and saves through the form', async () => {
        const wrapper = mountComponent({ canEdit: true })

        // The whole box is the target, not just the three numbers inside it.
        const box = wrapper.get('[title="Adjust Stealth"]')
        expect(box.element.tagName).toBe('BUTTON')
        expect(box.findComponent(RegularHalfFifth).exists()).toBe(true)
        expect(box.text()).toContain('Stealth')

        await box.trigger('click')

        const valueInput = wrapper.get('#skill_value')
        expect(valueInput.element.value).toBe('40')
        expect(wrapper.text()).toContain('Move without being seen.')

        const save = wrapper.findAll('button').find((b) => b.text() === 'Save')
        await save.trigger('click')

        expect(lastForm.put).toHaveBeenCalledTimes(1)
        const [url, options] = lastForm.put.mock.calls[0]
        expect(url).toContain('character.skill.update')
        expect(url).toContain('stealth')
        expect(options).toMatchObject({ preserveScroll: true })
    })

    it('does not open the edit modal without edit rights', async () => {
        const wrapper = mountComponent({ canEdit: false })

        // Without edit rights the box is inert: no button, no title, no editor.
        expect(wrapper.find('[title="Adjust Stealth"]').exists()).toBe(false)
        expect(wrapper.findAll('button').some((b) => b.text().includes('Stealth'))).toBe(false)

        await wrapper.findAllComponents(RegularHalfFifth)[0].find('.grid').trigger('click')

        expect(wrapper.find('#skill_value').exists()).toBe(false)
    })

    it('adds a skill through the picker in edit mode', async () => {
        const wrapper = mountComponent({
            editable: true,
            availableSkills: [{ id: 9, slug: 'archaeology', display_name: 'Archaeology', starting_value: 1 }],
        })

        await wrapper.get('select').setValue('archaeology')
        const addBtn = wrapper.findAll('button').find((b) => b.text() === 'Add')
        await addBtn.trigger('click')

        expect(router.put).toHaveBeenCalledTimes(1)
        const [url, data] = router.put.mock.calls[0]
        expect(url).toContain('character.skill.attach')
        expect(url).toContain('archaeology')
        expect(data).toEqual({ value: 1 })
    })

    describe('the relevant-only filter', () => {
        it('is on by default and keeps improved skills plus the configured must-haves', () => {
            // No stored preference at all: this is what a player sees on first visit.
            const wrapper = mount(Skills, {
                props: {
                    character: makeCharacter(),
                    editable: false,
                    canEdit: false,
                    availableSkills: [],
                    alwaysRelevantSkills: ['dodge'],
                },
                global: { stubs: { teleport: true } },
            })

            expect(wrapper.text()).toContain('Stealth')      // 40 over a base of 20
            expect(wrapper.text()).toContain('Spot Hidden')  // 50 over a base of 25
            expect(wrapper.text()).toContain('Dodge')        // untouched, but always relevant
            expect(wrapper.text()).not.toContain('Accounting') // untouched and not must-have
        })

        it('shows everything on the sheet once switched off', async () => {
            const wrapper = mountComponent({ relevantOnly: true })

            expect(wrapper.text()).not.toContain('Accounting')

            await wrapper.get('[role="switch"]').trigger('click')

            expect(wrapper.text()).toContain('Accounting')
            expect(wrapper.findAllComponents(RegularHalfFifth).length).toBe(4)
        })

        it('remembers the choice for the next visit', async () => {
            const wrapper = mountComponent({ relevantOnly: true })
            await wrapper.get('[role="switch"]').trigger('click')

            expect(window.localStorage.getItem('cthulhu.skills.relevant-only')).toBe('false')

            const next = mountComponent({ relevantOnly: false })
            expect(next.text()).toContain('Accounting')
        })

        it('counts what it is holding back', () => {
            const wrapper = mountComponent({ relevantOnly: true })

            expect(wrapper.text()).toContain('1 skill sits at the starting value')
        })

        it('looks past the filter while searching', async () => {
            const wrapper = mountComponent({ relevantOnly: true })

            await wrapper.get('input[type="search"]').setValue('account')

            expect(wrapper.text()).toContain('Accounting')
        })
    })

    describe('eras', () => {
        // Every sheet starts with the whole canonical list on it, so a Twenties
        // investigator arrives carrying Fighting (Chainsaw).
        const withChainsaw = (value = 10) => {
            const character = makeCharacter()

            character.skills.push({
                id: 6,
                slug: 'fighting-chainsaw',
                display_name: 'Fighting (Chainsaw)',
                description: '',
                starting_value: 10,
                eras: ['modern'],
                pivot: { value, experience: 0, show: true },
            })

            return character
        }

        it('puts away an untouched skill belonging to another era', () => {
            const wrapper = mountComponent({ character: withChainsaw(), era: '1920s' })

            expect(wrapper.text()).not.toContain('Fighting (Chainsaw)')
            expect(wrapper.text()).toContain('1 skill belongs to another era')
        })

        it('keeps it once it has been given a value', () => {
            const wrapper = mountComponent({ character: withChainsaw(45), era: '1920s' })

            expect(wrapper.text()).toContain('Fighting (Chainsaw)')
        })

        it('reaches it by name', async () => {
            const wrapper = mountComponent({ character: withChainsaw(), era: '1920s' })

            await wrapper.get('input[type="search"]').setValue('chainsaw')

            expect(wrapper.text()).toContain('Fighting (Chainsaw)')
        })

        it('leaves it alone in the era it belongs to', () => {
            const wrapper = mountComponent({ character: withChainsaw(), era: 'modern' })

            expect(wrapper.text()).toContain('Fighting (Chainsaw)')
            expect(wrapper.text()).not.toContain('belongs to another era')
        })

        it('says nothing about eras when the sheet has no era', () => {
            const wrapper = mountComponent({ character: withChainsaw(), era: null })

            expect(wrapper.text()).toContain('Fighting (Chainsaw)')
        })

        it('sorts the add-skill picker into this era and the rest', () => {
            const wrapper = mountComponent({
                editable: true,
                era: '1920s',
                availableSkills: [
                    { id: 9, slug: 'archaeology', display_name: 'Archaeology', starting_value: 1, eras: ['1920s', 'modern'] },
                    { id: 10, slug: 'computer-use', display_name: 'Computer Use', starting_value: 5, eras: ['modern'] },
                ],
            })

            const groups = wrapper.findAll('optgroup')

            expect(groups.map((group) => group.attributes('label'))).toEqual(['This era', 'Another era'])
            expect(groups[0].text()).toContain('Archaeology')
            expect(groups[1].text()).toContain('Computer Use')
        })
    })

    it('does not show the add-skill picker outside edit mode', () => {
        const wrapper = mountComponent({
            editable: false,
            availableSkills: [{ id: 9, slug: 'archaeology', display_name: 'Archaeology', starting_value: 1 }],
        })

        expect(wrapper.find('select').exists()).toBe(false)
    })
})
