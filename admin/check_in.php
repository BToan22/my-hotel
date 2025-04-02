<?php
require __DIR__ . "/../DB/db_connect.php";

// Process category_id filter
$category_id = $_GET['category_id'] ?? 'all';

try {
    // Get room categories
    $stmt = $pdo->query("SELECT * FROM room_categoricals ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get room list based on filter
    $where = ($category_id !== 'all') ? " WHERE category_id = :category_id AND status = 0" : " WHERE status = 0";
    $sql = "SELECT * FROM rooms" . $where . " ORDER BY category_id ASC";
    $stmt = $pdo->prepare($sql);

    if ($category_id !== 'all') {
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query error: " . $e->getMessage());
}
?><div class="container mt-4">
    <h2 class="text-center">Check In</h2>

    <!-- <div class="card mb-3">
        <div class="card-body">
            <form id="filterForm">

                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Room Type</label>
                        <select class="form-select" name="category_id">
                            <option value="all" <?= ($category_id === 'all') ? 'selected' : '' ?>>All</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div> -->

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Room Type</th>
                <th>Room Number</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rooms) > 0): ?>
                <?php $i = 1;
                foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($categories[array_search($room['category_id'], array_column($categories, 'id'))]['name']) ?></td>
                        <td><?= htmlspecialchars($room['room']) ?></td>
                        <td>
                            <span class="badge bg-success">Available</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary checkin-btn" data-room-id="<?= $room['id'] ?>" data-bs-toggle="modal" data-bs-target="#checkinModal">
                                Check-in
                            </button>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No available rooms</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<!-- Modal check in -->
<div class="modal fade" id="checkinModal" tabindex="-1" aria-labelledby="checkinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkinModalLabel">Check-in Guest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="checkinForm">
                    <input type="hidden" name="room_id" id="modalRoomId">
                    <div class="mb-3">
                        <label for="ref_no" class="form-label">Reference No</label>
                        <input type="text" class="form-control" name="ref_no" id="ref_no" required>
                    </div>
                    <button type="submit" class="btn btn-success">Confirm Check-in</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".checkin-btn").forEach(button => {
            button.addEventListener("click", function() {
                let roomId = this.getAttribute("data-room-id");
                document.getElementById("modalRoomId").value = roomId;
            });
        });

        document.getElementById("checkinForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let roomId = document.getElementById("modalRoomId").value;
            let refNo = document.getElementById("ref_no").value;

            fetch("process_check_in.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        room_id: roomId,
                        ref_no: refNo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        alert("Check-in successful!");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        });

    });
</script>