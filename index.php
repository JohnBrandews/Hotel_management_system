<?php
// index.php
require_once 'guest_functions.php';
require_once 'reservation_functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card {
            margin-bottom: 1.5rem;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color: #4a90e2;
            color: white;
            border-bottom: none;
            font-weight: 600;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .form-control {
            border-radius: 6px;
            border-color: #ced4da;
        }
        .form-control:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.25);
        }
        .btn-primary {
            background-color: #4a90e2;
            border-color: #4a90e2;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #357abd;
            border-color: #357abd;
        }
        .section-title {
            color: #4a90e2;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 0.5rem;
        }
    </style>
</head>
<body>
<div class="container">
        <h1 class="text-center mb-4" style="color: #4a90e2;">Hotel Management System</h1>

        <?php 
        // Check for success message
        if (isset($_GET['success']) && $_GET['success'] == 'reservation') {
            echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Reservation Successful!</strong> Your reservation has been confirmed.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        ?>
        

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Guest Registration</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Replace the default function with a Bootstrap-styled version
                        function generateBootstrapGuestRegistrationForm() {
                            return '
                            <form action="process_guest.php" method="POST">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality">
                                </div>
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Register Guest</button>
                            </form>';
                        }
                        echo generateBootstrapGuestRegistrationForm(); 
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Make Reservation</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Replace the default function with a Bootstrap-styled version
                        function generateBootstrapReservationForm() {
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
                                <div class="mb-3">
                                    <label for="guest_id" class="form-label">Guest</label>
                                    <select id="guest_id" name="guest_id" class="form-select" required>
                                        ' . fetchGuestOptions() . '
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="room_id" class="form-label">Room</label>
                                    <select id="room_id" name="room_id" class="form-select" required>
                                        ' . $room_options . '
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="check_in_date" class="form-label">Check-in Date</label>
                                    <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="check_out_date" class="form-label">Check-out Date</label>
                                    <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="total_guests" class="form-label">Total Guests</label>
                                    <input type="number" class="form-control" id="total_guests" name="total_guests" min="1" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Make Reservation</button>
                            </form>';
                        }
                        echo generateBootstrapReservationForm(); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>