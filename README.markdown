#EXSL Function Manager

##Status
This is currently alpha. This should function, but I'm not finished with documentation or testing.

##Synopsis

This extension provides a delegate that allows other extensions to register php functions with Symphony's XSLTProcessor object. It also provides a stream to an XSL document that declares these functions using EXSL:function.

##Background

Symphony 2.0.7 added the capability to register PHP functions for use within XSLT transformations, allowing developers to implement their own functions in a similar fashion as the built-in functions (such as substring(), etc). There are both benefits and pitfalls to this capabilty. One general concern is the increased coupling of XSLT that uses these functions to PHP's XSL implementation. Another related concern is the lack of version control; calling any registered php function uses the php: namespace.

Fortunately EXSL allows for the creation of functions in a custom-defined namespace. The benefit of defining extension function namespaces is that it abstractly attaches the function to its behavior, rather than to its implementation (as you would in the php: namespace). Assuming the behavior you require necessitates creating an extension function, doing it within its own namespace is the most agnostic way to do so. 

 
## Usage
### Extension Developers

#### EFM Delegate
To register a PHP function for use as an exsl function can do so using the EFM's own delegate: `ManageEXSLFunctions`. The `ManageEXSLFunctions` delegate passes the EXSL Function Manager object. A function is added using the Manager object's `addFunction()` method.

`addFunction($name, $uri-namespace, $handle[optional])`
where

* `$name` = the php function name as it would be called (or added to the normal registerfunction method).
* `$uri-namespace` = The URI used to uniquely identify the function or group of functions you are registering
* `$handle` = An optional name to be used as the exsl function name  if you want the function called in the XSL to be different from the php function name.

Be sure to define all function names (or alternatively handles if set) as well as the namespace used for them in your extension documentation.

#### Accepting DOMElements with Type Hinting
If your extension needs to parse XML sent from the XSL transformation, EFM will automatically convert an XSL variable to a node-set. To trigger this, just type hint your php function variable as an array. The node-set will arrive in your php function as a DomDocument object wrapped in an array. To return an XML node, be sure to return a DOmElement rather than a full DOmDocument.

### In a Symphony Page
With EXSL Function Manager activated, include the registered functions using the efm stream just as you would an xsl document, in the top level of your XSL:

`<xsl:include href='efm://functions' />`

XSL authors will need to declare the namespace of every function managed by EFM that they want to call in their XSL document. The prefix they choose is up to them.

## Examples
See the Examples directory for a Hello World client extension.

## Caveats
Simply put, there are probably a lot more ways to abuse this than anything else. Here are a few general guidelines gleaned from the Symphony community:

1. **Don't** use this to retrieve source data. That's what Data Sources are for.
2. **Don't** use this to retrieve XSL. That's what Pages are for.
3. **Do** use this to generate views, or initiate behavior using those views, that would otherwise be awkward or impossible within Symphony.
4. **Do** use it for utility functions missing from XSLT 1, providing they are not native PHP functions. If they are native php functions, then use the php: namespace for the function.

The bottom line is that Symphony provides a pretty good MVC structure, and introducing php functions into the XSL transformation stands a good chance of mucking that up. So hey, let's be careful out there.



