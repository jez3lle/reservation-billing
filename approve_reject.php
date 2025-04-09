<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db_connect.php';
require_once 'email_config.php'; 


if (isset($_POST['action']) && isset($_POST['reservation_code'])) {
    $action = $_POST['action'];
    $reservation_code = $_POST['reservation_code'];
    $reservation_type = $_POST['reservation_type']; // Get the reservation type (guest or user)
    
    if ($action == 'approve') {
        if ($reservation_type == 'guest') {
            // Handle guest reservation approval
            $stmt = $conn->prepare("UPDATE guest_reservation SET status = 'Approved' WHERE reservation_code = ?");
            $stmt->bind_param("s", $reservation_code);
            
            if ($stmt->execute()) {
                $stmt = $conn->prepare("SELECT * FROM guest_reservation WHERE reservation_code = ?");
                $stmt->bind_param("s", $reservation_code);
                $stmt->execute();
                $result = $stmt->get_result();
                $guest = $result->fetch_assoc();
                $reservation = [
                    'code' => $guest['reservation_code'],
                    'name' => $guest['first_name'] . ' ' . $guest['last_name'],
                    'email' => $guest['email'],
                    'contact' => $guest['contact_number'],
                    'tour_type' => $guest['tour_type'],
                    'check_in' => $guest['check_in'],
                    'check_out' => $guest['check_out'],
                    'adults' => $guest['adult_count'],
                    'kids' => $guest['kid_count'],
                    'amount' => number_format($guest['total_price'], 2),
                ];
        
                require_once 'generate_pdf.php';
                $pdf_path = generatePDF($reservation);
                $to = $guest['email'];
                $subject = "Reservation Approved - Rainbow Forest Paradise Resort and Campsite";
                $body = "
                   Dear {$reservation['name']},<br><br>
                    We are happy to let you know that your reservation code <strong>{$reservation['code']}</strong> has been approved!<br><br>
                    Attached is your reservation confirmation with full details. Kindly present it upon arrival at the resort.<br><br>
                    If you have any questions, feel free to contact us.<br><br>
                    Warm regards,<br>
                    <b>Rainbow Forest Paradise Resort and Campsite</b>
                ";
                send_email($to, $subject, $body, $pdf_path);
            }
        } else {
            // Handle user reservation approval
            $stmt = $conn->prepare("UPDATE user_reservation SET status = 'Approved' WHERE reservation_code = ?");
            $stmt->bind_param("s", $reservation_code);
            
            if ($stmt->execute()) {
                // Get user reservation details
                $stmt = $conn->prepare("SELECT ur.*, u.first_name, u.last_name, u.email, u.contact_number 
                                      FROM user_reservation ur
                                      JOIN user u ON ur.user_id = u.id
                                      WHERE ur.reservation_code = ?");
                $stmt->bind_param("s", $reservation_code);
                $stmt->execute();
                $result = $stmt->get_result();
                $user_reservation = $result->fetch_assoc();
                
                // Create reservation array for PDF generation (same format as guest)
                $reservation = [
                    'code' => $user_reservation['reservation_code'],
                    'name' => $user_reservation['first_name'] . ' ' . $user_reservation['last_name'],
                    'email' => $user_reservation['email'],
                    'contact' => $user_reservation['contact_number'],
                    'tour_type' => $user_reservation['tour_type'] ?? 'Standard',
                    'check_in' => $user_reservation['check_in'],
                    'check_out' => $user_reservation['check_out'],
                    'adults' => $user_reservation['adult_count'] ?? 0,
                    'kids' => $user_reservation['kid_count'] ?? 0,
                    'amount' => number_format($user_reservation['total_price'] ?? 0, 2),
                ];
                
                require_once 'generate_pdf.php';
                $pdf_path = generatePDF($reservation);
                $to = $user_reservation['email'];
                $subject = "Reservation Approved - Rainbow Forest Paradise Resort and Campsite";
                $body = "
                    Dear {$reservation['name']},<br><br>
                    We are happy to let you know that your reservation code <strong>{$reservation['code']}</strong> has been approved!<br><br>
                    Attached is your reservation confirmation with full details. Kindly present it upon arrival at the resort.<br><br>
                    You can view this reservation in your account dashboard.<br><br>
                    If you have any questions, feel free to contact us.<br><br>
                    Warm regards,<br>
                    <b>Rainbow Forest Paradise Resort and Campsite</b>
                ";
                send_email($to, $subject, $body, $pdf_path);
            }
        }
    } elseif ($action == 'reject') {
        if ($reservation_type == 'guest') {
            // Handle guest reservation rejection
            $stmt = $conn->prepare("UPDATE guest_reservation SET status = 'Rejected' WHERE reservation_code = ?");
            $stmt->bind_param("s", $reservation_code);
            $stmt->execute();
            
            // Optionally, send rejection email to guest
            $stmt = $conn->prepare("SELECT email, first_name, last_name FROM guest_reservation WHERE reservation_code = ?");
            $stmt->bind_param("s", $reservation_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $guest = $result->fetch_assoc();
            
            if ($guest) {
                $to = $guest['email'];
                $subject = "Reservation Update - Rainbow Forest Paradise Resort and Campsite";
                $body = "
                    Dear {$guest['first_name']} {$guest['last_name']},<br><br>
                    We regret to inform you that your reservation with code <strong>{$reservation_code}</strong> has been rejected.<br><br>
                    This could be due to various reasons such as payment verification issues or availability constraints.<br><br>
                    Please contact our customer service for more information and assistance.<br><br>
                    Warm regards,<br>
                    <b>Rainbow Forest Paradise Resort and Campsite</b>
                ";
                send_email($to, $subject, $body);
            }
        } else {
            // Handle user reservation rejection
            $stmt = $conn->prepare("UPDATE user_reservation SET status = 'Rejected' WHERE reservation_code = ?");
            $stmt->bind_param("s", $reservation_code);
            $stmt->execute();
            
            // Optionally, send rejection email to user
            $stmt = $conn->prepare("SELECT u.email, u.first_name, u.last_name 
                                  FROM user_reservation ur 
                                  JOIN user u ON ur.user_id = u.id 
                                  WHERE ur.reservation_code = ?");
            $stmt->bind_param("s", $reservation_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if ($user) {
                $to = $user['email'];
                $subject = "Reservation Update - Rainbow Forest Paradise Resort and Campsite";
                $body = "
                    Dear {$user['first_name']} {$user['last_name']},<br><br>
                    We regret to inform you that your reservation with code <strong>{$reservation_code}</strong> has been rejected.<br><br>
                    This could be due to various reasons such as payment verification issues or availability constraints.<br><br>
                    You can check your account dashboard for more information or contact our customer service for assistance.<br><br>
                    Warm regards,<br>
                    <b>Rainbow Forest Paradise Resort and Campsite</b>
                ";
                send_email($to, $subject, $body);
            }
        }
    }
}

// Redirect back to admin.php
header("Location: admin.php");
exit;
?>