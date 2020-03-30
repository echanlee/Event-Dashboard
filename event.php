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
           
           echo "<h1>".$evt."</h1><br>";
           if($registration == 1) {
            echo "Capacity: ".$curCap."/".$maxCap;
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
           echo "Hosted by: ".$club1."<br>";
           echo "Date: ".$date1."       Time:".$time1."<br>";
           echo "Location: ".$loc."<br>";
           echo "Description<br>".$descrip."<br>";
           }
         }

         function registerForEvent($mysqli, $evtID, $stID, $clubID) {
            $sql = "INSERT INTO registeredEvent VALUES(".$stID.$evtID.", ".$stID.", ".$clubID.", ".$evtID.")";

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