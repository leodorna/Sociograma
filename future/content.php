<main class="mdl-layout__content" style="width:100%; height:90%; margin:0">

  <div id="tabs">
  		<div>
            <br>
			
            <input type="button" value="Mapa Original" OnClick="zoomClick(0)" />
			<img src="Imagens/zoom_in.svg" class="zoom_button" OnClick="zoomClick(1.1)">
			<img src="Imagens/zoom_out.svg" class="zoom_button" OnClick="zoomClick(0.9)">
		</div>
        <img id ="interrogacao" src="Imagens/interrogacao.png" style="display:none;">
        
            
            <div id="tabs-1" style="width:100%; height:100%"></div>

            <div id="tabs-2">
                <!-- <form style="margin:0;"> -->
                    <select style="margin:0;font-size:14pt;" id="Selecao">
                        <option selected="selected" value="0">Selecionar Categorias</option>
                        <option value="5">Agrupamento</option>
                        <option value="2">Ausência</option>
                        <option value="1">Colaboração</option>
                        <option value="3">Distanciamento pela Turma</option>
                        <option value="6">Evasão</option>
                        <option value="4">Popularidade</option>
                    </select>
                 <!-- </form> -->
          		<div id="boxEstrategia" style="visibility:hidden;position:absolute;top:80;right:3;width:300;height:100;background-color:rgba(0, 0, 0, 0.4)">
                    <div id="closeBox">x</div>
                    <p id="textBox" style="color:white;font-size:16px;margin:10; font-weight: 400">
                    </p>
                </div>

                <div id="buttons" style="position:absolute; top:50; margin:0; width:100;">
                </div>
            </div>
        </div>
  <!--   </div> -->

</main>
