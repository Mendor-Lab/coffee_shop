<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Bite & Brew</title>
    <meta name="description" content="Learn about Bite&Brew's story, mission, and the passionate team behind our artisan coffee and delightful bites.">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main style="padding-top: 100px;">
        <section class="section about" id="about">
            <div class="container">
                <div class="section-title">
                    <h2>About Us</h2>
                    <p>Your neighborhood coffee shop where every cup tells a story</p>
                </div>
                <div class="about-content">
                    <div class="about-text">
                        <h2>Our Story</h2>
                        <p>Bites and Brew began with a simple idea — to create a cozy place where people can enjoy freshly brewed coffee and delicious handmade bites. Founded in 2024, our goal is to serve quality, warmth, and comfort in every cup and every bite.</p>
                        <p>Over the years, we've grown into a beloved community hub, but our commitment to quality, sustainability, and warm hospitality remains unchanged.</p>

                        <p>Our journey started with a small coffee shop, and we've since expanded to include a bakery, a café, and a restaurant. We're proud to serve our community with the best coffee and pastries, and we're always looking for new ways to improve our service and offerings.</p>

                        <p>We use premium coffee beans, handpicked ingredients, and bake our pastries fresh every morning. Whether you're studying, relaxing, or meeting friends — this is your place.</p>
                        <h3 style="margin-top: 2rem;">Our Mission</h3>
                        <p>We strive to provide exceptional service, delicious pastries, and a welcoming space for everyone. Whether you're here to work, relax, or catch up with friends, Bites and Brew is your home away from home.</p>

                        

                        <div class="about-features">
                            <div class="feature">
                                <div class="feature-icon">✓</div>
                                <div class="feature-text">
                                    <h4>Premium Ingredients</h4>
                                    <p>We source only the finest beans and ingredients.</p>
                                </div>
                            </div>
                            <div class="feature">
                                <div class="feature-icon">♻</div>
                                <div class="feature-text">
                                    <h4>Sustainable Practices</h4>
                                    <p>Committed to eco-friendly operations and packaging.</p>
                                </div>
                            </div>
                            <div class="feature">
                                <div class="feature-icon">👨‍🍳</div>
                                <div class="feature-text">
                                    <h4>Expert Baristas</h4>
                                    <p>Our team is trained in the art of coffee making.</p>
                                </div>
                            </div>
                            <div class="feature">
                                <div class="feature-icon">❤</div>
                                <div class="feature-text">
                                    <h4>Community Focused</h4>
                                    <p>We support local artists and community events.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="about-image">
                        <img src="assets/public/images/inside store.JPG" alt="Bites and Brew café interior">
                    </div>
                </div>
            </div>
        </section>
        
        <section class="section team" id="team">
    <div class="container">
        <div class="section-title">
            <h2>Meet the Team</h2>
            <p>The passionate people behind your favorite coffee shop</p>
        </div>
        
        <div class="team-container">
            <div class="team-card">
                <div class="team-image-wrapper">
                    <img src="assets/public/images/thabo.jpg" alt="Team Member" class="team-photo">
                    <div class="team-overlay">
                        <p class="thank-you-message">Thank you for being an amazing customer!</p>
                    </div>
                </div>
                <div class="team-card-content">
                    <h3>Thabo Mokoena</h3>
                    <span class="role">Founder & Head Barista</span>
                    <p>Passionate about handcrafted coffee and building a community space for all coffee lovers.</p>
                </div>
            </div>
            
            <div class="team-card">
                <div class="team-image-wrapper">
                    <img src="assets/public/images/lele.jpg" alt="Team Member" class="team-photo">
                    <div class="team-overlay">
                        <p class="thank-you-message">We appreciate your support!</p>
                    </div>
                </div>
                <div class="team-card-content">
                    <h3>Lele Smith</h3>
                    <span class="role">Pastry Chef</span>
                    <p>Creates fresh muffins, croissants, and sweet treats every morning with love.</p>
                </div>
            </div>
            
            <div class="team-card">
                <div class="team-image-wrapper">
                    <img src="assets/public/images/michael.jpg" alt="Team Member" class="team-photo">
                    <div class="team-overlay">
                        <p class="thank-you-message">Your smile makes our day!</p>
                    </div>
                </div>
                <div class="team-card-content">
                    <h3>Michael Jacobs</h3>
                    <span class="role">Store Manager</span>
                    <p>Keeps everything running smoothly and ensures customer service is always top-notch.</p>
                </div>
            </div>
        </div>
    </div>
</section>


    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
</body>
</html>
