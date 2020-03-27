<html>
    <form method="post">
        Student ID: <input type="text" name="stID"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name = "Submit">
    </form>

    <?php
        session_start();
        if(isset($_POST["Submit"])) {
            
            $studentID = $_POST["stID"];
            $_SESSION['stID'] = $studentID;

            include('./my_connect.php');
            $mysqli = get_mysqli_conn();        
            
            $sql = "SELECT * FROM users WHERE student_ID = ?";
            $stmt = $mysqli->prepare($sql);

            $stmt -> bind_param('s', $studentID);

            // (5) Execute prepared statement
            $stmt -> execute();
        
            $stmt -> bind_result($std, $email, $faculty, $education, $password);

            while ($stmt->fetch())
            {
                if($password ==  $_POST["password"]){
                    $_SESSION['email'] = $email;
                    $_SESSION['faculty'] = $faculty;
                    $_SESSION['education'] = $education;
                    header("Location: mainDashboard.php");
                    exit();
                }
                else {
                    echo "<br><body>Invalid password</body>";
                    exit();
                }
            }
            echo "Invalid student ID";
        }

    ?>

</html>