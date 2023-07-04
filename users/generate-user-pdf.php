<?php
ob_clean(); // Clear output buffer
// Include connection file 
include "dbconfig.php";
include_once('pdf/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('logo.jpg', 10, 10, 50);
        $this->SetFont('Arial', 'B', 13);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(80, 10, 'Complaint Details', 1, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$display_heading = ['complaintNumber' => 'Complaint Number', 'userId' => 'User ID', 'subcategory' => 'Sub-Category', 'status' => 'Status', 'state' => 'State'];

$result = mysqli_query($conn, "SELECT complaintNumber, userId, subcategory, state, status FROM tblcomplaints WHERE complaintNumber = complaintNumber") or die("database error:" . mysqli_error($conn));
$header = mysqli_query($conn, "SHOW COLUMNS FROM tblcomplaints");

$pdf = new PDF();

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', 'B', 10);

foreach ($header as $heading) {
    if (isset($display_heading[$heading['Field']])) {
        $pdf->Cell(34, 10, $display_heading[$heading['Field']], 1, 0, 'C');
    }
}

foreach ($result as $row) {
    $pdf->SetFont('Arial', '', 10);
    $pdf->Ln();
    foreach ($row as $column) {
        $pdf->Cell(34, 10, $column, 1, 0, 'C');
    }
}

ob_end_clean(); // Discard output buffer
$pdf->Output();
?>
