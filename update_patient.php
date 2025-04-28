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

// Get the submitted data
$firstname = $_POST['firstname'];
$mi = $_POST['mi'];
$lastname = $_POST['lastname'];
$phonenum = $_POST['phone_num'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$primary_phy_ssn = $_POST['primary_phy_ssn'];
$insurance_name = $_POST['insurance_name'];
$ptid = $_POST['pt_id'];



error_log("ptid: " . $ptid);

// protects against SQL injection attacks
$query ="   UPDATE patient 
            SET firstname = $1,
                mi = $2,
                lastname = $3,
                phone_num = $4,
                email = $5,
                dob = $6,
                gender = $7,
                address = $8, 
                insurance_name = $9";

// setup query for updating information for pt_id patient
$query .= " WHERE pt_id::text = $10";

// If primary_phy_ssn is not empty, add it to the query
if (!empty($primary_phy_ssn)) {
    $query .= ", primary_phy_ssn = $11";
}

// Prepare the parameters for the query
$params = array($firstname, $mi, $lastname, $phonenum, $email, $dob, $gender, $address, $insurance_name, $ptid);

// Add primary_phy_ssn to the parameters array if it's not empty
if (!empty($primary_phy_ssn)) {
    $params[] = $primary_phy_ssn;
}

$result = pg_query_params($conn, $query, $params);

if (!$result) {
    error_log("Database query failed: " . pg_last_error($conn));
}

// Close the connection
pg_free_result($result);
pg_close($conn);

?>