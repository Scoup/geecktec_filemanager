<?php
$this->layout = '';
	header("HTTP/1.0 200 OK");
	header('Content-type: text/json; charset=utf-8');
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Pragma: no-cache");
//header("X-JSON: ".$this->Js->object($output)); 
echo $this->Js->object($output); 
die();
?>