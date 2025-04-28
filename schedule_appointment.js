document.getElementById("appointment_form").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent the form from submitting normally
    var formData = new FormData(this);  // Get the form data

    // Create a new AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "schedule_appointment.php", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Check the response text to see if the appointment ID is invalid
            if (xhr.responseText === "Appointment Scheduled") {
                alert(xhr.responseText);
            } else {
                alert(xhr.responseText);
            }
            // Handle the server's response
            document.getElementById("responseMessage").innerHTML = xhr.responseText;
        } else {
            // In case of an error
            document.getElementById("responseMessage").innerHTML = "Error making appointment. Please try again.";
        }
    };

    // Send the form data to the server
    xhr.send(formData);
});
