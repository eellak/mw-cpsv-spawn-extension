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
		$public_service_page=WikiPage::factory($article_title);
		//$test_user=User::newFromId(2);
		
		/**
		 * CURRENTLY WORKING ON THIS in order to see if there is a certain structured
		 * representation for the wikiPage or that i should proceed with parsing the
		 * content for keywords.
		 */
		$content_text=ContentHandler::getContentText($content);
		if($user){
			$public_service_page->doEditContent($article_content, $content_text, $flags, $baseRevId, $user);
			wfErrorLog($content_text, '/var/www/sftp_webadmins/sites/dev-wiki.ellak.gr/public/log/file_debug.log');
		}
		return true;
  }
}