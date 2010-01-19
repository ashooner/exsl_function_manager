<?php
	Class extension_hello_world extends Extension{

		public function about(){
			return array('name' => 'Hello World',
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
							'delegate' => 'ManageEXSLFunctions',
							'callback' => 'loadhelloworld'
						),

					);
		}
		
		public function loadhelloworld($context){
			//delegate context passes the EXSL FUnction Manager object. addFunction is used to register the extension's functions
			$context['manager']->addFunction('extension_test_function::helloworld','http://example.com','hello');		
			$context['manager']->addFunction('extension_test_function::hellonode','http://example.com','hellonode');			
		}
		
		public static function helloworld($name) {
			return 'Hello, ' . $name;
		}
		
		public static function hellonode( array $name) {
			//By type hinting an array, this function is passed a DomDocument wrapped in array.
			
			return 'Hello, ' . $name[0];
		}
		
	}
	