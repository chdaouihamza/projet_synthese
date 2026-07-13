<?php

require_once 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get city from session
$selectedCity = $_SESSION['user_city'] ?? 'oujda';
$cityNames = ['oujda' => 'Oujda', 'marrakech' => 'Marrakech', 'fes' => 'Fes'];
$cityName = $cityNames[$selectedCity] ?? 'Oujda';

// Fetch Tourist Places (LieuTouristique) from database
$stmt = $pdo->prepare("SELECT * FROM lieutouristique WHERE ville = ? OR ville IS NULL");
$stmt->execute([$cityName]);
$touristPlaces = $stmt->fetchAll();

// If no places in database, use default array
if(empty($touristPlaces)) {
    $touristPlaces = [
        ['id_lieu_touristique' => 1, 'nom' => 'Lalla Aicha Park', 'description' => 'Lush gardens, lake promenade, perfect picnic spot.', 'prix' => 5, 'ville' => 'Oujda', 'image' => 'park.jfif'],
        ['id_lieu_touristique' => 2, 'nom' => 'Oujda Museum', 'description' => 'Archaeologic & ethnographic richness.', 'prix' => 8, 'ville' => 'Oujda', 'image' => '434646.webp'],
        ['id_lieu_touristique' => 3, 'nom' => 'Medina of Oujda', 'description' => 'Souks, traditional crafts.', 'prix' => 0, 'ville' => 'Oujda', 'image' => 'lmdina.jfif'],
        ['id_lieu_touristique' => 4, 'nom' => 'Medina of Oujda', 'description' => 'Souks, traditional crafts.', 'prix' => 0, 'ville' => 'Oujda', 'image' => 'mosque.jfif'],
        ['id_lieu_touristique' => 5, 'nom' => 'Lalla Aicha Park', 'description' => 'Lush gardens, lake promenade, perfect picnic spot.', 'prix' => 5, 'ville' => 'Marrakech', 'image' => 'jemaa.jpg'],
        ['id_lieu_touristique' => 6, 'nom' => 'Lalla Aicha Park', 'description' => 'Lush gardens, lake promenade, perfect picnic spot.', 'prix' => 5, 'ville' => 'Marrakech', 'image' => 'majorelle.jpg'],
        ['id_lieu_touristique' => 7, 'nom' => 'Lalla Aicha Park', 'description' => 'Lush gardens, lake promenade, perfect picnic spot.', 'prix' => 5, 'ville' => 'Fes', 'image' => 'quaraouiyine.jpg'],
        ['id_lieu_touristique' => 8, 'nom' => 'Lalla Aicha Park', 'description' => 'Lush gardens, lake promenade, perfect picnic spot.', 'prix' => 5, 'ville' => 'Fes', 'image' => 'tannery.jpg'],
    ];
}

// Restaurants data
$restaurants = [
    ["name" => "Café Al Andalous", "cuisine" => "Moroccan", "price_per_person" => 15],
    ["name" => "Restaurant La Gazelle", "cuisine" => "International", "price_per_person" => 30],
    ["name" => "Chez Lalla Aicha", "cuisine" => "Traditional", "price_per_person" => 20],
];

// Hotels data
$hotels = [
    ["name" => "Hotel Ibis", "stars" => 3, "price_per_night" => 65, "rating" => 4.0],
    ["name" => "Park Inn by Radisson", "stars" => 4, "price_per_night" => 120, "rating" => 4.5],
    ["name" => "Riad Al Faris", "stars" => 4, "price_per_night" => 95, "rating" => 4.3],
];

// Transport options
$transportOptions = [
    ['name' => 'Taxi Service', 'type' => 'Taxi', 'price_per_day' => 30, 'desc' => 'Convenient taxi service', 'icon' => '🚕'],
    ['name' => 'Car Rental', 'type' => 'Location', 'price_per_day' => 45, 'desc' => 'Rent a car', 'icon' => '🚗'],
    ['name' => 'Scooter Rental', 'type' => 'Scooter', 'price_per_day' => 20, 'desc' => 'Scooter rental', 'icon' => '🛵'],
];

// Activities (separate from tourist places)
$activities = [
    ["name" => "Cooking Class", "description" => "Learn traditional Moroccan cooking", "price" => 35],
    ["name" => "Sunset Camel Ride", "description" => "Experience sunset on camelback", "price" => 25],
    ["name" => "Hiking Tour", "description" => "Guided hiking in Atlas mountains", "price" => 40],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elyora - Tourist Places</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .section-title {
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #2c6e2f;
            display: inline-block;
        }
        .price-free {
            color: #27ae60;
            font-weight: bold;
        }
        .price-paid {
            color: #e67e22;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <div class="header-brand">
        <img src="Elyora-logo.png" alt="Elyora Logo" class="logo" onerror="this.src='https://placehold.co/74x74?text=EL'">
        <h1 class="elyorah1">Elyora</h1>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="itinerary.php">Itinerary</a>
        <a href="places.php">Places</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="edit_profile.php">Edit Account</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <div class="card" style="text-align:center;">
        <h2>🏛️ Explore <?php echo htmlspecialchars($cityName); ?></h2>
        <p>Discover tourist attractions, restaurants, hotels, transport, and activities</p>
        <?php if(isset($_SESSION['user_name'])) echo "<p>👋 Welcome {$_SESSION['user_name']}!</p>"; ?>
    </div>
    
    <!-- ==================== TOURIST PLACES SECTION (LieuTouristique) ==================== -->
    <h2 class="section-title">📍 Tourist Attractions (Lieux Touristiques)</h2>
    <div class="places-grid">
    <?php
    foreach($touristPlaces as $place) {
        $priceDisplay = ($place['prix'] > 0) ? '$' . $place['prix'] : '<span class="price-free">Free</span>';
        $priceClass = ($place['prix'] > 0) ? 'price-paid' : 'price-free';
   $imgSrc = !empty($place['image'])
    ? 'image/' . $place['image']
    : 'image/placeholder.jpg';
        
        echo '<div class="card">';
        echo '<img src="' . htmlspecialchars($imgSrc) . '" alt="' . htmlspecialchars($place['nom']) . '" onerror="this.src=\'https://placehold.co/400x200?text=' . urlencode($place['nom']) . '\'">';
        echo '<h3>🏛️ ' . htmlspecialchars($place['nom']) . '</h3>';
        echo '<p>' . htmlspecialchars($place['description']) . '</p>';
        echo '<p class="' . $priceClass . '">🎟️ Entry: ' . $priceDisplay . '</p>';
        // Pass type=lieu instead of place
        echo '<a href="payment.php?type=lieu&item=' . urlencode($place['nom']) . '&price=' . $place['prix'] . '&city=' . urlencode($cityName) . '&id_lieu=' . $place['id_lieu_touristique'] . '" class="btn" style="margin-top:1rem;">📅 Book Visit</a>';
        echo '</div>';
    }
    ?>
    </div>
    
    <!-- ==================== ACTIVITIES SECTION (Separate) ==================== -->
    <h2 class="section-title">🎯 Activities & Experiences</h2>
    <div class="places-grid">
    <?php
    foreach($activities as $activity) {
        echo '<div class="card">';
        echo '<img src="activity-placeholder.jpg" alt="' . htmlspecialchars($activity['name']) . '" onerror="this.src=\'https://placehold.co/400x200?text=Activity\'" style="background:#e9e2d4;">';
        echo '<h3>🎯 ' . htmlspecialchars($activity['name']) . '</h3>';
        echo '<p>' . htmlspecialchars($activity['description']) . '</p>';
        echo '<p class="price-paid">💰 Price: $' . $activity['price'] . '</p>';
        echo '<a href="payment.php?type=activite&item=' . urlencode($activity['name']) . '&price=' . $activity['price'] . '&city=' . urlencode($cityName) . '" class="btn" style="margin-top:1rem;">🎯 Book Activity</a>';
        echo '</div>';
    }
    ?>
    </div>
    
    <!-- ==================== RESTAURANTS SECTION ==================== -->
    <h2 class="section-title">🍽️ Local Restaurants</h2>
    <div class="places-grid">
    <?php
    foreach($restaurants as $restaurant) {
        echo '<div class="card">';
        echo '<img src="restaurant-placeholder.jpg" alt="' . htmlspecialchars($restaurant['name']) . '" onerror="this.src=\'https://placehold.co/400x200?text=Restaurant\'" style="background:#e9e2d4;">';
        echo '<h3>🍴 ' . htmlspecialchars($restaurant['name']) . '</h3>';
        echo '<p><strong>Cuisine:</strong> ' . htmlspecialchars($restaurant['cuisine']) . '</p>';
        echo '<p><strong>Avg. cost per person:</strong> $' . $restaurant['price_per_person'] . '</p>';
        echo '<a href="payment.php?type=restaurant&item=' . urlencode($restaurant['name']) . '&price=' . $restaurant['price_per_person'] . '&city=' . urlencode($cityName) . '" class="btn" style="margin-top:1rem;">🍽️ Reserve Table</a>';
        echo '</div>';
    }
    ?>
    </div>
    
    <!-- ==================== HOTELS SECTION ==================== -->
    <h2 class="section-title">🏨 Hotels & Accommodations</h2>
    <div class="places-grid">
    <?php
    foreach($hotels as $hotel) {
        $stars = str_repeat('⭐', $hotel['stars']);
        echo '<div class="card">';
        echo '<img src="hotel-placeholder.jpg" alt="' . htmlspecialchars($hotel['name']) . '" onerror="this.src=\'https://placehold.co/400x200?text=Hotel\'" style="background:#e9e2d4;">';
        echo '<h3>🏨 ' . htmlspecialchars($hotel['name']) . '</h3>';
        echo '<p><strong>' . $stars . '</strong> (' . $hotel['stars'] . ' stars)</p>';
        echo '<p><strong>Rating:</strong> ' . $hotel['rating'] . ' / 5</p>';
        echo '<p><strong>Price per night:</strong> $' . $hotel['price_per_night'] . '</p>';
        echo '<a href="payment.php?type=hotel&item=' . urlencode($hotel['name']) . '&price=' . $hotel['price_per_night'] . '&city=' . urlencode($cityName) . '" class="btn" style="margin-top:1rem;">🏨 Book Now</a>';
        echo '</div>';
    }
    ?>
    </div>
    
    <!-- ==================== TRANSPORT SECTION ==================== -->
    <h2 class="section-title">🚗 Transport Options</h2>
    <div class="places-grid">
    <?php
    foreach($transportOptions as $transport) {
        echo '<div class="card">';
        echo '<img src="transport-placeholder.jpg" alt="' . htmlspecialchars($transport['name']) . '" onerror="this.src=\'https://placehold.co/400x200?text=Transport\'" style="background:#e9e2d4;">';
        echo '<h3>' . $transport['icon'] . ' ' . htmlspecialchars($transport['name']) . '</h3>';
        echo '<p>' . htmlspecialchars($transport['desc']) . '</p>';
        echo '<p><strong>Price per day:</strong> $' . $transport['price_per_day'] . '</p>';
        echo '<a href="payment.php?type=transport&item=' . urlencode($transport['name']) . '&price=' . $transport['price_per_day'] . '&city=' . urlencode($cityName) . '&transport=' . urlencode($transport['type']) . '" class="btn" style="margin-top:1rem;">' . $transport['icon'] . ' Book Transport</a>';
        echo '</div>';
    }
    ?>
    </div>
    
    <div style="text-align:center; margin:2rem 0;">
        <a href="itinerary.php" class="btn">🔙 Back to My Itinerary</a>
        <a href="my_bookings.php" class="btn" style="background-color: #8b6946;">📋 View My Bookings</a>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>