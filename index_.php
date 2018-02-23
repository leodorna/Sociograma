<?php
// Recebe as páginas
require_once(dirname(__FILE__).'/../sistema.inc.php');

$pagina=new Pagina();
$pagina->add_js('sociograma.js?t='.time());
$pagina->add_css('sociograma.css?t='.time());
$pagina->cabecalho('MapaSocial');
?>


<div class="sociograma">
	<?php
	$codTurma=intval($pagina->sessao->codTurma);
	if($codTurma===0)
		echo('	<p>Nenhuma turma selecionada.</p>');
	else{
		?>
		<form action="./mostraGrafo.php" method="post">
			<p>Defina o per�odo e os membros da turma que deseja analisar:</p>

			<p>
				<fieldset>
				<legend>Período</legend>
					<table style="font-size: 10px; border-spacing: 5px;">
						<td>Data de início: <input name="dataInicio" type="text" placeholder="01/01/2000" class="fonteFormatacao"></td>
						<td style="padding-left:10px">Data de fim: <input name="dataFim" type="text" placeholder="31/12/2099" class="fonteFormatacao"></td>
					</table>
				</fieldset>
			</p>


			<p>
				<fieldset>
				<legend>Cores</legend>
					<table style="font-size: 10px; border-spacing: 5px;">
						<td>Aluno:
						<?php
							imprimeCores(Aluno);
						?>
						</td>

						<td style="padding-left:30px":>Professor:
						<?php
							imprimeCores(Professor);
						?>
						</td>

						<td style="padding-left:30px":>Monitor:
						<?php
							imprimeCores(Monitor);
						?>
						</td>
					</table>
				</fieldset>
			</p>

			<fieldset>
				<legend>N�vel de relev�ncia</legend>
				<table style="font-size: 10px; border-spacing: 5px;">
					<tr>
						<td>Contatos:</td><td><?php imprimeNivel_Relevancia(contatos);?></td>
						<td style="padding-left:30px">Bate-papo:</td><td><?php imprimeNivel_Relevancia(batepapo);?></td>
					</tr>
					<tr>
						<td>F�rum:</td><td><?php imprimeNivel_Relevancia(forum);?></td>
						<td style="padding-left:30px">Biblioteca (coment�rios):</td><td><?php imprimeNivel_Relevancia(biblioteca);?></td>
					</tr>
					<tr>
						<td>A2:</td><td><?php imprimeNivel_Relevancia(a2);?></td>
					<td style="padding-left:30px">Webf�lio (coment�rios):</td><td><?php imprimeNivel_Relevancia(webfolio);?></td>
					</tr>
					</fieldset>
				</table>
			</fieldset>

			<p>
				<fieldset>
					<legend>Membros da turma</legend>
					<a href="javascript:void(0)" onclick="marcaMembros()">Marcar todos</a> | <a href="javascript:void(0)" onclick="desmarcaMembros()">Desmarcar todos</a> | <a href="javascript:void(0)" onclick="marcaProfessores()">Marcar professores</a> | <a href="javascript:void(0)" onclick="marcaMonitores()">Marcar monitores</a> | <a href="javascript:void(0)" onclick="marcaAlunos()">Marcar alunos</a> | <a href="javascript:void(0)" onclick="mostra_grupos()">Grupos</a><br>
					<?php
					$pesquisaMembros=db_busca('	SELECT tu.codUsuario,tu.associacao,u.nome
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
					echo('<div id="div_alunos" title="imprime_alunos"');
					foreach($dadosMembros as $codUsuario => $membro){
						switch($membro['associacao']){
							case 'P':
								$associacao='Professor';
								$checked='';
								break;
							case 'M':
								$associacao='Monitor';
								$checked='';
								break;
							case 'A':
							default:
								$associacao='Aluno';
								$checked='checked';
								break;
						}
						echo('<label><input type="checkbox" name="checkboxMembros[]" value="'.$codUsuario.'" class="'.$membro['associacao'].'" '.$checked.'>'.$membro['nome'].' ('.$associacao.')</label><br>');
					}
					echo('</div>');


					$pesquisaGrupos=db_busca('SELECT * FROM Producao WHERE codTurma="'.$codTurma.'" ORDER BY nome ASC');
					echo('<div id="div_grupos" style="display:none" title="imprime_grupos"');
					foreach($pesquisaGrupos as $Grupo){
						$codGrupo=intval($Grupo['codProducao']);
						echo('<label><input type="checkbox" name="checkboxGrupos[]" value="'.$codGrupo.'" >'.$Grupo['nome'].'</label><br>');
						}
					echo('</div>');

					/*<label>
					<input type="radio" name="layout" value="neato" checked>Neato</label>
					*/
					?>
				</fieldset>
			</p>
			<p><input type="submit" value="Analisar"></p>
		</form>
		<?php
	}
	?>
</div>
<?php
function imprimeCores($tipo){
	echo('<select  STYLE="font-size:8pt" name="cor'.$tipo.'">
			<option value="fcfcfc">Branco</option>
			<option value="020202">Preto</option>
			<option value="38bc48"');
			if ($tipo=='Monitor')
				echo ' selected';
			echo('>Verde</option>
			<option value="3395FF"');
			if ($tipo=='Aluno')
				echo ' selected';
			echo ('>Azul</option>
			<option value="ffe900">Amarelo</option>
			<option value="a999cc">Violeta</option>
			<option value="ff0022">Vermelho</option>
			<option value="ff8000"');
			if ($tipo=='Professor')
				echo ' selected';
			echo('>Laranja</option>
			<option value="964215">Marrom</option>
		  </select>');
}

function imprimeNivel_Relevancia($tipo_rel){
	echo('<select  STYLE="font-size:8pt" name="rel'.$tipo_rel.'">
			<option value="0">Não se aplica</option>
			<option value="1">Nada importante</option>
			<option value="2">Pouco importante</option>
			<option value="3">Relativamente importante</option>
			<option value="4">Muito importante</option>
			<option value="5" selected>Extremamente importante</option>
		  </select>');
}

$pagina->rodape();
?>
