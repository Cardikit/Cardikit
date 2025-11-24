"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
require("@testing-library/jest-dom/vitest");
var ResizeObserver = /** @class */ (function () {
    function ResizeObserver() {
    }
    ResizeObserver.prototype.observe = function () { };
    ResizeObserver.prototype.unobserve = function () { };
    ResizeObserver.prototype.disconnect = function () { };
    return ResizeObserver;
}());
if (!window.ResizeObserver) {
    window.ResizeObserver = ResizeObserver;
}
