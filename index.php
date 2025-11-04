<?php require_once __DIR__ . '/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bites & Brew</title>
        <meta name="description" content="Experience the finest, Handcrafted beverages, fresh pastries, and a cozy atmosphere.">
        <?php if (isset($_SESSION['csrf_token'])) { echo '<meta name="csrf-token" content="'.htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES).'">'; } ?>


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

        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Artisan Coffee <br>& <br>Delightful Bites</h1>
                    <p class="hero-subtitle">Experience the perfect blend of rich flavors and cozy ambiance at Bites&Brew. Where every cup tells a story and every bite brings joy.</p>
                    <div class="hero-actions">
                        <a href="menu.php" class="secondary-button">View Menu</a>
                            <a href="about.php" class="primary-button">Learn More</a>
                    </div>
                </div>
                <div class="scroll-indicator">
                    <span></span>
                </div>
            </div>
            <div class="hero-overlay"></div>
        </section>

        <!-- About Section -->
    <section class="section about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Our Story</h2>
                    <p>Founded in 2015, Bites&Brew began as a small neighborhood café with a simple mission: to create a welcoming space where people could enjoy exceptional coffee and delicious snacks.</p>
                    <p>Over the years, we've grown into a beloved community hub, but our commitment to quality, sustainability, and warm hospitality remains unchanged.</p>
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
                        <div class="stats-container flex">
                            <div class="stat-item">
                                <div class="stat-number">10</div>
                                <div class="stat-label">
                                    <p>Years of Experience</p>

                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50+</div>
                                <div class="stat-label">
                                    <p>Expert Team Members</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Bites&Brew café interior">
                </div>
            </div>
        </div>
    </section>

        <!-- Order Online Section -->
        <section class="section order-online" id="order">
            <div class="container">
            <div class="section-title">
                <h2>Order Online</h2>
                <p>Enjoy our delicious offerings from the comfort of your home or office with our easy online ordering system.</p>
            </div>
            <div class="order-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Browse Menu</h3>
                    <p>Explore our selection of coffees, teas, and snacks from our digital menu.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Customize Order</h3>
                    <p>Select your preferences, add special instructions, and build your perfect order.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Checkout & Pay</h3>
                    <p>Securely pay online and choose between pickup or delivery options.</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Enjoy Your Order</h3>
                    <p>Pick up your order at our café or have it delivered to your location.</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 3rem;">
                <a href="menu.php" class="primary-button">Start Your Order</a>
            </div>
        </div>
        </section>

        <!-- Contact Section -->
    <section class="section" id="contact">
        <div class="container">
            <div class="section-title">
                <h2>Get in touch with us</h2>
                <p>We'd love to hear from you. Visit us, give us a call, or send us a message.</p>
            </div>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">📍</div>
                            <div>
                                <h4>Our Location</h4>
                                <p>123 Coffee Street, Brew District<br>Vaal, Vanderbijl 1939</p>
                                <div class="map-container" style="margin-top: 12px; width: 100%; height: 320px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e5e5;">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d639.5147798323384!2d27.86284299021355!3d-26.711520125460787!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e9459a70c417c1d%3A0xfc3fae7fa3b1f3f6!2sAmphi%20Theatre!5e1!3m2!1sen!2sza!4v1762252640840!5m2!1sen!2sza" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">📞</div>
                            <div>
                                <h4>Phone Number</h4>
                                <p>(123) 456-7890</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">✉</div>
                            <div>
                                <h4>Email Address</h4>
                                <p>hello@bitesandbrew.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">🕒</div>
                            <div>
                                <h4>Opening Hours</h4>
                                <p>Monday - Friday: 7am - 8pm<br>Saturday - Sunday: 8am - 9pm</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <a href="contact.php" class="primary-button" style="width: 100%; display: block; text-align: center;">Go to Contact Page</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
    </body>
</html>
