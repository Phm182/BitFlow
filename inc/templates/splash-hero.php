<?php
    $splashMode = isset($splashMode) ? $splashMode : 'full';
    $showSplashButton = isset($showSplashButton) ? $showSplashButton : false;
    $showScrollButton = isset($showScrollButton) ? $showScrollButton : false;
    $scrollTarget = isset($scrollTarget) ? $scrollTarget : '#section-quienes';
?>
<div class="LetrasLogo" data-mode="<?php echo htmlspecialchars($splashMode); ?>">

<?php if ($splashMode === 'continue'): ?>

    <div class="splash-backdrop" aria-hidden="false">
        <div class="BitFlow HexIcono">
            <div class="hex-ring hex-ring-1"></div>
            <div class="hex-ring hex-ring-2"></div>
            <div class="icon-only-wrap">
                <img src="img/logo-bitflow-icon-hd.png" alt="BitFlow" class="icon-only">
            </div>
        </div>
    </div>

    <div class="splash-hero-bar">
        <div class="bitflow-letters-row">
            <div class="BitFlow lett B"><span>B</span></div>
            <div class="BitFlow lett I"><span>I</span></div>
            <div class="BitFlow lett T"><span>T</span></div>
            <div class="BitFlow lett F"><span>F</span></div>
            <div class="BitFlow lett L"><span>L</span></div>
            <div class="BitFlow lett O"><span>O</span></div>
            <div class="BitFlow lett W"><span>W</span></div>
        </div>
        <p class="splash-tagline" aria-hidden="true">Desarrollo de Software <span class="splash-tagline__slash">/</span><span class="splash-tagline__br" aria-hidden="true"></span> Soporte técnico</p>
    </div>

<?php else: ?>

    <div class="BitFlow HexIcono">
        <div class="hex-ring hex-ring-1"></div>
        <div class="hex-ring hex-ring-2"></div>
        <div class="icon-only-wrap">
            <img src="img/logo-bitflow-icon-hd.png" alt="BitFlow" class="icon-only">
        </div>
        <?php if ($showSplashButton): ?>
        <div class="Boton-flechas">
            <?php include 'inc/templates/Boton-flechas.php'; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="bitflow-letters-row">
        <div class="BitFlow lett B"><span>B</span></div>
        <div class="BitFlow lett I"><span>I</span></div>
        <div class="BitFlow lett T"><span>T</span></div>
        <div class="BitFlow lett F"><span>F</span></div>
        <div class="BitFlow lett L"><span>L</span></div>
        <div class="BitFlow lett O"><span>O</span></div>
        <div class="BitFlow lett W"><span>W</span></div>
    </div>

<?php endif; ?>

    <?php if ($showScrollButton): ?>
    <div class="Boton-flechas Boton-flechas--down">
        <?php include __DIR__ . '/boton-scroll.php'; ?>
    </div>
    <?php endif; ?>

</div>
