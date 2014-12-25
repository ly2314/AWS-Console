<?php
    include '/aws_loader.php';
    echo "
        <div>
            <h1>Auto Scaling</h1>
            <table border='1'>
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Min Size</th>
                        <th>Max Size</th>
                        <th>Instances</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>";
    $asclient = $aws->get('AutoScaling');
    $buckets = $asclient->describeAutoScalingGroups();
    foreach ($buckets['AutoScalingGroups'] as $bucket)
    {
        echo "<form action='as_action.php' method='POST'><tr>\n";
        echo "<td>{$bucket['AutoScalingGroupName']}</td>\n";
        echo "<td><input value='{$bucket['MinSize']}' name='MinSize'></td>\n";
        echo "<td><input value='{$bucket['MaxSize']}' name='MaxSize'></td>\n";
        echo "<td><ul>\n";
        foreach ($bucket['Instances'] as $instance)
        {
            echo "<li>{$instance['InstanceId']}</li>";
        }
        echo "</ul></td>\n";
        echo "<td><input type='submit'>
        <input type='hidden' value='Resize' name='doWork'>
        <input type='hidden' value='{$bucket['AutoScalingGroupName']}' name='asGroup'>
        </td>\n";
        echo "</tr></form>\n";
    }
    echo "
                </tbody>
            </table>
        </div>";
?>