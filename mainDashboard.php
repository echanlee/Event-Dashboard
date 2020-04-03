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

	button[type = submit]{
		font-family: 'Roboto', sans-serif;
		font-size: 15;
		color: black;
		margin: 10;
	
	}
	sep{
	    background-color: #ffbb00;
            border:none;
            color:#000000;
            padding: 0px 0px;
            text-align:center;
            text-decoration:none;
            display:block;
            width:200px;
            font-size: 16px;
            margin:10px 2px;
            cursor: pointer;
            border-radius:8px;
            margin-left:auto;
            margin-right:auto;

	}
	
	.button button:hover {
          background: #ccc;
        }



      </style>  
</head>


  <div class="topnav">
    <a style = "float:right" href="index.php">Logout</a>
    <a class="active" href="mainDashboard.php">Home</a>
    <a href="saved.php">Saved</a>
    <a href="advSearch.php">Advanced Search</a>
    <div class="search-container">
      <form action="search.php" method = "get">
        <input type="text" placeholder="Search.." name="search">
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>
  </div>
  <body>

  <?php
    session_start();
    include('./my_connect.php');
    $mysqli = get_mysqli_conn();      

    if(checkIfRep($mysqli, $_SESSION['stID']) > 0) {
      createEventButton();
    }

    displayEvents($mysqli, $_SESSION['stID'], $_SESSION['education']);
    $registerIDList = emailReminders($mysqli);
    if($registerIDList) {
      updateRegisteredEmail($mysqli, $registerIDList);
    }

  if(isset($_POST["save"])){
    list($eID, $cID) = explode("|", $_POST['save']);
    saveEvent($mysqli, $_SESSION['stID'], $cID, $eID);
  }

  if(isset($_POST["event"])) {
    $_SESSION['currentEvent'] = $_POST["event"];
    goToEvent($_POST["event"]);
  }

  function displayEvents($mysqli, $stID, $edLvl) {
    echo "<table>";
    echo "<tr>
            <th>Save</th>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
          </tr>";

    $sql = "SELECT event_ID, club_ID, event_name, event_date, location, description 
      FROM events where level_of_education = \"".$edLvl."\" 
      AND club_ID IN (SELECT club_ID FROM clubMembership WHERE student_ID = ".$stID.") 
      OR internal = 0 
      AND event_date >= CURRENT_DATE 
      AND level_of_education = \"".$edLvl."\"
      ORDER BY event_date, event_time";
    $stmt = $mysqli->prepare($sql);

    $stmt -> execute();

    $stmt -> bind_result($eventID, $clubID, $event, $date, $location, $description);

    while ($stmt->fetch())
    {
      echo '<form method="post"> <tr>
            <th><button name = "save" type = "submit" value ='.$eventID.'|'.$clubID.'>Save</button></th>
            <th><button name = "event" type = "submit" value = '.$eventID.'>'.$eventID.'</button></th>
            <td>'.$event.'</td>
            <td>'.$date.'</td>
            <td>'.$location.'</td>
            <td>'.$description.'</td>
          </tr></form>';
    }
  echo "</table>";
  }

  function saveEvent($mysqli, $stID, $clubID, $evtID) {
      $sql = "INSERT INTO savedEvent VALUES(".$stID.$evtID.", ".$stID.", ".$clubID.", ".$evtID.")";

      $stmt = $mysqli->prepare($sql);

      $stmt -> execute();
      $message = "Event number ".$evtID." was saved!";
      echo "<script>alert('$message');</script>";
  }

  function goToEvent($evtID) {
    echo "<script>window.location.href='event.php';</script>";
  }

  function checkIfRep($mysqli, $stID) {
    $sql = "SELECT COUNT(*) FROM `clubMembership` WHERE student_ID = ".$stID." AND member_type = \"Club Representative\"";
    $stmt = $mysqli->prepare($sql);

    $stmt -> execute();

    $stmt -> bind_result($count);

    while ($stmt->fetch())
    {
      return $count;
    }
  }

  function createEventButton(){
      echo "<form action='createEvent.php'>
	<sep>
        <button type='submit'>Create New Event</button>
	</sep>
      </form>";
  }

  function emailReminders($mysqli) {
    $sql = "SELECT u.email, e.event_name, e.event_time, e.location, r.register_ID 
    FROM registeredEvent r 
    NATURAL JOIN users u 
    NATURAL JOIN events e 
    WHERE event_date = CURDATE() + INTERVAL 1 DAY AND reminderEmail = 0";
    $stmt = $mysqli->prepare($sql);

    $stmt -> execute();

    $stmt -> bind_result($email, $event, $time, $location, $registerID);
    $registerIDList;
    
    while ($stmt->fetch())
    {
      $registerIDList = $registerID.".".$registerIDList; 
      $message = "Hello! \r\n\r\nYou have registed for the event ".$event." and it is happening tomorrow!\n
                This event takes place on at ".$time.".\r\n
                Location: ".$location."\r\n
                Look forward to seeing you there!";
      mail($email, "Reminder: You have a registered event tomorrow!", $message);
    }
    return $registerIDList;
  }

  function updateRegisteredEmail($mysqli, $registerIDList) {
    if(!isset($registerIDList)){
      exit();
    }
    $registerIDs = explode(".", $registerIDList);
    
    $sql = "UPDATE registeredEvent SET reminderEmail = 1 WHERE " ;

    for ($x = 0; $x < count($registerIDs)-1; $x++) {
      if($x == count($registerIDs)-2){
        $sql = $sql."register_ID = ".$registerIDs[$x];
      }
      else {
        $sql = $sql."register_ID = ".$registerIDs[$x]." OR ";
      }
    }
      $stmt = $mysqli->prepare($sql);

      $stmt -> execute();
  }
  ?> 
  </body>
</html>