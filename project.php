<?php
session_start();
$title = "Create Project";
require_once 'dbconnect.php';
include 'header.php';

?>
<!-- <h3 class="create-project"><b>CREATE PROJECT</b></h3> -->
<style>

</style>
<?php
if (isset($_SESSION['d']['lang'])) {
    $skill = $_SESSION['d']['lang'];
}
?>
<div class="project-form-container">
    <h3 style="text-align: center;" class="create-project">Create Project</h3>
    <br>
    <form id="create-project-form" action="project.php" method="post">
        <input type="hidden" name="csrf_token" value=<?php echo $_SESSION['csrf_token']; ?>>
        <div class="form-group">
            <label for="pname">Project Name <span style='color:red'>*</span></label>
            <input type="text" id="pname" name="pname" value="<?php if (isset($_SESSION['d']['pname'])) echo $_SESSION['d']['pname']; ?>" placeholder="Project Name">
            <?php
            if (!empty($_SESSION['errors']['pname']))
                echo "<span style='color:red'>*" . $_SESSION['errors']['pname'] . "</span>";
            ?>
        </div>
        <div class="form-group">
            <div class="aa" style="display: inline !important;">
                <label for="skill"><b>Skills</b><span style='color:red'>*</span></label>
                <div class="check-option" style="display:flex; flex-wrap: wrap;">
                    <div class="skills">
                        <input type="checkbox" <?php if (!empty($skill) && in_array('php', $skill)) echo "checked"; ?> id="php" name="skills[]" value="php" <?php if (!empty($skill) && in_array('php', $skill)) echo "checked"; ?>>
                        <label for="php">PHP</label>
                    </div>
                    <div class="skills">
                        <input type="checkbox" id="mysql" name="skills[]" value="mysql" <?php if (!empty($skill) && in_array('mysql', $skill))  echo "checked"; ?>>
                        <label for="mysql">MySql</label>
                    </div>
                    <div class="skills">
                        <input type="checkbox" id="javascript" name="skills[]" value="javascript" <?php if (!empty($skill) && in_array('javascript', $skill))  echo "checked"; ?>>
                        <label for="javascript">Javascript</label>
                    </div>
                    <div class="skills">
                        <input type="checkbox" id="html" name="skills[]" value="html" <?php if (!empty($skill) && in_array('html', $skill))  echo "checked"; ?>>
                        <label for="html">HTML</label>
                    </div>
                    <div class="skills">
                        <input type="checkbox" id="css" name="skills[]" value="css" <?php if (!empty($skill) && in_array('html', $skill))  echo "checked"; ?>>
                        <label for="css">CSS</label>
                    </div>
                    <div class="skills">
                        <input type="checkbox" id="java" name="skills[]" value="java" <?php if (!empty($skill) && in_array('html', $skill))  echo "checked"; ?>>
                        <label for="java">JAVA</label>
                    </div>
                </div>
            </div>
            <?php
            if (!empty($_SESSION['errors']['lang']))
                echo "<span style='color:red'>*" . $_SESSION['errors']['lang'] . "</span>";
            ?>
        </div>

        <button type="submit" style="margin-top:10px;"><b>Add Project</b></button>
         <a href="pro_display.php?val=2" class="btn btn-default"> back</a>
    </form>
</div>

<?php

if (!$conn->connect_error)
    // echo "Connected successfully<br>";
    // echo $_SESSION['data']['email'];
    $error = array();
$d = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_SESSION['csrf_token']))
        if(!hash_equals($_SESSION['csrf_token'] , $_POST['csrf_token']))
        {
            echo "Token_invalid";
            exit();
        }
    // echo "hii<br>";
    // echo $_POST['pname'];
    if (empty($_POST['pname'])) {
        $error['pname'] = "Please enter a project name.";
        // echo $error['pname'];
    } else
        $d['pname'] = $conn->real_escape_string($_POST['pname']);
    if (empty($_POST['skills'])) {
        $error['lang'] = "Please enter a language.";
        // echo $error['lang'];   
    } else
        $d['lang'] = $_POST['skills'];
    if (!empty($error)) {
        $_SESSION['errors'] = $error;
        $_SESSION['d'] = $d;
        header("Location: project.php");
        die();
    }

    $pname = htmlspecialchars(strtolower($_POST['pname']));
    $lang = htmlspecialchars(strtolower(implode(',', $_POST['skills'])));
    echo $_SESSION['data']['email'], $pname, $lang;
    $stmt = $conn->prepare("INSERT INTO `projects`(`email`,`pname`,`lang`)
                                VALUES(?,?,?);");
    $stmt->bind_param("sss", $_SESSION['data']['email'], $pname, $lang);
    if (!$stmt->execute())
        die("Can't add the new project.");
    else {
        echo "<p style='color:green; font-size:large; font-weight:bold; text-align:center;'>Project added successfully!!</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'pro_display.php?val=2';
                }, 800);
              </script>";
        // echo  "PROJECT ADDED!!";
        // header("Location:pro_display.php?val=2");
    }

    $conn->close();
}

?>
<?php
unset($_SESSION['errors']);
unset($_SESSION["d"]);
include 'footer.php';
?>