<?php

include_once 'assets/wiki_template_strings.php';
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
  'name' => 'CPSV Class/Page renderer',
  'author' => array( 'David Bromoiras of GFOSS' ),
  'url' => 'NULL',
  'descriptionmsg' => "Extension for spawning the necessary mediawiki and semantic mediawiki entities for compatibility with the CPSV model as well as creating all the semantic relations between these entitites.",
  'version'  => "0.1",
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
  private static $original_input_template_text="";
  private static $content_text="";
//	private $g_user;
//	private $g_content;
	
	
  public static function onPageContentSaveComplete($article, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $revision, $status, $baseRevId) {
		
    $service_template_map=array();
		self::$content_text=ContentHandler::getContentText($content);
		self::$original_input_template_text=ContentHandler::getContentText($content);
		
    //self::$content_text='';
    
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
    $input_service_competent_authority_value=''; //create organization template
    $input_service_provided_by_value='';
    $input_service_provided_to_value=''; //create agent templates
    $input_service_execution_method_value='';
    $input_service_formal_framework_value='';
		$input_service_formal_framework_description_value='';
    $input_service_main_document_value='';
    $input_service_collateral_documents='';
		$input_service_main_document_description_value='';
    $input_service_output_value=''; //create output templates
    $input_service_cost_value=''; //create cost template
    $input_service_completion_time_value=''; //period of time template
    $input_service_related_services_value=''; //
    $input_service_related_organizations_value=''; //
    $input_service_keywords_value=''; //no input
    $input_service_public_service_reference_value=''; //no input/no use finally
		$input_service_registry_value=''; //output template
		$input_service_registry_description_value=''; //description on the output template
    
    $wikitables_array=array();
		
    /**
     * Cut out the Service template to parse its contents line by line
     */
    $tmpl_start=mb_strpos(self::$content_text, 'Καταχωρημένη Υπηρεσία (νέο)', 0, 'UTF-8');
    $tmpl_end=mb_strpos(self::$content_text, '}}');
		
		/**
		 * Check if article is the same that triggered the PageContentSaveCommplete event
		 */
    if(!is_null($tmpl_start) && $tmpl_start && (self::$g_article_id===-1 || self::$g_article_id!=$article->getId()) && $flags===1+64){ //	EDIT_NEW + EDIT_AUTOSUMMARY
			
			wfDebugLog('cpsvex', 'TEMPLATE START::::::::::'.$tmpl_end);
			self::$g_article_id=$article->getId();
			wfDebugLog('cpsvex', 'article id'.self::$content_text);
      $service_template_map=preg_split("/\|/", mb_stristr(self::$content_text, "}}", true, 'UTF-8')); // The starting character for each template line
      if (sizeof($service_template_map)){
        wfDebugLog('cpsvex', '*****size of map'.sizeof($service_template_map));
      }
			else{
        wfDebugLog('cpsvex', '*****size of map UNINITIALIZED');				
			}
      
      foreach($service_template_map as $map_entry){
        
        $i=0;
				wfDebugLog('cpsvex', 'dump of service template map: '.var_dump($service_template_map[i]).PHP_EOL);
				
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
					wfDebugLog('cpsvex', 'earlier'.var_dump($input_service_competent_authority_value));
					
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
				
				if(mb_stristr($map_entry, "input_service_input_main_document", false, 'UTF-8')){
					
//					$input_service_input_value = $map_entry;
					
          $input_service_main_document_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_input_collateral_documents", false, 'UTF-8')){
					
//					$input_service_input_value = $map_entry;
					
          $input_service_collateral_documents_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
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
			
//			if(empty($input_service_competent_authority_value)){
//				$input_service_competent_authority_value='';
//			}
//			else{
//				$input_service_competent_authority_value.=' ';
//			}
//	?????????????????????????

			
			// Populate the structures for the special page

			// -- FRAMEWORKS --
			
			
		if(!empty($input_service_output_value)){
			foreach(explode(',', $input_service_output_value) as $output_element){
				if(!empty(trim($output_element))){
					wfErrorLog("PAGE_TITLE%%%::::::::".Title::newFromText(trim($output_element)), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
				}
			}
		}
			
			
			$not_found_framework_array=array();
			foreach(explode(',', $input_service_formal_framework_value) as $formal_framework){
				
				//if(!found) add_to_the_array
				$temp_title=Title::newFromText(trim($formal_framework));
				
				if( $temp_title->exists() ){
				
					$not_found_framework_array[]=array(trim($formal_framework), true);
					
				}
				else{
					
					$not_found_framework_array[]=array(trim($formal_framework), false);
					$framework_template_page = WikiPage::factory($temp_title);
					$framework_template_text = '== bla bla bla =='.PHP_EOL;
					$framework_template_content = new WikitextContent($framework_template_text);
//					$framework_template_page->doEditContent($framework_template_content, '', 1);
					if($framework_template_page->exists()){
						wfDebugLog('cpsvex', 'created framework page');
					}
					else{
						wfDebugLog('cpsvex', 'NOT created framework page');
					}
					
				}
			}
			if (!empty($not_found_framework_array)){
				$_SESSION['not_found_frameworks']=$not_found_framework_array;
			}
			
			
			// -- EVIDENCE --
			
			$not_found_evidence_array=array();
			foreach(explode(',', $input_service_main_document_value) as $document){
				//if(!found) add_to_the_array
				$temp_title=Title::newFromText($document);
				
				if(isset($temp_title) && !$temp_title->exists()){
					$not_found_evidence_array[]=trim($document);
					
				}
			}
//			$_SESSION['not_found_evidence']=$not_found_evidence_array;
			
//			$not_found_evidence_array=array();
			foreach(explode(',', $input_service_collateral_documents) as $document){
				//if(!found) add_to_the_array
				$temp_title=Title::newFromText($document);
				
				if(isset($temp_title) && !$temp_title->exists()){
					$not_found_evidence_array[]=trim($document);
				}
			}
			if (!empty($not_found_evidence_array)){
				$_SESSION['not_found_evidence']=$not_found_evidence_array;
			}
			
			
			// -- ORGANIZATIONS --
			
			$not_found_organizations_array=array();
			foreach(explode(',', $input_service_competent_authority_value) as $organization){
				//if(!found) add_to_the_array
				$temp_title=Title::newFromText($organization);
				$not_found_organizations_array[]=trim($organization);
				wfDebugLog('cpsvex', 'temp title-organization: '.$temp_title);
				
				if(isset($temp_title) && !$temp_title->exists()){
					
				}
			}
//			$_SESSION['not_found_evidence']=$not_found_evidence_array;
			
//			$not_found_evidence_array=array();
			foreach(explode(',', $input_service_provided_by_value) as $document){
				//if(!found) add_to_the_array
				$temp_title=Title::newFromText($document);
				$not_found_organizations_array[]=trim($document);
				
				if(isset($temp_title) && !$temp_title->exists()){
				}
			}
			if (!empty($not_found_organizations_array)){
				$_SESSION['not_found_organizations']=$not_found_organizations_array;
			}
			
			$not_found_output_array=array();
			foreach(explode(',', $input_service_output_value) as $output){
				$temp_title=Title::newFromText($output);
				$not_found_output_array[]=trim($output);
			}
			if(!empty($not_found_output_array)){
				$_SESSION['not_found_output']=$not_found_output_array;
			}
			
			
			$steps_table="{| class='wikitable'
				\n!Α/Α
				\n!Βήμα Διαδικασίας
				\n!Θεσμικό Πλαίσιο -Διοικητική Πρακτική
				\n!Εμπλεκόμενος Αρμόδιος
				\n!Χρόνος Διεκπεραίωσης Βήματος".PHP_EOL;      
            
      
      wfErrorLog('CONTENT TEXT::::::'.self::$content_text, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      $wikitables=CPSVSpawn::fill_wikitables_array(self::$content_text);
      
      wfErrorLog('WIKITABLE1::::::'.$wikitables[0], '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      wfErrorLog('WIKITABLE2::::::'.$wikitables[1], '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
			
      $evidence_table .= trim($wikitables['{{Δικαιολογητικό']).PHP_EOL."|}";
			$steps_table .= trim($wikitables['{{Βήμα Διαδικασίας']).PHP_EOL."|}";

			
			/**
			 * WHERE THE SEMANTIC CPSV AND BACKGROUND TEMPLATES ARE CREATED AND ADDED
			 */

      /**
       * WikiPage has static factory methods for creating new WikiPages for any
       * namespace and of any type
       */
			$CPSV_article_title_text='CSPV_'.$article->getTitle()->getText();
			$CPSV_article_title_object=Title::newFromText($CPSV_article_title_text);
      $public_service_template_page = WikiPage::factory($CPSV_article_title_object);
//			if(!$public_service_template_page->exists()){
				$public_service_template_text = return_cpsv_public_service_template($input_service_identifier_value, $input_service_name_value, $input_service_description_value, $input_service_competent_authority_value, $input_service_formal_framework_value, $input_service_output_value, $input_service_completion_time_value, $input_service_cost_value);
				$public_service_template_content = new WikitextContent($public_service_template_text);
				$public_service_template_page->doEditContent($public_service_template_content, '', 1);
//			}
		
    wfDebugLog('cpsvex', "PROVIDED_BY:::::::::::".$input_service_provided_by_value);
			
		
//	CREATE THE BACKBONE PAGES FOR THE AUXILLIARY ENTITIES

//	-- ORGANIZATIONS --
		
		if(!empty($not_found_organizations_array)){
			foreach($not_found_organizations_array as $provided_by_token){
				if(!empty(trim($provided_by_token))){
					$public_organization_template_page_title = Title::newFromText(trim($provided_by_token));
					$public_organization_template_page = WikiPage::factory($public_organization_template_page_title);
					$public_organization_template_text = return_cpsv_public_organization_template($public_organization_identifier, $provided_by_token, 'Ελλάδα'/*$public_organization_default_spatial*/);
					$public_organization_template_content = new WikitextContent($public_organization_template_text);
					$public_organization_template_page->doEditContent($public_organization_template_content, '', 1);
				}
			}
		}
			
		// --	SERVICES --
			
//      $evidence_template_page = WikiPage::factory($article_title);
//			$evidence_template_text = return_cpsv_evidence_template($evidence_identifier, $evidence_name);
//		
			/**
			 * Break the outputs string by commas and 
			 */
		if(!empty($input_service_output_value)){
			foreach(explode(',', $input_service_output_value) as $output_element){
				if(!empty(trim($output_element))){
					wfErrorLog("PAGE_TITLE%%%::::::::".Title::newFromText(trim($output_element)), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
					$output_template_page = WikiPage::factory(Title::newFromText(trim($output_element)));
					$output_template_text = return_cpsv_output_template($output_identifier, $output_element);
					$output_template_content = new WikitextContent($output_template_text);
					$output_template_page->doEditContent($output_template_content, '', 1);
				}
			}
		}
			
			
			
  		self::$content_text=$diadikasies_page_output_content;//.$evidence_table;
//      if($user){
//        $public_service_page->doEditContent($article_content, $content_text, $flags, $baseRevId, $user);
//				ContentHandler::makeContent($content_text, $article->getTitle());
				$new_content=new WikitextContent($diadikasies_page_output_content);
//        $artcl_status=$article->doEditContent(ContentHandler::makeContent($content_text, $article->getTitle()), 'test for template rewrite', EDIT_UPDATE, $baseRevId, $user);
				
//        $artcl_status=$article->doEditContent($new_content, 'test for template rewrite', 2); // 2 stands for EDIT_UPDATE. ref:	https://doc.wikimedia.org/mediawiki-core/1.27.1/php/group__Constants.html
				
				if(!empty($input_service_name_value)){	
					$diadikasies_compatible_page_title = Title::newFromText(trim('Υπηρεσία: '.$input_service_name_value));
					$diadikasies_compatible_page = WikiPage::factory($diadikasies_compatible_page_title);
					$artcl_status=$diadikasies_compatible_page->doEditContent($new_content, '', 1); // 2 stands for EDIT_UPDATE. ref:	https://doc.wikimedia.org/mediawiki-core/1.27.1/php/group__Constants.html

				}
				else{
//					throw new Exception('Empty service name!');
				}
				
				wfDebugLog('cpsvex', 'executin till here...');
						
//				header('Location: https://dev-diadikasies.ellak.gr/Ειδικό:CpsvFΕ');
//				exit();
//			}
    }
//    wfErrorLog($content_text, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
  }
	
	private static function template_string_append_closing_braces($template_serialized_string){
		$template_serialized_string.='}}';
	}
  
  private static function parse_wikitable_line_from_wikitemplate($template_string){
		
		/*
		 * before parsing the wikitable line, store a template page.
		 */
		if(mb_stristr($template_string, "Δικαιολογητικό", false, 'UTF-8')){
//				$template_header = mb_substr($wikitemplate_map[$i], 2);
//				$template_header = trim($template_header);

				$temp_title=Title::newFromText('evidence_'.str_pad(rand(0, 100000), 6, '0', STR_PAD_LEFT));
				$table_template_page = WikiPage::factory($temp_title);
				$table_template_content = new WikitextContent($template_string.'}}');
				$table_template_page->doEditContent($table_template_content, '', 1);
		}
    
    $wikitable_line="|-".PHP_EOL; // The serialized wiki table line for the current evidence table, to be appended to table instance
    $wikitemplate_map=preg_split("/\|/", $template_string); 
    $wikitemplate_type=trim($wikitemplate_map[0]);
    wfErrorLog("TRIMMED:::::::".$wikitemplate_type, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
		
		$wikitemplate_length=sizeof($wikitemplate_map);
		
    for($i=1; $i<=$wikitemplate_length-1; $i++){
			$wikitable_line .= "|" . mb_substr($wikitemplate_map[$i], mb_strpos($wikitemplate_map[$i], '=', NULL, 'UTF-8')+1).PHP_EOL;
			
    }
    return array('wikitemplate_type'=>$wikitemplate_type, 'wikitable_line'=>$wikitable_line);
  }
	

  private function fill_wikitables_array(){
    $wikitables_array=array();
    
    
    // optional part: remove the first template of the page, the one that should be the
    // "public service" template. we only care for "evidence" and "steps" templates
    // and all the others that should translate into wikitables
    $service_template_end=mb_strpos(self::$content_text, "}}");
    wfErrorLog("THE TEMPLATE END:::::::".$service_template_end, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
    self::$content_text=mb_substr(self::$content_text, $service_template_end+2, NULL, 'UTF-8');


    while(mb_stristr(self::$content_text, "{{", false, 'UTF-8')){ // while we can detect template starting points in the wikipage
      $current_template_end=mb_strpos(self::$content_text, "}}");
      $current_template_string=mb_substr(self::$content_text, 0, $current_template_end, 'UTF-8');
      self::$content_text=mb_substr(self::$content_text, $current_template_end+2, null, 'UTF-8');
//      $current_template_string=mb_stristr(self::$content_text, '}}', true, 'UTF-8');
//      self::$content_text=mb_stristr(self::$content_text, '}}', false, 'UTF-8');
    wfErrorLog("CURRENT TEMP STRING:::::::".$current_template_string, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
    wfErrorLog("RET CONT TEXT:::::::".self::$content_text, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
      

		$wikitable_line=CPSVSpawn::parse_wikitable_line_from_wikitemplate($current_template_string);
    wfErrorLog("WIKITABLE LINE:::::::".$wikitable_line[1], '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log').PHP_EOL;
      $wikitables_array[$wikitable_line['wikitemplate_type']] .= $wikitable_line['wikitable_line'];
      
    }
//    return $wikitables_array;
  }
}