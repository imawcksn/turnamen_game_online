<?php
include('../config/db.php');

function register($usn, $pw, $email)
{
    global $conn;

    // Check if the username already exists
    $sqlCheck = "SELECT * FROM users WHERE username = '$usn'";
    $sqlCheck = "SELECT * FROM users WHERE email = '$email'";

    $result = $conn->query($sqlCheck);

    if ($result->num_rows > 0) {
        // Username already exists
        return false;
    } else {
        // Hash the password and insert new user
        $pwHash = password_hash($pw, PASSWORD_DEFAULT);
        $sqlInsert = "INSERT INTO users (email, username, password, role) VALUES ('$email', '$usn', '$pwHash', 'user')";
        
        // Execute insert query
        if ($conn->query($sqlInsert)) {
            return true;
        } else {
            return false; // If there is an issue with the query
        }
    }
}

function login($usn, $pw)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE username='$usn'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($pw, $user['password'])) {
            $_SESSION['user'] = $user;
            return true;
        }
    }
    return false;
}

function logout() {
    // Start the session
    session_start();

    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();
    
    header('Location: home.php');
    exit();
}

function create_tournament($name, $description, $schedule, $livestream)
{
    global $conn;
    $sql = "INSERT INTO tournaments (name, description, schedule, livestream) VALUES ('$name', '$description', '$schedule', '$livestream')";
    return $conn->query($sql);
}

function getTournamentCategories()
{
    global $conn;
    return $conn->query("SELECT * FROM tournament_categories");
}

function getTournamentCategoryById($id)
{
    global $conn;
    return $conn->query("SELECT * FROM tournament_categories WHERE id = $id")->fetch_assoc();
}

function getTournaments()
{
    global $conn;
    return $conn->query("SELECT * FROM tournaments");
}

function getTournamentById($id)
{
    global $conn;
    return $conn->query("SELECT * FROM tournaments WHERE id=$id")->fetch_assoc();
}

function updateTournament($id, $name, $description, $schedule, $livestream)
{
    global $conn;
    $sql = "UPDATE tournaments SET name='$name', description='$description', schedule='$schedule', livestream='$livestream' WHERE id=$id";
    return $conn->query($sql);
}

function deleteTournament($id)
{
    global $conn;

    $sql = "DELETE FROM tournaments WHERE id=$id";
    return $conn->query($sql);
}

function getDataFromTable($table) {
    global $conn;
    $query = "SELECT * FROM $table";
    return $conn->query($query);
}

function updateUserProfile($userId, $username, $email, $hashedPassword = null) {
    global $conn;

    $sqlCheck = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $sqlCheck->bind_param("ssi", $username, $email, $userId);
    $sqlCheck->execute();
    $result = $sqlCheck->get_result();

    if ($result->num_rows > 0) {
        return false;
    }

    // Prepare query for updating user profile
    if ($hashedPassword) {
        // If password is provided, update it as well
        $hashedPassword = password_hash($hashedPassword, PASSWORD_DEFAULT);
        $sqlUpdate = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $sqlUpdate->bind_param("sssi", $username, $email, $hashedPassword, $userId);
    } else {
        // Update without changing the password
        $sqlUpdate = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $sqlUpdate->bind_param("ssi", $username, $email, $userId);
    }

    // Execute the update query
    if ($sqlUpdate->execute()) {
        return true;
    } else {
        return false; // Query execution failed
    }
}
function deleteRecord($table, $id) {
    global $conn;

    //versi lebih aman dan efisien dari query
    $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
    if (!$stmt) {
        return "Error preparing query: " . $conn->error;
    }

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Error executing query: " . $stmt->error;
    }
}

function editRecord($table, $columns, $placeholders, $bindTypes, $data) {
    global $conn;

    // If the table is 'users', check for duplicate email or username
    if ($table === 'users') {
        // Check if the email already exists
        if (isset($data['email'])) {
            $email = $data['email'];
            $emailCheckQuery = "SELECT COUNT(*) FROM $table WHERE email = ?";
            $stmt = $conn->prepare($emailCheckQuery);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($emailCount);
            $stmt->fetch();
            $stmt->close();
            
            if ($emailCount > 0) {
                // If the email already exists, return false with an error message
                return ["error" => "Email already exists."];
            }
        }

        // Check if the username already exists
        if (isset($data['username'])) {
            $username = $data['username'];
            $usernameCheckQuery = "SELECT COUNT(*) FROM $table WHERE username = ?";
            $stmt = $conn->prepare($usernameCheckQuery);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($usernameCount);
            $stmt->fetch();
            $stmt->close();

            if ($usernameCount > 0) {
                // If the username already exists, return false with an error message
                return ["error" => "Username already exists."];
            }
        }

        // If a password is provided, hash it
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); // Hash the password
        }
    }

    // Prepare the query for insertion
    $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Failed to prepare statement: " . $conn->error);
        return false; // Return false on failure
    }

    // Extract values and bind parameters
    $params = array_values($data);
    $stmt->bind_param($bindTypes, ...$params);

    // Execute the query
    if ($stmt->execute()) {
        $stmt->close();
        return true; // Return true on success
    } else {
        // Log and return error
        error_log("Error inserting data: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

// Function to validate input data
function validateInput($field, $value) {
    $valid = true;
    switch ($field) {
        case 'email':
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $valid = false;
            }
            break;
        case 'start_date':
        case 'release_date':
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                $valid = false;
            }
            break;
        case 'price':
        case 'slot':
        case 'max_slot':
        case 'prize':
            if (!is_numeric($value) || (int)$value != $value) {
                $valid = false; // Ensure it's an integer
            }
            break;
        default:
            if (empty($value)) {
                $valid = false;
            }
            break;
    }
    return $valid;
}

function getRecordById($table, $id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE `id` = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}   

function updateRecord($table, $id, $data) {
    global $conn;
    $columns = array_keys($data);
    $values = array_values($data);
    
    $setClause = implode(', ', array_map(fn($col) => "`$col` = ?", $columns));
    $stmt = $conn->prepare("UPDATE `$table` SET $setClause WHERE `id` = ?");
    $values[] = $id; // Add the record ID as the last parameter

    $types = str_repeat('s', count($values) - 1) . 'i'; // Determine types for bind_param
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
}
