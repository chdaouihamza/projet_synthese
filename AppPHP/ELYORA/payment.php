<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
$t=$_GET['type']??'';
$n=$_GET['item']??'';
$p=floatval($_GET['price']??0);
$c=$_GET['city']??'';
$tr=$_GET['transport']??null;
$il=$_GET['id_lieu']??null;
$tax=$p*.10;$tot=$p+$tax;
$_SESSION['pending_booking']=['type'=>$t,'item'=>$n,'price'=>$p,'tax'=>$tax,'total'=>$tot,'city'=>$c,'transport'=>$tr,'id_lieu'=>$il];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Elyora - Secure Payment</title>
<link rel="stylesheet" href="style.css">
<style>
.pc{display:flex;gap:2rem;flex-wrap:wrap}
.ps{flex:1;background:#f8f6f0;border-radius:24px;padding:1.5rem}
.pf{flex:1.5}
.cd{background:#fff;border-radius:16px;padding:1.5rem;border:1px solid #ede6d9}
.ci{width:100%;padding:.8rem;border:1px solid #ddd;border-radius:8px;margin-bottom:1rem}
.cr{display:flex;gap:1rem}
.pm{display:flex;gap:1rem;margin-bottom:1rem;padding:.8rem;border:2px solid #ddd;border-radius:12px;cursor:pointer}
.pm.sel{border-color:#2c6e2f;background:#e8f5e9}
</style>
</head>
<body>
<header>
  <div class="header-brand">
    <img src="Elyora-logo.png" alt="Elyora Logo" class="logo">
    <h1 class="elyorah1">Elyora</h1>
  </div>
  <nav>
    <a href="index.php">Home</a><a href="profile.php">Profile</a>
    <a href="itinerary.php">Itinerary</a><a href="places.php">Places</a>
    <a href="my_bookings.php">My Bookings</a><a href="logout.php">Logout</a>
  </nav>
</header>
<div class="container"><div class="card">
  <h2>💳 Complete Your Payment</h2>
  <p>Booking: <strong><?=htmlspecialchars($n)?></strong> in <?=htmlspecialchars($c)?></p>
  <div class="pc">
    <div class="ps">
      <h3>📋 Order Summary</h3>
      <div style="margin:1rem 0">
        <p><strong>Item:</strong> <?=htmlspecialchars($n)?></p>
        <p><strong>Location:</strong> <?=htmlspecialchars($c)?></p>
        <p><strong>Traveler:</strong> <?=$_SESSION['user_name']?></p>
        <hr>
        <p><strong>Subtotal:</strong> $<?=number_format($p,2)?></p>
        <p><strong>Tax (10%):</strong> $<?=number_format($tax,2)?></p>
        <p style="font-size:1.3rem;font-weight:bold;color:#2c6e2f"><strong>Total:</strong> $<?=number_format($tot,2)?></p>
      </div>
    </div>
    <div class="pf">
      <form action="confirm_booking.php" method="POST" id="paymentForm">
        <div class="cd">
          <h3>Select Payment Method</h3>
          <div class="pm sel" onclick="sel('credit')" id="mc">
            <input type="radio" name="payment_method" value="credit_card" id="cc" checked>
            <label>💳 Credit / Debit Card</label>
          </div>
          <div class="pm" onclick="sel('paypal')" id="mp">
            <input type="radio" name="payment_method" value="paypal" id="pp">
            <label>📧 PayPal</label>
          </div>
          <div id="cs">
            <label>Card Number</label>
            <input type="text" class="ci" id="cn" placeholder="1234 5678 9012 3456" required>
            <div class="cr">
              <div style="flex:1"><label>Expiry Date</label><input type="text" class="ci" id="ex" placeholder="MM/YY" required></div>
              <div style="flex:1"><label>CVV</label><input type="text" class="ci" id="cv" placeholder="123" required></div>
            </div>
            <label>Cardholder Name</label>
            <input type="text" class="ci" id="chn" value="<?=$_SESSION['user_name']?>" required>
          </div>
          <div id="pps" style="display:none">
            <label>PayPal Email</label>
            <input type="email" class="ci" id="pe" value="<?=$_SESSION['user_email']?>">
          </div>
          <button type="button" class="btn" style="width:100%" onclick="pay()">✅ Pay $<?=number_format($tot,2)?></button>
        </div>
      </form>
    </div>
  </div>
</div></div>
<script>
function sel(m){
  var c=m==='credit';
  document.getElementById('cs').style.display=c?'block':'none';
  document.getElementById('pps').style.display=c?'none':'block';
  document.getElementById('mc').classList.toggle('sel',c);
  document.getElementById('mp').classList.toggle('sel',!c);
  document.getElementById(c?'cc':'pp').checked=true;
}
function pay(){
  var m=document.querySelector('input[name="payment_method"]:checked');
  if(!m){alert("Please select a payment method.");return;}
  if(m.value==='credit_card'){
    var n=document.getElementById('cn').value.replace(/\s/g,'');
    if(n.length<15){alert("Please enter a valid card number.");return;}
    if(!document.getElementById('ex').value.match(/^(0[1-9]|1[0-2])\/\d{2}$/)){alert("Please enter valid expiry date (MM/YY).");return;}
  } else {
    var e=document.getElementById('pe').value;
    if(!e||!e.includes('@')){alert("Please enter a valid PayPal email.");return;}
  }
  alert("Processing payment...");
  document.getElementById('paymentForm').submit();
}
</script>
</body>
</html>