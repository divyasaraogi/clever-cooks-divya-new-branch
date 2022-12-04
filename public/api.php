<?php 
    require_once './includes/site.php';
    require_once './includes/db.php';

    $user = $_SESSION['user'];

    function startsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        return substr( $haystack, 0, $length ) === $needle;
    }

    function isAllowed() {
        global $user;
        if (isset($user)) {
            $type = $user['user_type'];
            return $type == 'admin' || $type == 'contributor';
        }
        return false;
    } 

    $action = $_GET['req'];
    $method = $_SERVER['REQUEST_METHOD'];
    
    if (isset($action, $method)) {
        if ($method == 'GET') {
            if ($action == 'logout') {
                session_destroy();
                header("Location: home.php");
                die();
            }
            if ($action == 'recing') {
                $recipe = $_GET['id'];
                if (isset($recipe)) {
                    $stmt = $pdo->prepare(
                        'SELECT ri.part_id, i.part_name, ri.quantity, ri.unit FROM `recipe-ing` ri 
                         INNER JOIN ingredients i ON i.part_id = ri.part_id WHERE ri.recipe_id = :recipe');
                    if ($stmt->execute(['recipe' => $recipe])) {
                        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                        die();
                    }
                }
                echo '[]';
                die();
            }
        } else if ($method == 'POST') {
            if (isset($_SESSION['apiError'])) {
                //clear previous errors
                unset($_SESSION['apiError']);
            }
            if ($action == 'signup') {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $name = $_POST['name'];
                $psAcct = $pdo->prepare('SELECT * FROM `accounts` WHERE `email` = :email');
                $psAcct->execute(['email' => $email]);
                $acct = $psAcct->fetch(PDO::FETCH_ASSOC);
                if (empty($email) || empty($password) || empty($name) || !empty($acct)) {
                    $_SESSION['apiError'] = array(
                        "req" => $action,
                        "email" => $email,
                        "name" => $name,
                        "error" => 'Already signed up or fields missing'
                    );
                    header("Location: signup.php");
                    die();
                } else {
                    //hash password
                    $password = password_hash($password, PASSWORD_BCRYPT);
                    $psAcctAdd = $pdo->prepare(
                        'INSERT INTO `accounts` (`user_id`,`email`, `password`, `display_name`, `full_name`, `photo_url`) 
                         VALUES (NULL, :email, :pass, :nick, :full, :photo)');
                    $psAcctAdd->execute([
                        'email' => $email,
                        'pass' => $password,
                        'nick' => '',
                        'full' => $name,
                        'photo' => 'assets/catprofile.jpeg'
                    ]);
                    $psAcct->execute(['email' => $email]);
                    $acct = $psAcct->fetch(PDO::FETCH_ASSOC);
                    unset($acct['password']);
                    $_SESSION['user'] = $acct;
                    header("Location: dashboard.php");
                    die();
                }
            } else if ($action == 'login') {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $psAcct = $pdo->prepare('SELECT * FROM `accounts` WHERE `email` = :email');
                $psAcct->execute(['email' => $email]);
                $acct = $psAcct->fetch(PDO::FETCH_ASSOC);
                if (!empty($acct) && password_verify($password, $acct['password'])) {
                    //success
                    unset($acct['password']);
                    $_SESSION['user'] = $acct;
                    header("Location: dashboard.php");    
                    die();
                } else {
                    $_SESSION['apiError'] = array(
                        "req" => $action,
                        "email" => $email,
                        "error" => 'Email or password invalid.'
                    );
                    header("Location: login.php");
                    die();
                }
            } else if ($action == 'ing' && isAllowed()) {
                $ingId = $_POST['ingId'];
                $grp = $_POST['grp'];
                $ing = $_POST['ing'];
                if (isset($grp, $ing)) {
                    if (!isset($ingId) || $ingId == '') {
                        //insert
                        $ingId = null;
                        $stmt = $pdo->prepare(
                            'INSERT INTO `ingredients`(`part_id`, `part_name`, `part_grp`) 
                             VALUES (:ingId, :ing, :grp)');
                    } else {
                        //update
                        $stmt = $pdo->prepare(
                            'UPDATE `ingredients` 
                             SET `part_name` = :ing, `part_grp` = :grp WHERE `part_id` = :ingId');
                    }
                    $params = [
                        'ingId' => $ingId,
                        'ing' => strtolower($ing),
                        'grp' => strtolower($grp)
                    ];
                    if ($stmt->execute($params)) {
                        if ($ingId == null) {
                            $ingId = $pdo->lastInsertId();
                            $params['ingId'] = $ingId;
                            $params['inserted'] = true;
                        }
                        $params['success'] = true;
                        echo json_encode($params);
                    } else {
                        echo '{ "error": "Something went wrong." }';
                    }
                    die();
                }
            } else if ($action == 'deling' && isAllowed()) {
                $ingId = $_POST['ingId'];
                if (isset($ingId)) {
                    $stmt = $pdo->prepare(
                        'DELETE FROM `ingredients` WHERE `part_id` = :ingId');
                    if ($stmt->execute([ 'ingId' => $ingId])) {
                        echo '{ "success": true }';
                    } else {
                        echo '{ "error": "Unable to remove." }';
                    }
                    die();
                }
            } else if ($action == 'rec' && isAllowed()) {
                $recipeId = $_POST['recipe'];
                $name = $_POST['name'];
                $title = $_POST['title'];
                $steps = $_POST['steps'];
                $time = $_POST['cook-time'];
                $diet = $_POST['diet'];
                $cuisine = $_POST['cuisine'];
                $calLow = $_POST['cal-low'];
                $calHigh = $_POST['cal-high'];

                if (isset($name, $title, $time, $calLow, $calHigh, $diet, $cuisine, $steps)) {
                    $params = [
                        'recipeId' => $recipeId,
                        'aname' => $name,
                        'title' => $title,
                        'steps' => $steps,
                        'atime' => $time,
                        'diet' => $diet,
                        'cuisine' => $cuisine,
                        'calLow' => $calLow,
                        'calHigh' => $calHigh
                    ];
                    //process photo
                    $origFilename = $_FILES["photo"]["name"];
                    $photo = '';
                    if (isset($origFilename) && $origFilename != '') {
                        $photo = 'uploads/' . uniqid('photo') .'.'. pathinfo($origFilename, PATHINFO_EXTENSION);
                        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo)) {
                            $photo = '';
                        } else {
                            $params['photo'] = $photo;
                        }
                    }
                    if (isset($recipeId) && $recipeId != '') {
                        //update
                        if ($photo == '') {
                            $stmt = $pdo->prepare(
                                'UPDATE `recipes` SET 
                                    `name`=:aname,`title`=:title,`steps`=:steps,`cook_time`=:atime,`diet`=:diet,
                                    `cuisine`=:cuisine,`calories_low`=:calLow,`calories_high`=:calHigh
                                 WHERE `recipe_id` = :recipeId');
                        } else {
                            $stmt = $pdo->prepare(
                                'UPDATE `recipes` SET 
                                    `name`=:aname,`title`=:title,`steps`=:steps,`cook_time`=:atime,`diet`=:diet,
                                    `cuisine`=:cuisine,`calories_low`=:calLow,`calories_high`=:calHigh,
                                    `photo`=:photo WHERE `recipe_id` = :recipeId');
                        }
                        
                    } else {
                        //insert
                        $params['recipeId'] = null;
                        if ($photo == '') {
                            $stmt = $pdo->prepare(
                                'INSERT INTO `recipes`(`recipe_id`, `name`, `title`, `steps`, `cook_time`, `diet`, `cuisine`, `calories_low`, `calories_high`) 
                                VALUES (:recipeId,:aname,:title,:steps,:atime,:diet,:cuisine,:calLow,:calHigh)');
                        } else {
                            $stmt = $pdo->prepare(
                                'INSERT INTO `recipes`(`recipe_id`, `name`, `title`, `steps`, `cook_time`, `diet`, `cuisine`, `calories_low`, `calories_high`,`photo`) 
                                VALUES (:recipeId,:aname,:title,:steps,:atime,:diet,:cuisine,:calLow,:calHigh,:photo)');
                        }
                    }

                    if ($stmt->execute($params)) {
                        //save the ingredients
                        if ($recipeId == null) {
                            $recipeId = $pdo->lastInsertId();
                        }
                        $params = [];
                        foreach ($_POST as $key => $value) {
                            if (startsWith($key, 'qty_')) {
                                $id = substr($key, 4);
                                if (isset($params[$id])) {
                                    $params[$id]['quantity'] = $value;
                                } else {
                                    $params[$id] = array(
                                        'quantity' => $value
                                    );
                                }
                            } else if (startsWith($key, 'uni_')) {
                                $id = substr($key, 4);
                                if (isset($params[$id])) {
                                    $params[$id]['unit'] = $value;
                                } else {
                                    $params[$id] = array(
                                        'unit' => $value
                                    );
                                }
                            } else if (startsWith($key, 'map_')) {
                                $id = substr($key, 4);
                                if (isset($params[$id])) {
                                    $params[$id]['mapId'] = $value;
                                } else {
                                    $params[$id] = array(
                                        'mapId' => $value
                                    );
                                }
                            }
                        }
                        //save ings
                        $updateStmt = $pdo->prepare('UPDATE `recipe-ing` SET `quantity`=:quantity,`unit`=:unit WHERE `map_id`=:mapId');
                        $insertStmt = $pdo->prepare(
                            'INSERT INTO `recipe-ing`(`map_id`, `part_id`, `recipe_id`, `quantity`, `unit`) 
                             VALUES (:mapId,:part,:recipeId,:quantity,:unit)');
                        foreach ($params as $key => $entry) {
                            if (isset($entry['map_id']) && $entry['map_id'] != '') {
                                $res = $updateStmt->execute($entry);
                            } else {
                                $entry['recipeId'] = $recipeId;
                                $entry['part'] = $key;
                                $entry['mapId'] = null;
                                $res = $insertStmt->execute($entry);
                            }
                            if (!$res) {
                                echo '{ "error": "Unable to save changes." }';
                                die();
                            }
                        }  
                        $params = [
                            'success' => true,
                            'recipe_id' => $recipeId,
                            'name' => $name,
                            'title' => $title,
                            'steps' => $steps, 
                            'cook_time' => $time,
                            'diet' => $diet,
                            'cuisine' => $cuisine, 
                            'calories_low' => $calLow, 
                            'calories_high' => $calHigh,
                        ];
                        if ($photo != '') {
                            $params['photo'] = $photo;
                        }
                        echo json_encode($params);
                        die();
                    } else {
                        echo '{ "error": "Unable to save changes." }';
                        die();
                    }
                }
            } else {
                //return 404
                header("Location: error.php");
                die();        
            }
        }    
    }
    //if we got to here then we assume, unhandled error
    header("Location: error.php");
    die();
?>