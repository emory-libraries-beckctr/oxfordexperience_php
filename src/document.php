<?php

include_once("config.php");
include("common_functions.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_REQUEST["id"];

$terms = $_REQUEST["keyword"];

$view = $_REQUEST["view"];

$exist_args{"debug"} =false;
$xmldb = new xmlDbConnection($exist_args);

html_head("Documents", true);


include("web/xml/browse-head.xml");

print '<div class="content">';

print '<h2>Oxford Experience Documents</h2>';


if($view == "xml") {
  $xsl_file = "xslt/xml.xsl";}
 else {
$xsl_file = "xslt/article.xsl";
 $header_xsl = "xslt/oxexp-dc.xsl";
 $header2_xsl = "xslt/dc-htmldc.xsl";
    }

$xsl_params = array('view' => $view, 'id' => $id);





$query='declare namespace tei="http://www.tei-c.org/ns/1.0";
declare option exist:serialize "highlight-matches=all";
for $b in /tei:TEI[@xml:id="' . "$id" . '"]';
if ($terms != '') {$query .= "[. |= \"$terms\"]";}
$query .= 'return
<result>
{$b/@xml:id}
{$b}
</result>';

if ($view == "xml") {
$xmldb->xquery($query);
print '<a href="document.php?id=' . $id . '">Return to document</a>';
$xmldb->displayXML();
    }
else {
// run the query 
$xmldb->xquery($query);
$xmldb->xslBind($header_xsl);
$xmldb->xslBind($header2_xsl);
$xmldb->transform();

$xmldb->xslTransform($xsl_file, $xsl_params);
$xmldb->printResult();
}
?> 
   
</div>
   
<?php
  include("web/xml/footer.xml");
  include("web/xml/google-trackoxex.xml");
?>


</body>
</html>
