<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    header('Location: splash.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.0/dist/aos.css" />
    <title>Home | Lumi Host</title>

    <meta name="description" content="Lumi Host | LUMInate Your Digital World!">
    <meta name="keywords" content="keywords, Hosting, ONE, seperated, with, commas">
    <meta name="theme-color" content="#b31629">
    <meta name="author" content="beefsnot">
    <style>
        .cookie-alert {
            position: fixed;
            bottom: 15px;
            right: 15px;
            width: 320px;
            margin: 0 !important;
            z-index: 999;
            opacity: 0;
            border-radius: 8px;
            border: none;
            background-color: #151720; /* Ensure background color is set */
            color: #ffffff; /* Ensure text color is set */
            transform: translateY(100%);
            transition: all 500ms ease-out;
        }

        .cookie-alert.show {
            opacity: 1;
            transform: translateY(0%);
            transition-delay: 1000ms;
        }

        .cookie-alert .card-body {
            background-color: #151720; /* Ensure background color is set */
            color: #ffffff; /* Ensure text color is set */
        }

        .cookie-alert .btn-primary {
            background-color: #1592e8;
            border-color: #1592e8;
        }

        .cookie-alert .btn-primary:hover {
            background-color: #1487d3;
            border-color: #1487d3;
        }
    </style>
</head>

<body>
    <div class="card cookie-alert shadow">
        <div class="card-body">
            <h5 class="card-title">&#x1F36A; Do you like cookies?</h5>
            <p class="card-text">We use cookies to ensure you get the best experience on our website.</p>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="essentialCookies" checked disabled>
                <label class="form-check-label" for="essentialCookies">
                    Essential Cookies
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="performanceCookies">
                <label class="form-check-label" for="performanceCookies">
                    Performance Cookies
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="functionalCookies">
                <label class="form-check-label" for="functionalCookies">
                    Functional Cookies
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="targetingCookies">
                <label class="form-check-label" for="targetingCookies">
                    Targeting Cookies
                </label>
            </div>
            <div class="btn-toolbar justify-content-between mt-3">
                <a href="http://cookiesandyou.com/" target="_blank" class="btn btn-link">Learn more</a>
                <div>
                    <button class="btn btn-primary accept-selected-cookies">Accept Selected</button>
                    <button class="btn btn-primary accept-cookies ml-2">Accept All</button>
                </div>
            </div>
        </div>
    </div>
    <header class="hero">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="https://lumihost.net">
                    <img class="img-fluid" src="/assets/img/logonew.svg" alt="Lumi Host">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link " href="#">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Hosting
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="https://billing.lumihost.net/">Minecraft Hosting</a>
                                <a class="dropdown-item" href="https://lumihost.net/">Rust Hosting (coming soon)</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="https://billing.lumihost.net/web-hosting">Website Hosting</a>
                                <a class="dropdown-item" href="https://lumihost.net/">Dedicated Server Hosting (coming soon)</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://lumihost.net/tickets/tickets.php">Support (coming soon)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/status/index.php">Status</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link nav-btn dropdown-toggle">
                                login
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="https://gamepanel.lumihost.net">Game Panel</a>
                                <a class="dropdown-item" href="https://webpanel.lumihost.net:2222">Web Panel</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="https://billing.lumihost.net">Billing Panel</a>
                                <a class="dropdown-item" href="/staff.php">Staff Center </a>
                          </div>
                      </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-group" data-aos="fade-up" data-aos-delay="100" class="aos-init aos-animate">
                        <h1>Experience <span class="main">Premium<br> Hosting</span> with<br> Lumi<br> Host</h1>
                        <div class="button-group d-flex">
                            <a href="https://billing.lumihost.net" class="btn btn-primary mr-3">Order Now</a>
                            <a href="/about.html" class="btn btn-secondary">About Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1920.975" height="134.421" viewBox="0 0 1920.975 134.421">
            <defs>
                <linearGradient id="linear-gradient" x1="0.5" x2="0.5" y2="1" gradientUnits="objectBoundingBox">
                    <stop offset="0" stop-color="#151720" stop-opacity="0" />
                    <stop offset="1" stop-color="#151720" />
                </linearGradient>
            </defs>
            <path id="Path_870" data-name="Path 870" d="M1990,754.629s363.476-72.362,549.549-74.085,296.338,60.3,528.929,82.7,304.091-36.181,511.7-36.181,330.8,20.675,330.8,20.675V814.93H1990Z" transform="translate(-1990 -680.509)" fill="url(#linear-gradient)" />
        </svg>
    </header>

    <section id="features">
    <div class="container mt-5">
        <div class="section-title text-center" data-aos="fade-up" data-aos-delay="200" class="aos-init aos-animate">
            <h6>Features</h6>
            <h4>What makes us stand out from the rest<span class="main">?</span></h4>
        </div>
        <div class="row mt-3">

            <div class="col-md-4 mt-3" data-aos="fade-right" data-aos-delay="300" class="aos-init aos-animate">
                <div class="feature-box shadow">
                    <div class="d-flex">
                        <i class="fas fa-heartbeat"></i>
                        <h4 class="ml-2">99.99% Uptime SLA</h4>
                    </div>
                    <p>We prioritize your uptime. Our commitment is to provide the highest uptime in the industry, ensuring your services are always available.</p>
                </div>
            </div>

            <div class="col-md-4 mt-3" data-aos="fade-up" data-aos-delay="300" class="aos-init aos-animate">
                <div class="feature-box shadow">
                    <div class="d-flex">
                        <i class="fa-solid fa-headset"></i>
                        <h4 class="ml-2">24/7 Support</h4>
                    </div>
                    <p>Our dedicated support team is available around the clock to assist you. We strive to respond to your inquiries promptly and effectively.</p>
                </div>
            </div>

            <div class="col-md-4 mt-3" data-aos="fade-left" data-aos-delay="300" class="aos-init aos-animate">
                <div class="feature-box shadow">
                    <div class="d-flex">
                        <i class="fa-solid fa-bolt-lightning"></i>
                        <h4 class="ml-2">Fast Website Hosting</h4>
                    </div>
                    <p>Utilizing DirectAdmin for our web panel, we ensure optimized and high-performance web hosting services tailored to your needs.</p>
                </div>
            </div>

            <div class="col-md-4 mt-3" data-aos="fade-right" data-aos-delay="400" class="aos-init aos-animate">
                <div class="feature-box shadow">
                    <div class="d-flex">
                        <i class="fa-solid fa-server"></i>
                        <h4 class="ml-2">Robust Game Servers</h4>
                    </div>
                    <p>Our game servers are powered by top-tier processors, providing an unparalleled gaming experience. Try it yourself and feel the difference.</p>
                </div>
            </div>

            <div class="col-md-4 mt-3" data-aos="fade-up" data-aos-delay="400" class="aos-init aos-animate">
                <div class="feature-box shadow">
                    <div class="d-flex">
                        <i class="fa-regular fa-floppy-disk"></i>
                        <h4 class="ml-2">Backups When You Need Them</h4>
                    </div>
                    <p>We offer flexible backup solutions for your web hosting services. Schedule regular backups or initiate them on demand to ensure your data is always secure.</p>
                </div>
            </div>

            <div class="col-md-4 mt-3" data-aos="fade-left" data-aos-delay="400" class="aos-init aos-animate">
                <div class="feature-box shadow">
                    <div class="d-flex">
                        <i class="fas fa-shield-alt"></i>
                        <h4 class="ml-2">Advanced Security Features</h4>
                    </div>
                    <p>We are committed to protecting your data with advanced security features. Our upcoming security enhancements will provide even greater protection against threats.</p>
                </div>
            </div>

        </div>
    </div>
</section>

    <section class="pb-5" id="reviews">
        <div class="container mt-5">
            <div class="section-title text-center" data-aos="fade-up" data-aos-delay="200">
                <h6>REVIEWS</h6>
                <h4>Don't just take our word for it<span class="main">.</span></h4>
            </div>
            <div class="swiper-container" data-aos="fade-up" data-aos-delay="300">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="review-card text-center">
                            <h4 class="mb-0">Name</h4>
                            <span>Kimmy</span>
                            <p class="mb-0 mt-4">Lumi Host really helped all my needs! I needed fast and reliable web hosting! Thanks Lumi Host!</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="review-card text-center">
                            <h4 class="mb-0">Name</h4>
                            <span>Ethan</span>
                            <p class="mb-0 mt-4">I needed a website for my small business, and Lumi Host had what I needed! I got lightning fast web hosting, with amazing uptime and protection!</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="review-card text-center">
                            <h4 class="mb-0">Name</h4>
                            <span>Titus</span>
                            <p class="mb-0 mt-4">I was in the market for a website for my wig company! Lumi Host was the best choice for me! I get backups every night so I know my data is safe!</p>
                        </div>
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <section class="pb-5" id="services">
        <div class="container mt-5">
            <div class="section-title text-center" data-aos="fade-up" data-aos-delay="200" class="aos-init aos-animate shadow">
                <h6>SERVICES</h6>
                <h4>See what we have to offer<span class="main">.</span></h4>
            </div>

            <div class="row mt-3">

                <div class="col-md-4 mt-3" data-aos="fade-right" data-aos-delay="300" class="aos-init aos-animate">
                    <div class="service-box shadow">
                        <div class="d-flex">
                            <i class="fas fa-gamepad"></i>
                            <h4 class="ml-2">Game Hosting</h4>
                        </div>
                        <p><span class="main"><i class="fas fa-check"></i></span> Powered By Ryzen Processors</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> Pterodactyl Control Panel</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> DDoS Protected</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> Dedicated Support</p>
                        <a href="https://billing.lumihost.net" class="btn btn-primary w-100">ORDER NOW</a>
                    </div>
                </div>

                <div class="col-md-4 mt-3" data-aos="fade-up" data-aos-delay="300" class="aos-init aos-animate">
                    <div class="service-box shadow">
                        <div class="d-flex">
                            <i class="fas fa-cloud"></i>
                            <h4 class="ml-2">Website Hosting</h4>
                        </div>
                        <p><span class="main"><i class="fas fa-check"></i></span> Powered By Xeon & AMD Processors</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> DirectAdmin Panel</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> DDoS Protected</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> Dedicated Support</p>
                        <a href="https://billing.lumihost.net/web-hosting" class="btn btn-primary w-100">ORDER NOW</a>
                    </div>
                </div>

                <div class="col-md-4 mt-3" data-aos="fade-left" data-aos-delay="300" class="aos-init aos-animate">
                    <div class="service-box shadow">
                        <div class="d-flex">
                            <i class="fas fa-server"></i>
                            <h4 class="ml-2">Dedicated Hosting</h4>
                        </div>
                        <p><span class="main"><i class="fas fa-check"></i></span> Ryzen & Intel Servers</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> Custom Control Panel</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> DDoS Protected</p>
                        <p><span class="main"><i class="fas fa-check"></i></span> Dedicated Support</p>
                        <a href="https://lumihost.net" class="btn btn-primary w-100">COMING SOON</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Subscribe to Newsletter Section -->
    <section id="subscribe" class="pb-5">
        <div class="container mt-5">
            <div class="section-title text-center" data-aos="fade-up" data-aos-delay="200">
                <h6>SUBSCRIBE</h6>
                <h4>Subscribe to our Newsletter<span class="main">.</span></h4>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <form action="newsletter/subscribe.php" method="POST">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="group">Select Group:</label>
                            <select class="form-control" id="group" name="group" required>
                                <option value="">Select a group</option>
                                <?php
                                // Fetch available groups
                                $groupsResult = $db->query("SELECT id, name FROM groups");
                                if ($groupsResult === false) {
                                    die('Database query failed: ' . $db->error);
                                }
                                while ($row = $groupsResult->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="btn btn-primary" title="Go to top">
        <i class="fas fa-server"></i>
    </button>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Navigation<span class="main">.</span></h5>
                        <ul>
                            <li><a href="https://lumihost.net">Home</a></li>
                            <li><a href="https://lumihost.net">About</a></li>
                            <li><a href="https://billing.lumihost.net/">Hosting</a></li>
                            <li><a href="https://lumihost.net/tickets/tickets.php">Support</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Login<span class="main">.</span></h5>
                        <ul>
                            <li><a href="https://gamepanel.lumihost.net">Game Panel</a></li>
                            <li><a href="https://webpanel.lumihost.net:2222">Web Panel</a></li>
                            <li><a href="https://billing.lumihost.net">Billing Panel</a></li>
                            <li><a href="/staff.php">Staff Center</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Legal<span class="main">.</span></h5>
                        <ul>
                            <li><a href="/tos.php">Terms of Service</a></li>
                            <li><a href="/pp.php">Privacy Policy</a></li>
                            <li><a href="/cookies.php">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <h6 class="mb-0">Copyright © 2025 Lumi Host. | All Rights Reserved |</h6>
            </div>
        </div>
    </footer>


    <script>
        document.querySelector('.accept-selected-cookies').addEventListener('click', function() {
            if (document.getElementById('performanceCookies').checked) {
                setCookie('performanceCookies', true, 60);
            }
            if (document.getElementById('functionalCookies').checked) {
                setCookie('functionalCookies', true, 60);
            }
            if (document.getElementById('targetingCookies').checked) {
                setCookie('targetingCookies', true, 60);
            }
            setCookie('acceptCookies', true, 60);
            document.querySelector('.cookie-alert').classList.remove('show');
        });

        document.querySelector('.accept-cookies').addEventListener('click', function() {
            setCookie('performanceCookies', true, 60);
            setCookie('functionalCookies', true, 60);
            setCookie('targetingCookies', true, 60);
            setCookie('acceptCookies', true, 60);
            document.querySelector('.cookie-alert').classList.remove('show');
        });

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        if (!getCookie("acceptCookies")) {
            document.querySelector('.cookie-alert').classList.add('show');
        }
    </script>
    <script src="assets/js/jquery.slim.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/aos.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.swiper-container', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                },
            });
        });

        AOS.init({
            duration: 1200,
        });

        // Back to Top Button
        const backToTopButton = document.getElementById('back-to-top');

        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        }

        backToTopButton.addEventListener('click', function() {
            $('html, body').animate({scrollTop: 0}, 'slow');
            // Animation of server powering on
            backToTopButton.innerHTML = '<i class="fas fa-server"></i>';
            setTimeout(() => {
                backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
            }, 1000); // Reset icon after 1 second
        });
    </script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/6785b98daf5bfec1dbeb17a9/1ihh5pjpu';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
<!--End of Tawk.to Script-->
</body>

</html>