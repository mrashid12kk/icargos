<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ajax Excel File Upload</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

	
</head>
<body>
	<div class="container mt-5">
		<div class="card">
			<div class="card-header">
				<h2>Ajax Excel File Upload</h2>
			</div>
			<div class="card-body">
				<form id="excel-form" method="POST" enctype="multipart/form-data"> 
					<div class="form-group">
					    <label>Upload File:</label>
					    <input type="file" name="file" id="excel-file" class="form-control">
					</div>
					<input type="submit" name="upload" value="Upload" class="btn btn-primary">

				</form>
				<div >
					<div class="card" >
						<div class="card-header text-center text-white bg-info">
							<h2>Excel Data View</h2>
						</div>
						<div class="card-body" id="show-excel-data"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
	<script>
		$(document).ready(function() {
			$("#excel-form").on("submit",function(e) {
				e.preventDefault();
				
				$.ajax({
					// url: "show-excel-data-browser.php",
					url: "import-excel-data-to-database.php",
					method: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						$("#show-excel-data").html(data);
					}
				});

			})
		});
	</script>
</body>
</html>