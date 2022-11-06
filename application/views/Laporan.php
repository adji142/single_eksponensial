<?php
    require_once(APPPATH."views/parts/header.php");
    require_once(APPPATH."views/parts/sidebar.php");
    require_once(APPPATH."views/parts/footer.php");
    $active = 'dashboard';
?>
<div id="page-wrapper" class="gray-bg dashbard-1">
   <div class="content-main">

	<!--banner-->	
    <div class="banner">
    	<h2>
		<a href="<?php echo base_url(); ?>">Home</a>
		<i class="fa fa-angle-right"></i>
		<span>Laporan Hasil Prediksi</span>
		</h2>
    </div>
	<!--//banner-->
	 <!--faq-->
	<div class="blank">
		<div class="blank-page">
        	 <div class="form-group">
            <label>Tgl Awal</label>
            <input type="date" name="tglawal" id="tglawal">
            <label>s/d</label>
            <label>Tgl Akhir</label>
            <input type="date" name="tglakhir" id="tglakhir">
            <label>Jenis</label>
                <select class="span3 m-wrap" name="filtertipe" id="filtertipe">
                    <option value="Disperindag">Disperindag</option>
                    <option value="Kelurahan">Kelurahan</option>
                    <option value="Umum">Umum</option>
                </select>
                <button class="span3 m-wrap" name="searchbytipe" id="searchbytipe">Cari</button>
            </div>
            <div class="dx-viewport demo-container">
                <div id="data-grid-demo">
                    <div id="gridContainer">
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--//faq-->
	<!---->
<div class="copy">
    <p> &copy; 2020 TPA Putri Cempo. All Rights Reserved | Published by <a href="http://aistrick.com.com/" target="_blank">TPA Putri Cempo</a> </p>     
</div>
</div>
</div>
<div class="clearfix"> </div>

<script type="text/javascript">
    $(function () {
        $('#searchbytipe').click(function () {
            var where_field = 'Tipe';
            var where_value = $('#filtertipe').val();
            var table = 'ttransaksi';

            var Jenis = $('#filtertipe').val();
            var tglawal = $('#tglawal').val();
            var tglakhir = $('#tglakhir').val();

            $.ajax({
              type: "post",
              url: "<?=base_url()?>Apps/LaporanHasilForecast",
              data: {Jenis:Jenis,tglawal:tglawal,tglakhir:tglakhir},
              dataType: "json",
              success: function (response) {
                bindGrid(response.data);
              }
            });        });
        function bindGrid(data) {

            $("#gridContainer").dxDataGrid({
                allowColumnResizing: true,
                dataSource: data,
                keyExpr: "NoProses",
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
                    // allowAdding:true,
                    // allowUpdating: true,
                    // allowDeleting: true,
                    texts: {
                        confirmDeleteMessage: ''  
                    }
                },
                searchPanel: {
                    visible: true,
                    width: 240,
                    placeholder: "Search..."
                },
                export: {
                    enabled: true,
                    fileName: "Daftar Peramalan"
                },
                columns: [
                    {
                        dataField: "NoProses",
                        caption: "NoProses",
                        allowEditing:false,
                        visible : false
                    },
                    {
                        dataField: "TglProses",
                        caption: "Tanggal Proses",
                        allowEditing:false
                    },
                    {
                        dataField: "Periode",
                        caption: "Periode Forecast",
                        allowEditing:false
                    },
                    {
                        dataField: "Jenis",
                        caption: "Jenis",
                        allowEditing:false
                    },
                    {
                        dataField: "Forecast",
                        caption: "Hasil Forecast",
                        allowEditing:false
                    },
                    {
                        dataField: "MaE",
                        caption: "MaE Terkecil",
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
                        var table = 'ttransaksi';
                        var field = 'id';
                        var value = id;

                        $.ajax({
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
    });
    function SearchData() {
        var param = $('#nama').val();
        $('#Modalpelanggan').modal('toggle');
        $.ajax({
          type: "post",
          url: "<?=base_url()?>Lookup/LookupPelanggan",
          data: {param:param},
          dataType: "json",
          success: function (response) {
            if (response.success == true) {
                if (response.count == 1) {
                    $.each(response.data,function (k,v) {
                        $('#nama').val(v.NmCustomer);
                        $('#kdpelanggan').val(v.id);
                      });
                }
                else{
                    var html = '';
                    var i;
                    for (i = 0; i < response.data.length; i++) {
                      html += '<tr>' +
                              '<td id = "lookup_id">' + response.data[i].id+'</td>' +
                              '<td id = "lookup_fullname">' + response.data[i].NmCustomer +'</td></tr>';
                    }
                    $('#load_data').html(html);
                    $('#Modalpelanggan').modal('show');
                    $('#Modalpelanggan').modal('toggle');
                    $('#Modalpelanggan').modal('show');
                }
            }
          }
        });
    }
</script>