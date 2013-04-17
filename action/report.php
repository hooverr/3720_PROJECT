<?php
    require('fpdf.php');
    class PDF extends FPDF
    {
        function hTRGB($hexColor)
        {
            $rgb = array();
            $rgb['red'] = hexdec(substr($hexColor,1,2));
            $rgb['green'] = hexdec(substr($hexColor,3,2));
            $rgb['blue'] = hexdec(substr($hexColor,5,2));
            
            return $rgb;
        }
        function getDoctorNames()
        {
            include('../login.php');
            $mysqli = new mysqli($host,$username,$password,$database );
            $query = "SELECT doctor_id, name from Doctor"; 
            if($result = $mysqli->query($query))
            {
                while($row= $result->fetch_assoc())
                {
                    $nameArray[$row["doctor_id"]] = $row["name"];
                }
                mysqli_free_result($result);
            }
            $mysqli->close();
            return $nameArray;
        }
        function GetDoctorData()
        {
            include('../login.php');
	    $colors = array("#7d7d7d","#7d9dbd","#7dbdfd","#7dfd9d","#9d7ddd","#9dbd7d","#9dddbd","#9dfdfd","#bd9d9d","#bdbddd","#bdfd7d","#dd7dbd","#dd9dfd","#dddd9d","#ddfddd","#fd9d7d","#fdbdbd","#fdddfd");
            $names = $this->getDoctorNames();
            $link = mysql_connect($host,$username,$password);
            if (!$link) {
                die('Could not connect: ' . mysql_error());
            }
    
            mysql_select_db($database,$link);
	    
	    $chars = preg_split('/-/', filter_var($_GET["month"],FILTER_SANITIZE_STRING), -1, PREG_SPLIT_OFFSET_CAPTURE);
            $month = $chars[0][0]; $year = $chars[1][0];
            $result = mysql_query("select * from Schedule where Month = ".$month." and Year = ".$year) or die(mysql_error());
    
            if (!$result) {
                die('Invalid query: ' . mysql_error());
            }
            $doctorData = array();
            while ($row = mysql_fetch_assoc($result)) {
                for($i = 1; $i <= 31; $i++)
                {
                    $doctorData[$i] = array();
                    $doctorData[$i][0] = $row[(string)$i];
                    $doctorData[$i][1] = $this->hTRGB($colors[$row[(string)$i] % count($colors)]);
                    $doctorData[$i][2] = $names[$row[(string)$i]];
                }
            }
            
            mysql_free_result($result);
            mysql_close($link);
            return $doctorData;
        }
        function SetupCalendar()
        {
	    $chars = preg_split('/-/', filter_var($_GET["month"],FILTER_SANITIZE_STRING), -1, PREG_SPLIT_OFFSET_CAPTURE);
            $month = $chars[0][0]; $year = $chars[1][0];
            $day =  (int) date("w", mktime(0, 0, 0, $month, 1, $year)); // to get the first weekday

      
            $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year)); // number of days in the month.
            
            
            $data = array();
            
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
            $reportType = filter_var($_GET["type"],FILTER_SANITIZE_STRING);
            if(strcmp($reportType,"external") == 0)
            {
                $this->SetFont('Arial','',8);
                $lines = file('info.txt');
                $v = 16;
                foreach($lines as $line)
                {
                    $this->Text(41,$v,$line);
                    $v += 3.4;
                }
                
                // Logo
                $this->Image('logo.png',10,6,30);
                
                    
                $this->SetFont('Arial','B',15);
                // Move to the right
                $this->Cell(30);
                // Title
                $today = getdate();
		    
		$chars = preg_split('/-/', filter_var($_GET["month"],FILTER_SANITIZE_STRING), -1, PREG_SPLIT_OFFSET_CAPTURE);
		$month = $chars[0][0]; $year = $chars[1][0];
                
                $this->Cell(30,0,'Schedule for '.date("F", mktime(0, 0, 0, $month, 1, $year)).' '.$year);
                
                $this->Line(41,12.5,290,12.5);
            }
            else
            {
                $this->SetFont('Arial','B',15);
                // Move to the right
                $this->Cell(1);
                // Title
                $today = getdate();
		    
		$chars = preg_split('/-/', filter_var($_GET["month"],FILTER_SANITIZE_STRING), -1, PREG_SPLIT_OFFSET_CAPTURE);
		$month = $chars[0][0]; $year = $chars[1][0];
                
                $this->Cell(0,18,'Schedule for '.date("F", mktime(0, 0, 0, $month, 1, $year)).' '.$year);
                $this->Line(10.5,22.5,290,22.5);
            }
            // Arial bold 15

            
            
            // Line break
            $this->Ln(20);
        }

        function DrawCalendar($header, $dayData, $doctorData)
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
                    $this->Text($xText+($x*40)-($this->GetStringWidth($dayData[$r][$x]) / 2)+2.45,$yText+($r*25),$dayData[$r][$x]);
                    if($doctorData[$dayData[$r][$x]])
                    {
                            
                        $this->SetFont('');
                        $textColor = $doctorData[$dayData[$r][$x]][1]['red']+$doctorData[$dayData[$r][$x]][1]['green']+$doctorData[$dayData[$r][$x]][1]['blue'];
                        $textColor /= 3;
                        $textColor = $textColor < 128? 255:0;
                        $this->SetFillColor($doctorData[$dayData[$r][$x]][1]['red'],$doctorData[$dayData[$r][$x]][1]['green'],$doctorData[$dayData[$r][$x]][1]['blue']);
                        $this->SetTextColor($textColor,$textColor,$textColor);
                        $this->Rect($xText+($x*40)+1,$yText+($r*25)+2,38,5,'F');
                        $this->Text($xText+($x*40)+2,$yText+($r*25)+5.6,$doctorData[$dayData[$r][$x]][2]);
                        $this->SetFillColor(224,235,255);
                        $this->SetTextColor(0,0,0);
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
    $dayData = $pdf->SetupCalendar();
    $doctorData = $pdf->GetDoctorData();
    $pdf->AddPage();
    $pdf->DrawCalendar($header,$dayData,$doctorData);
    $pdf->Output();
?>