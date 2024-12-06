<?php
// guest_functions.php
require_once 'db_connection.php';

// Generate Guest Registration Form
function generateGuestRegistrationForm() {
    return '
    <form action="process_guest.php" method="POST">
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" required>
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Phone:</label>
            <input type="tel" name="phone">
        </div>
        <div>
            <label>Nationality:</label>
            <input type="text" name="nationality">
        </div>
        <div>
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth">
        </div>
        <button type="submit">Register Guest</button>
    </form>';
}

// Fetch Guest Options for Reservation Form
function fetchGuestOptions() {
    global $conn;
    $guest_query = "SELECT guest_id, CONCAT(first_name, ' ', last_name) AS full_name FROM Guests";
    $guest_result = $conn->query($guest_query);
    
    $options = '';
    while ($guest = $guest_result->fetch_assoc()) {
        $options .= "<option value='{$guest['guest_id']}'>{$guest['full_name']}</option>";
    }
    
    return $options;
}

// Process Guest Registration
function processGuestRegistration($data) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Guests (first_name, last_name, email, phone, nationality, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['nationality'], $data['date_of_birth']);
    return $stmt->execute();
}
?>