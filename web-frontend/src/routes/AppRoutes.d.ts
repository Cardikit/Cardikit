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
declare const AppRoutes: React.FC;
export default AppRoutes;
