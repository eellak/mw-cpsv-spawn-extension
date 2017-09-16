<?php


/*
 * The extension configuration details take place here. For some reason the
 * extension.json method of defining the details would not work on the dev-wiki
 * environment.
 * 
 * The functionality of the extension will fire every time a new page is saved
 * via the PageForms form for registering a new, or editing an existing public
 * service.
 */
$wgExtensionCredits['other'][] = array(
  'path' => __FILE__,
  'name' => 'CPSV class spawner',
  'author' => array( 'David Bromoiras of GFOSS' ),
  'url' => 'NULL',
  'descriptionmsg' => "Extension for spawning the necessary mediawiki and semantic mediawiki entities for compatibility with the CPSV model as well as creating all the semantic relations between these entitites.",
  'version'  => 0.1,
  'license-name' => 'GPL-2.0+',
		
);

	/**
	 * Register the extension class to the MediaWiki (mw) classloader
	 */
	$wgAutoloadClasses["CPSVSpawn"] = __DIR__."/cpsv.php";
	
	/**
	 * Register the main function of the extension to fire on the
	 * PageContentSaveComplete event hook, which is a hook of the MediaWiki 
	 * system
	 * 
	 * https://www.mediawiki.org/wiki/Manual:Hooks/PageContentSaveComplete
	 */
//	$wgHooks["PageContentSaveComplete"][] = "CPSVSpawn::onPageContentSaveComplete";
	$wgHooks["PageContentSaveComplete"][] = "CPSVSpawn::onPageContentSaveComplete";

class CPSVSpawn{
	
	
//  public static function onPageContentSave($article, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $revision, $status, $baseRevId) {    
	private static $g_article_id=-1;
//	private $g_user;
//	private $g_content;
	
	
  public static function onPageContentSaveComplete($article, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $revision, $status, $baseRevId) {    
  
		//wfErrorLog('*****FLAGS'.$flags, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
		
    $service_template_map=array();
		$content_text=ContentHandler::getContentText($content);
		
		/**
		 * Variables that hold the template serialized contents as strings.
		 * They are used to create the new WikiPages for each additional entity
		 * where necessary
		 */
		
		$diadikasies_page_output_content="";
		$diadikasies_page_serialized_template="{{Πλαίσιο Πληροφοριών Υπηρεσίας".PHP_EOL;
		$cpsv_public_service_serialized_template="{{";
		$cpsv_evidence_serialized_template="{{";
		$cpsv_cost_serialized_template="{{";
		$cpsv_formal_framework_serialized_template="{{";
		$cpsv_public_organization_serialized_template="{{";
		
		/**
		 * The values parsed from the input template in ordered to be processed.
		 */
		
    $input_service_identifier_value='';
    $input_service_name_value='';
    $input_service_description_value='';
    $input_service_competent_authority_value='';
    $input_service_provided_by_value='';
    $input_service_provided_to_value='';
    $input_service_execution_method_value='';
    $input_service_formal_framework_value='';
		$input_service_formal_framework_description_value='';
    $input_service_input_value='';
		$input_service_input_description_value='';
    $input_service_output_value='';
    $input_service_cost_value='';
    $input_service_completion_time_value='';
    $input_service_related_services_value='';
    $input_service_related_organizations_value='';
    $input_service_keywords_value='';
    $input_service_public_service_reference_value='';
		$input_service_registry_value='';
		$input_service_registry_description_value='';
	
		
    
    /**
     * Cut out the Service template to parse its contents line by line
     */
    $tmpl_start=mb_strpos($content_text, '{{Καταχωρημένη Υπηρεσία', 0, 'UTF-8');
    $tmpl_end=mb_strpos($content_text, '}}');
		
		/**
		 * Check if article is the same that triggered the PageContentSaveCommplete event
		 */
    if(!is_null($tmpl_start) && (self::$g_article_id===-1 || self::$g_article_id!=$article->getId()) && $flags===1+64){ //	EDIT_NEW + EDIT_AUTOSUMMARY
			self::$g_article_id=$article->getId();
      $service_template_map=preg_split("/\|/", $content_text); // The starting character for each template line
      if (sizeof($service_template_map)){
//        wfErrorLog('*****size of map'.sizeof($service_template_map), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      }
      
      foreach($service_template_map as $map_entry){
        
        $i=0;
				
        /**
         * Check that the template field has indeed been 
         */
				if(mb_stristr($map_entry, "input_service_identifier", false, 'UTF-8')){
//					$diadikasies_page_serialized_template .= $map_entry;
//					$cpsv_public_service_serialized_template .= $map_entry;
//					$diadikasies_page_serialized_template .= "|Αναγνωριστικό Υπηρεσίας=GR".rand(0, 1000000);
					
//					$input_service_identifier_value = $map_entry;
					$input_service_identifier_value = "GR".rand(0, 1000000);
				}
				
				if(mb_stristr($map_entry, "input_service_name", false, 'UTF-8')){
//					$diadikasies_page_serialized_template .= $map_entry;
//					$cpsv_public_service_serialized_template .= $map_entry;
					
//					$diadikasies_page_serialized_template .= "|".$map_entry;
//					$input_service_name_value = $map_entry;
					$input_service_name_value = mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
				}
				
        if(mb_stristr($map_entry, "input_service_description", false, 'UTF-8')){
					
//					$input_service_description_value = $map_entry;
					
//          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
//          $input_service_description_value="== Περιγραφη Υπηρεσιας == ".PHP_EOL.$tmpl_value;
					$input_service_description_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
        }
				
				if(mb_stristr($map_entry, "input_service_competent_authority", false, 'UTF-8')){
					
					$input_service_competent_authority_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
//					$input_service_competent_authority_value = $map_entry;
//					$diadikasies_page_serialized_template .= "|".$map_entry;
//          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
//          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
				}
				
				if(mb_stristr($map_entry, "input_service_provided_by", false, 'UTF-8')){
					
					$input_service_provided_by_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
//					$input_service_provided_by_value = $map_entry;
					
//					$diadikasies_page_serialized_template .= "|".$map_entry;
//          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_provided_to", false, 'UTF-8')){
					
//					$input_service_provided_to_value = $map_entry;
					
//					$diadikasies_page_serialized_template .= $map_entry;
//          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
          $input_service_provided_to_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_execution_method", false, 'UTF-8')){
					
//					$input_service_execution_method_value = $map_entry;
					
//					$diadikasies_page_serialized_template .= $map_entry;
          $input_service_execution_method_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_formal_framework_entry", false, 'UTF-8')){
					
//					$input_service_formal_framework_value = $map_entry;
					
//					$diadikasies_page_serialized_template.=$map_entry;
          $input_service_formal_framework_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_formal_framework_description", false, 'UTF-8')){
					
//					$input_service_formal_framework_value = $map_entry;
          
					$input_service_formal_framework_description_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_input", false, 'UTF-8')){
					
//					$input_service_input_value = $map_entry;
					
          $input_service_input_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_cost", false, 'UTF-8')){
					
//					$input_service_cost_value = $map_entry;
					
//					$diadikasies_page_serialized_template.=$map_entry;
          $input_service_cost_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_output", false, 'UTF-8')){
					
//					$input_service_output_value = $map_entry;
          
					$input_service_output_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_completion_time", false, 'UTF-8')){
					
//					$input_service_completion_time_value = $map_entry;
					
//					$diadikasies_page_serialized_template.=$map_entry;
          $input_service_completion_time_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_related_services", false, 'UTF-8')){
					
//					$input_service_related_services_value = $map_entry;
          
					$input_service_related_services_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_related_organizations", false, 'UTF-8')){
					
//					$input_service_related_organizations_value = $map_entry;
          
          $input_service_related_organizations_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_keywords", false, 'UTF-8')){
					
//					$input_service_keywords_value = $map_entry;
          
          $input_service_keywords_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_public_service_reference", false, 'UTF-8')){
					
//					$input_service_public_service_reference_value = $map_entry;
          
          $input_service_public_service_reference_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_registry_entry", false, 'UTF-8')){
					
//					$input_service_registry_value = $map_entry;
          
          $input_service_registry_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_registry_description", false, 'UTF-8')){
					
//					$input_service_registry_description_value = $map_entry;
          
          $input_service_registry_description_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
//				
//				if(mb_stristr($map_entry, "input_service_completion_time", false, 'UTF-8')){
//					
//				}
      }
			
			$diadikasies_page_serialized_template .= 
//							'|input_service_name='.mb_substr($input_service_name_value, mb_strpos($input_service_name_value, '=', NULL, 'UTF-8')+1).PHP_EOL.
//							mb_substr($input_service_name_value, mb_strpos($input_service_name_value, '=', NULL, 'UTF-8')+1).'|'.PHP_EOL.
							"|Ταυτότητα Υπηρεσίας=".$input_service_identifier_value.PHP_EOL.
							"|Τίτλος Υπηρεσίας=".$input_service_name_value.PHP_EOL.
							"|Παρέχεται Από=".$input_service_competent_authority_value.','.$input_service_provided_by_value.PHP_EOL.
							"|Παρέχεται Σε=".$input_service_provided_to_value.PHP_EOL.
							"|Νομοθετικό Πλαίσιο=".$input_service_formal_framework_value.PHP_EOL.
							"|Εργάσιμες ημέρες κατά προσέγγιση=".$input_service_completion_time_value.PHP_EOL.
							"|Κόστος σε ευρώ=".$input_service_cost_value.PHP_EOL.
							'}}'.PHP_EOL;
			
/**
 * The part of the program where the multiple templates for evidence and input
 * are parsed and serialized into strings to be appended to the page content
 */		
			
			$evidence_table="{| class='wikitable'
				\n!Α/Α
				\n!Απαραίτητα Δικαιολογητικά
				\n!Κατάθεση από τον Αιτούντα / Αυτεπάγγελτη Αναζήτηση".PHP_EOL;
			$steps_table="{| class='wikitable'
				\n!Α/Α
				\n!Βήμα Διαδικασίας
				\n!Θεσμικό Πλαίσιο - Διοικητική Πρακτική
				\n!Εμπλεκόμενος Αρμόδιος
				\n!Χρόνος Διεκπεραίωσης Βήματος".PHP_EOL;
			
      
      $evidence_template_labels=[
          "Α.Α.=", 
          "Απαραίτητο Δικαιολογητικό=", 
          "Υποκείμενο υποβολής - Αυτεπάγγελτη Αναζήτηση="
          ];
      
      $steps_template_labels=[
          "Α.Α.=",
          "Βήμα Διαδικασίας=",
          "Θεσμικό Πλαίσιο- Διοικητική Πρακτική=",
          "Εμπλεκόμενος Αρμόδιος=",
          "Χρόνος Διεκπεραίωσης Βήματος=",
          ];
      
      function parse_wikitable_from_wikitemplate($template_string, array $template_labels){
				$wikitable_line="|-".PHP_EOL; // The serialized wiki table for the current evidence table, to be appended to wiki page
        $wikitemplate_map=preg_split("/\|/", $substring);
        
        foreach($evidence_template_map as $evidence_map_entry){
          
          foreach($template_labels as $template_label){
          
            if(mb_stristr($evidence_map_entry, $template_label, false, 'UTF-8')){
              $evidence_table_line .=
                      "|".mb_substr($evidence_map_entry, mb_strpos($evidence_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
              wfErrorLog("ev_tbl_ln........>".$evidence_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
            }
          }
        }
        
      }
      
      
			/**
			 * Function parses template instance, produces single wikitable line from it.
			 */
			function parse_evidence_template($substring){
				$evidence_table_line="|-".PHP_EOL; // The serialized wiki table for the current evidence table, to be appended to wiki page
				$evidence_template_map=array();
				$evidence_template_map=preg_split("/\|/", $substring);
				
//				$evidence_table_line="|-".PHP_EOL;
				foreach($evidence_template_map as $evidence_map_entry){
					
					wfErrorLog("THE TABLE LINE::::".$evidence_map_entry, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;

					if(mb_stristr($evidence_map_entry, "Α.Α.=", false, 'UTF-8')){
						$evidence_table_line .=
										"|".mb_substr($evidence_map_entry, mb_strpos($evidence_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$evidence_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
//					else{
//						$evidence_table_line .= "|".PHP_EOL;
//						wfErrorLog("ELSE>1", '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
//					}
					
					if(mb_stristr($evidence_map_entry, "Απαραίτητο Δικαιολογητικό=", false, 'UTF-8')){
						$evidence_table_line .=
										"|".mb_substr($evidence_map_entry, mb_strpos($evidence_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$evidence_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
//					else{
//						$evidence_table_line .= "|".PHP_EOL;
//						wfErrorLog("ELSE>2", '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
//					}
					
					if(mb_stristr($evidence_map_entry, "Υποκείμενο υποβολής - Αυτεπάγγελτη Αναζήτηση=", false, 'UTF-8')){
						$evidence_table_line .=
										"|".mb_substr($evidence_map_entry, mb_strpos($evidence_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$evidence_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
//					else{
//						$evidence_table_line .= "|".PHP_EOL;
//						wfErrorLog("ELSE>3", '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
//					}
				}
			}
				
			/**
			 * Function parses template instance, produces single wikitable line from it.
			 */
			function parse_steps_template($substring){
				$steps_table_line="|-".PHP_EOL; // The serialized wiki table for the current steps table, to be appended to wiki page
				$steps_template_map=array();
				$steps_template_map=preg_split("/\|/", $substring);
				
//				$steps_table_line="|-".PHP_EOL;
				foreach($steps_template_map as $steps_map_entry){
					
					wfErrorLog("THE TABLE LINE::::".$steps_map_entry, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;

					if(mb_stristr($steps_map_entry, "Α.Α.=", false, 'UTF-8')){
						$steps_table_line .=
										"|".mb_substr($steps_map_entry, mb_strpos($steps_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$steps_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
//					else{
//						$steps_table_line .= "|".PHP_EOL;
//						wfErrorLog("ELSE>1", '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
//					}
					
					if(mb_stristr($steps_map_entry, "Βήμα Διαδικασίας=", false, 'UTF-8')){
						$steps_table_line .=
										"|".mb_substr($steps_map_entry, mb_strpos($steps_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$steps_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
//					else{
//						$steps_table_line .= "|".PHP_EOL;
//						wfErrorLog("ELSE>2", '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
//					}
					
					if(mb_stristr($steps_map_entry, "Θεσμικό Πλαίσιο- Διοικητική Πρακτική=", false, 'UTF-8')){
						$steps_table_line .=
										"|".mb_substr($steps_map_entry, mb_strpos($steps_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$steps_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
					if(mb_stristr($steps_map_entry, "Εμπλεκόμενος Αρμόδιος=", false, 'UTF-8')){
						$steps_table_line .=
										"|".mb_substr($steps_map_entry, mb_strpos($steps_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$steps_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
					if(mb_stristr($steps_map_entry, "Χρόνος Διεκπεραίωσης Βήματος=", false, 'UTF-8')){
						$steps_table_line .=
										"|".mb_substr($steps_map_entry, mb_strpos($steps_map_entry, '=', NULL, 'UTF-8')+1).PHP_EOL;
						wfErrorLog("ev_tbl_ln........>".$steps_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
					}
//					else{
//						$steps_table_line .= "|".PHP_EOL;
//						wfErrorLog("ELSE>3", '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
//					}
				}
				
				wfErrorLog("THE ΤABLE-LINE::::".$steps_table_line, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
				return $steps_table_line."|-";
			}
			
			// Placing the content text into a temporary content text substring variable
			// for inside loop manipulation.
			
      function retrieve_wikitemplates_as_substrings($content_text){
        // detect each template's boundaries {{ }}
        // parse first line and add to the map with the first line value as key
        // parse the lines using the parse template function.
      }
      
      
			$content_text_substring=$content_text;
			wfErrorLog("THE TABLE::::".$content_text_substring, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
			wfErrorLog("TIMES FOUND::::".mb_substr_count($content_text_substring, "{{Δικαιολογητικό", 'UTF-8'), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
			$evidence_times_found=mb_substr_count($content_text_substring, "{{Δικαιολογητικό", 'UTF-8');
			$steps_times_found=mb_substr_count($content_text_substring, "{{Βήμα Διαδικασίας", 'UTF-8');
			
			for($i=0; $i < $evidence_times_found; $i++){
				wfErrorLog("THE ΙΟΤΑ:::::".$i, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
				$current_tmpl_start=mb_strpos($content_text_substring, "{{Δικαιολογητικό", 0, 'UTF-8');
			
				// resetting the start of the string to the first occurence of "{{Δικαιολογητικό" that was identified above
				$content_text_substring= mb_substr($content_text_substring, $current_tmpl_start);
				$current_tmpl_end=mb_strpos($content_text_substring, "}}", 0, 'UTF-8');
				//$current_tmpl_length=$current_tmpl_end-$current_tmpl_start;
				wfErrorLog("THE START:::::".$current_tmpl_start, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
				wfErrorLog("THE END:::::".$current_tmpl_end, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
				//wfErrorLog("THE LENGTH:::::".$current_tmpl_length, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;

				
				// Substring the template contents
//				$content_text_substring= mb_substr(0, $current_tmpl_end, 'UTF-8');
				$evidence_table .= parse_evidence_template(mb_substr($content_text_substring, 0, $current_tmpl_end, 'UTF-8'));
				
				
				// Trim the last parsed template from the start of the string
				$content_text_substring=mb_substr($content_text_substring, $current_tmpl_end+2, null, 'UTF-8');
				
				wfErrorLog("AFTER SUBSTRING:::::".$content_text_substring, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
			}
			
			$evidence_table .= PHP_EOL."|}";
			
			
			
			for($i=0; $i < $steps_times_found; $i++){
				wfErrorLog("THE ΙΟΤΑ:::::".$i, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
				$current_tmpl_start=mb_strpos($content_text_substring, "{{Βήμα Διαδικασίας", 0, 'UTF-8');
			
				// resetting the start of the string to the first occurence of "{{Δικαιολογητικό" that was identified above
				$content_text_substring= mb_substr($content_text_substring, $current_tmpl_start);
				$current_tmpl_end=mb_strpos($content_text_substring, "}}", 0, 'UTF-8');
				//$current_tmpl_length=$current_tmpl_end-$current_tmpl_start;
				wfErrorLog("THE START:::::".$current_tmpl_start, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
				wfErrorLog("THE END:::::".$current_tmpl_end, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
				//wfErrorLog("THE LENGTH:::::".$current_tmpl_length, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;

				
				// Substring the template contents
//				$content_text_substring= mb_substr(0, $current_tmpl_end, 'UTF-8');
				$steps_table .= parse_steps_template(mb_substr($content_text_substring, 0, $current_tmpl_end, 'UTF-8'));
				
				
				// Trim the last parsed template from the start of the string
				$content_text_substring=mb_substr($content_text_substring, $current_tmpl_end+2, null, 'UTF-8');
				
				wfErrorLog("AFTER SUBSTRING:::::".$content_text_substring, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
			}
			
			$steps_table .= PHP_EOL."|}";
			
			
			$diadikasies_page_output_content=$diadikasies_page_serialized_template.PHP_EOL.
          "== Περιγραφη Υπηρεσιας == ".PHP_EOL.
					$input_service_description_value.PHP_EOL.
          "=== Νομοθετικό Πλαίσιο === ".PHP_EOL.
					$input_service_formal_framework_description_value.PHP_EOL.
          "=== Τρόπος Διεκπεραίωσης === ".PHP_EOL.
					$input_service_execution_method_value.PHP_EOL.
          "=== Έντυπο που χρησιμοποιείται === ".PHP_EOL.
					$input_service_execution_method_value.PHP_EOL.
          "== Δικαιολογητικά == ".PHP_EOL.
					$evidence_table.PHP_EOL.
          "== Διαδικασίες == ".PHP_EOL.
					$steps_table.PHP_EOL;
							
      
      /**
       * define the content of a test page to build... currently too buggy, but works.
       */
      $article_title=Title::newFromText('dave'.rand(0, 100000));

      /**
       * ContentHandler has static methods for creating the content for a mw page.
       */
      $article_content=ContentHandler::makeContent('test content', $article_title);

      /**
       * WikiPage has static factory methods for creating new WikiPages for any
       * namespace and of any type
       */
      //$public_service_page=WikiPage::factory($article_title);
      //$test_user=User::newFromId(2);

//  		$content_text=$content_text.$input_service_description_value;
  		$content_text=$diadikasies_page_output_content;//.$evidence_table;
      if($user){
//        $public_service_page->doEditContent($article_content, $content_text, $flags, $baseRevId, $user);
//				ContentHandler::makeContent($content_text, $article->getTitle());
				$new_content=new WikitextContent($content_text);
//        $artcl_status=$article->doEditContent(ContentHandler::makeContent($content_text, $article->getTitle()), 'test for template rewrite', EDIT_UPDATE, $baseRevId, $user);
        $artcl_status=$article->doEditContent($new_content, 'test for template rewrite', 2); // 2 stands for EDIT_UPDATE. ref:	https://doc.wikimedia.org/mediawiki-core/1.27.1/php/group__Constants.html
//        wfErrorLog($artcl_status, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
//        wfErrorLog("the page id: ".$article->getId().PHP_EOL, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
//        wfErrorLog($article->getTitle(), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      }
    }
//    wfErrorLog($content_text, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
		
    return true;
  }
	
	private static function template_string_append_closing_braces($template_serialized_string){
		$template_serialized_string.='}}';
	}
	
}