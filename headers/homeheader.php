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