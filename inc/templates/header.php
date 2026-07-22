<!DOCTYPE html>
<html lang="es" >
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="BitFlow - Desarrollo de software, aplicaciones web y soluciones digitales a medida.">
        <title>BitFlow | Desarrollo de Software</title>
        <link rel="icon" type="image/png" href="img/favicon.png" sizes="512x512">
        <link rel="apple-touch-icon" href="img/favicon.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/normalize.css">       
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="css/boton-menu.css">
        <link rel="stylesheet" href="css/contacto.css">
        <link rel="stylesheet" href="css/lightbox.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/boton-flechas.css">
        <link rel="stylesheet" href="css/ProySlider.css">
        <link rel="stylesheet" href="css/Glitch.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?php if (!empty($bodyClass) && $bodyClass === 'page-inicio'): ?>
        <style>
            .LetrasLogo[data-mode="continue"]:not(.is-split-visible) .splash-hero-bar {
                margin-top: 0;
                height: 0;
                overflow: hidden;
                visibility: hidden;
            }
            .LetrasLogo[data-mode="continue"]:not(.is-split-visible) .bitflow-letters-row,
            .LetrasLogo[data-mode="continue"]:not(.is-split-visible) .splash-tagline {
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none;
            }
        </style>
        <script>try{if(sessionStorage.getItem('bitflowFromSplash'))document.documentElement.classList.add('splash-from-index');}catch(e){}</script>
<?php endif; ?>
        
    
    </head>
    <body class="<?php echo isset($bodyClass) ? htmlspecialchars($bodyClass) : ''; ?>">
