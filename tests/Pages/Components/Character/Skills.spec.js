// tests/Pages/Components/Character/Skills.spec.js
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'

// Mock @inertiajs/vue3's useForm so we can intercept the post call
let lastForm
vi.mock('@inertiajs/vue3', () => {
    const useForm = vi.fn((initial) => {
        lastForm = {
            ...initial,
            post: vi.fn((url, options = {}) => {
                if (typeof options.onSuccess === 'function') options.onSuccess()
                return Promise.resolve()
            }),
        }
        return lastForm
    })
    // router is imported in the component; provide a harmless stub
    const router = {}
    return { useForm, router }
})

import Skills from '@/Pages/Components/Character/Skills.vue'

const RegularHalfFifthStub = {
    name: 'RegularHalfFifth',
    props: ['modelValue', 'editable'],
    emits: ['update:modelValue', 'input'],
    template: `
    <input
      data-testid="skill-input"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value); $emit('input', $event)"
    />
  `,
}

const ModalPopupStub = {
    name: 'ModalPopup',
    props: { isOpen: Boolean },
    emits: ['modal-close', 'response1', 'response2'],
    template: `
    <div v-if="isOpen" data-testid="modal">
      <slot />
      <button data-testid="modal-ok" @click="$emit('response2')">OK</button>
      <button data-testid="modal-cancel" @click="$emit('modal-close')">Cancel</button>
    </div>
  `,
}

const flushPromises = () => new Promise((resolve) => setTimeout(resolve, 0))

describe('Skills.vue', () => {
    beforeEach(() => {
        // Stub global route helper
        globalThis.route = (...args) => `route:${JSON.stringify(args)}`

        // Stub axios used by the component (it’s often global in apps)
        globalThis.axios = {
            get: vi.fn(() => Promise.resolve({})),
            put: vi.fn(() => Promise.resolve({})),
        }
    })

    afterEach(() => {
        vi.clearAllMocks()
        delete globalThis.route
        delete globalThis.axios
    })

    const makeCharacter = () => ({
        id: 123,
        slug: 'char-slug',
        skills: [
            {
                id: 1,
                slug: 'stealth',
                display_name: 'Stealth',
                pivot: { value: 40, experience: 0 },
            },
            {
                id: 2,
                slug: 'spot-hidden',
                display_name: 'Spot Hidden',
                pivot: { value: 50, experience: 0 },
            },
        ],
    })

    const mountComponent = (editable = false) => {
        return mount(Skills, {
            props: {
                character: makeCharacter(),
                editable,
            },
            global: {
                components: {
                    RegularHalfFifth: RegularHalfFifthStub,
                    ModalPopup: ModalPopupStub,
                },
            },
        })
    }

    it('renders one RegularHalfFifth per skill and shows skill names', () => {
        const wrapper = mountComponent(false)
        const inputs = wrapper.findAll('[data-testid="skill-input"]')
        expect(inputs.length).toBe(2)
        expect(wrapper.text()).toContain('Stealth')
        expect(wrapper.text()).toContain('Spot Hidden')
    })

    it('increments experience when clicking a skill name and shows the counter', async () => {
        const wrapper = mountComponent(false)

        // Click on "Stealth" span
        const stealthSpan = wrapper.findAll('span').find((s) => s.text() === 'Stealth')
        expect(stealthSpan).toBeTruthy()
        await stealthSpan.trigger('click')

        // axios.get resolves immediately; wait for DOM to update
        await flushPromises()

        // Experience should be displayed now (bubble appears)
        const expBubble = wrapper.findAll('div').find((d) => {
            const cls = d.classes()
            return cls.includes('rounded-full') && cls.includes('w-6') && d.text().trim() === '1'
        })
        expect(expBubble).toBeTruthy()
    })

    it('turns the experience bubble red when threshold is reached', async () => {
        const wrapper = mountComponent(false)

        // For Stealth: value 40 => threshold floor(40/10) = 4
        const stealthSpan = wrapper.findAll('span').find((s) => s.text() === 'Stealth')
        for (let i = 0; i < 4; i++) {
            await stealthSpan.trigger('click')
            await flushPromises()
        }

        const expBubble = wrapper.findAll('div').find((d) => {
            const cls = d.classes()
            return cls.includes('rounded-full') && cls.includes('w-6')
        })
        expect(expBubble).toBeTruthy()
        expect(expBubble.classes()).toContain('bg-red-800')
    })

    it('resets experience when clicking the bubble', async () => {
        const wrapper = mountComponent(false)

        // Increment once so the bubble appears
        const stealthSpan = wrapper.findAll('span').find((s) => s.text() === 'Stealth')
        await stealthSpan.trigger('click')
        await flushPromises()

        let expBubble = wrapper.findAll('div').find((d) => {
            const cls = d.classes()
            return cls.includes('rounded-full') && cls.includes('w-6')
        })
        expect(expBubble).toBeTruthy()

        // Click the parent clickable wrapper that triggers reset
        await expBubble.trigger('click')
        await flushPromises()

        // Bubble should disappear
        expBubble = wrapper.findAll('div').find((d) => {
            const cls = d.classes()
            return cls.includes('rounded-full') && cls.includes('w-6')
        })
        expect(expBubble).toBeUndefined()
    })

    it('debounces value updates and calls axios.put with numeric value', async () => {
        vi.useFakeTimers()

        const wrapper = mountComponent(true)

        const firstInput = wrapper.get('[data-testid="skill-input"]')
        // Simulate typing a new value; our stub emits an 'input' event with target.value
        await firstInput.setValue('55')

        // Debounce is 600ms
        expect(globalThis.axios.put).not.toHaveBeenCalled()
        vi.advanceTimersByTime(600)

        await flushPromises()
        expect(globalThis.axios.put).toHaveBeenCalledTimes(1)
        const [, body] = globalThis.axios.put.mock.calls[0]
        expect(body).toEqual({ value: 55 })

        vi.useRealTimers()
    })

    it('shows Add Skill button only when editable and posts via useForm on confirm', async () => {
        const wrapper = mountComponent(true)

        const addBtn = wrapper.find('button')
        expect(addBtn.exists()).toBe(true)
        expect(addBtn.text()).toMatch(/Add Skill/i)

        // Opens modal
        await addBtn.trigger('click')
        await flushPromises()

        // Modal is visible
        const modal = wrapper.find('[data-testid="modal"]')
        expect(modal.exists()).toBe(true)

        // Click OK (emits response2 => addSkill)
        const ok = modal.get('[data-testid="modal-ok"]')
        await ok.trigger('click')
        await flushPromises()

        // useForm.post should have been called (captured in lastForm)
        expect(lastForm).toBeTruthy()
        expect(lastForm.post).toHaveBeenCalledTimes(1)
        const [url, options] = lastForm.post.mock.calls[0]
        expect(typeof url).toBe('string')
        expect(url.startsWith('route:')).toBe(true)
        expect(options).toMatchObject({ preserveScroll: true })

        // Modal should close on success
        const modalAfter = wrapper.find('[data-testid="modal"]')
        expect(modalAfter.exists()).toBe(false)
    })

    it('does not show Add Skill button when not editable', () => {
        const wrapper = mountComponent(false)
        const addBtn = wrapper.findAll('button').find((b) => /Add Skill/i.test(b.text()))
        expect(addBtn).toBeUndefined()
    })
})
