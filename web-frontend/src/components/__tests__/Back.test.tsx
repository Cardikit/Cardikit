import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { describe, it, vi, expect } from 'vitest';
import { MemoryRouter } from 'react-router-dom';
import Back from '../Back';

// Create a manual mock for useNavigate
const mockNavigate = vi.fn();
vi.mock('react-router-dom', async () => {
  const actual = await vi.importActual<typeof import('react-router-dom')>('react-router-dom');
  return {
    ...actual,
    useNavigate: () => mockNavigate,
  };
});

describe('Back Component', () => {
  it('calls navigate(-1) when the button is clicked', async () => {
    render(
      <MemoryRouter>
        <Back />
      </MemoryRouter>
    );

    const button = screen.getByRole('button');
    await userEvent.click(button);

    expect(mockNavigate).toHaveBeenCalledWith(-1);
  });
});

