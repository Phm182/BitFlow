
<div class="wrapper">
  <form id="contacto" action="validacion_contacto.php" method="post" name="formulario">
    <div class="form_contacto">
        <div class="field">
            <input type="text" id="nombre" name="nombre" placeholder="¿Cómo te llamas?" value="" autofocus/>
            <label for="nombre">Nombre</label>
        </div>
        <div class="field">
            <input type="tel" id="celular" name="celular" placeholder="Número de celular" minlength="10" value="" required>
            <label for="celular">Celular</label>
        </div>
        <div class="field">
            <input type="text" id="email" name="email" placeholder="mail@ejemplo.com" value=""/>
            <label for="email">E-Mail</label>
        </div>
        
        <div class="field">
            <textarea id="consulta" rows="4" name="consulta" placeholder="Tu mensaje..." value=""></textarea>
            <label for="consulta">Consulta</label>
        </div>
        <div class="radio">
            <div>
                <p>Seleccione metodo de respuesta:</p>
            </div>
            <div class="radio_options">
                <div >
                    <input type="radio" id="metodo_celular" name="metodo" value="celular" checked >
                    <label for="metodo_celular">Celular</label>
                </div>
            <div>
                    <input type="radio" id="metodo_email" name="metodo" value="email" >
                    <label for="metodo_email">Correo electrónico</label>
                </div>      
                <div>
                    <input type="radio" id="metodo_ambos" name="metodo" value="ambos metodos" >
                    <label for="metodo_ambos">Ambas</label>
                </div>
            </div>
                                
        </div>
        <input id="btn_enviar" class="button" name="enviar" type="submit" value="Enviar" />
    </div>
  </form>

</div>










<!-- 
    <div class="contact-section">	
		<div class="contact-close hover-target"></div>
		<div class="section-center">
			<div class="container">
				<div class="row justify-content-center respon_form">

                    <form id="consulta" action="validacion_contacto.php" method="post" name="formulario" >

                        <div class="form_contacto">
                            <div>
                                <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="" required  >
                            </div>
                            <div>
                                <input type="text" id="apellido" name="apellido" placeholder="Apellido" value="">
                            </div>
                            <div>
                                <input type="tel" id="telefono" name="telefono" placeholder="Número de celular" minlength="10" value="" required>
                            </div>
                            <div>
                                <input type="email" id="email" name="email" placeholder="Correo electrónico" value="" size="47" required>
                            </div>
                            <div>
                                <textarea  id="texto" name="texto" cols="50" rows="3" placeholder="Escribe una consulta..." maxlenght="500"></textarea>
                            </div>
                            <p>¿Por cuál medio preferis tu respuesta?</p>
                            <div class="radio">
                                <div>
                                    <input type="radio" name="metodo" value="email" checked>Correo electrónico
                                </div>
                                <div>
                                   <input type="radio" name="metodo" value="teléfono" >Teléfono
                                </div>          
                               
                            </div>

                            <div>
                                <input type="submit" name="enviar" id="btn_enviar" class="btn_enviar" value="Enviar">
                            </div>
                        </div>
                   




                    </form>







					<div class="col-12 text-center mail">
						<a href="pablo.morales7@bue.edu.ar" class="hover-target ">pablo.morales7@bue.edu.ar</a>
					</div>
					<div class="col-12 text-center social mt-4 ">
						<a href="https://www.instagram.com/pab182.ph/" class="hover-target"><i class="fab fa-instagram"></i> Instagram</a>
						<a href="#" class="hover-target"><i class="fab fa-youtube"></i> Youtube</a>
						<a href="https://www.facebook.com/pab182" class="hover-target"><i class="fab fa-facebook"></i> Facebook</a>
					</div>
				</div>
			</div>
		</div>
	</div> --> 