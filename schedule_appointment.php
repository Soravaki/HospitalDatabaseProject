<?php

function createApptID($conn){
    // query to get latest used ID
    $result = pg_query($conn, "SELECT appt_id FROM appointment ORDER BY appt_id DESC LIMIT 1");

    if (!$result) {
        error_log("Error fetching last appointment ID: " . pg_last_error($conn));
        return false;
    }

    $row = pg_fetch_row($result);

    if (!$row) {
        // creates new id A000
        $appt_id = A . "000";
    } else {
        $last_id = $row[0];
        $last_number = substr($last_id, 1); // get number after base id

        // increment by 1
        $next_number = str_pad((int)$last_number + 1, 3, "0", STR_PAD_LEFT);

        // combine base part with new incremented number
        $appt_id = "A" . $next_number;
    }

    pg_free_result($result);
    return $appt_id;
}

function grabEssn($ptid, $conn){
    // Query to check if the patient has a primary physician
    $query = "  SELECT primary_phy_ssn 
                FROM patient 
                WHERE pt_id = $1";
                
    $result = pg_query_params($conn, $query, [$ptid]);

    // If query fails, log the error and return false
    if ($result === false) {
        error_log("Error executing query: " . pg_last_error($conn));
        return false;
    }

    // If the primary physician is not found, insert essnid as null;
    if (pg_num_rows($result) == 0) {
        return null;
    } 

    // fetch and return primary physician ssn
    $row = pg_fetch_assoc($result);
    return $row['primary_phy_ssn']; // return just SSN value
}

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

// get the submited information
$fname = $_POST['patient_fname'];
$lname = $_POST['patient_lname'];
$ptid = $_POST['patient_id'];
$date = $_POST['date'];
$time = $_POST['time'];

// check if patient is a valid patient by checking the ID
$query = "SELECT 1 FROM patient WHERE pt_id = $1";
$result = pg_query_params($conn, $query, [$ptid]);
if (!$result || !pg_fetch_row($result)) { 
    echo "Invalid Patient ID";
} else {
    // check if patient ID matches first and last name
    $query = "SELECT 1 FROM patient WHERE pt_id = $1 AND firstname = $2 AND lastname = $3";
    $result = pg_query_params($conn, $query, array($ptid, $fname, $lname));
        if (!$result || !pg_fetch_row($result)) {
            echo "Invalid Patient First/Last Name";
        } else {
            // insert into Appointment
            $apptid = createApptID($conn);
            $essn = grabEssn($ptid, $conn);
            $status = "Scheduled";
            $query = "INSERT INTO appointment(appt_id, pt_id, essn, date, time, status) VALUES ($1, $2, $3, $4, $5, $6)";
            $result = pg_query_params($conn, $query, array($apptid, $ptid, $essn, $date, $time, $status));
            echo " Appointment Scheduled";
        }
}

if (!$result) {
    error_log("Database query failed: " . pg_last_error($conn));
}

// Close the connection
if ($result) {
    pg_free_result($result);
}
pg_close($conn);

?>