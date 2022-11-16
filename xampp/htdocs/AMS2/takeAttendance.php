<?php
    session_start();
    include('connection.php');
    // echo $_POST['date'], $_POST['students'], $_POST['period'];
    if (isset($_POST['date']) && isset($_POST['students']) && isset($_POST['period'])){
        $date = $_POST['date'];
        $students = $_POST['students'];
        $period = $_POST['period'];
        // echo $date;
        $sql = "SELECT * FROM `attendance` WHERE `date` = '$date'";  
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $there = false;
                $att = json_decode($row['attendance'], true);
                for ($i=0; $i<count($att); $i++){
                    if ($att[$i]['period']['name'] == $period['name'] && $att[$i]['period']['periodStart'] == $period['periodStart']){
                        $att[$i]['present'] = $students;
                        $new_ecnoded = json_encode($att);
                        $there = true;
                        $sql = "UPDATE `attendance` SET `attendance`='$new_ecnoded' WHERE `date`='$date'";
                        if ($conn->query($sql) === TRUE) {
                            echo "Already found and Attendance replaced successfully!";
                        }else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                        break;
                    }
                }
                if (!$there){
                    $new = array("period" => $period, "present" => $students);
                    array_push($att, array("period" => $period, "present" => $students));
                    $new_ecnoded = json_encode($att);
                    $sql = "UPDATE `attendance` SET `attendance`='$new_ecnoded' WHERE `date`='$date'";
                    if ($conn->query($sql) === TRUE) {
                        echo "Attendance updated successfully!";
                    }else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
                break;
            }
        }else{
            $new = array("period" => $period, "present" => $students);
            // var_dump($new);
            $new = array($new);
            $new_ecnoded = json_encode($new);
            $sql = "INSERT INTO `attendance` (`date`, `attendance`)
            VALUES ('$date', '$new_ecnoded')";
            if ($conn->query($sql) === TRUE) {
                echo "Attendance created successfully!";
            }else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    $conn -> close();
    // print_r("HELLO");
    // $x = json_decode('[{"a":1,"b":2,"c":3,"d":4,"e":5},{"a":1,"b":2,"c":3}]', true);
    // var_dump($x);
    // echo "";
    // var_dump($x[0]['a']);

    // $sql = "SELECT * FROM `students`";
    // $result = $conn->query($sql);
    // $students = array();
    // if ($result->num_rows > 0) {
    //     // output data of each row
    //     while($row = $result->fetch_assoc()) {
    //         array_push($students, $row);
    //     }
    // }else {
        
    // }
    // var_dump($students);
    // $new = array("period" => (object) ["name" => 'DBS', "period" => 2], "present" => $students);
    // // var_dump($new);
    // $new = array($new);
    // // var_dump($new);
    // // echo json_encode($new);
    // $new_ecnoded = json_encode($new);
    // // $new = array($new);
    
    // $sql = "INSERT INTO `attendance` (`date`, `attendance`)
    // VALUES ('12/05/2022', '$new_ecnoded')";
    // echo $sql;
    // if ($conn->query($sql) === TRUE) {
    //     echo "\nAttendance created successfully!";
    // }else {
    //     echo "\nError: " . $sql . "<br>" . $conn->error;
    // }

?>