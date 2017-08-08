<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8"/>
		<meta name="description" content="list of goods in a category" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Goods List</title>
		<link rel="stylesheet" href="css/stylesheet.css" />
		
	</head>
	<body>
		
		<?php
			include 'Header.php';
            $clientId = file_get_contents('conf.ini');
            include_once '/data/www/default/wecreu/tools/discountCalculator.php';

          //  $db = Database::getInstance();
            //$category = new Category($db,1);
		?>
		
		<div class="middle">
		
			<div class="content">
				<h3>Goods in Category:<?php $selectcategory;
                                            if(isset( $_GET["cid"])){
                                                $selectcategory = $_GET["cid"];
                                                $alldata = $category->getOne($selectcategory);
                                                $row = mysqli_fetch_assoc($alldata);
                                                echo " $row[category_name]"; 
                                            }else{
                                                $selectcategory = "*";
                                                echo "All"; 
                                            }?> </h3>
				
                <?php
                   
                    $good = new Good($db);
                  //  echo "getting good object";
                    $alldata = $good->getAllGoods($selectcategory,$clientId);
                 //   echo "getting goods list";
                   
                    while ($row = mysqli_fetch_assoc($alldata)){
                     $imagepath = "/wecreu/images/".$row['good_image'];
                    ?>
                    <div class="gooditem">
                        <a href="GoodDetail.php?gid=<?php echo "$row[good_id]";?>">
                        <?php echo "$row[good_name]";  ?>
                        <img src="<?php echo $imagepath; ?>" alt="good image" height="120" width="120" style="padding:20px 40px;"/>
                        <br />
                        $<?php echo discountCalculate($row['good_id']);  ?>
                        </a>
                    </div>
                 <?php
                    }
                ?>

				
			</div>

			<?php
				include 'Categories.php';
			?>
		
		</div>
		
		<?php
			include 'Menu.php';
		?>
		
		<?php
			include 'Footer.php';
		?>
			

	
	</body>
</html>