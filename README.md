evc
===

External Voucher Code Generator - Re-Written with (PHP)Unit Tests.

The generate codes method accepts 3 parameters:
 - Number of Codes (to be generated)
 - Format for Codes (PREFIX*****)
 - CDA (the ID of the object in Salesforce)
 
 ### 

The main class/code is located in **codes.php**. Each method is tested in **Test.php**.
The class is instantiated and used to write to Salesforce in **index.php**.

To run the tests you will need PHPUnit installed on your machine. ( if you don't already have it, google for it! ) then issue these two commands from command line:

	phpunit --log-junit foo.xml Test.php
	xsltproc foo.xsl foo.xml > output.html 

 
 This module uses the Salesforce API to write data back to SF. See: 
* https://github.com/developerforce/Force.com-Toolkit-for-PHP
* http://wiki.developerforce.com/page/Getting_Started_with_the_Force.com_Toolkit_for_PHP

If you want to *play* with the Salesforce SOAP API open the **force.php** file and edit/run till you "*get*" it.
( you will need update **config.php** with your Salesforce username, password and secure token )
   
You will need to *re-generate the **soap/wsdl file** in salesforce to get the latest Custom Object Definitions (if the DB object you are writing to in SF has changed ) Instructions:

* http://www.salesforce.com/us/developer/docs/api/Content/sforce_api_quickstart_steps.htm #step_2_generate_or_obtain_the_web_service_wsdl