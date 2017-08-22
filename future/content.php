
<main class="mdl-layout__content">

    <div id="tabs">
        <img id ="interrogacao" src="Imagens/interrogacao.png" style="display:none;">
        <div id="tabs-1" style="width:100%; height:100%"></div>

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
            <div id="boxEstrategia"style="visibility:hidden;position:absolute;top:80;right:3;width:300;height:100;background-color:gba(0, 0, 0, 0.4)">
                <div id="closeBox">x</div>
                <p id="textBox" style="color:white;font-size:16px;margin:10; font-weight: 400">
                </p>
            </div>

            <div id="buttons" style="position:absolute; top:350; left:30; width: 20; height:400;">
            </div>
        </div>
    </div>

</main>
