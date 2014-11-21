<?php
    include '/aws_loader.php';
    echo "
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
                <tbody>";
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
    echo "
                </tbody>
            </table>
        </div>";
?>