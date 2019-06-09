<h2>Fontseller Font Upload</h2>

<?php echo(  $steps ); ?>

<?php echo( join( "<br />", $errors ) ); ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="exampleFormControlFile1">Select Files</label>
        <input type="file" class="form-control-file" id="exampleFormControlFile1" name="font-files[]" multiple>
    </div>
    <input type="submit" class="button button-primary" name="UploadFiles" value="Upload Files"> 
</form>