<?php
if (!defined('BASEPATH')) {
  header('Location:/404');
}
require_once __DIR__ . '/../../../src/Utils.php';
$site_name = Config::APP['name'];
$site_url = Utils::getCurrentUrl();
$url_text = Utils::getUrlText($site_url)["msg"];
$page_title = $url_text ? "$url_text | $site_name" : $site_name;

if(CORE->userLoggedIn()['type'] != 'admin')
{
    echo "<script>window.location='".BASEPATH."'</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
</head>
<body>