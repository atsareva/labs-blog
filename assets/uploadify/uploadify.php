<?php
            
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif'); //допустимые расширения
$max_file_size = 1024 * 500;
$max_dimension = 1000;
if (!empty($_FILES))
{
    if ($_FILES['Filedata']['error'] != 0)
    {
        echo $_FILES['Filedata']['error'];
    } else
    {
        $error = false;
        $fileParts = pathinfo($_FILES['Filedata']['name']);
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
        $generate_name = time() ."_".rand(100,999)."." . $fileParts['extension'];
        $targetFile = str_replace('//', '/', $targetPath) . $generate_name; //$_FILES['Filedata']['name'];

        $option = getimagesize($tempFile);
        $size = filesize($tempFile);
       /* if (!in_array($fileParts['extension'], $allowed_ext))
        {
            $error = true;
            echo "10";//'Invalid file type.';
            
            /*$myFile = "chelyabinskiy_debug.log";
            $fh = fopen($myFile, 'w') or die("can't open file");
            $Data = "extension:". $fileParts['extension'] ."\n";
            fwrite($fh, $Data);
            fclose($fh);*/
        /*}
        if (!$error && ($option[0] > $max_dimension || $option[1] > $max_dimension))
        {
            $error = true;
            echo "11";//'Dimension must be at least 1000x1000px.';
        }
        if (!$error && $size > $max_file_size)
        {
            $error = true;
            echo "12";//'The file is bigger' . $max_file_size;
        }

        if (!$error)
        {*/
            move_uploaded_file($tempFile, $targetFile);
            $ext = pathinfo($_REQUEST['folder'] . '/' . $_FILES['Filedata']['name']);
            $stringData = '/upload/' . $generate_name; //str_replace($_SERVER['DOCUMENT_ROOT'], '', $targetFile);
            echo $stringData;
       // }
    }
}
?>