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
    <p> &copy; 2020 AisSystem. All Rights Reserved | Published by <a href="http://aistrick.com.com/" target="_blank">AIS System</a> </p>      
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
                    bindGridforcast(response.dataForcast);
                    ShowChart(response.dataForcast);
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
		
    });
    
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
            keyExpr: "Indicator",
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
                    dataField: "Indicator",
                    caption: "Indicator",
                    allowEditing:false
                },
                {
                    dataField: "Aktual",
                    caption: "Aktual",
                    allowEditing:false
                },
                {
                    dataField: "Forcast",
                    caption: "Forcast",
                    allowEditing:false,
                },
                {
                    dataField: "Error",
                    caption: "Error",
                    allowEditing:false
                },
                {
                    dataField: "RSFE",
                    caption: "RSFE",
                    allowEditing:false
                },
                {
                    dataField: "AbsError",
                    caption: "AbsError",
                    allowEditing:false
                },
                {
                    dataField: "AbsTotal",
                    caption: "AbsTotal",
                    allowEditing:false
                },
                {
                    dataField: "MAD",
                    caption: "MAD",
                    allowEditing:false
                },
                {
                    dataField: "TrackingSignal",
                    caption: "TrackingSignal",
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
            }
        });

        // add dx-toolbar-after
        // $('.dx-toolbar-after').append('Tambah Alat untuk di pinjam ');
    }
    
    function ShowChart(forcastX) {
        var value1 = [];
        var label1 = [];
        var value2 = [];
        var label2 = [];

        var row = 1;
        $.each(forcastX,function (k,v) {
            if (row != forcastX.length) {
                label1.push(v.Indicator);
                value1.push(v.TrackingSignal);
            }

            row +=1;
        });


        var ctx = document.getElementById("myChart");
        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;

        var dataFirst = {
            label: "Tracking Signal",
            data: value1,
            lineTension: 0,
            fill: false,
            borderColor: 'red'
          };
        var speedData = {
          labels: label1,
          datasets: [dataFirst]
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