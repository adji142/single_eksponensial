
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Minimal an Admin Panel Category Flat Bootstrap Responsive Website Template | Gallery :: w3layouts</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Minimal Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="<?php echo base_url();?>Assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!--<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />-->
<!-- Custom Theme files -->
<link href="<?php echo base_url();?>Assets/css/style.css" rel='stylesheet' type='text/css' />
<link href="<?php echo base_url();?>Assets/css/font-awesome.css" rel="stylesheet"> 
<script src="<?php echo base_url();?>Assets/js/jquery.min.js"> </script>
<script src="<?php echo base_url();?>Assets/js/bootstrap.min.js"> </script>
  
<!-- Mainly scripts -->
<script src="<?php echo base_url();?>Assets/js/jquery.metisMenu.js"></script>
<script src="<?php echo base_url();?>Assets/js/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<link href="<?php echo base_url();?>Assets/css/custom.css" rel="stylesheet">
<script src="<?php echo base_url();?>Assets/js/custom.js"></script>
<script src="<?php echo base_url();?>Assets/js/screenfull.js"></script>
		<script>
		$(function () {
			$('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

			if (!screenfull.enabled) {
				return false;
			}

			

			$('#toggle').click(function () {
				screenfull.toggle($('#container')[0]);
			});
			

			
		});
		</script>



</head>
<body>
<div id="col-md-12">
	<div class="content-main">
		<div class="banner">
	    	<h2>
		<a href="#">Home</a>
		<i class="fa fa-angle-right"></i>
			<span>Gallery</span>
		<i class="fa fa-angle-right"></i>
			<a href="home/login">Login</a>
			</h2>
	    </div>
	</div>
	<div class="gallery">
		<?php 
			$data = $this->ModelsExecuteMaster->GetData('tgalery')->result();

			$template = '';
			foreach ($data as $key) {
				$linkImage = base_url().'Assets/images/upload/'.$key->Image;
				$template .= '
					<div class="col-md">
						<div class="gallery-img">
							<a href="'.$linkImage.'" class="b-link-stripe b-animate-go  swipebox"  title="'.$key->Nama.'" >
								 <img class="img-responsive " src="'.$linkImage.'" alt="" />   
									<span class="zoom-icon"> </span> </a>
							</a>
						</div>	
			 	 		<div class="text-gallery">
			 	 			<h6>'.$key->Nama.'</h6>
			 	 		</div>
			 	 	</div>
				';
			}
			echo $template;
		?>
	</div>
	<!--//gallery-->
		<!---->
<div class="copy">
            <p> &copy; 2016 Minimal. All Rights Reserved | Design by <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>	    </div>
		</div>
		</div>
		<div class="clearfix"> </div>
       </div>
     
<!---->
<link rel="stylesheet" href="<?php echo base_url();?>Assets/css/swipebox.css">
	<script src="<?php echo base_url();?>Assets/js/jquery.swipebox.min.js"></script> 
	    <script type="text/javascript">
			jQuery(function($) {
				$(".swipebox").swipebox();
			});
</script>
<!--scrolling js-->
	<script src="<?php echo base_url();?>Assets/js/jquery.nicescroll.js"></script>
	<script src="<?php echo base_url();?>Assets/js/scripts.js"></script>
	<!--//scrolling js-->

</body>
</html>

