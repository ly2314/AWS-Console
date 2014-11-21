<?php
    require 'aws-lib/aws-autoloader.php';
    use Aws\Common\Aws;
    $aws = Aws::factory('config.php');
    $glacierclient = $aws->get('Glacier');
    if ($_POST['doWork'] == 'GlacierGet')
    {

    }
    if ($_POST['doWork'] == 'GlacierDownload')
    {
        $vault = $_POST['vault_id'];
        $job_id = $_POST['id'];
        $range = $_POST['range'];
        $result = $glacierclient->getJobOutput(array(
            'accountId' => '-',
            'vaultName' => $vault,
            'jobId' => $job_id,
        ));
        header('Content-Type:image/jpeg');
        echo $result['body'];
    }
?>