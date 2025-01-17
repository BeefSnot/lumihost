<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMI Host Status</title>
    <link rel="stylesheet" href="../assets/css/status.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="dark-theme">
    <header class="hero page">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img class="img-fluid" src="/assets/img/logonew.png" alt="Lumi Host">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../about.html">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../apply.html">Apply</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../staff.php">Staff Center</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../radio/index.php">Radio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../status/index.php">Status</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section id="status">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6>Service Status</h6>
                <h4>LUMI Host Status<span class="main">.</span></h4>
            </div>
            <div class="status-list text-center dark-background">
                <div class="status-item">
                    <h5><a href="details.php?service=website" class="status-link">Website</a></h5>
                    <p id="website-status">Loading...</p>
                </div>
                <div class="status-item">
                    <h5><a href="details.php?service=nameserver1" class="status-link">Nameserver 1</a></h5>
                    <p id="nameserver1-status">Loading...</p>
                </div>
                <div class="status-item">
                    <h5><a href="details.php?service=nameserver2" class="status-link">Nameserver 2</a></h5>
                    <p id="nameserver2-status">Loading...</p>
                </div>
                <div class="status-item">
                    <h5><a href="details.php?service=customer_database" class="status-link">Customer Database</a></h5>
                    <p id="customer_database-status">Loading...</p>
                </div>
                <div class="status-item">
                    <h5><a href="details.php?service=usa_node1" class="status-link">USA Node 1 (Tulsa OK)</a></h5>
                    <p id="usa_node1-status">Loading...</p>
                </div>
                <div class="status-item">
                    <h5><a href="details.php?service=lumi_radio" class="status-link">Lumi Radio</a></h5>
                    <p id="lumi_radio-status">Loading...</p>
                </div>
                <!-- Add more status items if needed!-->
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Navigation<span class="main">.</span></h5>
                        <ul>
                            <li><a href="../index.php">Home</a></li>
                            <li><a href="../about.html">About</a></li>
                            <li><a href="../apply.html">Apply</a></li>
                            <li><a href="../staff.php">Staff Center</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Login<span class="main">.</span></h5>
                        <ul>
                            <li><a href="https://lumihost.net">Game Panel (coming soon)</a></li>
                            <li><a href="https://webpanel.lumihost.net:2222">Web Panel</a></li>
                            <li><a href="https://billing.lumihost.net">Billing Panel</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-col py-4">
                        <h5>Legal<span class="main">.</span></h5>
                        <ul>
                            <li><a href="../tos.php">Terms of Service</a></li>
                            <li><a href="../pp.php">Privacy Policy</a></li>
                            <li><a href="../cookies.php">Cookie Policy</a></li>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
        });

        // JavaScript to fetch and display status data
        document.addEventListener("DOMContentLoaded", function() {
            fetchStatus();
        });

        function fetchStatus() {
            fetch('status.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('website-status').innerText = data.website ? 'Operational' : 'Down';
                    document.getElementById('nameserver1-status').innerText = data.nameserver1 ? 'Operational' : 'Down';
                    document.getElementById('nameserver2-status').innerText = data.nameserver2 ? 'Operational' : 'Down';
                    document.getElementById('customer_database-status').innerText = data.customer_database ? 'Operational' : 'Down';
                    document.getElementById('usa_node1-status').innerText = data.usa_node1 ? 'Operational' : 'Down';
                    document.getElementById('lumi_radio-status').innerText = data.lumi_radio ? 'Operational' : 'Down';
                })
                .catch(error => {
                    console.error('Error fetching status:', error);
                });
        }
    </script>
</body>

</html>