<?php
// process_guest.php
require_once 'db_connection.php';
require_once 'guest_functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $guestData = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'] ?? '',
        'nationality' => $_POST['nationality'] ?? '',
        'date_of_birth' => $_POST['date_of_birth'] ?? null
    ];

    // Process guest registration
    if (processGuestRegistration($guestData)) {
        // echo "Guest registered successfully!";
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Regestration Successful!</strong> Your registration has been confirmed.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
        // Redirect to a confirmation page or list of guests
        // header("Location: guests_list.php");
    } else {
        echo "Error registering guest: " . $conn->error;
    }

    // Close the connection
    $conn->close();

?>