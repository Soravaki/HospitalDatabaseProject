<?php
// database connection details
$host = "localhost";
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "postgres";

// Create connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Check if the connection is successful
if (!$conn) {
    // Retrieve the last error from the PHP error log
    $error = error_get_last();
    die("Connection failed: " . $error['message']);
} else {
    error_log("Connected to PostgreSQL database!");
}

// Get the submitted SSN
$apptid = $_POST['Appt_ID'];
$date = $_POST['New_Date'];
$time = $_POST['New_Time'];

// Log the SSN to the server log
error_log("Appt_ID received: " . $apptid); // This will log the appt_id in the PHP error log

// protects against SQL injection attacks
$query = "  SELECT * 
            FROM appointment 
            WHERE appt_id::text = $1"; // checks if appt_id is in the appointments
            
$result = pg_query_params($conn, $query, array($apptid));

if (!$result) {
    error_log("Database query failed: " . pg_last_error($conn));
}

if (pg_num_rows($result) > 0) {
    // Appointment found

    $query = "UPDATE appointment SET date = $1, time = $2 WHERE appt_id::text = $3";
    $result = pg_query_params($conn, $query, array($date, $time, $apptid));
    echo "Appointment Rescheduled";
    

} else {
    // Appointment not found
    echo "Invalid Appointment ID";
    
}



// Close the connection
pg_free_result($result);
pg_close($conn);

?>