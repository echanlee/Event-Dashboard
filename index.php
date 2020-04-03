<html>
<head>
    <style>
       input[type=submit]{
            background-color: #ffbb00;
            border:none;
            color:#000000;
            padding: 5px 10px;
            text-align:center;
            text-decoration:none;
            display:block;
            width:30%;
            font-size: 16px;
            margin:4px 2px;
            cursor: pointer;
            border-radius:8px;
            margin-left:35%;
	    margin-top:2%;
        }
        input[type=text]{
            border:none;
            color:#000000;
            padding: 5px 10px;
            text-align:center;
            text-decoration:none;
            display:block;
            width:35%;
            font-size: 16px;
            cursor: pointer;
            margin-left:32.5%;
            margin-top: 1%;
            border: 1px solid #ffbb00;
        }
        input[type=password]{
            border:none;
            background-color: #ccc

            color:#000000;
            padding: 5px 10px;
            text-align:center;
            text-decoration:none;
            display:block;
            width:35%;
            font-size: 16px;
            cursor: pointer;
            margin-left:32.5%;
            margin-top: 1%;
            border: 1px solid #ffbb00;

        }


        .rectangle {
            height: 450px;
            width: 600px;
            background: white;
            align-content: center;
            position: fixed;
            top:25%;
            left:50%;
            margin-top:-100px;
            margin-left: -300px;

        }
        .txt{
                position: absolute;
                margin-left: 20px;
                margin-right: 20px;
        }
        h1{
            font-family: 'Roboto', sans-serif;
            color: black;
            text-align: center;
            font-weight: 700;
            font-size:15px;
            line-height:10pt;
        }
        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
            padding: 5px 5px;
        }

        img.avatar {
            width: 40%;
            border-radius: 50%;
        }

    </style>
</head>
<body>

<div class = "rectangle">
<div id="txt">

    <form method="post">
        <div class="imgcontainer">
            <img src="waterloo.png" alt="Avatar" class="avatar">
          </div>
<h1>
        Student ID: <input type="text" name="stID"><br>
        <br>
        Password: <input type="password" name="password"><br>
</h1>
        <input type="submit" name = "Submit">
    </form>

    <?php

        session_start();
        if(isset($_POST["Submit"])) {
            
            $studentID = $_POST["stID"];
            $_SESSION['stID'] = $studentID;

            include('./my_connect.php');
            $mysqli = get_mysqli_conn();        
            
            $sql = "SELECT * FROM users WHERE student_ID = ?";
            $stmt = $mysqli->prepare($sql);

            $stmt -> bind_param('s', $studentID);

            $stmt -> execute();
        
            $stmt -> bind_result($std, $email, $faculty, $education, $password);

            while ($stmt->fetch())
            {
                if($password ==  $_POST["password"]){
                    $_SESSION['email'] = $email;
                    $_SESSION['faculty'] = $faculty;
                    $_SESSION['education'] = $education;
                    header("Location: mainDashboard.php");
                    exit();
                }
                else {
                    echo "<br><body>Invalid password</body>";
                    exit();
                }
            }
            echo "Invalid student ID";
        }

    ?>
    
    </div>
</div>
</body>
</html>