<?php
require_once 'core/init.php';

$user = new User();
$sale = new Sale();
$validate = new Validate();
$category = new Category();
$good = new Good();
$action = Input::get('action');
$itemId = Input::get('item_id');
$saleId = Input::get('sale_id');

if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}

if(!$saleId){
    Redirect::to('index.php');
}

if(!$sale->isBelongToUser($saleId, $user->data()->client_id)){
    Redirect::to('index.php');
}

$sale->findSale(array('sale_id', '=', $saleId));

if (!$sale->exists()){
    Redirect::to('index.php');
}

if($action === 'delete'){
    $good->setGood(array(
        'sale_id' => null
    ), $itemId);
}

if(Input::exists()){
    $validation = $validate->check($_POST, array(
        'description' => array(
            'name' => 'Description',
            'required' => true,
            'max' => 255
        ),
        'discount' => array(
            'name' => 'Discount',
            'required' => true,
        ),
        'start_date' => array(
            'name' => 'Start date',
        ),
        'end_date' => array(
            'name' => 'End date',
            'required' => true,
            'futureDate' => true,
            'notMatches' => 'start_date',
            'notMatchesTo' => 'Start Date'
        )
    ));

    if ($validation->passed()) {

        try {
            $sale->update(array(
                'sale_description' => Input::get('description'),
                'discount' => Input::get('discount'),
                'end_date' => date('Y-m-d', strtotime(Input::get('end_date'))),
            ), $saleId);

            Session::flash('saleD', 'Sale has been updated!');
            Redirect::to('saleDetail.php?sale_id='.$saleId);

        } catch (Exception $e) {
            die($e->getMessage()); //or you can redirect to other page.
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Wecrue - Edit Sale</title>
</head>
<body>

<nav class="navbar bg-primary navbar-inverse navbar-toggleable-sm sticky-top">
    <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#menuContent" aria-controls="menuContent" aria-expanded="false" aria-label="Toggle Navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuContent">
            <div class="navbar-nav mr-auto">
                <a class="nav-item nav-link" href="index.php">Home</a>
                <a class="nav-item nav-link" href="generateTemplate.php">Generate site</a>
                <a class="nav-item nav-link" href="profile.php?user=<?php echo escape($user->data()->username); ?>">Profile</a>

                <div class="dropdown">
                    <a class="nav-item nav-link dropdown-toggle" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       id="profileDropdown"
                    >Account</a>

                    <div class="dropdown-menu" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="edit-com.php">Update account</a>
                        <a class="dropdown-item" href="changepassword.php">Change password</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a class="nav-item nav-link dropdown-toggle" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       id="pageDropdown"
                    >Page</a>
                    <div class="dropdown-menu" aria-labelledby="pageDropdown">
                        <a class="dropdown-item" href="editCover.php">Edit cover</a>
                        <a class="dropdown-item" href="editFooter.php">Edit footer</a>
                        <a class="dropdown-item" href="editAboutUs.php">Edit about us</a>
                        <a class="dropdown-item" href="pageList.php">View pages</a>
                        <a class="dropdown-item" href="addPage.php">Create page</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a class="nav-item nav-link dropdown-toggle" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       id="categoryDropdown"
                    >Category</a>

                    <div class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <a class="dropdown-item" href="category.php">View categories</a>
                        <a class="dropdown-item" href="addCategoryForm.php">Create category</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a class="nav-item nav-link dropdown-toggle" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       id="goodDropdown"
                    >Good</a>

                    <div class="dropdown-menu" aria-labelledby="goodDropdown">
                        <a class="dropdown-item" href="good.php">View goods</a>
                        <a class="dropdown-item" href="create-good.php">Create good</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a class="nav-item nav-link dropdown-toggle active" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       id="saleDropdown"
                    >Sale</a>

                    <div class="dropdown-menu" aria-labelledby="saleDropdown">
                        <a class="dropdown-item" href="sale.php">View sales</a>
                        <a class="dropdown-item" href="onsale.php">Goods on sale</a>
                        <a class="dropdown-item" href="createsale.php">Create Sale</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a class="nav-item nav-link dropdown-toggle" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       id="saleDropdown"
                    >Orders</a>

                    <div class="dropdown-menu" aria-labelledby="saleDropdown">
                        <a class="dropdown-item" href="orderList.php">View orders</a>
                    </div>
                </div>
                <a class="nav-item nav-link" href="logout.php">Log out</a>
            </div>
        </div>
        <h1 class="navbar-brand mb-0 mr-3">Hello <a class="text-white" href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</h1>
    </div>
</nav>

<div class="container bg-faded py-5">
    <h2 class="mb-4">Edit sale form</h2>
    <?php
    if(Session::exists('editS')) {
        echo '<p class="text-success">' . Session::flash('editS') . '</p>';
    }

    if($validate->errors()) {
        foreach ($validation->errors() as $error) {
            echo '<small class="text-warning">' . $error . '</small><br />';
        }
    }


    ?>
    <form action="" method="post">
        <fieldset class="form-group">
            <legend></legend>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="sale_name"><span class="text-danger">*</span> Name</label>
                <input class="form-control" type="text" name="sale_name" id="sale_name" placeholder="Enter name for sale" value="<?php echo escape($sale->data()->sale_name); ?>" disabled/>
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="description"><span class="text-danger">*</span> Description</label>
                <textarea class="form-control" rows="3" name="description" id="description" placeholder="Enter description"><?php echo escape($sale->data()->sale_description); ?></textarea>
            </div>
            <div class="form-group form-inline">
                <label class="form-control-label mr-2 ml-3" for="discount"><span class="text-danger">*</span>&nbspDiscount</label>
                <input class="form-control" type="number" min="0.01" step="0.01" name="discount" id="discount" style="width: 90px;" value="<?php echo escape($sale->data()->discount); ?>" />
                <span class="input-group-addon">%</span>
            </div>
            <div class="form-group col-md-6" >
                <label class="form-control-label" for="start_date"><span class="text-danger">*</span> Start date</label>
                <input class="form-control" type="date" name="start_date" id="start_date" placeholder="Format: YYYY-MM-DD" value="<?php echo $sale->data()->start_date; ?>" disabled />
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="end_date"><span class="text-danger">*</span> End date</label>
                <input class="form-control" type="date" name="end_date" id="end_date" placeholder="Format: YYYY-MM-DD" value="<?php echo $sale->data()->end_date; ?>" />
            </div>
            <fieldset class="form-group">
                <legend>Item currently selected for sale</legend>
                <div class="form-group col-md-6">
                    <?php
                    if($sale->exists()) {
                        $good->getGood(array('sale_id', '=', $saleId));
                        $goodsOnSale = $good->data();

                        foreach ($goodsOnSale as $goodItem) {
                            if ($goodItem->sale_id) echo '' . $goodItem->good_name . ' <a href="editsale.php?action=delete&item_id='. escape($goodItem->good_id) .'&sale_id='.escape($saleId).'">(delete)</a><br>';
                        }
                    }
                    ?>
                </div>
            </fieldset>
        </fieldset>
        <div class="form-group">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input class="btn btn-primary" type="submit" value="Edit" />
        </div>
    </form>
</div>
<?php include('includes/footer.inc'); ?>

<script src="js/jquery-3.1.1.slim.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
