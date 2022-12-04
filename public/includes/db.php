<?php
    if ($host  == 'localhost') {
        $dbuser = 'root';
	    $dbpass = 'root';
        $dbname = 'clever-cooks';
        $dbhost = 'localhost';
        $dbport = 8889;
    } else {
        $dbconfig = parse_ini_file(__DIR__ . '/../../db/config.ini', true); 
        $dbhost = $dbconfig['database']['host'];  
        $dbname = $dbconfig['database']['name'];
        $dbuser = $dbconfig['database']['user'];
        $dbpass = $dbconfig['database']['password'];
        $dbport = $dbconfig['database']['port'];
    }
    
    $dsn = 'mysql:host='. $dbhost .';port='. $dbport .';dbname='. $dbname .';charset=utf8mb4';
    $pdo = new PDO($dsn, $dbuser, $dbpass);

    function getFilters(&$filters, $stmt, $field) {
        $cats = [];
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $cat = $row[$field];
                if (isset($filters[$cat])) {
                    array_push($filters[$cat], $row);
                } else {
                    $filters[$cat] = array($row);
                    array_push($cats, $cat);
                }
            }
        }
        return $cats;
    }
?>