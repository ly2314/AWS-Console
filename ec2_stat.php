<html>
    <?php
        require 'aws-lib/aws-autoloader.php';
        use Aws\Common\Aws;
        $aws = Aws::factory('config.php');
        $cloudwatchclient = $aws->get('CloudWatch');
        $ec2instance = $_GET['id'];
    ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>
            Status of instance <?php echo $ec2instance; ?>
        </title>
        <script src='js/dygraph-1.0.1-combined.js'></script>
    </head>
    <body>
        <h1>Status of instance <?php echo $ec2instance; ?></h1>
        <?php
            function gen_graph($cloudwatchclient, $metric, $id)
            {
                $statsyo = $cloudwatchclient->getMetricStatistics(array(
                    'Namespace' => 'AWS/EC2',
                    'MetricName' => $metric,
                    'Dimensions' => array(array('Name' => 'InstanceId', 'Value' => $id)),
                    'StartTime' => strtotime('-1 days'),
                    'EndTime' => strtotime('now'),
                    'Period' => 60,
                    'Statistics' => array('Average', 'Minimum', 'Maximum')
                ));
                echo "<h2>".$statsyo['Label'].' of '.$id."</h2><br>\n";

                $data = 'Timestamp,Average,Minimum,Maximum\n';
                for ($i = 0; $i < count($statsyo['Datapoints']); $i++)
                {
                    $data .= $statsyo['Datapoints'][$i]['Timestamp'];
                    $data .= ',';
                    $data .= $statsyo['Datapoints'][$i]['Average'];
                    $data .= ',';
                    $data .= $statsyo['Datapoints'][$i]['Minimum'];
                    $data .= ',';
                    $data .= $statsyo['Datapoints'][$i]['Maximum'];
                    $data .= '\n';
                }

                echo "<div id='{$metric}Graph'></div>\n";
                echo "<script>\n";
                echo "var {$metric}Data = \"{$data}\";\n";
                echo "var g1 = new Dygraph(document.getElementById('{$metric}Graph'), {$metric}Data,\n";
                echo "    {\n"; 
                echo "        animatedZooms: true,\n";
                echo "        labelsSeparateLines : true,\n";
                echo "    });\n";
                echo "</script>\n";
            }
        ?>
        <?php gen_graph($cloudwatchclient, 'CPUUtilization', $ec2instance); ?>
        <?php gen_graph($cloudwatchclient, 'NetworkIn', $ec2instance); ?>
        <?php gen_graph($cloudwatchclient, 'NetworkOut', $ec2instance); ?>
        <?php gen_graph($cloudwatchclient, 'DiskReadOps', $ec2instance); ?>
        <?php gen_graph($cloudwatchclient, 'DiskWriteOps', $ec2instance); ?>
        <?php gen_graph($cloudwatchclient, 'DiskReadBytes', $ec2instance); ?>
        <?php gen_graph($cloudwatchclient, 'DiskWriteBytes', $ec2instance); ?>
        <?php gen_graph($cloudwatchclient, 'StatusCheckFailed', $ec2instance); ?>
    </body>
</html>