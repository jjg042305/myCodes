function notBlank() {
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('findForm').addEventListener('submit',function(e){
        const petType = document.getElementById('petType').value.trim();
        const breed = document.getElementById('breed').value.trim();
        const age = document.getElementById('age').value.trim();
        const gender = document.getElementById('gender').value.trim();
        const along = document.getElementById('along').value.trim();
        const errorMessageElement = document.getElementById('error-message');

        if (petType === "" || breed === "" || age === "" || gender === "" || along === "") {
            e.preventDefault();

            // If any field is empty, display the error message
            errorMessageElement.style.display = "block";
        } else {
            // If all fields are filled, hide the error message and process the form (example: alert)
            errorMessageElement.style.display = "none";
            alert("Form submitted successfully!"); }
                                                                                 

      }
      )
    })
}

notBlank();