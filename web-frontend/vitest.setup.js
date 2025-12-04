import '@testing-library/jest-dom/vitest';
class ResizeObserver {
    observe() { }
    unobserve() { }
    disconnect() { }
}
if (!window.ResizeObserver) {
    window.ResizeObserver = ResizeObserver;
}
