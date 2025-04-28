document.getElementById("reschedule_form").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent the form from submitting normally
    var formData = new FormData(this);  // Get the form data

    // Create a new AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "reschedule_appointment.php", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Check the response text to see if the appointment ID is invalid
            if (xhr.responseText === "Invalid Appointment ID") {
                alert("Invalid Appointment ID. Please try again.");
            } else if (xhr.responseText === "Appointment Rescheduled") {
                alert("Appointment successfully rescheduled!");
            }
            // Handle the server's response
            document.getElementById("responseMessage").innerHTML = xhr.responseText;
        } else {
            // In case of an error
            document.getElementById("responseMessage").innerHTML = "Error rescheduling appointment. Please try again.";
        }
    };

    // Send the form data to the server
    xhr.send(formData);
});

document.getElementById("cancel_appointment").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent the form from submitting normally
    var formData = new FormData(this);  // Get the form data

    // Create a new AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "cancel_appointment.php", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Check the response text to see if the appointment ID is invalid
            if (xhr.responseText === "Invalid Appointment ID") {
                alert("Invalid Appointment ID. Please try again.");
            } else if (xhr.responseText === "Appointment Canceled") {
                alert("Appointment successfully canceled!");
            }
            // Handle the server's response
            document.getElementById("responseMessage").innerHTML = xhr.responseText;
        } else {
            // In case of an error
            document.getElementById("responseMessage").innerHTML = "Error rescheduling appointment. Please try again.";
        }
    };

    // Send the form data to the server
    xhr.send(formData);
});
