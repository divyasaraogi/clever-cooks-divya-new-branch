<?php
    require_once './includes/site.php';
    require_once './includes/db.php';

    $page = "Home";
    $pageDesc = "Homepage of the Clever Cooks site";
    $toolbarFooter = true;
    $body = "home-content.php";
    $user = $_SESSION['user'];

    $filters = [];
    $stmt = $pdo->prepare(
               'SELECT t.tag_id, t.tag_name, t.tag_grp, g.grp_name FROM tags t 
                INNER JOIN groupings g ON g.grp_id = t.tag_grp');
    getFilters($filters, $stmt, 'grp_name');
    $stmt = $pdo->prepare('SELECT `part_id`, `part_name`, `part_grp` FROM `ingredients` ORDER BY `part_grp` ASC, `part_name` ASC');
    getFilters($filters, $stmt, 'part_grp');

    $cardList = [
        'title' => 'Latest Recipes',
        'card_id' => 'recipe_id',
        'actionUrl' => 'recipe.php'
    ];
    $stmt = $pdo->prepare('SELECT r.recipe_id, r.name, r.title, r.photo FROM recipes r ORDER BY r.recipe_id DESC LIMIT 6');
    if ($stmt->execute()) {
        $cardList['cards'] = $stmt->fetchAll(PDO::FETCH_ASSOC);   
    }

    $json = [
        'cardList' => $cardList
    ];
    //lastly load the page template
    include "./templates/page.php"
?>
