<?php
    require_once(APPPATH."views/parts/header.php");
    require_once(APPPATH."views/parts/sidebar.php");
    require_once(APPPATH."views/parts/footer.php");
    $active = 'dashboard';
?>
<script src="<?php echo base_url();?>Assets/chart/Chart.bundle.js"></script>
<script src="<?php echo base_url();?>Assets/chart/Chart.bundle.min.js"></script>
<script src="<?php echo base_url();?>Assets/chart/Chart.js"></script>
<script src="<?php echo base_url();?>Assets/chart/Chart.min.js"></script>

<link href="<?php echo base_url();?>Assets/chart/Chart.css" rel='stylesheet' type='text/css' />
<link href="<?php echo base_url();?>Assets/chart/Chart.min.css" rel='stylesheet' type='text/css' />
<div id="page-wrapper" class="gray-bg dashbard-1">
   <div class="content-main">

	<!--banner-->	
    <div class="banner">
    	<h2>
		<a href="<?php echo base_url(); ?>">Home</a>
		<i class="fa fa-angle-right"></i>
		<span>Proses</span>
		</h2>
    </div>
	<!--//banner-->
	 <!--faq-->
	<div class="blank">
		<div class="grid-form">
            <div class="grid-form1">
                <div class="form-group">
                    <input type="month" name="startmont" id="startmont">
                    <label>S/D</label>
                    <input type="month" name="endmont" id="endmont">
                    <button class="span12 btn btn-danger" name="getdata" id="getdata">Get Data</button>
                </div>
                <div class="dx-viewport demo-container">
                    <div id="data-grid-demo">
                        <div id="gridContainer">
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <SELECT name = "filter" id = "filter">
                        <option value="1">Motif Kartun</option>
                        <option value="2">Motif Abstrak</option>
                        <option value="3">Motif Tropical</option>
                    </SELECT>
                    <button class="span12 btn btn-danger" name="proses" id="proses">Proses</button>
                </div>
            </div>
            <div class="grid-form1">
                <h5 id="forms-inline">Hasil Perhitungan Forecast</h5>
                <div class="dx-viewport demo-container">
                    <div id="data-grid-demo">
                        <div id="gridContainer_Forecast">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid-form1">
                <h5 id="forms-inline">Hasil Perhitungan MAE</h5>
                <div id="MAEUsed"></div>
                <div class="dx-viewport demo-container">
                    <div id="data-grid-demo">
                        <div id="gridContainer_Mae">
                        </div>
                    </div>
                </div>
            </div>
            <!-- gridContainer_HasilForecast -->
            <div class="grid-form1">
                <h5 id="forms-inline">Hasil Forecast</h5>
                <div class="dx-viewport demo-container">
                    <div id="data-grid-demo">
                        <div id="gridContainer_HasilForecast">
                        </div>
                    </div>
                </div>
                <div id="Perkiraanharga"></div>
            </div>
            <div class="grid-form1">
                <!-- <div class="col-md-8 content-top-2"> -->
                    <h5 id="forms-inline">Grafik</h5>
                    <div class="container">
                        <canvas id="myChart" width="6" height="6"></canvas>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
<div class="copy">
    <p> &copy; 2020 DownStore. All Rights Reserved | Published by <a href="http://aistrick.com.com/" target="_blank">DownStore</a> </p>      
</div>
</div>
</div>
<div class="clearfix"> </div>

<script type="text/javascript">
    var datauji;
    var forcast;
    $(function () {
        // var dataForcast;
        var countofloop = 0;
        $(document).ready(function () {
            var startmont = '1000-01-01';
            var endmont = '1000-01-01';
            $.ajax({
			  async:false,
              type: "post",
              url: "<?=base_url()?>Apps/initialData2nd",
              data: {startmont:startmont,endmont:endmont},
              dataType: "json",
              success: function (response) {
                if (response.success) {
                    datauji = response.data;
                    bindGrid(response.data);
                }
                else{
                    $('#load_data').html(
                        "<p>"+response.message+"</p>"
                    );
                }
              }
            });
        });
        $('#getdata').click(function () {
            var startmont = $('#startmont').val();
            var endmont = $('#endmont').val();

            var label = [];
            var value = [];
            $.ajax({
				async:false,
              type: "post",
              url: "<?=base_url()?>Apps/initialData2nd",
              data: {startmont:startmont,endmont:endmont},
              dataType: "json",
              success: function (response) {
                if (response.success) {
                    datauji = response.data;
                    bindGrid(response.data);
                }
                else{
                    $('#load_data').html(
                        "<p>"+response.message+"</p>"
                    );
                }
              }
            });
        });
        $('#proses').click(function () {
            // hasil forecast
            $('#proses').text('Tunggu sebentar ...');
            $('#proses').attr('disabled',true);

            var periode = '';
            var jumlah = 0;
            var jenis = '';

            var januari = 0.0;
            var februari = 0.0;
            var LastRow = 0.0;

            var LastResH01 = 0.0;
            var LastResH02 = 0.0;
            var LastResH03 = 0.0;
            var LastResH04 = 0.0;
            var LastResH05 = 0.0;
            var LastResH06 = 0.0;
            var LastResH07 = 0.0;
            var LastResH08 = 0.0;
            var LastResH09 = 0.0;

            var H01 = 0.0;
            var H02 = 0.0;
            var H03 = 0.0;
            var H04 = 0.0;
            var H05 = 0.0;
            var H06 = 0.0;
            var H07 = 0.0;
            var H08 = 0.0;
            var H09 = 0.0;

            var filter = $('#filter').val();
            // End hasil forecast
            var x = 0.0;
            var bulan = 1;
            DeleteData(filter);
            $.each(datauji,function (k,v) {
                periode = v.Periode;
                switch(filter){
                    case "1" :
                        jenis = "KARTUN";
                        jumlah = v.KARTUN;
                        if (bulan == 1) { //periode == "Jan-2019"
                            januari = v.KARTUN;
                            H01 = 0.0;
                            H02 = 0.0;
                            H03 = 0.0;
                            H04 = 0.0;
                            H05 = 0.0;
                            H06 = 0.0;
                            H07 = 0.0;
                            H08 = 0.0;
                            H09 = 0.0;
                            LastRes = v.KARTUN;
                            LastResH01 = v.KARTUN;
                            LastResH02 = v.KARTUN;
                            LastResH03 = v.KARTUN;
                            LastResH04 = v.KARTUN;
                            LastResH05 = v.KARTUN;
                            LastResH06 = v.KARTUN;
                            LastResH07 = v.KARTUN;
                            LastResH08 = v.KARTUN;
                            LastResH09 = v.KARTUN;
                        }
                        else if (bulan == 2) { // periode == "Feb-2019"
                            H01 = januari;
                            H02 = januari;
                            H03 = januari;
                            H04 = januari;
                            H05 = januari;
                            H06 = januari;
                            H07 = januari;
                            H08 = januari;
                            H09 = januari;
                            februari = v.KARTUN;
                            LastRow = v.KARTUN;
                        }
                        else{
                            H01 = (0.1 * LastRow) + (0.9 * LastResH01)
                            H02 = (0.2 * LastRow) + (0.8 * LastResH02)
                            H03 = (0.3 * LastRow) + (0.7 * LastResH03)
                            H04 = (0.4 * LastRow) + (0.6 * LastResH04)
                            H05 = (0.5 * LastRow) + (0.5 * LastResH05)
                            H06 = (0.6 * LastRow) + (0.4 * LastResH06)
                            H07 = (0.7 * LastRow) + (0.3 * LastResH07)
                            H08 = (0.8 * LastRow) + (0.2 * LastResH08)
                            H09 = (0.9 * LastRow) + (0.1 * LastResH09)
                            console.log("(0.1 * "+LastRow+") + (0.9  *" +LastResH01+") = "+H01);

                            LastRow = v.KARTUN;
                            LastResH01 = H01
                            LastResH02 = H02
                            LastResH03 = H03
                            LastResH04 = H04
                            LastResH05 = H05
                            LastResH06 = H06
                            LastResH07 = H07
                            LastResH08 = H08
                            LastResH09 = H09
                        }
                    break;
                    case "2" :
                        jenis = "ABSTRAK";
                        jumlah = v.ABSTRAK;
                        if (bulan == 1) { //periode == "Jan-2019"
                            januari = v.ABSTRAK;
                            H01 = 0.0;
                            H02 = 0.0;
                            H03 = 0.0;
                            H04 = 0.0;
                            H05 = 0.0;
                            H06 = 0.0;
                            H07 = 0.0;
                            H08 = 0.0;
                            H09 = 0.0;
                            LastRes = v.ABSTRAK;
                            LastResH01 = v.ABSTRAK;
                            LastResH02 = v.ABSTRAK;
                            LastResH03 = v.ABSTRAK;
                            LastResH04 = v.ABSTRAK;
                            LastResH05 = v.ABSTRAK;
                            LastResH06 = v.ABSTRAK;
                            LastResH07 = v.ABSTRAK;
                            LastResH08 = v.ABSTRAK;
                            LastResH09 = v.ABSTRAK;
                        }
                        else if (bulan == 2) { //periode == "Feb-2019"
                            H01 = januari;
                            H02 = januari;
                            H03 = januari;
                            H04 = januari;
                            H05 = januari;
                            H06 = januari;
                            H07 = januari;
                            H08 = januari;
                            H09 = januari;
                            februari = v.ABSTRAK;
                            LastRow = v.ABSTRAK;
                        }
                        else{
                            H01 = (0.1 * LastRow) + (0.9 * LastResH01)
                            H02 = (0.2 * LastRow) + (0.8 * LastResH02)
                            H03 = (0.3 * LastRow) + (0.7 * LastResH03)
                            H04 = (0.4 * LastRow) + (0.6 * LastResH04)
                            H05 = (0.5 * LastRow) + (0.5 * LastResH05)
                            H06 = (0.6 * LastRow) + (0.4 * LastResH06)
                            H07 = (0.7 * LastRow) + (0.3 * LastResH07)
                            H08 = (0.8 * LastRow) + (0.2 * LastResH08)
                            H09 = (0.9 * LastRow) + (0.1 * LastResH09)
                            // console.log(x.toFixed(1) + " * "+LastRow+" + (1 - "+x.toFixed(1)+" ) *" +LastResH03+" = "+H03);

                            LastRow = v.ABSTRAK;
                            LastResH01 = H01
                            LastResH02 = H02
                            LastResH03 = H03
                            LastResH04 = H04
                            LastResH05 = H05
                            LastResH06 = H06
                            LastResH07 = H07
                            LastResH08 = H08
                            LastResH09 = H09
                        }
                    break;
                    case "3" :
                        jenis = "TROPIKAL";
                        jumlah = v.TROPIKAL;
                        if (bulan == 1) { //periode == "Jan-2019"
                            januari = v.TROPIKAL;
                            H01 = 0.0;
                            H02 = 0.0;
                            H03 = 0.0;
                            H04 = 0.0;
                            H05 = 0.0;
                            H06 = 0.0;
                            H07 = 0.0;
                            H08 = 0.0;
                            H09 = 0.0;
                            LastRes = v.TROPIKAL;
                            LastResH01 = v.TROPIKAL;
                            LastResH02 = v.TROPIKAL;
                            LastResH03 = v.TROPIKAL;
                            LastResH04 = v.TROPIKAL;
                            LastResH05 = v.TROPIKAL;
                            LastResH06 = v.TROPIKAL;
                            LastResH07 = v.TROPIKAL;
                            LastResH08 = v.TROPIKAL;
                            LastResH09 = v.TROPIKAL;
                        }
                        else if (bulan == 2) { //periode == "Feb-2019"
                            H01 = januari;
                            H02 = januari;
                            H03 = januari;
                            H04 = januari;
                            H05 = januari;
                            H06 = januari;
                            H07 = januari;
                            H08 = januari;
                            H09 = januari;
                            februari = v.TROPIKAL;
                            LastRow = v.TROPIKAL;
                        }
                        else{
                            H01 = (0.1 * LastRow) + (0.9 * LastResH01)
                            H02 = (0.2 * LastRow) + (0.8 * LastResH02)
                            H03 = (0.3 * LastRow) + (0.7 * LastResH03)
                            H04 = (0.4 * LastRow) + (0.6 * LastResH04)
                            H05 = (0.5 * LastRow) + (0.5 * LastResH05)
                            H06 = (0.6 * LastRow) + (0.4 * LastResH06)
                            H07 = (0.7 * LastRow) + (0.3 * LastResH07)
                            H08 = (0.8 * LastRow) + (0.2 * LastResH08)
                            H09 = (0.9 * LastRow) + (0.1 * LastResH09)
                            // console.log(x.toFixed(1) + " * "+LastRow+" + (1 - "+x.toFixed(1)+" ) *" +LastResH03+" = "+H03);

                            LastRow = v.TROPIKAL;
                            LastResH01 = H01
                            LastResH02 = H02
                            LastResH03 = H03
                            LastResH04 = H04
                            LastResH05 = H05
                            LastResH06 = H06
                            LastResH07 = H07
                            LastResH08 = H08
                            LastResH09 = H09
                        }
                    break;
                }
                AppendData(bulan,periode,jenis,jumlah,H01,H02,H03,H04,H05,H06,H07,H08,H09);
                bulan += 1;
                // console.log(periode);
                // console.log(jenis);
                // console.log(jumlah);

                // console.log(H01);
                // console.log(H02);
                // console.log(H03);
                // console.log(H04);
                // console.log(H05);
                // console.log(H06);
                // console.log(H07);
                // console.log(H08);
                // console.log(H09);
            });
            // console.log(x.toFixed(1) + " * "+LastRow+" + (1 - "+x.toFixed(1)+" ) *" +LastResH03);
            H01 = (0.1 * LastRow) + (0.9 * LastResH01)
            LastResH01 = H01

            H02 = (0.2 * LastRow) + (0.8 * LastResH02)
            LastResH02 = H02

            H03 = (0.3 * LastRow) + (0.7 * LastResH03)
            LastResH03 = H03

            H04 = (0.4 * LastRow) + (0.6 * LastResH04)
            LastResH04 = H04

            H05 = (0.5 * LastRow) + (0.5 * LastResH05)
            LastResH05 = H05

            H06 = (0.6 * LastRow) + (0.4 * LastResH06)
            LastResH06 = H06

            H07 = (0.7 * LastRow) + (0.3 * LastResH07)
            LastResH07 = H07

            H08 = (0.8 * LastRow) + (0.2 * LastResH08)
            LastResH08 = H08

            H09 = (0.9 * LastRow) + (0.1 * LastResH09)
            LastResH09 = H09
            bulanx = bulan;
            alpabeth = "";
            Tahun = "";
            console.log(bulan);
            if (bulan > 12) {
                bulan = 1;
                Tahun = "2020";
            }
            else{
                Tahun = periode.substring(4,8);
            }
            switch(bulan){
                case 1 :
                    alpabeth = "Jan"
                break;
                case 2 :
                    alpabeth = "Feb"
                break;
                case 3 :
                    alpabeth = "Mar"
                break;
                case 4 :
                    alpabeth = "Apr"
                break;
                case 5 :
                    alpabeth = "Mei"
                break;
                case 6 :
                    alpabeth = "Jun"
                break;
                case 7 :
                    alpabeth = "Jul"
                break;
                case 8 :
                    alpabeth = "Ags"
                break;
                case 9 :
                    alpabeth = "Sep"
                break;
                case 10 :
                    alpabeth = "Okt"
                break;
                case 11 :
                    alpabeth = "Nov"
                break;
                case 12 :
                    alpabeth = "Des"
                break;
            }
            AppendData(bulanx,alpabeth+'-'+Tahun,jenis,jumlah,H01,H02,H03,H04,H05,H06,H07,H08,H09);
            ShowDataForecast(jenis);
        });
    });
    function AppendData(number,Periode,Jenis,Jumlah,F01,F02,F03,F04,F05,F06,F07,F08,F09) {
        $.ajax({
			async:false,
            type    :'post',
            url     : '<?=base_url()?>Apps/AddForecast',
            data    : {number:number,Periode:Periode,Jenis:Jenis,Jumlah:Jumlah,F01:F01,F02:F02,F03:F03,F04:F04,F05:F05,F06:F06,F07:F07,F08:F08,F09:F09},
            dataType: 'json',
            success : function (response) {
              if(response.success == true){
                console.log("saved");
              }
              else{
                Swal.fire({
                  type: 'error',
                  title: 'Woops...',
                  text: response.message,
                  // footer: '<a href>Why do I have this issue?</a>'
                }).then((result)=>{
                    $('#proses').text('Proses');
                    $('#proses').attr('disabled',false);
                });
              }
            }
          });
    }
    function DeleteData(Jenis) {
        var jns;

        switch(Jenis){
            case "1" : 
                jns = "KARTUN"
            break;
            case "2" : 
                jns = "ABSTRAK"
            break;
            case "3" : 
                jns = "TROPIKAL"
            break;
        }
        $.ajax({
			async:false,
            type    :'post',
            url     : '<?=base_url()?>Apps/RemoveData',
            data    : {'Jenis':jns},
            dataType: 'json',
            success : function (response) {
              if(response.success == true){
                console.log('done');
              }
              else{
                Swal.fire({
                  type: 'error',
                  title: 'Woops...',
                  text: response.message,
                  // footer: '<a href>Why do I have this issue?</a>'
                }).then((result)=>{
                    location.reload();
                });
              }
            }
          });
    }
    function ShowDataForecast(Filter) {
        $.ajax({
			async:false,
          type: "post",
          url: "<?=base_url()?>Apps/ShowDataForcast",
          data: {'Jenis':Filter},
          dataType: "json",
          success: function (response) {
            if (response.success) {
                GenerateMae(response.data);
                bindGridforcast(response.data);
            }
            else{
                $('#load_data').html(
                    "<p>"+response.message+"</p>"
                );
            }
          }
        });
    }
    function GenerateMae(dataForcast) {
        // MAEUsed
        var Total01 = 0.00;
        var Total02 = 0.00;
        var Total03 = 0.00;
        var Total04 = 0.00;
        var Total05 = 0.00;
        var Total06 = 0.00;
        var Total07 = 0.00;
        var Total08 = 0.00;
        var Total09 = 0.00;

        var Hasil01 = 0.00;
        var Hasil02 = 0.00;
        var Hasil03 = 0.00;
        var Hasil04 = 0.00;
        var Hasil05 = 0.00;
        var Hasil06 = 0.00;
        var Hasil07 = 0.00;
        var Hasil08 = 0.00;
        var Hasil09 = 0.00;

        var count = 0;

        var Jenis='';
        console.log(dataForcast.length);

        var MaeUsed = 0;
        var index = 0;
        $.each(dataForcast,function (k,v) {
            Jenis = v.Jenis;
            if (v.number == 1) {
                Total01 += 0.00;
                Total02 += 0.00;
                Total03 += 0.00;
                Total04 += 0.00;
                Total05 += 0.00;
                Total06 += 0.00;
                Total07 += 0.00;
                Total08 += 0.00;
                Total09 += 0.00;

                count += 0;
            }
            else if(v.number == dataForcast.length){
                Total01 += 0.00;
                Total02 += 0.00;
                Total03 += 0.00;
                Total04 += 0.00;
                Total05 += 0.00;
                Total06 += 0.00;
                Total07 += 0.00;
                Total08 += 0.00;
                Total09 += 0.00;

                count += 0;
            }
            else{
                Total01 += Math.abs(v.Jumlah - v.F01);
                Total02 += Math.abs(v.Jumlah - v.F02);
                Total03 += Math.abs(v.Jumlah - v.F03);
                Total04 += Math.abs(v.Jumlah - v.F04);
                Total05 += Math.abs(v.Jumlah - v.F05);
                Total06 += Math.abs(v.Jumlah - v.F06);
                Total07 += Math.abs(v.Jumlah - v.F07);
                Total08 += Math.abs(v.Jumlah - v.F08);
                Total09 += Math.abs(v.Jumlah - v.F09);

                count += 1;
            }
            index += 1;
        });

        Hasil01 = Total01/count;
        Hasil02 = Total02/count;
        Hasil03 = Total03/count;
        Hasil04 = Total04/count;
        Hasil05 = Total05/count;
        Hasil06 = Total06/count;
        Hasil07 = Total07/count;
        Hasil08 = Total08/count;
        Hasil09 = Total09/count;

        MaeUsed = Math.min(Hasil01,Hasil02,Hasil03,Hasil04,Hasil05,Hasil06,Hasil07,Hasil08,Hasil09);

        var xdata = '';
        var alpha = '';
        switch(MaeUsed){
            case MaeUsed = Hasil01 :
                xdata = 'Alpa yang dipakai = 0.1 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F01';
                GetVerifiedForcast('F01');
            break;
            case MaeUsed = Hasil02 :
                xdata = 'Alpa yang dipakai = 0.2 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F02';
                GetVerifiedForcast('F02');
            break;
            case MaeUsed = Hasil03 :
                xdata = 'Alpa yang dipakai = 0.3 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F03';
                GetVerifiedForcast('F03');
            break;
            case MaeUsed = Hasil04 :
                xdata = 'Alpa yang dipakai = 0.4 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F04';
                GetVerifiedForcast('F04');
            break;
            case MaeUsed = Hasil05 :
                xdata = 'Alpa yang dipakai = 0.5 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F05';
                GetVerifiedForcast('F05');
            break;
            case MaeUsed = Hasil06 :
                xdata = 'Alpa yang dipakai = 0.6 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F06';
                GetVerifiedForcast('F06');
            break;
            case MaeUsed = Hasil07 :
                xdata = 'Alpa yang dipakai = 0.7 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F07';
                GetVerifiedForcast('F07');
            break;
            case MaeUsed = Hasil08 :
                xdata = 'Alpa yang dipakai = 0.8 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F08';
                GetVerifiedForcast('F08');
            break;
            case MaeUsed = Hasil09 :
                xdata = 'Alpa yang dipakai = 0.9 dengan nilai '+MaeUsed+' ';
                $('#MAEUsed').html(
                    "<p>"+xdata+"</p>"
                );
                alpha = 'F09';
                GetVerifiedForcast('F09');
            break;
        }

        console.log(MaeUsed);

        $.ajax({
			async:false,
            type    :'post',
            url     : '<?=base_url()?>Apps/RemoveMae',
            data    : {'Jenis':Jenis},
            dataType: 'json',
            success : function (response) {
              if(response.success == true){
                console.log('Deleted');
              }
              else{
                Swal.fire({
                  type: 'error',
                  title: 'Woops...',
                  text: response.message,
                  // footer: '<a href>Why do I have this issue?</a>'
                }).then((result)=>{
                    location.reload();
                });
              }
            }
          });

        $.ajax({
			async:false,
            type    :'post',
            url     : '<?=base_url()?>Apps/AddMae',
            data    : {Jenis:Jenis,'F01':Hasil01,'F02':Hasil02,'F03':Hasil03,'F04':Hasil04,'F05':Hasil05,'F06':Hasil06,'F07':Hasil07,'F08':Hasil08,'F09':Hasil09},
            dataType: 'json',
            success : function (response) {
              if(response.success == true){
                console.log("saved");
              }
              else{
                Swal.fire({
                  type: 'error',
                  title: 'Woops...',
                  text: response.message,
                  // footer: '<a href>Why do I have this issue?</a>'
                }).then((result)=>{
                    $('#proses').text('Save');
                    $('#proses').attr('disabled',false);
                });
              }
            }
          });
        // bindGridMAE

        $.ajax({
			async:false,
          type: "post",
          url: "<?=base_url()?>Apps/ShowDataMAE",
          data: {'Jenis':Jenis},
          dataType: "json",
          success: function (response) {
            if (response.success) {
                bindGridMAE(response.data);

            }
            else{
                $('#load_data').html(
                    "<p>"+response.message+"</p>"
                );
            }
          }
        });
        $('#proses').text('Proses');
        $('#proses').attr('disabled',false);
        // ShowChart();
        getForecastdata(alpha);
    }
    function GetVerifiedForcast(Field) {
        var tipe = $('#filter').val();

        switch(tipe){
            case "1" :
                tipe = "KARTUN";
            break;
            case "2" :
                tipe = "ABSTRAK";
            break;
            case "3" :
                tipe = "TROPIKAL"
        }
        $.ajax({
			async:false,
          type: "post",
          url: "<?=base_url()?>Apps/GetverifiedForecast",
          data: {Field:Field,tipe:tipe},
          dataType: "json",
          success: function (response) {
            if (response.success) {
                forcast = response.data;   
                ShowChart(response.data);             
            }
            else{
                $('#load_data').html(
                    "<p>"+response.message+"</p>"
                );
            }
            console.log(forcast);
          }
        });
    }
    function getForecastdata (mae) {
        var tipe = $('#filter').val();
        var dataAll =  0;
        var hargakain = 0;
        var hargakaret = 0;
        var ongkosjait =0;
        var ongkospotong =0;

        switch(tipe){
            case "1" :
                tipe = "KARTUN";
            break;
            case "2" :
                tipe = "ABSTRAK";
            break;
            case "3" :
                tipe = "TROPIKAL"
        }
        $.ajax({
          async:false,
          type: "post",
          url: "<?=base_url()?>Apps/ShowForecstHasilGG",
          data: {mae:mae,tipe:tipe},
          dataType: "json",
          success: function (response) {
            if (response.success) {
                dataAll = response.data;
                bindGridHasilForecast(response.data)
            }
            else{
                $('#load_data').html(
                    "<p>"+response.message+"</p>"
                );
            }
          }
        });
        console.log(dataAll[0].Forecast);

        $.ajax({
          async:false,
          type: "post",
          url: "<?=base_url()?>Apps/ShowForecstHasilGG",
          data: {mae:mae,tipe:tipe},
          dataType: "json",
          success: function (response) {
            if (response.success) {
                dataAll = response.data;
                bindGridHasilForecast(response.data)
            }
            else{
                $('#load_data').html(
                    "<p>"+response.message+"</p>"
                );
            }
          }
        });
        // Perkiraanharga
        var where_field = '';
        var where_value = '';
        var table = 'thppsetting';

        $.ajax({
          async:false,
          type: "post",
          url: "<?=base_url()?>Apps/FindData",
          data: {where_field:where_field,where_value:where_value,table:table},
          dataType: "json",
          success: function (response) {
            // bindGrid(response.data);
            var kebutuhankain = Math.round((dataAll[0].Forecast/2)*0.45);
            $('#Perkiraanharga').html(
                "<pre>"+
                    "Kebutuhan Kain     : "+ numberWithCommas(kebutuhankain * response.data[0].hargakain) + "<br>" +
                    "Kebutuhan Karet    : "+ numberWithCommas(dataAll[0].Forecast * response.data[0].hargakaret) +"<br>" +
                    "Ongkos Jahit       : "+ numberWithCommas(dataAll[0].Forecast * response.data[0].ongkosjait) +"<br>" + 
                    "Ongkos Potong      : "+ numberWithCommas(dataAll[0].Forecast * response.data[0].ongkospotong) +"<br>"+
                    "Ongkos Packing     : "+ numberWithCommas(dataAll[0].Forecast * response.data[0].biayakemas) +"<br>"+
                    "________________________________ + <br>"+
                    "Total              : " + numberWithCommas(((kebutuhankain * response.data[0].hargakain) + (dataAll[0].Forecast * response.data[0].hargakaret) + (dataAll[0].Forecast * response.data[0].ongkosjait) + (dataAll[0].Forecast * response.data[0].ongkospotong) + (dataAll[0].Forecast * response.data[0].biayakemas))) + "<BR>"+
                    "Harga per pcs      : " + numberWithCommas(Math.round(((kebutuhankain * response.data[0].hargakain) + (dataAll[0].Forecast * response.data[0].hargakaret) + (dataAll[0].Forecast * response.data[0].ongkosjait) + (dataAll[0].Forecast * response.data[0].ongkospotong) + (dataAll[0].Forecast * response.data[0].biayakemas)) / dataAll[0].Forecast))+
                "</pre>"
            );
          }
        });
    }
    function ShowChart(forcastX) {
        console.log(forcastX);
        var value1 = [];
        var label1 = [];
        var value2 = [];
        var label2 = [];

        var tipe = $('#filter').val();
        var startmont = $('#startmont').val();
        var endmont = $('#endmont').val();

        var period = "";
        $.ajax({
			async:false,
          type: "post",
          url: "<?=base_url()?>Apps/initialData2nd",
          data: {startmont:startmont,endmont:endmont},
          dataType: "json",
          success: function (response) {
            if (response.success) {
                var nextperiod = "";
                var bulan = 1;
                $.each(response.data,function (k,v) {
                    label1.push(v.Periode);
                    period = v.Periode;
                    switch(tipe){
                        case "1" :
                            value1.push(v.KARTUN);
                        break;
                        case "2" :
                            value1.push(v.ABSTRAK);
                        break;
                        case "3" :
                            value1.push(v.TROPIKAL);
                        break;
                    }
                    bulan += 1;
                });
                alpabeth = "";
                var tahun = '';
                if (bulan > 12) {
                    bulan = 1;
                    tahun = '2020'
                }
                else{
                    tahun = period.substring(4, 8);
                }
                switch(bulan){
                    case 1 :
                        alpabeth = "Jan"
                    break;
                    case 2 :
                        alpabeth = "Feb"
                    break;
                    case 3 :
                        alpabeth = "Mar"
                    break;
                    case 4 :
                        alpabeth = "Apr"
                    break;
                    case 5 :
                        alpabeth = "Mei"
                    break;
                    case 6 :
                        alpabeth = "Jun"
                    break;
                    case 7 :
                        alpabeth = "Jul"
                    break;
                    case 8 :
                        alpabeth = "Ags"
                    break;
                    case 9 :
                        alpabeth = "Sep"
                    break;
                    case 10 :
                        alpabeth = "Okt"
                    break;
                    case 11 :
                        alpabeth = "Nov"
                    break;
                    case 12 :
                        alpabeth = "Des"
                    break;
                }
                label1.push(alpabeth+"-"+tahun);
                value1.push(0);
            }
            else{
                $('#load_data').html(
                    "<p>"+response.message+"</p>"
                );
            }
          }
        });

        $.each(forcastX,function (k,v) {
            value2.push(v.data);
        });
        var ctx = document.getElementById("myChart");
        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;

        console.log(label1);
        var dataFirst = {
            label: "Penjualan (pcs)",
            data: value1,
            lineTension: 0,
            fill: false,
            borderColor: 'red'
          };
        var dataSecond = {
            label: "forcast (pcs)",
            data: value2,
            lineTension: 0,
            fill: false,
            borderColor: 'blue'
          };
        var speedData = {
          labels: label1,
          datasets: [dataFirst,dataSecond]
        };

        var chartOptions = {
          legend: {
            display: true,
            position: 'bottom',
            labels: {
              boxWidth: 80,
              fontColor: 'black'
            }
          }
        };
        var lineChart = new Chart(ctx, {
          type: 'line',
          data: speedData,
          options: chartOptions
        });
    }
    function bindGrid(data) {

        $("#gridContainer").dxDataGrid({
            allowColumnResizing: true,
            dataSource: data,
            keyExpr: "Periode",
            showBorders: true,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            export: {
                    enabled: true,
                    fileName: "Daftar Transaksi"
            },
            columns: [
                {
                    dataField: "Periode",
                    caption: "Periode",
                    allowEditing:false
                },
                {
                    dataField: "KARTUN",
                    caption: "KARTUN",
                    allowEditing:false
                },
                {
                    dataField: "ABSTRAK",
                    caption: "ABSTRAK",
                    allowEditing:false
                },
                {
                    dataField: "TROPIKAL",
                    caption: "TROPIKAL",
                    allowEditing:false
                }
            ],
            onEditingStart: function(e) {
                GetData(e.data.id);
            },
            onInitNewRow: function(e) {
                // logEvent("InitNewRow");
                $('#modal_').modal('show');
            },
            onRowInserting: function(e) {
                // logEvent("RowInserting");
            },
            onRowInserted: function(e) {
                // logEvent("RowInserted");
                // alert('');
                // console.log(e.data.onhand);
                // var index = e.row.rowIndex;
            },
            onRowUpdating: function(e) {
                // logEvent("RowUpdating");
                
            },
            onRowUpdated: function(e) {
                // logEvent(e);
            },
            onRowRemoving: function(e) {
                id = e.data.id;
                Swal.fire({
                  title: 'Apakah anda yakin?',
                  text: "anda akan menghapus data di baris ini !",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {
                    var table = 'app_setting';
                    var field = 'id';
                    var value = id;

                    $.ajax({
						async:false,
                        type    :'post',
                        url     : '<?=base_url()?>Apps/remove',
                        data    : {table:table,field:field,value:value},
                        dataType: 'json',
                        success : function (response) {
                          if(response.success == true){
                            Swal.fire(
                              'Deleted!',
                              'Your file has been deleted.',
                              'success'
                            ).then((result)=>{
                              location.reload();
                            });
                          }
                          else{
                            Swal.fire({
                              type: 'error',
                              title: 'Woops...',
                              text: response.message,
                              // footer: '<a href>Why do I have this issue?</a>'
                            }).then((result)=>{
                                location.reload();
                            });
                          }
                        }
                      });
                    
                  }
                  else{
                    location.reload();
                  }
                })
            },
            onRowRemoved: function(e) {
                // console.log(e);
            },
            onEditorPrepared: function (e) {
                // console.log(e);
            }
        });

        // add dx-toolbar-after
        // $('.dx-toolbar-after').append('Tambah Alat untuk di pinjam ');
    }
    function bindGridforcast(data) {

        $("#gridContainer_Forecast").dxDataGrid({
            allowColumnResizing: true,
            dataSource: data,
            keyExpr: "Periode",
            showBorders: true,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            export: {
                    enabled: true,
                    fileName: "Daftar Forecast"
                },
            columns: [
                {
                    dataField: "Periode",
                    caption: "Periode",
                    allowEditing:false
                },
                {
                    dataField: "Jenis",
                    caption: "Jenis",
                    allowEditing:false
                },
                {
                    dataField: "F01",
                    caption: "Alpa 0.1",
                    allowEditing:false,
                },
                {
                    dataField: "F02",
                    caption: "Alpa 0.2",
                    allowEditing:false
                },
                {
                    dataField: "F03",
                    caption: "Alpa 0.3",
                    allowEditing:false
                },
                {
                    dataField: "F04",
                    caption: "Alpa 0.4",
                    allowEditing:false
                },
                {
                    dataField: "F05",
                    caption: "Alpa 0.5",
                    allowEditing:false
                },
                {
                    dataField: "F06",
                    caption: "Alpa 0.6",
                    allowEditing:false
                },
                {
                    dataField: "F07",
                    caption: "Alpa 0.7",
                    allowEditing:false
                },
                {
                    dataField: "F08",
                    caption: "Alpa 0.8",
                    allowEditing:false
                },
                {
                    dataField: "F09",
                    caption: "Alpa 0.9",
                    allowEditing:false
                },
            ],
            summary:{
                totalItems:[
                    {
                        column : "F01",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F02",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F03",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F04",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F05",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F06",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F07",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F08",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    {
                        column : "F09",
                        summaryType: "sum",
                        valueFormat: 'fixedPoint',
                        precision: '1',
                        alignment: 'right'
                    },
                    // displayFormat: 'Variance: {0}'
                ]
            },
            onEditingStart: function(e) {
                GetData(e.data.id);
            },
            onInitNewRow: function(e) {
                // logEvent("InitNewRow");
                $('#modal_').modal('show');
            },
            onRowInserting: function(e) {
                // logEvent("RowInserting");
            },
            onRowInserted: function(e) {
                // logEvent("RowInserted");
                // alert('');
                // console.log(e.data.onhand);
                // var index = e.row.rowIndex;
            },
            onRowUpdating: function(e) {
                // logEvent("RowUpdating");
                
            },
            onRowUpdated: function(e) {
                // logEvent(e);
            },
            onRowRemoving: function(e) {
                id = e.data.id;
                Swal.fire({
                  title: 'Apakah anda yakin?',
                  text: "anda akan menghapus data di baris ini !",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {
                    var table = 'app_setting';
                    var field = 'id';
                    var value = id;

                    $.ajax({
						async:false,
                        type    :'post',
                        url     : '<?=base_url()?>Apps/remove',
                        data    : {table:table,field:field,value:value},
                        dataType: 'json',
                        success : function (response) {
                          if(response.success == true){
                            Swal.fire(
                              'Deleted!',
                              'Your file has been deleted.',
                              'success'
                            ).then((result)=>{
                              location.reload();
                            });
                          }
                          else{
                            Swal.fire({
                              type: 'error',
                              title: 'Woops...',
                              text: response.message,
                              // footer: '<a href>Why do I have this issue?</a>'
                            }).then((result)=>{
                                location.reload();
                            });
                          }
                        }
                      });
                    
                  }
                  else{
                    location.reload();
                  }
                })
            },
            onRowRemoved: function(e) {
                // console.log(e);
            },
            onEditorPrepared: function (e) {
                // console.log(e);
            }
        });

        // add dx-toolbar-after
        // $('.dx-toolbar-after').append('Tambah Alat untuk di pinjam ');
    }
    function bindGridMAE(data) {

        $("#gridContainer_Mae").dxDataGrid({
            allowColumnResizing: true,
            dataSource: data,
            keyExpr: "Jenis",
            showBorders: true,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            export: {
                    enabled: true,
                    fileName: "Daftar MAE"
                },
            columns: [
                {
                    dataField: "Jenis",
                    caption: "Jenis",
                    allowEditing:false
                },
                {
                    dataField: "F01",
                    caption: "Alpa 0.1",
                    allowEditing:false,
                },
                {
                    dataField: "F02",
                    caption: "Alpa 0.2",
                    allowEditing:false
                },
                {
                    dataField: "F03",
                    caption: "Alpa 0.3",
                    allowEditing:false
                },
                {
                    dataField: "F04",
                    caption: "Alpa 0.4",
                    allowEditing:false
                },
                {
                    dataField: "F05",
                    caption: "Alpa 0.5",
                    allowEditing:false
                },
                {
                    dataField: "F06",
                    caption: "Alpa 0.6",
                    allowEditing:false
                },
                {
                    dataField: "F07",
                    caption: "Alpa 0.7",
                    allowEditing:false
                },
                {
                    dataField: "F08",
                    caption: "Alpa 0.8",
                    allowEditing:false
                },
                {
                    dataField: "F09",
                    caption: "Alpa 0.9",
                    allowEditing:false
                },
            ],
            onEditingStart: function(e) {
                GetData(e.data.id);
            },
            onInitNewRow: function(e) {
                // logEvent("InitNewRow");
                $('#modal_').modal('show');
            },
            onRowInserting: function(e) {
                // logEvent("RowInserting");
            },
            onRowInserted: function(e) {
                // logEvent("RowInserted");
                // alert('');
                // console.log(e.data.onhand);
                // var index = e.row.rowIndex;
            },
            onRowUpdating: function(e) {
                // logEvent("RowUpdating");
                
            },
            onRowUpdated: function(e) {
                // logEvent(e);
            },
            onRowRemoving: function(e) {
                
            },
            onRowRemoved: function(e) {
                // console.log(e);
            },
            onEditorPrepared: function (e) {
                // console.log(e);
            },
            rowPrepared : function (rowElement, rowInfo) {
                console.log(rowInfo);
            }
        });

        // add dx-toolbar-after
        // $('.dx-toolbar-after').append('Tambah Alat untuk di pinjam ');
    }
    function bindGridHasilForecast(data) {

        $("#gridContainer_HasilForecast").dxDataGrid({
            allowColumnResizing: true,
            dataSource: data,
            keyExpr: "Periode",
            showBorders: true,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            export: {
                    enabled: true,
                    fileName: "Hasil Forecast"
                },
            columns: [
                {
                    dataField: "Periode",
                    caption: "Periode",
                    allowEditing:false
                },
                {
                    dataField: "Jenis",
                    caption: "Jenis",
                    allowEditing:false
                },
                {
                    dataField: "Forecast",
                    caption: "Forecast",
                    allowEditing:false,
                }
            ],
            onEditingStart: function(e) {
                GetData(e.data.id);
            },
            onInitNewRow: function(e) {
                // logEvent("InitNewRow");
                $('#modal_').modal('show');
            },
            onRowInserting: function(e) {
                // logEvent("RowInserting");
            },
            onRowInserted: function(e) {
                // logEvent("RowInserted");
                // alert('');
                // console.log(e.data.onhand);
                // var index = e.row.rowIndex;
            },
            onRowUpdating: function(e) {
                // logEvent("RowUpdating");
                
            },
            onRowUpdated: function(e) {
                // logEvent(e);
            },
            onRowRemoving: function(e) {
                
            },
            onRowRemoved: function(e) {
                // console.log(e);
            },
            onEditorPrepared: function (e) {
                // console.log(e);
            },
            rowPrepared : function (rowElement, rowInfo) {
                console.log(rowInfo);
            }
        });

        // add dx-toolbar-after
        // $('.dx-toolbar-after').append('Tambah Alat untuk di pinjam ');
    }
    function round(value, decimals) {
      return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
    }
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function generateUUID() { // Public Domain/MIT
        var d = new Date().getTime();//Timestamp
        var d2 = (performance && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16;//random number between 0 and 16
            if(d > 0){//Use timestamp until depleted
                r = (d + r)%16 | 0;
                d = Math.floor(d/16);
            } else {//Use microseconds since page-load if supported
                r = (d2 + r)%16 | 0;
                d2 = Math.floor(d2/16);
            }
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }
</script>