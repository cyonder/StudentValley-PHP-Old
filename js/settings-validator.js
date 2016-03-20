$(document).ready(function(){

    $.validator.addMethod("alphabetic",
        function(value, element){
            return /^[a-zA-Z]+$/.test(value);
        },
        "Alphabetic characters only!"
    );

    $("#profile").validate({
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
            displayName: {
              maxlength: 20
            },
            phone: {
                maxlength: 15,
                digits: true
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
            displayName: {
                maxlength: "Cannot be longer than 20 characters!"
            },
            phone: {
                maxlength: "Cannot be longer than 15 digits!",
                digits: "Numbers only!"
            }
        }
    });
});


$(document).ready(function(){

    $.validator.addMethod("alphabetic",
        function(value, element){
            return /^[a-zA-Z]+$/.test(value);
        },
        "Alphabetic characters only!"
    );

    $("#email").validate({
        rules: {
            email: {
                required: true,
                maxlength: 100,
                email: true
            }
        },
        messages: {
            email: {
                required: "Please provide an email!",
                maxlength: "Email is too long!",
                email: "Enter a valid email address!"
            }
        }
    });
});

$(document).ready(function(){

    $.validator.addMethod("alphabetic",
        function(value, element){
            return /^[a-zA-Z]+$/.test(value);
        },
        "Alphabetic characters only!"
    );

    $("#password").validate({
        rules: {
            currentPassword: {
                required: true
            },
            newPassword: {
                required: true,
                maxlength: 64,
                minlength: 6
            },
            newPasswordAgain: {
                equalTo: "#newPassword"
            }
        },
        messages: {
            currentPassword: {
                required: "Provide your current password!"
            },
            newPassword: {
                required: "Please provide a password!",
                maxlength: "Password is too long!",
                minlength: "Must be at least 6 characters long!"
            },
            newPasswordAgain: {
                equalTo: "Password does not match!"
            }
        }
    });
});