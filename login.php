<?php
session_start();
$title = "Login Page";
include 'header.php';
require_once 'dbconnect.php';

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
                    <li><a href="reg.php" role="tab" data-toggle="tab">Sign up</a></li>
                    <li class="active"><a href="login.php" role="tab" data-toggle="tab">Log in</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active fade in" id="login">
                        <h2 class="text-uppercase text-center"> Log in</h2>
                        <form id="login" action="login.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
                            <div class="form-group">
                                <!-- <label> Your Email<span class="req">*</span> </label> -->
                                <br>
                                <input type="email" class="form-control" id="username" name="username" placeholder="username or email">
                                <p class="help-block text-danger"></p>

                            </div>
                            <div class="form-group">
                                <!-- <label> Password<span class="req">*</span> </label> -->
                                <br>
                                <input type="password" class="form-control" id="psword" name="psword" placeholder="password">
                                <p class="help-block text-danger"></p>
                            </div>
                            <p><?php if (isset($_SESSION['errors'])) echo "*" . $_SESSION['errors']; ?></p>
                            <div class="mrgn-30-top">
                                <button type="submit" class="btn btn-larger btn-block">
                                    Log in
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->
</div>
<?php
$error  = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']))
    {
        echo "token invalid.";
        exit();
    }
    // else{
    //     echo "<h2 style='color:white'>token validated.</h2>\n";
    //     die();
    // }

    if (empty($_POST['username']) || empty($_POST['psword'])) {
        $error = "Username and Password is required.\n";
        $_SESSION['errors'] = $error;
        echo "<script>window.location.href = 'login.php';</script>";
        die();
    } else {
        $uname = htmlspecialchars(strip_tags($_POST['username']));
        $psword = htmlspecialchars(strip_tags($_POST['psword']));
        //checking user existance in the database
        $stmt = $conn->prepare("SELECT * FROM `user_info` WHERE `email` = ? and `psword` = ? ;");
        $stmt->bind_param('ss', $uname, $psword);
        if (!$stmt->execute())
            die("Cant execute");

        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $error = "Invalid username/password";
            $_SESSION['errors'] = $error;
            echo "<script>window.location.href = 'login.php';</script>";
            die();
        } else {
            $data = array();
            $row = $result->fetch_assoc();

            $data['fname'] = $row['fname'];
            $data['lname'] = $row['lname'];
            $data['email'] = $row['email'];
            // $data['psword'] = $row['psword'];

            $_SESSION["id"] = $row['id'];
            $_SESSION["data"] = $data;
            echo "<script>window.location.href = 'profile.php';</script>";
        }
        $conn->close();
    }
}
?>
<?php
unset($_SESSION['errors']);
include 'footer.php'
?>