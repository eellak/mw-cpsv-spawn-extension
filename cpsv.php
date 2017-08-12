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
  
		wfMessage('the class has loaded')->text();
		$article_title=Title::newFromText('dave'.rand(4, 1000000), NS_MAIN);
		$public_service_page=WikiPage::factory($article_title);
		error_log('mpainei mesa');
		wfMessage('it is inside')->text();
		return true;
  }
}