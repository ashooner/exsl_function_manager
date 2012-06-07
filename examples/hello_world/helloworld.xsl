<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:my_function="http://example.com">
	<!-- 
		Note the function declaration above.
		While the namespace URI is from the function's extension, I've made up the prefix 'my_function' just for use in this doc.
	-->

<xsl:output method="xml"
	doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
	doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
	omit-xml-declaration="yes"
	encoding="UTF-8"
	indent="yes" />

<!-- include the stream, this does all sorts of awesome  -->	
<xsl:include href='efm://functions' /> 

<xsl:template match="/">
	
	<xsl:variable name='name' select="'I am a boring string.'" />
	<p><xsl:value-of select='my_function:hello($name)' /></p>
	
	
	<xsl:variable name='testnode'>
		<node>Hello, I'm a node. I've been to PHP and back. Wild stuff.</node>
	</xsl:variable>
	<xsl:copy-of select='my_function:hellonode($testnode)' />

</xsl:template>
</xsl:stylesheet>