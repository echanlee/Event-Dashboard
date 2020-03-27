<html>
<link rel="stylesheet" type="text/css" href="styleSheet.css">
  <div class="topnav">
    <a class="active" href="mainDashboard.php">Home</a>
    <a href="saved.php">Saved</a>
    <div class="search-container">
      <form action="/action_page.php">
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

    displayEvents($mysqli, $_SESSION['stID'], $_SESSION['education']);

  if(isset($_POST["save"])){
    list($eID, $cID) = explode("|", $_POST['save']);
    saveEvent($mysqli, $_SESSION['stID'], $cID, $eID);
  }

  if(isset($_POST["event"])){
    goToEvent($mysqli, $_POST["event"]);
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

    // (5) Execute prepared statement
    $stmt -> execute();

    $stmt -> bind_result($eventID, $clubID, $event, $date, $location, $description);

    while ($stmt->fetch())
    {
      echo '<form method="post"> <tr>
            <th><button name = "save" type = "submit" value ='.$eventID.'|'.$clubID.'>Save</button></th>
            <th><button name = "event" type = "submit" value = '.$eventID.'>'.$eventID.'</button></th>
            <th>'.$event.'</th>
            <th>'.$date.'</th>
            <th>'.$location.'</th>
            <th>'.$description.'</th>
          </tr></form>';
    }
  echo "</table>";
  }

  function saveEvent($mysqli, $stID, $clubID, $evtID) {
      $sql = "INSERT INTO savedEvent VALUES(".$stID.$evtID.", ".$stID.", ".$clubID.", ".$evtID.")";
      // Prepared statement, stage 1: prepare
      $stmt = $mysqli->prepare($sql);

      $stmt -> execute();
      $message = "Event number ".$evtID." was saved!";
      echo "<script>alert('$message');</script>";
  }

  function goToEvent($mysqli, $evtID) {
    $_SESSION['currentEvent'] = $evtID;
    header("Location: event.php");
    exit;
  }

  ?> 
  </body>
</html>