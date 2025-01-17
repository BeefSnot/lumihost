<?php
$service = $_GET['service'] ?? 'unknown';

function ping($host, $port, $timeout) {
    $starttime = microtime(true);
    $fsock = @fsockopen($host, $port, $errno, $errstr, $timeout);
    $stoptime = microtime(true);
    $status = 0;

    if (!$fsock) {
        $status = -1;  // Site is down
    } else {
        fclose($fsock);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }

    return $status;
}

$services = [
    'website' => ['host' => 'lumihost.net', 'port' => 80],
    'nameserver1' => ['host' => 'ns1.lumihost.net', 'port' => 53],
    'nameserver2' => ['host' => 'ns2.lumihost.net', 'port' => 53],
    'customer_database' => ['host' => 'webpanel.lumihost.net', 'port' => 3306],
    'usa_node1' => ['host' => 'radio.lumihost.net', 'port' => 80],
    // Add more services as needed
];

$ping = -1;
if (array_key_exists($service, $services)) {
    $ping = ping($services[$service]['host'], $services[$service]['port'], 10);
}

$uptime = 99.99; // Placeholder for uptime percentage, replace with actual calculation if available

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($service); ?> Status</title>
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

    <section id="status-details">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6><?php echo ucfirst($service); ?> Status</h6>
                <h4><?php echo ucfirst($service); ?> Details<span class="main">.</span></h4>
            </div>
            <div class="status-details text-center dark-background">
                <p>Uptime: <?php echo $uptime; ?>%</p>
                <p>Ping: <?php echo $ping >= 0 ? $ping . ' ms' : 'Down'; ?></p>
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
    </script>
</body>

</html>