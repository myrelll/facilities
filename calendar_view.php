<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('vendor/inc/config.php');
include('admin/vendor/inc/config.php');

$host = "localhost";
$username = "root";
$password = "";
$database = "vehiclebookings";

try {
  $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  date_default_timezone_set("UTC");

  include('vendor/inc/checklogin.php');

  function tableExists($dbh, $tableName) {
      $stmt = $dbh->prepare("SHOW TABLES LIKE :tableName");
      $stmt->execute([':tableName' => $tableName]);
      return $stmt->rowCount() > 0;
  }

} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  exit;
}

$v_id = $_GET['v_id'] ?? '';

if (!empty($v_id)) {
    $stmt = $db->prepare("SELECT v_name FROM tms_vehicle WHERE v_id = ?");
    $stmt->bindParam(1, $v_id, PDO::PARAM_INT);
    $stmt->execute();
    $v_name = $stmt->fetchColumn();
}
$currentYear = date('Y');
?>
 <?php
    $ret = "SELECT * FROM tms_vehicle ORDER BY RAND()";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    $count = 0;
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Facility Calendar</title>
    <script src="js/daypilot/daypilot-all.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style type="text/css">
        body {
            padding: 25px;
            margin: 0;
            background-color: white;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #164863;
        }

        .header {
            color: #164863;
            text-align: center;
            padding: 5px 0;
            margin-top: 5px;
            z-index: 1;
        }

        .header h1 {
            margin: 0;
            font-size: 14pt;
        }

        #buttonHome {
            background-color: #D2F6FF;
            border: none;
            color: #4889cf;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 5px;
            display: block;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        #buttonHome:hover {
            background-color: #427D9D;
            color: white;
        }

        .calendar-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            margin-top: 2px;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            color: #164863;
            background-color:white;
        }

        .month-container {
            width: 30%;
            margin-right: 20px;
            background-color: #78cee3;
            border-radius: 4px;
            padding: 5px;
            height: 250px;;
        }

        .month-list-container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 10px; 
        }

        .month-item-container {
            width: calc(27% - 10px);
            margin-bottom: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .month-item {
            cursor: pointer;
            background-color: #faf5f5;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s;
            width: 50px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            overflow: hidden;
            font-size: 14px;
            font-weight: bold;
        }

        .month-item:hover {
            background-color: #F8E1CC;
            color: #E38732;
        }

        .month-item.active {
            background-color: #164863;
            color: white;
        }

        .trending-list-container {
            width: 100%;
            margin-top: 20px;
            background-color: #78cee3;
            border-radius: 4px;
            padding: 5px;
            margin-left: -5px;
            height: 270px;
        }

        .trending-item {
        width: calc(25% - 10px);
        background-color: #78cee3;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        text-align: center;
        animation: fadeInUp 1s ease-in-out;
        transition: transform 0.3s, background-color 0.3s;
        position:relative;
        }
        .trending-list-container h3 {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
            font-size: 14px;
            color: #164863;
        }
        .trending-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .trending-item .thumb {
        position: relative; 
        width: 47px;
        height: 47px;
        margin-bottom: 10px;
        background-color: #D2F6FF;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        box-sizing: border-box;
        transition: background-color 0.3s ease;
        }
        .trending-item:hover {
            background-color: #F8E1CC;
            transform: scale(1.1);
            border-radius: 10px;
        }

        .trending-item .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
        }
        .trending-item .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #F8E1CC;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 15px;
        color: navy blue;
        font-weight: bold;
        transition: opacity 0.3s ease;
        opacity: 0; 
        }
        .trending-item:hover .overlay {
        opacity: 1;
        font-size: 10px;
        font-weight: 300;
        color: #777;
        align-items: center;
        }
        .trending-item:hover .thumb {
        background-color: #F8E1CC;
        font-size: 11px;
        
        }
        .trending-item .down-content {
        width: 100%;
        word-break: break-word;
        }
        .calendar-wrapper {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background-color: #78cee3;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        color: #164863;
        width: 50%;
        }

        #dpMonth {
            background-color: #fff;
            font-weight: bold;
            font-size: 11px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
        }

        .event-details-popup {
            display: none;
            position: absolute;
            background-color: #D2F6FF;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 250px;
            font-size: 13px;
        }

        .event-details-popup.show {
            display: block;
        }

        .event-details-popup-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
        }
        .close-popup {
        cursor: pointer;
        background-color: #9BBEC8;
        color: white;
        padding: 5px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        width: 30px;
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        margin-left:5px;
        margin-right:15px; 
        }

.close-popup:hover {
    background-color: #427D9D;
}

/* Additional styling for the popup content for better alignment */
.event-details-popup-content {
    margin-top: 5px;
    flex-grow: 1;
    text-align: left; /* Ensure content is left-aligned */
}


        .custom-event {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            z-index: 10;
        }

        .year-navigation {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .year-nav-btn {
            background-color: #427D9D;
            color: #cccc;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
            font-size: 12px;
            width: 85px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
        }

        .year-nav-btn:hover {
            background-color: #D2F6FF;
            color: #4889cf;
            transform: scale(1.2);
        }

        .year-box {
            background-color: #164863;
            color: white;
            padding: 10px;
            margin: 0 5px;
            font-size: 16px;
            border-radius: 4px;
            width: 100px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s;
        }

        .year-box:hover {
            transform: scale(1);
        }

        .year-nav-btn,
        .year-box {
            margin: 0 5px;
        }

        #selectedYear {
            background-color: #D2F6FF;
            color: #4889cf;
            padding: 10px;
            margin: 0 5px;
            font-size: 16px;
            border-radius: 4px;
            width: 70px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s;
            font-weight: bold;
        }

        #selectedYear:hover {
            background-color: #427D9D;
            color: white;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="main">
    <div class="calendar-container">
        <div class="month-container">
            <div class="year-navigation">
                <button id="prevYear" class="year-nav-btn" onclick="changeYear(-1)"><?php echo date('Y') - 1; ?></button>
                <span id="selectedYear" class="year-box"><?php echo date('Y'); ?></span>
                <button id="nextYear" class="year-nav-btn" onclick="changeYear(1)"><?php echo date('Y') + 1; ?></button>
            </div>

            <div class="content-wrapper">
                <div class="month-list-container">
                    <?php
                    $months = [
                        "JAN", "FEB", "MAR", "APR", "MAY", "JUN", 
                        "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"
                    ];

                    foreach ($months as $index => $month) {
                        echo '<div class="month-item-container">';
                        echo '<div class="month-item" onclick="navigateToMonth(' . $index . ')">' . $month . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <!-- Trending Items Container -->
               <!-- Trending Items Container -->
               <div class="trending-list-container">
    <h3>Facilities</h3>
    <div class="trending-items">
        <?php while ($row = $res->fetch_object()) { ?>
            <div class="trending-item" onclick="navigateToSchedule(<?php echo $row->v_id; ?>)">
                <div class="thumb">
                    <img src="vendor/img/<?php echo $row->v_dpic; ?>" alt="">
                    <div class="overlay"><?php echo $row->v_name; ?></div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

            </div>
        </div>
       
 
<!-- Event List Containers -->

        <div class="calendar-wrapper">
        <div class="header">
                <h1 id="calendarTitle">
                    <?php echo isset($v_name) ? $v_name : 'Facility'; ?><br>
                    <span id="calendarMonth"><?php echo date('F'); ?> Calendar</span>
                </h1>
            </div>
            <div id="dpMonth"></div>
            <button id="buttonHome" onclick="goBack()">BACK TO HOME</button>
        </div>
    </div>

    <div id="eventDetailsPopup" class="event-details-popup">
    <div class="event-details-popup-header">
        <button class="close-popup" onclick="closeEventDetailsPopup()">X</button>
        <div id="eventDetailsContent" class="event-details-popup-content"></div>
    </div>
</div>



    </div>
</div>

<script type="text/javascript">
    var dpMonth = new DayPilot.Month("dpMonth");
    dpMonth.startDate = new DayPilot.Date(); 
    dpMonth.showWeekend = true;
    dpMonth.cellHeight = 70;

    dpMonth.onBeforeEventRender = args => {
        if (!args.data.backColor) {
            args.data.backColor = "#6aa84f";
        }
        args.data.borderColor = "darker";
        args.data.fontColor = "#fff";
        args.data.barHidden = true;
        args.data.cssClass = "custom-event";
        args.data.moveDisabled = true;
        args.data.html = `<div style='background-color: ${args.data.backColor}; padding: 0px; border-radius: 0px;'>
                          <div style='font-size: 9px; color: #fff;'>${args.data.text}</div>
                        </div>`;

        args.data.areas = [
            {
                right: 2,
                top: 2,
                width: 2,
                height: 2,
                visibility: "Hover"
            }
        ];
    };

    dpMonth.onEventClick = function(args) {
        var e = args.e;
        var popup = document.getElementById('eventDetailsPopup');
        var content = document.getElementById('eventDetailsContent');

        if (popup && content) {
            content.innerHTML = `
                <p><strong>Reserved By:</strong> ${e.data.reservee}</p>
                <p><strong>Event:</strong> ${e.data.text}</p>
                <p><strong>Start:</strong> ${new Date(e.data.start).toLocaleString()}</p>
                <p><strong>End:</strong> ${new Date(e.data.end).toLocaleString()}</p>
            `;

            var rect = args.originalEvent.target.getBoundingClientRect();
            var calendarRect = dpMonth.nav.top.getBoundingClientRect();
            var top = rect.top - calendarRect.top + window.scrollY;
            var left = rect.left - calendarRect.left + rect.width + 400; 

            popup.style.top = top + 'px';
            popup.style.left = left + 'px';

            var popupRect = popup.getBoundingClientRect();
            if (popupRect.right > window.innerWidth) {
                popup.style.left = (left - popupRect.width - rect.width - 20) + 'px';
            }

            popup.classList.add('show');
        } else {
            console.error("Popup or content element not found");
        }
    };

    function closeEventDetailsPopup() {
        var popup = document.getElementById('eventDetailsPopup');
        if (popup) {
            popup.classList.remove('show');
        } else {
            console.error("Popup element not found");
        }
    }

    dpMonth.init();

    function getMonthName(date) {
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return monthNames[date.getMonth()];
    }

    function updateHeaderWithMonth(date) {
        const currentMonth = getMonthName(date);
        document.getElementById("calendarMonth").innerText = currentMonth + " Calendar";
    }


    // Function to load events
    function loadEvents(selectedYear, selectedMonth) {
        const params = new URLSearchParams({
            v_id: '<?php echo $v_id; ?>',
            start: dpMonth.startDate.toString(),
            end: dpMonth.startDate.addMonths(1).toString(),
            selectedYear: selectedYear
        });

        console.log("Loading events with params:", params.toString());

        DayPilot.Http.ajax({
            url: "calendar_events.php?" + params.toString(),
            success: function (ajax) {
                const events = ajax.data;
                console.log("Fetched events:", events);
                if (Array.isArray(events)) {
                    dpMonth.events.list = events.map(event => ({
                        id: event.id,
                        text: event.text,
                        start: event.start,
                        end: event.end,
                        backColor: event.backColor,
                        reservee: event.reservee
                    }));
                    dpMonth.update();
                    console.log("Updated calendar with events:", dpMonth.events.list);
                  
                } else {
                    console.error("Invalid events format:", events);
                }
            },
            error: function (ajax) {
                console.error("Error fetching events:", ajax.responseText);
            }
        });
    }

    function selectYear(year) {
        const selectedYear = parseInt(year);
        const currentMonthIndex = dpMonth.startDate.getMonth();
        const date = new Date(selectedYear, currentMonthIndex, 2);
        dpMonth.startDate = new DayPilot.Date(date);
        dpMonth.update();
        updateHeaderWithMonth(date);
        loadEvents(selectedYear, currentMonthIndex);
    }

    function navigateToMonth(monthIndex) {
        const selectedYear = parseInt(document.getElementById('selectedYear').textContent);
        const date = new Date(selectedYear, monthIndex, 2); 
        dpMonth.startDate = new DayPilot.Date(date);
        dpMonth.update();
        loadEvents(selectedYear, monthIndex);
        updateHeaderWithMonth(date);
    }

    
    function changeYear(direction) {
        const selectedYearElement = document.getElementById('selectedYear');
        let currentYear = parseInt(selectedYearElement.textContent);
        currentYear += direction;
        selectedYearElement.textContent = currentYear;

        document.getElementById('prevYear').textContent = currentYear - 1;
        document.getElementById('nextYear').textContent = currentYear + 1;

        selectYear(currentYear); 
    }

    document.addEventListener("DOMContentLoaded", function () {
        const selectedYear = parseInt(document.getElementById('selectedYear').textContent);
        loadEvents(selectedYear, dpMonth.startDate.getMonth());
        updateHeaderWithMonth(dpMonth.startDate);
    });

   
    function goBack() {
        window.location.href = 'index.php';
    }
</script>
<script>
        function navigateToSchedule(v_id) {
            window.location.href = 'calendar_view.php?v_id=' + v_id;
        }
    </script>

</body>
</html>


