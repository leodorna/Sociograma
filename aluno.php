<?php

/*
require_once('../sistema.inc.php');

$codUsuario = $_GET['cod_aluno'];

$nome = "SELECT nome from Usuario where codUsuario = ".$codUsuario;

$resultado = db_busca($nome);

nome_aluno = $resultado[0]["nome"];
*/
?>

<HTML>
<HEAD><TITLE>Aluno</TITLE>

<SCRIPT Language="Javascript" src="funcoesMenu.js"></SCRIPT>


<?php

	require_once('../sistema.inc.php');
	$codUsuario = $_GET['cod_aluno'];
	$nome = "SELECT nome from Usuario where codUsuario = ".$codUsuario;
	$resultado = db_busca($nome);
	$nome_aluno = $resultado[0]["nome"];
	
	


	require_once('../sistema.inc.php');
	require_once('../gerais.inc.php');

	$pagina = new Pagina();

	$idUser = $pagina->sessao->codUsuario;
	$imagesPath = $pagina->skin_loc;
	$codTurma=intval($_GET["codTurma"]);	
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?=$imagesPath?>/principal/aba.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>

<?php
		//Mostra somente as pastas da turma selecionada
		$consultaPastas = db_busca('select * from BibliotecaPasta where codSubPasta=0 and codTurma='.$codTurma);

		echo("<form action='gravaPasta.php' method='POST'>
		<TABLE WIDTH=50% style='margin=10px;'>
		<TR><TH COLSPAN='2' align='center' class='bg0' style='font-size=12px;'>Intera&ccedil&otildees</TH></TR><br>
		<TD class='bg0'>Nome do Aluno: </TD><TD class='bg0' size='60'>$nome_aluno</TD></TR>
		<TR><TD class='bg0'>F&oacuterum: </TD><TD class='bg0' size='60'></TD></TR>
		");
		
		/*echo('<TD class="bg0" ><select class="fontes" name="subPastaCombo" id="subPastaCombo">
			  <option value="0" selected>Nenhuma</option>');
		foreach($consultaPastas as $pasta){
			echo('<option value="'.$pasta['codPasta'].'">'.$pasta['nomePasta'].'</option>');
		}
		echo("</select>");
		
		echo("</TR></TD><TR><TD class='bg0'></TD><TD class='bg0' WIDTH='10' align='right'><INPUT type=image src='$imagesPath/botoes/criar.gif'></TD></TR>
		<input type='hidden' name='codTurma' value=".$codTurma.">
		</FORM>
		</TABLE>");*/		
?>