<?php
require __DIR__ . "/../DB/db_connect.php";
session_start();
if (!isset($_SESSION["user_id"])) {
    echo "<script>alert('You must be logged in to make a reservation.');
    window.location.href = 'login.php';</script>";
    exit;
} else {
    $user_id = $_SESSION["user_id"];
    $stmt = $pdo->prepare("SELECT name, mail, phone FROM customers WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = $user['name'] ?? '';
    $email = $user['mail'] ?? '';
    $phone = $user['phone'] ?? '';
}

include '../includes/Head.php';
include '../includes/Header.php'; ?>

<div id="information" class="spacer reserve-info">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <h3 class="text-center fw-bold mb-4">Make a Reservation</h3>
                        <form id="reservationForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" placeholder="Your Name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control" placeholder="Your Email" name="mail" value="<?= htmlspecialchars($email) ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" placeholder="Your Phone Number" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <select class="form-select" name="room_type" required>
                                        <option selected disabled>Type of Room</option>
                                        <option>Single Room</option>
                                        <option>Double Room</option>
                                        <option>Deluxe Room</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <select class="form-select" name="adult" required>
                                        <option selected disabled>No. of Adults</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <select class="form-select" name="children" required>
                                        <option selected disabled>No. of Children</option>
                                        <option>0</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Check-in Date</label>
                                    <input type="date" class="form-control" name="datein" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Check-out Date</label>
                                    <input type="date" class="form-control" name="dateout" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Days of Stay</label>
                                    <input type="number" class="form-control" name="days_of_stay" min="1" required disabled>
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" placeholder="Additional Message" rows="4" name="message"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">Submit Reservation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkInInput = document.querySelector('input[name="datein"]');
        const checkOutInput = document.querySelector('input[name="dateout"]');
        const daysOfStayInput = document.querySelector('input[name="days_of_stay"]');

        function calculateDaysOfStay() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(checkOutInput.value);

            if (checkInDate < today) {
                alert("Check-in date must be today or later!");
                checkInInput.value = "";
                daysOfStayInput.value = "";
                return;
            }
            if (checkOutDate <= checkInDate) {
                alert("Check-out date must be after check-in date!");
                checkOutInput.value = "";
                daysOfStayInput.value = "";
                return;
            }

            const timeDiff = checkOutDate - checkInDate;
            const daysOfStay = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            daysOfStayInput.value = daysOfStay;
        }

        checkInInput.addEventListener("change", calculateDaysOfStay);
        checkOutInput.addEventListener("change", calculateDaysOfStay);

        document.getElementById("reservationForm").addEventListener("submit", async function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            let jsonData = {};
            formData.forEach((value, key) => jsonData[key] = value);

            let response = await fetch("create_booking.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(jsonData)
            });

            let result = await response.json();
            if (result.success) {
                alert("Booking successful!");
                window.location.href = "book.php";
            } else {
                alert("Error: " + result.message);
            }
        });
    });
</script>