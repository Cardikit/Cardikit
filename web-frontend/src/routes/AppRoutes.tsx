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
const AppRoutes: React.FC = () => {
    return (
        <BrowserRouter>
            <AuthProvider>
                <Routes>
                    <Route element={<GuestRoute />}>
                        <Route path="/login" element={<Login />} />
                        <Route path="/" element={<Welcome />} />
                        <Route path="/register" element={<Register />} />
                    </Route>

                    <Route element={<PrivateRoute />}>
                        <Route path="/dashboard" element={<Dashboard />} />
                        <Route path="/editor" element={<Editor />} />
                        <Route path="/editor/:id" element={<Editor />} />
                    </Route>

                    <Route path="*" element={<NotFound />} />
                </Routes>
            </AuthProvider>
        </BrowserRouter>
    );
};

export default AppRoutes;
