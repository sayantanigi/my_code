<!DOCTYPE html>
<html>
	<head>
		<title>Crop Image Before Upload using CropperJS with PHP</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>        
		<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
		<script src="https://unpkg.com/dropzone"></script>
		<script src="https://unpkg.com/cropperjs"></script>
		<style>
		.image_area{position:relative}.overlay,.text{position:absolute}img{display:block;max-width:100%}.preview{overflow:hidden;width:160px;height:160px;margin:10px;border:1px solid red}.modal-lg{max-width:1000px!important}.overlay{bottom:10px;left:0;right:0;background-color:rgba(255,255,255,.5);overflow:hidden;height:0;transition:.5s;width:100%}.image_area:hover .overlay{height:50%;cursor:pointer}.text{color:#333;font-size:20px;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);text-align:center}
		</style>
	</head>
	<body>
		<div class="container" align="center">
			<br />
			<h3 align="center"></h3>
			<br />
			<div class="row">
				<div class="col-md-4">&nbsp;</div>
				<div class="col-md-4">
					<div class="image_area">
						<form method="post">
							<label for="upload_image">
								<img src="upload/user.png" id="uploaded_image" class="img-responsive img-circle" />
								<div class="overlay">
								    <div class="text">Click to Change Profile Image</div>
								</div>
			    				<input type="file" name="image" class="image" id="upload_image" style="display:none">
			    			</label>
			    		</form>
			    	</div>
			    </div>
    		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			  	<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<h5 class="modal-title" id="modalLabel">Crop Image Before Upload</h5>
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          			<span aria-hidden="true">×</span>
			        		</button>
			      		</div>
			      		<div class="modal-body">
			        		<div class="img-container">
			            		<div class="row">
			                		<div class="col-md-8">
			                    		<img src="" id="sample_image" />
			                		</div>
			                		<div class="col-md-4">
			                    		<div class="preview"></div>
			                		</div>
			            		</div>
			        		</div>
			      		</div>
			      		<div class="modal-footer">
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			        		<button type="button" class="btn btn-primary" id="crop">Crop</button>
			      		</div>
			    	</div>
			  	</div>
			</div>			
		</div>
	</body>
</html>

<script>

$(document).ready(function(){

	/*function readURL(input)
	{
  		if(input.files && input.files[0])
  		{
    		var reader = new FileReader();
    
		    reader.onload = function(event) {
		      	$('#uploaded_image').attr('src', event.target.result);
		      	$('#uploaded_image').removeClass('img-circle');
		      	$('#upload_image').after('<div align="center" id="crop_button_area"><br /><button type="button" class="btn btn-primary" id="crop">Crop</button></div>')
		    }
		    reader.readAsDataURL(input.files[0]); // convert to base64 string
  		}
  	}

  	$("#upload_image").change(function() {
  		readURL(this);
  		var image = document.getElementById("uploaded_image");
  		cropper = new Cropper(image, {
    		aspectRatio: 1,
    		viewMode: 3,
    		preview: '.preview'
    	});
	});*/

	
	var $modal = $('#modal');
	var image = document.getElementById('sample_image');
	var cropper;

	//$("body").on("change", ".image", function(e){
	$('#upload_image').change(function(event){
    	var files = event.target.files;
    	var done = function (url) {
      		image.src = url;
      		$modal.modal('show');
    	};
    	//var reader;
    	//var file;
    	//var url;

    	if (files && files.length > 0)
    	{
      		/*file = files[0];
      		if(URL)
      		{
        		done(URL.createObjectURL(file));
      		}
      		else if(FileReader)
      		{*/
        		reader = new FileReader();
		        reader.onload = function (event) {
		          	done(reader.result);
		        };
        		reader.readAsDataURL(files[0]);
      		//}
    	}
	});

	$modal.on('shown.bs.modal', function() {
    	cropper = new Cropper(image, {
    		aspectRatio: 1,
    		viewMode: 3,
    		preview: '.preview'
    	});
	}).on('hidden.bs.modal', function() {
   		cropper.destroy();
   		cropper = null;
	});

	$("#crop").click(function(){
    	canvas = cropper.getCroppedCanvas({
      		width: 400,
      		height: 400,
    	});

    	canvas.toBlob(function(blob) {
        	//url = URL.createObjectURL(blob);
        	var reader = new FileReader();
         	reader.readAsDataURL(blob); 
         	reader.onloadend = function() {
            	var base64data = reader.result;  
            
            	$.ajax({
            		url: "upload.php",
                	method: "POST",                	
                	data: {image: base64data},
                	success: function(data){
                    	console.log(data);
                    	$modal.modal('hide');
                    	$('#uploaded_image').attr('src', data);
                    	//alert("success upload image");
                	}
              	});
         	}
    	});
    });
	
});
</script>

<!--Php cropper js example, Php crop image before upload, crop image using cropper.js Php, Php crop image before upload cropper.js, cropper js Php example, Php image upload cropper, how to use image cropper in Php!-->