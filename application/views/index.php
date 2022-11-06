<?php
    $user_id = $this->session->userdata('userid');
    if($user_id != ''){
        echo "<script>location.replace('".base_url()."home');</script>";
    }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Minimal Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="<?php echo base_url();?>Assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href="<?php echo base_url();?>Assets/css/style.css" rel='stylesheet' type='text/css' />
<link href="<?php echo base_url();?>Assets/css/font-awesome.css" rel="stylesheet"> 
<script src="<?php echo base_url();?>Assets/js/jquery.min.js"> </script>
<script src="<?php echo base_url();?>Assets/js/bootstrap.min.js"> </script>

<script src="<?php echo base_url();?>Assets/sweetalert2-8.8.0/package/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>Assets/sweetalert2-8.8.0/package/dist/sweetalert2.min.css">
</head>
<body>
	<div class="login">
		<h1><a href="index.html">TPA PUTRI CEMPO </a></h1>
		<div class="login-bottom">
			<h2>Login</h2>
			<form id="loginform">
			<div class="col-md-12">
				<div class="login-mail">
					<input type="text" placeholder="Username" required="" id="username" name="username"> 
					<i class="fa fa-envelope"></i>
				</div>
				<div class="login-mail">
					<input type="password" placeholder="Password" required="" id="password" name="password">
					<i class="fa fa-lock"></i>
				</div>
				<button class="btn btn-success" id="btn_login" /> Login</button>
			</div>
			
			<div class="clearfix"> </div>
			</form>
		</div>
	</div>
		<!---->
<div class="copy-right">
            <p> &copy; 2020 TPA Putri Cempo <a href="http://w3layouts.com/" target="_blank">TPA Putri Cempo</a> </p>	    </div>  
<!---->
<!--scrolling js-->
<script src="<?php echo base_url();?>Assets/js/jquery.nicescroll.js"></script>
<script src="<?php echo base_url();?>Assets/js/scripts.js"></script>
<!--//scrolling js-->
</body>
</html>
<script type="text/javascript">
    $(function () {
        // Handle CSRF token
        $.ajaxSetup({
            beforeSend:function(jqXHR, Obj){
                var value = "; " + document.cookie;
                var parts = value.split("; csrf_cookie_token=");
                if(parts.length == 2)
                Obj.data += '&csrf_token='+parts.pop().split(";").shift();
            }
        });
        $(document).ready(function () {
            // body...
        });
        // end Handle CSRF token
        $('#loginform').submit(function (e) {
            $('#btn_login').text('Tunggu Sebentar...');
            $('#btn_login').attr('disabled',true);

            e.preventDefault();
            var me = $(this);
            // alert(me.serialize());
            $.ajax({
                type: "post",
                url: "<?=base_url()?>Auth/Log_Pro",
                data: me.serialize(),
                dataType: "json",
                success:function (response) {
                    if(response.success == true){
                        location.replace("<?=base_url()?>Home")
                    }
                    else{
                        if(response.message == 'L-01'){
                            Swal.fire({
                              type: 'error',
                              title: 'Oops...',
                              text: 'User dan password tidak sesuai dengan database!',
                              // footer: '<a href>Why do I have this issue?</a>'
                            }).then((result)=>{
                                $('#username').val('');
                                $('#password').val('');
                                $('#btn_login').text('Login');
                                $('#btn_login').attr('disabled',false);
                            });
                        }
                        else if(response.message == 'L-02'){
                            Swal.fire({
                              type: 'error',
                              title: 'Oops...',
                              text: 'User tidak di temukan!',
                              footer: '<a href>Why do I have this issue?</a>'
                            }).then((result)=>{
                                $('#username').val('');
                                $('#password').val('');
                                $('#btn_login').text('Login');
                                $('#btn_login').attr('disabled',false);
                            });
                        }
                        else{
                            Swal.fire({
                              type: 'error',
                              title: 'Oops...',
                              text: 'Undefine Error Contact your system administrator!',
                              footer: '<a href>Why do I have this issue?</a>'
                            }).then((result)=>{
                                $('#username').val('');
                                $('#password').val('');
                                $('#btn_login').text('Login');
                                $('#btn_login').attr('disabled',false);
                            });
                        }
                    }
                }
            });
        });
    });
</script>
