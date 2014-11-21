<?php
    include '/aws_loader.php';
    echo "
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
                <tbody>";
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
    echo "
                </tbody>
            </table>
        </div>";
?>