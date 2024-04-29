<?php
session_start();
$title = "Update Project";
require_once 'dbconnect.php';
include 'header.php';

$pname = $lang = "";
if (isset($_GET['value'])) {
    $value = htmlspecialchars($_GET['value']);
    $_SESSION['pid'] = $value;
}
$value = $_SESSION['pid'];
// echo $value . "<br>";

$stmt = $conn->prepare("SELECT * FROM `projects` WHERE `pid` = ?");
$stmt->bind_param("s", $value);
if (!$stmt->execute()) {
    die("Can't execute");
}

$result = $stmt->get_result()->fetch_assoc();
// print_r($result);
$pname =  $result['pname'];
$skill = explode(',', $result['lang']); // convert from string to array



?>
<!-- <div id="form"> -->
<style>

</style>

<div class="container">
    <div class="project-update-container">
        <h3 class="project-update" style="text-align:center;">Update Project</h3><br>
        <form id="update-project" method="post">
        <input type="hidden" name="csrf_token" value= <?php echo $_SESSION['csrf_token']; ?>>
            <div class="form-group">
                <label for="pname">Project Name <span style="color:red">*</span></label><br>
                <input type="text" style="width: 350px; margin-right: auto;" id="pname" name="pname" value="<?php echo isset($_SESSION['info']['pname']) ? $_SESSION['info']['pname'] : $pname; ?>" placeholder="Project Name" class="form-control">
                <?php
                if (!empty($_SESSION['errors']['pname']))
                    echo "<span style='color:red; font-size:15px;'>*" . $_SESSION['errors']['pname'] . "</span>";
                ?>
            </div>
            <div class="form-group">
                <!-- <div class=""> -->
                <label for="skills"><b>Skills</b><span style='color:red'>*</span></label>
                <div class="check-option">
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
                        <input type="checkbox" id="css" name="skills[]" value="css" <?php if (!empty($skill) && in_array('css', $skill))  echo "checked"; ?>>
                        <label for="css">CSS</label>
                    </div>
                    <div class="skills">
                        <input type="checkbox" id="java" name="skills[]" value="java" <?php if (!empty($skill) && in_array('java', $skill))  echo "checked"; ?>>
                        <label for="java">JAVA</label>
                    </div>
                </div>
                <?php
                if (!empty($_SESSION['errors']['lang']))
                    echo "<span style='color:red; font-size:15px;'>*" . $_SESSION['errors']['lang'] . "</span>";
                ?>
            </div>
            <button class="btn btn-primary" type="submit" name="submit"><b>Update</b></button>
            <a href="pro_display.php?val=2" class="btn btn-default"> back</a>
        </form>
    </div>
</div>



<?php
$error = array();
$data = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION['csrf_token']))
        if(!hash_equals($_SESSION['csrf_token'] , $_POST['csrf_token']))
        {
            echo "Token_invalid";
            exit();
        }
    if (isset($_POST['submit'])) {
        if (empty($_POST['pname']))
            $error['pname'] = "Project name required";
        else
            $data['pname'] = htmlspecialchars($_POST['pname']);
        if (empty($_POST['skills']))
            $error['lang'] = "Language required";
        else
            $data['lang'] = $_POST['skills'];
        if (!empty($error)) {
            $_SESSION['info'] = $data;
            $_SESSION['errors'] = $error;
            header("Location: project_upd.php");
            die();
        }
        $pname = htmlspecialchars(strtolower($_POST['pname']));
        $lang = htmlspecialchars(strtolower(implode(',', $_POST['skills'])));
        // echo $lang;
        $value = $_SESSION['pid'];

        $stmt = $conn->prepare("UPDATE `projects`
                                    SET `pname` = ?, `lang` = ?
                                    WHERE `pid` = ?");
        $stmt->bind_param("sss", $pname, $lang, $value);
        echo $value;
        // die();

        if (!$stmt->execute()) {
            die('Error : (' . $stmt->errno . ') ' . $stmt->error);
        } else {
            // echo "Data updated successfully";
            echo "<p style='color:green; font-size:large; font-weight:bold; text-align:center;'>Project data updated successfully!!</p>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'pro_display.php?val=2';
                }, 500);
              </script>";
            // header("Location: pro_display.php?val=2");
            // exit(); // Make sure to exit after redirection
        }
    }
}
?>

<?php
unset($_SESSION['errors']);
unset($_SESSION['info']);
include 'footer.php'
?>