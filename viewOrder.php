<?php
require_once 'core/init.php';

$user = new User();
$validate = new Validate();

if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}
if (!isset($_GET['id'])){
    Redirect::to('index.php');
}

$clientId = escape($user->data()->client_id);

include_once('tools/order.php');
include_once("tools/sql.php");

$db = Database::getInstance();
//create an object
$order = new Order($db, $clientId);
$order_id = $_GET['id'];
$alldata = $order->getOne($order_id);

if(Input::exists()){
    if(Token::check(Input::get('token'))){
    if  (strcmp($_POST['client_admin_email'],$user->data()->client_admin_email) == 0){
      $validation = $validate->check($_POST, array(

        'client_information' => array(
          'name' => 'Client information',
          'max' => 256
        )
      ));
    }
    else {
      $validation = $validate->check($_POST, array(

        'client_information' => array(
          'name' => 'Client information',
          'max' => 256
        ),
        'client_admin_email' => array(
          'name' => 'Admin email',
          'required' => true,
          'unique' => 'client',
          'max' => 150
        )
      ));
    }
    if($validation->passed()){

      try{

          $user->update(array(
            'client_information' => Input::get('client_information'),
            'client_tax' => (Input::get('client_tax') ?: 0.0),
            'payment_option_paypal' => (Input::get('paypal') ?: 0),
            'payment_option_visa' => (Input::get('visa') ?: 0),
            'payment_option_mastercard' => (Input::get('master') ?: 0),
            'payment_option_ae' => (Input::get('ae') ?: 0),
            'client_admin_email' => Input::get('client_admin_email')
          ));


                Session::flash('home', 'Your information have been updated.');
                Redirect::to('index.php');

            } catch (Exception $e){
                die($e->getMessage());
            }
        } else{

            foreach ($validation->errors() as $error){
                echo $error, '<br />';
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
    <script type="text/javascript" charset="utf-8" src="tools/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="tools/ueditor/ueditor.all.js"> </script>
    <script type="text/javascript" charset="utf-8" src="tools/ueditor/lang/en/en.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Wecrue</title>
    <style type="text/css">
        .red {
            color: red;
        }
    </style>

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
                  <?php

                  if($user->isLoggedIn()) {
                  ?>
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
                          <a class="dropdown-item" href="edit-good.php">Edit good</a>
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

                  <a class="nav-item nav-link" href="logout.php">Log out</a>
              </div>
          </div>

          <h1 class="navbar-brand mb-0 mr-3">Hello <a class="text-white" href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</h1>

          <?php
          } else{
          ?>
          <a class="nav-item nav-link" href="aboutus.php">About us</a>
          <a class="nav-item nav-link" href="login.php">Log in</a>
          <a class="nav-item nav-link" href="register.php">Register</a>
      </div>
      </div>

      <h1 class="navbar-brand mb-0 mr-3">Hi there!</h1>

      <?php
      }
      ?>

      </div>
  </nav>
<div class="container bg-faded py-5">
    <h2 class="mb-4">Invoice Detail</h2>
    <?php $row = mysqli_fetch_assoc($alldata);?>
    <table>
        <tr>
            <th>Customer Name: </th>
            <td><?php echo $row['customer_name']?></td>
        </tr>
        <tr>
            <th>Customer Number: </th>
            <td><?php echo $row['customer_number']?></td>
        </tr>
        <tr>
            <th>Customer Address: </th>
            <td><?php echo $row['customer_street_address']?></td>
        </tr>
        <tr>
            <th>Customer City: </th>
            <td><?php echo $row['customer_city']?></td>
        </tr>
        <tr>
            <th>Customer State: </th>
            <td><?php echo $row['customer_state']?></td>
        </tr>
        <tr>
            <th>Customer Country: </th>
            <td><?php echo $row['customer_country']?></td>
        </tr>
        <tr>
            <th>Customer Email: </th>
            <td><?php echo $row['customer_email']?></td>
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <th>Invoice #: </th>
            <td><?php echo $row['invoice_id']?></td>
        </tr>
        <tr>
            <th>Invoice Price: </th>
            <td>$<?php echo $row['invoice_final_price']?></td>
        </tr>
        <tr>
            <th>Number of products: </th>
            <td><?php echo $row['invoice_total_quantity']?></td>
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <th>Product name</th>
            <th>Product description</th>
            <th>Product quantity</th>
        </tr>
        <tr>
            <td><?php echo $row['good_name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $row['good_description']?>&nbsp;&nbsp;</td>
            <td style="text-align:center"><?php echo $row['good_quantity']?></td>
        </tr>



    <br/>
    <?php
    while ($row = mysqli_fetch_assoc($alldata)) {
        ?>
        <tr>
            <td><?php echo $row['good_name']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $row['good_description']?>&nbsp;&nbsp;</td>
            <td style="text-align:center"><?php echo $row['good_quantity']?></td>
        </tr>
        <?php
    }
    ?>
    </table>
</div>
<?php include('includes/footer.inc'); ?>

<script src="js/jquery-3.1.1.slim.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>
 <script type="text/javascript">
    var ue = UE.getEditor('editor', {
      toolbars: [['forecolor', 'fontsize', 'bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'fullscreen']],
      elementPathEnabled: true,
      enableContextMenu: false,
      wordCount:false,
      emotionLocalization:true,
      imagePopup:false
    });

    function save(){
      var data = ue.getContent();
      $.ajax({
        url:"tools/ueditor/php/postPage.php",
        data:{data:data,clientid:<?php echo $clientId;?>, pageId:<?php echo $page_id;?>},
        dataType:"text",
        method:"POST",
        success:function(data){
          alert(data);
        },
        error:function(data){
          alert(data);
        }
      });
    }

  </script>
</body>
</html>