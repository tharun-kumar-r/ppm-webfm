<?php
if (!defined('BASEPATH')) {
  header('Location:/404');
}
require_once __DIR__ . '/../../src/Utils.php';
$site_name = Config::APP['name'];
$site_url = Utils::getCurrentUrl();
$url_text = Utils::getUrlText($site_url)["msg"];
$page_title = $url_text ? "$url_text | $site_name" : $site_name;
$companyData = DBFunctions::query("CALL GetCompanyAndMetadata(?, ?)", [1, $url_text ? Utils::getUrlText($site_url, false)["msg"] : '/'], true);
$metaData = Config::APP['isDynamicApp'] && $companyData['metadata_exists'] ? $companyData : Config::APP['metaData'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $metaData['meta_description']; ?>">
    <meta name="keywords" content="<?php echo $metaData['meta_keywords']; ?>">
    <link rel="canonical" href="<?php echo $site_url; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $metaData['meta_description']; ?>">
    <meta property="og:image" content="<?php echo $metaData['og_image']; ?>">
    <meta property="og:url" content="<?php echo $site_url; ?>">
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $page_title; ?>">
    <meta name="twitter:description" content="<?php echo $metaData['meta_description']; ?>">
    <meta name="twitter:image" content="<?php echo $metaData['og_image']; ?>">
    <meta name="robots" content="<?php echo !Config::APP['dev'] ? 'index, follow' : 'noindex, unfollow'; ?>">
    <link rel="icon" type="image/png" href="<?php echo $logo; ?>">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Website",
        "name": "<?php echo $site_name; ?>",
        "url": "<?php echo $site_url; ?>",
        "description": "<?php echo $metaData['meta_description']; ?>",
        "image": "<?php echo $metaData['og_image']; ?>"
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "<?php echo Config::APP['businessType']; ?>",
        "name": "<?php echo $site_name; ?>",
        "url": "<?php echo $site_url; ?>",
        "logo": "<?php echo $logo; ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?php echo $companyData['address']; ?>",
            "addressCountry": "<?php echo Config::APP['country']; ?>"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "<?php echo $companyData['phone']; ?>",
            "email": "<?php echo $companyData['email']; ?>",
            "contactType": "customer service"
        }
    }
    </script>


    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
</head>
<body>