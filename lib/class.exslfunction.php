<?php

	class EXSLFunction
	{
		private $fn_name;
		private $fn_handle;
		private $fn_namespace;
		private $fn_declaration;
		private $fn_xslfunction;


		public function __construct($strName, $strURI, $strHandle = null){
			$this->fn_name      = $strName;
			$this->fn_namespace = $strURI;
			if( !$strHandle ){
				$this->fn_handle = $strName;
			}
			else{
				$this->fn_handle = $strHandle;
			}
		}


		public function getDeclarations($prefix){
			$strDeclaration       = "xmlns:".$prefix."='".$this->fn_namespace."'";
			$this->fn_declaration = $strDeclaration;
			return $this->fn_declaration;
		}

		public function getFunction($prefix){
			$reflector = new ReflectionMethod($this->fn_name);

			//handle parameters
			$params        = $reflector->getParameters();
			$strParams     = '';
			$strPassParams = '';

			foreach($params as $param){
				$strParams .= '<xsl:param name="'.$param->getName().'"/>';

				if( $param->isArray() ){
					// function wants a DomDocument (which comes wrapped in an array)
					$strPassParams .= 'exsl:node-set($'.$param->getName().")";
				}
				else{
					$strPassParams .= '$'.$param->getName();
				}

				if( $param != end( $params ) ){
					$strPassParams .= ',';
				}
			}

			$strFunction =
				'<func:function name="'.$prefix.":".$this->fn_handle.'" xmlns:func="http://exslt.org/functions">'
					.$strParams.
					'<func:result>
						<xsl:copy-of select="php:function(\''.$this->fn_name.'\','.$strPassParams.')" />
					</func:result>
				</func:function>';

			$this->fn_xslfunction = $strFunction;

			return $this->fn_xslfunction;
		}

	}
