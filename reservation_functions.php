<?php
// reservation_functions.php
require_once 'db_connection.php';
require_once 'guest_functions.php';

// Generate Reservation Form
function generateReservationForm() {
    global $conn;
    
    // Fetch available rooms
    $room_query = "SELECT room_id, room_number, room_type FROM Rooms WHERE room_status = 'Available'";
    $room_result = $conn->query($room_query);
    
    $room_options = '';
    while ($room = $room_result->fetch_assoc()) {
        $room_options .= "<option value='{$room['room_id']}'>{$room['room_number']} - {$room['room_type']}</option>";
    }
    
    return '
    <form action="process_reservation.php" method="POST">
        <div>
            <label>Guest:</label>
            <select name="guest_id" required>
                ' . fetchGuestOptions() . '
            </select>
        </div>
        <div>
            <label>Room:</label>
            <select name="room_id" required>
                ' . $room_options . '
            </select>
        </div>
        <div>
            <label>Check-in Date:</label>
            <input type="date" name="check_in_date" required>
        </div>
        <div>
            <label>Check-out Date:</label>
            <input type="date" name="check_out_date" required>
        </div>
        <div>
            <label>Total Guests:</label>
            <input type="number" name="total_guests" min="1" required>
        </div>
        <button type="submit">Make Reservation</button>
    </form>';
}

// Process Reservation
function processReservation($data) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Reservations (guest_id, room_id, check_in_date, check_out_date, total_guests, reservation_status) VALUES (?, ?, ?, ?, ?, 'Confirmed')");
    $stmt->bind_param("iissi", $data['guest_id'], $data['room_id'], $data['check_in_date'], $data['check_out_date'], $data['total_guests']);
    return $stmt->execute();
}
?>