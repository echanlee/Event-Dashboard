<html>
<link rel="stylesheet" type="text/css" href="styleSheet.css">
<a style = "float:right" href="index.php">Logout</a>
  <div class="topnav">
    <a href="mainDashboard.php">Home</a>
    <a href="saved.php">Saved</a>
    <a href="advSearch.php">Advanced Search</a>
    <div class="search-container">
      <form action="search.php" method = "get">
        <input type="text" placeholder="Search.." name="search">
        <button type="submit"><i ></i></button>
      </form>
    </div>
  </div>

  <form method="post">
        <label for="eventName">Event name:</label><br>
        <input type="text" name="eventName"><br><br>
        <label for="clubName">Club name:</label><br>
        <input type="text" name="clubName"><br>
        <label for="date">Date:</label><br>
        <input type="date" name="date"><br><br>
        <label for="time">Time:</label><br>
        <input type="time" name="time"><br><br>
        <label for="location">Location:</label><br>
        <input type="text" name="location"><br><br>
        <label for="lvlEd">Level of Education:</label><br>
        <input type="text" name="lvlEd"><br><br>
        <label for="internal">Internal event only?</label>
            <select id="internal" name = "internal" value = "1">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select><br><br>
        <label for="register">Registration Required?</label>
            <select id="register" name = "register" value = "1">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select><br><br>
        <label for="maxcap">Max capacity:</label><br>
        <input type="number" name="maxcap" value = 10><br><br>
        <label for="description">Description:</label><br>
        <input type="text" name="description"><br><br>
        <input type="submit" value="Submit" name = "event">
    </form> 
    <?php
        include('./my_connect.php');
        $mysqli = get_mysqli_conn();  
        if(isset($_POST["event"])) {
            $required = array('eventName', 'clubName', 'date', 'time', 'location', 'lvlEd', 'internal', 'register', 'maxcap', 'description');
            foreach($required as $field){
                if(empty($_POST[$field])) {
                    $error = true;
                  }
                }
            if(!$error) {
                $clubID = checkClubName($mysqli, $_POST['clubName']);
                if($clubID){
                    createNewEvent($mysqli, $required, $clubID);
                }
                else {
                    echo "Club name is not valid.";
                }
            }
            else {
                echo "All fields must be filled in.";
            }
        }

        function createNewEvent($mysqli, $required, $clubID) {
            $id = time() % 16127;
            $sql = "INSERT INTO events VALUES(".$id.", ".$clubID.", \"".$_POST[$required[0]]."\", \"".$_POST[$required[3]].":00\", \""
                .$_POST[$required[2]]."\", \"".$_POST[$required[4]]."\", \"".$_POST[$required[5]]."\", \"".$_POST[$required[9]]."\", "
                .$_POST[$required[7]].",0, ".$_POST[$required[8]].", ".$_POST[$required[6]].")";
            $stmt = $mysqli->prepare($sql);
            echo $sql;

            $stmt->execute();
        }

        function checkClubName($mysqli, $club) {
            $sql = "SELECT club_ID FROM club WHERE club_name = \"".$club."\"";
            $stmt = $mysqli->prepare($sql);
            
            $stmt -> execute();

            $stmt -> bind_result($clubID);

            while ($stmt->fetch())
            {
            return $clubID;
            }
        }
    ?>
</html>