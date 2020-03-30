<html>
<link rel="stylesheet" type="text/css" href="styleSheet.css">
<a style = "float:right" href="index.php">Logout</a>
  <div class="topnav">
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
    emailReminders($mysqli);

  if(isset($_POST["save"])){
    list($eID, $cID) = explode("|", $_POST['save']);
    saveEvent($mysqli, $_SESSION['stID'], $cID, $eID);
  }

  if(isset($_POST["event"])) {
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
        <button type='submit'>Create New Event</button>
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
    updateRegisteredEmail($mysqli, $registerIDList);
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