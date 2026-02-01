// tests/vitest.setup.js
import { vi } from 'vitest'

// Provide a minimal global route helper used by inertia router calls, if needed.
globalThis.route = (...args) => `route:${JSON.stringify(args)}`

// Avoid unexpected native dialogs during tests.
vi.stubGlobal('confirm', vi.fn(() => true))

import ResizeObserver from 'resize-observer-polyfill'
if (typeof globalThis.ResizeObserver === 'undefined') {
    globalThis.ResizeObserver = ResizeObserver
}
