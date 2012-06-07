<?php
	Class extension_hello_world extends Extension{
		
		public function getSubscribedDelegates(){
			return array(
				// 1. Subscribe to the EXSLT Function Manager's "ManageEXSLFunctions" delegate
				array(
					'page' => '/frontend/',
					'delegate' => 'ManageEXSLFunctions',
					'callback' => 'loadhelloworld'
				)
			);
		}
		
		// 2. The delegate callback method. Register your XSLT functions here
		public function loadhelloworld($context){
			
			$context['manager']->addFunction(
				'extension_hello_world::helloworld', // callback class and static method name
				'http://example.com', // namespace of your choosing
				'hello' // the function name you'll use in XSLT
			);
			
			$context['manager']->addFunction(
				'extension_hello_world::hellonode',
				'http://example.com',
				'hellonode'
			);
		}
		
		// 3. The functions!
		public static function helloworld($name) {
			return 'Hello, ' . $name;
		}
		
		public static function hellonode( array $name) {
			// By type hinting an array, this function is passed a DomDocument wrapped in array.
			return $name[0];
		}
		
	}
	