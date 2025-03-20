<?php include '../includes/Head.php';
include '../includes/Header.php'; ?>

<div id="information" class="spacer reserve-info">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <h3 class="text-center fw-bold mb-4">Make a Reservation</h3>
                        <form role="form" method="post" action="create_booking.php">
                            <!-- Name & Email -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" placeholder="Your Name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control" placeholder="Your Email" name="mail" required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <input type="tel" class="form-control" placeholder="Your Phone Number" name="phone" required>
                            </div>

                            <!-- Room Type, Adults & Children -->
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

                            <!-- Check-in & Check-out Date -->
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
                                    <input type="number" class="form-control" name="days_of_stay" min="1" required>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="mb-3">
                                <textarea class="form-control" placeholder="Additional Message" rows="4" name="message"></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button class="btn btn-dark w-100 py-2 fw-bold">Submit Reservation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/Footer.php'; ?>