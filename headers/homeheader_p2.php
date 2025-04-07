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
                    <li><a href="home_p2.php">HOME</a></li>
                    <li><a href="aboutus_p2.php">ABOUT</a></li>
                    <li><a href="accomodation_p2.html">ACCOMMODATIONS</a></li>
                    <li><a href="activities_p2.php">ACTIVITIES</a></li>
                    <li><a href="contact_p2.php">CONTACT US</a></li>
                    <li><a href="booking_form.html">BOOK NOW</a></li>
                </ul>
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
            <h1><span>Welcome to Phase 2</span><br>Public</h1>
            <p>Stay Your Way – Choose a Room, Enjoy the Adventure!</p>
            <p>Nature, Comfort, and Activities – Your Ideal Escape!</p>
            <a href="#" class="booknow">BOOK NOW</a> 
        </div>
        <div class="menu-img">
            <img src="images/img20.jpg" alt="Image 1" onclick="changeBackground('images/resort3.png', this)">
            <img src="images/img20.jpg" alt="Image 2" onclick="changeBackground('images/img23.jpg', this)">
            <img src="images/img25.jpg" alt="Image 3" onclick="changeBackground('images/img25.jpg', this)">
        </div>
    </header>
