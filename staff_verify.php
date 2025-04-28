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
$ssn = $_POST['ssn'];
// Log the SSN to the server log
error_log("SSN received: " . $ssn); // This will log the SSN in the PHP error log

// protects against SQL injection attacks
$query = "SELECT * FROM employee WHERE ssn::text = $1";
$result = pg_query_params($conn, $query, array($ssn));

if (!$result) {
    error_log("Database query failed: " . pg_last_error($conn));
}

if (pg_num_rows($result) > 0) {
    // SSN found
    echo "Login successful";
} else {
    // SSN not found
    echo "Invalid SSN. Please try again.";
    
}

// Close the connection
pg_free_result($result);
pg_close($conn);

?>