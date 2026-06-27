<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }
include '../config/db.php';

// Cek apakah FPDF ada
if (!file_exists('../fpdf/fpdf.php')) {
    die('
    <!DOCTYPE html>
    <html>
    <head>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    </head>
    <body class="container mt-5">
      <div class="alert alert-warning">
        <h5>⚠️ Library FPDF Belum Ada!</h5>
        <p>Ikuti langkah berikut untuk mengaktifkan fitur Report PDF:</p>
        <ol>
          <li>Download FPDF dari <a href="http://www.fpdf.org" target="_blank">www.fpdf.org</a></li>
          <li>Ekstrak dan letakkan file <code>fpdf.php</code> di folder <code>fpdf/</code></li>
          <li>Refresh halaman ini</li>
        </ol>
        <a href="daftar.php" class="btn btn-success">Kembali</a>
      </div>
    </body>
    </html>
    ');
}

require '../fpdf/fpdf.php';

$data = mysqli_query($conn, "SELECT * FROM bunga ORDER BY kategori, nama_bunga");
$total_rows = mysqli_num_rows($data);
$total_nilai = mysqli_fetch_row(mysqli_query($conn, "SELECT SUM(harga*stok) FROM bunga"))[0];

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->SetFillColor(46,125,50);
        $this->SetTextColor(255,255,255);
        $this->Cell(0,12,'LAPORAN DATA BUNGA - TOKO BUNGA',0,1,'C',true);
        $this->SetFont('Arial','',9);
        $this->SetTextColor(100,100,100);
        $this->SetFillColor(255,255,255);
        $this->Cell(0,6,'Dicetak pada: '.date('d/m/Y H:i:s').' | Oleh: '.$_SESSION['user'],0,1,'C');
        $this->Ln(3);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(150,150,150);
        $this->Cell(0,10,'Halaman '.$this->PageNo().' - Toko Bunga Management System',0,0,'C');
    }
}

$pdf = new PDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// Header Tabel
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(200,230,201);
$pdf->SetTextColor(30,70,32);
$pdf->SetDrawColor(150,150,150);

$headers = ['No', 'Kode Bunga', 'Nama Bunga', 'Kategori', 'Harga (Rp)', 'Stok', 'Nilai Stok (Rp)', 'Tgl Input', 'Keterangan'];
$widths  = [10, 25, 45, 28, 30, 15, 35, 22, 57];

foreach ($headers as $i => $h) {
    $pdf->Cell($widths[$i], 8, $h, 1, 0, 'C', true);
}
$pdf->Ln();

// Isi Tabel
$pdf->SetFont('Arial','',9);
$pdf->SetFillColor(248,255,248);
$pdf->SetTextColor(0,0,0);
$no = 1;
$alt = false;

while ($row = mysqli_fetch_assoc($data)) {
    if ($pdf->GetY() > 185) { $pdf->AddPage(); }
    $fill = $alt;
    $pdf->SetFillColor($fill ? 240 : 255, $fill ? 248 : 255, $fill ? 240 : 255);
    $nilai = number_format($row['harga']*$row['stok'],0,',','.');
    $pdf->Cell($widths[0], 7, $no++, 1, 0, 'C', $fill);
    $pdf->Cell($widths[1], 7, $row['kode_bunga'], 1, 0, 'C', $fill);
    $pdf->Cell($widths[2], 7, $row['nama_bunga'], 1, 0, 'L', $fill);
    $pdf->Cell($widths[3], 7, $row['kategori'], 1, 0, 'C', $fill);
    $pdf->Cell($widths[4], 7, number_format($row['harga'],0,',','.'), 1, 0, 'R', $fill);
    $pdf->Cell($widths[5], 7, $row['stok'], 1, 0, 'C', $fill);
    $pdf->Cell($widths[6], 7, $nilai, 1, 0, 'R', $fill);
    $pdf->Cell($widths[7], 7, date('d/m/Y',strtotime($row['tanggal_input'])), 1, 0, 'C', $fill);
    $pdf->Cell($widths[8], 7, mb_substr($row['keterangan'],0,40,'UTF-8'), 1, 0, 'L', $fill);
    $pdf->Ln();
    $alt = !$alt;
}

// Footer Total
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(200,230,201);
$pdf->SetTextColor(30,70,32);
$total_w = array_sum($widths) - $widths[8] - $widths[7] - $widths[6] - $widths[5] - $widths[4];
$pdf->Cell($total_w, 8, 'TOTAL', 1, 0, 'R', true);
$pdf->Cell($widths[4], 8, '', 1, 0, 'C', true);
$pdf->Cell($widths[5], 8, '', 1, 0, 'C', true);
$pdf->Cell($widths[6], 8, number_format($total_nilai,0,',','.'), 1, 0, 'R', true);
$pdf->Cell($widths[7]+$widths[8], 8, 'Total: '.$total_rows.' jenis bunga', 1, 0, 'C', true);
$pdf->Ln(15);

// Tanda tangan
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0,0,0);
$x = $pdf->GetPageWidth() - 80;
$pdf->SetX($x);
$pdf->Cell(60,6,'Admin Toko Bunga',0,1,'C');
$pdf->SetX($x);
$pdf->Cell(60,20,'',0,1,'C');
$pdf->SetX($x);
$pdf->Cell(60,6,'( '.$_SESSION['user'].' )',0,1,'C');

$pdf->Output('I','Laporan_Bunga_'.date('Ymd').'.pdf');
?>