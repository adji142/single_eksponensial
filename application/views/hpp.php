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
		<span>HPP(Harga Pokok Produksi) Setting</span>
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
    <p> &copy; 2020 DownStore. All Rights Reserved | Published by <a href="http://aistrick.com.com/" target="_blank">DownStore</a> </p>   
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
          <h2 class="modal-title">Tambah HPP</h2>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" enctype='application/json' id="post_">
                <div class="control-group">
                    <label class="control-label">Harga Kain / Meter</label>
                    <div class="controls">
                        <input type="number" name="hargakain" id="hargakain" required="" placeholder="Harga Kain / Meter">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="formtype" id="formtype" value="add">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Harga Karet / Pcs Celana</label>
                    <div class="controls">
                        <input type="number" name="hargakaret" id="hargakaret" required="" placeholder="Harga Karet / Pcs Celana">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Ongkos Jait / pcs</label>
                    <div class="controls">
                        <input type="number" name="ongkosjait" id="ongkosjait" required="" placeholder="Ongkos Jait / pcs">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Ongkos Potong / pcs</label>
                    <div class="controls">
                        <input type="number" name="ongkospotong" id="ongkospotong" required="" placeholder="Ongkos Potong / pcs">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Biaya Kemas / pcs</label>
                    <div class="controls">
                        <input type="number" name="biayakemas" id="biayakemas" required="" placeholder="Biaya Kemas / pcs">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Pemakaian Kain / pcs (M)</label>
                    <div class="controls">
                        <input type="text" name="pemakaiankainperpcs" id="pemakaiankainperpcs" required="" placeholder="Pemakaian Kain / pcs (M)">
                    </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Tipe</label>
                  <div class="controls">
                    <select id="Jenis" name="Jenis">
                      <option value="KARTUN">KARTUN</option>
                      <option value="ABSTRAK">ABSTRAK</option>
                      <option value="TROPIKAL">TROPIKAL</option>
                    </select>
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
              url: "<?=base_url()?>Apps/FindData",
              data: {where_field:where_field,where_value:where_value,table:table},
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
                url     : '<?=base_url()?>Apps/HPPSetting',
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
        $('.close').click(function() {
            location.reload();
        });
        function GetData(id) {
            var where_field = 'id';
            var where_value = id;
            var table = 'thppsetting';
            $.ajax({
              type: "post",
              url: "<?=base_url()?>Apps/FindData",
              data: {where_field:where_field,where_value:where_value,table:table},
              dataType: "json",
              success: function (response) {
                $.each(response.data,function (k,v) {
                    console.log(v.KelompokUsaha);
                    $('#hargakain').val(v.hargakain);
                    $('#hargakaret').val(v.hargakaret);
                    $('#ongkosjait').val(v.ongkosjait);
                    $('#ongkospotong').val(v.ongkospotong);
                    $('#biayakemas').val(v.biayakemas);
                    $('#pemakaiankainperpcs').val(v.pemakaiankainperpcs);
                    
                    $('#id').val(v.id);
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
                keyExpr: "id",
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
                        dataField: "hargakain",
                        caption: "Harga Kain",
                        allowEditing:false
                    },
                    {
                        dataField: "hargakaret",
                        caption: "Harga Karet",
                        allowEditing:false
                    },
                    {
                        dataField: "ongkosjait",
                        caption: "Ongkos Jait",
                        allowEditing:false
                    },
                    {
                        dataField: "ongkospotong",
                        caption: "Ongkos Potong",
                        allowEditing:false
                    },
                    {
                        dataField: "biayakemas",
                        caption: "Biaya Kemas",
                        allowEditing:false
                    },
                    {
                        dataField: "pemakaiankainperpcs",
                        caption: "Pemakaian kain / pcs",
                        allowEditing:false
                    },
                    {
                        dataField: "Jenis",
                        caption: "Jenis Motif",
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
                        var value = id;

                        $.ajax({
                            type    :'post',
                            url     : '<?=base_url()?>Apps/HPPSetting',
                            data    : {id:id,'formtype':'delete'},
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
