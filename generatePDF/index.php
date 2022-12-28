<?php
    session_start();
    include "../db_conn.php";

    $globalTVA = 19;

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
        header("Location: ../index.php");
        exit();
    }

    function printPDF($lname, $fname, $email, $adress, $phone, $roomName, $night, $price){
        require('fpdf.php');

        class PDF extends FPDF {
            function Header() {
                $this->SetTextColor(0, 74, 176);
                $this->SetFont('Arial', 'B', 28);
                $this->Cell(134, 12,'Hotel Maria');

                $this->SetFont('Arial', 'B', 35);
                $this->SetTextColor(0);
                $this->setX(165);
                $this->Cell(118, 12,'Factura');
                $this->Ln(18);
            }

            function Footer() {
                $this->SetY(-15);
                $this->SetFont('Arial','I',8);
                $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            }

            function AddBusinessInformation() {
                global $globalTVA;
                $nameBusiness = "SC Hotel Maria SRL";
                $CIF = "RO17991374";
                $adresa = "Splaiul Unirii, numarul 23, etaj 4, Bucuresti, 450253";
                $telefon = "+40787520577";
                $email = "denisflorin69@yahoo.com";
                $IBAN = "RO10BTRLRONCRT0987654271";
                $banca = "Banca Transilvania";
                $TVA = $globalTVA;

                $this->SetFont('Arial', '', 12);
                $this->SetTextColor(0);
                $this->SetY(30);

                $this->Cell(134, 6, $nameBusiness);
                $this->Ln();
                
                $this->Cell(134, 6,'CIF: ' . $CIF);
                $this->Ln();

                $this->Cell(134, 6, 'Adresa: ' . $adresa);
                $this->Ln();

                $this->Cell(134, 6,'Telefon: ' . $telefon);
                $this->Ln();

                $this->Cell(134, 6,'Email: ' . $email);
                $this->Ln();

                $this->Cell(134, 6,'IBAN: ' . $IBAN . ', ' . $banca);
                $this->Ln();

                $this->Cell(134, 6,'Cota TVA: ' . $TVA . '%');
                $this->Ln();

                $this->Cell(134, 6,'Plata TVA la incasare');
                $this->Ln();
            }

            function AddClientInformation($nume, $prenume, $adresa, $telefon, $email){
                $this->SetFont('Arial', 'B', 17);
                $this->SetTextColor(0);

                $this->SetY(30);
                $this->SetX(165);
                $this->Cell(118, 6, "Client:");
                $this->Ln();

                $this->SetFont('Arial', '', 12);

                $this->SetX(165);
                $this->Cell(118, 6, 'Nume: ' . $nume);
                $this->Ln();

                $this->SetX(165);
                $this->Cell(118, 6, 'Prenume: ' . $prenume);
                $this->Ln();

                $this->SetX(165);
                $this->Cell(118, 6, 'Adresa: ' . $adresa);
                $this->Ln();

                $this->SetX(165);
                $this->Cell(118, 6, 'Telefon: ' . $telefon);
                $this->Ln();

                $this->SetX(165);
                $this->Cell(118, 6, 'Email: ' . $email);
                $this->Ln();
            }

            function CreateTable($roomName, $nights, $price){
                global $globalTVA;
                $priceWithoutTVA = $price * 100 / (100 + $globalTVA);

                $this->SetY(92);
                $this->Cell(268, 90, "", 1, 0);

                $this->SetFont('Arial', 'B', 15);
                $this->SetTextColor(0);

                $this->SetY(92);
                $this->Cell(20, 18, "Nr. crt.", 1, 0, 'C');
                $this->Cell(125, 18, "Denumirea produselor sau a serviciilor", 1, 0, 'C');
                $this->Cell(20, 18, "U.M.", 1, 0, 'C');
                $this->Cell(20, 18, "Cant.", 1, 0, 'C');
                $this->MultiCell(35, 6, "Pret unitar (fara TVA) -RON-", 1, 'C');
                $this->SetY(92);
                $this->SetX(235);
                $this->MultiCell(22, 9, "Valoare -RON-", 1, 'C');
                $this->SetY(92);
                $this->SetX(257);
                $this->MultiCell(26, 6, "Valoare TVA -RON-", 1, 'C');

                $height = 7;
                $this->SetFont('Arial', '', 13);
                $this->Cell(20, $height, "0", 1, 0, 'C');
                $this->Cell(125, $height, "1", 1, 0, 'C');
                $this->Cell(20, $height, "2", 1, 0, 'C');
                $this->Cell(20, $height, "3", 1, 0, 'C');
                $this->Cell(35, $height, "4", 1, 0, 'C');
                $this->Cell(22, $height, "5 (3x4)", 1, 0, 'C');
                $this->Cell(26, $height, "6", 1, 0, 'C');

                $this->ln();
                $height = 40;
                $this->SetFont('Arial', '', 18);
                $this->Cell(20, $height, "1", 1, 0, 'C');
                $this->Cell(125, $height, $roomName, 1, 0, 'C');
                $this->SetFont('Arial', '', 16);
                $this->Cell(20, $height, "Noapte", 1, 0, 'C');
                $this->SetFont('Arial', '', 18);
                $this->Cell(20, $height, "4", 1, 0, 'C');
                $this->Cell(35, $height, round($priceWithoutTVA * 100) / 100, 1, 0, 'C');
                $this->Cell(22, $height, $price * $nights, 1, 0, 'C');
                $this->Cell(26, $height, round(($price - $priceWithoutTVA) * $nights * 100) / 100, 1, 0, 'C');

                $this->ln();
                $this->SetFont('Arial', 'B', 40);
                $this->Cell(165, 25, "Total", 1, 0, 'C');
                $this->SetFont('Arial', 'B', 30);
                $this->Cell(103, 25, "1300 RON", 1, 0, 'C');
            }
        }

        $pdf = new PDF('L', 'mm', array(210, 298));
        $pdf->AliasNbPages();
        $pdf->SetMargins(15, 10, 15);
        $pdf->AddPage();
        $pdf->AddBusinessInformation();
        $pdf->AddClientInformation($lname, $fname, $adress, $phone, $email);
        $pdf->CreateTable($roomName, $night, $price);
        $pdf->Output();
    }

    //printPDF("Cringanu", "Denis-Florin", "denisflorin69@yahoo.com" , "Strada principala, Tanasoaia, Vrancea, 672555", "0765230530", "Camera Luxury pentru 2 persoane", 4, 200);

    $idSession = $_SESSION['id'];
    $idRent = $_GET['id'];
    $sql = "SELECT * FROM rents WHERE id = $idRent AND id_user = $idSession;";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        header("Location: ../index.php");
        exit();
    }
    $result = $result->fetch_object();

    $idRoom = $result->id_room;
    $lname = $_SESSION['lname'];
    $fname = $_SESSION['fname'];
    $email = $_SESSION['email'];
    $adress = "Unknown";
    $phone = "Unknown";

    $startDate = new DateTime($result->startDate);
    $endDate = new DateTime($result->endDate);
    $nights = $endDate->diff($startDate)->format("%a"); 

    $sql = "SELECT name, type, price FROM rooms WHERE id = $idRoom;";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        header("Location: ../index.php");
        exit();
    }
    $result = $result->fetch_object();
    $nameRoom = $result->name;
    $typeRoom = $result->type;
    $price = $result->price;

    $aboutRoom = "$nameRoom: ";
    if($typeRoom <= 5){
        if($typeRoom == 1)
            $aboutRoom = $aboutRoom . " apartament cu o camera";
        else $aboutRoom = $aboutRoom . " apartament cu $typeRoom camere";
    } else if($typeValue == 6)
        $aboutRoom = $aboutRoom . "camera dubla";
    else if($typeRoom == 7) 
        $aboutRoom = $aboutRoom . "camera tripla";
    else if($typeRoom == 8) 
        $aboutRoom = $aboutRoom . "camera quadrupla";

    printPDF($lname, $fname, $email, $phone, $adress, $aboutRoom, $nights, $price)
?>