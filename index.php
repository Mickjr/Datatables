<?php
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
    $site_url = $protocol . $_SERVER['SERVER_NAME'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test</title>

    <!-- Bootstrap -->
    <link href="<?php echo $site_url; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo $site_url; ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Alertify -->
    <link href="<?php echo $site_url; ?>/vendors/alertify/alertify.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="<?php echo $site_url; ?>/vendors/DataTables/datatables.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="<?php echo $site_url; ?>/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Pickadate -->
    <link href="<?php echo $site_url; ?>/vendors/pickadate/compressed/themes/classic.css" rel="stylesheet">
    <link href="<?php echo $site_url; ?>/vendors/pickadate/compressed/themes/classic.date.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo $site_url; ?>/style.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="<?php echo $site_url; ?>/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo $site_url; ?>/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="<?php echo $site_url; ?>/vendors/DataTables/datatables.min.js"></script>
    <!-- Alertify -->
    <script src="<?php echo $site_url; ?>/vendors/alertify/alertify.js"></script>
    <!-- Select2 -->
    <script src="<?php echo $site_url; ?>/vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Pickadate -->
    <script src="<?php echo $site_url; ?>/vendors/pickadate/compressed/picker.js"></script>
	<script src="<?php echo $site_url; ?>/vendors/pickadate/compressed/picker.date.js"></script>

  </head>
  <body>

      <div id="wrapper" class="container">

          <div class="row">
              <div class="col-md-5">
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                          <select id="gender" name="gender" class="form-control select2_single">
                              <option value="All">All</option>
                              <option value="Female">Female</option>
                              <option value="Male">Male</option>
                          </select>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Born From:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                          <div class="input-group">
                              <input  type="text" id="date_from" name="date_from"
                                  class="form-control datepicker">
                              <span class="add-on input-group-addon">
                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                              </span>
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Born To:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                          <div class="input-group">
                              <input  type="text" id="date_to" name="date_to"
                                  class="form-control datepicker">
                              <span class="add-on input-group-addon">
                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                              </span>
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-8 col-md-offset-3 col-sm-8 col-sm-offset-3 col-xs-12">
                          <button id="submit" name="submit" class="btn btn-success" type="submit">Get Info</button>
                      </div>
                  </div>

              </div>
              <div class="col-md-7"></div>

          </div>

          <div id="results" class="row">
              <div class="col-sm-12">
                  <div id="table-container">
                      <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th>First Name`</th>
                                  <th>Last Name</th>
                                  <th>Email</th>
                                  <th>Birth Date</th>
                                  <th>Gender</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div><!-- End of Table Container -->
              </div>

          </div><!-- END of Table Row -->

      </div><!-- End of Wrapper -->


      <script>

        var $table;

        $(document).ready(function() {

            $('#results').hide();

            $('.datepicker').pickadate({
				format: 'mm/dd/yyyy',
				formatSubmit : 'yyyy-mm-dd',
                selectMonths: true,
                selectYears: true,
                selectYears: 100,
                min: [1960,1,1],
                max: new Date()
			});

            $("#gender").select2({
				placeholder: "Select Role", allowClear: false
			});

        });

        $(document).on('click', '#submit', function(e) {

            e.preventDefault();

            if ( table ) {
                table_load();
            } else {
                //$('#table tbody').empty();
                table.ajax.reload();
            }

        });

        function table_load() {
            $('#results').show();

            gend = $('#gender').val();
            from = $('#date_from').val();
            to   = $('#date_to').val();

    	    table = $('#table').DataTable({

    	        "processing": true, //Feature control the processing indicator.
                "destroy": true,
    	        "serverSide": true, //Feature control DataTables' server-side processing mode.
    	        "order": [], //Initial no order.

    	        // Load data for the table's content from an Ajax source
    	        "ajax": {
    	            "url": "<?php echo $site_url; ?>/inc/get_data.php",
    	            "type": "POST",
                    data: { type: gend, dfrom: from, dto: to },
                    "dataSrc" : function ( json ) {
                        alertify.alert("SQL COMMAND: " + json.sqlCommand);
                        return json.data;
                    }
    	        },

    	        //Set column definition initialisation properties.
    	        "columnDefs": [
    	        {
    	            "targets": [ -1 ], //last column
    	            "orderable": false, //set not orderable
    	        },
    	        ],
                "lengthMenu": [ 10, 25, 50, 100, 200, 300, 500, 1000 ],
                dom: '<"top"l>frtip<"bottom">B',
                buttons: {
                    'name': 'primary',
                    buttons: [
                        {
                            extend: 'copy',
                            text: 'Copy to Clipboard'
                        },
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ]
                },

    	    });

            // table.buttons().container()
            //     .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

        }

      </script>


  </body>
</html>
