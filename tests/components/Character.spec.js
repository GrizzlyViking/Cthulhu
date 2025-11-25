// tests/components/Character.spec.js
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { defineComponent } from 'vue'
import Character from '@/Pages/Character.vue'

// Mock inertia exports used inside Character.vue
vi.mock('@inertiajs/vue3', () => {
  const Head = defineComponent({
    name: 'Head',
    props: { title: String },
    // Expose the title via a data attribute for easy assertions
    template: '<div data-test="head" :data-title="title"><slot /></div>',
  })

  const router = {
    get: vi.fn(),
    put: vi.fn(),
    delete: vi.fn(),
  }

  const useForm = vi.fn(() => ({
    post: vi.fn(),
    processing: false,
    errors: {},
    data: { avatar: null },
  }))

  const usePage = vi.fn(() => ({
    props: { auth: { user: { id: 1, name: 'Test User' } } },
  }))

  return { Head, router, useForm, usePage }
})

describe('Character.vue', () => {
  const sampleCharacter = {
    name: 'Sherlock Holmes',
    slug: 'sherlock-holmes',
    player: { name: 'John Watson' },
  }

  const mountComponent = () =>
    mount(Character, {
      props: { character: sampleCharacter },
      global: {
        // Keep child components stubbed but with props available for inspection
        stubs: {
          // Layout that renders header and default slots
          AuthenticatedLayout: {
            template: '<div><slot name="header" /><slot /></div>',
          },
          Backstory: true,
          Vitals: true,
          Characteristics: true,
          Skills: true,
          Weapons: true,
          Dropdown: true,
          // Headless UI Switch + HeroIcon
          Switch: true,
          UserCircleIcon: true,
        },
      },
    })

  beforeEach(() => {
    vi.clearAllMocks()
  })

  it('sets the page title via Head to character name', () => {
    const wrapper = mountComponent()
    const head = wrapper.get('[data-test="head"]')
    expect(head.attributes('data-title')).toBe(sampleCharacter.name)
  })

  it('renders header with character and player names', () => {
    const wrapper = mountComponent()
    const html = wrapper.html()
    expect(html).toContain(sampleCharacter.name)
    expect(html).toContain(`(${sampleCharacter.player.name})`)
  })

  it('renders Backstory, Vitals, Characteristics, and Skills with correct props', () => {
    const wrapper = mountComponent()

    const backstory = wrapper.getComponent({ name: 'Backstory' })
    expect(backstory.props('character')).toEqual(sampleCharacter)
    expect(backstory.props('editable')).toBe(false)

    const vitals = wrapper.getComponent({ name: 'Vitals' })
    expect(vitals.props('character')).toEqual(sampleCharacter)

    const characteristics = wrapper.getComponent({ name: 'Characteristics' })
    expect(characteristics.props('character')).toEqual(sampleCharacter)
    expect(characteristics.props('editable')).toBe(false)

    const skills = wrapper.getComponent({ name: 'Skills' })
    expect(skills.props('character')).toEqual(sampleCharacter)
  })

  it('does not render the editable Switch (it is behind v-if="false")', () => {
    const wrapper = mountComponent()
    const switchComp = wrapper.findComponent({ name: 'Switch' })
    expect(switchComp.exists()).toBe(false)
  })
})
