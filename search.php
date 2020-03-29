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
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>
  </div>
  <body>
  <?php
        session_start();
        include('./my_connect.php');
        $mysqli = get_mysqli_conn();
        if($_GET["search"]){
          $search = $_GET["search"];
          displayEvents($mysqli, $_SESSION['stID'], $_SESSION['education'], $search);
        }
        else {
          displayAdvancedSearch($mysqli, $_SESSION['stID'], $_SESSION['education'], $_GET['clubName'], $_GET['eventName'], 
            $_GET['date'], $_GET['location'], $_GET['clubType'], $_GET['description']);
        }
    
      if(isset($_POST["save"])){
        list($eID, $cID) = explode("|", $_POST['save']);
        saveEvent($mysqli, $_SESSION['stID'], $cID, $eID);
      }
    
      if(isset($_POST["event"])){
        goToEvent($mysqli, $_POST["event"]);
      }
      function displayEvents($mysqli, $stID, $edLvl, $search) {
        echo "<table>";
        echo "<tr>
                <th>Save</th>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Description</th>
              </tr>";
    
        $sql = "SELECT event_ID, club_ID, event_name, event_date, location, description FROM (
          SELECT event_ID, club_ID, event_name, event_date, location, description, event_time 
          FROM events where level_of_education = \"".$edLvl."\" 
          AND club_ID IN (SELECT club_ID FROM clubMembership WHERE student_ID = ".$stID.") 
          OR internal = 0 
          AND event_date >= CURRENT_DATE 
          AND level_of_education = \"".$edLvl."\")  a 
          WHERE event_name LIKE \"%".$search."%\" or description LIKE \"%".$search."%\"
          ORDER BY event_date, event_time";
        $stmt = $mysqli->prepare($sql);

        // (5) Execute prepared statement
        $stmt -> execute();
        $stmt->store_result();    
        if($stmt->num_rows > 0) {
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
        else{
          echo "</table><br>";
          echo "No results found for your search.";
        }
      }

      function displayAdvancedSearch($mysqli, $stID, $edLvl, $clubName, $eventName, $date, $location, $clubType, $description) {

        $sql = "SELECT event_ID, club_ID, event_name, event_date, location, description FROM (
          SELECT event_ID, club_ID, event_name, event_date, location, description, event_time, level_of_education 
          FROM events WHERE 
          club_ID IN (SELECT club_ID FROM clubMembership WHERE student_ID = ".$stID.") 
          OR internal = 0 
          AND event_date >= CURRENT_DATE) a NATURAL JOIN club c where level_of_education = \"".$edLvl."\" ";

       

        if($clubName){
          $sql = $sql. "AND club_name LIKE \"%".$clubName."%\" ";
        }
        if($eventName){
          $sql = $sql. "AND event_name LIKE \"%".$eventName."%\" ";
        }
        if($date){
          $sql = $sql. "AND event_date = \"".$date."\" ";
        }
        if($location) {
          $sql = $sql. "AND location LIKE \"%".$location."%\" ";
        }
        if($clubType) {
          $sql = $sql. "AND club_type LIKE \"%".$clubType."%\" ";
        }
        if($description) {
          $sql = $sql. "AND description LIKE \"%".$description."%\" ";
        }

        $sql = $sql. "ORDER BY event_date, event_time";
        $stmt = $mysqli->prepare($sql);

        
        echo "<table>";
        echo "<tr>
                <th>Save</th>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Description</th>
              </tr>";
        // (5) Execute prepared statement
        $stmt -> execute();
        $stmt->store_result(); 
        if($stmt->num_rows > 0) {
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
        else{
          echo "</table><br>";
          echo "No results found for your search.";
        }
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