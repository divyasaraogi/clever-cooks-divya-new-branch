<?php
    require_once './includes/site.php';
    
    $page = "Sign Up";
    $pageDesc = "Sign-up to an account at Clever Cooks";
    $toolbarFooter = false;
    $body = "signing.php";
    if (isset($_SESSION['apiError']) && $_SESSION['apiError']['req'] == 'signup') {
        $error = $_SESSION['apiError'];
    }

    //lastly load the page template
    include "./templates/page.php"
?>
