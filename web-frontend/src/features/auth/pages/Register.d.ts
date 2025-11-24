/**
* Register Screen
* ---------------
*  This screen enables new users to create an account in the Cardikit app.
*  It includes:
*  - Inputs for name, email, password, confirm password
*  - Terms & Conditions checkbox (validated)
*  - Error handling for duplicate accounts or input issues
*  - Link to login for existing users
*
*  On success, triggers auth context refresh to reflect the new session.
*  UI follows a clean, mobile-first layout with friendly form UX.
*
*  @since 0.0.1
*/
declare const Register: React.FC;
export default Register;
