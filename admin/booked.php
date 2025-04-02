<?php
require __DIR__ . "/../DB/db_connect.php";

// category
$cat_query = $pdo->query("SELECT * FROM room_categoricals");
$categories = $cat_query->fetchAll(PDO::FETCH_ASSOC);
$cat_arr = [];
foreach ($categories as $cat) {
    $cat_arr[$cat['id']] = $cat['name'];
}

// Room booked
$bookings_query = $pdo->query("SELECT * FROM booking  ORDER BY status ASC, id ASC");
$bookings = $bookings_query->fetchAll(PDO::FETCH_ASSOC);
// var_dump($bookings);
// die();
?>
<div class="container my-4">
    <h2 class="mb-4">Booked Rooms</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Ref no</th>
                        <th>Category</th>
                        <th>Name customer</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php $i = 1;
                    foreach ($bookings as $row): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['ref_no']) ?></td>
                            <td><?= htmlspecialchars($row['room_type']) ?></td>
                            <!-- <td><?= htmlspecialchars($cat_arr[$row['room_type']] ?? 'Unknown') ?></td> -->
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>
                                <?php if ($row['status'] == 0): ?>
                                    <span class="badge bg-success">Booked</span>
                                <?php elseif ($row['status'] == 1): ?>
                                    <span class="badge bg-primary">Checked-in</span>
                                <?php elseif ($row['status'] == 2): ?>
                                    <span class="badge bg-secondary">Checked-out</span>
                                <?php elseif ($row['status'] == 3): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif ($row['status'] == -1): ?>
                                    <span class="badge bg-danger">Canceled</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 3): ?>
                                    <button class="btn btn-sm btn-success confirm_booking" data-id="<?= $row['id'] ?>">Confirm</button>
                                <?php endif; ?>
                                <?php if ($row['status'] != -1): ?>
                                    <button class="btn btn-sm btn-danger cancel_booking" data-id="<?= $row['id'] ?>">Cancel</button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-info view_detail"
                                    data-id="<?= $row['id'] ?>"
                                    data-ref="<?= htmlspecialchars($row['ref_no']) ?>"
                                    data-user="<?= htmlspecialchars($row['user_id']) ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-mail="<?= htmlspecialchars($row['mail']) ?>"
                                    data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                    data-room-type="<?= htmlspecialchars($row['room_type']) ?>"
                                    data-assigned_room_id="<?= htmlspecialchars($row['assigned_room_id']) ?>"
                                    data-adult="<?= htmlspecialchars($row['adult']) ?>"
                                    data-children="<?= htmlspecialchars($row['children']) ?>"
                                    data-datein="<?= htmlspecialchars($row['datein']) ?>"
                                    data-dateout="<?= htmlspecialchars($row['dateout']) ?>"
                                    data-days="<?= htmlspecialchars($row['days_of_stay']) ?>"
                                    data-status="<?= $row['status'] ?>"
                                    data-message="<?= htmlspecialchars($row['message']) ?>"
                                    data-price="<?= htmlspecialchars($row['price']) ?>">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- detail booking  -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailModalLabel">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Ref No</th>
                        <td id="detailRefNo"></td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td id="detailUser"></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td id="detailName"></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td id="detailMail"></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td id="detailPhone"></td>
                    </tr>
                    <tr>
                        <th>Room Type</th>
                        <td id="detailRoomType"></td>
                    </tr>
                    <tr>
                        <th>Room</th>
                        <td id="detailRoom"></td>
                    </tr>
                    <tr>
                        <th>Adults</th>
                        <td id="detailAdult"></td>
                    </tr>
                    <tr>
                        <th>Children</th>
                        <td id="detailChildren"></td>
                    </tr>
                    <tr>
                        <th>Check-in</th>
                        <td id="detailDateIn"></td>
                    </tr>
                    <tr>
                        <th>Check-out</th>
                        <td id="detailDateOut"></td>
                    </tr>
                    <tr>
                        <th>Days of Stay</th>
                        <td id="detailDays"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="detailStatus"></td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td id="detailMessage"></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td id="detailPrice"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.view_detail').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('detailRefNo').textContent = this.getAttribute('data-ref');
            document.getElementById('detailUser').textContent = this.getAttribute('data-user');
            document.getElementById('detailName').textContent = this.getAttribute('data-name');
            document.getElementById('detailMail').textContent = this.getAttribute('data-mail');
            document.getElementById('detailPhone').textContent = this.getAttribute('data-phone');
            document.getElementById('detailRoomType').textContent = this.getAttribute('data-room-type');
            document.getElementById('detailRoom').textContent = this.getAttribute('data-assigned_room_id');
            document.getElementById('detailAdult').textContent = this.getAttribute('data-adult');
            document.getElementById('detailChildren').textContent = this.getAttribute('data-children');
            document.getElementById('detailDateIn').textContent = this.getAttribute('data-datein');
            document.getElementById('detailDateOut').textContent = this.getAttribute('data-dateout');
            document.getElementById('detailDays').textContent = this.getAttribute('data-days');
            document.getElementById('detailMessage').textContent = this.getAttribute('data-message');
            document.getElementById('detailPrice').textContent = this.getAttribute('data-price');

            let statusText = "";
            switch (this.getAttribute('data-status')) {
                case "0":
                    statusText = "Booked";
                    break;
                case "1":
                    statusText = "Check-in";
                    break;
                case "2":
                    statusText = "Check-out";
                    break;
                case "3":
                    statusText = "Pending";
                    break;
                default:
                    statusText = "Unknown";
            }
            document.getElementById('detailStatus').textContent = statusText;

            var myModal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
            myModal.show();
        });
    });
    document.querySelectorAll('.confirm_booking').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-id');
            fetch('manage_booking.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: bookingId
                    })
                }).then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                }).catch(error => console.error('Error:', error));
        });
    });

    document.querySelectorAll('.cancel_booking').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-id');
            if (confirm("Do you want to cancel this booking?")) {
                fetch('manage_booking.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: bookingId
                        })
                    }).then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    }).catch(error => console.error('Error:', error));
            }
        });
    });
</script>