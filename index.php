<?php 
include_once("./url.php")
 ?>
<!DOCTYPE HTML>
<html>
<head>
<title>pending in</title>
<!-- Bootstrap -->
<link href="/bootstrap/css/bootstrap.min.css" rel='stylesheet' type='text/css' />

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">

<script type="text/javascript" src="/themes/tmp/js/jquery.min.js"></script>

<script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
</head>
<body style="width:80%;margin:0 auto;">

<?php 
$today=date("Y-m-d",time());
$tomorrow=date("Y-m-d",strtotime("tomorrow"));
$yesterday=date("Y-m-d",strtotime("yesterday"));
login();
$todayin=getDomains("in",$today);
$tomorrowin=getDomains("in",$tomorrow);
$todayorg=getDomains("org",$today);	
echo "<table class=\"table table-striped \"><th>pending date= {$today} .in</th><th>google site</th> <th>open archive</th>";

foreach ($todayin as $key => $domain) {
    
    echo "
              <tr>
                  <td><a href=\"https://www.google.com/?gws_rd=ssl#q=site:".$domain."\" target=\"_blank\">".$domain."</a></td>
                  <td><a href=\"https://www.google.com/?gws_rd=ssl#q=site:".$domain."\" target=\"_blank\">"."查看site结果"."</a></td>
                  <td><a href=\"https://web.archive.org/web/*/http://".$domain."\" target=\"_blank\">"."查看archive结果"."</a></td>
                </tr>";
          
  }
echo "</table>";

echo "<table class=\"table table-striped\"><th>pending date= {$tomorrow} .in</th><th>google site</th> <th>open archive</th>";

foreach ($todayin as $key => $domain) {
    
    echo "
    
              <tr>
                  <td><a href=\"https://www.google.com/?gws_rd=ssl#q=site:".$domain."\" target=\"_blank\">".$domain."</a></td>
                  <td><a href=\"https://www.google.com/?gws_rd=ssl#q=site:".$domain."\" target=\"_blank\">"."查看site结果"."</a></td>
                  <td><a href=\"https://web.archive.org/web/*/http://".$domain."\" target=\"_blank\">"."查看archive结果"."</a></td>
                </tr>";
          
  }
echo "</table>";

echo "<table class=\"table table-striped\"><th>pending date= {$today} .org</th><th>google site</th> <th>open archive</th>";

foreach ($todayorg as $key => $domain) {
    
    echo "
    
              <tr>
                  <td><a href=\"https://www.google.com/?gws_rd=ssl#q=site:".$domain."\" target=\"_blank\">".$domain."</a></td>
                  <td><a href=\"https://www.google.com/?gws_rd=ssl#q=site:".$domain."\" target=\"_blank\">"."查看site结果"."</a></td>
                  <td><a href=\"https://web.archive.org/web/*/http://".$domain."\" target=\"_blank\">"."查看archive结果"."</a></td>
                </tr>";
          
  }
echo "</table>";
  
 ?>



 

</body>
</html>
 <!--cache-html-->
