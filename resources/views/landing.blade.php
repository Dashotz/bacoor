<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>City Government of Bacoor</title>
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
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
        </div>
    </header>

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
                        <a class="btn primary" href="/login">SIGN IN</a>
                    </div>
                </div>
                <div class="hero-media">
                    <div class="hex-container">
                        <div class="hex-tile large">
                            <img src="{{ asset('images/landing/hero-1.jpg') }}" alt="Collaborative meeting" />
                        </div>
                        <div class="hex-tile small top-right">
                            <img src="{{ asset('images/landing/hero-2.jpg') }}" alt="Presentation" />
                        </div>
                        <div class="hex-tile small bottom-right">
                            <img src="{{ asset('images/landing/hero-3.jpg') }}" alt="Document discussion" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cards-section">
            <div class="container cards-grid">
                <article class="info-card">
                    <h3>City Treasurer's Office</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re p quod per se ipsum voluptatem mer.</p>
                    <a href="#services" class="card-cta">Learn more →</a>
                </article>
                <article class="info-card highlight">
                    <h3>City Assessor's Office</h3>
                    <p>Get Free Copy of Tax Declaration, Tax Mapping, Certification and more. Assessor and Appraisal services for local property.</p>
                    <a href="#services" class="card-cta">Learn more →</a>
                </article>
                <article class="info-card">
                    <h3>Mayor's Permit</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re p quod per se ipsum voluptatem mer.</p>
                    <a href="#services" class="card-cta">Learn more →</a>
                </article>
            </div>

            <div class="container cards-grid compact">
                <article class="info-card">
                    <h3>City Health Office</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re.</p>
                    <a href="#services" class="card-cta">Learn more →</a>
                </article>
                <article class="info-card">
                    <h3>City Zoning's Office</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis enim aut in ea re.</p>
                    <a href="#services" class="card-cta">Learn more →</a>
                </article>
            </div>
        </section>

        <section id="about" class="about-section">
            <div class="container about-grid">
                <div class="about-media">
                    <div class="video-placeholder">
                        <button class="play-btn" aria-label="Play video">▶</button>
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
            <div class="container gallery-grid">
                <div class="gallery-tile"></div>
                <div class="gallery-tile"></div>
                <div class="gallery-tile"></div>
                <div class="gallery-tile"></div>
            </div>
        </section>

        <section id="services" class="process-section">
            <div class="container process-grid">
                <article class="process-card">
                    <h3>Client Design Consultation</h3>
                    <p>Submit your request and our team will guide you with clear requirements and timelines.</p>
                </article>
                <article class="process-card">
                    <h3>Prototyping Home Design</h3>
                    <p>We validate your documents and keep you updated on progress with transparent steps.</p>
                </article>
                <article class="process-card">
                    <h3>Processing to Design Home</h3>
                    <p>Release results quickly and securely, with notifications sent to your email.</p>
                </article>
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
                    <div class="placeholder"></div>
                </div>
            </div>
        </section>

        <section class="testimonials-section">
            <div class="container">
                <span class="eyebrow">Citizens' feedback</span>
                <h2>Our testimonial from best clients</h2>
                <div class="testimonial">
                    <p>“Fast processing and very helpful staff. Being able to apply online saved me a lot of time.”</p>
                    <div class="author">— Jane D., Bacoor Resident</div>
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
                <h4>Information</h4>
                <p>Modern online services for Bacoor citizens. Quick processing, clear requirements, and secure results.</p>
                <div class="socials">
                    <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                </div>
            </div>
            <div class="footer-col">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Our Services</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="/login">Login</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul>
                    <li>Bacoor City, Cavite</li>
                    <li><a href="mailto:info@bacoor.gov">info@bacoor.gov</a></li>
                </ul>
                <form class="newsletter" action="#" onsubmit="return false">
                    <input type="email" placeholder="Enter your email" />
                    <button class="btn primary" type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="container footer-bottom">
            <p>© <span id="year"></span> City Government of Bacoor</p>
            <nav class="tiny-nav">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms</a>
            </nav>
        </div>
    </footer>
</body>
</html>


