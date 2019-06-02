<h2>Fontseller Font Upload</h2>
<div class="steps">
    <div class="active">Upload the font file</div>
    <div>Name the Font Family</div>
    <div>Set the </div>
</div>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="exampleFormControlFile1">Select Files</label>
        <input type="file" class="form-control-file" id="exampleFormControlFile1" name="font-files[]" multiple>
    </div>
    <input type="submit" class="button button-primary" name="fsUpload" value="Upload"> 
</form>