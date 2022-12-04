<?php
    require_once './includes/site.php';

    //if logged in, just go to dash
    if (isset($_SESSION['user'])) {
        header("Location: dashboard.php");
        die();
    }

    $page = "Login";
    $pageDesc = "Login page at Clever Cooks";
    $toolbarFooter = false;
    $body = "login-content.php";
    if (isset($_SESSION['apiError']) && $_SESSION['apiError']['req'] == 'login') {
        $error = $_SESSION['apiError'];
    }

    //lastly load the page template
    include "./templates/page.php"
?>
