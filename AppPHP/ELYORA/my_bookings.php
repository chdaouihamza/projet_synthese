<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$all_bookings = [];

// DB bookings
try {
    $stmt = $pdo->prepare("
        SELECT r.id_reservation, r.date_debut, r.statut,
               p.montant as paid_amount, p.modePaiement,
               h.nom as hotel_name, rt.nom as restaurant_name,
               a.nom as activity_name, a.prix as activity_price,
               t.type as transport_type
        FROM reservation r
        LEFT JOIN paiement p ON r.id_reservation = p.id_reservation
        LEFT JOIN hotel h ON r.id_hotel = h.id_hotel
        LEFT JOIN restaurant rt ON r.id_restaurant = rt.id_restaurant
        LEFT JOIN activite a ON r.id_activite = a.id_activite
        LEFT JOIN transport t ON r.id_transport = t.id_transport
        WHERE r.id_touriste = ? ORDER BY r.date_debut DESC
    ");
    $stmt->execute([$user_id]);

    foreach($stmt->fetchAll() as $b) {
        if($b['hotel_name'])          { $item = $b['hotel_name'];                          $type = 'hotel';     $price = $b['paid_amount'] ?? 0; }
        elseif($b['restaurant_name']) { $item = $b['restaurant_name'];                     $type = 'restaurant';$price = $b['paid_amount'] ?? 0; }
        elseif($b['activity_name'])   { $item = $b['activity_name'];                       $type = 'place';     $price = $b['activity_price'] ?? $b['paid_amount'] ?? 0; }
        elseif($b['transport_type'])  { $item = ucfirst($b['transport_type']) . ' Service'; $type = 'transport'; $price = $b['paid_amount'] ?? 0; }
        else                          { $item = 'Booking #' . $b['id_reservation'];         $type = 'unknown';   $price = $b['paid_amount'] ?? 0; }

        $all_bookings[] = ['ref' => 'DB'.$b['id_reservation'], 'type' => $type, 'item' => $item,
            'city' => 'Morocco', 'total' => floatval($price), 'payment_method' => $b['modePaiement'] ?? 'Credit Card',
            'date' => $b['date_debut'], 'status' => $b['statut'], 'source' => 'database'];
    }
} catch(PDOException $e) { error_log($e->getMessage()); }

// Session + last booking
foreach($_SESSION['booking_history'] ?? [] as $b) { $all_bookings[] = $b + ['source' => 'session']; }
if(isset($_SESSION['last_booking'])) {
    $last = $_SESSION['last_booking'] + ['source' => 'recent'];
    if(!array_filter($all_bookings, fn($b) => ($b['ref'] ?? '') == ($last['ref'] ?? '')))
        array_unshift($all_bookings, $last);
}

// Deduplicate by ref
$seen = [];
$all_bookings = array_filter(array_reverse($all_bookings), fn($b) => !($seen[$b['ref']] ?? false) && ($seen[$b['ref']] = true));
$all_bookings = array_reverse(array_values($all_bookings));

$total_spent = array_sum(array_column($all_bookings, 'total'));

$type_icons = ['hotel' => '🏨', 'restaurant' => '🍽️', 'place' => '🏛️', 'transport' => '🚗'];
$pay_labels  = ['credit_card' => '💳 Credit Card', 'paypal' => '📧 PayPal (Demo)'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elyora - My Bookings</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .booking-card { transition: all 0.2s; }
        .booking-card:hover { transform: translateY(-2px); }
        .status-badge { background:#2c6e2f; color:#fff; padding:.2rem .8rem; border-radius:20px; font-size:.8rem; }
        .demo-note { background:#e3f2fd; border-left:4px solid #2196f3; padding:1rem; border-radius:8px; margin-bottom:1rem; }
    </style>
</head>
<body>
<header>
    <div class="header-brand">
        <img src="Elyora-logo.png" alt="Elyora Logo" class="logo" onerror="this.src='https://placehold.co/74x74?text=EL'">
        <h1 class="elyorah1">Elyora</h1>
    </div>
    <nav>
        <a href="index.php">Home</a><a href="profile.php">Profile</a><a href="itinerary.php">Itinerary</a>
        <a href="places.php">Places</a><a href="my_bookings.php">My Bookings</a>
        <a href="edit_profile.php">Edit Account</a><a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <div class="card">
        <h2>📋 My Travel Bookings</h2>
        <p>Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>! Here are all your confirmed reservations.</p>
    </div>

    <div class="demo-note">
        <strong>📌 School Project Demo Note:</strong> PayPal payments work in demo mode (no real money required).
        All bookings are saved and displayed below.
    </div>

    <?php if(empty($all_bookings)): ?>
        <div class="card" style="text-align:center">
            <p style="font-size:1.2rem">😊 You don't have any bookings yet.</p>
            <p style="margin:1rem 0">Start exploring and book your first travel experience!</p>
            <a href="places.php" class="btn" style="margin-top:.5rem">✨ Start Exploring & Book Now</a>
        </div>
    <?php else: ?>
        <?php foreach($all_bookings as $booking): ?>
            <div class="card booking-card">
                <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap">
                    <div>
                        <h3><?= $type_icons[$booking['type']] ?? '🎒' ?> <?= htmlspecialchars($booking['item']) ?></h3>
                        <?php if(!empty($booking['city'])): ?><p><strong>📍 <?= htmlspecialchars($booking['city']) ?></strong></p><?php endif; ?>
                        <p><strong>📅 Booked on:</strong> <?= date('M d, Y H:i', strtotime($booking['date'])) ?></p>
                        <p><strong>🎫 Reference:</strong> #<?= htmlspecialchars($booking['ref']) ?></p>
                        <?php if(isset($booking['source'])): ?><p><small>Source: <?= ucfirst($booking['source']) ?></small></p><?php endif; ?>
                    </div>
                    <div style="text-align:right">
                        <p><strong>Total Paid:</strong> <span style="color:#2c6e2f;font-size:1.2rem;font-weight:bold">$<?= number_format(floatval($booking['total']), 2) ?></span></p>
                        <p><strong>Status:</strong> <span class="status-badge">✅ <?= $booking['status'] ?? 'Confirmed' ?></span></p>
                        <p><strong>Payment:</strong> <?= $pay_labels[$booking['payment_method']] ?? $booking['payment_method'] ?></p>
                    </div>
                </div>
                <hr>
                <div style="display:flex;gap:1rem;margin-top:1rem;flex-wrap:wrap">
                    <a href="#" class="btn" style="background-color:#8b6946;font-size:.85rem;padding:.5rem 1rem"
                       onclick="alert('Voucher sent to <?= $_SESSION['user_email'] ?>')">📧 Download Voucher</a>
                    <a href="#" class="btn" style="background-color:#555;font-size:.85rem;padding:.5rem 1rem"
                       onclick="alert('Free cancellation up to 24h before\nContact: support@elyora.com')">❓ Need Help</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="card" style="background:linear-gradient(135deg,#f0f7f0,#e8f5e9);text-align:center">
            <h3>📊 Total Spent</h3>
            <p style="font-size:2.5rem;font-weight:bold;color:#2c6e2f">$<?= number_format($total_spent, 2) ?></p>
            <p>You have made <?= count($all_bookings) ?> booking(s) with Elyora!</p>
            <hr style="margin:1rem 0">
            <p style="font-size:.9rem;color:#666"><strong>💡 Tip:</strong> All your bookings are saved. You can download vouchers for each booking.</p>
        </div>
    <?php endif; ?>

    <div style="text-align:center;margin:1rem 0;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
        <a href="places.php" class="btn">🗺️ Book More Experiences</a>
        <a href="itinerary.php" class="btn" style="background-color:#8b6946">🗺️ View My Itinerary</a>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>