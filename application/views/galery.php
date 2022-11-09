
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
<style type="text/css">
	.float{
		position:fixed;
		width:100px;
		height:100px;
		bottom:40px;
		right:40px;
		background-color:#0C9;
		color:#FFF;
		border-radius:50px;
		text-align:center;
		box-shadow: 2px 2px 3px #999;
	}

	.my-float{
		margin-top:35px;
	}
</style>
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
			$data = $this->ModelsExecuteMaster->GetData('titemmasterdata')->result();

			$template = '';
			foreach ($data as $key) {
				$linkImage = base_url().'Assets/images/upload/'.$key->Image;
				$template .= '
					<div class="col-md">
						<div class="gallery-img">
							<a href="'.$linkImage.'" class="b-link-stripe b-animate-go  swipebox"  title="'.$key->namaitem.'" >
								 <img class="img-responsive " src="'.$linkImage.'" alt="" />   
									<span class="zoom-icon"> </span> </a>
							</a>
						</div>	
			 	 		<div class="text-gallery">
			 	 			<div>Rp. '.number_format($key->Harga).'</div>
			 	 			<button class="span12 btn btn-danger" id ="'.$key->kodeitem.'" >Add to Chart</button>
			 	 		</div>
			 	 	</div>
				';
				// id="itm_'.$key->kodeitem.'"
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

<div class="bs-example2 bs-example-padded-bottom">
  <div class="modal fade" id="ModalAddToChart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h2 class="modal-title">Tambah Item Master</h2>
        </div>
        <div class="modal-body">
          <!-- <form class="form-horizontal" enctype='application/json' id="post_"> -->
                <div class="control-group">
                    <div class="controls">
                        <input type="hidden" name="KodeItem" id="KodeItem" required="" placeholder="KodeItem">
                        <h1><div id="NamaItem"></div></h1><br>
                        <h2>
                        	<font color="red">
                        		<div id="hargaitem"></div>
                        	</font>
                        </h2><br>
                        <img src="#" id="GambarItem" class="img-responsive">
                        <input type="hidden" name="UniqID" id="UniqID" required="" placeholder="UniqID">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Jumlah</label>
                    <div class="controls">
                        <input type="number" name="Qty" id="Qty" required="" placeholder="Jumlah">
                    </div>
                </div>
                <br>
                <button class="btn btn-primary" id="btn_Save">Save</button>
            <!-- </form> -->
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
</div>


<div class="bs-example2 bs-example-padded-bottom">
  <div class="modal fade" id="ModalCheckOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h2 class="modal-title">Tambah Item Master</h2>
        </div>
        <div class="modal-body">
        	<div>
        		<table id="TableCheckout" border="1">
        			<thead>
        				<tr>
	        				<td width="170px">Nama Item</td>
	        				<td width="100px">Qty</td>
	        				<td width="150px">Harga</td>
	        				<td width="150px">Total</td>
	        			</tr>
        			</thead>
        			<tbody></tbody>
        		</table>
        	</div>
        	<br>
        	<div class="control-group">
                <label class="control-label">Tanggal Booking</label>
                <div class="controls">
                    <input type="date" name="TanggalBook" id="TanggalBook" required="" placeholder="NoTlp">
                </div>
            </div>
        	<div class="control-group">
                <label class="control-label">Nama Pemesan</label>
                <div class="controls">
                    <input type="text" name="NamaPemesan" id="NamaPemesan" required="" placeholder="Nama Pemesan">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">No WA</label>
                <div class="controls">
                    <input type="text" name="NoTlp" id="NoTlp" required="" placeholder="NoTlp">
                </div>
            </div>
        	<button class="btn btn-primary" id="btn_Checkout">Save</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
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

	<script src="<?php echo base_url();?>Assets/js/bootstrap.min.js"> </script>

	<script src="<?php echo base_url();?>Assets/js/jquery.flot.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>Assets/css/clndr.css" type="text/css" />
	<script src="<?php echo base_url();?>Assets/js/underscore-min.js" type="text/javascript"></script>
	<script src= "<?php echo base_url();?>Assets/js/moment-2.2.1.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>Assets/js/clndr.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>Assets/js/site.js" type="text/javascript"></script>
	<link href="<?php echo base_url();?>Assets/css/owl.carousel.css" rel="stylesheet">
	<script src="<?php echo base_url();?>Assets/js/owl.carousel.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>Assets/css/graph.css">

	<script src="<?php echo base_url();?>Assets/sweetalert2-8.8.0/package/dist/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>Assets/sweetalert2-8.8.0/package/dist/sweetalert2.min.css">

	<script src="<?php echo base_url();?>Assets/devexpress/bootstrap-select.min.js"></script>
	<!--//scrolling js-->
<a class="float" id="btco">
<i class="fa fa-shopping-cart fa-2x my-float"> <i id="ChartCount"></i></i>
</a>
</body>
</html>

<script type="text/javascript">
	$(function () {
		var DataUUID = '';
		$(document).ready(function () {
			var navigator_info = window.navigator;
			var screen_info = window.screen;
			var uid = navigator_info.mimeTypes.length;
			uid += navigator_info.userAgent.replace(/\D+/g, '');
			uid += navigator_info.plugins.length;
			uid += screen_info.height || '';
			uid += screen_info.width || '';
			uid += screen_info.pixelDepth || '';
			console.log(uid);
			DataUUID = uid;
			$('#UniqID').val(uid);

			$.ajax({
              type: "post",
              url: "<?=base_url()?>Chart/Read",
              data: {UniqID:uid},
              dataType: "json",
              success: function (response) {
                // bindGrid(response.data);
                $('#ChartCount').text(response.data.length);
              }
            });
		});
		$(document).on("click","button",function(){
			// alert(this.id);
			var KodeItemx = this.id;
			$.ajax({
              type: "post",
              url: "<?=base_url()?>ItemMaster/Read",
              data: {kodeitem:KodeItemx},
              dataType: "json",
              success: function (response) {
                // bindGrid(response.data);
                console.log(response.data)
                $('#KodeItem').val(KodeItemx);
                $('#NamaItem').text(response.data[0].namaitem);
                $('#hargaitem').text("Rp. " + addCommas(response.data[0].Harga));
                $("#GambarItem").attr("src","<?php echo base_url().'Assets/images/upload/'?>" + response.data[0].Image);
              }
            });
			$('#ModalAddToChart').modal('show');
			// $('#NamaItem').text("Item dongs");
		});

		$('.close').click(function() {
            location.reload();
        });

		$('#btco').click(function () {
			$.ajax({
              type: "post",
              url: "<?=base_url()?>Chart/Read",
              data: {UniqID:DataUUID},
              dataType: "json",
              success: function (response) {
                // bindGrid(response.data);
                var html = "";
                var Total = 0;
                $.each(response.data,function (k,v) {
                	html += "<tr>";
                	html += "<td>"+v.namaitem+"</td>";
                	html += "<td>"+v.Qty+"</td>";
                	html += "<td>Rp. "+addCommas(v.HargaItem)+"</td>";
                	html += "<td>Rp. "+addCommas(parseFloat(v.Qty) * parseFloat(v.HargaItem))+"</td>";
                	html += "</tr>";
                	Total += parseFloat(v.Qty) * parseFloat(v.HargaItem);
                	// console.log("asdasddad");
                });
                html +="<tr>";
                html +="<td colspan='3'><b>Total</b></td>";
                html +="<td>Rp. "+addCommas(Total)+"</td>"
                html +="</tr>";
                tableBody = $("#TableCheckout tbody");
                tableBody.append(html);
                // console.log(response.data);
                // $("#TableCheckout").append(html);
                // console.log(response.data)
              }
            });
			$('#ModalCheckOut').modal('show');
		});
		$('.close_co').click(function() {
            location.reload();
        });
		$('#btn_Save').click(function () {
			$('#btn_Save').text('Tunggu Sebentar.....');
            $('#btn_Save').attr('disabled',true);

			$.ajax({
              type: "post",
              url: "<?=base_url()?>Chart/appendTransaksi",
              data: {id:0,UniqID:$('#UniqID').val(),KodeItem:$('#KodeItem').val(),Qty:$('#Qty').val(),Harga:0},
              dataType: "json",
              success: function (response) {
                if(response.success == true){
	                $('#modal_').modal('toggle');
	                Swal.fire({
	                  type: 'success',
	                  title: 'Horay..',
	                  text: 'Data Berhasil disimpan!',
	                  // footer: '<a href>Why do I have this issue?</a>'
	                }).then((result)=>{
	                  location.reload();
	            	});
	            }
	            else{
                    $('#modal_').modal('toggle');
                    Swal.fire({
                      type: 'error',
                      title: 'Woops...',
                      text: response.message,
                      // footer: '<a href>Why do I have this issue?</a>'
                    }).then((result)=>{
                        $('#modal_').modal('show');
                        $('#btn_Save').text('Save');
                        $('#btn_Save').attr('disabled',false);
                    });
                  }
              }
            });
		})

		$('#btn_Checkout').click(function () {
			$('#btn_Checkout').text('Tunggu Sebentar.....');
            $('#btn_Checkout').attr('disabled',true);

			$.ajax({
              type: "post",
              url: "<?=base_url()?>Chart/CheckOut",
              data: {UniqID:DataUUID,TglTransaksi:$('#TanggalBook').val(),Nama:$('#NamaPemesan').val(),NoWA:$('#NoTlp').val()},
              dataType: "json",
              success: function (response) {
                if(response.success == true){
	                $('#ModalCheckOut').modal('toggle');
	                Swal.fire({
	                  type: 'success',
	                  title: 'Horay..',
	                  text: 'Data Berhasil disimpan!',
	                  // footer: '<a href>Why do I have this issue?</a>'
	                }).then((result)=>{
	                  location.reload();
	            	});
	            }
	            else{
                    $('#ModalCheckOut').modal('toggle');
                    Swal.fire({
                      type: 'error',
                      title: 'Woops...',
                      text: response.message,
                      // footer: '<a href>Why do I have this issue?</a>'
                    }).then((result)=>{
                        $('#ModalCheckOut').modal('show');
                        $('#btn_Checkout').text('Save');
                        $('#btn_Checkout').attr('disabled',false);
                    });
                  }
              }
            });
		});
        function addCommas(nStr)
		{
		    nStr += '';
		    x = nStr.split('.');
		    x1 = x[0];
		    x2 = x.length > 1 ? '.' + x[1] : '';
		    var rgx = /(\d+)(\d{3})/;
		    while (rgx.test(x1)) {
		        x1 = x1.replace(rgx, '$1' + ',' + '$2');
		    }
		    return x1 + x2;
		}
	});
</script>