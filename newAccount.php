<html>

    <form method="post">
    Student ID: <input type="text" name="username"><br/>
    Email: <input type="text" name="email"><br/>
    Faculty: <input type="text" name="faculty"><br/>
    Password: <input type="password" name="password"><br/>
    Confirm Password: <input type="password" name="passwordConfirm"><br/>
    <input name = "Submit1" type="submit" value = "Submit">
    <?php
        if(isset($_POST["Submit1"]))
        {
            if(empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["faculty"]) || empty($_POST["password"]) || empty($_POST["passwordConfirm"]) || empty($_POST["username"])) {
                echo "<br><body>All fields must be filled</body>";
            }
            else if($_POST["password"] != $_POST["passwordConfirm"])
            {
                echo "<br> <body>Passwords must match</body>";
            }
            //check if username is unique
            //else if()
            
            //check if email is unique
            //else if

            else {
                //create account
                header("Location: mainDashboard.php");
                exit();
            }
        }
        ?>
    </form>


</html>