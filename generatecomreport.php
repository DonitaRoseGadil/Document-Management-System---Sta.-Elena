
<?php
require('libraries/fpdf.php');
include "connect.php";

// Get POST data
$all = isset($_POST['generateAllReports']) && $_POST['generateAllReports'] === 'on';
$start = $_POST['reportStartDate'] ?? '';
$end = $_POST['reportEndDate'] ?? '';
// $barangay = $_POST['reportBarangay'] ?? '';

// Query data
if ($all) {
    $sql = "SELECT * FROM committee_reports ORDER BY date_report ASC";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT * FROM committee_reports WHERE date_report BETWEEN ? AND ? ORDER BY date_report ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start, $end);
}
$stmt->execute();
$result = $stmt->get_result();

// Extend FPDF for footer
class PDF extends FPDF {
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

     function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i<$nb) {
            $c = $s[$i];
            if($c=="\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep = $i;
            $l += $cw[$c];
            if($l>$wmax) {
                if($sep==-1) {
                    if($i==$j)
                        $i++;
                }
                else
                    $i = $sep+1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }
}


$pdf = new PDF('L', 'mm', 'A4');

// Watermark logo in the center (use a faded PNG for best effect)
$watermarkPath = 'images/watermark.png'; // Use a faded/semi-transparent PNG
$pageWidth = $pdf->GetPageWidth();
$pageHeight = $pdf->GetPageHeight();
$wmWidth = 100;  // Adjust size as needed
$wmHeight = 100; // Adjust size as needed

// Center position
$wmX = ($pageWidth - $wmWidth) / 2;
$wmY = ($pageHeight - $wmHeight) / 2;

$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4');
// Place watermark behind content
$pdf->Image($watermarkPath, $wmX, $wmY, $wmWidth, $wmHeight);
// Set font
$pdf->SetFont('Times', '', 12);
$pdf->SetXY(0, 12); // move to top
$pdf->Cell(0, 5, '         Republic of the Philippines', 0, 1, 'C');
$pdf->Cell(0, 5, 'Province of Camarines Norte' , 0, 1, 'C');
$pdf->Cell(0, 5, 'Municipality of Sta. Elena', 0, 1, 'C');

// Title
$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 17);
$pdf->Cell(0, 8, 'Committee Report', 0, 1, 'C');
$pdf->SetFont('Times', '', 12);


if (!$all) {
    $pdf->Cell(0,10,"Date Range: " . date("F j, Y", strtotime($start)) . " - " . date("F j, Y", strtotime($end)), 0, 1, 'C');
    
} else {
    $pdf->Cell(0,10,"All Records",0,1,'C');
}
// $pdf->Cell(0, 5, "Barangay: " . (!empty($barangay) ? $barangay : "All Barangay"), 0, 1, 'C');
$pdf->Ln(5);

// Table header
$pdf->SetFont('Arial','B',8);
$pdf->Cell(130,8,'TITLE',1, 0,'C');
$pdf->Cell(35,8,'COMMITTEE CATEGORY',1, 0,'C');
$pdf->Cell(34,8,'COMMITTEE SECTION',1, 0,'C');
$pdf->Cell(45,8,'COUNCILOR',1, 0,'C');
$pdf->Cell(34,8,'DATE APPROVED',1, 0,'C');
// $pdf->Cell(30,8,'REMARKS',1);
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$rowCount = 0;
while ($row = $result->fetch_assoc()) {
    $w = [130, 35, 34, 45, 34];
    $lineHeight = 6;
    $title = mb_convert_encoding($row['title'], 'ISO-8859-1', 'UTF-8');
    $com_cat = mb_convert_encoding($row['committee_category'], 'ISO-8859-1', 'UTF-8');
    $com_sec = mb_convert_encoding($row['committee_section'], 'ISO-8859-1', 'UTF-8');
    $councilor = mb_convert_encoding($row['councilor'], 'ISO-8859-1', 'UTF-8');
    if (!empty($row['date_report']) && $row['date_report'] !== '0000-00-00') {
        $date_approved = date('F j, Y', strtotime($row['date_report']));
    } else {
        $date_approved = 'No data';
    }
    // $remarks = mb_convert_encoding($row['remarks'], 'ISO-8859-1', 'UTF-8');

    // Calculate number of lines for MultiCell columns
    $nb_title = $pdf->NbLines($w[0], $title);
    $nb_cat = $pdf->NbLines($w[1], $com_cat);
    $nb_sec = $pdf->NbLines($w[2], $com_sec);
    $nb_coun = $pdf->NbLines($w[3], $councilor);
    $nb_date = $pdf->NbLines($w[4], $date_approved);
    $maxLines = max($nb_title, $nb_cat, $nb_sec, $nb_coun, $nb_date, 1);
    $rowHeight = $lineHeight * $maxLines;

    // Check for page break before rendering the row
    $bottomMargin = 20; // You can tweak this if needed
    if ($pdf->GetY() + $rowHeight > ($pdf->GetPageHeight() - $bottomMargin)) {
        $pdf->AddPage('L', 'A4');
        $pdf->Image($watermarkPath, $wmX, $wmY, $wmWidth, $wmHeight);

        // Reprint the table header
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(130,8,'TITLE',1, 0,'C');
        $pdf->Cell(35,8,'COMMITTEE CATEGORY',1, 0,'C');
        $pdf->Cell(34,8,'COMMITTEE SECTION',1, 0,'C');
        $pdf->Cell(45,8,'COUNCILOR',1, 0,'C');
        $pdf->Cell(34,8,'DATE APPROVED',1, 0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','',9);
    }

    // Save current position
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    
    // Draw borders for all columns
    $pdf->Rect($x, $y, $w[0], $rowHeight); // TITLE
    $pdf->Rect($x + $w[0], $y, $w[1], $rowHeight); // COMMITTEE CATEGORY
    $pdf->Rect($x + $w[0] + $w[1], $y, $w[2], $rowHeight); // COMMITTEE SECTION
    $pdf->Rect($x + $w[0] + $w[1] + $w[2], $y, $w[3], $rowHeight); // COUNCILOR
    $pdf->Rect($x + $w[0] + $w[1] + $w[2] + $w[3], $y, $w[4], $rowHeight); // DATE APPROVED
    // $pdf->Rect($x + $w[0] + $w[1] + $w[2] + $w[3], $y, $w[4], $rowHeight); // REMARKS

    // Print each cell
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($w[0], $lineHeight, $title, 0, 'L');
    $pdf->SetXY($x + $w[0], $y);
    $pdf->MultiCell($w[1], $lineHeight, $com_cat, 0, 'C');
    $pdf->SetXY($x + $w[0] + $w[1], $y);
    $pdf->MultiCell($w[2], $lineHeight, $com_sec, 0, 'C');
    $pdf->SetXY($x + $w[0] + $w[1] + $w[2], $y);
    $pdf->MultiCell($w[3], $lineHeight, $councilor, 0, 'C');
    $pdf->SetXY($x + $w[0] + $w[1] + $w[2] + $w[3], $y);
    $pdf->MultiCell($w[4], $lineHeight, $date_approved, 0, 'C');
    // $pdf->MultiCell($w[4], $rowHeight, $remarks, 0, 'L');

    // Move to next line
    $pdf->SetY($y + $rowHeight);

    $rowCount++;
}
if ($rowCount === 0) {
    $pdf->Cell(238,8,'No records found for the selected date range.',1,1,'C');
}

// Output PDF
$pdf->Output('I', 'committee_report.pdf');
?>