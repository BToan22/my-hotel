<?php include '../includes/Head.php';
include '../includes/Header.php'; ?>

<div class="container mt-5">
    <!-- title -->
    <div class="text-center py-4 text-dark" style="background: #d6d6d6; border-radius: 10px;">
        <h2 class="fw-bold">Our Services</h2>
    </div>

<!-- service -->
    <div class="row row-cols-2 row-cols-md-4 g-4 mt-4">
        <?php
        $services = [
            "9.png" => "Luxury Rooms",
            "10.png" => "Spa & Wellness",
            "11.png" => "Gym & Fitness",
            "12.png" => "Fine Dining",
            "13.png" => "Pool & Jacuzzi",
            "14.png" => "Event Hall",
            "15.png" => "Concierge Service",
            "16.png" => "Airport Pickup"
        ];

        foreach ($services as $image => $title) : ?>
            <div class="col">
                <div class="card shadow-lg border-0">
                    <img src="../images/photos/450x675/<?php echo $image; ?>" class="card-img-top rounded hover-zoom" alt="<?php echo $title; ?>">
                    <div class="card-body text-center">
                        <h5 class="fw-bold"><?php echo $title; ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    /* Hiệu ứng phóng to khi hover */
    .hover-zoom {
        transition: transform 0.3s ease-in-out;
    }

    .hover-zoom:hover {
        transform: scale(1.05);
    }
</style>

<?php include '../includes/Footer.php'; ?>