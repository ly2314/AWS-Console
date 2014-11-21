<?php
    include 'aws_loader.php';
    $s3client = $aws->get('s3');
    $bucket_name = $_POST['bucket'];
    $object_key = $_POST['key'];
    if ($_POST["doWork"] == "Delete")
    {
        $result = $s3client->deleteObject(array(
            'Bucket' => $bucket_name,
            'Key' => $object_key,
        ));
    }
    else if ($_POST['doWork'] == 'Download')
    {
        $result = $s3client->getObject(array(
            'Bucket' => $bucket_name,
            'Key' => $object_key,
        ));
        header('Content-Type:'.$result["ContentType"]);
        header('Content-Disposition: attachment; filename='.$object_key);
        echo $result['Body'];
    }
    else if ($_POST['doWork'] == 'Upload')
    {
        $file = $_FILES['file'];
        $result = $s3client->putObject(array(
            'Bucket'       => $bucket_name,
            'Key'          => $file['name'],
            'SourceFile'   => $file['tmp_name'],
            'ContentType'  => $file['type'],
        ));
    }
    header('Location: s3_files.php?name='.$bucket_name);
?>