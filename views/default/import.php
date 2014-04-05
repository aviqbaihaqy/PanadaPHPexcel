<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header">Import From Excel</h3>

			<div id="alert" class="alert alert-dismissable hide">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			</div>

			<form id="f_import" class="form-horizontal" action="<?= $this->location('import/upload') ?>" enctype="multipart/form-data" method="post" rel="create">
				<fieldset>
					<!-- Select Basic -->
					<div class="form-group">
						<label class="col-md-4 control-label" for="selectdata">Select Master Data</label>
						<div class="col-md-4">
							<select id="selectdata" name="selectdata" class="form-control">
								<!-- <option value="#DOC123">DOC123</option> -->
								<option value="#DOC001">Option One</option>
								<option value="#DOC002">Option two</option>
								<option value="#DOC003">Option three</option>
								<option value="#DOC004">Option for</option>
								<option value="#DOC005">Option five</option>
							</select>
						</div>
					</div>

					<!-- File Button --> 
					<div class="form-group">
						<label class="col-md-4 control-label" for="filebutton">File</label>
						<div class="col-md-4">
							<input id="filebutton" name="my_file" class="input-file" type="file">
						</div>
					</div>

					<!-- Button -->
					<div class="form-group">
						<label class="col-md-4 control-label" for="singlebutton"></label>
						<div class="col-md-4">
							<button id="import_submit" type="submit" name="singlebutton" class="btn btn-primary" data-loading-text="Loading...">Import Document</button>
						</div>
					</div>

				</fieldset>
			</form>

			<table id="preview" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed table-hover table-bordered"  width="100%">
			</table>

		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>

<script type="text/javascript">
	/* upload file added by aviq
	* ================== */
	$(function(){
		$('form').on('submit', uploadFiles);
		
		$('form').ajaxStop(function(){
			$("#import_submit").button('reset');
		});
		
		// Catch the form submit and upload the files
		function uploadFiles(event){
			event.stopPropagation(); // Stop stuff happening
			event.preventDefault(); // Totally stop stuff happening
			
			$("#import_submit").button('loading');
			// Create a formdata object and add the files
			var data = new FormData($('form')[0]);

			$.ajax({
				url: $('form').attr('action'),
				type: 'POST',
				data: data,
				cache: false,
				dataType: 'json',
				processData: false, // Don't process the files
				contentType: false, // Set content type to false as jQuery will tell the server its a query string request
				success: function(data, textStatus, jqXHR){
					if(typeof data.message.success != 'undefined'){
						$("div#alert").removeClass("alert-danger").addClass("alert-success").show().html('<strong>Success</strong> '+data.message.success);
						dataTables(data);
					}
					else{
						$("div#alert").removeClass("alert-success").addClass("alert-danger").show().html('<strong>Error</strong> '+data.message.error);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					$("div#alert").removeClass("alert-success").addClass("alert-error").show().html('<strong>Error</strong> '+ errorThrown);
				}
			});
		}
		
		function dataTables(data){
			var mdata = [];
			var cDef =[];
			var i = 0;
			$.each(data.import[0], function(key,value){
				mdata.push({"mData": key});
				cDef.push({"sTitle": key,"aTargets": [i]});
				i++;
			});
			
			var oTable = $('#preview').dataTable({              
				"sDom": "<'header'><'row'<'col-md-3'l><'col-md-5'><'col-md-4'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"aaData": data.import, 
				"sScrollY": "500px",
				"sScrollX": "100%",
				"bDestroy": true,
				"aoColumns": mdata,
				"aoColumnDefs": cDef,
				"iDisplayLength": 50,
				"aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]]
			});
			$("div.header").html('<h3>Data yang sukses di import</h3>');
		}
	});
</script>