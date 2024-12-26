<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About Us</h3>
            <p>Leading healthcare provider committed to delivering excellence in medical services and patient care.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="/web_project/index.php">Home</a></li>
                <li><a href="/web_project/pages/services.php">Services</a></li>
                <li><a href="/web_project/pages/about.php">About Us</a></li>
                <li><a href="/web_project/pages/contact.php">Contact</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Contact Info</h3>
            <ul class="contact-info">
                <li><i class="fas fa-map-marker-alt"></i> 123 Hospital Street, City</li>
                <li><i class="fas fa-phone"></i> +1 234 567 8900</li>
                <li><i class="fas fa-envelope"></i> info@hospital.com</li>
                <li><i class="fas fa-clock"></i> Mon - Fri: 8:00 AM - 8:00 PM</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Hospital Management System. All Rights Reserved.</p>
    </div>

    <style>
    .site-footer {
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 50px 0 20px;
        font-family: 'Arial', sans-serif;
        text-align: left;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        padding: 0 20px;
    }

    .footer-section h3 {
        color: #3498db;
        margin-bottom: 20px;
        font-size: 1.2rem;
    }

    .footer-section ul {
        list-style: none;
        padding: 0;
    }

    .footer-section ul li {
        margin-bottom: 10px;
    }

    .footer-section a {
        color: #ecf0f1;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-section a:hover {
        color: #3498db;
    }

    .social-links {
        margin-top: 20px;
    }

    .social-links a {
        display: inline-block;
        width: 35px;
        height: 35px;
        background: #34495e;
        border-radius: 50%;
        text-align: center;
        line-height: 35px;
        margin-right: 10px;
        transition: background 0.3s ease;
    }

    .social-links a:hover {
        background: #3498db;
    }

    .contact-info li {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .contact-info i {
        color: #3498db;
        width: 20px;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #34495e;
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .contact-info li {
            justify-content: center;
        }

        .social-links {
            justify-content: center;
        }
    }
    </style>
</footer>