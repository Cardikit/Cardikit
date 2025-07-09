import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from '@/contexts/AuthContext';
import PrivateRoute from '@/routes/PrivateRoute';
import GuestRoute from '@/routes/GuestRoute';
import Dashboard from '@/features/dashboard/pages/Dashboard';
import Login from '@/features/auth/pages/Login';
import Register from '@/features/auth/pages/Register';
import Welcome from '@/features/auth/pages/Welcome';
import NotFound from '@/pages/NotFound';

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
                    </Route>

                    <Route path="*" element={<NotFound />} />
                </Routes>
            </AuthProvider>
        </BrowserRouter>
    );
};

export default AppRoutes;
