
<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Booking</title>
    <link rel="stylesheet" href="css/mystyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- FullCalendar CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <style>
        #bookingForm {
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            width: 100%;
            margin-top:90px;
            max-width: 900px;
            color: #000000;
            text-align: center;
            margin: 0 auto; /* Centers the form horizontally */
            display: flex;
            flex-direction: column;
        }

        /* Labels */
        label {
            font-weight: bold;
            color: #000000;
            display: block;
        }

        /* Flexbox for input fields and button */
        .form-row {
            display: flex;
            margin: 0;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-left: -10px;
        }

        /* Adjust input fields */
        input[type="date"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #03624c;
            border-radius: 5px;
            outline: none;
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            color: #03624c;
            font-weight: bold;
        }

        /* Placeholder text color */
        input[type="date"]::placeholder {
            color: #03624c;
        }

        /* Button */
        button {
            background-color: #03624c;
            color: #FFFAEC;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #FBFFE4;
            color: #1B4D3E;
        }

        .message-box {
            color: #03624c;
            border-radius: 5px;
        }
        .loading {
            color: blue;
        }
        .error {
            color: #03624c;
        }
        #proceedToReservation {
            padding: 8px 12px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .calendar-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 20px;
        }
        .calendar-box {
            width: 48%;
        }
        #checkAvailability {
            margin-top: 20px;
            display: block;
        }
        .selected-dates {
            margin-top: 10px;
            font-weight: bold;
        }
        .fc-daygrid-day {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="top-space">
        <div class="hamburger" onclick="toggleMenu()">☰</div>
    </div>
    <div class="menu">
        <div class="close-icon" onclick="toggleMenu()">X</div>
        <div class="menucontainer">
            <div class="phase-card phase-private">
                <h2>PHASE 1</h2>
                <h3>PRIVATE</h3>
                <p>
                    Enjoy exclusive access to the entire resort! This includes two pools, two houses, a pavilion, and a cozy kubo, ensuring privacy and relaxation.
                    Perfect for families, reunions, and private gatherings. Guests can also partake in exciting activities available in the public area.
                </p>
                <button onclick="bookNow('Phase 1')">BOOK NOW</button>
            </div>
            <div class="phase-card phase-public">
                <h2>PHASE 2</h2>
                <h3>PUBLIC</h3>
                <p>
                    Stay in our welcoming accommodations, including rooms, cabins, and houses, ideal for individuals or small groups. 
                    Enjoy thrilling activities such as ziplining, bonfires, and swimming, making your stay an unforgettable adventure!
                </p>
                <button onclick="bookNow('Phase 2')">BOOK NOW</button>
            </div>            
        </div>
    </div>

    <header class="page-header">
        <div class="navbar">
            <!-- Logo -->
            <div class="logo">
                <img src="rainbow-logo.png" alt="Resort Logo">
                <div class="logo-text">
                    <h1>Rainbow Forest Paradise</h1>
                    <h2>Resort and Campsite</h2>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="homep1.html">HOME</a></li>
                    <li><a href="aboutus.html">ABOUT</a></li>
                    <li><a href="accom.html">ACCOMMODATIONS</a></li>
                    <li><a href="#">ACTIVITIES</a></li>
                    <li><a href="#">BOOK NOW</a></li>
            </ul>
            <div class="icon">
                <img src="logo.png" alt="User">
            </div>
        </div>
    </header>
    <form id="bookingForm">
        <div class="calendar-container">
            <!-- Check-in Calendar -->
            <div class="calendar-box">
                <label>Check-in Date:</label>
                <div id="checkinCalendar"></div>
                <p class="selected-dates">Selected Check-in: <span id="selectedCheckIn">None</span></p>
            </div>

            <!-- Check-out Calendar -->
            <div class="calendar-box">
                <label>Check-out Date:</label>
                <div id="checkoutCalendar"></div>
                <p class="selected-dates">Selected Check-out: <span id="selectedCheckOut">None</span></p>
            </div>
        </div>

        <button type="button" id="checkAvailability" disabled>Check Availability</button>
        <div id="availabilityResult" class="message-box"></div>
    </form>
    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const hamburger = document.querySelector('.hamburger');
            const hamburgerVertical = document.querySelector('.hamburger-vertical');
            const header = document.querySelector('.page-header');
            menu.classList.toggle('active');
            header.classList.toggle('hidden');
            if (menu.classList.contains('active')) {
                hamburger.style.display = 'none';
                hamburgerVertical.style.display = 'block';
            } else {
                hamburger.style.display = 'block';
                hamburgerVertical.style.display = 'none';
            }
        }

        function bookNow(phase) {
            alert(`You clicked Book Now for ${phase}!`);
        }
    </script>

    <script>
        $(document).ready(function () {
    var check_in = null;
    var check_out = null;
    var bookedDates = [];

    function fetchBookedDates() {
        $.ajax({
            url: "fetch1_booked_dates.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                bookedDates = data;
                renderCalendar('checkinCalendar', true);
                renderCalendar('checkoutCalendar', false);
            }
        });
    }

    function renderCalendar(calendarId, isCheckIn) {
        var calendarEl = document.getElementById(calendarId);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            dateClick: function (info) {
                if (bookedDates.includes(info.dateStr)) {
                    alert("This date is already booked.");
                    return;
                }

                if (isCheckIn) {
                    check_in = info.dateStr;
                    $("#selectedCheckIn").text(check_in);
                    check_out = null;
                    $("#selectedCheckOut").text("None");
                } else {
                    if (!check_in) {
                        alert("Please select a check-in date first.");
                        return;
                    }
                    if (new Date(info.dateStr) <= new Date(check_in)) {
                        alert("Check-out date must be after check-in date.");
                        return;
                    }
                    check_out = info.dateStr;
                    $("#selectedCheckOut").text(check_out);
                }

                updateButtonState();
            },
            events: bookedDates.map(date => ({ start: date, display: 'background', color: 'red' }))
        });
        calendar.render();
    }

    function updateButtonState() {
        if (check_in && check_out) {
            $("#checkAvailability").prop("disabled", false);
        } else {
            $("#checkAvailability").prop("disabled", true);
        }
    }

    fetchBookedDates();
});
            // Render the two separate calendars
            renderCalendar('checkinCalendar', true);
            renderCalendar('checkoutCalendar', false);

            $("#checkAvailability").click(function () {
                if (!check_in || !check_out) {
                    $("#availabilityResult").html("<p class='error'>Please select both check-in and check-out dates.</p>");
                    return;
                }

                $("#availabilityResult").html("<p class='loading'>Checking availability...</p>");

                $.ajax({
                    url: "check1_availability.php",
                    type: "POST",
                    data: { check_in: check_in, check_out: check_out },
                    success: function (response) {
                        $("#availabilityResult").html(response);

                        if (response.includes("Available")) {
                            $("#availabilityResult").append(`
                                <button id="proceedToReservation" data-checkin="${check_in}" data-checkout="${check_out}">Proceed to Reservation</button>
                            `);
                        }
                    }
                });
            });

            $(document).on("click", "#proceedToReservation", function () {
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");

                $.ajax({
                    url: "check_login.php",
                    type: "GET",
                    success: function (response) {
                        if (response.trim() === "logged_in") {
                            window.location.href = "reservation1_form.php";
                        } else {
                            window.location.href = "index.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
                        }
                    },
                    error: function () {
                        alert("Error checking login status. Please try again.");
                    }
                });
            });
        
    </script>

</body>
</html>