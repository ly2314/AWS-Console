<html>
    <?php
        require 'aws-lib/aws-autoloader.php';
        use Aws\Common\Aws;
        $aws = Aws::factory('config.php');
        $s3client = $aws->get('s3');
        $bucket_name = $_GET['name'];
    ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Objects in <?php echo $bucket_name; ?></title>
    </head>
    <body>
        <table border='1'>
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Last Modified</th>
                    <th>Size</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $bucket_objects = $s3client->ListObjects(array(
                        'Bucket' => $bucket_name
                    ));
                    foreach ($bucket_objects['Contents'] as $object)
                    {
                        echo "<tr>\n";

                        echo "<td>{$object['Key']}</td>\n";

                        echo "<td>{$object['LastModified']}</td>\n";

                        echo "<td>{$object['Size']}</td>\n";
                        
                        echo "<td>\n";
                        echo "<form action='s3_action.php' method='POST'>\n";
                        echo "<input type='hidden' name='key' value={$object['Key']}>\n";
                        echo "<input type='hidden' name='bucket' value={$bucket_name}>\n";
                        echo "<input type='submit' name='doWork' value='Download'>\n";
                        echo "<input type='submit' name='doWork' value='Delete'>\n";
                        echo "</form>\n";
                        echo "</td>\n";

                        echo "</tr>\n";
                    }
                ?>
            </tbody>
        </table>
        <form action='s3_action.php' method='POST' enctype="multipart/form-data">
            <input type='hidden' name='key'>
            <input type='file' id='file' name='file'>
            <input type='hidden' name='bucket' value='<?php echo $bucket_name; ?>'>
            <input type='submit' name='doWork' value='Upload'>
        </form>
    </body>
</html>