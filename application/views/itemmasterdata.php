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
		<a href="index.html">Home</a>
		<i class="fa fa-angle-right"></i>
		<span>Item Master Data</span>
		</h2>
    </div>
	<!--//banner-->
	 <!--faq-->
	<div class="blank">
		<div class="grid-form1">
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
    <p> &copy; 2021 AIS System. All Rights Reserved | Published by <a href="http://aistrick.com.com/" target="_blank">AIS System</a> </p>   
</div>
</div>
</div>
<div class="clearfix"> </div>

<div class="bs-example2 bs-example-padded-bottom">
  <div class="modal fade" id="modal_" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h2 class="modal-title">Tambah Item Master</h2>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" enctype='application/json' id="post_">
                <div class="control-group">
                    <label class="control-label">Kode Item</label>
                    <div class="controls">
                        <input type="text" name="kodeitem" id="kodeitem" required="" placeholder="kodeitem">
                        <input type="hidden" name="formtype" id="formtype" value="add">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">namaitem</label>
                    <div class="controls">
                        <input type="text" name="namaitem" id="namaitem" required="" placeholder="namaitem">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Harga</label>
                    <div class="controls">
                        <input type="number" name="Harga" id="Harga" required="" placeholder="Harga">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Gambar</label>
                    <div class="controls">
                        <!-- <input type="number" name="Harga" id="Harga" required="" placeholder="Harga"> -->
                        <input type="file" name="file">
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
            var table = 'thppsetting';

            $.ajax({
              type: "post",
              url: "<?=base_url()?>ItemMaster/Read",
              data: {kodeitem:''},
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
                url     : '<?=base_url()?>ItemMaster/appendTransaksi',
                // data    : me.serialize(),
                data:new FormData(this),
                processData:false,
                contentType:false,
                cache:false,
                async:false,
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
        $('.close').click(function() {
            location.reload();
        });
        function GetData(id) {
            var where_field = 'id';
            var where_value = id;
            var table = 'thppsetting';
            $.ajax({
              type: "post",
              url: "<?=base_url()?>ItemMaster/Read",
              data: {kodeitem:''},
              dataType: "json",
              success: function (response) {
                $.each(response.data,function (k,v) {
                    $('#kodeitem').val(v.kodeitem);
                    $('#namaitem').val(v.namaitem);
                    $('#Harga').val(v.Harga);
                    $('#formtype').val("edit");

                    $('#modal_').modal('show');
                  });
              }
            });
        }
        function bindGrid(data) {
          var adding = true;
            if (data.length > 3) {
              adding=false;
            }
            else{
              adding = true;
            }
            $("#gridContainer").dxDataGrid({
                allowColumnResizing: true,
                dataSource: data,
                keyExpr: "kodeitem",
                showBorders: true,
                allowColumnReordering: true,
                allowColumnResizing: true,
                columnAutoWidth: true,
                showBorders: true,
                paging: {
                    enabled: false
                },
                editing: {
                    mode: "row",
                    allowUpdating: true,
                    allowDeleting: true,
                    allowAdding:adding,
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
                    fileName: "Daftar Pelayan"
                },
                columns: [
                    {
                        dataField: "kodeitem",
                        caption: "Kode Item",
                        allowEditing:false
                    },
                    {
                        dataField: "namaitem",
                        caption: "Nama Item",
                        allowEditing:false
                    },
                    {
                        dataField: "Harga",
                        caption: "Harga",
                        allowEditing:false
                    },
                ],
                onEditingStart: function(e) {
                    GetData(e.data.kodeitem);
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
                    id = e.data.kodeitem;
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
                        var value = id;

                        $.ajax({
                            type    :'post',
                            url     : '<?=base_url()?>ItemMaster/remove',
                            data    : {'kodeitem':id},
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
</script>
