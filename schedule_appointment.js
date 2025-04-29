document.getElementById("appointment_form").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent the form from submitting normally
    var formData = new FormData(this);  // Get the form data

    // Create a new AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "schedule_appointment.php", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var responseText = xhr.responseText.trim(); // Clean extra spaces
            if (responseText === "Appointment Scheduled") {
                alert("Success: " + responseText);
            } else {
                alert("Error: " + responseText);
            }
            // Update the page with the server's response
            document.getElementById("responseMessage").innerHTML = responseText;
        } else {
            // In case of an error
            alert("Failed to connect to server.");
            document.getElementById("responseMessage").innerHTML = "Error making appointment. Please try again.";
        }
    };

    xhr.onerror = function() {
        alert("Network error.");
    };

    // Send the form data to the server
    xhr.send(formData);
});
