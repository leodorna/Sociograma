<div id="tabs" style="background-color: #dedede">
    <img id ="interrogacao" src="Imagens/interrogacao.png" style="display:none;">
    <!-- <ul>
        <li id="mapInt" class="mdl-button mdl-js-button  mdl-button--colored"><a href="#tabs-1">Mapa Interações</a></li>
        <li id="mapCat" class="mdl-button mdl-js-button  mdl-button--colored"><a href="#tabs-2">Mapa Categorias</a></li>
    </ul>

    <div>
        <br>
        <input type="button" value="Aumentar Mapa" OnClick="zoomClick(1.1)" />
        <input type="button" value="Diminuir Mapa" OnClick="zoomClick(0.9)" />
        <input type="button" value="Mapa Original" OnClick="zoomClick(0)" />
        <button name="button" OnClick="zoomClick(1.1)"> coisas </button>
    </div> -->
    <div id="tabs-1" style="width:100%; height:100%">

    </div>
    <div id="tabs-2" style="padding:4pt">
        <form style="margin:0;">
            <select style="margin:0;font-size:14pt;" id="Selecao">
                <option value="0">Todas</option>
                <option value="5">Agrupamento</option>
                <option value="2">Ausência</option>
                <option value="1">Colaboração</option>
                <option value="3">Distanciamento pela Turma</option>
                <option value="6">Evasão</option>
                <option value="4">Popularidade</option>
            </select>
        </form>
        <div id="boxEstrategia" style="visibility:hidden;position:absolute;top:80;right:3;width:300;height:100;background-color:rgba(0, 0, 0, 0.4)">
            <div id="closeBox">x</div>
            <p id="textBox" style="color:white;font-size:16px;margin:10; font-weight: 400">
            </p>
        </div>
            <div id="buttons" style="position:absolute; top:350; left:30; width: 20; height:400;">
            </div>
    </div>
</div>
