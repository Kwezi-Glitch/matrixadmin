<?php

/**
 * Please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
 * in the application's header (in views/_header.php). To add new languages simply copy this file,
 * and create a language switch in your root files.
 */

// login & registration classes
define("MESSAGE_ACCOUNT_NOT_ACTIVATED", "Account suspended. Please click on the confirm link in the mail to reactivate.");

define("MESSAGE_CAPTCHA_WRONG", "Captcha was wrong!");
define("MESSAGE_COOKIE_INVALID", "Invalid cookie");
define("MESSAGE_DATABASE_ERROR", "There seems to be a database connection problem.");
define("MESSAGE_EMAIL_EXISTS", "This email address is already registered. Please use \"I forgot my credentials\" for help.");
define("MESSAGE_EMAIL_CHANGE_FAILED", "Sorry, your email changing failed.");
define("MESSAGE_EMAIL_CHANGED_SUCCESSFULLY", "Your email address has been changed successfully. New email address is ");
define("MESSAGE_EMAIL_EMPTY", "Email cannot be empty.");
define("MESSAGE_EMAIL_INVALID", "Please check your email address format.");
define("MESSAGE_EMAIL_SAME_LIKE_OLD_ONE", "Sorry, that email address is the same as your current one. Please choose another one.");
define("MESSAGE_EMAIL_TOO_LONG", "Please enter a valid email address.");
define("MESSAGE_LINK_PARAMETER_EMPTY", "Empty link parameter data.");
define("MESSAGE_LOGGED_OUT", "You have been logged out.");
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
define("MESSAGE_LOGIN_FAILED", "Login failed.");
define("MESSAGE_OLD_PASSWORD_WRONG", "Your OLD password was wrong.");
define("MESSAGE_CONFIRM_PASSWORD", "Please enter matching passwords.");
define("MESSAGE_PASSWORD_CHANGE_FAILED", "Sorry, your password changing failed.");
define("MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY", "Password successfully changed!");
define("MESSAGE_PASSWORD_EMPTY", "Please input a password.");
define("MESSAGE_PASSWORD_RESET_MAIL_FAILED", "Password reset mail NOT successfully sent! Error: ");
define("MESSAGE_PASSWORD_RESET_MAIL_SUCCESSFULLY_SENT", "Password reset mail successfully sent!");
define("MESSAGE_PASSWORD_TOO_SHORT", "Password should have atleast 6 characters");
define("MESSAGE_PASSWORD_WRONG", "Wrong password. Try again.");
define("MESSAGE_PASSWORD_WRONG_3_TIMES", "You have entered an incorrect password 3 or more times already. Please wait for 30 seconds to try again.");
define("MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL", "Sorry, no such id/verification code combination here...");
define("MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL", "Activation was successful! You can now log in!");
define("MESSAGE_REGISTRATION_FAILED", "Sorry, your registration has failed. Please go back and try again. Contact support for help");
define("MESSAGE_RESET_LINK_HAS_EXPIRED", "Your reset link has expired. Please use the reset link within one hour.");
define("MESSAGE_VERIFICATION_MAIL_ERROR", "Sorry, we could not send you an verification mail. Your account has NOT been created.");
define("MESSAGE_VERIFICATION_MAIL_NOT_SENT", "Verification Mail NOT successfully sent! Error: ");
define("MESSAGE_VERIFICATION_MAIL_SENT", "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.");
define("MESSAGE_USER_DOES_NOT_EXIST", "This user does not exist");
define("MESSAGE_USERNAME_BAD_LENGTH", "Username cannot be shorter than 2 characters or longer than 20 characters");
define("MESSAGE_USERNAME_CHANGE_FAILED", "Sorry, your chosen username renaming failed");
define("MESSAGE_USERNAME_CHANGED_SUCCESSFULLY", "Your username has been changed successfully. New username is ");
define("MESSAGE_USERNAME_EMPTY", "Please input your username to continue.");
define("MESSAGE_USERNAME_EXISTS", "Sorry, that username is already taken.");
define("MESSAGE_USERNAME_INVALID", "Username not allowed. Rules: Only letters a-Z and numbers 0-9 are allowed, Limit: 2 to 30 characters");
define("MESSAGE_USERNAME_SAME_LIKE_OLD_ONE", "Sorry, that username is the same as your current one. Please choose another one.");

// views
define("WORDING_BACK_TO_LOGIN", "Back to Login Page");
define("WORDING_CHANGE_EMAIL", "Change email");
define("WORDING_CHANGE_PASSWORD", "Change password");
define("WORDING_CHANGE_USERNAME", "Change username");
define("WORDING_CURRENTLY", "currently");
define("WORDING_EDIT_USER_DATA", "Edit user data");
define("WORDING_EDIT_YOUR_CREDENTIALS", "You are logged in and can edit your credentials here");
define("WORDING_FORGOT_MY_PASSWORD", "I forgot my password");
define("WORDING_LOGIN", "Log in");
define("WORDING_LOGOUT", "Log out");
define("WORDING_NEW_EMAIL", "New email");
define("WORDING_NEW_PASSWORD", "New password");
define("WORDING_NEW_PASSWORD_REPEAT", "Repeat new password");
define("WORDING_NEW_USERNAME", "New username (username cannot be empty and must be azAZ09 and 2-64 characters)");
define("WORDING_OLD_PASSWORD", "Your OLD Password");
define("WORDING_PASSWORD", "Password");
define("WORDING_PROFILE_PICTURE", "Your profile picture (from gravatar):");
define("WORDING_REGISTER", "Register");
define("WORDING_REGISTER_NEW_ACCOUNT", "Register new account");
define("WORDING_REGISTRATION_CAPTCHA", "Please enter these characters");
define("WORDING_REGISTRATION_EMAIL", "Email");
define("WORDING_REGISTRATION_PASSWORD", "Password");
define("WORDING_REGISTRATION_PASSWORD_REPEAT", "Password repeat");
define("WORDING_REGISTRATION_USERNAME", "Username");
define("WORDING_REMEMBER_ME", "Remenber me");
define("WORDING_REQUEST_PASSWORD_RESET", "Request a password reset. Enter your username and you'll get a mail with instructions:");
define("WORDING_RESET_PASSWORD", "Reset my password");
define("WORDING_SUBMIT_NEW_PASSWORD", "Submit new password");
define("WORDING_USERNAME", "Username");
define("WORDING_YOU_ARE_LOGGED_IN_AS", "You are logged in as ");
//proflie page
define("WORDING_NAME","Name");
define("WORDING_PHONE_NUMBER","Phone Number");
define("WORDING_BANK_NAME","Bank Name");
define("WORDING_ACCOUNT_NAME","Account Name");
define("WORDING_ACCOUNT_NUMBER","Account Number");
define("WORDING_SUBMIT","Submit");