<?php
    include('header.php');
    include ('db_config.php');
?>
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<div class="row" style="padding:10px"></div>
<div class="container" >
    <div class="col-md-4 col-md-offset-4">
        <?php
            //echo "<pre>";
            //print_r($_SESSION);
           // echo "</pre>";
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $_SESSION['g_PR_Name_pay']; ?></div>
            <div class="panel-body">
            <?php echo $_SESSION['error'];
               $_SESSION['error']="";
            ?>
                Payment amount: <strong><i style="font-size:12px" class="fa fa-inr"></i> <?php echo $_SESSION['payment']; ?></strong>
                <p>select payment mode:</p>
                <a type="button" class="btn btn-primary btn-color btn-bg-color btn-sm" href="paymentbywallet.php"> Pay By Wallet</a>
                <a type="button" class="btn btn-primary btn-color btn-bg-color btn-sm " href="payment_gateway/testpayment.php">Pay By Card</a>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php');?>
   