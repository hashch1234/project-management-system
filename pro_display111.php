<?php
session_start();
$title = "Display projects page";
require_once 'dbconnect.php';
include 'header.php';
?>

<?php

if (isset($_GET['val'])) {
    $val = $_GET['val'];
    $_SESSION['val'] = $val;
    if (isset($_SESSION['search']))
        unset($_SESSION['search']);
    if (isset($_SESSION['skill']))
        unset($_SESSION['skill']);
}
$val = $_SESSION['val'];
// $result;
$search = "";

//ALL PROJECTS
if ($val == 1) {
    $display = "ALL PROJECTS";
    $sql = "SELECT * FROM `projects`";
}
//MY PROJECTS 
elseif ($val == 2) {
    $display = "MY PROJECTS";
    $sql = 'SELECT * FROM `projects` WHERE `email` = "' . $_SESSION['data']['email'] . '"';
}
//PROJECTS RELATED TO SKILL 
elseif ($val == 3) {
    $display = "PROJECTS RELATED TO MY  SKILL";
    $stmt = $conn->prepare("SELECT `skill` FROM `user_info` WHERE `email` = ?;");
    $stmt->bind_param("s", $_SESSION['data']['email']);
    if (!$stmt->execute())
        die("Can't execute");
    $result = $stmt->get_result()->fetch_assoc();
    // var_dump($result);
    // die();
    $skills = $result['skill'];
    // var_dump($skills);

    if (!empty($skills)) {
        $skills = explode(",", $skills);
        $whereClause = '';
        foreach ($skills as $s) {
            if ($whereClause !== '') {
                $whereClause .= ' OR ';
            }
            $whereClause .= "(`lang` LIKE '%$s,%' OR `lang`  LIKE '%$s') ";
        }
        // echo $whereClause;
        // die();

        $sql = "SELECT * FROM `projects` WHERE ($whereClause)";

        // echo  $sql."<br>";

    }
}

//search
if (isset($_POST['search_sub'])) {
    $search = $_POST['search'];
    $_SESSION['search'] = $search;
}

//filter skill
if (isset($_POST['skill_sub'])) { {
        // var_dump($_POST['skills']);
        if (isset($_POST['skills'])) {
            $skill = $_POST['skills'];
            $_SESSION['skill'] = $skill;
        } else
            unset($_SESSION['skill']);
    }
}
//making query for search and sort
//only search
if (isset($_SESSION['search']) && !isset($_SESSION['skill'])) {
    $search = $_SESSION['search'];
    if ($val == 1)
        $sql .= " WHERE";
    else
        $sql .= " AND";

    $sql .= " `pname` LIKE '%$search%' OR `lang` LIKE '%$search%'";
}
//only  filter skills
elseif (!isset($_SESSION['search']) && isset($_SESSION['skill'])) {
    $skill = $_SESSION['skill'];
    $clause = '';
    foreach ($skill as $s) {
        if ($clause !== '') {
            $clause .= ' OR ';
        }
        $clause .= "(`lang` LIKE '%$s,%' OR `lang`  LIKE '%$s') ";
    }

    if ($val == 1)
        $sql .= " WHERE";
    else
        $sql .= " AND";

    $sql .= " $clause";
    // $sql .= " WHERE (`lang` LIKE '%$skill,%' OR `lang` LIKE '%$skill')";
    // echo $skill;
}
//both search and skill
elseif (isset($_SESSION['search']) && isset($_SESSION['skill'])) {
    $search = $_SESSION['search'];
    $skill = $_SESSION['skill'];
    $clause = '';
    foreach ($skill as $s) {
        if ($clause !== '') {
            $clause .= ' OR ';
        }
        $clause .= "(`lang` LIKE '%$s,%' OR `lang`  LIKE '%$s') ";
    }
    if ($val == 1)
        $sql .= " WHERE";
    else
        $sql .= " AND";
    $sql .= " (`pname` LIKE '%$search%' OR `lang` LIKE '%$search%') AND ($clause) ";
}

if (isset($_GET["sort_name"])) {
    if ($_GET["sort_name"] == 1)
        $sql .= " ORDER BY `pname`";
    elseif ($_GET["sort_name"] == 2)
        $sql .= " ORDER BY `pname` desc";
}
if (isset($_GET["sort_lang"])) {
    if ($_GET["sort_lang"] == 1)
        $sql .= " ORDER BY `lang`";
    elseif ($_GET["sort_lang"] == 2)
        $sql .= " ORDER BY `lang` desc";
}

// w$sql .= ";";
// echo  $sql."<br>";
if (empty($skills) && $val == 3) {
    $n = 0;
} else {
    $stmt = $conn->prepare($sql);
    if (!$stmt->execute()) die("Can't execute");
    $result = $stmt->get_result();

    $n = $result->num_rows;
}

$pages = 5;
$tot_pages = ceil($n / $pages);

// $d = array();
// $data = array();
// $key = array();
$projects;
if ($n > 0) {
    $projects = 1;
    // while ($row = $result->fetch_assoc()) 
    // {

    //     $d['pname'] = $row['pname'];
    //     $d['lang'] = $row['lang'];
    //     $key[] = $row['pid'];
    //     $data[] = $d;
    // }
    //  print_r($data);
    //  print_r($key);

} else {
    $projects = 0;
}

?>
<?php
if (isset($_GET['pg']))
    $pg = $_GET['pg'];
else
    $pg = 1;
if (isset($_POST['search_sub']) || isset($_POST['skill_sub']))
    $pg = 1;
// echo $pg;
// while( $i < $tot_pages)
// {
$i = ((int)$pg - 1) * $pages;
$t = $i + $pages;
// echo $i;
?>


<div class="project-container">
    <br>
    <br>
    <div class="mpage">
        <form id="search-filter" action="pro_display.php" method="post">
            <!-- Search bar -->
            <div class="filter-search" style="width:90% ; margin:auto; display:flex; justify-content:space-between; width:80%;">
                <div class="col">
                    <input type="search" id="search" name="search" placeholder="search" value="<?php if (isset($_SESSION['search'])) echo $_SESSION['search']; ?>">
                    <button type="submit" class="btn btn-primary" name="search_sub">search</button>
                    <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
                </div>
                <div class="col">
                    <label><b>Skills: </b></label>&nbsp;&nbsp;
                    <input type="checkbox" id="php" name="skills[]" value="php" <?php if (!empty($skill) && in_array("php", $skill)) echo "checked"; ?>>
                    <label for="php">PHP</label>&nbsp;
                    <input type="checkbox" id="mysql" name="skills[]" value="mysql" <?php if (!empty($skill) && in_array("mysql", $skill)) echo "checked"; ?>>
                    <label for="mysql">MySql</label>&nbsp;
                    <input type="checkbox" id="javascript" name="skills[]" value="javascript" <?php if (!empty($skill) && in_array("javascript", $skill)) echo "checked"; ?>>
                    <label for="javascript">JavaScript</label>&nbsp;
                    <input type="checkbox" id="html" name="skills[]" value="html" <?php if (!empty($skill) && in_array("html", $skill)) echo "checked"; ?>>
                    <label for="html">HTML</label>&nbsp;
                    <input type="checkbox" id="css" name="skills[]" value="css" <?php if (!empty($skill) && in_array("css", $skill)) echo "checked"; ?>>
                    <label for="css">CSS</label>&nbsp;
                    <input type="checkbox" id="java" name="skills[]" value="java" <?php if (!empty($skill) && in_array("java", $skill)) echo "checked"; ?>>
                    <label for="java">Java</label>&nbsp;
                    &nbsp;
                    <button type="submit" class="btn btn-primary" name="skill_sub">submit</button>
                    <a href="<?php if ($val == 1) echo 'pro_display.php?val=1';
                                elseif ($val == 2) echo 'pro_display.php?val=2';
                                elseif ($val == 3) echo 'pro_display.php?val=3'; ?>" class="btn btn-default"> clear</a>
                </div>
            </div>
        </form>
        <br>
        <br>
        <h3 class="project-update" style="text-align:center;"><?php echo $display; ?></h3>

        <?php if ($val == 2) { ?> <a href="project.php" class="btn btn-success" style="margin-left:10%;">Add Project</a><?php } ?>
        <br>
        <div class="project_table">
            <?php if ($projects  != 0) { ?>
                <table class="table table-striped" style="width: 80%; margin:auto;">
                    <tr>
                        <th>
                            <a href="<?php if (isset($_GET['sort_name']) && $_GET['sort_name'] == 1) echo 'pro_display.php?sort_name=2';
                                        else echo 'pro_display.php?sort_name=1'; ?>"  class= 'ajax'><label><b>NAME<?php if (isset($_GET['sort_name']) && $_GET['sort_name'] == 2) echo "&#9660;";
                                                                                                else echo "&#9650;"; ?></b></label></a>
                        </th>
                        <th>
                            <a href="<?php if (isset($_GET['sort_lang']) && $_GET['sort_lang'] == 1) echo 'pro_display.php?sort_lang=2';
                                        else echo 'pro_display.php?sort_lang=1'; ?>" class="ajax"><label><b>LANGUAGE<?php if (isset($_GET['sort_lang']) && $_GET['sort_lang'] == 2) echo "&#9660;";
                                                                                                    else echo "&#9650;"; ?></b></label>
                        </th>
                        <?php if ($val == 2) { ?>
                            <th>
                                ACTIONS
                            </th>
                        <?php } ?>


                    </tr>

                    <br>

                    <?php
                    $sql .= " LIMIT $i , $pages";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt->execute()) die("Can't execute");
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {

                    ?>
                        <tr>
                            <!-- <php if ($i >= $n) break; ?>-->
                            <td>
                                <?php echo htmlspecialchars_decode($row['pname']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars_decode($row['lang']); ?>
                            </td>
                            <?php if ($val == 2) { ?>

                                <td>
                                    <!-- <form class="fm" action="project_upd.php" method="post">
                                <input type="hidden"  name="value" value="<?php echo htmlspecialchars($row['pid']); ?>">
                                <button type="submit" class="btn btn-primary" class="edit" style="text-decoration:none; color:white;">EDIT</button>
                            </form> -->
                                    <a href="project_upd.php?value=<?php echo htmlspecialchars($row['pid']); ?>" class="btn btn-warning">edit</a>

                                    <!-- <form class="fm" action="project_del.php" method="post">
                                <input type="hidden" name="value" value="<?php echo htmlspecialchars($row['pid']); ?>">
                                <button type="submit" class="btn btn-danger" class="delete" onclick="confirmDelete()" style="text-decoration:none; color:white;">DELETE</button>
                            </form> -->
                                    <a href="project_del.php?value=<?php echo htmlspecialchars($row['pid']); ?>" onclick="return confirm('Are you sure you want to delete this project?')" class="btn btn-danger">delete</a>

                                </td>
                            <?php } ?>
                        </tr>
                    <?php
                        $i++;
                    }

                    ?>
                </table>
        </div>

        <!-- <br>
        <button><a href="project.php" style="text-decoration:none; color:black;"><b>CREATE NEW PROJECT</b></a></button> -->
        <br>
        <div class="page">
            <nav aria-label="Page navigation example" style="text-align:center;">
                <ul class="pagination justify-content-center">
                    <?php
                    $pn = $pl = "";

                    for ($btn = 1; $btn <= $tot_pages; $btn++) {
                        if (isset($_GET['sort_name']))
                            if ($_GET['sort_name'] == 1)
                                $pn = "&sort_name=1";
                            elseif ($_GET['sort_name'] == 2)
                                $pn = "&sort_name=2";

                        if (isset($_GET['sort_lang']))
                            if ($_GET['sort_lang'] == 1)
                                $pn = "&sort_lang=1";
                            elseif ($_GET['sort_lang'] == 2)
                                $pn = "&sort_lang=2";
                        if ($btn == $pg)
                            $ac = "active";
                        else
                            $ac = "";
                        echo "<li class= '$ac' ><a href='pro_display.php?pg=" . $btn . $pn . $pl . "' class='ajax'>" . $btn . "</a></li>";
                    }

                    ?>
                </ul>
            </nav>
        </div>



    <?php
            } else {
                echo "<br><p class= 'no_projects'>No Projects</b></p><br>";
                // $_SERVER['PHP_SELF'];
                exit();
            }
            // echo $_SESSION['search'];
    ?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script>
    jQuery(document).ready(function($){
	if (Modernizr.history) {
		history.replaceState({ myTag: true }, null, window.location.href);
	}
	jQuery(document).on("click", "a.ajax", function (evt) {
		if (evt.which == 1) {
			if (!evt.ctrlKey && Modernizr.history) {
                var _href = jQuery(this).attr("href");
                console.log(_href);
				loadContent(_href, function (data) {
					history.pushState({ myTag: true }, null, _href);
				});
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return false;
		}
	});
   

    function loadContent(_href, callback) {
	jQuery.ajax({
		type: 'post',
		url: _href,
		success: function (data) {
			var data1 = jQuery(data).filter(".mpage").html();
			if (typeof (data1) == "undefined") { data1 = jQuery(".mpage > *", data); }
			jQuery(".mpage").html(data1);
			unsaved = false;
			// if (callback && typeof callback == "function") {
			// 	callback(data);
			// }
		}
	});
}
    function confirmDelete() {
        // Display a confirmation box
        var result = confirm("Are you sure you want to delete this item?");

        // If user confirms deletion, proceed with deletion
        if (result) {
            // Here you can write your code to delete the item
            // For demonstration purpose, let's just hide the item
            var itemToDelete = document.getElementById("itemToDelete");
            itemToDelete.style.display = "none";
        }
    }
});
</script>

<?php include 'footer.php' ?>