<head>
	<meta charset="utf-8">
	<style>
		table, th, td {
		    border: 1px solid black;
			padding: 4px;
			text-align: center;
		}
		table{
			border-collapse: collapse;
		}
		html *{
			 font-family: Arial !important;
		}
		.div-indicador{
			margin: 10px 2px 10px 2px;
		}
	</style>
</head>
<body>
	<?php
	require_once(dirname(__FILE__).'/../sistema.inc.php');
	include("Relatorio.php");

	function enviadas($dados, $funcionalidade){
		$enviadas = $dados["env".$funcionalidade];
			
		if($enviadas == NULL)
			$enviadas = 0;
		
		return $enviadas;
	}

	function recebidas($dados, $funcionalidade){
		$recebidas = $dados["rec".$funcionalidade];

		if($recebidas == NULL)
			$recebidas = 0;

		return $recebidas;
	}

	function ausencia($enviadas, $recebidas, $peso){
		if($peso == 0)
			return "Funcionalidade não selecionada";

		if($recebidas == 0 ){
			return "Não recebeu mensagens";
		}

		if($recebidas > 0 && $enviadas == 0){
			return "Ausente";
		} 
		else return "Não Ausente";
	}


	function distanciamento($enviadas, $recebidas, $peso){
		if($peso == 0)
			return "Funcionalidade não selecionada";

		if($enviadas == 0 ){
			return "Não enviou mensagens";
		}
		
		if($enviadas > 0 && $recebidas == 0){
			return "Distanciado pela turma";
		}
		else return "Não distanciado";
	}
	$relevancia = array("Não se Aplica",
						"Nada Importante",
						"Pouco Importante",
						"Relativamente Importante",
						"Muito Importante",
						"Extremamente Importante");
	$dados = array();
	foreach($_POST as $key=>$post){
		if(strpos($key, "Pop") == FALSE && $key != "grupos")
			$dados[$key] = $post;
	}

	if(isset($_POST["mediaPop"])){
		$mediaPop = number_format($_POST["mediaPop"], 2);
	}
	if(isset($_POST["mediaPopTurma"])){
		$mediaPopTurma = number_format($_POST["mediaPopTurma"], 2);
	}
	if(isset($_POST["desvioPopular"])){
		$desvioPopular = number_format($_POST["desvioPopular"], 2);
	}
	if(isset($_POST["desvioPopularTurma"])){
		$desvioPopularTurma = number_format($_POST["desvioPopularTurma"], 2);
	}
	if(isset($_POST["grupos"])){
		$grupos = $_POST["grupos"];
		$agrupamento = array();

		foreach($grupos as $grupo){
			$index = strtok($grupo,",");
			$agrupamento[$index] = strtok(",");
		}

	}

	$relatorio = new Relatorio($dados['id'], $dados['turma']);

	$relatorio->cabecalho();

	echo "<h2>INDICADORES DE INTERAÇÃO SOCIAL</h2>";
	?>
	<div class="div-indicador">
	<?php
	echo "<p><b>Colaboração:</b> indicador quantitativo de compartilhamento de informações do participante, incluindo conteúdos, arquivos, links etc.</p>";

	echo "<table>".
			"<tr>
				<th>Funcionalidade</th>
				<th>Nível de Relevância da Funcionalidade</th>
				<th>Quantidade de ítens compartilhados</th>
				<th>Indicador de Interação</th>
			</tr>".
			"<tr>
				<td>Bate-papo</td>
				<td>".$relevancia[$dados["pesoBP"]]." (".$dados["pesoBP"].")</td>
				<td>".$dados["ColabBP"]."</td>
				<td></td>
			</tr>".
			"<tr>
				<td>Fórum</td>
				<td>".$relevancia[$dados["pesoFo"]]." (".$dados["pesoFo"].")</td>
				<td>".$dados["ColabForum"]."</td>
				<td></td>
			</tr>".
			"<tr>
				<td>Biblioteca</td>
				<td>".$relevancia[$dados["pesoBib"]]." (".$dados["pesoBib"].")</td>
				<td>".$dados["ColabBib"]."</td>
				<td></td>
			</tr>".
			"<tr>
				<td>Webfólio</td>
				<td>".$relevancia[$dados["pesoWF"]]." (".$dados["pesoWF"].")</td>
				<td>".$dados["ColabWF"]."</td>
				<td></td>
			</tr>"
		  ."</table>";

	echo "<hr>";
	?>
	</div>
	<div class="div-indicador">
	<?php
	echo "<p><b>Popularidade:</b> indicar que destaca o participante que mantem uma frequência maior de interações do que o restante dos participantes da turma.</p>";

	echo "<table>".
			"<tr>
				<th>Indicador de Interação em relação aos usuários selecionados</th>
				<th>Média</th>
				<th>Desvio padrão</th>
				<th>Indicador de Interação em relação a turma</th>
				<th>Média</th>
				<th>Desvio padrão</th>
			</tr>".
			"<tr>
				<td>".$dados["popularidade"]."</td>
				<td>".$mediaPop."</td>
				<td>".$desvioPopular."</td>
				<td>".$dados["popularidadeTurma"]."</td>
				<td>".$mediaPopTurma."</td>
				<td>".$desvioPopularTurma."</td>
			</tr>"
		 ."</table>";

	echo "<hr>";
	?>
	</div>
	<div class="div-indicador">
	<?php
	echo "<p><b>Ausência:</b> refere-se ao participante que entra no ambiente, mas não retorna as mensagens enviadas pelo restante da turma.</p>";
	
	echo "<table>".
			"<tr>
				<th>Funcionalidades Selecionadas</th>
				<th>Quantidade de mensagens recebidas</th>
				<th>Quantidade de mensagens enviadas</th>
				<th>Resultado</th>
			</tr>".
			"<tr>
				<td>A2</td>
				<td>".recebidas($dados, "A2")."</td>
				<td>".enviadas($dados, "A2")."</td>
				<td>".ausencia(enviadas($dados, "A2") ,recebidas($dados, "A2"), $dados["pesoA2"])."</td>
			</tr>".
			"<tr>
				<td>Contatos</td>
				<td>".recebidas($dados, "CO")."</td>
				<td>".enviadas($dados, "CO")."</td>
				<td>".ausencia(enviadas($dados, "CO") ,recebidas($dados, "CO"), $dados["pesoCo"])."</td>
			</tr>".
			"<tr>
				<td>Fórum</td>
				<td>".recebidas($dados, "Forum")."</td>
				<td>".enviadas($dados, "Forum")."</td>
				<td>".ausencia(enviadas($dados, "Forum") ,recebidas($dados, "Forum"), $dados["pesoFo"])."</td>
			</tr>".
			"<tr>
				<td>Biblioteca</td>
				<td>".recebidas($dados, "Bib")."</td>
				<td>".enviadas($dados, "Bib")."</td>
				<td>".ausencia(enviadas($dados, "Bib") ,recebidas($dados, "Bib"), $dados["pesoBib"])."</td>
			</tr>".
			"<tr>
				<td>Webfólio</td>
				<td>".recebidas($dados, "WF")."</td>
				<td>".enviadas($dados, "WF")."</td>
				<td>".ausencia(enviadas($dados, "WF") ,recebidas($dados, "WF"), $dados["pesoWF"])."</td>
			</tr>".
			"<tr>
				<td>Bate-papo</td>
				<td>".recebidas($dados, "BP")."</td>
				<td>".enviadas($dados, "BP")."</td>
				<td>".ausencia(enviadas($dados, "BP") ,recebidas($dados, "BP"), $dados["pesoBP"])."</td>
			</tr>"
			."</table>";

	echo "<hr>";
	?>
	</div>
	<div class="div-indicador">
	<?php
	echo "<p><b>Distanciamento pela turma:</b> indica o sujeito que entra em contato com o restante da turma, mas não recebe retorno.</p>";
	
	echo "<table>".
			"<tr>
				<th>Funcionalidades Selecionadas</th>
				<th>Quantidade de mensagens recebidas</th>
				<th>Quantidade de mensagens enviadas</th>
				<th>Resultado</th>
			</tr>".
			"<tr>
				<td>A2</td>
				<td>".recebidas($dados, "A2")."</td>
				<td>".enviadas($dados, "A2")."</td>
				<td>".distanciamento(enviadas($dados, "A2") ,recebidas($dados, "A2"), $dados["pesoA2"])."</td>
			</tr>".
			"<tr>
				<td>Contatos</td>
				<td>".recebidas($dados, "CO")."</td>
				<td>".enviadas($dados, "CO")."</td>
				<td>".distanciamento(enviadas($dados, "CO") ,recebidas($dados, "CO"), $dados["pesoCo"])."</td>
			</tr>".
			"<tr>
				<td>Fórum</td>
				<td>".recebidas($dados, "Forum")."</td>
				<td>".enviadas($dados, "Forum")."</td>
				<td>".distanciamento(enviadas($dados, "Forum") ,recebidas($dados, "Forum"), $dados["pesoFo"])."</td>
			</tr>".
			"<tr>
				<td>Biblioteca</td>
				<td>".recebidas($dados, "Bib")."</td>
				<td>".enviadas($dados, "Bib")."</td>
				<td>".distanciamento(enviadas($dados, "Bib") ,recebidas($dados, "Bib"), $dados["pesoBib"])."</td>
			</tr>".
			"<tr>
				<td>Webfólio</td>
				<td>".recebidas($dados, "WF")."</td>
				<td>".enviadas($dados, "WF")."</td>
				<td>".distanciamento(enviadas($dados, "WF") ,recebidas($dados, "WF"), $dados["pesoWF"])."</td>
			</tr>".
			"<tr>
				<td>Bate-papo</td>
				<td>".recebidas($dados, "BP")."</td>
				<td>".enviadas($dados, "BP")."</td>
				<td>".distanciamento(enviadas($dados, "BP") ,recebidas($dados, "BP"), $dados["pesoBP"])."</td>
			</tr>"
			."</table>";

	echo "<hr>";
	?>
	</div>
	<div class="div-indicador">
	<?php
	echo "<p><b>Grupos informais (clusters):</b> apresenta as trocas constantes estabelecidas entre três ou mais sujeitos através das funcionalidades do ROODA, constituindo um grupo informal de contato.</p>";

	echo "<table>
			<tr>
				<th>Grupos informais</th>
				<th>Participantes</th>
			</tr>";
	foreach($agrupamento as $key=>$participantes){
		echo "<tr>
				<td>".$key."</td>
				<td>".$participantes."</td>
			  </tr>";
	}
	echo "</table>";

	?>
	</div>
</body>