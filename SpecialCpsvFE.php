<?php

class SpecialCpsvFE extends SpecialPage{
	function __construct(){
		parent::__construct( 'CpsvFΕ' );
	}
	
	function execute(){
		$this->setHeaders();
//		header("Refresh:0");
		$not_found_evidence_array = array();
		$not_found_organization_array = array();
		$not_found_framework_array = array();
		
		$wikitext = '';
		
		if(isset($_SESSION['not_found_organizations'])){	
			$wikitext .= '== Οργανισμοί που Εισήχθησαν =='.PHP_EOL;
			$not_found_organization_array = $_SESSION['not_found_organizations'];

			foreach ($not_found_organization_array as $nf_element){
				$wikitext .= '{{#formlink:form=Public Organization|link text=Συμπλήρωσε '.$nf_element.'|target='.$nf_element.'|popup|}}'.PHP_EOL.PHP_EOL;
			}
		}
		
		if(isset($_SESSION['not_found_evidence'])){	
			$wikitext .= '== Δικαιολογητικά που Εισήχθησαν =='.PHP_EOL;
			$not_found_evidence_array = $_SESSION['not_found_evidence'];

			foreach ($not_found_evidence_array as $nf_element){
				$wikitext .= '{{#formlink:form=Evidence|link text=Συμπλήρωσε '.$nf_element.'|target='.$nf_element.'|popup|}}'.PHP_EOL.PHP_EOL;
			}
		}
		
		if(isset($_SESSION['not_found_frameworks'])){	
			$not_found_framework_array = $_SESSION['not_found_frameworks'];

			$wikitext .= '== Νομοθεσία Που Εισήχθη =='.PHP_EOL;
			foreach ($not_found_framework_array as $nf_element){
				if( $nf_element[1] ){
					$wikitext .= '{{#formlink:form=Φόρμα Εισαγωγής Νομοθετικού Πλαισίου|link text=Επεξεργάσου '.$nf_element[0].'|target='.$nf_element[0].'|popup|}}'.PHP_EOL.PHP_EOL;
				}
				else{
					$wikitext .= '{{#formlink:form=Φόρμα Εισαγωγής Νομοθετικού Πλαισίου|link text=Συμπλήρωσε '.$nf_element[0].'|target='.$nf_element[0].'|popup|}}'.PHP_EOL.PHP_EOL;
					
				}
			}
			
		}
		
		if(isset($_SESSION['not_fount_output'])){
			$not_found_output_array=$_SESSION['not_found_output'];
			
			foreach ($not_found_output_array as $nf_element){
				$wikitext .= '{{#formlink:form=Output|link text=Επεξεργάσου '.$nf_element.'|target='.$nf_element.'|popup|}}'.PHP_EOL.PHP_EOL;
			}
		}
		
		$en_crypt=MWCryptRand::generateHex( 64, true );
		$original_url='http://www.diadikasies.gr/';
		$wikitext .=
						"<div class='controls-container' >
							<div class='button'>
								<a href='$original_url'>
									Συνέχεfια </span>$en_crypt</span>
								</a>
							</div>
						</div>";
		$output = $this->getOutput();
		
		$output->addHtml( $wikitext );
		
		
		// REMEMBER TO CLEAR SESSION VARIABLES!!!!!!!
		
	}
}
?>
