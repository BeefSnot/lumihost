<!-- filepath: /C:/Users/James/Desktop/LumiHost/lumihost/job_applications.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.0/dist/aos.css" />
    <title>Job Applications | Lumi Host</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.html');
        exit;
    }
    ?>
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
                            <a class="nav-link " href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="apply.html">Apply</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section id="job-applications">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6>Manage Applications</h6>
                <h4>Job Applications<span class="main">.</span></h4>
            </div>
            <div class="text-center mt-4">
                <a href="admin.php" class="btn btn-primary">Return to Admin Panel</a>
            </div>
            <div id="applications-list" class="mt-4">
                <?php
                $applications_file = 'applications.json';
                $applications = [];

                if (file_exists($applications_file)) {
                    $applications = json_decode(file_get_contents($applications_file), true);
                }

                foreach ($applications as $application) {
                    echo '<div class="card mb-3">';
                    echo '<div class="card-body" style="color: black;">';
                    echo '<h5 class="card-title">' . $application['name'] . '</h5>';
                    echo '<p class="card-text"><strong>Email:</strong> ' . $application['email'] . '</p>';
                    echo '<p class="card-text"><strong>Phone:</strong> ' . $application['phone'] . '</p>';
                    echo '<p class="card-text"><strong>Position:</strong> ' . $application['position'] . '</p>';
                    echo '<p class="card-text"><strong>Status:</strong> ' . $application['status'] . '</p>';
                    if ($application['resume']) {
                        echo '<p class="card-text"><strong>Resume:</strong> <a href="' . $application['resume'] . '" target="_blank">Download</a></p>';
                    }
                    echo '<form onsubmit="updateStatus(event, \'' . $application['email'] . '\')">';
                    echo '<div class="form-group">';
                    echo '<label for="status">Update Status</label>';
                    echo '<select class="form-control" id="status" name="status">';
                    echo '<option value="Pending"' . ($application['status'] == 'Pending' ? ' selected' : '') . '>Pending</option>';
                    echo '<option value="Approved"' . ($application['status'] == 'Approved' ? ' selected' : '') . '>Approved</option>';
                    echo '<option value="Denied"' . ($application['status'] == 'Denied' ? ' selected' : '') . '>Denied</option>';
                    echo '</select>';
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="comment">Comment</label>';
                    echo '<textarea class="form-control" id="comment" name="comment" rows="3">' . (isset($application['comment']) ? $application['comment'] : '') . '</textarea>';
                    echo '</div>';
                    echo '<button type="submit" class="btn btn-primary">Update</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
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
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="apply.html">Apply</a></li>
                            <li><a href="admin.php">Admin</a></li>
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
                            <li><a href="tos.php">Terms of Service</a></li>
                            <li><a href="pp.php">Privacy Policy</a></li>
                            <li><a href="cookies.php">Cookie Policy</a></li>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
        });

        function updateStatus(event, email) {
            event.preventDefault();
            const form = event.target;
            const status = form.status.value;
            const comment = form.comment.value;

            fetch('update_application.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, status, comment })
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => {
                alert('Error updating application: ' + error);
            });
        }
    </script>
</body>

</html>