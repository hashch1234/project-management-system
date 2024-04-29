<?php
    session_start();
    require_once 'dbconnect.php';
        $errors = array();
        $data = array();
        echo "Connected successfully<br>";
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(isset($_SESSION['csrf_token']))
                if(!hash_equals($_SESSION['csrf_token'] , $_POST['csrf_token']))
                {
                    echo "Token_invalid";
                    exit();
                }
            if(empty($_POST["fname"]))
                $errors['fname'] = "Firstname required";
            else
                $data['fname'] = htmlspecialchars($_POST["fname"]);
            if(empty($_POST["lname"]))
                $errors['lname'] = "Lastname required";
                else
                $data['lname'] = htmlspecialchars($_POST["lname"]);
            if(empty($_POST["email"]))
                $errors['email'] = "email required";
            else
            {
                if(!filter_var($_POST['email'] , FILTER_VALIDATE_EMAIL))
                {
                    $errors['email'] = "Invalid email";
                }
                else
                {
                    $stmt = $conn->prepare( "SELECT * FROM user_info WHERE email=?;" );
                    $stmt->bind_param('s', htmlspecialchars($_POST['email']));
                    $stmt->execute();  //$result will hold the result of this query (which is true or false)
                    $result = $stmt->get_result()->num_rows;
                    echo $result;
                    if($result > 0 )
                        $errors['email'] = "Email already exists.";
                    else
                        $data['email']=htmlspecialchars($_POST['email']);
                }
            }
            if(empty($_POST["psword"]))
                $errors['psword'] = "Password required";
            if(empty($_POST["conpsword"]))
                $errors['conpsword'] = " Confirm Password required";
            else
            {
                if (htmlspecialchars($_POST['psword']) != htmlspecialchars($_POST['conpsword']))
                    $errors['conpsword'] = 'Passwords do not match';
            }

            if(!empty($errors))
            {
                $_SESSION['errors']= $errors;
                $_SESSION['data'] = $data;
                header("Location: reg.php");
                die();
            }

            $fname = htmlspecialchars($_POST['fname']);
            $lname = htmlspecialchars($_POST['lname']);
            $email = htmlspecialchars($_POST['email']);
            $psword = htmlspecialchars(($_POST['psword']));

            $stmt = $conn->prepare("INSERT INTO `user_info`(`fname` , `lname` , `email` , `psword`)
                                    VALUES(?, ?, ?,?);");
            $stmt->bind_param("ssss" , $fname , $lname , $email , $psword);   
            if(!$stmt->execute())
                die("Error : Data Not Inserted<br>");
            else
                {$_SESSION['success']="Registration Successful!!";
                    header("Location: reg.php");
                }
                // session_unset();
                // session_destroy();
                header("Location: reg.php");
                // echo "Data Inserted Successefully.<br>";
            $conn->close();
        }
        
?>