<!-- R. ABINAYA | IT2001 -->
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div id='container'>
            <div id='header'>
                Welcome to K.RAMAKRISHNAN College of Engineering <br> Student Attendance Manangement System
            </div>
            <div id='loginContainer'>
                <div class='heading'>STUDENT ATTENDANCE SYSTEM</div>
                <div class='heading2'>Login Panel</div>
                <form class='input-field' action = "" id="login-form" method = "POST">
                    <select class='form-control' name="user-role" id="user_role" placeholder="Enter Username">
                        <option value="" disabled selected>-- SELECT YOUR USER ROLE --</option>
                        <option value="STAFF">STAFF</option>
                        <option value="STUDENT">STUDENT</option>
                    </select>
                    <input id='username' class='form-control' type="text" name="username" placeholder="Enter Username" required />
                    <input id='password'  class='form-control' type="password" name="password" placeholder="Enter Password" required />
                    <input style='user-select:none' class='myBtn' type='submit' value='Login'/>
                </form>
            </div>
        </div>
        <div id='login-error' style="display: none;">
            !!Login failed. Please ensure username and password are correct!
        </div>
    <script src="jquery.js"></script>
    <script>
        $( "#login-form" ).submit(function( event ) {
            // alert( "Handler for .submit() called." );
            event.preventDefault();
            var un=document.getElementById('username').value;  
            var ps=document.getElementById('password').value; 
            var ur=document.getElementById('user_role').value;
            // console.log(ur, ur.length); 
            if(un.length=="" && ps.length=="") {  
                alert("User Name and Password fields are empty");  
                return false;  
            }  
            else  
            {  
                if(un.length=="") {  
                    alert("User Name is empty");  
                    return false;  
                }   
                if (ps.length=="") {  
                    alert("Password field is empty");  
                    return false;  
                }if (ur.length <= 0){
                    alert("Please select user role!");
                    return false;
                }this.submit();
            }     
        });
    </script>   
    </body>
</html>

<?php
    session_start();
    include('connection.php'); 
    // echo $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'];
    if( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout']) ){
        session_unset();
        // session_destroy();
    }
    else if( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['user-role'])) {
        $username = $_POST['username'];  
        $password = $_POST['password'];
        $user_role = $_POST['user-role'];   
        // echo $username, $password, $user_role;
            //to prevent from mysqli injection  
            $username = stripcslashes($username);  
            $password = stripcslashes($password);  
            $username = mysqli_real_escape_string($conn, $username);  
            $password = mysqli_real_escape_string($conn, $password);
        if ($user_role == 'STUDENT'){
            $sql = "SELECT * FROM students WHERE `name` = '$username' and `roll_no` = '$password'";  
            $result = mysqli_query($conn, $sql);  
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
            $count = mysqli_num_rows($result);  
            
            if($count == 1){  
                $_SESSION["username"] = $username;
                $_SESSION["password"] = $password;
                $_SESSION["user-role"] = $user_role;
                // echo "<script>alert('Logged in successfully!')</script>";
                header("Location: " . "AMS.php");
                exit();
            }  
            else{
                $_SESSION["login-error"] = true; 
                echo "<script>document.getElementById('login-error').style.display = 'block'</script>";  
                // echo "<script>alert('failed to login!')</script>";
            }
        }else{
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
            $_SESSION["user-role"] = $user_role;

            if ($username == "Rajeswari" && $password == 'Advisor'){
                header("Location: " . "AMS.php");
                exit();
            }else{
                $_SESSION["login-error"] = true; 
                echo "<script>document.getElementById('login-error').style.display = 'block'</script>";  
            }
        }
        
    }else if($_SESSION["login-error"]){
        $_SESSION["login-error"]= false;
        echo "<script>document.getElementById('login-error').style.display = 'block'</script>";  
        // echo "<script>alert('failed to login!')</script>";
    }
    else if ( strlen($_SESSION["username"]) > 0 && strlen($_SESSION["password"]) > 0 && strlen($_SESSION["user-role"]) > 0 ){
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
        $user_role = $_SESSION["user-role"];

        if ($user_role == 'STUDENT'){
            $sql = "SELECT * FROM students WHERE `name` = '$username' and `roll_no` = '$password'";  
            $result = mysqli_query($conn, $sql);  
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
            $count = mysqli_num_rows($result);  
            
            if($count == 1){
                // echo $username, ",", $password;
                // echo "<script>alert('Logged in successfully!')</script>";
                // echo "Location: ", $_SERVER['HTTP_HOST'], "/AMS.php";
                header("Location: " . "AMS.php");
                exit(); 
            }else{  
                // echo "<script>alert('failed to login!')</script>";  
            }
        }else{
           if ($username == "Rajeswari" && $password == 'Advisor'){
                header("Location: " . "AMS.php");
                exit();
            }else{

            }
        }
    }$conn->close();
?>