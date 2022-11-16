<?php
    session_start();
    include('connection.php');
    // echo $_SESSION["username"], $_SESSION["password"], $_SESSION["user-role"];
    // echo $_SERVER['HTTP_HOST'], ":", $_SERVER['REQUEST_URI'];
    if (strlen($_SESSION["username"]) <= 0 || strlen($_SESSION["password"]) <= 0 || strlen($_SESSION["user-role"]) <= 0 ){
        // echo $_SERVER['HTTP_HOST'], ":", $_SERVER['REQUEST_URI'];
        header("Location: " . "index.php");
        exit();
    }else{
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
        $user_role = $_SESSION["user-role"];
        if ($user_role == 'STUDENT'){
            $sql = "SELECT * FROM students WHERE `name` = '$username' and `roll_no` = '$password'";  
            $result = mysqli_query($conn, $sql);  
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
            $count = mysqli_num_rows($result);  
            
            if($count == 1){
                // echo $username, $password, $user_role;
            }else{  
                header("Location: " . "index.php");
                exit(); 
            }
        }else{
            if ($username == "Rajeswari" && $password == 'Advisor'){
                
            }else{
                header("Location: " . "index.php");
                exit(); 
            }
        } 
    }
    
?>

<html>
    <head>
        <title>Homepage</title>
        <meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="AMS_style.css">
    </head>
    <body>
        <div id="notification" class="alert" style="display: none;">
            <span class="closebtn2" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="status_text"><strong>Copy:</strong> Link copied successfully!</div>
        </div>
        <div id='header'>
            <div>
            <div class='heading'>STUDENT ATTENDANCE SYSTEM</div>
            <div class='small_heading'>-BY Department of Information Technology</div>
            </div>
            <form action="index.php" method='post' style='display:flex; margin:0px;'>
                <input id='logout' name='logout' value='' style='display:none'/>
                <input type='submit' class='logout_btn' value='Logout'/>
            </form>
        </div>
        <div class='container'>
            <div id='myAttendanceCard' class='card' onclick="myAttendance()">
                <img src='attendance.png'/>
                <div id='my_attendance_heading'><?php if ($_SESSION['user-role'] == 'STAFF') echo "Student Attendance"; else echo "My Attendance";?></div>
            </div>

            <?php 
            if ($_SESSION['user-role'] === 'STAFF') echo "<div id='takeAttendanceCard' class='card' onclick='takeAttendance()'>
                <img src='class.png'/>
                <div>Take Attendance</div>
            </div>"
            ?>
             <?php 
            if ($_SESSION['user-role'] === 'STAFF') echo "<div id='viewAttendanceCard' class='card' onclick='viewAttendance()'>
                <img src='attendance.png'/>
                <div>View Attendance</div>
            </div>
            <div id='detailReportCard' class='card' onclick='getWholeReport()'>
                <img src='attendance.png'/>
                <div>Detailed Attendance</div>
            </div>"
            ?>
        </div>

        <div id='DR' style='display:none'>
            <div class='heading' >Stundents Attendance Detailed Report</div>
            <hr>
            <div class='date_holder'>
                <label for="startDate_DR">Start Date:</label>
                <input type="date" id="startDate_DR" name="startDate">
            </div>
            <div class='date_holder'>
                <label for="endDate_DR">End Date:</label>
                <input type="date" id="endDate_DR" name="endDate">
            </div>
            <div class='myBtn' onclick='calculate_detailed_report(getElementById("startDate_DR").value, getElementById("endDate_DR").value)' style='display:inline-block'>Calcukate</div>
            <table id='DR_TABLE'>
                <thead>
                    <tr><th id='DR_TABLE_DATE' class="TA_HEAD" colspan="6" style="font-size: 16px;">START to END</th></tr>
                    <tr>
                        <th class="TA_HEAD">
                            <div>ROLL NO</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>NAME</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>CONDUCTED HOURS</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>ATTENDED HOURS</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>LEAVE HOURS</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>PERCENTAGE</div>
                        </th>
                    </tr>
                </thead>
                <tbody id='DR_BODY'>
                </tbody>
            </table>
            <div class='myBtn' onclick="tableToExcel_download('DR_TABLE', 'sheet_1')" style='display:inline-block'>Download report</div>
        </div>

        <div id='VA_T' style='display:none'>
            <div class='heading' id='TA_Aheading'>View attendance for:</div>
            <div class='date_holder'>
                <label for="startDate_view">Date:</label>
                <input onchange="dateChangeView(this)" type="date" id="startDate_view" name="startDate">
            </div>
            <hr>
            <div class='heading'>Please select the period:</div>
            <div class='colorCode_holder'>
                <div class='colorCode'>
                    <div style='background-color:lightgreen'></div>
                    <div> - Attendance taken already</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:#FF9999'></div>
                    <div> - Attendance yet to take</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:green'></div>
                    <div> - Active attenendace</div>
                </div>
            </div>
            <table id='TA_ATABLE'>
                <thead>
                    <tr>
                        <th class="TA_HEAD">
                            <div>Time/</div>
                            <div style='font-size:14px;margin:0px;'>PERIOD</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>8.45AM-9.35AM</div>
                            <div>1</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>9.35AM-10.25AM</div>
                            <div>2</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>10.45AM-11.30AM</div>
                            <div>3</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>11.30AM-12.20PM</div>
                            <div>4</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>12.20PM-1.10PM</div>
                            <div>5</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>2.00PM-2.50PM</div>
                            <div>6</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>2.50PM-3.40PM</div>
                            <div>7</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>3.40PM-4.30PM</div>
                            <div>8</div>
                        </th>
                    </tr>
                </thead>
                <tbody id='TA_ABODY'>
                </tbody>
            </table>
            <hr>
            <div class='heading' id='TA_Ahead'>Please select the period to continue</div>
            <div class='colorCode_holder'>
                <div class='colorCode'>
                    <div style='background-color:rgb(255, 255, 130)'></div>
                    <div> - Attendance not taken</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:rgb(252, 72, 72)'></div>
                    <div> - Absent</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:green'></div>
                    <div> - Present</div>
                </div>
            </div>
            <table id='TA_Atake' style='visibility:hidden'>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Roll.No</th>
                        <th>Name</th>
                        <th>Present</th>
                    </tr>
                </thead>
                <tbody id='TA_Atake_body'>

                </tbody>
            </table>
        </div>


        <div id='VA' style='display:none'>
            <div class='heading' id='VA_head' >Please select the dates to view the students attendance:</div>
            <hr>
            <div class='date_holder'>
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" name="startDate">
            </div>
            <div class='date_holder'>
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" name="endDate">
            </div>
            <select class='form-control' name="student_list" id="student_list" placeholder="Select a student:">
                <option value="" disabled selected>-- SELECT A STUDENT --</option>
            </select>
            <div class='btn_container' style='justify-content: flex-start;' >
                <div class='myBtn' onclick='getAttendance(getElementById("startDate").value, getElementById("endDate").value)'>Get Report</div>
            </div>

            <div class='colorCode_holder'>
                <div class='colorCode'>
                    <div style='background-color:rgb(255, 255, 130)'></div>
                    <div> - Attendance not taken</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:rgb(252, 72, 72)'></div>
                    <div> - Absent</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:greenyellow'></div>
                    <div> - Present</div>
                </div>
            </div>

            <table id='VA_TABLE'>
                <thead>
                    <tr>
                        <th class="TA_HEAD">
                            <div>Time/</div>
                            <div style='font-size:14px;margin:0px;'>PERIOD</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>8.45AM-9.35AM</div>
                            <div>1</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>9.35AM-10.25AM</div>
                            <div>2</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>10.45AM-11.30AM</div>
                            <div>3</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>11.30AM-12.20PM</div>
                            <div>4</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>12.20PM-1.10PM</div>
                            <div>5</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>2.00PM-2.50PM</div>
                            <div>6</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>2.50PM-3.40PM</div>
                            <div>7</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>3.40PM-4.30PM</div>
                            <div>8</div>
                        </th>
                    </tr>
                </thead>
                <tbody id='VA_BODY'>
                </tbody>
            </table>
            <!-- onclick='tableToExcel("VA_TABLE","SHEET_1")' -->
            <div class='btn_container' id='downloadReport' style='justify-content: flex-start; display:none' >
                <div class='myBtn' onclick="tableToExcel()" >Download report</div>
            </div>
        </div>

        <div id='EA' style='display:none'>
            <div class='heading'>Please select the date to view <?php echo $_SESSION['username']; ?> attendance:</div>
            <label for="startDate_export">Start Date:</label>
            <input type="date" id="startDate_export" name="startDate">

            <label for="endDate_export">End Date:</label>
            <input type="date" id="endDate_export" name="endDate">
        </div>

        <div id='TA' style='display:none'>
            <div class='heading' id='TA_heading'>Take attendance for Today()</div>
            <!-- <div class='date_holder'>
                <label for="startDate">Date:</label>
                <input onchange="dateChange(this)" type="date" id="startDate_take" name="startDate">
            </div> -->
            <hr>
            <div class='heading'>Please select the period:</div>
            <div class='colorCode_holder'>
                <div class='colorCode'>
                    <div style='background-color:lightgreen'></div>
                    <div> - Attendance taken already</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:#FF9999'></div>
                    <div> - Attendance yet to take</div>
                </div>
                <div class='colorCode'>
                    <div style='background-color:green'></div>
                    <div> - Active attenendace</div>
                </div>
            </div>
            <table id='TA_TABLE'>
                <thead>
                    <tr>
                        <th class="TA_HEAD">
                            <div>Time/</div>
                            <div style='font-size:14px;margin:0px;'>PERIOD</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>8.45AM-9.35AM</div>
                            <div>1</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>9.35AM-10.25AM</div>
                            <div>2</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>10.45AM-11.30AM</div>
                            <div>3</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>11.30AM-12.20PM</div>
                            <div>4</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>12.20PM-1.10PM</div>
                            <div>5</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>2.00PM-2.50PM</div>
                            <div>6</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>2.50PM-3.40PM</div>
                            <div>7</div>
                        </th>
                        <th class="TA_HEAD">
                            <div>3.40PM-4.30PM</div>
                            <div>8</div>
                        </th>
                    </tr>
                </thead>
                <tbody id='TA_BODY'>
                </tbody>
            </table>
            <hr>
            <div class='heading' id='TA_head'>Please select the period to continue</div>
            <table id='TA_take' style='visibility:hidden'>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Roll.No</th>
                        <th>Name</th>
                        <th>Present</th>
                    </tr>
                </thead>
                <tbody id='TA_take_body'>

                </tbody>
            </table>
            <div class='btn_container'>
                <div class='myBtn' onclick='submitAttendance()' id='TA_submit' style="display:none; text-align:right">Submit Attendance</div>
            </div>
        </div>

        <table id='dummy_table' style='visibility:hidden'>
            <thead>
                <tr>
                    <th class="TA_HEAD">
                        <div>Time/</div>
                        <div style='font-size:14px;margin:0px;'>PERIOD</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>8.45AM-9.35AM</div>
                        <div>1</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>9.35AM-10.25AM</div>
                        <div>2</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>10.45AM-11.30AM</div>
                        <div>3</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>11.30AM-12.20PM</div>
                        <div>4</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>12.20PM-1.10PM</div>
                        <div>5</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>2.00PM-2.50PM</div>
                        <div>6</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>2.50PM-3.40PM</div>
                        <div>7</div>
                    </th>
                    <th class="TA_HEAD">
                        <div>3.40PM-4.30PM</div>
                        <div>8</div>
                    </th>
                </tr>
            </thead>
        <tbody id='DUMMY_BODY'>

        </tbody>
        </table>

    <script src="jquery.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
    <script>
        var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        var peroids = [
            [{name: 'HOLIDAY', period: '8'}],

            [{name: 'DBMS/', period: '2', periodStart:1},{name: 'WT LAB', period: '2', periodStart:3},{name: 'PS', period: '1', periodStart:5},{name: 'PS3C', period: '1', periodStart:6},{name: 'DBS', period: '1', periodStart:7},{name: 'AI', period: '1', periodStart:8}],

            [{name: 'OS', period: '1', periodStart:1},{name: 'DBS', period: '1', periodStart:2},{name: 'IPS', period: '1', periodStart:3},{name: 'PS3C', period: '1', periodStart:4},{name: 'WIP', period: '1', periodStart:5},{name: 'OS', period: '1', periodStart:6},{name: 'WIP', period: '1', periodStart:7},{name: 'PS', period: '1', periodStart:8}],

            [{name: 'DBMS/', period: '2', periodStart:1},{name: 'WT LAB', period: '2', periodStart:3},{name: 'AI', period: '1', periodStart:5},{name: 'PS', period: '1', periodStart:6},{name: 'IPS LAB', period: '2', periodStart:7}],

            [{name: 'WIP', period: '1', periodStart:1},{name: 'OS', period: '1', periodStart:2},{name: 'DBS', period: '1', periodStart:3},{name: 'PS', period: '1', periodStart:4},{name: 'OS', period: '1', periodStart:5},{name: 'PS3A', period: '1', periodStart:6},{name: 'AI', period: '1', periodStart:7},{name: 'WIP', period: '1', periodStart:8}],

            [{name: 'MINI', period: '2', periodStart:1}, {name: 'PROJECT', period: '2', periodStart:3},{name: 'AI', period: '1', periodStart:5},{name: 'PS', period: '1', periodStart:6},{name: 'PS3A', period: '1', periodStart:7},{name: 'DBS', period: '1', periodStart:8}],

            [{name: 'SKILL RANK', period: '1', periodStart:1},{name: 'HACKER RANK', period: '1', periodStart:2},{name: 'WIP', period: '1', periodStart:3},{name: 'PS', period: '1', periodStart:4},{name: 'PT', period: '1', periodStart:5},{name: 'LIB', period: '1', periodStart:6},{name: 'LEISURE', period: '1', periodStart:7},{name: 'OS', period: '1', periodStart:8}]
        ];
        var period = peroids[new Date().getDay()];
        var lastIndex = -1;
        var currentPeriod;
        var cache_data = null;
        var cache_dates = null;
        var attDate = new Date();
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        var user_role = <?php echo "'" . $_SESSION["user-role"] . "'"; ?>;
        var students = <?php
            $sql = "SELECT * FROM `students`";
            $result = $conn->query($sql);
            $students = array();
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                array_push($students, $row);
              }
            }else {
              
            }$conn -> close();
            echo json_encode($students);
        ?>;
        var username = '<?php echo $_SESSION['username'] ?>';
        var user_role = '<?php echo $_SESSION['user-role'] ?>';
        // console.log(username, user_role);
        // students = Jstudents);
        students.map((item,i) => {
            students[i]['present'] = 'true';
        });
        var dummyStudents = JSON.parse(JSON.stringify(students));
        // console.log(dummyStudents[3]);
        // console.log(user_role);

        function dateChange(e){
            var date = new Date(e.value);
            // console.log(date.toLocaleDateString());
            takeAttendance(date);
        }

        function dateChangeView(e){
            var date = getDateFromString(e.value);
            // console.log(date.toLocaleDateString());
            viewAttendance(date);
        }

        var getDaysArray = function(start, end) {
            for(var arr=[],dt=new Date(start); dt<=new Date(end); dt.setDate(dt.getDate()+1)){
                arr.push(new Date(dt).toLocaleDateString());
            }
            return arr;
        };

        function showNotification(text, info, bg){
            var status_text = document.getElementsByClassName('status_text')[0];
            status_text.innerHTML = `<strong>${info} </strong> ${text}`;
            status_text.style.backgroundColor = bg;
            $('#notification').fadeIn(2000, ()=>{
                document.querySelector('#notification').style.display = 'block';
                setTimeout($('#notification').fadeOut(4000, ()=>{
                document.querySelector('#notification').style.display = 'none';
                }), 7000);
            });
        }

        function getDateFromString2(str){
            date = str.replaceAll('/', '-');
            date = date.split("-");
            // console.log(`${date[0]} ${months[date[1]-1]} ${date[2]} 00:12:00 GMT`);
            return new Date(`${date[1]} ${months[date[0]-1]} ${date[2]} 00:12:00 GMT`);
        }

        function getDateFromString(str){
            date = str.replaceAll('/', '-');
            date = date.split("-");
            // console.log(`${date[0]} ${months[date[1]-1]} ${date[2]} 00:12:00 GMT`);
            return new Date(`${date[0]} ${months[date[1]-1]} ${date[2]} 00:12:00 GMT`);
        }
        // showNotification("Please select start and end date! And also make sure if the start date comes before the end date", 'Info!', 'blue');

        function getAttendance(start, end){
            // console.log(start,end);
            if (start.length != 0 && end.length != 0 && getDateFromString(start).getTime() <= getDateFromString(end).getTime()){
                console.log(getDateFromString(start));
                var daylist = getDaysArray(getDateFromString(start),getDateFromString(end));
                // daylist.map((v)=>v.toISOString().slice(0,10)).join("");
                // console.log(daylist);
                var sendData = {dates: daylist};
                if (user_role == 'STAFF'){
                    if (document.getElementById('student_list').value.length <= 0){
                        showNotification("Please select a student to view the attendance!", 'Info!', 'red');
                        return;
                    }
                    sendData['username'] = document.getElementById('student_list').value;
                }
                $.ajax({
                    url: 'getAttendance.php',
                    type: 'post',
                    dataType: 'json',
                    data: sendData,
                    success: (data) => {
                        // console.log(data);
                        cache_data = data;
                        cache_dates = daylist;
                        var table = document.getElementById('VA_BODY');
                        table.innerHTML = "";
                        daylist.map((date,i) => {
                            // console.log(date);
                            date = getOS() == 'Linux' ? getDateFromString(date) : getDateFromString2(date);
                            var ret = `<tr><th colspan='1' disabled style='pointer-events:none;'>${date.toLocaleDateString()}</th>`;
                            var uname = user_role === 'STAFF' ? document.getElementById('student_list').value : username;
                            peroids[date.getDay()].map((item, j) => {
                                var there = false;
                                data[i].map(d => {
                                    if (d.period.name == item.name && d.period.periodStart == item.periodStart){
                                        d.present.map(s => {
                                            // console.log(s);
                                            if (s.name == uname && s.present == 'true'){
                                                ret += `<td style='background-color:lightgreen' colspan="${item.period}">${item.name}</td>`
                                                there = true;
                                            }
                                        })
                                        if (!there) ret += `<td style='background-color:rgb(252, 72, 72)' colspan="${item.period}">${item.name}</td>`;
                                        there = true;
                                    }
                                })
                                if (!there) ret += `<td style='background-color:rgb(255, 255, 130)' colspan="${item.period}">${item.name}</td>`
                            })
                            ret += "</tr>";
                            table.innerHTML += ret;
                        });document.getElementById('downloadReport').style.display = 'flex';
                        // setTimeout(CreatePDFfromHTML(), 1000);
                    }
                })
            }else{
                showNotification("Please select start and end date! And also make sure if the start date comes before the end date", 'Info!', 'blue');
            }
        }

        function tableToExcel(){
            var daylist = cache_dates;
            var data = cache_data;
            // console.log(daylist);
            var table = document.getElementById('DUMMY_BODY');
            table.innerHTML = "";
            daylist.map((date,i) => {
                // console.log(date);
                date = getOS() == 'Linux' ? getDateFromString(date) : getDateFromString2(date);
                var ret = `<tr><th colspan='1' disabled style='pointer-events:none;'>${date.toLocaleDateString()}</th>`;
                var uname = user_role === 'STAFF' ? document.getElementById('student_list').value : username;
                peroids[date.getDay()].map((item, j) => {
                    var there = false;
                    data[i].map(d => {
                        if (d.period.name == item.name && d.period.periodStart == item.periodStart){
                            d.present.map(s => {
                                // console.log(s);
                                if (s.name == uname && s.present == 'true'){
                                    ret += `<td style='background-color:lightgreen' colspan="${item.period}">${item.name + " (P)"}</td>`
                                    there = true;
                                }
                            })
                            if (!there) ret += `<td style='background-color:rgb(252, 72, 72)' colspan="${item.period}">${item.name + " (A)"}</td>`;
                            there = true;
                        }
                    })
                    if (!there) ret += `<td style='background-color:rgb(255, 255, 130)' colspan="${item.period}">${item.name + " (A.N.T)"}</td>`
                })
                ret += "</tr>";
                table.innerHTML += ret;
            });
            // tableToExcel_download('dummy_table', 'sheet_1');
            fnExcelReport()
        }

        var tableToExcel_download = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
            return function(table, name) {
                if (!table.nodeType) table = document.getElementById(table);
                console.log(table);
                var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                window.location.href = uri + base64(format(template, ctx))
            }
        })()

        function getOS() {
            var userAgent = window.navigator.userAgent,
                platform = window.navigator?.userAgentData?.platform ?? window.navigator.platform,
                macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
                windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
                iosPlatforms = ['iPhone', 'iPad', 'iPod'],
                os = null;

            if (macosPlatforms.indexOf(platform) !== -1) {
                os = 'Mac OS';
            } else if (iosPlatforms.indexOf(platform) !== -1) {
                os = 'iOS';
            } else if (windowsPlatforms.indexOf(platform) !== -1) {
                os = 'Windows';
            } else if (/Android/.test(userAgent)) {
                os = 'Android';
            } else if (!os && /Linux/.test(platform)) {
                os = 'Linux';
            }

            return os;
            }

        function submitAttendance(){
            // console.log({date: attDate, students: students, period: currentPeriod});
            $.ajax({
                url: 'takeAttendance.php',
                type: 'post',
                dataType: 'text',
                data: {date: attDate, students: students, period: currentPeriod},
                success: (data) => {
                    // console.log(data);
                    showNotification("Attendance entered successfully!", 'Success!', 'lighgreen');
                    document.getElementsByClassName("container")[0].scrollIntoView();
                    takeAttendance();
                }
            })
        }

        function expandAttendance(index, e, date){
            // console.log(new Date(parseInt(date)).toLocaleDateString());
            if (lastIndex != -1){
                if (document.getElementById(period[lastIndex].name + lastIndex).getAttribute('data-taken'))
                    document.getElementById(period[lastIndex].name + lastIndex).style.backgroundColor = 'lightgreen';
                else
                    document.getElementById(period[lastIndex].name + lastIndex).style.backgroundColor = '#FF9999';
            }
            e.style.backgroundColor = "green";
            lastIndex = index;
            // console.log(cache_data);
            var there = false;
            cache_data[0].map(item => {
                if (item.period.name == period[index].name && item.period.periodStart == period[index].periodStart){
                    students = JSON.parse(JSON.stringify(item.present));
                    there = true;
                }
            })
            if (!there) {
                students = JSON.parse(JSON.stringify(dummyStudents));
            }
            currentPeriod = period[index];
            document.getElementById('TA_take').style.visibility = 'visible';
            document.getElementById('TA_submit').style.display = 'flex';
            document.getElementById('TA_head').innerHTML = "Attendance for: " + period[index].name;
            var studentTable = document.getElementById('TA_take_body');
            studentTable.innerHTML = "";
            students.map( (student, i) => {
                studentTable.innerHTML += `<tr><td>${student.id}</td><td>${student.roll_no}</td><td style='text-align:left'>${student.name}</td><td><input type="checkbox" onclick="applyAttendance(${i})" name="present" value="present" ${student.present && student.present != 'false' ? "checked" : ""}></td></tr>`
            })
        }

        function applyAttendance(i){
            if (students[i].present && students[i].present == 'true') students[i].present = 'false';
            else students[i].present = 'true';
            // console.log(students[i].present)
        }

        function takeAttendance(date = new Date()){
            resetAll();
            document.getElementById('takeAttendanceCard').style.backgroundColor = 'rgb(253, 221, 221)';
            lastIndex = -1;
            period = peroids[date.getDay()];
            attDate = date.toLocaleDateString(); 
            document.getElementById('TA').style.display = 'block';
            document.getElementById('TA_heading').innerHTML = `Take attendance for ${date.getDate() == new Date().getDate() && date.getMonth() == new Date().getMonth() ? "Today" : ""} [${date.toLocaleDateString()}]`;
            // console.log(period);
            var table = document.getElementById('TA_BODY');
            table.innerHTML = "";
            $.ajax({
                url: 'getAttendance.php',
                type: 'post',
                dataType: 'json',
                data: {dates:[date.toLocaleDateString()]},
                success: (data) => {
                    // console.log(data);
                    cache_data = data;
                    var ret = `<tr><th colspan='1' disabled style='pointer-events:none;'>${days[date.getDay()].toUpperCase()}</th>`;
                    period.map((item, i) => {
                        var there = false;
                        if (data.length > 0){
                            data[0].map(d => {
                                if (d.period.name == item.name && d.period.periodStart == item.periodStart){
                                    there = true;
                                    ret += `<td data-taken='taken' style='background-color:lightgreen' id="${item.name + i}" onclick="expandAttendance(${i}, this, ${date.getTime()})" colspan="${item.period}">${item.name}</td>`
                                }
                            })
                        }
                        if (!there) ret += `<td id="${item.name + i}" onclick="expandAttendance(${i}, this, ${date.getTime()})" colspan="${item.period}">${item.name}</td>`
                    })
                    ret += "</tr>";
                    table.innerHTML = ret;
                }
            })
            
        }

        // var periods = [50, 50, 45, 50, 50, 50, 50, 50]
        var periods = [60, 60, 60, 60, 60, 60, 60, 60]

        function getWholeReport(){
            resetAll();
            document.getElementById('detailReportCard').style.backgroundColor = 'rgb(253, 221, 221)';
            document.getElementById('DR').style.display = 'block';
        }

        function calculate_detailed_report(start, end){
            if (start.length != 0 && end.length != 0 && getDateFromString(start).getTime() <= getDateFromString(end).getTime()){
                console.log(getDateFromString(start));
                var daylist = getDaysArray(getDateFromString(start),getDateFromString(end));
                var studentsData = JSON.parse(JSON.stringify(dummyStudents));
                studentsData.map((item,i) => {
                    studentsData[i]['mins_present'] = 0;
                });
                var table = document.getElementById('DR_BODY');
                table.innerHTML = "";
                document.getElementById('DR_TABLE_DATE').innerHTML = getDateFromString(start).toLocaleDateString() + " - " + getDateFromString(end).toLocaleDateString();
                // daylist.map((v)=>v.toISOString().slice(0,10)).join("");
                // console.log(daylist);
                var sendData = {dates: daylist};
                $.ajax({
                    url: 'getAttendance.php',
                    type: 'post',
                    dataType: 'json',
                    data: sendData,
                    success: (data) => {
                        var totalMins = 0;
                        data.map(dt => {
                            dt.map(d => {
                                // console.log(d);
                                var currentPeriodMins = 0;
                                for (var i=parseInt(d['period'].periodStart)-1; i<(parseInt(d['period'].periodStart)-1) + parseInt(d['period'].period); i++){
                                    currentPeriodMins += periods[i];
                                    // console.log(periods[i], i)
                                }
                                totalMins += currentPeriodMins;
                                d.present.map((s,i) => {
                                    if (s.present && s.present == 'true'){
                                        studentsData[i].mins_present += currentPeriodMins;
                                    }
                                })
                            })
                        })
                        // console.log(totalMins);
                        // console.log(studentsData);
                        var totalHrs = (totalMins / 60);
                        var hrsStudent = [];
                        studentsData.map((item,i) => {
                            hrsStudent.push((studentsData[i]['mins_present']/60));
                        });
                        studentsData.map((s,i) => {
                            var perc = (s.mins_present * (100/totalMins))
                            // table.innerHTML += `<tr><td>${s.roll_no}</td><td>${s.name}</td><td>${totalHrs.toFixed(2)}</td><td>${hrsStudent[i].toFixed(2)}</td><td>${(totalHrs - hrsStudent[i]).toFixed(2)}</td><td>${(s.mins_present * (100/totalMins)).toFixed(2) + "%"}</td></tr>`
                            table.innerHTML += `<tr><td>${s.roll_no}</td><td style='text-align:left'>${s.name}</td><td>${parseInt(totalHrs)}</td><td>${parseInt(hrsStudent[i])}</td><td>${parseInt(totalHrs - hrsStudent[i])}</td><td>${ perc ? perc.toFixed(2) + "%" : "0.00%"}</td></tr>`
                        })
                    }
                })
            }else{
                showNotification("Please select start and end date! And also make sure if the start date comes before the end date", 'Info!', 'blue');
            }
        }

        function resetAll(){
            try{
                document.getElementById('viewAttendanceCard').style.backgroundColor = 'white';
                document.getElementById('takeAttendanceCard').style.backgroundColor = 'white';
                document.getElementById('detailReportCard').style.backgroundColor = 'white';
            }catch {}
            document.getElementById('myAttendanceCard').style.backgroundColor = 'white';
            document.getElementById('DR').style.display = 'none';
            document.getElementById('TA').style.display = 'none';
            document.getElementById('TA_BODY').innerHTML = "";
            document.getElementById('VA_T').style.display = 'none';
            document.getElementById('TA_ABODY').innerHTML = "";
            document.getElementById('VA').style.display = 'none';
            document.getElementById('EA').style.display = 'none';
            document.getElementById('TA_take').style.visibility = 'hidden';
            document.getElementById('TA_Atake').style.visibility = 'hidden';
            document.getElementById('TA_submit').style.display = 'none';
            document.getElementById('TA_take_body').innerHTML = "";
            document.getElementById('TA_Atake_body').innerHTML = "";
            document.getElementById('TA_head').innerHTML = "Please select the period to continue";
        }

        function getOption(name){
            return "<option value='" + name + "'>" + name + "</option>"
        }

        function expandAttendanceView(index, e, date){
            if (lastIndex != -1){
                if (document.getElementById(period[lastIndex].name + lastIndex).getAttribute('data-taken'))
                    document.getElementById(period[lastIndex].name + lastIndex).style.backgroundColor = 'lightgreen';
                else
                    document.getElementById(period[lastIndex].name + lastIndex).style.backgroundColor = '#FF9999';
            }
            e.style.backgroundColor = "green";
            lastIndex = index;
            // console.log(cache_data);
            var there = false;
            cache_data[0].map(item => {
                if (item.period.name == period[index].name && item.period.periodStart == period[index].periodStart){
                    students = JSON.parse(JSON.stringify(item.present));
                    there = true;
                }
            })
            if (!there) students = JSON.parse(JSON.stringify(dummyStudents));
            currentPeriod = period[index];
            document.getElementById('TA_Atake').style.visibility = 'visible';
            document.getElementById('TA_Ahead').innerHTML = "Attendance for: " + period[index].name;
            var studentTable = document.getElementById('TA_Atake_body');
            studentTable.innerHTML = "";
            var data = cache_data;
            students.map( (student, i) => {
                studentTable.innerHTML += `<tr><td>${student.id}</td><td>${student.roll_no}</td><td style='text-align:left'>${student.name}</td><td style="background-color:${student.present && student.present != 'false' && there ? "green" : there ? "red" : "yellow"}"></td></tr>`
            })
        }

        function viewAttendance(date = new Date()){
            resetAll();
            document.getElementById('viewAttendanceCard').style.backgroundColor = 'rgb(253, 221, 221)';
            lastIndex = -1;
            // var date = getDateFromString(document.getElementById('startDate_view').value);
            period = peroids[date.getDay()];
            attDate = date.toLocaleDateString(); 
            document.getElementById('VA_T').style.display = 'block';
            document.getElementById('TA_Aheading').innerHTML = `View attendance for ${date.getDate() == new Date().getDate() && date.getMonth() == new Date().getMonth() ? "Today" : ""} [${date.toLocaleDateString()}]`;
            // console.log(period);
            var table = document.getElementById('TA_ABODY');
            table.innerHTML = "";
            $.ajax({
                url: 'getAttendance.php',
                type: 'post',
                dataType: 'json',
                data: {dates:[date.toLocaleDateString()]},
                success: (data) => {
                    // console.log(data);
                    cache_data = data;
                    var ret = `<tr><th colspan='1' disabled style='pointer-events:none;'>${days[date.getDay()].toUpperCase()}</th>`;
                    period.map((item, i) => {
                        var there = false;
                        if (data.length > 0){
                            data[0].map(d => {
                                if (d.period.name == item.name && d.period.periodStart == item.periodStart){
                                    there = true;
                                    ret += `<td data-taken='taken' style='background-color:lightgreen' id="${item.name + i}" onclick="expandAttendanceView(${i}, this, ${date.getTime()})" colspan="${item.period}">${item.name}</td>`
                                }
                            })
                        }
                        if (!there) ret += `<td id="${item.name + i}" onclick="expandAttendanceView(${i}, this, ${date.getTime()})" colspan="${item.period}">${item.name}</td>`
                    })
                    ret += "</tr>";
                    table.innerHTML = ret;
                }
            })
        }

        function myAttendance(){
            resetAll();
            document.getElementById('myAttendanceCard').style.backgroundColor = 'rgb(253, 221, 221)';
            document.getElementById('VA').style.display = 'block';
            if (user_role == 'STAFF') {
                var student_div = document.getElementById('student_list');
                students.map( s => {
                    student_div.innerHTML += getOption(s.name);
                });
            }else{
                document.getElementById('student_list').style.display = 'none';
                document.getElementById('VA_head').innerHTML = `Please select the dates to 
            view ${username} attendance:`;
            }
        }

        function CreatePDFfromHTML(table) {
            var HTML_Width = $(table).width();
            var HTML_Height = $(table).height();
            var top_left_margin = 15;
            var PDF_Width = HTML_Width + (top_left_margin * 2);
            var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
            var canvas_image_width = HTML_Width;
            var canvas_image_height = HTML_Height;

            var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

            html2canvas($(table)[0]).then(function (canvas) {
                var imgData = canvas.toDataURL("image/jpeg", 1.0);
                var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
                for (var i = 1; i <= totalPDFPages; i++) { 
                    pdf.addPage(PDF_Width, PDF_Height);
                    pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                }
                pdf.save((user_role == 'STAFF' ? document.getElementById('student_list').value : username) + ".pdf");
                // $(".html-content").hide();
            });
        }

        function fnExcelReport() {
            var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
    
            tab_text = tab_text + '<x:Name>Attendance Report</x:Name>';
    
            tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
            tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
    
            tab_text = tab_text + "<table>";
            tab_text = tab_text + $('#dummy_table').html();
            tab_text = tab_text + '</table></body></html>';
    
            var data_type = 'data:application/vnd.ms-excel';
    
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
    
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                if (window.navigator.msSaveBlob) {
                    var blob = new Blob([tab_text], {
                        type: "application/csv;charset=utf-8;"
                    });
                    navigator.msSaveBlob(blob, (user_role == 'STAFF' ? document.getElementById('student_list').value : username) + ".xls");
                }
            } else {
                window.location.href =  data_type + ', ' + encodeURIComponent(tab_text);
                // $('#tests').attr('download', 'Test file.xls');
            }
        }
        // console.log(getOS())
    </script>   
    </body>
</html>
