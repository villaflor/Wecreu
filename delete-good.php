<?php
/*
delete script. deletes a good from database and its associated image
Author Olga
*/
require_once '/data/www/default/wecreu/core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}

    include '/data/www/default/wecreu/tools/good.php';
    include_once '/data/www/default/wecreu/tools/sql.php';

    $db = Database::getInstance();
    $good = new Good($db);

    $alldata = $good->getGoodDetail($_GET["gid"]);
    $row = mysqli_fetch_assoc($alldata);

    $deletepath = "/data/www/default/wecreu/images/".$row['good_image'];

    if (is_file($deletepath)) {

            unlink($deletepath);
    }

    if( $good->deleteGood($_GET["gid"])){
        echo "<script type='text/javascript'>alert('successfully deleted good') </script>";
    } else {
        echo "<script type='text/javascript'>alert('failed to delete good') </script>";
    }
    header('Location: good.php');
    exit();
?>