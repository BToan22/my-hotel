<?php
require __DIR__ . "/../DB/db_connect.php";

$cat_stmt = $pdo->query("SELECT * FROM room_categoricals ORDER BY name ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

$where = '';
$params = [];
if (isset($_GET['category_id']) && $_GET['category_id'] !== 'all') {
    $where = "WHERE category_id = :category_id";
    $params[':category_id'] = $_GET['category_id'];
}

$room_stmt = $pdo->prepare("SELECT * FROM rooms $where ORDER BY category_id ASC");
$room_stmt->execute($params);
$rooms = $room_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h3 class="mb-3">Manage Rooms</h3>

    <!-- Create Room Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createRoomModal">Create Room</button>

    <!-- filter -->
    <form class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Category</label>
            <select class="form-select" name="category_id" onchange="window.location.href='index.php?page=rooms&category_id=' + this.value;">
                <option value="all">All</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <!-- List room -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Room List</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Room</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($rooms): ?>
                        <?php $i = 1;
                        foreach ($rooms as $room): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($categories[array_search($room['category_id'], array_column($categories, 'id'))]['name']) ?></td>
                                <td><?= htmlspecialchars($room['room']) ?></td>
                                <td>
                                    <span class="badge <?= $room['status'] == 0 ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $room['status'] == 0 ? 'Available' : 'Unavailable' ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-room" data-id="<?= $room['id'] ?>" data-category="<?= $room['category_id'] ?>" data-room="<?= htmlspecialchars($room['room']) ?>" data-status="<?= $room['status'] ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-room" data-id="<?= $room['id'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No rooms found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Room Modal -->
<div class="modal fade" id="createRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="roomForm">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="roomId">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category_id" id="roomCategory">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label class="form-label mt-2">Room Name</label>
                    <input type="text" class="form-control" name="room" id="roomName" required>
                    <label class="form-label mt-2">Status</label>
                    <select class="form-select" name="status" id="roomStatus">
                        <option value="0">Available</option>
                        <option value="1">Unavailable</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.edit-room').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('roomId').value = this.dataset.id;
            document.getElementById('roomCategory').value = this.dataset.category;
            document.getElementById('roomName').value = this.dataset.room;
            document.getElementById('roomStatus').value = this.dataset.status;
            new bootstrap.Modal(document.getElementById('createRoomModal')).show();
        });
    });

    document.querySelectorAll('.delete-room').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm("Are you sure you want to delete this room?")) {
                fetch('manage_room.php', {
                    method: 'DELETE',
                    body: JSON.stringify({
                        id: parseInt(this.dataset.id)
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json()).then(data => {
                    alert(data.message);
                    location.reload();
                });
            }
        });
    });

    document.getElementById('roomForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = {
            id: document.getElementById('roomId').value ? parseInt(document.getElementById('roomId').value) : 0,
            category_id: parseInt(document.getElementById('roomCategory').value),
            room: document.getElementById('roomName').value.trim(),
            status: parseInt(document.getElementById('roomStatus').value)
        };

        fetch('manage_room.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        }).then(response => response.json()).then(data => {
            alert(data.message);
            location.reload();
        });
    });
</script>