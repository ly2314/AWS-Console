<?php
    include '/aws_loader.php';
    $asclient = $aws->get('AutoScaling');
    if ($_POST["doWork"] == "Resize")
    {
        $result = $asclient->updateAutoScalingGroup(array(
            'AutoScalingGroupName' => $_POST['asGroup'],
            'MinSize' => $_POST['MinSize'],
            'MaxSize' => $_POST['MaxSize'],
            'DesiredCapacity' => $_POST['MinSize'],
        ));
    }
    header('Location: index.php');
?>