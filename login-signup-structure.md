sign up:
- the user will input their email
- the system will generate a 16 alphanumerical verification code and send it to the user's email
- the user will input the verification code
- if the verification code is correct, the user will be verified and will proceed to add a password
- the user will input their password
- the user will confirm their password
- if the passwords match, the user will be registered and will be redirected to the login page

NOTE:
- only one account can be created per email
- the user will not be able to login until they have verified their email
- in case if the user has not verified their email, they will be redirected to the login page and will be asked to verify their email
- in case if the user has not verified their email for more than 24 hours, they will be asked to sign up again
- in case if the user has not verified their email for more than 7 days, their account will be deleted
- in case if the user disconnected in the middle of the sign up process, they will be asked to sign up again



login:
- the user will input their email and password
- the user can choose if they want to remember their login
- if the user is verified, the user will be logged in
- if the user is not verified, the user will be asked to verify their email
- if the user is not verified for more than 24 hours, they will be asked to sign up again
- if the user is not verified for more than 7 days, their account will be deleted
- in case if the user disconnected in the middle of the login process, they will be asked to login again

forget password:
- the user will input their email
- the system will generate a 16 alphanumerical verification code and send it to the user's email
- the user will input the verification code
- if the verification code is correct, the user will be verified and will proceed to add a new password
- the user will input their new password
- the user will confirm their new password
- if the new passwords match, the user will be logged in

NOTE: since the forget password page is either at the login page or in the profile page, to prevent anyone from directly opening or accessing the forget password page, the user will be redirected to the login page