<!-- Batas Tampilan -->
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
                    <input type="month" name="TglAwal" id="TglAwal">
                    <label>S/D</label>
                    <input type="month" name="TglAkhir" id="TglAkhir">
                    <button class="span12 btn btn-danger" name="getdata" id="getdata">Get Data</button>
                </div>
                <div class="dx-viewport demo-container">
                    <div id="data-grid-demo">
                        <div id="gridContainer">
                        </div>
                    </div>
                </div>
                <br>
                Total Transaksi : <div id="trxTotal"> </div> <br>
                Count Transaksi : <div id="trxCount"> </div><br>
                Alpha : <div id="trxAlpha"></div> <br>
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
    <p> &copy; 2020 TPA Putri Cempo. All Rights Reserved | Published by <a href="http://aistrick.com.com/" target="_blank">TPA Putri Cempo</a> </p>      
</div>
</div>
</div>
<div class="clearfix"> </div>
<!-- Batas Tampilan -->

<script type="text/javascript">
    var datauji;
    var forcast;
    var usedMae = 0;
    $(function () {
        // var dataForcast;
        var countofloop = 0;
		
		//Generate Data Transaksi
        $('#getdata').click(function () {
            $('#getdata').text('Tunggu sebentar ...');
            $('#getdata').attr('disabled',true);

            var TglAwal = $('#TglAwal').val()+'-01';
            var TglAkhir = $('#TglAkhir').val()+'-01';
            // date2.setMonth(date2.getMonth()+3);
            var label = [];
            var value = [];
            $.ajax({
				async:false,
              type: "post",
              url: "<?=base_url()?>proses/GetInitalData",
              data: {TglAwal:TglAwal,TglAkhir:TglAkhir},
              dataType: "json",
              success: function (response) {
                if (response.success) {
                    datauji = response.data;
                    bindGrid(response.data);
                    $.each(response.dataOpt,function (k,v) {
                        console.log(v.Total);
                        $('#trxTotal').text(v.Total);
                        $('#trxCount').text(v.Count);
                        $('#trxAlpha').text(v.Alpha);
                      });

                    // $('#trxTotal').val(response.dataOpt[0].Total);
                    // $('#trxCount').val(response.dataOpt[0].Count);
                    // $('#trxAlpha').val(response.dataOpt[0].Alpha);
                }
                else{
                    $('#load_data').html(
                        "<p>"+response.message+"</p>"
                    );
                }
              }
            });
            $('#getdata').text('Get Data');
            $('#getdata').attr('disabled',false);
        });
		//Generate Data Transaksi 
		
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
                        jenis = "Disperindag";
                        jumlah = v.Disperindag;
                        if (bulan == 1) { //periode == "Jan-2019"
                            januari = v.Disperindag;
                            H01 = 0.0;
                            H02 = 0.0;
                            H03 = 0.0;
                            H04 = 0.0;
                            H05 = 0.0;
                            H06 = 0.0;
                            H07 = 0.0;
                            H08 = 0.0;
                            H09 = 0.0;
                            LastRes = v.Disperindag;
                            LastResH01 = v.Disperindag;
                            LastResH02 = v.Disperindag;
                            LastResH03 = v.Disperindag;
                            LastResH04 = v.Disperindag;
                            LastResH05 = v.Disperindag;
                            LastResH06 = v.Disperindag;
                            LastResH07 = v.Disperindag;
                            LastResH08 = v.Disperindag;
                            LastResH09 = v.Disperindag;
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
                            februari = v.Disperindag;
                            LastRow = v.Disperindag;
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

                            LastRow = v.Disperindag;
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
                        jenis = "Kelurahan";
                        jumlah = v.Kelurahan;
                        if (bulan == 1) { //periode == "Jan-2019"
                            januari = v.Kelurahan;
                            H01 = 0.0;
                            H02 = 0.0;
                            H03 = 0.0;
                            H04 = 0.0;
                            H05 = 0.0;
                            H06 = 0.0;
                            H07 = 0.0;
                            H08 = 0.0;
                            H09 = 0.0;
                            LastRes = v.Kelurahan;
                            LastResH01 = v.Kelurahan;
                            LastResH02 = v.Kelurahan;
                            LastResH03 = v.Kelurahan;
                            LastResH04 = v.Kelurahan;
                            LastResH05 = v.Kelurahan;
                            LastResH06 = v.Kelurahan;
                            LastResH07 = v.Kelurahan;
                            LastResH08 = v.Kelurahan;
                            LastResH09 = v.Kelurahan;
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
                            februari = v.Kelurahan;
                            LastRow = v.Kelurahan;
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

                            LastRow = v.Kelurahan;
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
                        jenis = "Umum";
                        jumlah = v.Umum;
                        if (bulan == 1) { //periode == "Jan-2019"
                            januari = v.Umum;
                            H01 = 0.0;
                            H02 = 0.0;
                            H03 = 0.0;
                            H04 = 0.0;
                            H05 = 0.0;
                            H06 = 0.0;
                            H07 = 0.0;
                            H08 = 0.0;
                            H09 = 0.0;
                            LastRes = v.Umum;
                            LastResH01 = v.Umum;
                            LastResH02 = v.Umum;
                            LastResH03 = v.Umum;
                            LastResH04 = v.Umum;
                            LastResH05 = v.Umum;
                            LastResH06 = v.Umum;
                            LastResH07 = v.Umum;
                            LastResH08 = v.Umum;
                            LastResH09 = v.Umum;
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
                            februari = v.Umum;
                            LastRow = v.Umum;
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

                            LastRow = v.Umum;
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
                Tahun = "2021";
            }
            else{
                Tahun = periode.substring(4,8);
            }
            // for (var i = 1; i < bulan; i++) {
            //     Things[i]
            // }
            var longdata = new Date($('#endmont').val());
            // var x = new Date(longdata.setMonth(longdata.getMonth()+1));
            xbulan = parseInt($('#endmont').val().substring(5,$('#endmont').val().length))+1;
            xtaun = longdata.getFullYear();
            // console.log(""+b.toString()+"-"+t.toString()+"");
            if (xbulan == 13) {
                xtaun = xtaun + 1;
            }
            console.log(xbulan);
            switch(xbulan){
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
                case 13 :
                    alpabeth = "Jan"
                break;
            }

            AppendData(bulanx,alpabeth+'-'+xtaun.toString(),jenis,jumlah,H01,H02,H03,H04,H05,H06,H07,H08,H09);
            ShowDataForecast(jenis);
        });
    });
    function AddMonth(strDate,intNum)
    {
        sdate =  new Date(strDate);
        sdate.setMonth(sdate.getMonth()+intNum);
        return sdate.getMonth()+1 + "-" + sdate.getDate() + "-" + sdate.getFullYear();
    }
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
                jns = "Disperindag"
            break;
            case "2" : 
                jns = "Kelurahan"
            break;
            case "3" : 
                jns = "Umum"
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

        usedMae = MaeUsed;
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
                tipe = "Disperindag";
            break;
            case "2" :
                tipe = "Kelurahan";
            break;
            case "3" :
                tipe = "Umum"
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
    
    function bindGrid(data) {

        $("#gridContainer").dxDataGrid({
            allowColumnResizing: true,
            dataSource: data,
            keyExpr: "BulanIndex",
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
                    dataField: "BulanIndex",
                    caption: "Kode",
                    allowEditing:false
                },
                {
                    dataField: "Bulan",
                    caption: "Bulan",
                    allowEditing:false
                },
                {
                    dataField: "Total",
                    caption: "Total",
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