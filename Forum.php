<?php
	class Forum{
		private $usuarios;
		private $turma;
		private $topicos;
		private $dataInicio;
		private $dataFim;
		private $data;
		private $interacoes;

		public __construct($usuarios, $turma, $dataInicio, $dataFim){
			$this->usuarios = $usuarios;
			$this->turma = $turma;
			$this->dataInicio = $dataInicio;
			$this->dataFim = $dataFim;
			$buscaForum= db_busca('SELECT codForum	 FROM Forum WHERE codTurma="'.$this->turma.'"');
			$this->topicos = db_busca('SELECT codTopico FROM ForumTopico WHERE codForum = "'.$buscaForum[0]['codForum'].'"');
			$this->data = array(array());
			$this->interacoes = array(array());
		}
		
		public 

		public init_data(){
			
			foreach($this->topicos as $topico)
			{
				$buscaMensagens = db_busca('SELECT codUsuario, mensagem, citou FROM ForumMensagem
												 WHERE (codTopico="'.$topico['codTopico'].'" AND
														DATE(hora) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
														ORDER BY codMensagem ASC');
							
				foreach($buscaMensagens as $mensagem)
				{
					$codUsuario = $mensagem['codUsuario'];
					$citou = $mensagem['citou'];
					if(in_array($codUsuario, $usuarios)){
						
						if(isset($this->interacoes[$codUsuario][$citou]))
							$this->interacoes[$codUsuario][$citou]++;
						else $this->interacoes[$codUsuario][$citou] = 1;

						if(intval($mensagem['citou'])==0) $this->data[$codUsuario]['topicosCriados'] += 1;
						if (strpos($mensagem['mensagem'], "<a href=")) $this->data[$codUsuario]['conteudosRelevantes'] += 1;
						if (strpos($mensagem['mensagem'], "<IMG src=")) $this->data[$codUsuario]['conteudosRelevantes'] += 1;
					}
				}
			}
		}

		private incrementaArray($array){
			
		}

		


		
	}
?>