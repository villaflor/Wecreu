<?php
  $clientId = file_get_contents('conf.ini');
  include_once("/data/www/default/wecreu/tools/sql.php");
  include_once('/data/www/default/wecreu/tools/client.php');
  include_once('/data/www/default/wecreu/tools/page.php');
  include_once('/data/www/default/wecreu/tools/good.php');
  include_once '/data/www/default/wecreu/tools/discountCalculator.php';
  //create an object
  $db = Database::getInstance();
  $client = new Client($db,$clientId);
  $page = new Page($db,$clientId);
  $good = new Good($db,$clientId);
  if (file_exists ("../template.php")){
    $color="#7b0b0b";
    include_once("../template.php");
  }

?>
<head>
  <title>
    <?php
      echo $client->getClientSiteTitle();
    ?>
  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/table.css">
  <link rel="stylesheet" href="css/promotion.css">
  <style>
    body .cname{
      font-family: 'Tangerine', serif;
      font-size: 48px;
    }
  </style>
</head>
