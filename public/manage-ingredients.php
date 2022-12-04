<?php
    require_once './includes/site.php';
    require_once './includes/db.php';

    $user = $_SESSION['user'];
    //if not logged in, back to landing
    if (!isset($user)) {
        header("Location: home.php");
        die();
    } else if ($user['user_type'] == 'user') {
        header("Location: dashboard.php");
        die();
    }

    $page = "Ingredients";
    $pageDesc = "Manage ingredients of the Clever Cooks site";
    
    $toolbarFooter = true;
    $body = "manage-ing-content.php";

    $ings = [];
    $stmt = $pdo->prepare('SELECT `part_id`, `part_name`, `part_grp` FROM `ingredients` ORDER BY `part_grp` ASC, `part_name` ASC');
    if ($stmt->execute()) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cat = $row['part_grp'];
            if (isset($ings[$cat])) {
                array_push($ings[$cat], $row);
            } else {
                $ings[$cat] = array($row);
            }
        }
    }

    //lastly load the page template
    include "./templates/page.php"
?>
