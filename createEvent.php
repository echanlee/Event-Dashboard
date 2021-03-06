<html>
<head>
      <style>
        button {
          background: none!important;
          border: none;
          padding: 0!important;
          color: #069;
          text-decoration: underline;
          cursor: pointer;
        }

        table{
          width:90%;
	  margin-left:auto;
	  margin-right:auto;
	  vertical-align: center;
          padding:0px;
	  font-family: 'Roboto', sans-serif;
	  font-size: 15;
	  color: black;
        }
	
	th, td{
          font-family: 'Roboto', sans-serif;
	  padding: 10px;
	  border-bottom: 1px solid #ddd;
	}

        .topnav {
          overflow: hidden;
          background-color: #000000;
        }

        .topnav a {
          font-family: 'Roboto', sans-serif;
          float: left;
          display: block;
          color: white;
          text-align: center;
          padding: 14px 16px;
          text-decoration: none;
          font-size: 17px;
        }

        .topnav a:hover {
          background-color: #ffbb00;
          color: black;
        }

        .topnav a.active {
          background-color: #ffbb00;
          color: black;
        }

        .topnav .search-container {
          float: right;
        }

        .topnav input[type=text] {
          padding: 6px;
          margin-top: 8px;
          font-size: 17px;
          border: none;
        }

        .topnav .search-container button {
          float: right;
          padding: 6px 10px;
          margin-top: 8px;
          margin-right: 16px;
          background: #ddd;
          font-size: 17px;
          border: none;
          cursor: pointer;
        }

        .topnav .search-container button:hover {
          background: #ccc;
        }

        @media screen and (max-width: 600px) {
          .topnav .search-container {
            float: none;
          }
          .topnav a, .topnav input[type=text], .topnav .search-container button {
            float: none;
            display: block;
            text-align: left;
            width: 100%;
            margin: 0;
            padding: 14px;
          }
          .topnav input[type=text] {
            border: 1px solid #ccc;  
          }
        }

      h1{
            font-family: 'Roboto', sans-serif;
            color: black;
            text-align: center;
            font-weight: 700;
            font-size:15px;
            line-height:15pt;
        }

       input[type=submit]{
            background-color: #ffbb00;
            border:none;
            color:#000000;
            padding: 5px 10px;
            text-align:center;
            text-decoration:none;
            display:block;
            width:100px;
            font-size: 16px;
            margin:4px 2px;
            cursor: pointer;
            border-radius:8px;
            margin-left:auto;
            margin-right:auto;

        }


      </style>  
</head>



  <div class="topnav">
    <a style = "float:right" href="index.php">Logout</a>
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
<h1>
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
            <select id="internal" name = "internal">
                <option value="1" selected="selected">Yes</option>
                <option value="0">No</option>
            </select><br><br>
        <label for="register">Registration Required?</label>
            <select id="register" name = "register">
                <option value="1" selected="selected">Yes</option>
                <option value="0">No</option>
            </select><br><br>
        <label for="maxcap">Max capacity:</label><br>
        <input type="number" name="maxcap" value = 10><br><br>
        <label for="description">Description:</label><br>
        <input type="text" name="description"><br><br>
        <input type="submit" value="Submit" name = "event">
</h1>
    </form> 
    <?php
        include('./my_connect.php');
        $mysqli = get_mysqli_conn();  
        if(isset($_POST["event"])) {
            $required = array('eventName', 'clubName', 'date', 'time', 'location', 'lvlEd', 'internal', 'register', 'maxcap', 'description');
            foreach($required as $field){
                if(!isset($_POST[$field])) {
                    echo 'Field '.$field."empty<br>";
                    $error = true;
                  }
                }
            if(!$error) {
                $clubID = checkClubName($mysqli, $_POST['clubName']);
                if($clubID){
                    createNewEvent($mysqli, $required, $clubID);
                }
                else {
                    echo "<h1>Club name is not valid.</h1>";
                }
            }
            else {
                echo "<h1>All fields must be filled in.</h1>";
            }
        }

        function createNewEvent($mysqli, $required, $clubID) {
            $id = time() % 16127;
            $sql = "INSERT INTO events VALUES(".$id.", ".$clubID.", \"".$_POST[$required[0]]."\", \"".$_POST[$required[3]].":00\", \""
                .$_POST[$required[2]]."\", \"".$_POST[$required[4]]."\", \"".$_POST[$required[5]]."\", \"".$_POST[$required[9]]."\", "
                .$_POST[$required[7]].",0, ".$_POST[$required[8]].", ".$_POST[$required[6]].")";
            $stmt = $mysqli->prepare($sql);

            $stmt->execute();
            echo "<h1>Event was created!</h1>";
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