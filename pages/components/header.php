<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include('../backend/controller/AuthController.php');

$AuthController = new AuthController();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$auth_user_id = $_SESSION['id'];

$userResult = $AuthController->GetUser($auth_user_id);

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
} else {
    header("Location: ../logout.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="../styles.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div id="global-loader">
        <div class="loader-content text-center">
            <div class="spinner-brand"></div>
            <p class="mt-3 fw-bold text-uppercase" style="color: #a71b1b; letter-spacing: 1px; font-size: 0.9rem;">Loading...</p>
        </div>
    </div>

    <style>
        /* Loader Overlay */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        /* Hidden State */
        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* Custom Spinner (Red & Gold) */
        .spinner-brand {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #a71b1b; /* Main Red */
            border-right: 5px solid #FFC107; /* Gold */
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <nav class="navbar navbar-expand-lg navbar-dark bg-main">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">
                <img src="../backend/storage/assets/logo.png" alt="Felamo" style="width: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    <?php
                    if ($user['role'] == "teacher") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="levels.php">Markahan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="my_sections.php">My Sections</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="students.php">My Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="leader_boards.php">Leader Boards</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="notifications.php">Notifications</a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if ($user['role'] == "super_admin") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="teachers.php">Teachers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="students.php">Students</a>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($user['name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-main" aria-labelledby="userDropdown">

                            <li></li>
                            <li><a class="dropdown-item text-light" href="profile.php">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-light" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <div id="alert" style="position: absolute; top:10px; right:10px; font-size: 12px;"></div>