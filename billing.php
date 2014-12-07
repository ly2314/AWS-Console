<?php
    include 'aws_loader.php';
    include 'billing_config.php';

    $month = Date('m');
    $year = Date('Y');
    $csv_name = $account_id.'-aws-billing-csv-'.$year.'-'.$month.'.csv';
    $s3client = $aws->get('s3');
    $temp = tempnam(sys_get_temp_dir(), 's3d');
    $result = $s3client->getObject(array(
        'Bucket' => $bucket_name,
        'Key' => $csv_name,
        'SaveAs' => $temp
    ));
    echo "
        <div>
            <h1>Billing</h1>";
    $result = array();
    if (($handle = fopen($temp, "r")) !== FALSE)
    {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE)
        {
            $num = count($data);
            if((!($data[$num - 1] == 0)) && $data[0] == 'Estimated' && $data[3] == 'LinkedLineItem')
            {
                if (array_key_exists($data[13], $result))
                {
                    $result[$data[13]] += $data[28];
                }
                else
                {
                    $result[$data[13]] = $data[28];
                }
            }
        }
        fclose($handle);
    }

    echo "<table border='1'> 
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($result as $key => $value)
    {
        echo "<tr>";
        echo "<td>".$key."</td>";
        echo "<td>".$value."</td>";
        echo "</tr>";
    }
    echo "</tbody>
        </table></div>";
?>