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

    $page = "Recipes";
    $pageDesc = "Manage recipes of the Clever Cooks site";
    
    $toolbarFooter = true;
    $body = "manage-recipe-content.php";

    $filters = [];
    $stmt = $pdo->prepare(
               'SELECT t.tag_id, t.tag_name, t.tag_grp, g.grp_name FROM tags t 
                INNER JOIN groupings g ON g.grp_id = t.tag_grp');
    getFilters($filters, $stmt, 'grp_name');
    $stmt = $pdo->prepare('SELECT `part_id`, `part_name`, `part_grp` FROM `ingredients` ORDER BY `part_grp` ASC, `part_name` ASC');
    $ingCats = getFilters($filters, $stmt, 'part_grp');

    $cardList = [
        'title' => '0 found',
        'small' => true,
        'actionUrl' => ''
    ];

    $json = [
        'cats' => $ingCats,
        'filters' => $filters,
        'cardList' => $cardList
    ];

    //lastly load the page template
    include "./templates/page.php"
?>
