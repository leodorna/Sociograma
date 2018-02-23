
<?php
	require_once(dirname(__FILE__).'/../sistema.inc.php'); 
	include("Settings.php");

	class Relatorio{
		private $usuario;
		private $turma;
		private $interacoes;

		public function __construct($id, $turma){
			$this->usuario = $id;
			$this->turma = $turma;
			$this->get_turma();
			$this->interacoes = array();
		}

		public function cabecalho(){

			$date = getdate();
			$day = sprintf("%02d", $date['mday']);
			$month = sprintf("%02d", $date['mon']);

			$pesquisaTurma = db_busca('SELECT codDisciplina, nomeTurma FROM Turma WHERE codTurma="'.$this->turma.'"');
			$pesquisaDisciplina = db_busca('SELECT nomeDisciplina FROM Disciplina WHERE codDisciplina="'.$pesquisaTurma[0]['codDisciplina'].'"');

			echo "<h2>Identificação</h2>";
			echo "<p>Aluno: ".$this->get_username()."</p>";
			echo "<p>Atividade de Ensino: ".$pesquisaDisciplina[0]['nomeDisciplina']."</p>";
			echo "<p>Turma: ".$pesquisaTurma[0]['nomeTurma']."</p>";
			echo "<p>Relatório gerado em: ".$day."/".$month."/".$date["year"]."</p>";
			echo "<hr>";
		}

		public function interacoes($dataInicio, $dataFim){
			$this->getInteracoesContatos($dataInicio, $dataFim);
			$this->getInteracoesBatePapo($dataInicio, $dataFim);
			$this->getInteracoesBiblioteca($dataInicio, $dataFim);
			$this->getInteracoesWebfolio($dataInicio, $dataFim);
			$this->getInteracoesA2($dataInicio, $dataFim);
			$interacoes_forum = $this->getInteracoesForum($dataInicio, $dataFim);

			foreach($this->interacoes as $funcionalidade=>$interacao){
				$total = 0;
				echo "<h3>Interações em ".$funcionalidade.":</h3>";
				if(count($interacao) > 0)
					foreach($interacao as $usuario=>$quantidade){
						if(isset($this->usuariosTurma[$usuario]))
							echo "<p>".$this->usuariosTurma[$usuario].": ".$quantidade."</p>";
						$total += intval($quantidade);
					}
				else{
					echo "<p>Nenhuma interação</p>";
				}

				echo "<br><p>TOTAL: ".$total."</p>";
				echo "<hr>";
			}

			echo "<h3>Interações em Fórum:</h3>";
			foreach($interacoes_forum as $interacoes){
				if(!($this->estaNaTurma($interacoes['enviou']) && $this->estaNaTurma($interacoes['recebeu']))){
					continue;
				}
				if($interacoes['enviou'] != $interacoes['recebeu']){
					echo '<div style="margin: 30 0 30 10;">';
					echo '<p>'.$this->usuariosTurma[$interacoes['enviou']].' enviou para '.$this->usuariosTurma[$interacoes['recebeu']].'</p>';
					echo '<p style="padding-left: 15px; margin-top: 10px;">'.$interacoes['mensagem'].'</p>';
					echo '</div>';	
				}
				
			}
		}

		private function getInteracoesContatos($dataInicio, $dataFim){
			$func = 'Contatos';
			$this->interacoes[$func] = array();

			$interacoes = db_busca(' SELECT c_mensagem.id_remetente, c_mensagem_destinatario.id_destinatario FROM c_mensagem INNER JOIN c_mensagem_destinatario ON c_mensagem.id_mensagem = c_mensagem_destinatario.id_mensagem WHERE (id_turma="'.$this->turma.'" AND (c_mensagem.id_remetente="'.$this->usuario.'" OR c_mensagem_destinatario.id_destinatario ="'.$this->usuario.'") AND DATE(c_mensagem.data_hora) BETWEEN "'.$dataInicio.'" AND "'.$dataFim.'")');
			

			$this->count_interacao($interacoes, $func, 'id_remetente', 'id_destinatario');
			
		}

		private function getInteracoesBatePapo($dataInicio, $dataFim){
			$func = 'Bate-papo';
			$this->interacoes[$func] = array();

			$interacoes = db_busca('SELECT BatePapoMensagem.codUsuario, BatePapoMensagem.destino FROM BatePapoMensagem INNER JOIN BatePapoSala ON BatePapoMensagem.codSala = BatePapoSala.codSala WHERE (BatePapoSala.codTurma = '.$this->turma.' AND (BatePapoMensagem.codUsuario = '.$this->usuario.' OR BatePapoMensagem.destino ='.$this->usuario.') AND DATE(quando) BETWEEN "'.$dataInicio.'" AND "'.$dataFim.'")');

			$this->count_interacao($interacoes, $func, 'codUsuario', 'destino');
		}

		private function getInteracoesBiblioteca($dataInicio, $dataFim){
			$func = 'Biblioteca';
			$this->interacoes[$func] = array();

			$interacoes = db_busca('SELECT BibliotecaMaterial.codUsuario as recebeu, BibliotecaComentarios.codUsuario as enviou FROM BibliotecaMaterial INNER JOIN BibliotecaComentarios ON BibliotecaMaterial.codMaterial = BibliotecaComentarios.codMaterial WHERE (BibliotecaMaterial.codTurma = '.$this->turma.' AND (BibliotecaMaterial.codUsuario = '.$this->usuario.' OR BibliotecaComentarios.codUsuario ='.$this->usuario.') AND DATE(BibliotecaComentarios.data) BETWEEN "'.$dataInicio.'" AND "'.$dataFim.'")');

			$this->count_interacao($interacoes, $func, 'enviou', 'recebeu');
		}

		private function getInteracoesWebfolio($dataInicio, $dataFim){
			$func = 'Webfolio';
			$this->interacoes[$func] = array();

			$interacoes = db_busca('SELECT WFArquivo.codUsuario as recebeu, WFComentario.codUsuario as enviou FROM WFArquivo INNER JOIN WFComentario ON WFArquivo.codArquivo = WFComentario.codArquivo WHERE (WFArquivo.codTurma = '.$this->turma.' AND (WFArquivo.codUsuario = '.$this->usuario.' OR WFComentario.codUsuario ='.$this->usuario.') AND DATE(WFComentario.quando) BETWEEN "'.$dataInicio.'" AND "'.$dataFim.'")');

			$this->count_interacao($interacoes, $func, 'enviou', 'recebeu');
		}

		private function getInteracoesA2($dataInicio, $dataFim){
			$func = 'A2';
			$this->interacoes[$func] = array();
			$str_users_turma = implode(', ', array_keys($this->usuariosTurma)); 
		
			$interacoes = db_busca('SELECT codUsuario1, codUsuario2 FROM A2 WHERE (codUsuario1 IN ('.$str_users_turma.') AND codUsuario2 = '.$this->usuario.') OR (codUsuario1 = '.$this->usuario.' AND codUsuario2 IN ('.$str_users_turma.')) AND DATE(quando) BETWEEN '.$dataInicio.' AND '.$dataFim.'');
			
			$this->count_interacao($interacoes, $func, 'codUsuario1', 'codUsuario2');
		}

		private function getInteracoesForum($dataInicio, $dataFim){
			/*$func = 'Forum';
			$this->interacoes[$func] = array();
			*/

			$topicos = db_busca('SELECT ForumTopico.codTopico FROM ForumTopico INNER JOIN Forum ON ForumTopico.codForum = Forum.codForum WHERE Forum.codTurma='.$this->turma.'');

			$codsTopico = array();
			foreach($topicos as $topico){
				array_push($codsTopico, $topico['codTopico']);
			}
			
			if(!empty($codsTopico)){
				$mensagensUsuario = db_busca('SELECT codUsuario,citou,codMensagem FROM ForumMensagem WHERE ( codTopico IN ('.implode(', ', $codsTopico).') AND DATE(hora) BETWEEN "'.$dataInicio.'" AND "'.$dataFim.'" AND codUsuario='.$this->usuario.')');
			}

			$usuarioCitou = array();
			$usuarioMensagem = array();
			$usuarioRecebeu = array();
			foreach($mensagensUsuario as $mensagem){
				if($mensagem['citou'] != 0)
					array_push($usuarioCitou, $mensagem['citou']);
				array_push($usuarioMensagem, $mensagem['codMensagem']);
				
			}
			if(!empty($usuarioMensagem)){
				$usuarioRecebeu = db_busca('SELECT codUsuario, mensagem FROM ForumMensagem WHERE citou IN('.implode(', ',$usuarioMensagem).')');
			}
			
			$interacoes = array();

			//pega todas as mensagens que o usuário clicado enviou para alguém
			foreach($usuarioCitou as $citou){
				$user = db_busca('SELECT codUsuario, mensagem FROM ForumMensagem WHERE codMensagem ='.$citou.'');
				array_push($interacoes, array('enviou'=>$this->usuario, 'recebeu'=>$user[0]['codUsuario'], 'mensagem'=>$user[0]['mensagem']));
			}

			foreach($usuarioRecebeu as $recebeu){
				array_push($interacoes, array('enviou'=>$recebeu['codUsuario'], 'recebeu'=>$this->usuario, 'mensagem'=>$recebeu['mensagem']));
			}

			return $interacoes;
		}

		private function count_interacao($interacoes, $funcionalidade, $enviou, $recebeu){

			foreach($interacoes as $mensagem){
				$userR = $mensagem[$recebeu];
				$userE = $mensagem[$enviou];

				if(!($this->estaNaTurma($userR) && $this->estaNaTurma($userE))){
					continue;
				}
				if($userE == $this->usuario){
					if(isset($this->interacoes[$funcionalidade][$userR]))
						$this->interacoes[$funcionalidade][$userR] += 1;
					else
						$this->interacoes[$funcionalidade][$userR] = 1;
				}
				elseif($userR == $this->usuario){
					if(isset($this->interacoes[$funcionalidade][$userE]))
						$this->interacoes[$funcionalidade][$userE] += 1;
					else
						$this->interacoes[$funcionalidade][$userE] = 1;
				}
			}

			uasort($this->interacoes[$funcionalidade], 'cmp');

		}

		private function estaNaTurma($user){
			if(isset($this->usuariosTurma[$user]))
				return True;
			else 
				return False;
		}

		private function get_turma(){
			$turma = db_busca('SELECT tu.codUsuario, u.nome FROM (SELECT codUsuario FROM TurmaUsuario WHERE codTurma="'.$this->turma.'") AS tu INNER JOIN Usuario AS u ON tu.codUsuario=u.codUsuario');

			foreach($turma as $usuario){
				if(strlen($usuario['nome']) > 1)
					$this->usuariosTurma[$usuario['codUsuario']] = $this->format_name($usuario['nome']);
			}
		}

		private function format_name($name){
			$name = strtolower($name);
			$name[0] = strtoupper($name[0]);
			for($i = 1; $i < strlen($name); $i++){
				if($name[$i] == ' ')
					$name[$i+1] = strtoupper($name[$i+1]);
			}
			return $name;
		}

		private function get_username(){
			$nome=db_busca('SELECT nome FROM Usuario WHERE codUsuario = '.$this->usuario.'');

			return $this->format_name($nome[0]['nome']); 
		}
	}

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}

?>