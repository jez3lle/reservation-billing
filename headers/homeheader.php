<div class="top-space">
        <div class="hamburger" onclick="toggleMenu()">☰</div>
    </div>
    <div class="menu">
        <div class="close-icon" onclick="toggleMenu()">X</div>
        <div class="menucontainer">
            <div class="phase-card phase-private">
                <h2>PHASE 1</h2>
                <h3>PRIVATE</h3>
                <p>
                    Enjoy exclusive access to the entire resort! This includes two pools, two houses, a pavilion, and a cozy kubo, ensuring privacy and relaxation.
                    Perfect for families, reunions, and private gatherings. Guests can also partake in exciting activities available in the public area.
                </p>
                <a href="home_p1.php" class="phasebutton">Proceed to Phase 1</a>
            </div>
            <div class="phase-card phase-public">
                <h2>PHASE 2</h2>
                <h3>PUBLIC</h3>
                <p>
                    Stay in our welcoming accommodations, including rooms, cabins, and houses, ideal for individuals or small groups. 
                    Enjoy thrilling activities such as ziplining, bonfires, and swimming, making your stay an unforgettable adventure!
                </p>
                <a href="home_p2.php" class="phasebutton">Proceed to Phase 2</a>
            </div>            
        </div>
    </div>
    <header class="hero">
    <div class="overlay"></div>
    <nav class="home-navbar">
        <div class="logo">
            <img src="images/rainbow-logo.png" alt="Logo">
            <div>
                <h1>Rainbow Forest Paradise</h1>
                <h2>Resort and Campsite</h2>
            </div>
        </div>
        <div class="nav-right">
            <ul id="menu-img" class="home-nav-links">
                <li><a href="home_p1.php">HOME</a></li>
                <li><a href="aboutus_p1.php">ABOUT</a></li>
                <li><a href="accomodation_p1.php">ACCOMMODATIONS</a></li>
                <li><a href="activities_p1.php">ACTIVITIES</a></li>
                <li><a href="contact_p1.php">CONTACT US</a></li>
                <li><a href="#">BOOK NOW</a></li>
            </ul>
        </div>
        <div class="booking-search">
            <input type="text" id="reference-search" placeholder="Enter booking reference #">
            <button onclick="searchBooking()">Search</button>
            <div id="search-results" class="search-results-dropdown"></div>
        </div>
        <div class="icon">
            <?php if($current_user): ?>
                <div class="user-info">
                    <span class="user-name">Hello, <?= htmlspecialchars($current_user["first_name"]) ?></span>
                    <div class="user-actions">
                        <a href="account.php" class="profile-btn">My Profile</a>
                        <form action="logout.php" method="post">
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="user-icon">
                    <img src="images/logo.png" alt="User Icon">
                </a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="hero-text">
        <h1><span>Welcome to Phase 1</span><br>Private</h1>
        <p>Book the entire property for your exclusive retreat!</p>
        <p>Experience nature, peace, and luxury.</p>
        <a href="#" class="booknow">BOOK NOW</a> 
    </div>
    <div class="menu-img">
        <img src="images/bg2.png" alt="Image 1" onclick="changeBackground('images/bg2.png', this)">
        <img src="images/resort2.png" alt="Image 2" onclick="changeBackground('images/resort2.png', this)">
        <img src="images/house1.png" alt="Image 3" onclick="changeBackground('images/house1.png', this)">
    </div>
</header>

<style>
.home-navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    position: relative;
    z-index: 10;
}

.booking-search {
    display: flex;
    align-items: center;
    position: relative;
    margin: 0 15px;
}

.booking-search input {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px 0 0 4px;
    width: 220px;
    font-size: 14px;
}

.booking-search button {
    padding: 8px 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
    font-size: 14px;
}

.booking-search button:hover {
    background-color: #45a049;
}

.search-results-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 4px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 100;
}

.search-result-item {
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    color: black;
}

.search-result-item:hover {
    background-color: #f5f5f5;
}

.pending-payment {
    background-color: #fff3cd;
}

.continue-payment-btn {
    background-color: #dc3545;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-top: 5px;
    display: inline-block;
}

.continue-payment-btn:hover {
    background-color: #c82333;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .home-navbar {
        flex-wrap: wrap;
    }
    
    .booking-search {
        order: 2;
        width: 100%;
        margin: 10px 0;
    }
    
    .nav-right {
        order: 3;
    }
}
.search-results-dropdown {
    /* Keep other properties */
    display: none; /* This will be changed by JavaScript when there are results */
}

/* Add this to make sure dropdown is visible when it has content */
.search-results-dropdown:not(:empty) {
    display: block;
}
expired-booking {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
}

.expired-label {
    background-color: #dc3545;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    margin-left: 5px;
}
</style>

<script>
function searchBooking() {
    const referenceNumber = document.getElementById('reference-search').value.trim();
    const resultsContainer = document.getElementById('search-results');

    if (!referenceNumber) {
        resultsContainer.innerHTML = '<div class="search-result-item invalid-booking">Please enter a reference number.</div>';
        resultsContainer.style.display = 'block';
        return;
    }

    resultsContainer.innerHTML = '<div class="search-result-item">Searching...</div>';
    resultsContainer.style.display = 'block';

    fetch('search_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'reference=' + encodeURIComponent(referenceNumber)
    })
    .then(response => {
        // Get the raw text first to see if there are any errors
        return response.text().then(text => {
            try {
                // Try to parse as JSON
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse response as JSON:', text);
                throw new Error('Invalid response from server: ' + e.message);
            }
        });
    })
    .then(data => {
        resultsContainer.innerHTML = ''; // Clear previous content
        console.log('Search response:', data); // Debug logging

        if (data.error) {
            resultsContainer.innerHTML = `<div class="search-result-item">${data.error}</div>`;
            resultsContainer.style.display = 'block';
            return;
        }

        // Check if there are any results at all
        if (!data.results || data.results.length === 0) {
            resultsContainer.innerHTML = '<div class="search-result-item">No bookings found with this reference number</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        // Filter results to only include exact matches and non-expired reservations
        const validMatches = data.results.filter(booking => 
            booking.reference_number.toLowerCase() === referenceNumber.toLowerCase() && 
            !booking.is_expired);
        
        const expiredMatches = data.results.filter(booking => 
            booking.reference_number.toLowerCase() === referenceNumber.toLowerCase() && 
            booking.is_expired);
        
        // If no valid or expired matches, show "no match found"
        if (validMatches.length === 0 && expiredMatches.length === 0) {
            resultsContainer.innerHTML = '<div class="search-result-item">No exact match found for reference number: ' + referenceNumber + '</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        // REMOVED: The automatic redirect for single pending payment
        // We now always show the results and let the user decide which one to select

        // Display valid matches first (if any)
        let html = '';
        
        if (validMatches.length > 0) {
            validMatches.forEach(booking => {
                html += `
                <div class="search-result-item">
                    <strong>Booking #${booking.reference_number}</strong>
                    <p>Name: ${booking.name}</p>
                    <p>Date: ${booking.booking_date}</p>
                    <p>Check-in: ${booking.check_in}</p>
                    <p>Check-out: ${booking.check_out}</p>
                    <p>Status: ${booking.status}</p>
                    <p>Amount: ${booking.total_amount}</p>
                    ${booking.status.toLowerCase() !== 'approved' ? 
                      `<a href="saved_billing.php?code=${booking.reservation_code}&type=${booking.reservation_type}" class="continue-payment-btn">Continue Payment</a>` : 
                      ''}
                </div>`;
            });
        }
        
        // Then add expired matches (if any)
        if (expiredMatches.length > 0) {
            expiredMatches.forEach(booking => {
                html += `
                <div class="search-result-item expired-booking">
                    <strong>Booking #${booking.reference_number}</strong> <span class="expired-label">EXPIRED</span>
                    <p>Name: ${booking.name}</p>
                    <p>Date: ${booking.booking_date}</p>
                    <p>Check-in: ${booking.check_in}</p>
                    <p>Check-out: ${booking.check_out}</p>
                    <p>Status: ${booking.status}</p>
                    <p>Amount: ${booking.total_amount}</p>
                </div>`;
            });
        }
        
        resultsContainer.innerHTML = html;
        resultsContainer.style.display = 'block';
    })
    .catch(error => {
        console.error('Error details:', error);
        resultsContainer.innerHTML = '<div class="search-result-item">An error occurred while searching. Please try again. Details: ' + error.message + '</div>';
        resultsContainer.style.display = 'block';
    });
}
function displayResults(data) {
    const resultsContainer = document.getElementById('search-results');
    resultsContainer.innerHTML = '';

    if (data.success) {
        const results = data.bookings;

        if (results.length === 1 && results[0].payment_status.toLowerCase() === 'pending') {
            window.location.href = `saved_billing.php?code=${results[0].reservation_code}&type=${results[0].reservation_type}`;
            return;
        }

        results.forEach(booking => {
            const item = document.createElement('div');
            item.className = 'search-result-item valid-booking';
            
            let html = `
                <strong>${booking.name}</strong><br>
                Date: ${booking.date}<br>
                Status: ${booking.payment_status}<br>
            `;
            
            // Only add the Continue Payment button if status is not approved
            if (booking.payment_status.toLowerCase() !== 'approved') {
                html += `<a href="saved_billing.php?code=${booking.reservation_code}&type=${booking.reservation_type}" class="continue-payment-btn">Continue Payment</a>`;
            }
            
            item.innerHTML = html;
            resultsContainer.appendChild(item);
        });
    } else {
        resultsContainer.innerHTML = '<div class="search-result-item invalid-booking">No booking found with that reference.</div>';
        resultsContainer.style.display = 'block';
    }
}
</script>
