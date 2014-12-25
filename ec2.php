<?php
    include 'aws_loader.php';
    echo "
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
            <tbody>";
    $ec2client = $aws->get('Ec2');
    $ec2instances = $ec2client->DescribeInstances();
    foreach ($ec2instances['Reservations'] as $reservation)
    {
        foreach ($reservation['Instances'] as $instance)
        {
            echo "<tr>\n";
            echo "<td><a href='ec2_stat.php?id={$instance['InstanceId']}'>{$instance['InstanceId']}</a></td>\n";
            $contains_name = false;
            foreach ($instance['Tags'] as $tag)
            {
                if ($tag['Key'] == 'Name')
                {
                    $contains_name = true;
                    echo "<td>{$tag['Value']}</td>\n";
                    break;
                }
            }
            if ($contains_name === false)
            {
                echo "<td></td>\n";
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
    echo "</tbody>
        </table>
    </div>";
?>