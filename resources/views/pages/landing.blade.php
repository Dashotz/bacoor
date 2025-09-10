<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>City Government of Bacoor</title>
    @vite(['resources/css/app.css', 'resources/css/pages/landing.css', 'resources/js/core/app.js', 'resources/js/pages/landing.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#0a3b7a" />
    <style>body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f5f8fc;color:#0a2540}</style>
</head>
<body>
    <header class="landing-header">
        <div class="header-grid">
            <div class="brand">
                <img src="{{ asset('images/bacoor-logo.png') }}" class="brand-logo" alt="Bacoor City" />
                <span class="brand-title">BACOOR CITY EGOV™</span>
            </div>
            <nav class="main-menu" aria-label="Primary">
                <a class="menu-link" href="#home">HOME</a>
                <a class="menu-link" href="#about">ABOUT US</a>
                <a class="menu-link" href="#services">OUR SERVICES</a>
                <a class="menu-link" href="#contact">CONTACT US</a>
            </nav>
            <div class="auth-cta">
                <a class="menu-link login" href="/login">LOGIN</a>
            </div>
            <button class="burger-btn" id="burger-btn" aria-label="Toggle mobile menu">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
        </div>
    </header>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/bacoor-logo.png') }}" class="sidebar-logo" alt="Bacoor City" />
            <span class="sidebar-title">BACOOR CITY EGOV™</span>
            <button class="sidebar-close" id="sidebar-close">&times;</button>
        </div>
        <nav class="sidebar-nav">
            <a class="sidebar-link" href="#home">HOME</a>
            <a class="sidebar-link" href="#about">ABOUT US</a>
            <a class="sidebar-link" href="#services">OUR SERVICES</a>
            <a class="sidebar-link" href="#contact">CONTACT US</a>
            <a class="sidebar-link sidebar-login" href="/login">LOGIN</a>
        </nav>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <main>
        <section id="home" class="hero-section">
            <div class="container hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow">WELCOME TO</p>
                                         <h1 class="hero-title">
                         <span class="title-city">CITY</span><span class="title-assessors"> ASSESSOR'S</span>
                         <span class="title-office">OFFICE</span>
                     </h1>
                                         <div class="hero-actions">
                         <a class="btn register-btn" href="/register">REGISTER HERE!</a>
                     </div>
                </div>
                <div class="hero-media">
                    <div class="image-cluster"></div>
                        <div class="image-large">
                            <img src="{{ asset('images/landing/hero-1.jpg') }}" alt="Collaborative meeting" />
                        </div>
                        <div class="image-small top">
                            <img src="{{ asset('images/landing/hero-2.jpg') }}" alt="Presentation" />
                        </div>
                        <div class="image-small bottom">
                            <img src="{{ asset('images/landing/hero-3.jpg') }}" alt="Document discussion" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cards-section">
            <div class="container">
                <h2>OUR SERVICES</h2>
                <div class="cards-grid">
                <article class="info-card">
                    <h3>City Treasurer's Office</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re p quod per se ipsum voluptatem mer.</p>
                    <a href="#services" class="card-cta">Learn more</a>
                </article>
                                 <article class="info-card">
                     <h3>City Assessor's Office</h3>
                     <p>Get Free Copy of Tax Declaration, Tax Mapping, Certification and more. Assessor and Appraisal services for local property.</p>
                     <a href="#services" class="card-cta">Learn more</a>
                 </article>
                <article class="info-card">
                    <h3>Mayor's Permit</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re p quod per se ipsum voluptatem mer.</p>
                    <a href="#services" class="card-cta">Learn more</a>
                </article>
            </div>

            <div class="cards-grid compact">
                <article class="info-card">
                    <h3>City Health Office</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re.</p>
                    <a href="#services" class="card-cta">Learn more</a>
                </article>
                <article class="info-card">
                    <h3>City Zoning's Office</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re.</p>
                    <a href="#services" class="card-cta">Learn more</a>
                </article>
            </div>
        </section>

        <section id="about" class="about-section">
            <div class="container about-grid">
                <div class="about-media">
                    <div class="video-container">
                        <video controls class="about-video" poster="{{ asset('images/vid-thumb.png') }}">
                            <source src="{{ asset('videos/bacoor-video.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                <div class="about-copy">
                    <span class="eyebrow">Who we are</span>
                    <h2>We are perfect team for public service</h2>
                    <p>We provide a wide range of convenient, transparent, and citizen‑centric services. From certifications to permits, apply online and track your requests.</p>
                    <ul class="bullets">
                        <li>Flexible time</li>
                        <li>Client priority</li>
                        <li>Best team</li>
                        <li>Secure processing</li>
                    </ul>
                    <div class="stats">
                        <div><strong>15Y</strong><span>Experience</span></div>
                        <div><strong>25+</strong><span>Best team</span></div>
                        <div><strong>500+</strong><span>Served</span></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="gallery-section">
            <div class="container">
                <h2>OUR GALLERY</h2>
                <div class="carousel-container">
                    <div class="carousel-track">
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-1.jpg') }}" alt="Bacoor City Office" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-2.jpg') }}" alt="City Services" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-3.jpg') }}" alt="Public Service" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-4.jpg') }}" alt="Community" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-5.jpg') }}" alt="City Hall" />
                        </div>
                        <!-- Duplicate images for seamless scrolling -->
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-1.jpg') }}" alt="Bacoor City Office" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-2.jpg') }}" alt="City Services" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-3.jpg') }}" alt="Public Service" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-4.jpg') }}" alt="Community" />
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('images/gallery/gallery-5.jpg') }}" alt="City Hall" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="process-section">
            <div class="container">
                <h2>OUR PROCESS</h2>
                <div class="process-grid">
                <article class="process-card">
                    <div class="process-header">
                        <div class="process-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>Client Design Consultation</h3>
                    </div>
                    <p>Submit your request and our team will guide you with clear requirements and timelines.</p>
                </article>
                <article class="process-card">
                    <div class="process-header">
                        <div class="process-icon">
                            <i class="fas fa-drafting-compass"></i>
                        </div>
                        <h3>Prototyping Home Design</h3>
                    </div>
                    <p>We validate your documents and keep you updated on progress with transparent steps.</p>
                </article>
                <article class="process-card">
                    <div class="process-header">
                        <div class="process-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3>Processing to Design Home</h3>
                    </div>
                    <p>Release results quickly and securely, with notifications sent to your email.</p>
                </article>
                </div>
            </div>
        </section>

        <section class="partner-section">
            <div class="container partner-grid">
                <div class="partner-copy">
                    <span class="eyebrow">Perfect partner</span>
                    <h2>We have priority for citizen‑first service</h2>
                    <p>Simple, reliable, and secure interactions to help you get things done without hassle.</p>
                    <a href="#contact" class="btn secondary">Read more</a>
                </div>
                <div class="partner-media">
                    <div class="placeholder placeholder-1">
                        <img src="{{ asset('images/gallery/gallery-1.jpg') }}" alt="Bacoor City Services" />
                    </div>
                    <div class="placeholder placeholder-2">
                        <img src="{{ asset('images/gallery/gallery-2.jpg') }}" alt="City Government" />
                    </div>
                    <div class="placeholder placeholder-3">
                        <img src="{{ asset('images/gallery/gallery-3.jpg') }}" alt="Public Service" />
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonials-section">
            <div class="container testimonials-grid">
                <div class="testimonials-copy">
                    <span class="eyebrow">Clients feedback</span>
                    <h2>Our testimonial from<br>best clients</h2>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                </div>
                <div class="testimonial-card">
                    <div class="star-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="testimonial-text">"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo."</p>
                    <div class="author-info">
                        <div class="author-avatar"></div>
                        <div class="author-details">
                            <div class="author-name">JOHN DE</div>
                            <div class="author-title">DEPARTMENT HEAD</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-banner">
            <div class="container cta-flex">
                <h2>Lets change your own home life</h2>
                <a href="#contact" class="btn primary">Contact us</a>
            </div>
        </section>
    </main>

    <footer id="contact" class="site-footer">
        <div class="container footer-grid">
            <div class="footer-col">
                <h4>INFORMATION</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
                <div class="socials">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>NAVIGATION</h4>
                <ul>
                    <li><a href="#home"><i class="fas fa-chevron-right"></i> Homepage</a></li>
                    <li><a href="#about"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="#services"><i class="fas fa-chevron-right"></i> Our Services</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>CONTACT US</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Bacoor City, Cavite</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>Hello@example.com</span>
                    </div>
                </div>
                <form class="newsletter" action="#" onsubmit="return false">
                    <input type="email" placeholder="Email Address" />
                    <button class="btn subscribe-btn" type="submit">SUBSCRIBE</button>
                </form>
            </div>
        </div>
        <div class="footer-divider"></div>
        <div class="container footer-bottom">
            <p>ALLRIGHT RESERVED - 2025 @</p>
            <nav class="tiny-nav">
                <a href="#">DISCLAIMER</a>
                <a href="#">PRIVACY POLICY</a>
                <a href="#">TERM OF USE</a>
            </nav>
        </div>
    </footer>
</body>
</html>


