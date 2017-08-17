<?php

$wgExtensionCredits['other'][] = array(
  'path' => __FILE__,
  'name' => 'CPSV class spawner',
  'author' => array( 'David Bromoiras of GFOSS' ),
  'url' => 'NULL',
  'descriptionmsg' => "It's",
  'version'  => 0.1,
  'license-name' => 'GPL-2.0+',
		
);

	$wgAutoloadClasses["CPSVSpawn"] = __DIR__."/cpsv.php";
	$wgHooks["PageContentSaveComplete"][] = "CPSVSpawn::onPageContentSaveComplete";

class CPSVSpawn{
	
	
  public static function onPageContentSaveComplete($article, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $revision, $status, $baseRevId) {    
  
		$article_title=Title::newFromText('dave'.rand(0, 100000));
		$article_content=ContentHandler::makeContent('test content', $article_title);
		$public_service_page=WikiPage::factory($article_title);
		//$test_user=User::newFromId(2);
		
		$content_text=ContentHandler::getContentText($content);
		if($user){
			$public_service_page->doEditContent($article_content, $content_text, $flags, $baseRevId, $user);
		}
		return true;
  }
}