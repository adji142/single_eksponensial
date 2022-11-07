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
		<span>Transaksi </span>
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
    <p> &copy; 2020 AisSystem. All Rights Reserved | Published by <a href="http://aistrick.com.com/" target="_blank">AisSystem</a> </p>     
</div>
</div>
</div>
<div class="clearfix"> </div>

    <!-- modal -->
<div class="bs-example2 bs-example-padded-bottom">
  <div class="modal fade" id="modal_" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h2 class="modal-title">Tambah Transaksi</h2>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" enctype='application/json' id="post_">
            <div class="control-group">
              <label class="control-label">Tanggal Transaksi</label>
              <div class="controls">
                <input type="date" name="TglTransaksi" id="TglTransaksi" required="">
                <input type="hidden" class="span3 m-wrap" id="id" name="id">
                  <input type="hidden" class="span3 m-wrap" id="formtype" name="formtype" value="add">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Item</label>
              <div class="controls">
                <select id="KodeItem" name="KodeItem">
                    <?php 
                        $data = $this->ModelsExecuteMaster->GetData('titemmasterdata')->result();

                        foreach ($data as $key) {
                            var_dump($key);
                            echo "<option value='".$key->kodeitem."'>".$key->namaitem."</option>";
                        }
                    ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Jumlah</label>
              <div class="controls">
                <input type="number" name="Qty" id="Qty" required="">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Harga</label>
              <div class="controls">
                <input type="number" name="Harga" id="Harga" required="">
              </div>
            </div>
                <button class="btn btn-primary" id="btn_Save">Save</button>
          </form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
</div>
<script type="text/javascript">
    $(function () {
        $(document).ready(function () {
            var where_field = '';
            var where_value = '';
            var table = 'ttransaksi';

            $.ajax({
              type: "post",
              url: "<?=base_url()?>Transaksi/Read",
              // data: {id,''},
              dataType: "json",
              success: function (response) {
                bindGrid(response.data);
              }
            });
        });
        $('#post_').submit(function (e) {
            $('#btn_Save').text('Tunggu Sebentar.....');
            $('#btn_Save').attr('disabled',true);

            e.preventDefault();
            var me = $(this);

            $.ajax({
                type    :'post',
                url     : '<?=base_url()?>Transaksi/appendTransaksi',
                data    : me.serialize(),
                dataType: 'json',
                success : function (response) {
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
        });
        $('#searchbytipe').click(function () {
            var where_field = 'Tipe';
            var where_value = $('#filtertipe').val();
            var table = 'ttransaksi';

            var tipe = $('#filtertipe').val();
            var tglawal = $('#tglawal').val();
            var tglakhir = $('#tglakhir').val();

            $.ajax({
              type: "post",
              url: "<?=base_url()?>Transaksi/ReadByTgl",
              data: {tglawal:tglawal,tglakhir:tglakhir},
              dataType: "json",
              success: function (response) {
                bindGrid(response.data);
              }
            });        });
        $('.close').click(function() {
            location.reload();
        });
        $('#pelanggan_list').on('click','tr',function () {
            var return_IDData = 1;
            var lookup_id = $(this).find("#lookup_id").text();
            var lookup_fullname = $(this).find("#lookup_fullname").text();
            
            $('#nama').val(lookup_fullname);
            $('#kdpelanggan').val(lookup_id);

            $('#Modalpelanggan').modal('toggle');
        });
        $('#Lookupsearch').click(function () {
            SearchData(false);
        });
        $('#rst_btn').click(function () {
            $('#xsrc').val('');
            SearchData();
        });
        // function
        function ClearForm() {
            $('#kd_pos').attr('disabled',true);
            $('#nm_pos').attr('disabled',true);
            $('#kel').attr('disabled',true);
        }
        function GetData(id) {
            // console.log(id);
            var where_field = 'id';
            var where_value = id;
            var table = 'ttransaksi';
            $.ajax({
              type: "post",
              url: "<?=base_url()?>Apps/FindData",
              data: {where_field:where_field,where_value:where_value,table:table},
              dataType: "json",
              success: function (response) {
                $.each(response.data,function (k,v) {
                    console.log(v.KelompokUsaha);
                    $('#tgltransaksi').val(v.Tanggal);
                    $('#NoRef').val(v.NoRef);
                    $('#Merk').val(v.Merk);
                    $('#tipe').val(v.Tipe).change();
                    $('#jml').val(v.Qty);

                    $('#id').val(v.id);
                    $('#formtype').val("edit");

                    $('#modal_').modal('show');
                  });
              }
            });
        }
        function bindGrid(data) {

            $("#gridContainer").dxDataGrid({
                allowColumnResizing: true,
                dataSource: data,
                keyExpr: "id",
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
                    allowAdding:true,
                    allowUpdating: true,
                    allowDeleting: true,
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
                    fileName: "Daftar Transaksi"
                },
                columns: [
                    {
                        dataField: "id",
                        caption: "id",
                        allowEditing:false,
                        visible : false
                    },
                    {
                        dataField: "TglTransaksi",
                        caption: "Tanggal Transaksi",
                        allowEditing:false
                    },
                    // {
                    //     dataField: "NoRef",
                    //     caption: "No. Ref",
                    //     allowEditing:false
                    // },
                    // {
                    //     dataField: "Merk",
                    //     caption: "Merk",
                    //     allowEditing:false
                    // },
                    {
                        dataField: "KodeItem",
                        caption: "KodeItem",
                        allowEditing:false
                    },
                    {
                        dataField: "Qty",
                        caption: "Jumlah",
                        allowEditing:false
                    },
                    {
                        dataField: "Harga",
                        caption: "Harga",
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