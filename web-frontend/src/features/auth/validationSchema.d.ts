import * as yup from 'yup';
/**
* Validation schemas for auth forms
*
* Includes schemas for registration and login forms.
*
* @since 0.0.1
*/
export declare const registerSchema: yup.ObjectSchema<{
    name: string;
    email: string;
    password: string;
    confirmPassword: string;
    acceptTerms: NonNullable<boolean | undefined>;
}, yup.AnyObject, {
    name: undefined;
    email: undefined;
    password: undefined;
    confirmPassword: undefined;
    acceptTerms: undefined;
}, "">;
export declare const loginSchema: yup.ObjectSchema<{
    email: string;
    password: string;
}, yup.AnyObject, {
    email: undefined;
    password: undefined;
}, "">;
