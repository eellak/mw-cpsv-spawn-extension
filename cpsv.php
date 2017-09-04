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
	$wgHooks["PageContentSaveComplete"][] = "CPSVSpawn::onPageContentSaveComplete";

class CPSVSpawn{
	
	
  public static function onPageContentSaveComplete($article, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $revision, $status, $baseRevId) {    
  
    $service_template_map=array();
		$content_text=ContentHandler::getContentText($content);
  
    $service_description_value;
    
    /**
     * Cut out the template to parse its contents line by line
     */
    $tmpl_start=mb_strpos($content_text, '{{Καταχωρημένη Υπηρεσία', 0, 'UTF-8');
    $tmpl_end=mb_strpos($content_text, '}}');
    
    if(!is_null($tmpl_start)){
      $service_template_map=preg_split("/\\r\\n|\\r|\\n/", $content_text);
      if (sizeof($service_template_map)){
        //wfErrorLog('*****size of map'.sizeof($service_template_map), '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      }
      
      foreach($service_template_map as $map_entry){
        
        $i=0;
        /**
         * Check that the template field has indeed been 
         */
        if(mb_stristr($map_entry, "input_service_description", false, 'UTF-8')){
          $tmpl_value=mb_substr($map_entry, mb_strpos($map_entry, '=', NULL, 'UTF-8')+1);
          $service_description_value="== Περιγραφη Υπηρεσιας == ".$i.PHP_EOL.$tmpl_value.PHP_EOL;
          //wfErrorLog("Περιγραφη Υπηρεσιας => ".$service_description_value, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
        }
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

  		$content_text=$content_text.$service_description_value;
      if($user){
//        $public_service_page->doEditContent($article_content, $content_text, $flags, $baseRevId, $user);
        $artcl_status=$article->doEditContent(ContentHandler::makeContent($content_text, $article->getTitle()), 'test for template rewrite', EDIT_UPDATE, $baseRevId, $user);
        //wfErrorLog($artcl_status, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
      }
    }
    //wfErrorLog($content_text, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
		
    return true;
  }
}