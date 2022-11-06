<?php
  $user_id = $this->session->userdata('userid');
  $NamaUser = $this->session->userdata('NamaUser');
  if($user_id == ''){
    echo "<script>location.replace('".base_url()."home');</script>";
  }
//test
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Minimal Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<!-- <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script> -->
<link href="<?php echo base_url();?>Assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href="<?php echo base_url();?>Assets/css/style.css" rel='stylesheet' type='text/css' />
<link href="<?php echo base_url();?>Assets/css/font-awesome.css" rel="stylesheet"> 
<script src="<?php echo base_url();?>Assets/js/jquery.min.js"> </script>
<!-- Mainly scripts -->
<script src="<?php echo base_url();?>Assets/js/jquery.metisMenu.js"></script>
<!-- <script src="<?php echo base_url();?>Assets/js/jquery.slimscroll.min.js"></script> -->
<!-- Custom and plugin javascript -->
<link href="<?php echo base_url();?>Assets/css/custom.css" rel="stylesheet">
<script src="<?php echo base_url();?>Assets/js/custom.js"></script>
<script src="<?php echo base_url();?>Assets/js/screenfull.js"></script>
<!--skycons-icons-->
<script src="<?php echo base_url();?>Assets/js/skycons.js"></script>
<!--//skycons-icons-->

<!-- dev express -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>Assets/devexpress/dx.common.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>Assets/devexpress/dx.light.css" />
<script src="<?php echo base_url();?>Assets/devexpress/jszip.min.js"></script>
<script src="<?php echo base_url();?>Assets/devexpress/dx.all.js"></script>

</head>
<body>
<div id="wrapper">

<!----->
        <nav class="navbar-default navbar-static-top" role="navigation">
             <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <h1> <a class="navbar-brand" href="index.html">Putri Cempo</a></h1>         
			   </div>
			 <div class=" border-bottom">
        	<div class="full-left">
            <div class="clearfix"> </div>
           </div>
     
       
            <!-- Brand and toggle get grouped for better mobile display -->
		 
		   <!-- Collect the nav links, forms, and other content for toggling -->
			<div class="clearfix">
       
     </div>