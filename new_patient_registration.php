<?php
function createID($firstname, $mi, $lastname, $conn){
    // getting first letter and making base id
    $base_id = strtoupper(substr($firstname, 0, 1)) // first initial
             . strtoupper(substr($mi, 0, 1))        // middle initial
             . strtoupper(substr($lastname, 0, 1)); // last initial
    
    // query to get latest used ID
    $query = "SELECT pt_id FROM patient WHERE pt_id LIKE $1 ORDER BY pt_id DESC LIMIT 1";
    $result = pg_query_params($conn, $query, array($base_id . '%'));
    $row = pg_fetch_row($result);

    if (!$row) {
        // creates new id XXX0000
        $new_id = $base_id . "0000";
    } else {
        $last_id = $row[0];
        $last_number = substr($last_id, 3); // get number after base id

        // increment by 1
        $next_number = str_pad((int)$last_number + 1, 4, "0", STR_PAD_LEFT);

        // combine base part with new incremented number
        $new_id = $base_id . $next_number;
    }

    return $new_id;
}

function checkInsurance($insurancename, $insurancenum, $conn) {
    // Query to check if the insurance name already exists in the database
    $query = "  SELECT insurance_num 
                FROM insurance 
                WHERE insurance_name = $1";
                
    $result = pg_query_params($conn, $query, array($insurancename));

    // If query fails, log the error and return false
    if ($result === false) {
        error_log("Error executing query: " . pg_last_error($conn));
        return false;
    }

    // If the insurance is not found, insert the new insurance record
    if (pg_num_rows($result) == 0) {
        $query = "INSERT INTO insurance(insurance_name, insurance_num) VALUES ($1, $2)";
        $result = pg_query_params($conn, $query, array($insurancename, $insurancenum));

        // If inserting the insurance record fails, log the error and return false
        if ($result === false) {
            error_log("Error inserting insurance: " . pg_last_error($conn));
            return false;
        }
    }

    // Return true if insurance was either found or inserted
    return true;
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

// Get the submitted info
$firstname = $_POST["FirstName"];
$mi = $_POST["MI"];
$lastname = $_POST["LastName"];
$ssn = $_POST["ssn"];
$phonenum = $_POST["Phone_Num"];
$email = $_POST["Email"];
$dob = $_POST["DOB"];
$gender = $_POST["Gender"];
$streetadr = $_POST["StreetAddress"];
$city = $_POST["City"];
$state = $_POST["State"];
$insurancename = $_POST["Insurance_Name"];
$insurancenum = $_POST["Insurance_Num"];

// Log the SSN to the server log
error_log("Info received");


// Patient created

$pt_id = createID($firstname, $mi, $lastname, $conn);

checkInsurance($insurancename, $insurancenum, $conn);

// protects against SQL injection attacks
$query = "INSERT INTO patient(firstname, mi, lastname, pt_id, phone_num, email, dob, gender, address, primary_phy_ssn, insurance_name) 
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";

$result = pg_query_params($conn, $query, array($firstname, $mi, $lastname, $pt_id, $phonenum, $email, $dob, $gender, $streetadr, null, $insurancename));

// Check if the query was successful
if (!$result) {
    error_log("Error executing query: " . pg_last_error($conn));
} else {
    error_log("Executing query: " . $query);
}


echo "Patient Registered";

// Close the connection
pg_free_result($result);
pg_close($conn);





?>

