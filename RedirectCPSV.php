<?php

class SpecialFillEntities{
	
	public static function onArticleUpdateBeforeRedirect(){
		
		header('Location: https://dev-diadikasies.ellak.gr/Ειδικό:CpsvFΕ');
		exit();
	}
}