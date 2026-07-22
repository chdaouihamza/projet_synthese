<?php


require_once 'config.php'; // Include database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Elyora | Smart Travel Planner</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<header>
    <div class="header-brand">
        <!-- Logo with fallback if image doesn't load -->
        <img src="Elyora-logo.png" alt="Elyora Logo" class="logo" onerror="this.src='https://placehold.co/74x74?text=EL'">
        <h1 class="elyorah1">Elyora</h1>
    </div>
    <!-- Navigation menu - changes based on login status -->
    <nav>
        <a href="index.php">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Links for logged-in users -->
            <a href="profile.php">Profile</a>
            <a href="itinerary.php">Itinerary</a>
            <a href="places.php">Places</a>
            <a href="my_bookings.php">My Bookings</a>
            <a href="edit_profile.php">Edit Account</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <!-- Links for guests -->
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container">
    <!-- Dynamic greeting will be inserted here by JavaScript -->
    <div id="dynamicGreeting"></div>
    
    <!-- Hero section -->
    <div class="card">
        <h2>✨ Discover a New Way to Travel with Elyora</h2>
        <p>Smart tourism platform that builds your journey around <strong>your budget, chosen city, and interests</strong> — from hidden alleys in Fes to vibrant Marrakech souks.</p>
        <?php if(isset($_SESSION['user_name'])): ?>
            <!-- Personalized welcome message for logged-in users -->
            <p style="margin-top: 1rem; color: #2c6e2f;">👋 Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        <?php endif; ?>
    </div>
    
    <!-- Features grid -->
    <div class="places-grid">
        <div class="card">
            <h3>🎯 Tailored to You</h3>
            <p>Set your budget amount, pick a city, and we'll generate places, restaurants, hotels and activities perfectly aligned to you.</p>
        </div>
        <div class="card">
            <h3>🏛️ Local Experiences</h3>
            <p>Promoting authentic culture, supporting local artisans, and uncovering real Moroccan hospitality.</p>
        </div>
        <div class="card">
            <h3>📅 Smart Planning</h3>
            <p>Automatic day-by-day itinerary generation with restaurants & stays matching your preferences.</p>
        </div>
    </div>
    
    <!-- Call to action button -->
    <div style="text-align:center; margin:2rem 0;">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="btn">🎒 Update Your Travel Plan →</a>
        <?php else: ?>
            <a href="login.php" class="btn">🎒 Start Your Journey →</a>
        <?php endif; ?>
    </div>
    
    <!-- Google Maps iframe showing Morocco -->
    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d51288.78481521361!2d-1.923338923349211!3d34.68729259297568!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd7879e0b6543f37%3A0x19201ad9e39c593d!2sOujda%2C%20Morocco!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
        <p style="text-align:center;">📍 Discover Oujda, Marrakech, Fes – The heart of Morocco</p>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>