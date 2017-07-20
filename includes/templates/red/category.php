<div class="row">
  <div class=" col-md-3 col-sm-3 col-xs-3 sidebar">
      <ul class="nav nav-pills nav-stacked">
        <?php
			include_once('/data/www/default/wecreu/tools/category.php');
			$clientId = file_get_contents('conf.ini');

			//create an object
		    $category = new Category($db, $clientId);

        	$alldata = $category->getAll();
          if(mysqli_num_rows($alldata) == 0 ){
            echo "<p>It seems you don't have any category. <a href='/wecreu/addCategoryForm.php'>Click here to add.</a></p>";
          }else{
            while ($row = mysqli_fetch_assoc($alldata)) {
    		        //$row[category_id] $row[category_name] $row[category_description] $row[category_display]
    		        echo "<li><a href='good.php?gid=$row[category_id]'>$row[category_name]</a></li>";
    		    }
          }
        ?>
      </ul>
  </div>
