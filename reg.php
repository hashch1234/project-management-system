<?php session_start();
$title = "Registration page";
include 'header.php';
if(!isset($_SESSION['csrf_token']))
{
    $_SESSION['csrf_token']= bin2hex(random_bytes(32));
}

?>
<div id="form">
    <div class="container">
        <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-md-8 col-md-offset-2">
            <div id="userform">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="active"><a href="reg.php" role="tab" data-toggle="tab">Sign up</a></li>
                    <li><a href="login.php" role="tab" data-toggle="tab">Log in</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="signup">
                        <h2 class="text-uppercase text-center"> Register </h2>
                        <form id="signup" action="sub.php"  method="post" >
                            <input type="hidden" name="csrf_token" value= <?php echo $_SESSION['csrf_token']; ?>>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <br>
                                        <!-- <label>First Name<span class="req">*</span> </label> -->
                                        <input  type="text" class="form-control" id = "fname" name = "fname"  value = "<?php if(isset($_SESSION['data']['fname'])) echo $_SESSION['data']['fname'];?>" placeholder = "firstname">
                                        <?php
                                            if(isset($_SESSION['errors']['fname']))
                                                echo "<span>*".$_SESSION[ 'errors' ][ 'fname']."</span>";
                                        ?>
                                        <p class="help-block text-danger"></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <!-- <label> Last Name<span class="req">*</span> </label> -->
                                        <br>
                                        <input  type="text"  class="form-control" id = "lname" name = "lname"  value = "<?php if(isset($_SESSION['data']['lname'])) echo $_SESSION['data']['lname'];?>" placeholder = "lastname">
                                        <?php
                                            if(isset($_SESSION['errors']['lname']))
                                                echo "<span>*".$_SESSION[ 'errors' ][ 'lname']."</span>";
                                        ?>
                                        <p class="help-block text-danger"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label> Your Email<span class="req">*</span> </label> -->
                                <br>
                                <input  type="email" class="form-control" id = "email" name = "email"  value = "<?php if(isset($_SESSION['data']['email'])) echo $_SESSION['data']['email'];?>" placeholder = "abc@xyc.com">
                                <?php
                                    if(isset($_SESSION['errors']['email']))
                                        echo "<span>*".$_SESSION[ 'errors' ][ 'email']."</span>";
                                ?>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <!-- <label> Your Phone<span class="req">*</span> </label> -->
                                <br>
                                <input  type="password" class="form-control" id = "psword" name = "psword" placeholder="Create password">
                                <?php
                                    if(isset($_SESSION['errors']['psword']))
                                        echo "<span>*".$_SESSION[ 'errors' ][ 'psword']."</span>";
                                ?>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <!-- <label> Password<span class="req">*</span> </label> -->
                                <br>
                                <input  type="password" class="form-control" id = "conspword" name = "conpsword" placeholder="Confirm password" >
                                <?php
                                    if(isset($_SESSION['errors']['conpsword']))
                                        echo "<span>*".$_SESSION[ 'errors' ][ 'conpsword']."</span>";
                                ?>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="mrgn-30-top">
                                <button type="submit" class="btn btn-larger btn-block">
                                    Sign up
                                </button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->
    <?php 
                if(isset($_SESSION['success']))
                {
                    echo "<p style='color:#f8bfa1; font-size:large; font-weight:bold; text-align:center;'>".$_SESSION['success']."</p>" ;
            ?>
                    <!-- <script> alert("Registration Successful!!!");</script> -->
                    
            <?php
                session_unset();
                session_destroy();
                echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 800);
              </script>";
                // header("Location: login.php");
                }
            ?>
            <?php 
                unset($_SESSION['errors']);
                unset($_SESSION['data']);
                // unset($_SESSION['success']);
                
            ?>
</div>

<?php include 'footer.php' ?>