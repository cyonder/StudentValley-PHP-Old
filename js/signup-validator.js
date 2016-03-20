$(document).ready(function(){

    $.validator.addMethod("alphabetic",
        function(value, element){
            return /^[a-zA-Z]+$/.test(value);
        },
        "Alphabetic characters only!"
    );

    $("#signup").validate({
        rules: {
            firstName: {
                required: true,
                maxlength: 20,
                alphabetic: true
            },
            lastName: {
                required: true,
                maxlength: 20,
                alphabetic: true
            },
            email: {
                required: true,
                maxlength: 100,
                email: true
            },
            password: {
                required: true,
                maxlength: 64,
                minlength: 6
            }
        },
        messages: {
            firstName: {
                required: "Please enter your first name!",
                maxlength: "Cannot be longer than 20 characters!"
            },
            lastName: {
                required: "Please enter your last name!",
                maxlength: "Cannot be longer than 20 characters!"
            },
            email: {
                required: "Please provide an email!",
                maxlength: "Email is too long!",
                    email: "Enter a valid email address!"
            },
            password: {
                required: "Please provide a password!",
                maxlength: "Password is too long!",
                minlength: "Must be at least 6 characters long!"
            }
        }
    });
});