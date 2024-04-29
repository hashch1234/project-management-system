<?php
//echo 1;die;
session_start();
require_once 'dbconnect.php';
if(!$conn->connect_error)
// echo "Connected successfully<br>";
// var_dump($_POST);die;
    // print_r( $_SESSION );
    // echo "<br>";
    $errors = array();
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_SESSION['csrf_token']))
            if(!hash_equals($_SESSION['csrf_token'] , $_POST['csrf_token']))
            {
                // echo "Token_invalid";
                exit();
            }
        if(isset($_POST['remove_photo']))
        {
            // echo "hi";
            //  die();
            $name = "uploads/profile".$_SESSION['id'].".jpg";
            $stmt = $conn->prepare("UPDATE `user_info`
                                    SET `profile_photo` = 0
                                    WHERE `id`= ? and `profile_photo`= 1;");
            $stmt->bind_param("s" , $_SESSION['id']);
            if(!$stmt->execute())
                die('Execute failed');
            else{
                if(!unlink($name))
                die('Delete Failed');
                    // header("Location: profile.php");
                // echo "Error in deleting file";
                
                $response = [ 'success' => true ];
                header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;

            }
            // header("Location: profile.php");
            
        }

        if(isset($_POST['update']))
        {    
            $id = $_SESSION['id'];
            $stmt = $conn->prepare("SELECT `psword` FROM `user_info` WHERE `id` = ?;");
            $stmt->bind_param( "s", $id);
            $stmt->execute();
            
            $result = $stmt->get_result()->fetch_assoc();
            $psword =  $result['psword'];
            $photo_name ="";
            // echo "<br> database : $psword" ;
            // echo "<br>";
            // print_r($_POST);
            // var_dump($_FILES);
            // die();
            if(empty($_POST["fname"]))
                $errors['fname'] = "Firstname required";
            if(empty($_POST["lname"]))
                $errors['lname'] = "Lastname required";
            if(strtotime($_POST['dob']) > time())
                $errors['dob'] = "Invalid date of birth";

            if(!empty($_POST['chngpsword']) && empty($_POST['psword']))
                $errors['psword'] = "Current password can't be empty";
            elseif(!empty($_POST['chngpsword']) && !empty($_POST['psword']))
            {
                if( !empty($_POST['psword']) && ($_POST['psword'] != $psword))
                    $errors['psword'] = "Incorrect password.";
            }
            if(empty($_POST['chngpsword']) && !empty($_POST['psword']))
                $errors['chngpsword'] = "Change password can't be empty";
            
            if( !empty($_FILES['photo']['name']))
            {
                $photo =$_FILES['photo'];
                // print_r( $photo );
                // echo "<br>";
                $photo_ext = explode('.' , $photo['name']);
                $photo_ext = strtolower(end($photo_ext));
                // echo $photo_ext;
                // die();
                $allowed = ['jpg', 'png','jpeg'];
                if (!in_array($photo_ext, $allowed))  
                    $errors['photo']='Invalid image type!'; 
                
                else
                    $photo_name = "profile".$_SESSION['id'].".".$photo_ext;
            }

            if(!empty($errors))
            {
                // $_SESSION['errors']= $errors;
                // header("Location: profile.php");
                // die();
                $response = [
                    'success' => false,
                    'errors' => $errors
                ];
                header( "Content-Type: application/json" );
                echo json_encode( $response);
                exit();
            }

            if(empty($errors))
            {
                $fname = htmlspecialchars($_POST['fname']);
                $lname = htmlspecialchars($_POST['lname']);
                if(isset($_POST['gender']))
                    $gender =htmlspecialchars($_POST['gender']);
                else
                    $gender = '';
                $dob = htmlspecialchars($_POST['dob']);
                $city = htmlspecialchars($_POST['city']);
                if(isset($_POST['skills']))
                {
                    $skills = $_POST['skills'];
                    $skills = implode(",", $skills);
                }
                else
                    $skills = '';
                // echo $skills; 
                $about = htmlspecialchars(trim(strip_tags($_POST['about'])));
                
                
                    if(isset($photo_name)  && !empty($photo_name))
                    {
                        $is_photo = 1;
                    
                    $photo_des = 'uploads/'.$photo_name;
                    move_uploaded_file($photo['tmp_name'], $photo_des);
                    $stmt = $conn->prepare("UPDATE `user_info` SET `profile_photo` = 1 WHERE `id` = ?;") ;
                    $stmt->bind_param("s" , $_SESSION[ 'id' ] ) ;
                    $stmt->execute() or die("Can't execute");
                    }
                
                $crrpsword = htmlspecialchars($_POST['psword']);
                $chngpsword = htmlspecialchars($_POST['chngpsword']);

                if(!empty($chngpsword))
                {
                    $psword = $chngpsword;
                }   
                // echo $id;die;
                $stmt = $conn->prepare("UPDATE user_info
                                        SET `fname` = ?, 
                                            `lname` = ? ,
                                            `psword` = ?,  
                                            `gender` = ? , 
                                            `dob` = ? , 
                                            `city` = ? , 
                                            `skill` = ?,
                                            `about` = ?
                                        WHERE `id` = ?;");
                $stmt->bind_param("sssssssss" , $fname, $lname ,$psword, $gender , $dob , $city , $skills ,$about, $id );
                if(!$stmt->execute())
                    die("Error : Data Not Updated<br>");
                // else
                    // echo   $_SESSION['success']="Profile Updated!!";
                        // header("Location: profile.php");
                // echo "hello" ;   
                
                if(empty($errors))
                {
                    // echo "hi";
                    $response = ['success' => true];
                    // var_dump($response);
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
                
            }
            
        }
    }        
         
    
    // else
    // {
    //     $email = $_SESSION['data']['email'];
    //     $stmt = $conn->prepare("SELECT * FROM `user_info` WHERE `email` = ?;");
    //     $stmt->bind_param("s", $email);
    //     if(!$stmt->execute())
    //         die("Error Selecting User Info");
        
    //     $result = $stmt->get_result()->fetch_assoc();
    //     print_r($result);
    //     $_SESSION[ 'data' ]['gender']= $result['gender'];
    //     $_SESSION[ 'data' ]['dob']= $result['dob'];
    //     $_SESSION[ 'data' ]['city']= $result['city'];
    //     $_SESSION[ 'data' ]['skill']= $result['skill'];
    //     $_SESSION[ 'data' ]['about']= $result['about'];
    //     $_SESSION[ 'data' ]['photo']= $result['profile_photo'];
    //     print_r($_SESSION['data']);
    //    
    // }

?>

