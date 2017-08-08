<?php
require_once 'core/init.php';

$user = new User();
$validate = new Validate();

if (!$user->isLoggedIn()){
    Redirect::to('index.php');
}

if(Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validation = $validate->check($_POST, array(
            'category_name' => array(
                'name' => 'Name',
                'required' => true,
                'unique' => 'category',
                'min' => 2,
                'max' => 30
            ),
            'desc' => array(
                'name' => 'Description',
                'required' => true,
                'max' => 255
            )
        ));

        if ($validation->passed()) {

            try {
                include_once('tools/category.php');
                include_once("tools/sql.php");

                $db = Database::getInstance();
                $clientId = $user->data()->client_id;
                //create an object
                $category = new Category($db, $clientId);

                // add one
                if ($category->add($_POST['category_name'], $_POST['desc'])) {
                    Session::flash('category', 'New category '. $_POST['category_name'] .' has been added!');
                    Redirect::to('category.php');

                } else {
                    $validate->addError('Sorry, adding new category failed. Please try again.');
                    //echo "add #1 fail<br>";
                }
            } catch (Exception $e) {
                die($e->getMessage()); //or you can redirect to other page.
            }
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
    <title>Wecrue - Add Category</title>
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
                    <a class="nav-item nav-link dropdown-toggle active" href="#"
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
                    <a class="nav-item nav-link dropdown-toggle" href="#"
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
    <h2 class="mb-4">Create category form</h2>
    <?php
    if(Session::exists('createC')) {
        echo '<p class="text-success">' . Session::flash('createC') . '</p>';
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
                <label class="form-control-label" for="category_name"><span class="text-danger">*</span> Name</label>
                <input class="form-control" type="text" id="category_name" name="category_name" value="">
            </div>
            <div class="form-group col-md-6">
                <label class="form-control-label" for="desc"><span class="text-danger">*</span> Description</label>
                <textarea class="form-control" rows="3" name="desc" id="desc" placeholder="Enter description"><?php echo escape(Input::get('desc'))?></textarea>
                <input type="hidden" name="method" value="categoryAdd">
            </div>
        </fieldset>
        <div class="form-group">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input class="btn btn-primary" type="submit" value="Add" />
        </div>
    </form>
</div>

<?php include('includes/footer.inc'); ?>

<script src="js/jquery-3.1.1.slim.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
