<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>AWS API Test</title>
    </head>
    <?php
        require 'aws-lib/aws-autoloader.php';
        use Aws\Common\Aws;
        $aws = Aws::factory('config.php');
    ?>
    <body>
        <div>
            <h1>S3</h1>
            <table border='1'>
                <thead>
                    <tr>
                        <th>Bucket Name</th>
                        <th>Creation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $s3client = $aws->get('s3');
                        $buckets = $s3client->listBuckets();
                        foreach ($buckets['Buckets'] as $bucket)
                        {
                            echo "<tr>\n";
                            echo "<td><a href='s3_files.php?name={$bucket['Name']}'>{$bucket['Name']}</a></td>\n";
                            echo "<td>{$bucket['CreationDate']}</td>\n";
                            echo "</tr>\n";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <h1>ec2</h1>
            <table border='1'> 
                <thead>
                    <tr>
                        <th>Instance ID</th>
                        <th>Name</th>
                        <th>Instance Type</th>
                        <th>Operation System</th>
                        <th>Architecture</th>
                        <th>Public IP</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $ec2client = $aws->get('Ec2');
                        $ec2instances = $ec2client->DescribeInstances();
                        foreach ($ec2instances['Reservations'] as $reservation)
                        {
                            foreach ($reservation['Instances'] as $instance)
                            {
                                echo "<tr>\n";
                                echo "<td><a href='ec2_stat.php?id={$instance['InstanceId']}'>{$instance['InstanceId']}</a></td>\n";
                                foreach ($instance['Tags'] as $tag)
                                {
                                    if ($tag['Key'] == 'Name')
                                    {
                                        echo "<td>{$tag['Value']}</td>\n";
                                        break;
                                    }
                                }
                                echo "<td>{$instance['InstanceType']}</td>\n";
                                $ec2image = $ec2client->describeImages(array(
                                    'ImageIds' => [$instance['ImageId']],
                                ));
                                echo "<td>{$ec2image['Images'][0]['Name']}</td>\n";
                                echo "<td>{$instance['Architecture']}</td>\n";
                                echo "<td>{$instance['PublicIpAddress']}</td>\n";
                                echo "<td>{$instance['State']['Name']}</td>\n";
                                echo "<td>\n";
                                echo "<form method='POST' action='ec2_action.php'>\n";
                                echo "<input type='hidden' name='id' value='{$instance['InstanceId']}'>\n";
                                echo "<input type='submit' name='doWork' value='Start'>\n";
                                echo "<input type='submit' name='doWork' value='Stop'>\n";
                                echo "<input type='submit' name='doWork' value='Reboot'>\n";
                                echo "</form>\n";
                                echo "</td>";
                                echo "</tr>\n";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <h1>RDS</h1>
            <table border='1'>
                <thead>
                    <tr>
                        <th>DB Instance Identifier</th>
                        <th>Engine</th>
                        <th>DB Instance Status</th>
                        <th>Master Username</th>
                        <th>Instance Create Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $rdsclient = $aws->get('Rds');
                        $buckets = $rdsclient->describeDBInstances();
                        foreach ($buckets['DBInstances'] as $bucket)
                        {
                            echo "<tr>\n";
                            echo "<td>{$bucket['DBInstanceIdentifier']}</td>\n";
                            echo "<td>{$bucket['Engine']}</td>\n";
                            echo "<td>{$bucket['DBInstanceStatus']}</td>\n";
                            echo "<td>{$bucket['MasterUsername']}</td>\n";
                            echo "<td>{$bucket['InstanceCreateTime']}</td>\n";
                            echo "</tr>\n";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <h1>Load Balancer</h1>
            <table border='1'>
                <thead>
                    <tr>
                        <th>Load Balancer Name</th>
                        <th>DNS Name</th>
                        <th>Canonical Hosted Zone Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $rdsclient = $aws->get('ElasticLoadBalancing');
                        $buckets = $rdsclient->describeLoadBalancers();
                        foreach ($buckets['LoadBalancerDescriptions'] as $bucket)
                        {
                            echo "<tr>\n";
                            echo "<td>{$bucket['LoadBalancerName']}</td>\n";
                            echo "<td>{$bucket['DNSName']}</td>\n";
                            echo "<td>{$bucket['CanonicalHostedZoneName']}</td>\n";
                            echo "</tr>\n";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <!--div>
            <h1>Glacier</h1>
            <table border='1'>
                <thead>
                    <tr>
                        <th>Vault Name</th>
                        <th>Last Inventory Date</th>
                        <th>Creation Date</th>
                        <th>Number Of Archives</th>
                        <th>Size In Bytes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $glacierclient = $aws->get('Glacier');
                        $buckets = $glacierclient->listVaults(array(
                            'accountId' => '-',
                        ));
                        foreach ($buckets['VaultList'] as $bucket)
                        {
                            $glacierresult = $glacierclient->describeVault(array(
                                'vaultName' => $bucket['VaultName'],
                                'accountId' => '-',
                            ));
                            echo "<tr>\n";
                            echo "<td>{$bucket['VaultName']}</td>\n";
                            echo "<td>{$bucket['LastInventoryDate']}</td>\n";
                            echo "<td>{$glacierresult['CreationDate']}</td>\n";
                            echo "<td>{$glacierresult['NumberOfArchives']}</td>\n";
                            echo "<td>{$glacierresult['SizeInBytes']}</td>\n";
                            echo "</tr>\n";
                        }
                    ?>
                </tbody>
            </table>
        </div-->
        <!--div>
            <h1>Glacier File Operations</h1>
            <table border='1'>
                <thead>
                    <tr>
                        <th>Job Id</th>
                        <th>Status</th>
                        <th>Creation Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $glacier_get_result = $glacierclient->listJobs(array(
                            'vaultName' => 'sylabtest',
                            'accountId' => '-',
                        ));
                        foreach ($glacier_get_result['JobList'] as $bucket)
                        {
                            echo "<tr>\n";
                            echo "<td> {$bucket['JobId']} </td>\n";
                            echo "<td> {$bucket['StatusMessage']} </td>\n";
                            echo "<td> {$bucket['CreationDate']} </td>\n";
                            if ($bucket['Completed'] == 1)
                            {
                                $glacier_job_detail = $glacierclient->describeJob(array(
                                    'vaultName' => 'sylabtest',
                                    'accountId' => '-',
                                    'jobId' => $bucket['JobId'],
                                ));
                                echo "<td><form method='POST' action='glacier_action.php'>\n";
                                echo "<input type='submit' name='doWork' value='GlacierDownload'>\n";
                                echo "<input type='hidden' name='id' value='{$glacier_job_detail['JobId']}'>\n";
                                echo "<input type='hidden' name='vault_id' value='sylabtest'>\n";
                                echo "<input type='hidden' name='range' value='{$glacier_job_detail['RetrievalByteRange']}'>\n";
                                echo "</form>\n";
                            }
                            else
                            {
                                echo "<td></td>\n";
                            }
                            echo "</tr>\n";
                        }
                    ?>
                </tbody>
            </table>
            <ul>
                <li>
                    <form method='POST'>
                        <input type='hidden' name='id' value='iYxiwBl1ZrE5gG6rbLAtlaGNs_PlvMxZ74U0Jt7-PBEpPjLCwwcPUPdPAnidH26MKn1dG3TSeRPltUMEptPtxz406pZWPg9kyZtpU6ketfom6eymIUlDZk5xuJrT-klaZiT6TQImMg'>
                        <input type='submit' name='doWork' value='GlacierGet'>
                    </form>
                </li>
            </ul>
        </div-->
    </body>
</html>