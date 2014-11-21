<?php
    include 'aws_loader.php';
    echo "
    <div>
        <h1>S3</h1>
        <table border='1'>
            <thead>
                <tr>
                    <th>Bucket Name</th>
                    <th>Creation Date</th>
                </tr>
            </thead>
            <tbody>";
    $s3client = $aws->get('s3');
    $buckets = $s3client->listBuckets();
    foreach ($buckets['Buckets'] as $bucket)
    {
        echo "<tr>\n";
        echo "<td><a href='".$dir."s3_files.php?name={$bucket['Name']}'>{$bucket['Name']}</a></td>\n";
        echo "<td>{$bucket['CreationDate']}</td>\n";
        echo "</tr>\n";
    }
    echo "
            </tbody>
        </table>
    </div>";
?>