<?php      
    include('connection.php'); 
    $username = $_POST['username'];  
    $password = $_POST['password'];
    $user_role = $_POST['user-role'];    
    echo $username, $password, $user_role;
        //to prevent from mysqli injection  
        $username = stripcslashes($username);  
        $password = stripcslashes($password);  
        $username = mysqli_real_escape_string($conn, $username);  
        $password = mysqli_real_escape_string($conn, $password);
      
        $sql = "SELECT * FROM students WHERE `name` = '$username' and `roll_no` = '$password'";  
        $result = mysqli_query($conn, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);  
          
        if($count == 1){  
            echo "<h1><center> Login successful </center></h1>";  
        }  
        else{  
            die("Failed to connect with MySQL: ". mysqli_connect_error());  
        }     
?>  