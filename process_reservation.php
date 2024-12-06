<?php
// process_reservation.php
// Add error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure database connection
require_once 'db_connection.php';
require_once 'reservation_functions.php';

// Debug: Print out all POST data
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Verify the request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request method. Please submit the form properly.");
}

// Collect form data with additional validation
$reservationData = [
    'guest_id' => isset($_POST['guest_id']) ? intval($_POST['guest_id']) : 0,
    'room_id' => isset($_POST['room_id']) ? intval($_POST['room_id']) : 0,
    'check_in_date' => isset($_POST['check_in_date']) ? $_POST['check_in_date'] : '',
    'check_out_date' => isset($_POST['check_out_date']) ? $_POST['check_out_date'] : '',
    'total_guests' => isset($_POST['total_guests']) ? intval($_POST['total_guests']) : 1
];

// Validate input
if ($reservationData['guest_id'] <= 0 || $reservationData['room_id'] <= 0) {
    die("Invalid guest or room selection.");
}

if (empty($reservationData['check_in_date']) || empty($reservationData['check_out_date'])) {
    die("Check-in and check-out dates are required.");
}

// Process reservation with extended error handling
try {
    // Start a transaction
    $conn->begin_transaction();

    // Prepare reservation insertion
    $stmt = $conn->prepare("INSERT INTO Reservations (guest_id, room_id, check_in_date, check_out_date, total_guests, reservation_status) VALUES (?, ?, ?, ?, ?, 'Confirmed')");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("iissi", 
        $reservationData['guest_id'], 
        $reservationData['room_id'], 
        $reservationData['check_in_date'], 
        $reservationData['check_out_date'], 
        $reservationData['total_guests']
    );

    // Execute reservation
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Update room status
    $updateRoomStatus = $conn->prepare("UPDATE Rooms SET room_status = 'Occupied' WHERE room_id = ?");
    $updateRoomStatus->bind_param("i", $reservationData['room_id']);
    
    if (!$updateRoomStatus->execute()) {
        throw new Exception("Room status update failed: " . $updateRoomStatus->error);
    }

    // Commit transaction
    $conn->commit();

    // Redirect with success message
    header("Location: index.php?success=reservation");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    // Display detailed error
    echo "Reservation Error: " . $e->getMessage();
    
    // Log the error (optional)
    error_log("Reservation Error: " . $e->getMessage());
}

// Close statements and connection
$stmt->close();
$updateRoomStatus->close();
$conn->close();
?>