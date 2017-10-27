  <!-- The drawer is always open in large screens. The header is always shown,
  even in small screens. -->

 <meta charset="UTF-8">

<header class="mdl-layout__header top-shadow inset-shadow">
    <div class="mdl-layout__header-row">
        <div class="mdl-layout-spacer"></div>
        <div class="content-grid mdl-grid">
            <div class="mdl-cell mdl-cell--6-col mdl-cell--middle no-margin">
                <div class="place-box">

                    <span><a href="#"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a></span>

                    <h3>Mapa social</h3>
                </div>
                
                <a id="mapInt" class="map-select active" href="#"><span>Interações Sociais</span></a>
                <a id="mapCat" class="map-select" href="#"><span>Indicadores de Interação Social</span></a>
            
            </div>
            <div class="mdl-cell mdl-cell--6-col mdl-textfield--align-right mdl-cell--middle">
                <p class="no-margin"><a href="#" id="config-drawer"><i class="fa fa-cog" aria-hidden="true"></i></a></p>
            </div>
        </div>
    </div>
    <script src = "sociograma.js"></script>
</header>

<div style="padding:10px;" class="mdl-layout__drawer drawer-space right-drawer not-visible">
    <span class="mdl-layout-title">Configurações</span>

    <nav class="mdl-navigation">
        <form action="./mostraGrafo.php" method="post">
            <h3>Período</h3>
            <div>
                <div class = "mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="datainicio">
    				<label class="mdl-textfield__label" for="datainicio">Data de início</label>
  				</div>

                <div class = "mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="datafim">
                    <label class="mdl-textfield__label" for="datafim">Data de fim</label>
                </div>
                
            </div>

            <h3>Cores</h3>
            
            <div style="font-size: 12px;">
                <spam>Aluno:</spam><br>
                <?php
                    imprimeCores(Aluno);
                ?>

                <spam>Professor:</spam><br>
                <?php
                    imprimeCores(Professor);
                ?>

                <spam>Monitor:</spam><br>
                <?php
                    imprimeCores(Monitor);
                ?>
            </div>
            <h3>Nível de relevância</h3>
            <div style="font-size: 12px; border-spacing: 5px;">
                <div>
                	<spam>Contatos:</spam><br>
                    <?php imprimeNivel_Relevancia(contatos);?>
                </div>
                <div>
                	<spam>Bate-papo:</spam><br>
                    <?php imprimeNivel_Relevancia(batepapo);?>
                </div>
            	<div>
            		<spam>Fórum:</spam><br>
                	<?php imprimeNivel_Relevancia(forum);?>
                </div>
                <div>
                	<spam>Biblioteca:</spam><br>
                	<?php imprimeNivel_Relevancia(biblioteca);?>
                </div>
               	<div>
               		<spam>A2:</spam><br>
               		<?php imprimeNivel_Relevancia(a2);?>
               	</div>
               	<div>
               		<spam>Webfólio:</spam><br>
                	<?php imprimeNivel_Relevancia(webfolio);?>
                </div>
            </div>
            <div>
                <h3>Membros da turma</h3>
                <a href="javascript:void(0)" onclick="marcaMembros()">Marcar todos</a> | <a href="javascript:void(0)" onclick="desmarcaMembros()">Desmarcar todos</a> | <a href="javascript:void(0)" onclick="marcaProfessores()">Marcar professores</a> | <a href="javascript:void(0)" onclick="marcaMonitores()">Marcar monitores</a> | <a href="javascript:void(0)" onclick="marcaAlunos()">Marcar alunos</a> | <a href="javascript:void(0)" onclick="mostra_grupos()">Grupos</a><br>
            </div>

            <?php
            $id = 0;

            $pesquisaMembros=db_busca(' SELECT tu.codUsuario,tu.associacao,u.nome
                                        FROM
                                            (SELECT codUsuario,associacao
                                            FROM TurmaUsuario
                                            WHERE codTurma="'.$codTurma.'")
                                            AS tu
                                        INNER JOIN
                                            Usuario
                                            AS u
                                        ON tu.codUsuario=u.codUsuario
                                        ORDER BY u.nome ASC');
            $dadosMembros=array();

            foreach($pesquisaMembros as $membro){
                $codUsuario=intval($membro['codUsuario']);
                $dadosMembros[$codUsuario]['codUsuario']=$codUsuario;
                $dadosMembros[$codUsuario]['nome']=$membro['nome'];
                $dadosMembros[$codUsuario]['associacao']=$membro['associacao'];
            }   
            echo('<div id="div_alunos" title="imprime_alunos">');
            echo('<ul class="demo-list-control mdl-list">');
            if($_POST['checkboxMembros'])
            	$default = array('P'=>'', 'A'=>'', 'M'=>'');
            else
            	$default = array('P'=>'', 'A'=>'checked', 'M'=>'');
            
            foreach($dadosMembros as $codUsuario => $membro){

            	if(in_array($codUsuario, $_POST['checkboxMembros']))
                   	$checked = 'checked';
                else
                   	$checked=$default[$membro['associacao']];

                switch($membro['associacao']){
                    case 'P':
                        $associacao='Professor';
                        break;
                    case 'M':
                        $associacao='Monitor';
                        break;
                    case 'A':
                    default:
                        $associacao='Aluno';
                        break;
                }
                echo('<li class="mdl-list__item">
                		<span class="mdl-list__item-primary-content">'.$membro['nome'].' ('.$associacao.')</span>
                		<span class="mdl-list__item-secondary-action">
                			<label id="label'.$id.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-checkbox-'.$id.'">
                				<input type="checkbox" id="list-checkbox-'.$id.'" name="checkboxMembros[]" value="'.$codUsuario.'" class="'.$membro['associacao'].' mdl-checkbox__input" '.$checked.'>
                			</label>
                		</span>
                		</li>');
                $id++;
            }
            echo('</ul>');
            echo('</div>');

            $pesquisaGrupos=db_busca('SELECT * FROM Producao WHERE codTurma="'.$codTurma.'" ORDER BY nome ASC');
            $id = 0;
            echo('<div id="div_grupos" style="display:none" title="imprime_grupos">');
            echo('<ul class="demo-list-control mdl-list">');
            foreach($pesquisaGrupos as $Grupo){
                $codGrupo=intval($Grupo['codProducao']);
                //echo('<label><input type="checkbox" name="checkboxGrupos[]" value="'.$codGrupo.'" >'.$Grupo['nome'].'</label><br>');
                echo('<li class="mdl-list__item">
                        <span class="mdl-list__item-primary-content">'.$Grupo['nome'].'</span>
                        <span class="mdl-list__item-secondary-action">
                            <label id="grupo'.$id.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="group-checkbox-'.$id.'">
                        <input type="checkbox" id="group-checkbox-'.$id.'" name="checkboxGrupos[]" value="'.$codGrupo.'" class="mdl-checkbox__input">
                      </label>');
                $id++;
            }
            echo('</ul>');
            echo('</div>');
            ?>

            <button type="submit" style="background-color: #2180D3;" value="Analisar" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
  				Analisar
			</button>
        </form>
    </nav>
</div>

<?php
function imprimeCores($tipo){
    $cores = array('fcfcfc'=>'Branco', '020202'=>'Preto', '38bc48'=>'Verde', '3395FF'=>'Azul',
                   'ffe900'=>'Amarelo', 'a999cc'=>'Violeta', 'ff0022'=>'Vermelho', 'ff8000'=>'Laranja', '964215'=>'Marrom');

    $coresDefault = array('Aluno'=>'3395FF', 'Monitor'=>'38bc48', 'Professor'=>'ff8000'); 

    if($_POST['cor'.$tipo])
        $postData = $_POST['cor'.$tipo];
    else
        $postData = $coresDefault[$tipo];

    echo('<select class="soflow" STYLE="font-size:8pt" name="cor'.$tipo.'">');

    foreach($cores as $hexCode=>$cor){
        if($postData == $hexCode)
            echo '<option selected="selected" value='.$hexCode.'>'.$cor.'</option>';
        else
            echo '<option value='.$hexCode.'>'.$cor.'</option>';
    }
           
    echo('</select>');
}

function imprimeNivel_Relevancia($tipo_rel){
    $options = array("Não se aplica", "Nada importante", "Pouco importante", "Relativamente importante", "Muito importante", "Extremamente importante");
    $postData = $_POST["rel".$tipo_rel];
    
    
    echo('<select  class="soflow" STYLE="font-size:8pt" name="rel'.$tipo_rel.'">');

    foreach($options as $key => $option){
        if($key == $postData)
            echo '<option selected="selected" value="'.$key.'">'.$option.'</option>';
        else
            echo "<option value='".$key."'>".$option."</option>";
    }
    echo('</ul>');    
    echo('</select>');
}
?>
<!-- <div class="mdl-layout__obfuscator"></div> -->
