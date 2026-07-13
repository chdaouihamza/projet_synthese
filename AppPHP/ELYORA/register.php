<?php

require_once 'config.php';


if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';    // Variable for error messages
$success = '';  // Variable for success messages

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get all form data
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validation checks
    if(empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password)) {
        $error = "Please fill all fields";
    } elseif($password !== $password_confirm) {
        $error = "Passwords do not match";
    } elseif(strlen($password) < 8) {                   //password must be fort
        $error = "Password must be at least 8 characters";
    } else {
        // Check if email already exists in database
        $stmt = $pdo->prepare("SELECT id_touriste FROM touriste WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $error = "Email already registered";
        } else {
            // Hash password for security 
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user into database
            $stmt = $pdo->prepare("INSERT INTO touriste (nom, prenom, email, telephone, motDePasse) VALUES (?, ?, ?, ?, ?)");
            if($stmt->execute([$nom, $prenom, $email, $telephone, $hashedPassword])) {
                $success = "Account created successfully! Please login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elyora - Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .register-container { max-width: 600px; margin: 2rem auto; }
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
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<div class="container register-container">
    <div class="card">
        <h2>📝 Create Your Account</h2>
        
        <!-- Display error message -->
        <?php if($error): ?>
            <div class="error-message" style="background:#ffe0e0; padding:1rem; border-radius:12px; margin-bottom:1rem; color:#c00;">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Display success message -->
        <?php if($success): ?>
            <div class="success" style="background:#2b6e3c;">
                ✅ <?php echo htmlspecialchars($success); ?>
                <p style="margin-top:0.5rem;"><a href="login.php" style="color:white;">Click here to login</a></p>
            </div>
        <?php endif; ?>
        
        <!-- Registration form -->
        <form method="POST" action="">
            <label for="nom">Last Name *</label>
            <input type="text" id="nom" name="nom" required>
            
            <label for="prenom">First Name *</label>
            <input type="text" id="prenom" name="prenom" required>
            
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required>
            
            <label for="telephone">Telephone *</label>
            <input type="tel" id="telephone" name="telephone" pattern="[\+0-9\s\-]{7,15}" required>
            
            <label for="password">Password * (min 8 characters)</label>
            <input type="password" id="password" name="password" minlength="8" required>
            
            <label for="password_confirm">Confirm Password *</label>
            <input type="password" id="password_confirm" name="password_confirm" minlength="8" required>
            
            <button type="submit" class="btn">Register →</button>
        </form>
        
        <div class="register-link" style="text-align:center; margin-top:1rem;">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>