<?php require_once __DIR__ . '/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Bite & Brew</title>
    <meta name="description" content="Get in touch with Bite&Brew. Visit us, call, or send us a message.">
    <?php if (isset($_SESSION['csrf_token'])) { echo '<meta name="csrf-token" content="'.htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES).'">'; } ?>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/contact.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main style="padding-top: 100px;">
        <section class="section" id="contact">
            <div class="container">
                <div class="section-title">
                    <h2>Contact Us</h2>
                    <p>We'd love to hear from you. Visit us, give us a call, or send us a message.</p>
                </div>
                <div class="contact-content">
                    <div class="contact-info">
                        <h3>Get in Touch</h3>
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
                                    <p>hello@biteandbrew.com</p>
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

                        <div class="social-section">
                            <h4>Follow Us</h4>
                            <div class="social-links">
                                <a href="https://www.facebook.com/" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                <a href="https://x.com/" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form-wrapper">
                        <h3>Send us a Message</h3>
                        <form id="contactForm" class="contact-form">
                            <div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">
                                <label for="website">Leave this field empty</label>
                                <input type="text" id="website" name="website" autocomplete="off" tabindex="-1">
                            </div>
                            <div class="form-group">
                                <label for="name">Your Name *</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                                <span class="error-message" id="nameError"></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                                <span class="error-message" id="emailError"></span>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <input type="text" id="subject" name="subject" class="form-control" required>
                                <span class="error-message" id="subjectError"></span>
                            </div>
                            <div class="form-group">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message" class="form-control" rows="6" required></textarea>
                                <span class="error-message" id="messageError"></span>
                            </div>
                            <button type="submit" class="primary-button submit-btn">
                                <span class="btn-text">Send Message</span>
                                <span class="btn-loader" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Sending...
                                </span>
                            </button>
                        </form>
                        <div id="formMessage" class="form-message"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/contact.js"></script>
</body>
</html>
