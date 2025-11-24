/**
* GuestRoute
* ----------
* Prevents authenticated users from accessing guest-only pages (e.g. login/register).
*
* Logic:
* - If authentication is still loading, show the loading screen.
* - If user is not logged in, render the nested route via <Outlet />.
* - If user *is* logged in, redirect to dashboard.
*
* Usage:
* Wraps routes like `/login`, `/register`, `/` that should only be accessible to unauthenticated users.
*
* @since 0.0.1
*/
export default function GuestRoute(): import("react/jsx-runtime").JSX.Element;
