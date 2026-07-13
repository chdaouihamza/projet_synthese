<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
$b=$_SESSION['pending_booking']??null;
if(!$b){header("Location: places.php");exit();}
$pm=$_POST['payment_method']??'credit_card';
$uid=$_SESSION['user_id'];
$err='';$ref='';$cb=[];

try {
    $ref='ELY'.strtoupper(substr(uniqid(),-8)).rand(100,999);               
    $pdo->beginTransaction();
    $tid=$hid=$rid=$aid=$lid=null;

    if(!empty($b['transport'])){
        $s=$pdo->prepare("SELECT id_transport FROM transport WHERE type=?");
        $s->execute([$b['transport']]);
        $r=$s->fetch();
        if($r) $tid=$r['id_transport'];
        else{$s=$pdo->prepare("INSERT INTO transport(type,disponibilite)VALUES(?,1)");$s->execute([$b['transport']]);$tid=$pdo->lastInsertId();}
    }
    if($b['type']=='hotel'){
        $s=$pdo->prepare("SELECT id_hotel FROM hotel WHERE nom=?");$s->execute([$b['item']]);$r=$s->fetch();
        if($r) $hid=$r['id_hotel'];
        else{$s=$pdo->prepare("INSERT INTO hotel(nom,adresse,disponibilites)VALUES(?,?,1)");$s->execute([$b['item'],$b['city']]);$hid=$pdo->lastInsertId();}
    }
    if($b['type']=='restaurant'){
        $s=$pdo->prepare("SELECT id_restaurant FROM restaurant WHERE nom=?");$s->execute([$b['item']]);$r=$s->fetch();
        if($r) $rid=$r['id_restaurant'];
        else{$s=$pdo->prepare("INSERT INTO restaurant(nom,adresse,disponibles)VALUES(?,?,1)");$s->execute([$b['item'],$b['city']]);$rid=$pdo->lastInsertId();}
    }
    if($b['type']=='lieu'){
        $s=$pdo->prepare("SELECT id_lieu_touristique FROM lieutouristique WHERE nom=?");$s->execute([$b['item']]);$r=$s->fetch();
        if($r) $lid=$r['id_lieu_touristique'];
    }
    if($b['type']=='activite'){
        $s=$pdo->prepare("INSERT INTO activite(nom,description,prix,disponible,lieu)VALUES(?,?,?,1,?)");
        $s->execute([$b['item'],'Tourist activity',$b['price'],$b['city']]);$aid=$pdo->lastInsertId();
    }

    $s=$pdo->prepare("INSERT INTO reservation(id_touriste,date_debut,date_fin,statut,id_hotel,id_restaurant,id_transport,id_activite,id_lieu_touristique)VALUES(?,NOW(),DATE_ADD(NOW(),INTERVAL 1 DAY),'Paye',?,?,?,?,?)");
    $s->execute([$uid,$hid,$rid,$tid,$aid,$lid]);$resid=$pdo->lastInsertId();

    $s=$pdo->prepare("INSERT INTO paiement(id_reservation,montant,statut,modePaiement,date)VALUES(?,?,'Reussi',?,NOW())");
    $s->execute([$resid,$b['total'],$pm]);
    $pdo->commit();

    $cb=['ref'=>$ref,'type'=>$b['type'],'item'=>$b['item'],'city'=>$b['city'],'price'=>$b['price'],'tax'=>$b['tax'],'total'=>$b['total'],'payment_method'=>$pm,'date'=>date('Y-m-d H:i:s'),'status'=>'confirmed','reservation_id'=>$resid];
    if(!isset($_SESSION['booking_history']))$_SESSION['booking_history']=[];
    $_SESSION['booking_history'][]=$cb;
    $_SESSION['last_booking']=$cb;
    unset($_SESSION['pending_booking']);
} catch(Exception $e){$pdo->rollBack();$err="Booking failed: ".$e->getMessage();}

$types=['lieu'=>'🏛️ Tourist Attraction','activite'=>'🎯 Activity','restaurant'=>'🍽️ Restaurant','hotel'=>'🏨 Hotel','transport'=>'🚗 Transport'];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Elyora - Booking Confirmed</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <div class="header-brand">
    <img src="Elyora-logo.png" alt="Elyora Logo" class="logo" onerror="this.src='https://placehold.co/74x74?text=EL'">
    <h1 class="elyorah1">Elyora</h1>
  </div>
  <nav>
    <a href="index.php">Home</a><a href="profile.php">Profile</a>
    <a href="itinerary.php">Itinerary</a><a href="places.php">Places</a>
    <a href="my_bookings.php">My Bookings</a><a href="edit_profile.php">Edit Account</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<div class="container">
<?php if($err):?>
  <div style="background:#ffe0e0;padding:1rem;border-radius:12px;color:#c00">
    <h3>❌ Booking Failed</h3><p><?=$err?></p>
    <p style="margin-top:1rem"><a href="places.php" class="btn">← Try Again</a></p>
  </div>
<?php else:?>
  <div class="success" style="background:linear-gradient(135deg,#2b6e3c,#1e531f)">
    <h2>✅ Booking Confirmed!</h2>
    <p style="font-size:1.2rem;margin:1rem 0">Your reservation has been successfully confirmed.</p>
    <p>🎫 <strong>Booking Reference:</strong> #<?=$ref?></p>
    <p>📧 Confirmation sent to: <strong><?=$_SESSION['user_email']?></strong></p>
    <p>📞 Contact number: <strong><?=$_SESSION['user_telephone']?></strong></p>
  </div>
  <div class="card">
    <h3>📋 Booking Details</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
      <div><p><strong>Booking Type:</strong></p><p><strong>Item:</strong></p><p><strong>Location:</strong></p><p><strong>Traveler:</strong></p></div>
      <div>
        <p><?=$types[$cb['type']]??'📦 Package'?></p>
        <p><strong><?=htmlspecialchars($cb['item'])?></strong></p>
        <p><?=htmlspecialchars($cb['city'])?></p>
        <p><?=$_SESSION['user_name']?></p>
      </div>
    </div>
    <hr>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
      <div><p><strong>Payment Method:</strong></p><p><strong>Subtotal:</strong></p><p><strong>Tax (10%):</strong></p><p><strong>Total Paid:</strong></p></div>
      <div>
        <p><?=$pm=='credit_card'?'💳 Credit Card':'📧 PayPal'?></p>
        <p>$<?=number_format($cb['price'],2)?></p>
        <p>$<?=number_format($cb['tax'],2)?></p>
        <p style="font-size:1.3rem;font-weight:bold;color:#2c6e2f">$<?=number_format($cb['total'],2)?></p>
      </div>
    </div>
  </div>
  <div class="card" style="background:#e8f5e9;text-align:center">
    <h3>🎉 Thank You for Booking with Elyora!</h3>
    <p>Your adventure awaits. Please present your booking reference at the venue.</p>
  </div>
<?php endif?>
  <div style="text-align:center;margin:2rem 0;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
    <a href="my_bookings.php" class="btn" style="background-color:#8b6946">📋 View My Bookings</a>
    <a href="places.php" class="btn">🗺️ Book More</a>
    <a href="index.php" class="btn" style="background-color:#555">🏠 Home</a>
  </div>
</div>
<script src="script.js"></script>
</body>
</html>