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

vi.mock('axios', () => ({
    default: {
        get: vi.fn(() => Promise.resolve({})),
        put: vi.fn(() => Promise.resolve({})),
    },
}))

import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Skills from '@/Pages/Components/Character/Skills.vue'
import RegularHalfFifth from '@/Pages/Components/RegularHalfFifth.vue'

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

    const mountComponent = ({
        editable = false,
        canEdit = false,
        availableSkills = [],
        character = makeCharacter(),
        alwaysRelevantSkills = ['dodge'],
        relevantOnly = false,
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

    it('shows the experience bubble, marks it ready to improve, and clears it on click', async () => {
        const wrapper = mountComponent()

        // Spot Hidden: value 50 => threshold 5, experience 5 => ready to improve
        const bubble = wrapper.findAll('button').find((b) => b.text() === '5')
        expect(bubble).toBeTruthy()
        expect(bubble.classes()).toContain('bg-cthulhu-blood-400')

        await bubble.trigger('click')
        await flushPromises()

        expect(axios.get).toHaveBeenCalledTimes(1)
        expect(wrapper.findAll('button').find((b) => b.text() === '5')).toBeUndefined()
    })

    it('opens the edit modal from the value block and saves through the form', async () => {
        const wrapper = mountComponent({ canEdit: true })

        await wrapper.get('[title="Adjust Stealth"]').trigger('click')

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

        // Without edit rights the value block carries no title and no editor opens
        expect(wrapper.find('[title="Adjust Stealth"]').exists()).toBe(false)

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

    it('does not show the add-skill picker outside edit mode', () => {
        const wrapper = mountComponent({
            editable: false,
            availableSkills: [{ id: 9, slug: 'archaeology', display_name: 'Archaeology', starting_value: 1 }],
        })

        expect(wrapper.find('select').exists()).toBe(false)
    })
})
