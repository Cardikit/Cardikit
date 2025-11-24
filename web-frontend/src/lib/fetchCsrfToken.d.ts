/**
* Utility function to fetch the CSRF token from the server
*
* Sets the `X-CSRF-TOKEN` header in the axios instance
*
* NOTE: Use this utility before each `POST`, `PUT`, or `DELETE` request
*
* @since 0.0.1
*/
export declare const fetchCsrfToken: () => Promise<void>;
