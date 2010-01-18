<?php 
	require_once('FirePHPCore/fb.php');
class EXSLFunction{
	private $fn_name;
	private $fn_handle;
	private $fn_namespace;
	private $fn_declaration;
	private $fn_xslfunction;


	public function __construct($strName, $strURI, $strHandle = NULL){
		$this->fn_name = $strName;
		$this->fn_namespace = $strURI;
		if (!$strHandle) { $this->fn_handle = $strName;} else {$this->fn_handle = $strHandle;}
	}
	
	
	public function getDeclarations($prefix) {
		$strDeclaration = "xmlns:" . $prefix . "='" . $this->fn_namespace ."'";
		$this->fn_declaration = $strDeclaration;
		fb("Declaration", $strDeclaration);
		return $this->fn_declaration;
	}
	
	public function getFunction($prefix) {
		$reflector = new ReflectionMethod($this->fn_name);
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
		
		$strFunction = 	'<func:function name="' . $prefix . ":" . $this->fn_handle . '" xmlns:func="http://exslt.org/functions" >' 
			. $strParams .
				'<func:result>
					<xsl:copy-of select="php:function(\'' . $this->fn_name . '\',' . $strPassParams . ')" />
				</func:result>
			</func:function>';
			
		$this->fn_xslfunction = $strFunction;	
		return $this->fn_xslfunction;
	}
	
	
}
// function getXSL(){
// 	$strXSL = '';
// 	$i = 0;	
// 	foreach($this->functions as $function) {
// 		//create the block of namespaces for the new functions (using generic prefixes)
// 		//convert arguments passed to the exslt function into arguments for the php function
// 		$reflector = new ReflectionMethod($function['name']);
// 		$params = $reflector->getParameters();
// 		if ($function['handle']) {$xsl_name = $function['handle'];} else { $xsl_name = $function['name'];}
// 
// 		$strParams = '';
// 		$last_param = end($params); 
// 		$strPassParams = '';
// 			foreach( $params as $param) {
// 				$strParams .= '<xsl:param name="' . $param->getName() . '" />';
// 					if ($param->isArray()) {
// 						// function wants a domelement(which comes wrapped in an array)
// 						$strPassParams .= 'exsl:node-set($' . $param->getName() . ")";
// 					} else {
// 						$strPassParams .= '$' . $param->getName();
// 					}
// 				if ($param != $last_param) {$strPassParams .= ',';}
// 			}
// 		$strFunc = '<func:function name="function' . $i . ':' . $xsl_name . '" xmlns:func="http://exslt.org/functions" >' 
// 		. $strParams .
// 			'<func:result>
// 				<xsl:copy-of select="php:function(\'' . $function['name'] . '\',' . $strPassParams . ')" />
// 			</func:result>
// 		</func:function>';
// 			$i += 1;
// 		$strXSL .= $strFunc;
// 	}
// 	return $strXSL;
// }

