<?php
session_start();
$title = "Display projects page";
require_once 'dbconnect.php';
include 'header.php';
?>
<?php if(isset($_GET['val']))
      {
        $val = $_GET[ 'val' ]; 
      }
      if( isset($val))
      {
        if($val == 1)
            $display = 'ALL  PROJECTS';
        elseif ($val==2) 
            $display = 'MY PROJECTS';
        elseif ($val==3)
            $display = 'PROJECTS RELATED TO MY SKILLS';
      }
?>

<div class="project-container">
<br><br>
    <div class="filter-search" style="width:90% ; margin:auto; display:flex; justify-content:space-between; width:80%;">
        <div class="col">
            <label for="search"><b>Search: </b></label>&nbsp;&nbsp;
            <input type="search" id="search" name="search" placeholder="search" value="<?php if (isset($_SESSION['search'])) echo $_SESSION['search']; ?>">
            <!-- <button type="submit" class="btn btn-primary" name="search_sub">search</button> -->
        </div>
        <div class="col">
            <label><b>Skills: </b></label>&nbsp;&nbsp;
            <input type="checkbox" id="php"   class="skill-checkbox" name="skills[]" value="php" <?php if (!empty($skill) && in_array("php", $skill)) echo "checked"; ?>>
            <label for="php">PHP</label>&nbsp;
            <input type="checkbox" id="mysql" class="skill-checkbox"  name="skills[]" value="mysql" <?php if (!empty($skill) && in_array("mysql", $skill)) echo "checked"; ?>>
            <label for="mysql">MySql</label>&nbsp;
            <input type="checkbox" id="javascript" class="skill-checkbox"  name="skills[]" value="javascript" <?php if (!empty($skill) && in_array("javascript", $skill)) echo "checked"; ?>>
            <label for="javascript">JavaScript</label>&nbsp;
            <input type="checkbox" id="html"  class="skill-checkbox" name="skills[]" value="html" <?php if (!empty($skill) && in_array("html", $skill)) echo "checked"; ?>>
            <label for="html">HTML</label>&nbsp;
            <input type="checkbox" id="css"  class="skill-checkbox" name="skills[]" value="css" <?php if (!empty($skill) && in_array("css", $skill)) echo "checked"; ?>>
            <label for="css">CSS</label>&nbsp;
            <input type="checkbox" id="java"  class="skill-checkbox" name="skills[]" value="java" <?php if (!empty($skill) && in_array("java", $skill)) echo "checked"; ?>>
            <label for="java">Java</label>&nbsp;
            &nbsp;
            <!-- <button type="submit" class="btn btn-primary" name="skill_sub">submit</button> -->
            <a href="<?php if ($val == 1) echo 'pro_display1.php?val=1';
                        elseif ($val == 2) echo 'pro_display1.php?val=2';
                        elseif ($val == 3) echo 'pro_display1.php?val=3'; ?>" class="btn btn-default"> clear</a>
        </div>
    </div>

    <br>
    <br>
    <h3 class="project-update" style="text-align:center;"><?php echo $display; ?></h3>

    <?php if ($val == 2) { ?> <a href="project.php" class="btn btn-success" style="margin-left:10%;">Add Project</a><?php } ?>
    <br>
    <div  class="project_table">

    </div>
    <br>
    <div class="page">

    </div>
</div>

<script>
    $(document).ready(function(){

        $('#search').on('keyup' , function(){
            var search = $(this).val();
            console.log(search);
        });

        $('.skill-checkbox').change(function(){
            var selected = $('input[type="checkbox"].skill-checkbox:checked');
            console.log(selected);
            var skills = [];
            selected.each(function() {
                    skills.push($(this).val());
                });
            console.log(skills);
        });
    });
</script>