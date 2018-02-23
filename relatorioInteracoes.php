<meta charset="utf-8">
<head>
	<style>
		#content{
			line-height: 3px;
			text-align: justify;
		}
		html *{
			 font-family: Arial !important;
		}
	</style>
</head>
<body>
	<?php 
		include("Relatorio.php");
		$relatorio = new Relatorio($_POST['id'], $_POST['turma']);
	?>
	<div id="cabecalho">
		<?php	
			$relatorio->cabecalho();
		?>
	</div>
	<div id="content">
		<?php
			$relatorio->interacoes('2000-01-01', '2099-12-31');
		?>
	</div>
	<!-- <h3>Usuários que tiveram interações com <?php echo format_name($_POST['nome']); ?></h3>
	<?php
		require_once(dirname(__FILE__).'/../sistema.inc.php'); //necessário para incluir a função db_busca.
		
		if(isset($_POST['interacoes'])){
			$interacoes_str = stripslashes($_POST['interacoes']);
			$interacoes = json_decode($interacoes_str, true);
		}

		$interacoes_usuario = array();

		foreach($interacoes as $key=>$value){
			if($value['target'] == $_POST['id']){
				array_push($interacoes_usuario, $value['source']);
			}
			if($value['source'] == $_POST['id']){
				array_push($interacoes_usuario, $value['target']);
			}
		}

		$nomes_interacoes=db_busca('	SELECT nome
									FROM Usuario
									WHERE codUsuario IN ('.implode(", ", $interacoes_usuario).')');

		foreach($nomes_interacoes as $interacao){
			echo format_name($interacao['nome'])."<br>";
		}

		function format_name($name){
			$name = strtolower($name);
			$name[0] = strtoupper($name[0]);
			for($i = 1; $i < strlen($name); $i++){
				if($name[$i] == ' ')
					$name[$i+1] = strtoupper($name[$i+1]);
			}
			return $name;
		}
	?> -->
</body>