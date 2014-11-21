<?php
    require 'aws-lib/aws-autoloader.php';
    use Aws\Common\Aws;
    $aws = Aws::factory('config.php');
    $ec2client = $aws->get('Ec2');
    if ($_POST['doWork'] == 'Stop')
    {
        $result = $ec2client->stopInstances(array(
            'InstanceIds' => array($_POST['id']),
        ));
    }
    else if ($_POST['doWork'] == 'Start')
    {
        $result = $ec2client->startInstances(array(
            'InstanceIds' => array($_POST['id']),
        ));
    }
    else if ($_POST['doWork'] == 'Reboot')
    {
        $result = $ec2client->rebootInstances(array(
            'InstanceIds' => array($_POST['id']),
        ));
    }
    header('Location: index.php');
?>