<?php
	require_once( 'fpdf.php' );
	
	
	class PDF extends FPDF {
		function Header() {
			$this->SetFont( 'Arial', 'B', 18 ); //set font to Arial, Bold, and 16 Size
	
			//create heading with params
			//0 - 100% width
			//9 height
			//"Page Heading" - With this text
			//1 - border around it, and center aligned
			//1 - Move pionter to new line after writing this heading
			//'C' - center aligned
			$this->Cell( 0, 9, 'Payment Details', 1, 1, 'C' );
			
			$this->ln( 5 );
		}
		
		function Footer() {
			//move pionter at the bottom of the page
			$this->SetY( -15 );
			
			//set font to Arial, Bold, size 10
			$this->SetFont( 'Arial', 'B', 10 );
			
			//set font color to blue
			$this->SetTextColor( 52, 98, 185 );
			
			$this->Cell( 0, 10, '', 0, 0, 'L' );
			
			//set font color to gray
			$this->SetTextColor( 150, 150, 150 );
			
			//write Page No
			$this->Cell( 0, 10, 'Page No: ' . $this->PageNo(), 0, 0, 'R' );
		}
	}
	
?>
