<html>
  <head>
      <style>
        button {
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

        .topnav .search-container button:hover {
          background: #ccc;
        }


	button[type = submit]{
		font-family: 'Roboto', sans-serif;
		font-size: 15;
		color: black;
		text-decoration: none;
		margin-left:auto;
		margin-right:auto;
	}
	
	.button button:hover {
          background: #ccc;
        }
      h1{
            font-family: 'Roboto', sans-serif;
            color: black;
            text-align: center;
            font-weight: 500;
            font-size:15px;
            line-height:15pt;
        }
      h2{
            font-family: 'Roboto', sans-serif;
            color: black;
            text-align: center;
            font-weight: 700;
            font-size:20px;
            line-height:30pt;
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
        </form>
        </div>
    </div>
    <?php
         session_start();
         include('./my_connect.php');
         $mysqli = get_mysqli_conn();   
         $eventID = $_SESSION['currentEvent'];
         $clubID = $event = $time = $date = $location = $club = $description = "";

         showEvent($mysqli, $eventID);

         if(isset($_POST["register"])){
            registerForEvent($mysqli, $eventID, $_SESSION['stID'], $clubID);
            sendMail($mysqli, $event, $_SESSION['stID'], $time, $date, $location, $club, $description);
            updateEventCap($mysqli, $eventID);
          }

         function showEvent($mysqli, $eventID) {
            $sql = "SELECT club_name, club_id, event_name, event_time, event_date, location, description, registration, curr_capacity, max_capacity 
            FROM events NATURAL JOIN club 
            WHERE event_ID = ".$eventID;
   
           $stmt = $mysqli->prepare($sql);
   
           $stmt -> execute();
   
           $stmt -> bind_result($club1, $cID, $evt, $time1, $date1, $loc, $descrip, $registration, $curCap, $maxCap);
           while ($stmt->fetch())
           {
           
           echo "<h2>".$evt."</h2>";
           if($registration == 1) {
            echo "<h1> Capacity: ".$curCap."/".$maxCap;
            if($curCap < $maxCap) {
                global $clubID, $event, $time, $date, $location, $club, $description;
                $clubID = $cID;
                $event = $evt;
                $time = $time1; 
                $date = $date1;
                $location = $loc;
                $club = $club1;
                $description = $descrip;

                echo "<form method = 'post'>
                <button name = 'register' type = 'submit'>Register</button>
                </form>";
            }
            else {
                echo "EVENT IS FULL";
            }
        }
           echo "<h1>Hosted by: ".$club1."<br></h1>";
           echo "<h1>Date: ".$date1."       Time:".$time1."<br></h1>";
           echo "<h1>Location: ".$loc."<br></h1>";
           echo "<h1>Description<br>".$descrip."<br></h1>";
           }
         }

         function registerForEvent($mysqli, $evtID, $stID, $clubID) {
            $sql = "INSERT INTO registeredEvent VALUES(".$stID.$evtID.", ".$stID.", ".$clubID.", ".$evtID.", 0)";
            $stmt = $mysqli->prepare($sql);

            $stmt -> execute();
            $message = "Registed!";
            echo "<script>alert('$message');</script>";
         }

         function sendMail($mysqli, $event, $studentID, $time, $date, $location, $club, $description) {
           $sql = "SELECT email FROM users WHERE student_ID = ".$studentID;
   
           $stmt = $mysqli->prepare($sql);
   
           $stmt -> execute();
   
           $stmt -> bind_result($email);
           while ($stmt->fetch())
           {
                $message = "Hello! \r\n\r\nYou have registed for the event ".$event."!\n
                This event takes place on ".$date." at ".$time.".\r\n
                Location: ".$location."\r\n
                Club: ".$club."\r\nDescription \r\n".$description;

               $message = wordwrap($message, 90, "\r\n");
               mail($email, "You registered for an event!", $message);

           }
         }
         function updateEventCap($mysqli, $eventID) {
             $sql = "UPDATE events 
             SET curr_capacity = (SELECT COUNT(*) FROM registeredEvent WHERE event_ID = ".$eventID.") 
             WHERE event_ID = ".$eventID;

             $stmt = $mysqli->prepare($sql);

            $stmt -> execute();
            header("Refresh:0");  
         }
    ?>   
</html>