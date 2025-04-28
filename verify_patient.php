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
$ptid = $_POST['pt_id'];

// Log the SSN to the server log
error_log("pt_id received: " . $ptid); // This will log the appt_id in the PHP error log

// protects against SQL injection attacks
$query = "SELECT * FROM patient WHERE pt_id::text = $1"; // checks if appt_id is in the appointments
$result = pg_query_params($conn, $query, array($ptid));

if (!$result) {
    error_log("Database query failed: " . pg_last_error($conn));
}

if (pg_num_rows($result) > 0) {

    // Fetch the row
    $row = pg_fetch_assoc($result);

    // pt_id found
    echo json_encode([
        'success' => true,
        'firstname' => $row['firstname'],
        'mi' => $row['mi'],
        'lastname' => $row['lastname'],
        'phone_num' => $row['phone_num'],
        'email' => $row['email'],
        'dob' => $row['dob'],
        'gender' => $row['gender'],
        'address' => $row['address'],
        'primary_phy_ssn' => $row['primary_phy_ssn'],
        'insurance_name' => $row['insurance_name']
    ]);
} else {
    echo json_encode(['success' => false]);
}

// Close the connection
pg_close($conn);

?>