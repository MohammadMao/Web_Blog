// console.log("registration.js loaded!");
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        // console.log("Form submitted!");
        event.preventDefault();

        let errors = []; // Array to store validation errors

        // Get form values
        let username = document.getElementById('username').value;
        let password = document.getElementById('password').value;
        let email = document.getElementById('email').value;
        let full_name = document.getElementById('full_name').value;
        let profile_image = document.getElementById('profile_image').files[0];

        let validEmails = ['gmail', 'hotmail', 'outlook'];

        // Validate inputs
        if (!username) {
            errors.push("Username is required");
        }
        if (!password) {
            errors.push("Password is required");
        }
        if(password.length < 6){
            errors.push("Password is too short");
        }
        if(password.length > 15){
            errors.push("Password is too long");
        }
        if (!email) {
            errors.push("Email is required");
        }
        if (!full_name) {
            errors.push("Full Name is required");
        }

        

        // Validate profile image if provided
        if (profile_image) {
            
            let imageFileType = profile_image.type.toLowerCase();
            let validImageTypes = ["image/jpg", "image/jpeg", "image/png", "image/gif"];

            if (!validImageTypes.includes(imageFileType)) {
                errors.push("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            }

            if (profile_image.size > 500000) { // 500 KB
                errors.push("Sorry, your file is too large.");
            }

            // Validate that the file is an image
            let fileReader = new FileReader();
            let imageValidationPromise = new Promise((resolve, reject) => {
                fileReader.onload = function(e) {
                    let img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        if (!img.width || !img.height) {
                            errors.push("File is not an image.");
                        }
                        resolve(); // Resolve the promise once image validation is done
                    };
                    img.onerror = function() {
                        errors.push("File is not an image.");
                        resolve(); // Resolve the promise even if there's an error
                        
                    };
                };
                fileReader.onerror = function() {
                    errors.push("Error reading file.");
                    resolve(); // Resolve the promise even if there's an error
                };
                fileReader.readAsDataURL(profile_image);
                
            });

        } else {
            errors.push("Upload a profile picture");
        }

        displayErrors(errors);

        // Display errors
        function displayErrors(errors) {
            // console.log("errorsssssssss!");
            let errorMessagesDiv = document.getElementById('errorMessages');
            errorMessagesDiv.innerHTML = ""; // Clear previous errors
            if (errors.length > 0) {
                // Display errors, but don't prevent submission yet
                errors.forEach(function(error) {
                    // console.log("errorsssssssss!");
                    let errorElement = document.createElement('p');
                    errorElement.textContent = "Error: " + error;
                    errorMessagesDiv.appendChild(errorElement);
                });
            } else {
                // No errors, proceed with form submission
                submitForm();
            }
        }
        // Example function to handle form submission via AJAX
        function submitForm() {
            let formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);
            formData.append('email', email);
            formData.append('full_name', full_name);
            if (profile_image) {
                formData.append('profile_image', profile_image);

            }

            fetch('../php_processes/register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '../views/dashboard.php';
                } else {
                    displayErrors(data.errors);
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                displayErrors(['An unexpected error occurred. Please try again later.']);
            });
        }
    });
});