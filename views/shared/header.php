<?php
if (!defined('BASEPATH')) {
  header('Location:/404');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title . " - " . $app['company_name']; ?></title>
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo $app['domain']; ?>/src/img/fav.png' />
    <link rel="canonical" href="<?php echo $app['currenturl']; ?>" />
    <meta name="robots" content="index, follow">
    <meta name="author" content="<?php echo $app['company_name']; ?>">
    <meta name="description" content="<?php echo $title . " : " . $app['company_name']; ?> - <?php echo $app['cdesc']; ?>">
    <meta name="keywords" content="<?php echo $app['ckey']; ?>, <?php echo $title . " - " . $app['company_name']; ?>">
    <meta name="twitter:card" value="<?php echo $title . " - " . $app['company_name']; ?> ">
    <meta property="og:title" content="<?php echo $title . " - " . $app['company_name']; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $app['currenturl']; ?>" />
    <meta name="twitter:image" content="<?php echo $app['domain']; ?>/src/img/fav.png">
    <meta property="og:image" content="<?php echo $app['domain']; ?>/src/img/fav.png" />
    <meta property="og:description" content="<?php echo $title . " : " . $app['company_name']; ?> - <?php echo $app['cdesc']; ?>">
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "<?php echo $title . " - " . $app['company_name']; ?>",
      "url": "<?php echo $app['currenturl']; ?>",
      "description": "<?php echo $title . " : " . $app['company_name']; ?> - <?php echo $app['cdesc']; ?>",
      "image": {
        "@type": "ImageObject",
        "url": "<?php echo $app['domain']; ?>/src/img/fav.png"
      },
      "author": {
        "@type": "Rajsoft",
        "name": "<?php echo $app['company_name']; ?>"
      }
    }
    </script>
</head>


<body>