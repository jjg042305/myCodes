function validate() {
    document.addEventListener('DOMContentLoaded', function () {         // Add a submit event listener to the form with ID 'formV'

        document.getElementById('formV').addEventListener('submit', function (e) {
            e.preventDefault();             // Prevent the form from submitting to allow for validation checks

            const petType = document.getElementById('petType').value.trim(); // Retrieve and trim input values from the form to remove whitespace

            const breed = document.getElementById('breed').value.trim();
            const age = document.getElementById('age').value.trim();
            const gender = document.getElementById('gender').value.trim();
            const alongC = document.getElementById('alongC').value.trim();
            const alongD = document.getElementById('alongD').value.trim();
            const descript = document.getElementById('descrip').value.trim();
            const name = document.getElementById('ownerName').value.trim();
            const email = document.getElementById('email').value.trim();

            const errorMessageElement = document.getElementById('error-message');   // Identify the elements that will display error messages

            const errorMessageElement2 = document.getElementById('error-message2');

            // Check if any of the form fields are empty or if the email is invalid
            if (petType === "" || breed === "" || age === "" || gender === "" || alongC === "" || alongD === "" || descript === "" || name === "" || email === "") {
                errorMessageElement.style.display = "block";
                if (!(emailValidation(email))) {
                    errorMessageElement2.style.display = "block";
                }
                else errorMessageElement2.style.display = "none";

            }
            else {
                errorMessageElement.style.display = "none";
                if (!(emailValidation(email))) {
                    errorMessageElement2.style.display = "block";
                }
                else {
                    errorMessageElement2.style.display = "none";
                    sendData({
                        petType: petType,
                        breed: breed,
                        age: age,
                        gender: gender,
                        alongC: alongC,
                        alongD: alongD,
                        descript: descript,
                        name: name,
                        email: email
                    });
                }
            }
        })
    })
}

function sendData(data) {
    fetch('/submitGiveaway', {
        method: 'POST',
        body: new FormData(document.getElementById('formV')),
        credentials: 'same-origin'  // Ensure cookies are sent
        // Do not manually set Content-Type when using FormData
    }).then(response => response.text()).then(text => {
        document.open();
        document.write(text);
        document.close();
    }).catch(error => console.error('Error:', error));
}


function emailValidation(email) {
    if (!(email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/))) {   // Check the email against a regular expression for a basic valid email format

        return false;
    }
    else if (String(email).length > 254) {
        return false;
    }
    else return true;
}

validate();