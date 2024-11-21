<?php
include('../config/db.php');

function register($usn, $pw)
{
    global $conn;

    // Check if the username already exists
    $sqlCheck = "SELECT * FROM users WHERE username = '$usn'";
    $result = $conn->query($sqlCheck);

    if ($result->num_rows > 0) {
        // Username already exists
        return false;
    } else {
        // Hash the password and insert new user
        $pwHash = password_hash($pw, PASSWORD_DEFAULT);
        $sqlInsert = "INSERT INTO users (username, password, role) VALUES ('$usn', '$pwHash', 'user')";
        
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
    
    header('Location: index.php');
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
