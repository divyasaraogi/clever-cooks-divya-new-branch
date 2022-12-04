<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php include 'head.php'?>
    <body>
        <?php include "header.php" ?>
        <?php include "$body" ?>
        <?php include "footer.php" ?>
    <?php if (isset($json)) { ?>
        <script id='_json_data' type='application/json'><?= json_encode($json) ?></script>
    <?php } ?>
        <script src="js/main.js"></script>
    </body>
</html>