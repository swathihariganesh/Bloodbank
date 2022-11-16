<?php
    session_start();
    include('connection.php');
    if (isset($_POST['dates'])){
        if (isset($_POST['username'])) $username = $_POST['username'];
        else $username = $_SESSION['username'];
        $dates = $_POST['dates'];
        $data = [];
        for ($i=0; $i<count($dates); $i++){
            $sql = "SELECT * FROM `attendance` WHERE `date`='". $dates[$i] ."'";
            $result = $conn->query($sql);
            $students = array();
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $att = json_decode($row['attendance'], true);
                    array_push($data, $att);
                    break;
                }
            }else {
                array_push($data, array());
            }
        }
        echo json_encode($data);
    }else if (isset($_POST['all'])){
        $sql = "SELECT * FROM `attendance`";
        $result = $conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $att = json_decode($row['attendance'], true);
                array_push($data, $att);
            }
        }
        echo json_encode($data);
    }

    // $dates = ['07/04/2022', '08/04/2022', '09/04/2022', '10/04/2022', '11/04/2022', '12/04/2022', '13/04/2022', '14/04/2022'];
    // var_dump($dates);
    // $data = [];
    // for ($i=0; $i<count($dates); $i++){
    //     $sql = "SELECT * FROM `attendance` WHERE `date`='". $dates[$i] ."'";
    //     $result = $conn->query($sql);
    //     $students = array();
    //     if ($result->num_rows > 0) {
    //         // output data of each row
    //         while($row = $result->fetch_assoc()) {
    //             $att = json_decode($row['attendance'], true);
    //             array_push($data, $att);
    //             break;
    //         }
    //     }else {
    //         array_push($data, array());
    //     }
    // }
    // echo json_encode($data);
    $conn->close();
?>