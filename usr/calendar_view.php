<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('vendor/inc/config.php');

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

    // Function to check if a table exists
  
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit; // Terminate script if there's a database connection error
}

// Remove redundant session_start() call
// session_start();

// Check login status
check_login();
$aid = $_SESSION['u_id'];
$v_id = $_GET['v_id'] ?? '';


?>

<!DOCTYPE html>
<html>
<?php include ('vendor/inc/head.php');?>

<body id="page-top">
  <!--Navbar-->
  <?php include ('vendor/inc/nav.php');?>
  <!--End Navbar-->  

  <div id="wrapper">
    <!-- Sidebar -->
    <?php include('vendor/inc/sidebar.php');?>
    <!--End Sidebar-->

    <head>
      <meta charset="utf-8" />
      <title>Facility Calendar</title>
      <script src="js/daypilot/daypilot-all.min.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1" />

      <style type="text/css">
        #parent {
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
  }
  p, body, td, input, select, button { 
    font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; 
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
    padding: 10px 0; 
    text-align: center; 
    color: #000; 
    box-shadow: none; 
  }
  .header h1 { 
    margin: 0; 
    font-size: 24px; 
    font-weight: bold; 
  }
  .header div { 
    margin-top: 1px; 
    font-size: 14px; 
  }
  .main { 
    padding: 10px; 
    margin-top: 10px; 
    display: flex; 
    justify-content: center; 
  }
  .calendar-container { 
    max-width: 800px; /* Reduced width */
    width: 120%; 
    margin: 0 auto; 
  }

  .calendar-container {
            height: calc(100vh - 50px); /* Adjust to your header/footer size */
            padding: 10px;
        }
        #dpDay, #dpWeek, #dpMonth {
            width: 100%;
            height: 90%;
        }
  .toolbar {
    margin-bottom: 10px;
    text-align: center;
  }
  .toolbar-item a {
    background-color: #fff;
    border: 1px solid #c0c0c0;
    color: #333;
    padding: 8px 0px;
    width: 130px;
    border-radius: 2px;
    cursor: pointer;
    display: inline-block;
    text-align: center;
    text-decoration: none;
    margin: 0 5px;
  }
  .toolbar-item a.selected-button {
    background-color: #f3f3f3;
    color: #000;
  }
  /* context menu icons */
  .icon:before {
    position: absolute;
    margin-left: 0px;
    margin-top: 3px;
    width: 14px;
    height: 14px;
    content: '';
  }
  
  .icon-blue:before { background-color: #3d85c6; }
  .icon-green:before { background-color: #6aa84f; }
  .icon-orange:before { background-color: #e69138; }
  .icon-red:before { background-color: #cc4125; }
  /* active areas */
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
  #eventFormContainer {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 1000;
            overflow-y: auto;
            box-sizing: border-box;
        }
        #eventFormContainer h2 {
            margin: 0 0 20px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        #eventForm {
            display: flex;
            flex-direction: column;
        }
        #eventForm input[type="text"],
        #eventForm input[type="file"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        #eventForm button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
        }
        #eventForm button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        #eventForm button[type="submit"]:hover {
            background-color: #45a049;
        }
        #eventForm button[type="button"] {
            background-color: #f44336;
            color: white;
        }
        #eventForm button[type="button"]:hover {
            background-color: #e53935;
        }
        #cancelButton {
            margin-top: 10px;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #333;
            cursor: pointer;
        }
        .close:hover {
            color: #f44336;
        }

</style>

    </head>
    

    <body>
      <?php
      if (!empty($v_id)) {
          // Fetch facility name based on the facility ID
          $stmt = $mysqli->prepare("SELECT v_name FROM tms_vehicle WHERE v_id = ?");
          $stmt->bind_param("i", $v_id);
          $stmt->execute();
          $stmt->bind_result($v_name);
          $stmt->fetch();
          $stmt->close();
      }
      ?>

<div class="calendar-container">
  <div class="header">
    <h1><?php echo htmlspecialchars($v_name); ?> <span id="currentMonthName"></span> Calendar</h1>
  </div>

  <div class="main">
    <div style="display:flex; justify-content: center;">
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
</div>

<div id="eventFormContainer" style="display: none;">
        <form id="eventForm">
          <input type="text" name="name" placeholder="Event Name" required>
          <input type="text" name="reserved_by" placeholder="Booked By" required>
          <input type="text" name="start" readonly>
          <input type="text" name="end" readonly>
          <input type="file" name="image">
          <input type="hidden" name="v_id" value="<?php echo htmlspecialchars($v_id); ?>">
          <button type="submit">Create Event</button>
          <button type="button" id="cancelButton">Cancel</button>
        </form>
      </div>

      <script type="text/javascript">
    const nav = new DayPilot.Navigator("nav");
    nav.showMonths = 2;
    nav.skipMonths = 2;
    nav.init();

    const day = new DayPilot.Calendar("dpDay");
    day.viewType = "Day";
    day.cellHeight = 22;
    day.cellDuration = 30;
    configureCalendar(day);
    day.init();

    const week = new DayPilot.Calendar("dpWeek");
    week.viewType = "Week";
    week.cellHeight = 22;
    configureCalendar(week);
    week.init();

    const month = new DayPilot.Month("dpMonth");
    month.cellHeight = 75;
    configureCalendar(month);
    month.init();

    function getMonthName(date) {
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        return monthNames[date.getMonth()];
    }

    function updateHeaderWithMonth(date) {
        document.getElementById("currentMonthName").innerText = getMonthName(date);
    }

    function configureCalendar(dp) {
    dp.contextMenu = new DayPilot.Menu({
        items: [
            {
                text: "Delete",
                onClick: async args => {
                    const params = {
                        id: args.source.id(),
                    };
                    try {
                        const response = await DayPilot.Http.post("calendar_delete.php", params);
                        const result = await response.json();
                        if (result.result === 'OK') {
                            dp.events.remove(params.id);
                            console.log("Deleted");
                        } else {
                            console.error("Deletion failed:", result.message);
                            alert("Deletion failed: " + result.message);
                        }
                    } catch (error) {
                        console.error("Error in AJAX request:", error);
                        alert("Error in AJAX request: " + error);
                    }
                }
            },
            // other menu items
        ]
    });

    dp.onBeforeEventRender = args => {
        if (!args.data.backColor) {
            args.data.backColor = "#6aa84f";
        }
        args.data.borderColor = "darker";
        args.data.fontColor = "#fff";
        args.data.barHidden = true;
        args.data.areas = [
            {
                right: 2,
                top: 2,
                width: 20,
                height: 20,
                html: "&equiv;",
                action: "ContextMenu",
                cssClass: "area-menu-icon",
                visibility: "Hover"
            }
        ];
    };


        dp.onBeforeEventRender = args => {
            if (!args.data.backColor) {
                args.data.backColor = "#6aa84f";
            }
            args.data.borderColor = "darker";
            args.data.fontColor = "#fff";
            args.data.barHidden = true;
            args.data.areas = [
                {
                    right: 2,
                    top: 2,
                    width: 20,
                    height: 20,
                    html: "&equiv;",
                    action: "ContextMenu",
                    cssClass: "area-menu-icon",
                    visibility: "Hover"
                }
            ];
        };

        dp.onEventMoved = async args => {
            const params = {
                id: args.e.id(),
                newStart: args.newStart,
                newEnd: args.newEnd
            };
            await DayPilot.Http.post("calendar_move.php", params);
            console.log("Moved.");
        };

        dp.onEventResized = async args => {
            const params = {
                id: args.e.id(),
                newStart: args.newStart,
                newEnd: args.newEnd
            };
            await DayPilot.Http.post("calendar_move.php", params);
            console.log("Resized.");
        };

        async function submitFormData(formData) {
            try {
                const response = await fetch('calendar_create.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    dp.events.add({
                        start: formData.get('start'),
                        end: formData.get('end'),
                        id: result.id,
                        text: formData.get('name')
                    });
                    location.reload();
                } else {
                    console.error("Error from server:", result.error);
                }
            } catch (error) {
                console.error("Error creating event:", error);
            } finally {
                hideEventForm();  // Hide the form after submission
            }
        }

        dp.onTimeRangeSelected = async args => {
            updateHeaderWithMonth(args.start);
            const form = document.getElementById("eventForm");
            const container = document.getElementById("eventFormContainer");
            form.elements["start"].value = args.start.toString();
            form.elements["end"].value = args.end.toString();
            container.style.display = "block";
            dp.clearSelection();
            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                const formData = new FormData(form);
                await submitFormData(formData);
            });
        };

        dp.onEventClick = args => {
            const eventData = args.e.data;
            const eventName = eventData.text;
            const reservee = eventData.reservee;
            const startDate = eventData.start;
            const endDate = eventData.end;

            const message = `
                <div style="padding: 10px;">
                    <h3 style="margin-bottom: 5px;">Event Details</h3>
                    <hr style="border-top: 1px solid #ccc; margin-top: 5px; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between;">
                        <div style="flex: 1;">
                            <p><strong>Event Name:</strong></p>
                            <p><strong>Reservee:</strong></p>
                            <p><strong>Start Date:</strong></p>
                            <p><strong>End Date:</strong></p>
                        </div>
                        <div style="flex: 1;">
                            <p>${eventName}</p>
                            <p>${reservee}</p>
                            <p>${startDate}</p>
                            <p>${endDate}</p>
                        </div>
                    </div>
                </div>
            `;

            DayPilot.Modal.alert(message);
        };
    }

    const switcher = new DayPilot.Switcher({
        triggers: [
            { id: "buttonDay", view: day },
            { id: "buttonWeek", view: week },
            { id: "buttonMonth", view: month }
        ],
        navigator: nav,
        selectedClass: "selected-button",
        onChanged: args => {
            switcher.events.load("calendar_events.php?v_id=<?php echo $v_id; ?>");
            updateHeaderWithMonth(new Date(nav.selectionDay));
        }
    });

    switcher.select("buttonMonth");

    async function updateColor(e, color) {
        const params = {
            id: e.data.id,
            color: color
        };

        try {
            const response = await DayPilot.Http.post("calendar_color.php", params);
            const dp = switcher.active.control;
            e.data.backColor = color;
            dp.events.update(e);
            console.log("Color updated successfully for event ID:", e.data.id);
        } catch (error) {
            console.error("Error updating color:", error);
        }
    }

    function goBackOrHome() {
        window.location.href = 'usr-book-vehicle.php';
    }

    function hideEventForm() {
        document.getElementById("eventFormContainer").style.display = "none";
    }

    document.getElementById("cancelButton").addEventListener("click", hideEventForm);
</script>



    </body>
  </div>
</body>
</html>