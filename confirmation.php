<?php
session_start();

// Check if reservation was successful
if (!isset($_SESSION['reservation_success']) || !$_SESSION['reservation_success']) {
    header("Location: index.php");
    exit();
}

// Get reservation details
$reservationDetails = $_SESSION['reservation_details'];

// Clear the session
unset($_SESSION['reservation_success']);
unset($_SESSION['reservation_details']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .confirmation-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 500px;
            width: 100%;
        }
        .success-icon {
            color: #4a90e2;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="confirmation-card text-center">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
            </svg>
        </div>
        <h2>Reservation Confirmed!</h2>
        <div class="reservation-details mt-4">
            <p><strong>Check-in Date:</strong> <?php echo $reservationDetails['check_in_date']; ?></p>
            <p><strong>Check-out Date:</strong> <?php echo $reservationDetails['check_out_date']; ?></p>
            <p><strong>Total Guests:</strong> <?php echo $reservationDetails['total_guests']; ?></p>
            <p><strong>Total Price:</strong> <?php echo '$' . number_format($reservationDetails['total_price'], 2); ?></p>
        </div>
        <div class="mt-4">
            <a href="index.php" class="btn btn-primary">Make Another Reservation</a>
        </div>
    </div>
</body>
</html>