<?php 
//

class EXSLFunction{
	private var $fn_name;
	private var $fn_handle;
	private var $fn_namespace;
	private $fn_prefix;
	private var $fn_declaration;
	private var $fn_xslfunction;


	public function __construct($strName, $strURI, $strPrefix, $strHandle = NULL){
		$this->$fn_name = $strName;
		$this->$fn_namespace = $strURI;
		$this->$fn_prefix = $strPrefix;
		if (!$strHandle) { $this->fn_handle = $strName;}
		$this->createXSL();
	}
	
	
	public function getDec() {
		return $this->fn_declaration;
	}
	
	public function getXSL() {
		return $this->$fn_xslfunction;
	}
	
	private function createXSL() {
		$reflector = new ReflectionMethod($this->fn_name);
		$strDeclaration = "xmlns:" . $this->fn_prefix . "='" .  ."'";
		
		//handle parameters
		$params = $reflector->getParameters();
		$strParams = ''; 
		$strPassParams = '';
			foreach( $params as $param) {
				$strParams .= '<xsl:param name="' . $param->getName() . '" />';
					if ($param->isArray()) {
						// function wants a domelement(which comes wrapped in an array)
						$strPassParams .= 'exsl:node-set($' . $param->getName() . ")";
					} else {
						$strPassParams .= '$' . $param->getName();
					}
				if ($param != end($params)) {$strPassParams .= ',';}
			}
		
		$strFunction = 	'<func:function name="' . $strDeclaration . '" xmlns:func="http://exslt.org/functions" >\n' 
			. $strParams .
				'<func:result>\n
					<xsl:copy-of select="php:function(\'' . $this->fn_name . '\',' . $strPassParams . ')" />\n
				</func:result>\n
			</func:function>\n\n';
			
		$this->fn_declaration = $strDeclaration;
		$this->fn_xslfunction = $strFunction;
	}
	
	
	
}

