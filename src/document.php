<?php

include_once("config.php");
include("common_functions.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET["id"];

$terms = $_GET["keyword"];

$exist_args{"debug"} = true;
$xmldb = new xmlDbConnection($exist_args);

html_head("Documents", true);


include("xml/browse-head.xml");

print '<div class="content">';

print '<h2>Oxford Experience Documents</h2>';

$xsl_file = "article.xsl";
$xsl_params = array('mode' => "flat", "vol" => $vol);



/*$for='for $art in /TEI.2//div2[@id="' . "$id" . '"]';
if ($terms != '') {$for .= "[. |= \"$terms\"]";}
$let='let $prev := $art/preceding-sibling::div2[1]
let $next := $art/following-sibling::div2[1]
  let $issue := $art/..';
$return='return
<result>
{$art}
<issueid>
{$issue/@id}
{$issue/head}
</issueid>
<prev>
{$prev/@id}
{$prev/@type}
{$prev/head}
{$prev/docDate}
</prev>
<next>
{$next/@id}
{$next/@type}
{$next/head}
{$next/docDate}
</next>
</result>';

$query="$for $let $return";*/

$query='for $b in /TEI.2[@id="' . "$id" . '"]
return
<result>
{$b}
</result>';

// run the query 
$xmldb->xquery($query);
$xmldb->xslTransform($xsl_file, $xsl_params);
$xmldb->printResult();

?> 
   
</div>
   
<?php
  include("xml/footer.xml");
?>


</body>
</html>