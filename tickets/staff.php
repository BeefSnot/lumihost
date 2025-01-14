<!-- filepath: /C:/Users/James/Desktop/LumiHost/lumihost/tickets/staff.php -->
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Staff Tickets | Lumi Host</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
        header('Location: login.php');
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

    <section id="staff-tickets">
        <div class="container mt-5">
            <div class="section-title text-center">
                <h6>Staff Tickets</h6>
                <h4>Manage Tickets<span class="main">.</span></h4>
            </div>
            <div class="text-center mt-4">
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
            <div id="tickets-list" class="mt-4">
                <?php
                $conn = new mysqli('localhost', 'root', '', 'lumihost');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $result = $conn->query("SELECT tickets.id, tickets.subject, tickets.message, tickets.status, tickets.created_at, users.username FROM tickets JOIN users ON tickets.user_id = users.id");

                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card mb-3">';
                    echo '<div class="card-body" style="color: black;">';
                    echo '<h5 class="card-title">' . $row['subject'] . '</h5>';
                    echo '<p class="card-text"><strong>Submitted by:</strong> ' . $row['username'] . '</p>';
                    echo '<p class="card-text"><strong>Message:</strong> ' . $row['message'] . '</p>';
                    echo '<p class="card-text"><strong>Status:</strong> ' . $row['status'] . '</p>';
                    echo '<p class="card-text"><strong>Created at:</strong> ' . $row['created_at'] . '</p>';
                    echo '<form onsubmit="updateTicket(event, ' . $row['id'] . ')">';
                    echo '<div class="form-group">';
                    echo '<label for="status">Update Status</label>';
                    echo '<select class="form-control" id="status" name="status">';
                    echo '<option value="open"' . ($row['status'] == 'open' ? ' selected' : '') . '>Open</option>';
                    echo '<option value="in_progress"' . ($row['status'] == 'in_progress' ? ' selected' : '') . '>In Progress</option>';
                    echo '<option value="closed"' . ($row['status'] == 'closed' ? ' selected' : '') . '>Closed</option>';
                    echo '</select>';
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="reply">Reply</label>';
                    echo '<textarea class="form-control" id="reply" name="reply" rows="3"></textarea>';
                    echo '</div>';
                    echo '<button type="submit" class="btn btn-primary">Update</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }

                $conn->close();
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
                <h6 class="mb-0">Copyright © 2024 Lumi Host. | All Rights Reserved |</h6>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
        });

        function updateTicket(event, ticketId) {
            event.preventDefault();
            const form = event.target;
            const status = form.status.value;
            const reply = form.reply.value;

            fetch('update_ticket.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ticketId, status, reply })
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => {
                alert('Error updating ticket: ' + error);
            });
        }
    </script>
</body>

</html>