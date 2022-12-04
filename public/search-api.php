<?php 
    require_once './includes/site.php';
    require_once './includes/db.php';

    $user = $_SESSION['user']; 
    $query = $_GET['q'];
    
    function extractEndsWith($hayStack, $needle) {
        $len = strlen($needle);
        $mark = substr($hayStack, -$len);
        if ($len > 0 && $mark === $needle) {
            $prefix = substr($hayStack, 0, strlen($hayStack) - $len);
            return $prefix;
        }
        return '';
    }

    function escValue($val) {
        if ($val == false) {
            return '@';
        }
        return $val;
    }

    function retrieveIds(&$results, &$stmt, &$args, $like = true) {
        reset($args);
        $proceed = true;
        while ($proceed) {
            //empty means false but there should no tags called 'false'
            $params = [
                'one' => $like ? '%'. escValue(current($args)) .'%' : escValue(current($args)),
                'two' => $like ? '%'. escValue(next($args)) .'%' : escValue(next($args)),
                'three' => $like ? '%'. escValue(next($args)) .'%' : escValue(next($args)),
                'four' => $like ? '%'. escValue(next($args)) .'%' : escValue(next($args)),
                'five' => $like ? '%'. escValue(next($args)) .'%' : escValue(next($args)),
            ];
            error_log(print_r($params, true));
            if ($stmt->execute($params)) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $results[$row['recipe_id']] = $row;
                }
            }
            $proceed = next($args);
        }
    }

    error_log($query);

    $tokens = preg_split("/ /", $query);

    $words = [];
    $calories = [];
    $time = [];
    foreach ($tokens as $token) {
        $prefix = extractEndsWith($token, 'cal');
        if ($prefix != '' && is_numeric($prefix)) {
            array_push($calories, $prefix);
            continue;
        }
        $prefix = extractEndsWith($token, 'm');
        if ($prefix != '' && is_numeric($prefix)) {
            array_push($time, $prefix);
            continue;
        }
        array_push($words, $token);
    }

    $results = [];
    //search diet
    $stmt = $pdo->prepare( 
        'SELECT r.* FROM tags t 
         INNER JOIN recipes r ON r.diet = t.tag_id 
         WHERE t.tag_grp = 1 AND (t.tag_name like :one 
         OR t.tag_name like :two OR t.tag_name like :three OR t.tag_name like :four 
         OR t.tag_name like :five)');
    retrieveIds($results, $stmt, $words);
    //search cuisine
    $stmt = $pdo->prepare( 
        'SELECT r.* FROM tags t 
         INNER JOIN recipes r ON r.cuisine = t.tag_id 
         WHERE t.tag_grp = 2 AND (t.tag_name like :one 
         OR t.tag_name like :two OR t.tag_name like :three OR t.tag_name like :four 
         OR t.tag_name like :five)');
    retrieveIds($results, $stmt, $words);
    //search ingredients
    $stmt = $pdo->prepare(
        'SELECT DISTINCT(r.recipe_id), r.* FROM ingredients i 
         INNER JOIN `recipe-ing` ri ON ri.part_id = i.part_id 
         INNER JOIN recipes r ON r.recipe_id = ri.recipe_id
         WHERE i.part_name like :one OR i.part_name like :two OR i.part_name like :three 
         OR i.part_name like :four OR i.part_name like :five');
    retrieveIds($results, $stmt, $words);
    //search calories
    $stmt = $pdo->prepare(
        'SELECT r.* FROM recipes r WHERE (r.`calories_low` <= :one AND r.`calories_high` >= :one) 
         OR (r.`calories_low` <= :two AND r.`calories_high` >= :two) 
         OR (r.`calories_low` <= :three AND r.`calories_high` >= :three) 
         OR (r.`calories_low` <= :four AND r.`calories_high` >= :four) 
         OR (r.`calories_low` <= :five AND r.`calories_high` >= :five)');
    retrieveIds($results, $stmt, $calories, false);
    //search cooking time
    rsort($time);
    $stmt = $pdo->prepare('SELECT * FROM recipes r WHERE r.cook_time <= :one');
    if (count($time) > 0) {
        error_log($time[0]);
        if ($stmt->execute([ 'one' => $time[0]])) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[$row['recipe_id']] = $row;
            }
        }
    }
    
    //make sure it is an array of objects json
    echo '[';
    reset($results);
    $row = current($results);
    while ($row) {
        echo json_encode($row);
        $row = next($results);
        if ($row) {
            echo ',';
        }
    }
    echo ']';
?>