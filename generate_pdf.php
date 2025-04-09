<?php
require_once('tcpdf/tcpdf.php');

function generatePDF($reservation) {
    // Initialize TCPDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Rainbow Forest Paradise');
    $pdf->SetTitle('Reservation Confirmation');

   
    $pdf->AddPage();
    $pdf->SetFont('dejavusans', '', 10); // Use a font that supports UTF-8 (₱ symbol)

    // Convert tour_type to label
    $tourTypeString = match((int)$reservation['tour_type']) {
        0 => 'whole_day',
        1 => 'day_tour',
        2 => 'night_tour',
        default => 'unknown',
    };

    $tourTypes = [
        'whole_day' => 'Whole Day Tour',
        'day_tour' => 'Day Tour',
        'night_tour' => 'Night Tour',
    ];

    $tourTypeLabel = $tourTypes[$tourTypeString] ?? 'Unknown'; // Default to 'Unknown' if not found

    // HTML content for the reservation details
    $html = '
    <h2 style="text-align:center;">Reservation Confirmed</h2>
    <h3 style="text-align:center;">Rainbow Forest Paradise Resort and Campsite</h3>
    <p style="text-align:center;">Thank you for your reservation! Your payment has been received and confirmed.</p>

    <h4>Reservation Details:</h4>
    <table cellpadding="6" cellspacing="0" border="1" style="width:100%; border-collapse:collapse;">
        <tr style="background-color:#f0f0f0;"><th><b>Reservation Code</b></th><td>' . htmlspecialchars($reservation['code']) . '</td></tr>
        <tr><th><b>Name</b></th><td>' . htmlspecialchars($reservation['name']) . '</td></tr>
        <tr style="background-color:#f0f0f0;"><th><b>Email</b></th><td>' . htmlspecialchars($reservation['email']) . '</td></tr>
        <tr><th><b>Contact Number</b></th><td>' . htmlspecialchars($reservation['contact']) . '</td></tr>
        <tr style="background-color:#f0f0f0;"><th><b>Tour Type</b></th><td>' . $tourTypeLabel . '</td></tr>
        <tr><th><b>Check-in Date</b></th><td>' . htmlspecialchars($reservation['check_in']) . '</td></tr>
        <tr style="background-color:#f0f0f0;"><th><b>Check-out Date</b></th><td>' . htmlspecialchars($reservation['check_out']) . '</td></tr>
        <tr><th><b>Adults</b></th><td>' . htmlspecialchars($reservation['adults']) . '</td></tr>
        <tr style="background-color:#f0f0f0;"><th><b>Kids</b></th><td>' . htmlspecialchars($reservation['kids']) . '</td></tr>
        <tr><th><b>Total Amount Paid</b></th><td>₱' . htmlspecialchars($reservation['amount']) . '</td></tr>
    </table>

    <br><h4>Next Steps</h4>
    <p>Your reservation has been successfully recorded in our system. Please take note of the following:</p>
    <ul>
        <li>Save or print this confirmation for your records.</li>
        <li>Present your reservation code when you arrive at the resort.</li>
        <li>If you have any questions, contact us at <strong>rainbowforestparadise@gmail.com</strong> or call <strong>(123) 456-7890</strong>.</li>
    </ul>
    ';

    // Write the HTML content to the PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Create folder for saving PDFs if it doesn't exist
    $folderPath = __DIR__ . '/reservations';
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    // Path to save the PDF
    $pdfPath = $folderPath . "/confirmation_" . $reservation['code'] . ".pdf";
    $pdf->Output($pdfPath, 'F'); // Save PDF to file system

    // Return the file path
    return $pdfPath;
}
?>
