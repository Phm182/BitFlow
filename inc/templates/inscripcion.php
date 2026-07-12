
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
                    <input type="radio" name="metodo" value="celular" checked >
                    <label for="metodo">Celular</label>
                </div>
            <div>
                    <input type="radio" name="metodo" value="email" >
                    <label for="metodo">Correo electrónico</label>
                </div>      
                <div>
                    <input type="radio" name="metodo" value="ambos metodos" >
                    <label for="metodo">Ambas</label>
                </div>
            </div>
                                
        </div>
        <input id="btn_enviar" class="button" name="enviar" type="submit" value="Enviar" />
        <div class="mercado-pago">
            <script src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
                    data-preference-id="732569345-fca68b5d-7499-4c7f-b326-15c057c600e2">
            </script>
        </div>
        


    </div>
  </form>

</div>

