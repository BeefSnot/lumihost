<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMI Host Status</title>
    <link rel="stylesheet" href="../assets/css/status.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        .status-title-box {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin: 20px auto;
            text-align: center;
        }
    </style>
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
                            <a class="nav-link" href="index.php">Status</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary" href="admin_login.php">Status Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section id="status">
        <div class="container mt-5">
            <div class="text-center">
                <div class="status-title-box">
                    <h6>Service Status</h6>
                </div>
            </div>
            <div class="section-title text-center">
                <h4>LUMI Host Status<span class="main">.</span></h4>
                <h5 id="average-uptime">7 Day Average Uptime: Loading...</h5>
            </div>
            <div class="status-list text-center dark-background" id="status-list">
                <!-- Status items will be dynamically inserted here -->
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
                            <li><a href="https://gamepanel.lumihost.net">Game Panel</a></li>
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

    <script src="assets/js/jquery.slim.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="assets/js/aos.js"></script>    <script>
        AOS.init({
            duration: 1200,
        });

        // JavaScript to fetch and display status data
        document.addEventListener("DOMContentLoaded", function() {
            fetchStatus();
            setInterval(fetchStatus, 30000); // Update every 30 seconds
        });

        function fetchStatus() {
            fetch('status.php')
                .then(response => response.json())
                .then(data => {
                    const statusList = document.getElementById('status-list');
                    statusList.innerHTML = ''; // Clear existing items

                    for (const [service, details] of Object.entries(data.status)) {
                        const statusItem = document.createElement('div');
                        statusItem.classList.add('status-item');
                        statusItem.innerHTML = `
                            <h5><a href="details.php?service=${service}" class="status-link">${service}</a></h5>
                            <p>${details.status ? 'Operational' : 'Down'}</p>
                        `;
                        statusList.appendChild(statusItem);
                    }

                    document.getElementById('average-uptime').innerText = '7 Day Average Uptime: ' + data.averageUptime + '%';
                })
                .catch(error => {
                    console.error('Error fetching status:', error);
                });
        }
    </script>
</body>

</html>