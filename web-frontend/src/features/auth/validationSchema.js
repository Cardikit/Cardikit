import * as yup from 'yup';
/**
* Validation schemas for auth forms
*
* Includes schemas for registration and login forms.
*
* @since 0.0.1
*/
export const registerSchema = yup.object({
    name: yup.string().required('Name is required').min(2, 'Name must be at least 2 characters long').max(50, 'Name must be less than 50 characters long'),
    email: yup.string().email('Invalid email').required('Email is required'),
    password: yup.string().required('Password is required').min(6, 'Password must be at least 6 characters long').max(255, 'Password must be less than 255 characters long'),
    confirmPassword: yup.string().oneOf([yup.ref('password')], 'Passwords must match').required('Please confirm your password'),
    acceptTerms: yup.bool().oneOf([true], 'Terms must be accepted').required('Terms must be accepted'),
});
export const loginSchema = yup.object({
    email: yup.string().email('Invalid email').required('Email is required'),
    password: yup.string().max(255, 'Password must be less than 255 characters long').required('Password is required'),
});
