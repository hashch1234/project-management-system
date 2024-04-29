<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="">
    <title> <?php if (empty($title)) echo "Project website";
            else echo $title; ?></title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="js/custom.js"></script>

</head>

<body>
<div id="successModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #f8c0a1e6;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color: #FFFFFF;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color: #fff; text-align: center; font-weight: bold; text-transform: uppercase;">Success</h4>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <p style="color: #fff; font-weight: bold;">Your Profile Has Been Updated!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="RemoveImageModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #f8c0a1e6;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color: #FFFFFF;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color: #fff; text-align: center; font-weight: bold; text-transform: uppercase;">Success</h4>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <p style="color: #fff; font-weight: bold;">Image Removed Successfully!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<div class="mpage-container">   
<div class="header-navbar">
        <?php if(isset($_SESSION['id'])) 
        {
        ?>
        <a href="profile.php">Profile</a>
        <div class="header-dropdown">
            <a class="header-dropdown">Projects</a>
            <div class="header-dropdown-content">
                <a href="pro_display.php?val=1">All Projects</a>
                <a href="pro_display.php?val=2">My Projects</a>
                <a href="pro_display.php?val=3">Project For My Skills</a>
                <!-- <a href="project.php">Add Project</a> -->
            </div>
        </div>
        <a href="logout.php">Logout</a>
        <?php 
        }
        else
        {
        ?>
        <a href="reg.php">Register</a>
        <a href="login.php">Login</a>
        <?php
        }
        ?>
</div>
