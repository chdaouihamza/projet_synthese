<?php

require_once 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data from session
$name = $_SESSION['user_name'];
$email = $_SESSION['user_email'] ?? '';
$telephone = $_SESSION['user_telephone'] ?? '';
$budgetAmount = $_SESSION['user_budget_amount'] ?? 200;
$cityKey = $_SESSION['user_city'] ?? 'oujda';
$interests = $_SESSION['user_interests'] ?? [];
$duration = $_SESSION['user_duration'] ?? 3;
$transport_pref = $_SESSION['transport_pref'] ?? 'Marche';

// City data with places, restaurants, hotels, and activities
function getCityData($cityKey) {
    $cities = [
        "oujda" => [
            "name" => "Oujda",
            "map_embed" => "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d25694.40272837714!2d-1.923338923349211!3d34.68729259297568!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd7879e0b6543f37%3A0x19201ad9e39c593d!2sOujda%2C%20Morocco!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus",
            "places" => [
                ["name" => "Lalla Aicha Park", "image" => "park.jfif", "desc" => "Lush gardens, lake promenade, perfect picnic spot."],
                ["name" => "Oujda Museum", "image" => "434646.webp", "desc" => "Archaeologic & ethnographic richness of eastern Morocco."],
                ["name" => "Medina of Oujda", "image" => "lmdina.jfif", "desc" => "Souks, traditional crafts, authentic atmosphere."],
                ["name" => "Great Mosque of Oujda", "image" => "mosque.jfif", "desc" => "13th-century Islamic architecture."],
                ["name" => "Local Food Market", "image" => "karan.jpg", "desc" => "Fresh spices, olives, local bread & delicacies."],
                ["name" => "Artisan Workshops", "image" => "artist.jpg", "desc" => "Pottery, carpets, metalwork workshops."]
            ],
            "restaurants" => ["Café Al Andalous", "Restaurant La Gazelle", "Chez Lalla Aicha", "El Bahia Grill"],
            "hotels" => ["Hotel Ibis Oujda", "Park Inn by Radisson", "Riad Al Faris", "Hotel Atlas"],
            "activities" => ["Walking tour of old medina", "Sunset at Lalla Aicha Park", "Moroccan cooking workshop", "Horse carriage ride"]
        ],
        "marrakech" => [
            "name" => "Marrakech",
            "map_embed" => "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d106928.860690164!2d-8.030837956147878!3d31.629472318301737!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xdafee8d96179e51%3A0x5950b6534f87adb8!2sMarrakech%2C%20Morocco!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus",
            "places" => [
                ["name" => "Jemaa el-Fnaa", "image" => "jemaa.jpg", "desc" => "Vibrant square with storytellers, food stalls."],
                ["name" => "Majorelle Garden", "image" => "majorelle.jpg", "desc" => "Botanical garden & Berber museum."],
                ["name" => "Koutoubia Mosque", "image" => "koutoubia.jpg", "desc" => "Iconic 12th-century minaret."],
                ["name" => "Bahia Palace", "image" => "bahia.jpg", "desc" => "Ornate rooms and courtyards."],
                ["name" => "Souk Semmarine", "image" => "souk.jpg", "desc" => "Traditional market for crafts and spices."]
            ],
            "restaurants" => ["Nomad", "Le Jardin", "Café Des Épices", "Al Fassia"],
            "hotels" => ["Royal Mansour", "La Mamounia", "Riad Yasmine", "Kenzi Menara Palace"],
            "activities" => ["Hot air balloon", "Atlas mountains day trip", "Henna painting", "Agafay desert sunset"]
        ],
        "fes" => [
            "name" => "Fes",
            "map_embed" => "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3313.245708055463!2d-4.977588584677488!3d34.03363578061377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd9f8b6b1b2b6f0f%3A0x9d2b7e7c3d4e5f6a!2sFes%2C%20Morocco!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus",
            "places" => [
                ["name" => "Al Quaraouiyine Mosque", "image" => "quaraouiyine.jpg", "desc" => "Ancient university & mosque."],
                ["name" => "Chouara Tannery", "image" => "tannery.jpg", "desc" => "Famous leather dye pits."],
                ["name" => "Borj Nord", "image" => "borj.jpg", "desc" => "Weapons museum & viewpoint."],
                ["name" => "Medina of Fes", "image" => "fesmedina.jpg", "desc" => "UNESCO world heritage maze."]
            ],
            "restaurants" => ["The Ruined Garden", "Café Clock", "Dar Hatim", "Palais Faraj"],
            "hotels" => ["Palais Faraj Suites & Spa", "Riad Fes", "Sahrai Hotel", "Dar Bensouda"],
            "activities" => ["Mosaic workshop", "Medina guided tour", "Pottery class", "Traditional music night"]
        ]
    ];
    return $cities[$cityKey] ?? $cities["oujda"];
}

$cityData = getCityData($cityKey);
$cityName = $cityData['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elyora - Your Itinerary</title>
    <link rel="stylesheet" href="style.css">
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
    <!-- User summary card -->
    <div class="card">
        <h2>🗺️ Your Personalized Itinerary – <?php echo htmlspecialchars($cityName); ?></h2>
        <p><strong>Traveler:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($email); ?> | <strong>📞 Tel:</strong> <?php echo htmlspecialchars($telephone); ?></p>
        <p><strong>💰 Budget:</strong> $<?php echo number_format($budgetAmount); ?> USD | <strong>📅 Duration:</strong> <?php echo $duration; ?> days</p>
        <p><strong>🧩 Interests:</strong> <?php echo implode(', ', $interests); ?></p>
        <p><strong>🚗 Transport:</strong> <?php echo htmlspecialchars($transport_pref); ?></p>
        <hr>
        <p>✨ Based on your city & interests, we've crafted daily experiences blending culture, cuisine, and local gems.</p>
    </div>

    <?php
    // Generate daily itinerary
    $activitiesPool = $cityData['activities'];
    $restaurantsList = $cityData['restaurants'];
    $hotelsList = $cityData['hotels'];

    // Add interest-based activities
    $interestExtras = [];
    foreach($interests as $int) {
        if($int == 'History') $interestExtras[] = "🏛️ Guided tour of historical landmarks (Kasbah/Museums)";
        if($int == 'Food') $interestExtras[] = "🍜 Food tasting tour & traditional cooking demo";
        if($int == 'Nature') $interestExtras[] = "🌳 Botanical garden walk & nature escape";
        if($int == 'Art') $interestExtras[] = "🎨 Souk art hunt & local artisan workshop";
    }
    
    // Combine and shuffle activities
    $finalActivities = array_merge($activitiesPool, $interestExtras);
    shuffle($finalActivities);

    // Generate itinerary for each day (max 10 days displayed)
    $displayDays = min($duration, 10);
    for($day = 1; $day <= $displayDays; $day++) {
        // Pick 2-3 random activities for the day
        $dailyActs = [];
        for($i=0; $i<2; $i++) {
            $dailyActs[] = $finalActivities[($day + $i) % count($finalActivities)];
        }
        // Pick restaurant and hotel based on day index
        $restPick = $restaurantsList[($day % count($restaurantsList))];
        $hotelPick = $hotelsList[($day % count($hotelsList))];
        
        echo '<div class="card">';
        echo "<h3>📅 Day {$day} — <span class='city-badge'>{$cityName} Rhythm</span></h3>";
        echo "<ul style='margin-left:1.4rem;'>";
        foreach($dailyActs as $act) {
            echo "<li>✨ {$act}</li>";
        }
        echo "<li>🍽️ <strong>Lunch/Dinner:</strong> {$restPick} (local favorite)</li>";
        echo "<li>🏨 <strong>Overnight stay suggestion:</strong> {$hotelPick}</li>";
        echo "<li>🚗 <strong>Transport today:</strong> {$transport_pref}</li>";
        echo "</ul>";
        
        // Show budget-based tips
        echo '<p><small>💡 Tip: ' . ($budgetAmount > 500 ? 'Premium experiences available' : ($budgetAmount > 200 ? 'Mid-range options recommended' : 'Great value spots suggested')) . '</small></p>';
        echo '</div>';
    }
    
    // Message for extended stays
    if($duration > 10) {
        echo '<div class="card"><h3>🌟 Extended Stay Magic</h3><p>For days 11–' . $duration . ', enjoy free exploration, repeat your favorite activities or take day trips around ' . $cityName . '.</p></div>';
    }
    ?>
    
    <!-- Action buttons -->
    <div style="text-align: center; margin: 2rem 0;">
        <a href="places.php?city=<?php echo urlencode($cityKey); ?>" class="btn">🏞️ Explore Places & Attractions</a>
        <a href="book_full_experience.php" class="btn" style="background-color:#8b6946;" onclick="return confirmBooking()">📖 Book Full Experience</a>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>