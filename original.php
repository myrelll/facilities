<?php
session_start();
include('vendor/inc/config.php');
include('admin/vendor/inc/config.php');
include("vendor/inc/head.php");
include("vendor/inc/nav.php");

$host = "localhost";
$username = "root";
$password = "";
$database = "vehiclebookings";

try {
    // Establishing a database connection
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Setting up timezone
    date_default_timezone_set("UTC");

    // Include file where check_login() function is defined
    include('vendor/inc/checklogin.php');

    // Checking if the 'events' table exists
    function tableExists($dbh, $tableName) {
        $stmt = $dbh->prepare("SHOW TABLES LIKE :tableName");
        $stmt->execute([':tableName' => $tableName]);
        return $stmt->rowCount() > 0;
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit; // Terminate script if there's a database connection error
}

// Check login status
$aid = $_SESSION['u_id'];
$v_id = $_GET['v_id'] ?? '';

if (!empty($v_id)) {
  $stmt = $mysqli->prepare("SELECT v_name FROM tms_vehicle WHERE v_id = ?");
  $stmt->bind_param("i", $v_id);
  $stmt->execute();
  $stmt->bind_result($v_name);
  $stmt->fetch();
  $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Facility Calendar</title>
  <script src="js/daypilot/daypilot-all.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style type="text/css">
    p, body, td, input, select, button { 
      font-family: -apple-system,system-ui,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; 
      font-size: 14px; 
    }
    body { 
      padding: 0px; 
      margin: 0px; 
      background-color: #ffffff; 
    }
    a { 
      color: #1155a3; 
    }
    .space { 
      margin: 10px 0px 10px 0px; 
    }
    .header { 
      margin-top: 25px;
      width: 75%;
      margin-left: 15%;
      color: black; 
    }
    .header a { 
      color: white; 
    }
    .header h1 a { 
      text-decoration: none; 
    }
    .header h1 { 
      padding: 0px; 
      margin: 0px; 
    }
    .main { 
      padding: 10px; 
      margin-top: 10px; 
    }
    .toolbar {
      margin-bottom: 10px;
    }
    .toolbar-item a {
      background-color: #fff;
      border: 1px solid #c0c0c0;
      color: #333;
      padding: 8px 0px;
      width: 80px;
      border-radius: 2px;
      cursor: pointer;
      display: inline-block;
      text-align: center;
      text-decoration: none;
    }
    .toolbar-item a.selected-button {
      background-color: #f3f3f3;
      color: #000;
    }
    .icon:before {
      position: absolute;
      margin-left: 0px;
      margin-top: 3px;
      width: 14px;
      height: 14px;
      content: '';
    }
    .icon-blue:before { 
      background-color: #3d85c6; 
    }
    .icon-green:before { 
      background-color: #6aa84f; 
    }
    .icon-orange:before { 
      background-color: #e69138; 
    }
    .icon-red:before { 
      background-color: #cc4125; 
    }
    .area-menu-icon {
      background-color: #333333;
      box-sizing: border-box;
      border-radius: 10px;
      opacity: 0.7;
      color: white;
      display: flex;
      justify-content: center;
      font-size: 14px;
    }
    #buttonHome {
      background-color: #fff;
      border: 1px solid #c0c0c0;
      color: #333;
      padding: 8px 10px;
      border-radius: 2px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      font-size: 14px;
      outline: none;
    }
    .calendar-container {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
    }
    #nav, #dpDay, #dpWeek, #dpMonth {
      width: 100%;
      height: 400px;
    }
  </style>
</head>

<body>
<div class="header">
  <h1><center><?php echo htmlspecialchars($v_name); ?> <span id="currentMonthName"></span> Calendar</center></h1>
</div>
<div class="main">
  <div style="display:flex" class="calendar-container">
    <div style="">
      <div id="nav"></div>
    </div>
    <div style="flex-grow: 1; margin-left: 10px;">
      <div class="toolbar buttons">
        <span class="toolbar-item"><a id="buttonDay" href="#">Day</a></span>
        <span class="toolbar-item"><a id="buttonWeek" href="#">Week</a></span>
        <span class="toolbar-item"><a id="buttonMonth" href="#">Month</a></span>
        <button id="buttonHome" onclick="goBackOrHome()">Back/Home</button>
      </div>
      <div id="dpDay"></div>
      <div id="dpWeek"></div>
      <div id="dpMonth"></div>
    </div>
  </div>
</div>

<script type="text/javascript">
  // Initialize the Navigator
  const nav = new DayPilot.Navigator("nav");
  nav.showMonths = 3;
  nav.skipMonths = 3;
  nav.onTimeRangeSelected = args => {
    updateHeaderWithMonth(new Date(args.day));
  };
  nav.init();

  // Initialize the Day Calendar
  const day = new DayPilot.Calendar("dpDay");
  day.viewType = "Day";
  configureCalendar(day);
  day.height = 400; 
  day.init();

  // Initialize the Week Calendar
  const week = new DayPilot.Calendar("dpWeek");
  week.viewType = "Week";
  configureCalendar(week);
  week.height = 400; 
  week.init();

  // Initialize the Month Calendar
  const month = new DayPilot.Month("dpMonth");
  configureCalendar(month);
  month.height = 400; 
  month.onAfterRender = () => {
    updateHeaderWithMonth(new Date(month.startDate));
  };
  month.init();

  // Function to get the month name
  function getMonthName(date) {
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    return monthNames[date.getMonth()];
  }

  // Function to update the header with the month name
  function updateHeaderWithMonth(date) {
    console.log("Updating month to: ", getMonthName(date)); // Debugging
    const currentMonthName = getMonthName(date);
    document.getElementById('currentMonthName').innerText = currentMonthName;
  }

  // Function to configure the calendar
  function configureCalendar(dp) {
    dp.onBeforeEventRender = args => {
      if (!args.data.backColor) {
        args.data.backColor = "#6aa84f";
      }
      args.data.borderColor = "darker";
      args.data.fontColor = "#fff";
      args.data.barHidden = true;
      args.data.areas = []; 
    };

    dp.onEventMoved = args => {
      dp.events.update(args.e);
      console.log("Moving events is disabled.");
    };

    dp.onEventResized = args => {
      dp.events.update(args.e);
      console.log("Resizing events is disabled.");
    };

    dp.onTimeRangeSelected = args => {
      dp.clearSelection();
      console.log("Creating new events is disabled.");
    };

    dp.onEventClick = args => {
      const eventData = args.e.data;
      const eventName = eventData.text;
      const reservee = eventData.reservee;
      const startDate = eventData.start;
      const endDate = eventData.end;
      const custodian = eventData.custodian;
      const custodianEmail = eventData.custodian_email;

      const message = `<div style="padding: 10px;">
          <h3 style="margin-bottom: 5px;">Event Details</h3>
          <hr style="border-top: 1px solid #ccc; margin-top: 5px; margin-bottom: 10px;">
          <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1;">
              <p><strong>Event Name:</strong></p>
              <p><strong>Reservee:</strong></p>
              <p><strong>Start Date:</strong></p>
              <p><strong>End Date:</strong></p>
              <p><strong>Facility Custodian:</strong></p>
              <p><strong>Custodian Email:</strong></p>
            </div>
            <div style="flex: 1;">
              <p>${eventName}</p>
              <p>${reservee}</p>
              <p>${startDate}</p>
              <p>${endDate}</p>
              <p>${custodian}</p>
              <p>${custodianEmail}</p>
            </div>
          </div>
        </div>
      `;

      DayPilot.Modal.alert(message);
    };

    dp.onAfterRender = args => {
      updateHeaderWithMonth(new Date(dp.startDate));
    };
  }

  // Initialize the switcher for changing views
  const switcher = new DayPilot.Switcher({
    triggers: [
      { id: "buttonDay", view: day },
      { id: "buttonWeek", view: week },
      { id: "buttonMonth", view: month }
    ],
    navigator: nav,
    selectedClass: "selected-button",
    onChanged: args => {
  console.log("onChanged fired");
  if (args.view) {
    if (args.view.startDate) {
      updateHeaderWithMonth(new Date(args.view.startDate));
    } else if (args.view.days) {
      // Week view does not have startDate, using first day
      updateHeaderWithMonth(new Date(args.view.days[0].start));
    } else {
      console.warn("startDate is undefined for the current view.");
    }
  } else {
    console.warn("View is undefined.");
  }
  switcher.events.load(`calendar_events.php?v_id=<?php echo $v_id; ?>`);
}

  });

  // Set the initial view to Month
  switcher.select("buttonMonth");

  // Function to go back or home
  function goBackOrHome() {
    window.location.href = 'index.php';
  }

  // Initial call to update the header with the current month name
  updateHeaderWithMonth(new Date());
</script>

<?php include("vendor/inc/footer.php");?>

</body>
</html>
