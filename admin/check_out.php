<?php
require __DIR__ . "/../DB/db_connect.php";

// Get category
$stmt = $pdo->query("SELECT * FROM room_categoricals");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$cat_arr = [];
foreach ($categories as $cat) {
    $cat_arr[$cat['id']] = $cat['name'];
}

// Get list room
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
$room_arr = [];
foreach ($rooms as $room) {
    $room_arr[$room['id']] = $room['room'];
}

// Get list booking with status 1 and 2
$stmt = $pdo->query("SELECT * FROM booking WHERE status IN (1, 2) ORDER BY id ASC");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2 class="text-center">Check Out</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Room</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php $i = 1;
                        foreach ($bookings as $row): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($cat_arr[$row['room_type_id']] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($room_arr[$row['assigned_room_id']] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>
                                    <?php if ($row['status'] == 1): ?>
                                        <span class="badge bg-primary">Checked-In</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Checked-Out</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 1): ?>
                                        <button class="btn btn-sm btn-secondary check_out" data-id="<?= $row['id'] ?>">Check Out</button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-info view" data-id="<?= $row['id'] ?>">View</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No bookings available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Check Out -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="checkoutForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="bookingId">
                    <p><strong>Ref No:</strong> <span id="refNo"></span></p>
                    <p><strong>Name:</strong> <span id="customerName"></span></p>
                    <p><strong>Email:</strong> <span id="customerEmail"></span></p>
                    <p><strong>Phone:</strong> <span id="customerPhone"></span></p>
                    <p><strong>Room Type:</strong> <span id="roomCategory"></span></p>
                    <p><strong>Check-in Date:</strong> <span id="checkInDate"></span></p>
                    <p><strong>Check-out Date:</strong> <span id="checkOutDate"></span></p>
                    <p><strong>Days of Stay:</strong> <span id="daysOfStay"></span></p>
                    <p><strong>Message:</strong> <span id="customerMessage"></span></p>
                    <p><strong>Price:</strong> <span id="roomPrice"></span></p>
                    <p><strong>Total Price:</strong> <span id="totalPrice"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirm & Check Out</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal View bookingModal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="modalBookingId"></span></p>
                <p><strong>Name:</strong> <span id="modalBookingName"></span></p>
                <p><strong>Room Type:</strong> <span id="modalBookingRoomType"></span></p>
                <p><strong>Date In:</strong> <span id="modalBookingDateIn"></span></p>
                <p><strong>Date Out:</strong> <span id="modalBookingDateOut"></span></p>
                <p><strong>Days of Stay:</strong> <span id="modalBookingDaysStay"></span></p>
                <p><strong>Total Price:</strong> <span id="modalBookingTotalPrice"></span></p>
                <p><strong>Email:</strong> <span id="modalBookingMail"></span></p>
                <p><strong>Phone:</strong> <span id="modalBookingPhone"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".check_out").forEach(button => {
            button.addEventListener("click", function() {
                let bookingId = this.getAttribute("data-id");
                document.getElementById("bookingId").value = bookingId;

                fetch("process_check_out.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id: bookingId,
                            action: "get_booking_details_checkout"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {

                            document.getElementById("customerName").textContent = data.booking.name;
                            document.getElementById("roomCategory").textContent = data.booking.room_type;
                            document.getElementById("checkInDate").textContent = data.booking.datein;
                            document.getElementById("checkOutDate").textContent = data.booking.dateout;
                            document.getElementById("daysOfStay").textContent = data.booking.days_of_stay;
                            document.getElementById("roomPrice").textContent = data.booking.room_price;
                            document.getElementById("totalPrice").textContent = data.booking.total_price;
                            document.getElementById("customerEmail").textContent = data.booking.mail;
                            document.getElementById("customerPhone").textContent = data.booking.phone;
                            document.getElementById("refNo").textContent = data.booking.ref_no;

                            new bootstrap.Modal(document.getElementById("checkoutModal")).show();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    });

    document.getElementById("checkoutForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let bookingId = document.getElementById("bookingId").value;
        let daysOfStay = document.getElementById("daysOfStay").textContent;
        let totalPrice = document.getElementById("totalPrice").textContent;

        fetch("process_check_out.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: bookingId,
                    action: "check_out",
                    days_of_stay: daysOfStay,
                    total_price: totalPrice
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Check-out successful!");
                    location.reload();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
    });


    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".view").forEach(button => {
            button.addEventListener("click", function() {
                let bookingId = this.getAttribute("data-id");

                fetch(`process_check_out.php?id=${bookingId}`, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let booking = data.booking;

                            document.getElementById("modalBookingId").textContent = booking.id;
                            document.getElementById("modalBookingName").textContent = booking.name;
                            document.getElementById("modalBookingRoomType").textContent = booking.room_type;
                            document.getElementById("modalBookingDateIn").textContent = booking.datein;
                            document.getElementById("modalBookingDateOut").textContent = booking.dateout;
                            document.getElementById("modalBookingDaysStay").textContent = booking.days_of_stay;
                            document.getElementById("modalBookingTotalPrice").textContent = booking.total_price;
                            document.getElementById("modalBookingMail").textContent = booking.mail;
                            document.getElementById("modalBookingPhone").textContent = booking.phone;

                            var myModal = new bootstrap.Modal(document.getElementById('bookingModal'));
                            myModal.show();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    });
</script>