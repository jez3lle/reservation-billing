<?php
session_start(); // Start the session at the beginning

if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
    $check_in = date('Y-m-d', strtotime($_GET['check_in']));
    $check_out = date('Y-m-d', strtotime($_GET['check_out']));
    
    if ($check_in && $check_out && $check_in < $check_out) {
        $_SESSION['check_in'] = $check_in;
        $_SESSION['check_out'] = $check_out;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME - Rainbow Forest Paradise Resort and Campsite</title>
    <link rel="stylesheet" href="styles/mystyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        #bookingForm {
            margin: 20px auto 20px auto;
            padding:25px 40px;
            border-radius: 10px;
            background-color: white;
            width: 100%;
            max-width: 800px;
            border-left: 10px solid #afd757;
            color: #000000;
            text-align: center;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            color: #000000;
            display: block;
        }
        .form-row {
            display: flex;
            margin: 0;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-left: -10px;
        }
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
        input[type="date"]::placeholder {
            color: #03624c;
        }
        #checkAvailability {
            background-color: #4caf50;
            color: #FFFAEC;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
    
        #checkAvailability:hover {
            background-color: #FBFFE4;
            color: #1B4D3E;
        }
    
        .message-box {
            color: #03624c;
            border-radius: 5px;
            margin-top: 20px;
        }
        .loading {
            color: blue;
        }
        .error {
            color: #03624c;
        }
        #proceedToReservation {
            padding: 10px 18px;
            background: green;
            margin-top: 15px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
 <form id="bookingForm">
        <div class="form-row">
            <label for="check_in">Check-in:</label>
            <input type="date" id="check_in" name="check_in" required>

            <label for="check_out">Check-out:</label>
            <input type="date" id="check_out" name="check_out" required>

            <button type="button" id="checkAvailability">Check Availability</button>
        </div>
        <div id="availabilityResult" class="message-box"></div>
    </form>
    <script>
        $(document).ready(function(){
            var today = new Date().toISOString().split('T')[0];
            $("#check_in, #check_out").attr("min", today);
            $("#checkAvailability").click(function(){
                var check_in = $("#check_in").val();
                var check_out = $("#check_out").val();
                var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
                if (!check_in || !check_out) {
                    $("#availabilityResult").html("<p style='color: red;'>Please select both check-in and check-out dates.</p>");
                    return;
                }
                if (check_in === today) {
                    $("#availabilityResult").html("<p style='color: red;'>Same-day check-in is not allowed. Please select a future date.</p>");
                    return;
                }
                if (check_in >= check_out) {
                    $("#availabilityResult").html("<p style='color: red;'>Check-out date must be after check-in date.</p>");
                    return;
                }
                $("#availabilityResult").html("<p class='loading'>Checking availability...</p>");
                $.ajax({
                    url: "check_availability.php",
                    type: "POST",
                    data: { check_in: check_in, check_out: check_out },
                    success: function(response){
                        $("#availabilityResult").html(response);
                        $(".reservation-buttons, #proceedToReservation").parent("div").remove();
  
                        if(response.includes("Available!")) {
                            sessionStorage.setItem('check_in', check_in);
                            sessionStorage.setItem('check_out', check_out);

                            $.ajax({
                                url: "check_login.php",
                                type: "GET",
                                success: function(loginResponse) {
                                    if (loginResponse.trim() === "logged_in") {
                                        $("#availabilityResult").append(`
                                            <div style="display: flex; justify-content: center; margin-top: 15px;">
                                                <a href="reservation_form.php?check_in=${encodeURIComponent(check_in)}&check_out=${encodeURIComponent(check_out)}" class="btn" id="proceedToReservation" style="padding: 10px 18px; background: green; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Proceed to Reservation</a>
                                            </div>
                                        `);
                                    } else {
                                        $("#availabilityResult").append(`
                                            <div class="reservation-buttons" style="display: flex; justify-content: center; gap: 10px; margin-top: 15px;">
                                                <a href="guest_reservation.php?check_in=${encodeURIComponent(check_in)}&check_out=${encodeURIComponent(check_out)}" class="btn" id="guestReservation" style="padding: 10px 18px; background: #4caf50; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Continue as Guest</a>
                                                <a href="login.php?check_in=${encodeURIComponent(check_in)}&check_out=${encodeURIComponent(check_out)}" class="btn" id="loginToReserve" style="padding: 10px 18px; background: #03624c; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Login to Reserve</a>
                                            </div>
                                        `);
                                    }
                                }
                            });
                        }
                    }
                });
            });
            $(document).on("click", "#proceedToReservation", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");
                window.location.href = "reservation_form.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
            });
            $(document).on("click", "#guestReservation", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");
                window.location.href = "guest_reservation?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
            });
            $(document).on("click", "#loginToReserve", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");
                window.location.href = "login.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
            });
        });
        function proceedToReservationFunc(check_in, check_out) {
        window.location.href = "reservation_form.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
        }
    </script> <!-- Add this closing tag -->
</body>
</html>
