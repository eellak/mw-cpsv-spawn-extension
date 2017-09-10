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
  
		wfErrorLog('*****FLAGS'.$flags, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
		
    $service_template_map=array();
		$content_text=ContentHandler::getContentText($content);
		
		/**
		 * Variables that hold the template serialized contents as strings.
		 * They are used to create the new WikiPages for each additional entity
		 * where necessary
		 */
		$diadikasies_page_serialized_template="{{";
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
    $input_service_formal_framework_value='';
		$input_formal_framework_description_value='';
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
        wfErrorLog('*****size of map'.sizeof($service_template_map), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      }
      
      foreach($service_template_map as $map_entry){
        
        $i=0;
				
        /**
         * Check that the template field has indeed been 
         */
				if(mb_stristr($map_entry, "input_service_identifier", false, 'UTF-8')){
					$diadikasies_page_serialized_template.=$map_entry;
					$cpsv_public_service_serialized_template.=$map_entry;
					
				}
				
				if(mb_stristr($map_entry, "input_service_name", false, 'UTF-8')){
					$diadikasies_page_serialized_template.=$map_entry;
					$cpsv_public_service_serialized_template.=$map_entry;
					
				}
				
        if(mb_stristr($map_entry, "input_service_description", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
          $input_service_description_value="== Περιγραφη Υπηρεσιας == ".PHP_EOL.$tmpl_value.PHP_EOL;
          wfErrorLog("Περιγραφη Υπηρεσιας => ".$input_service_description_value, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
        }
				
				if(mb_stristr($map_entry, "input_service_competent_authority", false, 'UTF-8')){
					$diadikasies_page_serialized_template.=$map_entry;
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
          $input_service_description_value="== Περιγραφη Υπηρεσιας == ".PHP_EOL.$tmpl_value.PHP_EOL;
          wfErrorLog("Περιγραφη Υπηρεσιας => ".$input_service_description_value, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_provided_by", false, 'UTF-8')){
					$diadikasies_page_serialized_template.=$map_entry;
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_formal_framework", false, 'UTF-8')){
					$diadikasies_page_serialized_template.=$map_entry;
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_input", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_cost", false, 'UTF-8')){
					$diadikasies_page_serialized_template.=$map_entry;
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_output", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_completion_time", false, 'UTF-8')){
					$diadikasies_page_serialized_template.=$map_entry;
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_related_services", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_related_organizations", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_keywords", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_public_service_reference", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_formal_framework_description", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_registry", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_registry_description", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
				
				if(mb_stristr($map_entry, "input_service_public_service_reference", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
					
				}
//				
//				if(mb_stristr($map_entry, "input_service_completion_time", false, 'UTF-8')){
//					
//				}
      }
      
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

  		$content_text=$content_text.$input_service_description_value;
      if($user){
//        $public_service_page->doEditContent($article_content, $content_text, $flags, $baseRevId, $user);
//				ContentHandler::makeContent($content_text, $article->getTitle());
				$new_content=new WikitextContent($content_text);
//        $artcl_status=$article->doEditContent(ContentHandler::makeContent($content_text, $article->getTitle()), 'test for template rewrite', EDIT_UPDATE, $baseRevId, $user);
        $artcl_status=$article->doEditContent($new_content, 'test for template rewrite', 2); // 2 stands for EDIT_UPDATE. ref:	https://doc.wikimedia.org/mediawiki-core/1.27.1/php/group__Constants.html
        wfErrorLog($artcl_status, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
        wfErrorLog("the page id: ".$article->getId().PHP_EOL, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
        wfErrorLog($article->getTitle(), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      }
    }
    wfErrorLog($content_text, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
		
    return true;
  }
	
	private static function template_string_append_closing_braces($template_serialized_string){
		$template_serialized_string.='}}';
	}
	
}