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
import ComingSoon from '@/pages/ComingSoon';
import Account from '@/features/account/pages/Account';
import Analytics from '@/features/analytics/pages/Analytics';
import Contacts from '@/features/contacts/pages/Contacts';
import { appBasePath } from '@/lib/env';

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
* - `/dashboard/*`: app base (protected)
* - `/dashboard/login`, `/dashboard/register`, `/dashboard/welcome`: public pages
* - `/c/*`: handled elsewhere (card sharing)
* - `*`: 404 Not Found fallback
*
* @since 0.0.1
*/
const AppRoutes: React.FC = () => {
    return (
        <BrowserRouter basename={appBasePath}>
            <AuthProvider>
                <Routes>
                    <Route element={<GuestRoute />}>
                        <Route path="/login" element={<Login />} />
                        <Route path="/register" element={<Register />} />
                        <Route path="/welcome" element={<Welcome />} />
                    </Route>

                    <Route element={<PrivateRoute />}>
                        <Route index element={<Dashboard />} />
                        <Route path="/editor" element={<Editor />} />
                        <Route path="/editor/:id" element={<Editor />} />
                        <Route path="/account" element={<Account />} />
                        <Route path="/analytics" element={<Analytics />} />
                        <Route path="/contacts" element={<Contacts />} />
                    </Route>

                    <Route path="/coming-soon" element={<ComingSoon />} />
                    <Route path="*" element={<NotFound />} />
                </Routes>
            </AuthProvider>
        </BrowserRouter>
    );
};

export default AppRoutes;
