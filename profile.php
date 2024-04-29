<?php
session_start();
$title = "Profile Page";
require_once 'dbconnect.php';
include 'header.php';
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$gender = $dob = $city = $skill = $about = $photo = "";
if (isset($_SESSION['id'])) {
    $email = $_SESSION['data']['email'];
    echo $id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM `user_info` WHERE `email` = ? and `id` = ?;");
    $stmt->bind_param("ss", $email, $id);
    if (!$stmt->execute())
        die("Error Selecting User Info");

    $result = $stmt->get_result()->fetch_assoc();
    // print_r($result);
    $_SESSION['id'] = $result['id'];

    $fname = $result['fname'];
    $lname = $result['lname'];
    $gender = $result['gender'];
    $dob = $result['dob'];
    $city = $result['city'];
    $skill = explode(",", $result['skill']);
    // print_r($skill);
    $about = $result['about'];
    $is_photo = $result['profile_photo'];
    $conn->close();
}
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<div id="form">

    <div class="container pro">

        <?php
        if (isset($_SESSION['success']))
            echo "<div class= 'success-box'><p style = 'color:green; text-align : center; font-size:large;font-weight:bold;'>" . $_SESSION['success'] . "</p></div>";
        ?>
        <!-- <section class="container"> -->
        <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-md-8 col-md-offset-2">
            <div id="userform">
                <div id=success style="background-color: white; color:green;text-align : center; font-size:large;"> </div>
                <div id=error style="background-color: white; color:red;text-align : center; font-size:large ;width:auto"></div>
                <div class="tab-content profile">
                    <h2 class="text-uppercase text-center">Profile</h2>
                    <div class="profile-container">
                        <div class="mpage">
                            <div class="profile_pic">
                                <br>
                                <form id="remove_photo" method="post">
                                    <input type="hidden" name="csrf_token" value=<?php echo $_SESSION['csrf_token']; ?>>
                                    <div class="column">
                                        <?php
                                        if ($is_photo == 0)
                                            echo "<img id='default_photo' src = 'uploads/default_photo.jpg'><br>";
                                        else {
                                            echo "<img id = 'uploaded_photo' src = 'uploads/profile" . $_SESSION['id'] . ".jpg?'" . mt_rand() . ">";

                                        ?>
                                            <br>
                                            <button type="submit" class="btn btn-larger btn-block" id="remove" name="remove_photo" value="remove" onclick="return confirm('Remove profile photo?')">Remove</button>
                                        <?php } ?>
                                        <!-- <button type="submit" class="btn btn-larger btn-block" id="remove" name="photo">update</button> -->
                                    </div>
                                </form>
                            </div>
                            <br>
                            <form class="form" id="profile_form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value=<?php echo $_SESSION['csrf_token']; ?>>
                                <div class="column1">
                                    <div class="input-box">
                                        <label for="fname"><b>First Name </b></label>
                                        <input type="text" id="fname" name="fname" placeholder="firstname" value="<?php echo $fname; ?>">
                                        <?php
                                        // if (isset($_SESSION['errors']['fname']))
                                        //     echo "<span>*" . $_SESSION['errors']['fname'] . "</span>";
                                        ?>
                                        <span class="errors" id="erfname"></span>
                                    </div>

                                    <div class="input-box">
                                        <label for="lname"><b>Last Name </b></label>
                                        <input type="text" id="lname" name="lname" placeholder="lastname" value="<?php echo $lname; ?>">
                                        <span class="errors" id="erlname"></span>
                                    </div>
                                </div>
                                <div class="input-box">
                                    <label for="email"><b>Email </b></label>
                                    <input type="email" id="email" name="email" placeholder="abc@xyc.com" value="<?php if (isset($_SESSION['data']['email']))  echo $_SESSION['data']['email']; ?>" readonly>
                                </div>
                                <div class="column">
                                    <div class="input-box">
                                        <label for="city">City</label>
                                        <div class="select-box">

                                            <select id="city" name="city">
                                                <option value="">None</option>
                                                <option <?php if (!empty($city) && $city == "chandigarh") echo "selected"; ?> value="chandigarh">Chandigarh</option>
                                                <option <?php if (!empty($city) && $city == "mohali") echo "selected"; ?> value="mohali">Mohali</option>
                                                <option <?php if (!empty($city) && $city == "panchkula") echo "selected"; ?> value="panchkula">Panchkula</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label for="dob"><b>Date of Birth : </b></label>
                                        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                                        <span class="errors" id="erdob"></span>
                                    </div>
                                </div>
                                <div class="gender-box">
                                    <label for="gender"><b>Gender:</b></label>
                                    <div class="gender-option">
                                        <div class="gender">
                                            <input type="radio" <?php if ((!empty($gender)) && $gender == "male") echo "checked"; ?> id="male" name="gender" value="male" placeholder="male">
                                            <label for="male">Male</label>
                                        </div>
                                        <div class="gender">
                                            <input type="radio" <?php if ((!empty($gender)) && $gender == "female") echo "checked"; ?> id="female" name="gender" value="female" placeholder="female">
                                            <label for="female">Female</label>
                                        </div>
                                        <div class="gender">
                                            <input type="radio" <?php if ((!empty($gender)) && $gender == "others") echo "checked"; ?> id="others" name="gender" value="others" placeholder="others">
                                            <label for="others">Others</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="check-box">
                                    <label for="skills"><b>Skills: </b></label>
                                    <div class="check-option">
                                        <div class="skill">
                                            <input type="checkbox" <?php if (!empty($skill) && in_array('php', $skill)) echo "checked"; ?> id="php" name="skills[]" value="php" <?php if (!empty($skill) && in_array('php', $skill)) echo "checked"; ?>>
                                            <label for="php">PHP</label>
                                        </div>
                                        <div class="skill">
                                            <input type="checkbox" id="mysql" name="skills[]" value="mysql" <?php if (!empty($skill) && in_array('mysql', $skill))  echo "checked"; ?>>
                                            <label for="mysql">MySql</label>
                                        </div>
                                        <div class="skill">
                                            <input type="checkbox" id="javascript" name="skills[]" value="javascript" <?php if (!empty($skill) && in_array('javascript', $skill))  echo "checked"; ?>>
                                            <label for="javascript">Javascript</label>
                                        </div>
                                        <div class="skill">
                                            <input type="checkbox" id="html" name="skills[]" value="html" <?php if (!empty($skill) && in_array('html', $skill))  echo "checked"; ?>>
                                            <label for="html">HTML</label>
                                        </div>
                                        <div class="skill">
                                            <input type="checkbox" id="css" name="skills[]" value="css" <?php if (!empty($skill) && in_array('html', $skill))  echo "checked"; ?>>
                                            <label for="css">CSS</label>
                                        </div>
                                        <div class="skill">
                                            <input type="checkbox" id="java" name="skills[]" value="java" <?php if (!empty($skill) && in_array('html', $skill))  echo "checked"; ?>>
                                            <label for="java">JAVA</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-box">
                                    <!-- <label>About</label> -->
                                    <label for="about">About Yourself </label><br>
                                    <textarea name="about" id="about" rows="3" cols="30" placeholder="write here..."><?php echo htmlspecialchars(trim($about)); ?></textarea>
                                </div>
                                <div class="input-box">
                                    <label for="photo"><b>Update Profile Image: </b></label>
                                    <input type="file" id="photo" name="photo" placeholder="photo" value="<?php echo htmlspecialchars($photo); ?>">
                                    <span class="errors" id="erphoto"></span>
                                </div>
                                <br>

                                <h4><b>Change Password</b></h4>

                                <div class="column">
                                    <div class="input-box pswd">


                                        <label for="psword">Current Password</label>
                                        <input type="password" id="psword" name="psword">
                                        <span class="errors" id="erpsword"></span>

                                    </div>
                                    <!-- <input type="text" placeholder="Enter your city" required /> -->
                                    <div class="input-box pswd">
                                        <label for="chngpsword">Change Password</label>
                                        <input type="password" id="chngpsword" name="chngpsword">
                                        <span class="errors" id="erchngpsword"></span>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-larger btn-block" name="update" id="update" value="post">Update</button>
                            </form>
                        </div>
                        <!-- </section> -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
    unset($_SESSION['errors']);
    unset($_SESSION['data']['psword']);
    // unset($_SESSION['success']);
    ?>
</div>





    <?php include 'footer.php' ?>