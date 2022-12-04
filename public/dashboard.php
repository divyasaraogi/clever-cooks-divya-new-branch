<?php
    require_once './includes/site.php';
    require_once './includes/db.php';

    //if not logged in, back to landing
    if (!isset($_SESSION['user'])) {
        header("Location: home.php");
        die();
    }

    $page = "Dashboard";
    $pageDesc = "User dashboard of the Clever Cooks site";
    $user = $_SESSION['user'];
    $toolbarFooter = true;
    $body = "dash-content.php";

    //lastly load the page template
    include "./templates/page.php"
?>
