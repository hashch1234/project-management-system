<?php
session_start();
$title = "Delete Project";
require_once 'dbconnect.php';
include 'header.php';

$pname = $lang = "";
if (isset($_GET['value'])) {
    $value = htmlspecialchars($_GET['value']);
    $_SESSION['pid'] = $value;
}
$value = $_SESSION['pid'];
// echo $value . "<br>";
// var_dump($_SESSION);
$stmt = $conn->prepare("SELECT * FROM `projects` WHERE `pid` = ?");
$stmt->bind_param("s", $value);
if (!$stmt->execute()) {
    die("Can't execute");
}

$result = $stmt->get_result()->fetch_assoc();
// print_r($result);
echo $result['email'] . "<br>" . $_SESSION['data']['email']; 

if($result['email'] != $_SESSION['data']['email'])
{
    echo "<p class='illegal-del' style='background-color:white;min-height:200px; width:80%;text-align: center;font-size: 2rem; margin:100px auto;padding:100px 0;'>Not authorised to delete this project!</p>";
    echo "<script>
                setTimeout(function() {
                    window.location.href = 'pro_display.php?val=2';
                }, 2000);
              </script>";
    die();
}

$stmt = $conn->prepare("DELETE FROM `projects` WHERE `pid` =?;");
$stmt->bind_param("s", $value);
if (!$stmt->execute()) {
    die("can't delete");
} else {
    //     echo "<p style='color:red; font-size:large; font-weight:bold; text-align:center;'>Project Deleted!!</p>" ;
    //     echo "<script>
    //     setTimeout(function() {
    //         window.location.href = 'pro_display.php?val=2';
    //     }, 500);
    //   </script>";
    header('Location: pro_display.php?val=2');
    exit();
}
//     }
// }
?>

<?php
//main delete
// session_start();
// require_once 'dbconnect.php';



// if($_SERVER['REQUEST_METHOD'] == "POST")
// {
//     if(isset($_POST["delete"]))
//     {
//         $value = htmlspecialchars($_POST['delete']);
//         echo $value;
//     }
//     echo $_POST['delete']."<br>";
//     $stmt = $conn->prepare("DELETE FROM `projects` WHERE `email` = ? and `pname` = ?;");
//     $stmt->bind_param("ss" , $_SESSION['data']['email'] , $value);
//     if(!$stmt->execute())
//     {
//         die("can't delete");
//     }
//     else
//     {
//         echo "-------";
//         // header('Location: project_list.php');
//     }
// }


// if(isset($_POST['value']))
// {
//     $value = htmlspecialchars($_POST['value']);
// }
// echo $_POST['value']."<br>";
// $stmt = $conn->prepare("DELETE FROM `projects` WHERE `pid` =?;");
// $stmt->bind_param("s" , $value);
// if(!$stmt->execute())
// {
//     die("can't delete");
// }
// else
// {
//     echo "-------";
//     header('Location: pro_display.php?val=2');
// }
?>