<?php
	require_once('lib/class.functionmanager.php');	
	require_once('lib/class.functionstream.php');	
	require_once('lib/FirePHPCore/fb.php');

	Class extension_EXSL_Function_Manager extends Extension{
		public function about(){
			return array('name' => 'EXSL Function Manager',
						 'version' => '0.1',
						 'release-date' => '',
						 'author' => array('name' => 'Andrew Shooner',
										   'website' => 'http://andrewshooner.com',
										   'email' => 'ashooner@gmail.com')
				 		);
		}
		
		public function getSubscribedDelegates(){
			return array(
						array(
							'page' => '/frontend/',
							'delegate' => 'FrontendOutputPreGenerate',
							'callback' => 'initFunctionManager'
						),

					);			
		}
		
		public function initFunctionManager($context) {
				
			$Manager = new FunctionManager(&$context);
			$Manager->createDelegate();	
			$Manager->createStream();				
		}		
	}