<?php
require_once 'init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo htmlentities(translate('web_title')); ?></title>
    <link rel="icon" href="vendor/img/logo.ico" type="image/x-icon">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/blog-home.css" rel="stylesheet">

    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="manifest" href="manifest.webmanifest">
    <script>
        
        window.addEventListener('load', ()=> {
            registerSW();
        });
        // Register the Service worker
        async function registerSW() {
            if ('serviceWorker' in navigator){
            try {
                await navigator
                .serviceWorker
                .register('sw.js');
            }
            catch (e){
                console.log('SW registration failed');
            }
        }
        }
      </script>
</head>

<body>