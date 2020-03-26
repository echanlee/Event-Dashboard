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
            
            $sql = "SELECT password FROM users WHERE student_ID = ?";
            $stmt = $mysqli->prepare($sql);

            $stmt -> bind_param('s', $studentID);

            // (5) Execute prepared statement
            $stmt -> execute();
        
            $stmt -> bind_result($password);

            while ($stmt->fetch())
            {
                if($password ==  $_POST["password"]){
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