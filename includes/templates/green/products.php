<!DOCTYPE html>
<!--
Green template - Good List page, 
retrieves and provides a list of goods for a selected category, 
formatted for GREEN template

updated August 21 by Olga
Fixing price displays
-->
<html lang="en">
<head>
    <?php include("metadata.php") ?>
    <title><?php echo $client->getClientSiteTitle(); ?></title>
</head>

<body style="background-color: seagreen">
<header class="mb-5 mt-3" style="height: 20vh; align-content: center;">
    <?php include("header.inc") ?>
</header>
<div class="container mb-2">
    <nav class="nav nav-pills nav-fill">
        <a class="nav-item nav-link text-white" href="index.php">Home</a>
        <a class="nav-item nav-link active" href="products.php">Products</a>
        <a class="nav-item nav-link text-white" href="cart.php">Cart</a>
        <a class="nav-item nav-link text-white" href="about-us.php">About us</a>
        <?php if ($contact == 1 ){?>
<a class="nav-item nav-link text-white" href="contact-us.php">Contact us</a>
<?php } ?>
        <?php
        $alldata = $page->getAll();
        while ($row = mysqli_fetch_assoc($alldata)) {
          echo '<a class="nav-item nav-link text-white" href="page.php?page='.$row['id'].'">'.$row['page_name'].'</a>';
        }
        ?>
    </nav>
</div>
<div class="container mb-5">
    <nav class="nav nav-pills nav-fill">
        <?php
        $alldata = $category->getAll();
        $active="";
        if(!isset($_GET['cid'])){
            $active = 'active';
        }
        echo "<a class='nav-item nav-link text-white $active' href='products.php'>All categories</a>";
        while ($row = mysqli_fetch_assoc($alldata)) {
            if (isset($_GET['cid']) && $row['category_id'] == $_GET['cid']){
                $active = 'active';
            }else{
                $active = '';
            }
            echo "<a class='nav-item nav-link text-white $active' href='products.php?cid=$row[category_id]'>$row[category_name]</a>";
        }
        ?>
    </nav>
</div>
<div class="container mb-5">
    <div class="form-inline mb-5">
        <form action="search.php" method="GET">
            <input type="text" name="keyword" class="form-control" placeholder="Search for">
            <input type="submit" class="btn btn-secondary" value="Search!">
        </form>
    </div>

    <?php
    include '/data/www/default/wecreu/tools/good.php';
    include_once '/data/www/default/wecreu/tools/discountCalculator.php';

    $db = Database::getInstance();
    $good = new Good($db);
    // echo "getting good object";
    if(isset($_GET['cid'])){
        $alldata = $good->getAllGoods($_GET['cid'], $clientId);
    } else{
        $alldata = $good->getAllGoods("*", $clientId);
    }
    ?>

    <div class="row text-center">

    <?php
    while ($goodrow = mysqli_fetch_assoc($alldata)){

        $imagepath = "/wecreu/images/".$goodrow['good_image'] ;
        $priceentry = "price unavailable";
        $calcprice = discountCalculate($goodrow['good_id']);
        if(isset($goodrow['sale_id']) && $calcprice != $goodrow['good_price']){//display current price from database or with discount
            $priceentry = '<span style="color:#990000;">$'.$calcprice.'</span> [<span style="text-decoration:line-through;">$'.$goodrow['good_price'].'</span>]';
        } else {
            $priceentry = "$".$goodrow['good_price'];
        }


        echo '<section class="col-md-3">';
        echo '<p>' . $goodrow["good_name"] . '</p>';
        echo '<a href="detail.php?gid=' .$goodrow['good_id'] .'"><img class="img-thumbnail" height="250" width="250" src="'.$imagepath.'" alt="good image" /></a>';
        echo '<p>Price: ' . $priceentry . '</p>';
        echo "</section>";
    }
    ?>
</div>

</div>

<?php include('footer.php'); ?>
<script src="js/jquery-3.1.1.slim.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

