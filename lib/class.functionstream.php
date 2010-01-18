<?php 
class XslTemplateLoaderStream{
 	
    var $position = 0;
    var $template = null;
	
    function stream_open($path, $mode, $options, &$opened_path)
    {
		$context_array = stream_context_get_options($this->context);
		//$output = implode($options);
        $url = parse_url($path);
		$this->template = '<?xml version="1.0" encoding="UTF-8"?>
	        <xsl:stylesheet version="1.0" 
	                 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
					 xmlns:func="http://exslt.org/functions"
				     xmlns:exsl="http://exslt.org/common"  
					 xmlns:php="http://php.net/xsl"'
					. $context_array['xslstream']['namespaces']  .'
					extension-element-prefixes="func php" >'
					. $context_array['xslstream']['functions']  .'
	        </xsl:stylesheet>';
		
        return true;
    }
    function stream_read($count)
    {
       $ret = substr($this->template, $this->position, $count); 
       $this->position += $count;   
       return $ret;

		//         $ret = substr("<output >test</output>", $this->position, $count); 
		//         $this->position += $count;
		// return $ret;
    }
    function stream_write($data)
    {
        return 0;
    }
    function url_stat(){
        return array();     
        
    }
    function stream_eof()
    {
        return true;
    }
    
}

