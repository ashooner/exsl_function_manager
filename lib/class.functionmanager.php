<?php
	require_once('class.exslfunction.php');

	class FunctionManager
	{
		private $functions = array();
		private $page;


		function __construct($context){
			$this->page = $context['page'];
		}

		public function createDelegate(){
			Symphony::ExtensionManager()->notifyMembers( 'ManageEXSLFunctions', '/frontend/', array(
				'manager' => &$this
			) );
		}


		public function createStream(){
			// Register Stream Wrapper
			stream_wrapper_register( "efm", "XslTemplateLoaderStream" );
			$exsl = $this->getFunctions();
			$opts = array(
				'efm' => array(
					'namespaces' => $exsl['declarations'],
					'functions'  => $exsl['functions']
					//'functions' => print_r($exsl)
				)
			);

			$streamContext = stream_context_create( $opts );

			libxml_set_streams_context( $streamContext );
		}


		// For use in subscribed delegates
		public function addFunction($strName, $strURI, $strHandle = null){
			//Register function with PHP
			$this->page->registerPHPFunction( $strName );

			//Create a new EXSL function object
			$function = new EXSLFunction($strName, $strURI, $strHandle);

			//Add to Manager's function array, which groups by namespace URI
			$this->functions[$strURI][] = $function;
		}


		private function getFunctions(){
			$strFunctions    = "";
			$strDeclarations = "";
			$i               = 0;

			foreach($this->functions as $namespace){
				$prefix = 'fn'.$i;

				$strDeclarations .= $namespace[0]->getDeclarations( $prefix ); //Get the declaration from the first EXSL object in the array

				foreach($namespace as $function){
					$strFunctions .= $function->getFunction( $prefix );
				}

				$i++;
			}

			return array('declarations' => $strDeclarations, 'functions' => $strFunctions);

		}


	}
