<!DOCTYPE html>


<!-- Banner -->
<div class="banner">
    <div id="Banner" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/photos/banner1.png" class="d-block w-100" alt="slide">
            </div>
            <div class="carousel-item">
                <img src="images/photos/banner2.png" class="d-block w-100" alt="slide">
            </div>
            <div class="carousel-item">
                <img src="images/photos/banner3.png" class="d-block w-100" alt="slide">
            </div>
            <div class="carousel-item">
                <img src="images/photos/banner4.png" class="d-block w-100" alt="slide">
            </div>
        </div>
        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#Banner" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#Banner" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<!-- Search Form -->
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h2 class="text-center"><i class="fa fa-bed"></i> HBOOK</h2>
        <form action="/action_page.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label><i class="fa fa-calendar-o"></i> Check In</label>
                    <input type="date" class="form-control" name="CheckIn" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label><i class="fa fa-calendar-o"></i> Check Out</label>
                    <input type="date" class="form-control" name="CheckOut" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label><i class="fa fa-male"></i> Adults</label>
                    <input type="number" class="form-control" name="Adults" min="1" max="6" value="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label><i class="fa fa-child"></i> Kids</label>
                    <input type="number" class="form-control" name="Kids" min="0" max="6" value="0">
                </div>
                <div class="col-12">
                    <button class="btn btn-dark w-100" type="submit"><i class="fa fa-search"></i> Search availability</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Room Section -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Rooms</h3>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="images/photos/room_single.jpg" class="card-img-top" alt="Single Room">
                <div class="card-body text-center">
                    <h5 class="card-title">Single Room</h5>
                    <p class="card-text"><strong>From $99</strong></p>
                    <p class="text-muted">
                        <i class="fa fa-bath"></i>
                        <i class="fa fa-phone"></i>
                        <i class="fa fa-wifi"></i>
                        <i class="fa fa-tv"></i>
                        <i class="fa fa-cutlery"></i>

                    </p>
                    <a href="book.php" class="btn btn-dark w-100">Choose Room</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="images/photos/room_double.jpg" class="card-img-top" alt="Double Room">
                <div class="card-body text-center">
                    <h5 class="card-title">Double Room</h5>
                    <p class="card-text"><strong>From $149</strong></p>
                    <p class="text-muted">
                        <i class="fa fa-bath"></i>
                        <i class="fa fa-phone"></i>
                        <i class="fa fa-wifi"></i>
                        <i class="fa fa-tv"></i>
                        <i class="fa fa-cutlery"></i>

                    </p>
                    <a href="book.php" class="btn btn-dark w-100">Choose Room</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="images/photos/room_deluxe.jpg" class="card-img-top" alt="Deluxe Room">
                <div class="card-body text-center">
                    <h5 class="card-title">Deluxe Room</h5>
                    <p class="card-text"><strong>From $199</strong></p>
                    <p class="text-muted">
                        <i class="fa fa-bath"></i>
                        <i class="fa fa-phone"></i>
                        <i class="fa fa-wifi"></i>
                        <i class="fa fa-tv"></i>
                        <i class="fa fa-cutlery"></i>

                    </p>
                    <a href="book.php" class="btn btn-dark w-100">Choose Room</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="container mt-5">
    <h3 class="text-center mb-4 text-uppercase fw-bold">Our Services</h3>
    <div class="row">
        <!-- Room Services -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow">
                <div id="RoomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="images/photos/12.png" class="d-block w-100 img-fluid rounded-top" alt="Rooms">
                        </div>
                        <div class="carousel-item">
                            <img src="images/photos/13.png" class="d-block w-100 img-fluid rounded-top" alt="Rooms">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#RoomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#RoomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Rooms</h5>
                    <p class="card-text text-muted">Comfortable and stylish rooms for a relaxing stay.</p>
                    <a href="pages/room.php" class="btn btn-dark w-100">Explore Rooms</a>
                </div>
            </div>
        </div>

        <!-- Service Section -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow">
                <div id="ServiceCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="images/photos/7.png" class="d-block w-100 img-fluid rounded-top" alt="Services">
                        </div>
                        <div class="carousel-item">
                            <img src="images/photos/8.png" class="d-block w-100 img-fluid rounded-top" alt="Services">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#ServiceCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#ServiceCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Services</h5>
                    <p class="card-text text-muted">Exclusive services to enhance your stay experience.</p>
                    <a href="pages/service.php" class="btn btn-dark w-100">View Services</a>
                </div>
            </div>
        </div>

        <!-- Food Section -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow">
                <div id="FoodCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="images/photos/1.png" class="d-block w-100 img-fluid rounded-top" alt="Food">
                        </div>
                        <div class="carousel-item">
                            <img src="images/photos/2.png" class="d-block w-100 img-fluid rounded-top" alt="Food">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#FoodCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#FoodCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Food & Drinks</h5>
                    <p class="card-text text-muted">Delicious cuisine to satisfy your taste buds.</p>
                    <a href="pages/food.php" class="btn btn-dark w-100">See Menu</a>
                </div>
            </div>
        </div>
    </div>
</div>