<?php
    $splashMode = isset($splashMode) ? $splashMode : 'full';
    $showSplashButton = isset($showSplashButton) ? $showSplashButton : false;
    $showScrollButton = isset($showScrollButton) ? $showScrollButton : false;
    $scrollTarget = isset($scrollTarget) ? $scrollTarget : '#section-quienes';
?>
<div class="LetrasLogo" data-mode="<?php echo htmlspecialchars($splashMode); ?>">

    <div class="splash-hero-classic">
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
    </div>

    <?php if ($splashMode === 'continue'): ?>
    <div class="splash-hero-split" aria-hidden="true">
        <div class="splash-split__left">
            <span class="splash-split__brand">BitFlow</span>
        </div>
        <div class="splash-split__right">
            <p class="splash-split__line">Desarrollo de Software</p>
            <p class="splash-split__line splash-split__line--sub">Soporte técnico</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($showScrollButton): ?>
    <div class="Boton-flechas Boton-flechas--down">
        <?php include __DIR__ . '/boton-scroll.php'; ?>
    </div>
    <?php endif; ?>

</div>
