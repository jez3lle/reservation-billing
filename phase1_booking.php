<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Booking</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    body {
        background-color:#03624c;
    }
    #bookingForm {
        padding: 20px;
        border-radius: 10px;
        background-color: white;
        width: 100%;
        margin-top: 70px;
        max-width: 700px;
        border-left: 5px solid #afd757;
        color: #000000;
        text-align: center;
        margin: 0 auto; /* Centers the form horizontally */
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
        background-color: #03624c;
        color: #FFFAEC;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    #checkAvailability:hover {
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

                if (!check_in || !check_out) {
                    $("#availabilityResult").html("<p class='error'>Please select both check-in and check-out dates.</p>");
                    return;
                }

                if (check_in >= check_out) {
                    $("#availabilityResult").html("<p class='error'>Check-out date must be after check-in date.</p>");
                    return;
                }

                $("#availabilityResult").html("<p class='loading'>Checking availability...</p>");

                $.ajax({
                    url: "check_availability.php",
                    type: "POST",
                    data: { check_in: check_in, check_out: check_out },
                    success: function(response){
                        $("#availabilityResult").html(response);

                        if(response.includes("Available")) {
                            $("#availabilityResult").append(`
                                <button id="proceedToReservation" data-checkin="${check_in}" data-checkout="${check_out}">Proceed to Reservation</button>
                            `);
                        }
                    }
                });
            });

            $(document).on("click", "#proceedToReservation", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");

                $.ajax({
                    url: "check_login.php",
                    type: "GET",
                    success: function(response) {
                        if (response.trim() === "logged_in") {
                            window.location.href = "reservation_form.php";
                        } else {
                            window.location.href = "login.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
                        }
                    },
                    error: function() {
                        alert("Error checking login status. Please try again.");
                    }
                });
            });
        });
    </script>

</body>
</html>
