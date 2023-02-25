//the script we are writing uses ajax and jquery to implement some functionality
$('document').ready(function () {
    $('.registerform').validate({ 
        rules: {//object that is used to capture key/pairs from markup using their markup
            user_name: {
                required: true,
                minlength: 5
            },
            user_email: {
                required: true,
                email: true
            },
            user_password: {
                required: true,
                minlength: 6,
                maxlength:12
            },
            user_password_repeat:{
                required: true,
                equalTo: '#password'
            }
        },
        messages:{
            user_name:{
                required:"Please a enter a user name.",
                minlength:"Username too short.Please enter atleast 5 or more characters"
            },user_email: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },user_password: {
                required: "Please enter a password",
                minlength: "Password too short. Atleast 6 or more characters",
                maxlength:"Password too long. Not more than 12 characters."
            },user_password_repeat:{
                required: "Please re type your password",
                equalTo: "Passwords don't match. Enter matching passwords"
            }
        }
            
        

    });

});