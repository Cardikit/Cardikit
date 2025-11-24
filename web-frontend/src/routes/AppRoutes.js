import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from '@/contexts/AuthContext';
import PrivateRoute from '@/routes/PrivateRoute';
import GuestRoute from '@/routes/GuestRoute';
import Dashboard from '@/features/dashboard/pages/Dashboard';
import Editor from '@/features/editor/pages/Editor';
import Login from '@/features/auth/pages/Login';
import Register from '@/features/auth/pages/Register';
import Welcome from '@/features/auth/pages/Welcome';
import NotFound from '@/pages/NotFound';
/**
* AppRoutes
* ---------
* Handles client-side routing and route protection logic.
*
* - Wraps all routes with `AuthProvider` to enable authentication state globally.
* - `GuestRoute`: for unauthenticated-only pages like login/register.
* - `PrivateRoute`: for protected pages that require authentication.
*
* Routes:
* - `/` and `/register`, `/login`: public pages
* - `/dashboard`: private/protected route
* - `*`: 404 Not Found fallback
*
* @since 0.0.1
*/
const AppRoutes = () => {
    return (_jsx(BrowserRouter, { children: _jsx(AuthProvider, { children: _jsxs(Routes, { children: [_jsxs(Route, { element: _jsx(GuestRoute, {}), children: [_jsx(Route, { path: "/login", element: _jsx(Login, {}) }), _jsx(Route, { path: "/", element: _jsx(Welcome, {}) }), _jsx(Route, { path: "/register", element: _jsx(Register, {}) })] }), _jsxs(Route, { element: _jsx(PrivateRoute, {}), children: [_jsx(Route, { path: "/dashboard", element: _jsx(Dashboard, {}) }), _jsx(Route, { path: "/editor", element: _jsx(Editor, {}) }), _jsx(Route, { path: "/editor/:id", element: _jsx(Editor, {}) })] }), _jsx(Route, { path: "*", element: _jsx(NotFound, {}) })] }) }) }));
};
export default AppRoutes;
