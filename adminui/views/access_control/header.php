<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DOL | APIv2 AdminUI Dashboard</title>
<?php
//<!-- Core CSS - Include with every page -->
$style = array(
	'href' => base_url().'assets/css/bootstrap.min.css',
    'rel' => 'stylesheet'		
);

$font = array(
		'href' => base_url().'assets/font-awesome/css/font-awesome.css',
		'rel' => 'stylesheet'
);

//<!-- Page-Level Plugin CSS - Dashboard -->
$morrischart = array(
		'href' => base_url().'assets/css/plugins/morris/morris-0.4.3.min.css',
		'rel' => 'stylesheet'
);

$timeline = array(
		'href' => base_url().'assets/css/plugins/timeline/timeline.css',
		'rel' => 'stylesheet'
);

// <!-- SB Admin CSS - Include with every page -->
$sbadmin = array(
		'href' => base_url().'assets/css/sb-admin.css',
		'rel' => 'stylesheet'
);

echo link_tag($style);
echo link_tag($font);
echo link_tag($morrischart);
echo link_tag($timeline);
echo link_tag($sbadmin);

?>
</head>
<body>

