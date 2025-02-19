<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label for="file">Select file to upload:</label>
    <input type="file" name="fileToUpload" id="file">
    <button type="submit">Upload</button>
</form>

<!-- CSV Wisdom:
in most use cases, you always need to skip the firt line of the import
Also, the structure of the csv is of the utmost importance. 
If the csv is not structured properly, the import will fail.
The csv should be structured in a way that the first row contains 
the column names and the subsequent rows contain the data. -->