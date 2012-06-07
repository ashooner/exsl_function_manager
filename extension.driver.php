<?php
	
	require_once('lib/class.functionmanager.php');	
	require_once('lib/class.functionstream.php');	

	Class extension_EXSL_Function_Manager extends Extension{
		
		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendOutputPreGenerate',
					'callback' => 'initFunctionManager'
				)
			);			
		}
		
		public function initFunctionManager($context){
			$Manager = new FunctionManager(&$context);
			$Manager->createDelegate();
			$Manager->createStream();
		}
	}