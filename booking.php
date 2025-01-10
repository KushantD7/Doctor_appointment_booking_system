<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Sessions</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if (isset($_SESSION["user"])) {
        if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
            exit;
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
        exit;
    }

    // Import database connection
    include("../connection.php");

    // Fetch user data
    $sqlmain = "SELECT * FROM patient WHERE pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s", $useremail);
    $stmt->execute();
    $result = $stmt->get_result();
    $userfetch = $result->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];

    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13); ?>...</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
            </table>
        </div>

        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0; margin: 0; padding: 0; margin-top: 25px;">
                <tr>
                    <td width="13%">
                        <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding: 10px; margin-left: 20px; width: 125px;">Back</button></a>
                    </td>
                    <td colspan="3">
                        <?php
                        if ($_GET) {
                            if (isset($_GET["id"])) {
                                $id = $_GET["id"];
                                $sqlmain = "SELECT * FROM schedule INNER JOIN doctor ON schedule.docid=doctor.docid WHERE schedule.scheduleid=? ORDER BY schedule.scheduledate DESC";
                                $stmt = $database->prepare($sqlmain);
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();

                                $scheduleid = $row["scheduleid"];
                                $title = $row["title"];
                                $docname = $row["docname"];
                                $docemail = $row["docemail"];
                                $scheduledate = $row["scheduledate"];
                                $scheduletime = $row["scheduletime"];

                                $sql2 = "SELECT * FROM appointment WHERE scheduleid=?";
                                $stmt2 = $database->prepare($sql2);
                                $stmt2->bind_param("i", $id);
                                $stmt2->execute();
                                $result12 = $stmt2->get_result();
                                $apponum = ($result12->num_rows) + 1;

                                echo '
                                <form action="booking-complete.php" method="post">
                                    <input type="hidden" name="scheduleid" value="' . $scheduleid . '">
                                    <input type="hidden" name="apponum" value="' . $apponum . '">
                                    <input type="hidden" name="date" value="' . $today . '">
                                    <div class="abc scroll">
                                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px; border: none;">
                                            <tr>
                                                <td style="width: 50%;" rowspan="2">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%">
                                                            <div class="h1-search" style="font-size:25px;">Session Details</div><br><br>
                                                            <div class="h3-search" style="font-size:18px; line-height:30px;">
                                                                Doctor Name: <b>' . $docname . '</b><br>
                                                                Doctor Email: <b>' . $docemail . '</b><br>
                                                                Session Title: <b>' . $title . '</b><br>
                                                                Date: <b>' . $scheduledate . '</b><br>
                                                                Time: <b>' . $scheduletime . '</b><br>
                                                                Fee: <b>LKR. 2,000.00</b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%; padding: 15px;">
                                                            <div class="h1-search" style="font-size:20px; line-height:35px; margin-left:8px; text-align:center;">
                                                                Appointment Number
                                                            </div>
                                                            <center>
                                                                <div class="dashboard-icons" style="font-size:70px; font-weight:800; text-align:center; color:var(--btnnictext); background-color:var(--btnice);">
                                                                    ' . $apponum . '
                                                                </div>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <input type="submit" class="login-btn btn-primary btn btn-book" style="padding: 10px 25px; width: 95%; text-align: center;" value="Book Now" name="booknow">
                                                    </center>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
