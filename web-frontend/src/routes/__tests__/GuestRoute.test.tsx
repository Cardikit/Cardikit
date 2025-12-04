import { describe, it, expect, vi } from 'vitest';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import { render, screen } from '@testing-library/react';
import GuestRoute from '@/routes/GuestRoute';
import { useAuth } from '@/contexts/AuthContext';

// Mock the useAuth hook
vi.mock('@/contexts/AuthContext', () => ({
  useAuth: vi.fn(),
}));

// Mock loading component
vi.mock('@/components/Loading', () => ({
  default: () => <div>Loading...</div>,
}));

const TestPage = () => <div>Guest Page</div>;
const Dashboard = () => <div>Dashboard</div>;

describe('GuestRoute', () => {
  it('renders loading when auth is loading', () => {
    (useAuth as any).mockReturnValue({
      user: null,
      loading: true,
    });

    render(
      <MemoryRouter initialEntries={['/login']}>
        <Routes>
          <Route element={<GuestRoute />}>
            <Route path="/login" element={<TestPage />} />
          </Route>
        </Routes>
      </MemoryRouter>
    );

    expect(screen.getByText('Loading...')).toBeInTheDocument();
  });

  it('renders guest page when user is not authenticated', () => {
    (useAuth as any).mockReturnValue({
      user: null,
      loading: false,
    });

    render(
      <MemoryRouter initialEntries={['/login']}>
        <Routes>
          <Route element={<GuestRoute />}>
            <Route path="/login" element={<TestPage />} />
          </Route>
        </Routes>
      </MemoryRouter>
    );

    expect(screen.getByText('Guest Page')).toBeInTheDocument();
  });

  it('redirects authenticated user to dashboard', () => {
    (useAuth as any).mockReturnValue({
      user: { id: 1, name: 'Test User' },
      loading: false,
    });

    render(
      <MemoryRouter initialEntries={['/login']}>
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route element={<GuestRoute />}>
            <Route path="/login" element={<TestPage />} />
          </Route>
        </Routes>
      </MemoryRouter>
    );

    expect(screen.getByText('Dashboard')).toBeInTheDocument();
  });
});
