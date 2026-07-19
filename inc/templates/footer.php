<?php if (empty($hideSiteFooter)): ?>
   <!-- Footer  -->
   <footer>
            <div class="footer contenedor2">
                <div class="contenedor-footer">
                    <div class="caja-footer ordenador">
                        <h4>Sobre <span>BitFlow</span></h4>
                        <p>
                            Somos una empresa de desarrollo de software. Creamos aplicaciones web, móviles y soluciones digitales a medida para transformar ideas en productos reales.
                        </p>
                    </div>
                    <div class="caja-footer">
                        <h4> Nuestros <span>Contactos</span></h4>
                        <div class="footer-p">
                            <div>
                                <p class="footer-contacto">Comercial: <a href="https://wa.me/5491157595207" target="_blank" rel="noopener noreferrer" class="footer-tel" aria-label="Chatear por WhatsApp con Comercial"><span>+54 9 11 5759-5207</span></a></p>
                            </div>
                            <div>
                                <p class="footer-contacto">
                                    Mail comercial: <a href="mailto:desarrolloagpit@gmail.com" style="text-decoration: none; color:white;">
                                    <span>yamila.barral@cont-app.com</span>
                                    </a>
                                </p>
                            </div>
                            <br>
                            <div>
                                <p class="footer-contacto">Técnica: <a href="https://wa.me/5491159546184" target="_blank" rel="noopener noreferrer" class="footer-tel" aria-label="Chatear por WhatsApp con Soporte técnico"><span>+54 9 11 5954-6184</span></a></p>
                            </div>
                            <div>
                                <p class="footer-contacto">
                                    Mail soporte: <a href="mailto:desarrolloagpit@gmail.com" style="text-decoration: none; color:white;">
                                    <span>pablo.morales@cont-app.com</span>
                                    </a>
                                </p>
                            </div>
                            <a href="#" class="whatsapp wa-picker-trigger" aria-label="Contactar por WhatsApp">
                                <i class="fa fa-whatsapp whatsapp-icon"></i>
                            </a>
                        </div>
                    </div>
                    <div class="caja-footer">
                    <h4>Redes <span>Sociales</span></h4>
                    <nav class="nav-redes">
                        <a href="" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </nav>
                    </div>
                </div>
                <div class="copyright">
                    <p>Diseño Web: BitFlow</p>
                    <p>Todos los derechos Reservados BitFlow 2026 ®</p>
                </div>
            </div>
        </footer>
<?php else: ?>
        <a href="#" class="whatsapp wa-picker-trigger" aria-label="Contactar por WhatsApp">
            <i class="fa fa-whatsapp whatsapp-icon"></i>
        </a>
<?php endif; ?>

        <?php include __DIR__ . '/whatsapp-picker.php'; ?>

        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>         
        <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
        <script src="js/lightbox.js"></script>
        <script src="js/main.js"></script>
        <script src="js/whatsapp.js"></script>
<?php if (!empty($extraScripts) && is_array($extraScripts)): ?>
<?php foreach ($extraScripts as $scriptSrc): ?>
        <script src="<?php echo htmlspecialchars($scriptSrc); ?>"></script>
<?php endforeach; ?>
<?php endif; ?>


    </body>
</html>