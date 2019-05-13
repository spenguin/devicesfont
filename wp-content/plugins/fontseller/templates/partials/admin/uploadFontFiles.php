<h2>Fontseller Font Upload</h2>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="exampleFormControlFile1">Select Files</label>
        <input type="file" class="form-control-file" id="exampleFormControlFile1" name="font-files[]" multiple>
    </div>
    <button type="submit" class="btn btn-primary" name="fsUpload">Upload</button> 
</form>