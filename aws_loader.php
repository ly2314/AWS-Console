<?php
    require 'aws-lib/aws-autoloader.php';
    use Aws\Common\Aws;
    $aws = Aws::factory('config.php');
?>