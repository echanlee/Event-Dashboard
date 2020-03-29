<html>
<link rel="stylesheet" type="text/css" href="styleSheet.css">
<a style = "float:right" href="index.php">Logout</a>
  <div class="topnav">
    <a href="mainDashboard.php">Home</a>
    <a class="active" href="saved.php">Saved</a>
    <a href="advSearch.php">Advanced Search</a>
    <div class="search-container">
      <form action="search.php" method = "get">
        <input type="text" placeholder="Search.." name="search">
        <button type="submit"><i ></i></button>
      </form>
    </div>
  </div>
  <body>
  <?php
    session_start();
    include('./my_connect.php');
    $mysqli = get_mysqli_conn();
    displayEvents($mysqli, $_SESSION['stID']);

  if(isset($_POST["unsave"])){
    $eID = $_POST["unsave"];
    unsaveEvent($mysqli, $_SESSION['stID'], $eID);
  }

  if(isset($_POST["event"])){
    goToEvent($mysqli, $_POST["event"]);
  }

  function displayEvents($mysqli, $stID) {
    echo "<table>";
    echo "<tr>
            <th>Unsave</th>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
          </tr>";

    $sql = "SELECT event_ID, club_ID, event_name, event_date, location, description 
      FROM events 
      WHERE event_ID 
        in (select event_ID from savedEvent where student_ID = ".$stID.")
        ORDER BY event_date, event_time";

    $stmt = $mysqli->prepare($sql);

    $stmt -> execute();

    $stmt -> bind_result($eventID, $clubID, $event, $date, $location, $description);

    while ($stmt->fetch())
    {
      echo '<form method="post"> <tr>
            <th><button name = "unsave" type = "submit" value ='.$eventID.'>Unsave</button></th>
            <th><button name = "event" type = "submit" value = '.$eventID.'>'.$eventID.'</button></th>
            <th>'.$event.'</th>
            <th>'.$date.'</th>
            <th>'.$location.'</th>
            <th>'.$description.'</th>
          </tr></form>';
    }
  echo "</table>";
  }

  function unsaveEvent($mysqli, $stID, $evtID) {
    $sql = "DELETE FROM savedEvent WHERE saved_ID = ".$stID.$evtID;
    
    $stmt = $mysqli->prepare($sql);

    $stmt -> execute();
    header("Refresh:0");   
}

function goToEvent($mysqli, $evtID) {
  $_SESSION['currentEvent'] = $evtID;
  header("Location: event.php");
  exit;
}
  ?> 
  </body>
</html>