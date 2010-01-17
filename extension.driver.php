<?php
	require_once('lib/class.functionmanager.php');	
	require_once('lib/class.functionstream.php');	

	Class extension_function_manager extends Extension{

		public function about(){
			return array('name' => 'Function Manager',
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
			//Instatiate the manager
			
				$Manager = new FunctionManager(&$context);
			
			// Create Delegate
				$context['page']->ExtensionManager->notifyMembers(
				'ManagePhpFunctions', '/frontend/', 
					array(
						'manager' => &$Manager
					)
			 	);
		
			// Register Stream Wrapper
			stream_wrapper_register("xslstream", "XslTemplateLoaderStream");
			
			//Create context for stream using data members from function manager
			$opts = array(
			   'xslstream' => array(
			       'namespaces' => $Manager->getNamespaces(),
					'functions' => $Manager->getXSL()
			   )
			);
			$streamContext = stream_context_create($opts);
			libxml_set_streams_context($streamContext);
							
		}		
	}