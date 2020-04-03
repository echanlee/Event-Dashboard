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

      h1{
            font-family: 'Roboto', sans-serif;
            color: black;
            text-align: center;
            font-weight: 700;
            font-size:15px;
            line-height:15pt;
        }

       input[type=submit]{
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


      </style>  
</head>


    <div class="topnav">
        <a style = "float:right" href="index.php">Logout</a>
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
<h1>
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
</h1>
    </form> 
</html>