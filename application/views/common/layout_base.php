<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?=$_SETTING[$this->site.'_name'];?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=HTTP_PLUGIN;?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=HTTP_CSS;?>sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=HTTP_PLUGIN;?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<!-- jQuery -->
    <script src="<?=HTTP_PLUGIN;?>jquery-1.12.4.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
    <script src="<?=HTTP_PLUGIN;?>bootstrap/dist/js/bootstrap.min.js"></script>

    <?php if (isset($_JSON)): ?>
		<script type="text/javascript">
			var BK = BK || <?=json_encode($_JSON);?> || {};
		</script>
	<?php endif; ?>

</head>
<body>
	<?php
		// Loading inner content page
		echo $__content;
	?>

	<!-- Custom Theme JavaScript -->
    <!-- <script src="<?=HTTP_JS;?>sb&#45;admin&#45;2.js"></script> -->
</body>
</html>
