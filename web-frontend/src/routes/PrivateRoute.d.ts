/**
* PrivateRoute
* ------------
* Protects routes that require authentication.
*
* Logic:
* - While auth state is loading, show a loading indicator.
* - If user is authenticated, render the nested route via <Outlet />.
* - If not authenticated, redirect to the login page.
*
* Usage:
* Wraps any route that should only be accessible to logged-in users (e.g. /dashboard).
*
* @since 0.0.1
*/
export default function PrivateRoute(): import("react/jsx-runtime").JSX.Element;
