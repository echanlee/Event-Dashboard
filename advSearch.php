<html>
<link rel="stylesheet" type="text/css" href="styleSheet.css">
    <a style = "float:right" href="index.php">Logout</a>
    <div class="topnav">
        <a href="mainDashboard.php">Home</a>
        <a href="saved.php">Saved</a>
        <a class = "active" href="advSearch.php">Advanced Search</a>
        <div class="search-container">
        <form action="search.php" method = "get">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        </div>
    </div>
    <form action="search.php" method="get">
        <label for="clubName">Club name:</label><br>
        <input type="text" name="clubName"><br>
        <label for="eventName">Event name:</label><br>
        <input type="text" name="eventName"><br><br>
        <label for="date">Date:</label><br>
        <input type="date" name="date"><br><br>
        <label for="location">Location:</label><br>
        <input type="text" name="location"><br><br>
        <label for="clubType">Club Type:</label><br>
        <input type="text" name="clubType"><br><br>
        <label for="description">Description:</label><br>
        <input type="text" name="description"><br><br>
        <input type="submit" value="Submit">
    </form> 
</html>