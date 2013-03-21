
<?php

    require('../fpdf/fpdf.php');

    class PDF extends FPDF
    {
        // Load data
        function LoadData($file)
        {
            
            $day =  (int) date("w", strtotime(date('F') . ' 01,' . date('Y') . ' 00:00:00')); // to get the first weekday
      
            $daysInMonth = date("t"); // number of days in the month.
            /*
            $username = "robh_user";
            $password = "3720project";
            $link = mysql_connect("localhost",$username,$password);
            if (!$link) {
                die('Could not connect: ' . mysql_error());
            }
    
            mysql_select_db("robh_3720",$link);
            if($_POST["operation"] == "ClearTables")
            {
                mysql_query("delete from Settings;");
                mysql_query("delete from Schedule;");
                mysql_query("delete from Doctor_History;");
                mysql_query("delete from Requests;");
                mysql_query("delete from Doctor;");
                print("<b>Tables Cleared</b>");
            }
            elseif($_POST["operation"] == "ClearSchedule")
            {
                mysql_query("delete from Schedule;");
                print("<b>Schedule Cleared</b>");
            }
            elseif($_POST["operation"] == "FakeDocs")
            {
                mysql_query("INSERT INTO `Doctor` (`Doctor_ID`, `Name`, `Phone`, `Start_Date`, `End_Date`) VALUES(8, 'Rob H', '555-555-5555', '2013-03-11', NULL),(9, 'Dr Phil', '555-555-5555', '2013-03-13', NULL),(10, 'Tom Rickman', '', '2013-03-13', NULL),(11, 'Eric Bounty', '', '2013-03-13', NULL),(12, 'Sam Foreman', '', '2013-03-13', NULL),(13, 'Rich Lebenson', '', '2013-03-13', NULL),(14, 'Franklin Hampson', '', '2013-03-13', NULL),(15, 'Stence Fence', '', '2013-03-13', NULL),(16, 'Super Nova', '', '2013-03-13', NULL),(17, 'James James', '', '2013-03-13', NULL);");
                print("<b>Fake Doctors Added</b>");
                }
            mysql_close($link);
            
            */
            
            // Read file lines
            
            $data = array();
            
            for($i = 0; $i < 6; $i++)
            {
                $data[$i] = array(' ', ' ', ' ', ' ', ' ', ' ', ' ');
            }
            $i = 1;
            for($f = $day; $f <= 6; $f++)
            {
                $data[0][$f] = (string)$i;
                $i++;
            }
            
            for($w = 1; $w < 6; $w++)
            {
                for($c = 0; $c <= 6; $c++)
                {
                    if($i > $daysInMonth)
                    {break;}
                    $data[$w][$c] = $i;
                    $i++;
                }
            }
            //print_r( $data);
            return $data;
        }
        function Header()
        {
            // Logo
            $this->Image('logo.png',10,6,30);
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Move to the right
            $this->Cell(30);
            // Title
            $today = getdate();
            $this->Cell(30,0,'Schedule for '.date('F').' '.date('Y'));
            $this->SetFont('Arial','B',10);
            $this->Cell(-30);
            $this->Cell(10,10,'[Info here]');
            // Line break
            $this->Ln(20);
        }
        // Colored table
        function FancyTable($header, $data)
        {
            // Colors, line width and bold font
            $this->SetFillColor(200,200,255);
            $this->SetTextColor(255);
            $this->SetDrawColor(128,0,0);
            $this->SetLineWidth(.3);
            $this->SetFont('','B',10);
            // Header
            $w = array(40, 40, 40, 40, 40, 40, 40);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
            $this->Ln();
            // Color and font restoration
            $this->SetFillColor(224,235,255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Data
            $fill = false;
            $xText = 10;
            $yText = 41;
            for($r = 0; $r < 6;$r++)
            {
                
                for($x = 0;$x <= 6; $x++)
                {
                    //$data[$r][$x]
                    $this->Cell($w[$x],25,'','LR',0,'L',$fill);
                    $this->Rect($xText+($x*40),$yText+($r*25)-4,5,5,'D');
                    
                    $this->SetFont('Arial','B',10);
                    $this->Text($xText+($x*40)-($this->GetStringWidth($data[$r][$x]) / 2)+2.45,$yText+($r*25),$data[$r][$x]);
                    if($data[$r][$x] != ' ')
                    {
                            
                        $this->SetFont('');
                        
                        $this->SetFillColor(100,230,100);
                        $this->Rect($xText+($x*40)+1,$yText+($r*25)+2,38,5,'F');
                        $this->Text($xText+($x*40)+2,$yText+($r*25)+5.5,'Test Doctor Man');
                        $this->SetFillColor(224,235,255);
                    }
                }
                $this->Ln();
                $fill = !$fill;
            }
            // Closing line
            $this->Cell(array_sum($w),0,'','T');
        }
    }
    
    $pdf = new PDF('L');
    // Column headings
    $header = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
    // Data loading
    $data = $pdf->LoadData('countries.txt');
    $pdf->AddPage();
    $pdf->FancyTable($header,$data);
    $pdf->Output();
?>
            
    
    /*if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        list($doctorID, $phone) = split('[|]', $_POST["doc"]);
        $username = "robh_user";
        $password = "3720project";
        $link = mysql_connect("localhost",$username,$password);
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db("robh_3720",$link);
        if($_POST["operation"] == "ClearTables")
        {
            mysql_query("delete from Settings;");
            mysql_query("delete from Schedule;");
            mysql_query("delete from Doctor_History;");
            mysql_query("delete from Requests;");
            mysql_query("delete from Doctor;");
            print("<b>Tables Cleared</b>");
        }
        elseif($_POST["operation"] == "ClearSchedule")
        {
            mysql_query("delete from Schedule;");
            print("<b>Schedule Cleared</b>");
        }
        elseif($_POST["operation"] == "FakeDocs")
        {
            mysql_query("INSERT INTO `Doctor` (`Doctor_ID`, `Name`, `Phone`, `Start_Date`, `End_Date`) VALUES(8, 'Rob H', '555-555-5555', '2013-03-11', NULL),(9, 'Dr Phil', '555-555-5555', '2013-03-13', NULL),(10, 'Tom Rickman', '', '2013-03-13', NULL),(11, 'Eric Bounty', '', '2013-03-13', NULL),(12, 'Sam Foreman', '', '2013-03-13', NULL),(13, 'Rich Lebenson', '', '2013-03-13', NULL),(14, 'Franklin Hampson', '', '2013-03-13', NULL),(15, 'Stence Fence', '', '2013-03-13', NULL),(16, 'Super Nova', '', '2013-03-13', NULL),(17, 'James James', '', '2013-03-13', NULL);");
            print("<b>Fake Doctors Added</b>");
            }
        mysql_close($link);
    }*/
?>
