<?php include '../includes/Head.php';
include '../includes/Header.php'; ?>
<div class="container mt-5">
    <!-- title -->
    <div class="text-center py-4 text-dark" style="background: #d6d6d6; border-radius: 10px;">
        <h2 class="fw-bold">Foods and Drinks</h2>
    </div>


    <!-- image menu  -->
    <div class="row row-cols-2 row-cols-md-4 g-4 mt-4">
        <?php
        $images = [
            "1.png" => "Sandwich",
            "2.png" => "Steak",
            "3.png" => "Cherries",
            "4.png" => "Pasta and Wine",
            "5.png" => "Popsicle",
            "6.png" => "Salmon",
            "7.png" => "Sandwich",
            "8.png" => "Croissant"
        ];

        foreach ($images as $image => $alt) : ?>
            <div class="col">
                <div class="card shadow-sm border-0">
                    <img src="../images/photos/450x675/<?php echo $image; ?>" class="card-img-top rounded" alt="<?php echo $alt; ?>">
                    <div class="card-body text-center">
                        <p class="fw-bold"><?php echo $alt; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/Footer.php'; ?>