// tests/vitest.setup.js
import { vi } from 'vitest'
import { config } from '@vue/test-utils'

// Provide a minimal global route helper used by inertia router calls, if needed.
globalThis.route = (...args) => `route:${JSON.stringify(args)}`

// Templates resolve route() through the component instance (Ziggy registers it
// as a global property in the real app), so mock it there as well.
config.global.mocks.route = globalThis.route

// Avoid unexpected native dialogs during tests.
vi.stubGlobal('confirm', vi.fn(() => true))

import ResizeObserver from 'resize-observer-polyfill'
if (typeof globalThis.ResizeObserver === 'undefined') {
    globalThis.ResizeObserver = ResizeObserver
}
