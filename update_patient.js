document.getElementById("verifyPatientID").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent normal form submission

    var pt_id = document.getElementById("pt_id").value; // Get patient ID from the input field

    // Create a new XMLHttpRequest to send the patient ID to the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "verify_patient.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText); // Parse the JSON response
            if (response.success) {
                // Populate the fields with the response data
                document.getElementById("firstname").value = response.firstname;
                document.getElementById("mi").value = response.mi;
                document.getElementById("lastname").value = response.lastname;
                document.getElementById("phone_num").value = response.phone_num;
                document.getElementById("email").value = response.email;
                document.getElementById("dob").value = response.dob;
                document.getElementById("gender").value = response.gender;
                document.getElementById("address").value = response.address;
                document.getElementById("primary_phy_ssn").value = response.primary_phy_ssn;
                document.getElementById("insurance_name").value = response.insurance_name;
                document.getElementById("pt_id_hidden").value = pt_id;      // Set pt_id in the hidden input field

                // Show the update form
                document.getElementById("verifyPatientID").style.display = "none";
                document.getElementById("updatePatientInfo").style.display = "block";
                
            } else {
                alert("Patient not found!");
            }
        } else {
            alert("Error retrieving patient data.");
        }
    };
    xhr.send("pt_id=" + encodeURIComponent(pt_id)); // Send the patient ID as form data
});

// Handle the form submission for updating patient information
document.getElementById('updatePatientInfo').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form from submitting normally

    // Perform Ajax request or submit form using fetch or XMLHttpRequest
    fetch('update_patient.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.text())
    .then(data => {
        // Display alert based on server response
        if (data.includes('successfully')) {
            alert('Patient information updated successfully!');
        } else {
            alert('Failed to update patient information. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update patient information. Please try again.');
    });
});