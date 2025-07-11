import '@testing-library/jest-dom/vitest';
import type { TestingLibraryMatchers } from '@testing-library/jest-dom/matchers';

declare module 'vitest' {
    interface Expect extends TestingLibraryMatchers<unknown, void> {}
    interface AsymmetricMatchersContaining extends TestingLibraryMatchers<unknown, void> {}
}
