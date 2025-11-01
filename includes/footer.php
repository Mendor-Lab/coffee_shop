<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-widget">
                <div class="footer-logo">
                    <img src="assets/public/icons/bites&brew_logo.svg" alt="Bite&Brew Logo">
                    <h3>Bites&Brew</h3>
                </div>
                <p class="footer-about">Serving the finest coffee and homemade pastries in a cozy atmosphere since 2015.</p>
                <div class="social-links">
                     <a href="https://www.facebook.com/" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                     <a href="https://www.instagram.com/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                     <a href="https://x.com/" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <div class="footer-widget">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="menu.php" class="nav-link">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.php" class="nav-link">Contact</a>
                    </li>
                </ul>
            </div>

            <div class="footer-widget">
                <h4>Contact Info</h4>
                <ul class="contact-info">
                    <li><i class="fas fa-map-marker-alt"></i> 123 Coffee Street, Vaal, Vanderbijl 1939</li>
                    <li><i class="fas fa-phone"></i> (123) 456-7890</li>
                    <li><i class="fas fa-envelope"></i> hello@bitesandbrew.com</li>
                    <li><i class="fas fa-clock"></i> Mon-Fri: 7am-8pm | Sat-Sun: 8am-9pm</li>
                </ul>
            </div>

            <div class="footer-widget">
                <h4>Newsletter</h4>
                <p>Subscribe to our newsletter for the latest updates and offers.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); this.querySelector('button').textContent = 'Subscribed!'">
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <span id="current-year"></span> Bites&Brew. All rights reserved.</p>
            <div class="footer-legal">
                <a href="privacy-policy.php">Privacy Policy</a>
                <span>|</span>
                <a href="terms-of-service.php">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script>
    document.getElementById('current-year').textContent = new Date().getFullYear();
</script>
