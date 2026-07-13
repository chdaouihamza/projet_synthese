<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$duration = $_SESSION['user_duration'] ?? 3;
$city = (['oujda'=>'Oujda','marrakech'=>'Marrakech','fes'=>'Fes'])[$_SESSION['user_city'] ?? 'oujda'] ?? 'Morocco';
$price = ($duration * 50) + (count($_SESSION['user_interests'] ?? []) * 5);

$_SESSION['pending_booking'] = [
    'type' => 'package',
    'item' => "Complete {$duration}-Day Travel Package to {$city}",
    'price' => $price,
    'tax' => $price * 0.10,
    'total' => $price * 1.10,
    'city' => $city,
    'transport' => $_SESSION['transport_pref'] ?? 'Marche'
];

header("Location: payment.php?type=package&item=" . urlencode("Complete {$duration}-Day Travel Package to {$city}") . "&price={$price}&city={$city}");
exit();