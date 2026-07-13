// ============================================
// SCRIPT.JS - Elyora Travel Platform
// Client-side JavaScript functions
// ============================================

// ============================================
// PROFILE FORM VALIDATION
// ============================================
function validateNewProfileForm() {
    let fullName = document.getElementById('full_name')?.value.trim();
    let email = document.getElementById('email')?.value.trim();
    let telephone = document.getElementById('telephone')?.value.trim();
    let password = document.getElementById('password')?.value;
    let passwordConfirm = document.getElementById('password_confirm')?.value;
    let budgetAmount = document.getElementById('budget_amount')?.value;
    let city = document.getElementById('destination_city')?.value;
    let interests = document.querySelectorAll('input[name="interests[]"]:checked');
    
    if(!fullName || fullName.length < 2) {
        alert("Please enter your full name (at least 2 characters).");
        return false;
    }
    
    if(!email) {
        alert("Please enter your email address.");
        return false;
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailPattern.test(email)) {
        alert("Please enter a valid email address (e.g., name@example.com).");
        return false;
    }
    
    if(!telephone) {
        alert("Please enter your telephone number.");
        return false;
    }
    const phonePattern = /^[\+0-9\s\-]{7,15}$/;
    if(!phonePattern.test(telephone)) {
        alert("Please enter a valid telephone number (7-15 digits, can include +, spaces, or hyphens).");
        return false;
    }
    
    if(!password || password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
    }
    
    if(password !== passwordConfirm) {
        alert("Passwords do not match. Please re-enter.");
        return false;
    }
    
    if(!budgetAmount || budgetAmount < 50) {
        alert("Please enter a valid budget (minimum 50 USD).");
        return false;
    }
    
    if(!city) {
        alert("Please select a city you want to visit.");
        return false;
    }
    
    if(interests.length === 0) {
        alert("Please select at least one interest (History, Food, Nature, Art).");
        return false;
    }
    
    let duration = document.getElementById('trip_duration')?.value;
    if(!duration || duration < 1 || duration > 45) {
        alert("Please enter a valid trip duration (1-45 days).");
        return false;
    }
    
    alert("✓ Profile saved successfully! Generating your personalized itinerary...");
    return true;
}

// ============================================
// BOOKING CONFIRMATION
// ============================================
function confirmBooking() {
    return confirm("Confirm your booking? We'll prepare the best experience for you.");
}

// ============================================
// DYNAMIC GREETING
// ============================================
function displayGreeting() {
    const hour = new Date().getHours();
    let greetingMsg = "";
    if (hour < 12) {
        greetingMsg = "Good morning! 🌿 Ready to explore?";
    } else if (hour < 18) {
        greetingMsg = "Good afternoon! ✨ Plan your next adventure";
    } else {
        greetingMsg = "Good evening! 🌙 Discover dream destinations";
    }
    
    const greetDiv = document.getElementById('dynamicGreeting');
    if(greetDiv) {
        greetDiv.innerHTML = `<p style="text-align:center; font-size:1.2rem; margin-bottom:1rem;">${greetingMsg}</p>`;
    }
}

// ============================================
// LOGIN FORM VALIDATION
// ============================================
function validateLoginForm() {
    let email = document.getElementById('email')?.value.trim();
    let password = document.getElementById('password')?.value;
    
    if(!email) {
        alert("Please enter your email address.");
        return false;
    }
    
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }
    
    if(!password || password.length < 1) {
        alert("Please enter your password.");
        return false;
    }
    
    return true;
}

// ============================================
// PAGE LOAD INITIALIZATION
// ============================================
window.onload = function() {
    displayGreeting();
    console.log("Elyora Travel Platform loaded successfully!");
};