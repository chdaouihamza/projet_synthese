<?php



require_once 'config.php';

// If user is already logged in, redirect to home page


if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = ''; // Variable bax ndiro store error messages

// Check if form was submitted (POST request)
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate that fields are not empty
    if(empty($email) || empty($password)) {
        $error = "Please fill all fields";
    } else {
        // Prepare and execute SQL query to find user by email o kayprotecter mn sql injections
        $stmt = $pdo->prepare("SELECT * FROM touriste WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(); // Fetch user data as associative array
        
        // Verify password m3a  hashed passwords
        if($user && password_verify($password, $user['motDePasse'])) {
            // Store user information in session (persists across pages)
            $_SESSION['user_id'] = $user['id_touriste'];
            $_SESSION['user_name'] = $user['nom'] . ' ' . $user['prenom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_telephone'] = $user['telephone'];
            
            // Load saved travel preferences (sejour)
            $stmt2 = $pdo->prepare("SELECT * FROM sejour WHERE id_touriste = ?");
            $stmt2->execute([$user['id_touriste']]);
            $sejour = $stmt2->fetch();
            
            if($sejour) {
                // Store travel preferences in session
                $_SESSION['user_budget_amount'] = $sejour['budget'];
                $_SESSION['user_duration'] = $sejour['dureeSejour'];
                $_SESSION['user_interests'] = explode(',', $sejour['centresInteret'] ?? '');
                $_SESSION['transport_pref'] = $sejour['modeTransportPrefere'] ?? 'Marche';
            }
            
            // Set default city if not set
            if(!isset($_SESSION['user_city'])) {
                $_SESSION['user_city'] = 'oujda';
            }
            
            // Redirect to home page after successful login
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elyora - Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container { max-width: 500px; margin: 3rem auto; }
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

<div class="container login-container">
    <div class="card">
        <h2>🔐 Login to Your Account</h2>
        
        <!-- Display error message if any -->
        <?php if($error): ?>
            <div class="error-message" style="background:#ffe0e0; padding:1rem; border-radius:12px; margin-bottom:1rem; color:#c00;">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Login form -->
        <form method="POST" action="" onsubmit="return validateLoginForm()">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit" class="btn">Login →</button>
        </form>
        
        <div class="register-link" style="text-align:center; margin-top:1rem;">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>