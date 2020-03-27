<html>
<link rel="stylesheet" type="text/css" href="styleSheet.css">
  <div class="topnav">
    <a href="mainDashboard.php">Home</a>
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
    echo $_SESSION['stID'] ;
    echo "<table>";
    echo "<tr>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
          </tr>";
    //add while loop check if events are 

    echo "</table>";
  ?> 
  </body>
</html>