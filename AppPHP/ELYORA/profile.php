<?php

require_once 'config.php';

// Check if user is logged in, if not redirect to login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Retrieve existing travel preferences from database
$stmt = $pdo->prepare("SELECT * FROM sejour WHERE id_touriste = ?");
$stmt->execute([$user_id]);
$sejour = $stmt->fetch();

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $budgetAmount = $_POST['budget_amount'] ?? 0;
    $city = $_POST['destination_city'] ?? 'oujda';
    $interests = $_POST['interests'] ?? [];
    $duration = $_POST['trip_duration'] ?? 3;
    $transport_pref = $_POST['transport_pref'] ?? 'Marche';
    
    // Validation
    if(empty($budgetAmount) || $budgetAmount < 50) {
        $error = "Budget must be at least 50 USD";
    } elseif(empty($interests)) {
        $error = "Please select at least one interest";
    } else {
        // Convert interests array to comma-separated string for database storage
        $interests_str = implode(',', $interests);
        
        // Update or insert sejour record (without ville_preferee column)
        if($sejour) {
            $stmt = $pdo->prepare("UPDATE sejour SET budget=?, dureeSejour=?, centresInteret=?, modeTransportPrefere=? WHERE id_touriste=?");
            $stmt->execute([$budgetAmount, $duration, $interests_str, $transport_pref, $user_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO sejour (id_touriste, budget, dureeSejour, centresInteret, modeTransportPrefere) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $budgetAmount, $duration, $interests_str, $transport_pref]);
        }
        
        // Store in session for quick access
        $_SESSION['user_budget_amount'] = $budgetAmount;
        $_SESSION['user_duration'] = $duration;
        $_SESSION['user_interests'] = $interests;
        $_SESSION['user_city'] = $city;
        $_SESSION['transport_pref'] = $transport_pref;
        
        $success = "Travel preferences saved successfully!";
        
        // Refresh sejour data
        $stmt = $pdo->prepare("SELECT * FROM sejour WHERE id_touriste = ?");
        $stmt->execute([$user_id]);
        $sejour = $stmt->fetch();
    }
}

// Set default values from session or database
$budgetAmount = $_SESSION['user_budget_amount'] ?? ($sejour['budget'] ?? 200);
$duration = $_SESSION['user_duration'] ?? ($sejour['dureeSejour'] ?? 3);
$interests = $_SESSION['user_interests'] ?? ($sejour['centresInteret'] ? explode(',', $sejour['centresInteret']) : []);
$transport_pref = $_SESSION['transport_pref'] ?? ($sejour['modeTransportPrefere'] ?? 'Marche');
$selected_city = $_SESSION['user_city'] ?? 'oujda';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elyora - Travel Profile</title>
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
    <div class="card">
        <h2>✈️ Your Travel Preferences</h2>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        
        <!-- Display error or success messages -->
        <?php if($error): ?>
            <div class="error-message" style="background:#ffe0e0; padding:1rem; border-radius:12px; margin-bottom:1rem; color:#c00;">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success" style="background:#2b6e3c;">
                ✅ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- Travel preferences form -->
        <form method="POST" action="">
            <label for="budget_amount">💰 Your Budget (USD) *</label>
            <input type="number" id="budget_amount" name="budget_amount" step="10" min="50" value="<?php echo $budgetAmount; ?>" required>
            <small>Budget in US Dollars (USD)</small>

            <label for="destination_city">🌍 Choose your destination city *</label>
            <select id="destination_city" name="destination_city" required>
                <option value="oujda" <?php echo $selected_city == 'oujda' ? 'selected' : ''; ?>>Oujda – Authentic eastern charm</option>
                <option value="marrakech" <?php echo $selected_city == 'marrakech' ? 'selected' : ''; ?>>Marrakech – Red city & vibrant souks</option>
                <option value="fes" <?php echo $selected_city == 'fes' ? 'selected' : ''; ?>>Fes – Spiritual & artisanal heart</option>
            </select>

            <label>🎯 Your Interests (select at least one)</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="interests[]" value="History" <?php echo in_array('History', $interests) ? 'checked' : ''; ?>> 🏛️ History</label>
                <label><input type="checkbox" name="interests[]" value="Food" <?php echo in_array('Food', $interests) ? 'checked' : ''; ?>> 🍽️ Food</label>
                <label><input type="checkbox" name="interests[]" value="Nature" <?php echo in_array('Nature', $interests) ? 'checked' : ''; ?>> 🌿 Nature</label>
                <label><input type="checkbox" name="interests[]" value="Art" <?php echo in_array('Art', $interests) ? 'checked' : ''; ?>> 🎨 Art / Craft</label>
            </div>

            <label for="transport_pref">🚗 Preferred Transport *</label>
            <select id="transport_pref" name="transport_pref" required>
                <option value="Marche" <?php echo $transport_pref == 'Marche' ? 'selected' : ''; ?>>🚶 Walking</option>
                <option value="Taxi" <?php echo $transport_pref == 'Taxi' ? 'selected' : ''; ?>>🚕 Taxi</option>
                <option value="Location" <?php echo $transport_pref == 'Location' ? 'selected' : ''; ?>>🚗 Car Rental</option>
                <option value="Scooter" <?php echo $transport_pref == 'Scooter' ? 'selected' : ''; ?>>🛵 Scooter</option>
                <option value="Velos" <?php echo $transport_pref == 'Velos' ? 'selected' : ''; ?>>🚲 Bicycle</option>
            </select>

            <label for="trip_duration">📅 Trip Duration (days) *</label>
            <input type="number" id="trip_duration" name="trip_duration" min="1" max="45" value="<?php echo $duration; ?>" required>

            <button type="submit" class="btn">💾 Save Travel Preferences</button>
        </form>
        
        <!-- Itinerary Preview -->
        <div id="itinerary-preview"></div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>