<?php


require_once 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get current user data from database
$stmt = $pdo->prepare("SELECT * FROM touriste WHERE id_touriste = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate basic info
    if(empty($nom) || empty($prenom) || empty($email) || empty($telephone)) {
        $error = "Please fill all required fields";
    } 
    // If user wants to change password
    elseif(!empty($new_password)) {
        if(empty($current_password) || !password_verify($current_password, $user['motDePasse'])) {
            $error = "Current password is incorrect";
        } elseif($new_password !== $confirm_password) {
            $error = "New passwords do not match";
        } elseif(strlen($new_password) < 8) {
            $error = "New password must be at least 8 characters";
        } else {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE touriste SET nom=?, prenom=?, email=?, telephone=?, motDePasse=? WHERE id_touriste=?");
            if($stmt->execute([$nom, $prenom, $email, $telephone, $hashedPassword, $user_id])) {
                // Update session data
                $_SESSION['user_name'] = $nom . ' ' . $prenom;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_telephone'] = $telephone;
                $success = "Profile updated successfully!";
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM touriste WHERE id_touriste = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
            }
        }
    } 
    // Update without password change
    else {
        $stmt = $pdo->prepare("UPDATE touriste SET nom=?, prenom=?, email=?, telephone=? WHERE id_touriste=?");
        if($stmt->execute([$nom, $prenom, $email, $telephone, $user_id])) {
            $_SESSION['user_name'] = $nom . ' ' . $prenom;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_telephone'] = $telephone;
            $success = "Profile updated successfully!";
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM touriste WHERE id_touriste = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elyora - Edit Profile</title>
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
        <h2>✏️ Edit Your Profile</h2>
        
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
        
        <form method="POST" action="">
            <label for="nom">Last Name *</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
            
            <label for="prenom">First Name *</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
            
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label for="telephone">Telephone *</label>
            <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>" required>
            
            <hr style="margin: 1.5rem 0;">
            <h3>Change Password (optional)</h3>
            
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" placeholder="Enter current password to change">
            
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" placeholder="Min 8 characters" minlength="8">
            
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat new password">
            
            <button type="submit" class="btn">💾 Save Changes</button>
            <a href="profile.php" class="btn" style="background-color:#888;">← Back to Profile</a>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>