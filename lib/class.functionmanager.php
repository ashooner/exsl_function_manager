<?php

 class FunctionManager {
	private $functions = array();
	private $page;
	
	//constructor
	function __construct($context) {
		$this->page = $context['page'];
	}
	
	
	//setters
	public function addFunction($strName, $strURI, $strHandle = NULL){
		//register function with PHP
		$this->page->registerPHPFunction($strName);
		
		//add function to object's data member array
		$newFunction['name'] = $strName;
		$newFunction['uri'] = $strURI; 
		
		if($strHandle != NULL) {
			$newFunction['handle'] = $strHandle;
		} else {
			 			$newFunction['handle'] = $strName;
			 		}
		
		$this->functions[] = $newFunction;
	}

	//getters
	public function getNamespaces(){
			$strNamespaces = '';
			$i = 0;
			foreach ($this->functions as $function) {
				//TODO: pull from array index, not counter var
				$strNamespaces .= ' xmlns:function' . $i . '="' . $function['uri'] . '"
				'; 
			$i += 1;
			}
			return $strNamespaces;
		}
		
		public function getXSL(){
			$strXSL = '';
			$i = 0;	
			foreach($this->functions as $function) {
				//create the block of namespaces for the new functions (using generic prefixes)
				//convert arguments passed to the exslt function into arguments for the php function
				$reflector = new ReflectionMethod($function['name']);
				$params = $reflector->getParameters();
				if ($function['handle']) {$xsl_name = $function['handle'];} else { $xsl_name = $function['name'];}

				$strParams = '';
				$last_param = end($params); 
				$strPassParams = '';
					foreach( $params as $param) {
						$strParams .= '<xsl:param name="' . $param->getName() . '" />';
							if ($param->isArray()) {
								// function wants a domelement(which comes wrapped in an array)
								$strPassParams .= 'exsl:node-set($' . $param->getName() . ")";
							} else {
								$strPassParams .= '$' . $param->getName();
							}
						if ($param != $last_param) {$strPassParams .= ',';}
					}
				$strFunc = '<func:function name="function' . $i . ':' . $xsl_name . '" xmlns:func="http://exslt.org/functions" >' 
				. $strParams .
					'<func:result>
						<xsl:copy-of select="php:function(\'' . $function['name'] . '\',' . $strPassParams . ')" />
					</func:result>
				</func:function>';
					$i += 1;
				$strXSL .= $strFunc;
			}
			return $strXSL;
		}	
}