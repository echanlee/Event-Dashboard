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

	button[type = submit]{
		font-family: 'Roboto', sans-serif;
		font-size: 15;
		color: black;
		margin: 10;
	}
	
	.button button:hover {
          background: #ccc;
        }
      </style>  
</head>


  <div class="topnav">
 <a href="mainDashboard.php">Home</a>
    <a style = "float:right" href="index.php">Logout</a>
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
    $sql = "SELECT event_ID, club_ID, event_name, event_date, location, description 
    FROM events 
    WHERE event_ID 
      in (select event_ID from savedEvent where student_ID = ".$stID.")
      ORDER BY event_date, event_time";

    echo "<table>";
    echo "<tr>
            <th>Unsave</th>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
          </tr>";

    $stmt = $mysqli->prepare($sql);

    $stmt -> execute();

    $stmt -> bind_result($eventID, $clubID, $event, $date, $location, $description);

    while ($stmt->fetch())
    {
      echo '<form method="post"> <tr>
            <th><button name = "unsave" type = "submit" value ='.$eventID.'>Unsave</button></th>
            <th><button name = "event" type = "submit" value = '.$eventID.'>'.$eventID.'</button></th>
            <td>'.$event.'</td>
            <td>'.$date.'</td>
            <td>'.$location.'</td>
            <td>'.$description.'</td>
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