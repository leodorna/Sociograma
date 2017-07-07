

<?php
define('LAYOUT_PADRAO','neato');
define('DATA_INICIO_PADRAO','2000-01-01');
define('DATA_FIM_PADRAO','2099-12-31');
define('VALORMAX_ARESTA',6);  //define o limite da aresta


include("Popularidade.php");
include("Grupo.php");

class Sociograma{
	private $codUsuario;
	private $codTurma;
	private $layout;
	private $directed;
	private $dataInicio;
	private $dataFim;
	private $dadosMembros;
	private $imageGraphviz;
	private $arrayInteracoes;
	private $corAluno;
	private $corProfessor;
	private $corMonitor;
	private $interacaoContatos;
	private $interacaoBatepapo;
	private $interacaoForum;
	private $interacaoBiblioteca;
	private $interacaoA2;
	private $interacaoWebfolio;
	private $arrayIsolados;
	private $alunosTurma;
	public function __construct($codUsuario,$codTurma,$layout,$directed,$dataInicio,$dataFim,$corAluno,$corProfessor,$corMonitor,$interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio,$arrayMembros=NULL,$arrayGrupos=NULL)
	{
		$this->codUsuario=$codUsuario;
		$this->codTurma=$codTurma;
		$this->layout=$layout;
		$this->directed=$directed;
		$this->defineDirected();
		$this->ajustaDatas($dataInicio,$dataFim);
		$this->interacaoContatos=$interacaoContatos;
		$this->interacaoBatepapo=$interacaoBatepapo;
		$this->interacaoForum=$interacaoForum;
		$this->interacaoBiblioteca=$interacaoBiblioteca;
		$this->interacaoA2=$interacaoA2;
		$this->interacaoWebfolio=$interacaoWebfolio;
		$this->membros = $arrayMembros;
		$this->alunosTurma = array();
		$this->dadosColabora = array(array());


		$this->dadosMembros=array(array());
		if(!empty($arrayMembros))
			$this->pegaDadosMembros($arrayMembros);

		 if(!empty($arrayGrupos))
		 {
		 	if($arrayMembros != NULL)
		 		$i = sizeof($arrayMembros);
		 	else{
		 		$i = 0;	
		 		$arrayMembros = array();
		 	} 
			$cont = 0;

			foreach($arrayGrupos as $Grupos)
			{
				$codProducao = intval($arrayGrupos[$cont]);
				$selecionaPart = db_busca("SELECT U.nome, U.cracha, U.codUsuario, PU.associacao
									FROM ProducaoUsuario as PU
									LEFT JOIN Usuario as U USING(codUsuario)
									WHERE PU.codProducao = $codProducao
									ORDER BY PU.associacao, U.nome ASC");
				$cont = $cont + 1;
				foreach($selecionaPart as $part)
				{
					$codUsuario=$part['codUsuario'];

					if(!in_array($codUsuario, $arrayMembros)){
						$arrayMembros[$i] = $codUsuario;
						$this->dadosMembros[$codUsuario]['codUsuario']=$codUsuario;
						$this->dadosMembros[$codUsuario]['nome']=$part['nome'];
						$this->dadosMembros[$codUsuario]['associacao']=$part['associacao'];
						$this->dadosMembros[$codUsuario]['InteForum']= 0;
						$this->dadosMembros[$codUsuario]['InteBP']= 0;
						$this->dadosMembros[$codUsuario]['InteWF']= 0;
						$this->dadosMembros[$codUsuario]['InteBib']= 0;
						$this->dadosMembros[$codUsuario]['InteCO']= 0;
						$this->dadosMembros[$codUsuario]['InteA2']= 0;
						$this->dadosMembros[$codUsuario]['ColabForum'] = 0;		//para salvar alguma soma, por exemplo, no calculo da colaboração
						$this->dadosMembros[$codUsuario]['ColabBP'] = 0;
						$this->dadosMembros[$codUsuario]['ColabWF'] = 0;
						$this->dadosMembros[$codUsuario]['ColabBib'] = 0;
						$this->dadosMembros[$codUsuario]['calcA2'] = 0;
						$this->dadosMembros[$codUsuario]['calcCO'] = 0;
						$this->dadosMembros[$codUsuario]['Colab'] = 0;

						$i++;
					}
					
				}
			}
		}
		//$this->comandoLayout();
		$this->membros = $arrayMembros;

		if($this->podeVisualizar()){
			$this->corAluno='#'.$corAluno;
			$this->corProfessor='#'.$corProfessor;
			$this->corMonitor='#'.$corMonitor;
			$this->desenhaNodos();
			$this->contabilizaInteracoes($interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio);

			$agrupamento = new Grupo($this->arrayInteracoes);
			$agrupamento->BronKerbosch(array(), $this->getArrayMembrosComInteiros(), array());
			$agrupamento->toJavascriptArray($this->alunosTurma);

			$popularidade = new Popularidade($this->arrayInteracoes);
			$popularidade->calculaPopularidadeTurma();
			$popularidade->toJavascriptArray($this->alunosTurma);

			escreveInteracoes($this->arrayInteracoes, $this->alunosTurma);

		}else {
			echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
			echo 'alert ("Sem permiss\u00e3o para acessar o Mapa Social.")';
			echo '</SCRIPT>';
		}
	}

	private function defineDirected(){
		/*if($this->layout==='dot')   //Modo "dot" retirado do mapa social
			$this->directed=TRUE;
		else if(($this->layout==='neato')||($this->layout==='circo'))
			$this->directed=FALSE;
		else{
			$this->layout=LAYOUT_PADRAO;
			$this->defineDirected();*/
		if($this->directed==='yes')
			$this->directed=TRUE;
		else
			$this->directed=FALSE;
	}

	private function getArrayMembrosComInteiros(){	//array membros contém strings com os numeros
		$membrosInt = array();
		foreach($this->membros as $key=>$membros){
			$membrosInt[$key] = intval($membros);
		}

		return $membrosInt;
	}

	private function inverteData($data){
		$dia=intval(substr($data,0,2));
		$mes=intval(substr($data,3,2));
		$ano=intval(substr($data,6,4));
		if(checkdate($mes,$dia,$ano))
			return ($ano.'-'.$mes.'-'.$dia);
		else
			return '';
	}

	private function ajustaDatas($dataInicio,$dataFim){
		$this->dataInicio=$this->inverteData($dataInicio);
		$this->dataFim=$this->inverteData($dataFim);
		if((strtotime($this->dataFim)<=strtotime($this->dataInicio))||empty($this->dataInicio)||empty($this->dataFim)){
			$this->dataInicio=DATA_INICIO_PADRAO;
			$this->dataFim=DATA_FIM_PADRAO;
		}
	}

	private function contabilizaInteracoes($interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio){
		$this->arrayInteracoes= array(array());  //array com as chaves

		if($interacaoContatos)
		$this->analisaContatos($interacaoContatos);
		if($interacaoBatepapo)
		$this->analisaBatepapo($interacaoBatepapo);
		if($interacaoForum)
		$this->analisaForum($interacaoForum);
		if($interacaoBiblioteca)
		$this->analisaBiblioteca($interacaoBiblioteca);
		if($interacaoA2)
		$this->analisaA2($interacaoA2);
		if($interacaoWebfolio)
		$this->analisaWebfolio($interacaoWebfolio);
		//$this->procuraSolitarios();
		//$this->ajustaInteracoes();
	}


	private function analisaContatos($intContatos){
		$interacaoContatos_1=0.3*$intContatos;
		$interacaoContatos_2=0.3*$intContatos;
		$pesquisaMensagens=db_busca('	SELECT id_mensagem,id_remetente
										FROM c_mensagem
										WHERE (	id_turma="'.$this->codTurma.'" AND
												DATE(data_hora) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
										ORDER BY id_mensagem');

		foreach($pesquisaMensagens as $mensagem){
			$idRemetente=($mensagem['id_remetente']);
			$idMensagem=($mensagem['id_mensagem']);
			//$idMensagemCitada=($mensagem['id_citada']);
			$idRemetenteCitada=0;

			$pesquisaDestinatarios=db_busca('	SELECT id_destinatario
												FROM c_mensagem_destinatario
												WHERE id_mensagem="'.$idMensagem.'"');
			foreach($pesquisaDestinatarios as $destinatario){
				$idDestinatario=($destinatario['id_destinatario']);

				$this->incrementa_interacao($idRemetente, $idDestinatario, $interacaoContatos_2);
			}
		}
	}


	private function analisaBatepapo($intBatepapo) {
		$interacaoBatepapoTodos=$intBatepapo*0.2;
		$interacaoBatepapoPvt=0.3*$intBatepapo;

		//poe no array Pesquisa_salas todas as salas de bate-papo da turma
		$Pesquisa_salas = db_busca('SELECT codSala
									FROM BatePapoSala
									WHERE codTurma="'.$this->codTurma.'"
									ORDER BY codSala ASC');



		//esse foreach vai percorrer todas salas de bate-papo da turma
		foreach($Pesquisa_salas as $salas)
		{
			//array PesquisaAlunos guarda todos usuarios que mandaram mensagem por cada sala e seu respectivo destino
			$PesquisaAlunos = db_busca('SELECT codUsuario,destino
										FROM BatePapoMensagem
										WHERE (codSala="'.intval($salas['codSala']).'" AND
												DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
										ORDER BY codUsuario ASC');

			//agora tem que fazer as interações de casa aluno com seus respectivos destinos, este foreach vai fazer isso
			foreach($PesquisaAlunos as $sociais)
			{
				$AlunoInterage = $sociais['codUsuario'];	//alunointerage é quem mandou a msg
				$AlunoInteragido = $sociais['destino'];		//o alunointeragido é o destino e vai receber a setinha


				$this->incrementa_interacao($AlunoInterage, $AlunoInteragido, $interacaoBatepapoPvt);

			}
		}

	}

	private function analisaForum($intForum){
		$interacaoForum_1=0.2*$intForum; //por enquanto todas as interações têm pesos iguais
		$interacaoForum_2=0.2*$intForum;
		$interacaoForum_3=0.2*$intForum;
		//busca fórum daquela turma
		$pesquisaForuns=db_busca('	SELECT codForum
									FROM Forum
									WHERE codTurma="'.$this->codTurma.'"
									ORDER BY codForum ASC');
		foreach($pesquisaForuns as $forum){	//busca os tópicos do fórum da turma
			$pesquisaTopicosForum=db_busca('	SELECT codTopico
												FROM ForumTopico
												WHERE codForum="'.intval($forum['codForum']).'"
												ORDER BY codTopico ASC');

			foreach($pesquisaTopicosForum as $topico){
				$pesquisaMensagensForum=db_busca('	SELECT codUsuario,citou
													FROM ForumMensagem
													WHERE (	codTopico="'.intval($topico['codTopico']).'" AND
															DATE(hora) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
													ORDER BY codMensagem ASC');

				$primeiraMensagem_Forum=TRUE;
				foreach($pesquisaMensagensForum as $mensagem){
					$autorMensagem=intval($mensagem['codUsuario']);
					$mensagemCitada=intval($mensagem['citou']);
					$autorMensagemCitada=0;
					if($mensagemCitada!==0){
						$pesquisaMensagemCitada=db_busca('	SELECT codUsuario
															FROM ForumMensagem
															WHERE codMensagem="'.$mensagemCitada.'"
															LIMIT 1');
						if(count($pesquisaMensagemCitada)===1){
							$autorMensagemCitada=intval($pesquisaMensagemCitada[0]['codUsuario']);

							$this->incrementa_interacao($autorMensagem, $autorMensagemCitada, $interacaoForum_1);
						}
					}

				}
			}
		}
	}


	private function analisaBiblioteca($intBiblioteca)	//analisa os comentários da biblioteca
	{
		$interacaoBiblioteca_1=0.2*$intBiblioteca;
		$interacaoBiblioteca_2=0.2*$intBiblioteca;
		$interacaoBiblioteca_3=0.2*$intBiblioteca;
		//busca todos o materiais publicados
		$pesquisaMaterial=db_busca('	SELECT codMaterial, codUsuario
									FROM BibliotecaMaterial
									WHERE codTurma="'.$this->codTurma.'"
									ORDER BY codMaterial ASC');		//aqui eu imagino que faça uma "tabela" cujo as colunas são codMaterial e codUsuario

		foreach($pesquisaMaterial as $material)	//busca os comentários da biblioteca da turma
		{
			$pesquisaComentadorBiblioteca=db_busca('	SELECT codUsuario
												FROM BibliotecaComentarios
												WHERE( codMaterial="'.intval($material['codMaterial']).'" AND
												DATE(data) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
												ORDER BY codUsuario ASC');		//então busca os codUsuarios, comentadores, que correspondem a cada codMaterial

			$primeiroComentario_Biblioteca=TRUE;
			foreach($pesquisaComentadorBiblioteca as $comentador)
			{
				$meninoComentador = $comentador['codUsuario'];
				$meninoPostador = $material['codUsuario'];


				$this->incrementa_interacao($meninoComentador, $meninoPostador, $interacaoBiblioteca_1);

			}
		}
	}
	private function analisaA2($intA2)
	{
		$interacaoA2 = 0.1*$intA2;

		$pesquisaUsuariosTurma = db_busca('   SELECT codUsuario
												FROM TurmaUsuario
												WHERE codTurma="'.$this->codTurma.'"
												ORDER BY codUsuario ASC');
											//pega os codUsuarios da Turma escolhida na tabela TurmaUsuario
		foreach($pesquisaUsuariosTurma as $Aluno1) //daí pra cada aluno encontrado buscar uma interação com um segundo aluno.
		{
			$alunoEnviador = $Aluno1['codUsuario'];

			$Pesquisa_Aluno2 = db_busca('SELECT codUsuario2
								FROM A2
								WHERE ( codUsuario1="'.intval($Aluno1['codUsuario']).'" AND
								DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
								ORDER BY codUsuario2 ASC');
			foreach($Pesquisa_Aluno2 as $aluno2)
			{
				$alunoRecebedor = $aluno2['codUsuario2'];

				$this->incrementa_interacao($alunoEnviador, $alunoRecebedor, $interacaoA2);

			}
		}
	}


	private function analisaWebfolio($intWebfolio){	//analisa os comentários do webfolio
		$interacaoWebfolio = 0.5*$intWebfolio;

		$pesquisaArquivosTurma = db_busca(' SELECT codArquivo, codUsuario
											FROM WFArquivo
											WHERE codTurma= "'.$this->codTurma.'"
											ORDER BY codArquivo ASC');
		foreach($pesquisaArquivosTurma as $files)
		{
			$pesquisaComentadores = db_busca(' SELECT codUsuario
												FROM WFComentario
												WHERE ( codArquivo="'.intval($files['codArquivo']).'" AND
												DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
												ORDER BY codUsuario ASC');
			foreach($pesquisaComentadores as $aluninhos)
			{

				$comentador = $aluninhos['codUsuario'];
				$postador = $files['codUsuario'];

				$this->incrementa_interacao($comentador, $postador, $interacaoWebfolio);

			}
		}


	}

	private function ajustaInteracoes(){


		if($this->directed){
			foreach($this->arrayInteracoes as $usuario1=>$interacao){
				if(isset($this->dadosMembros[$usuario1])){
					foreach($interacao as $usuario2=>$forcaInteracao){
						if((!isset($this->dadosMembros[$usuario2]))||($usuario1===$usuario2))
							unset($this->arrayInteracoes[$usuario1][$usuario2]);
					}
				}
				else
					unset($this->arrayInteracoes[$usuario1]);
			}
		}
		else{
			foreach($this->arrayInteracoes as $usuario1=>$interacao){
				if(isset($this->dadosMembros[$usuario1])){
					foreach($interacao as $usuario2=>$forcaInteracao){
						if((!isset($this->dadosMembros[$usuario2]))||($usuario1===$usuario2))
							unset($this->arrayInteracoes[$usuario1][$usuario2]);
						else{
							if(isset($this->arrayInteracoes[$usuario2][$usuario1])){
								$this->arrayInteracoes[$usuario1][$usuario2]+=$this->arrayInteracoes[$usuario2][$usuario1];
								unset($this->arrayInteracoes[$usuario2][$usuario1]);
							}
						}
					}
				}
				else
					unset($this->arrayInteracoes[$usuario1]);
			}
		}

	}

	private function podeVisualizar(){	//define se o usuário pode visualisar o mapa social
		$pesquisaAssociacao=db_busca('	SELECT associacao
										FROM TurmaUsuario
										WHERE (	codUsuario="'.$this->codUsuario.'" AND
												codTurma="'.$this->codTurma.'")');

		$pesquisaPermissao=db_busca('SELECT acessoTodos
										FROM ra_acessosocial
										WHERE codTurma="'.$this->codTurma.'"');

		$permissao = intval($pesquisaPermissao[0]['acessoTodos']);

		if ($permissao===1)
			return TRUE;
		else if (($pesquisaAssociacao[0]['associacao']==='A') && ($permissao===0))
			return FALSE;
		else
			return TRUE;


		/* COMENTÁRIO - Renan_GAY 21/08
		if((count($pesquisaAssociacao)===1)&&(($pesquisaAssociacao[0]['associacao']==='P')||($pesquisaAssociacao[0]['associacao']==='M')))
			return TRUE;
		else
			return FALSE;
		*/
	}

	public function veIntensidade()
	{
		//FORUM
			$CodForum = db_busca('SELECT codForum FROM Forum WHERE codTurma="'.$this->codTurma.'"');

			$buscaForum= db_busca('SELECT codForum
								 	 FROM Forum
								 	 WHERE codTurma="'.$this->codTurma.'"');
			foreach($buscaForum as $Forumzinho)
			{
				$buscaTopico = db_busca('SELECT codTopico FROM ForumTopico WHERE codForum = "'.$Forumzinho['codForum'].'"');
				foreach($buscaTopico as $topico)
				{
					$buscaMensagens = db_busca('SELECT codUsuario, ncitadas FROM ForumMensagem
												 WHERE (codTopico="'.$topico['codTopico'].'" AND
															DATE(hora) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
													ORDER BY codMensagem ASC');
					foreach($buscaMensagens as $mensagem)
					{
						$user = $mensagem['codUsuario'];
						$this->dadosMembros[$user]['InteForum'] += (intval($mensagem['ncitadas'])+1);
					}
				}
			}
 		//Biblioteca
		$codMaterial =	db_busca('SELECT codMaterial, codUsuario FROM BibliotecaMaterial WHERE codTurma="'.$this->codTurma.'"');

		foreach ($codMaterial as $material)
		{
			$user = $material['codUsuario'];
			$this->dadosMembros[$user]['InteBib'] += 1;
		}

		//A2
		$pesquisaUsuariosTurma = db_busca('   SELECT codUsuario
												FROM TurmaUsuario
												WHERE codTurma="'.$this->codTurma.'"
												ORDER BY codUsuario ASC');

		foreach($pesquisaUsuariosTurma as $Aluno1)
		{
			$user = $Aluno1['codUsuario'];

			$Pesquisa_Aluno2 = db_busca('SELECT codUsuario2
								FROM A2
								WHERE ( codUsuario1="'.intval($user).'" AND
								DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
								ORDER BY codUsuario2 ASC');
			foreach($Pesquisa_Aluno2 as $Aluno2)
			{
				$alunoRecebedor = $Aluno2['codUsuario2'];
				if(in_array($alunoRecebedor, $this->alunosTurma))
				{
					$this->dadosMembros[$user]['InteA2'] += 1;
				}
			}
		}

		//BATE-PAPO
		$Pesquisa_salas = db_busca('SELECT codSala							/*Tem que pegar todas salas de bate papo contidas*/
									FROM BatePapoSala						/*na turma de análise*/
									WHERE codTurma="'.$this->codTurma.'"
									ORDER BY codSala ASC');

		foreach($Pesquisa_salas as $salas)
		{

			$PesquisaAlunos = db_busca('SELECT codUsuario, destino 		/*Agora é preciso pegar todos usuarios e destinos*/
										FROM BatePapoMensagem
										WHERE (codSala="'.intval($salas['codSala']).'" AND
												DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
										ORDER BY codUsuario ASC');

			foreach($PesquisaAlunos as $Aluno)
			{
				$user = $Aluno['codUsuario'];
				if($Aluno['destino'] != 0)			//destino == 0 significa que mandou msg pra todos, isso não conta.
				$this->dadosMembros[$user]['InteBP'] += 1;
			}
		}

		//CONTATOS
		$PesquisaContatos = db_busca('SELECT id_mensagem, id_remetente
									  FROM c_mensagem
									  WHERE (	id_turma="'.$this->codTurma.'" AND
												DATE(data_hora) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
										ORDER BY id_mensagem');
		foreach($PesquisaContatos as $dado)
		{
			$mensagem = $dado['id_mensagem'];
			$user = $dado['id_remetente'];
			$pesquisaDestinatarios=db_busca('	SELECT id_destinatario
												FROM c_mensagem_destinatario
												WHERE id_mensagem="'.$mensagem.'"');
			foreach($pesquisaDestinatarios as $receivers)
			{
				$this->dadosMembros[$user]['InteCO'] +=1;
			}
		}

		//WF
		$pesquisaArquivosTurma = db_busca(' SELECT codArquivo
											FROM WFArquivo
											WHERE codTurma= "'.$this->codTurma.'"
											ORDER BY codArquivo ASC');
		foreach($pesquisaArquivosTurma as $files)
		{
			$pesquisaComentarios = 	db_busca(' SELECT codUsuario FROM WFComentario
												WHERE ( codArquivo="'.intval($files['codArquivo']).'" AND
												DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
												ORDER BY codUsuario ASC');
			foreach($pesquisaComentarios as $users)
			{
				$user = $users['codUsuario'];
				$this->dadosMembros[$user]['InteWF'] +=1;
			}
		}



	}

	private function isSelected($user){
		if(in_array($user, $this->membros))
			return 1;
		return 0;
	}

	private function desenhaNodos()
   {
   		$this->veIntensidade();
   		$this->colaboracao();

		foreach($this->dadosMembros as $membroTurma)
		{
			$usuario1=$membroTurma['codUsuario'];

			$InteWF = ifNull_isZero($membroTurma['InteWF']);

			$InteBP = ifNull_isZero($membroTurma['InteBP']);

			$InteBib = ifNull_isZero($membroTurma['InteBib']);

			$InteForum = ifNull_isZero($membroTurma['InteForum']);

			$InteCO = ifNull_isZero($membroTurma['InteCO']);

			$InteA2 = ifNull_isZero($membroTurma['InteA2']);

			$ColabForum = ifNull_isZero($membroTurma['ColabForum']);

			$ColabBP = ifNull_isZero($membroTurma['ColabBP']);

			$ColabBib = ifNull_isZero($membroTurma['ColabBib']);

			$ColabWF = ifNull_isZero($membroTurma['ColabWF']);

			$Colab = ifNull_isZero($membroTurma['Colab']);

			$label=$this->primeiroNome($membroTurma['nome']);

			if ($membroTurma['associacao']==='A' ){
				echo "nodes.push( {id: ".$usuario1.", name: '".$label."', cor: '".$this->corAluno."',
					  nome: '".$this->primeiroNome($membroTurma['nome']).' '.$this->ultimoNome($membroTurma['nome'])."',
					  biblioteca:'".$InteBib."', forum:'".$InteForum."', a2: '".$InteA2."', wf: '".$InteWF."',
					  contato:'".$InteCO."',bp: '".$InteBP."', colabF: '".$ColabForum."', colabBP: '".$ColabBP."',
					  colabBib:'".$ColabBib."', colabWF: '".$ColabWF."', colab: '".$Colab."',  isolado: '".$membroTurma['isolado']."',
					  distR: '".$membroTurma['distR']."', distP: '".$membroTurma['distP']."', popularidade: '0', isSelected:'".$this->isSelected($usuario1)."'} );\n";
			}

			else if($membroTurma['associacao']==='P' || $membroTurma['associacao']==='R' ){
				echo "nodes.push( {id: ".$usuario1.", name: '".$label."', cor: '".$this->corProfessor."',
					  nome: '".$this->primeiroNome($membroTurma['nome']).' '.$this->ultimoNome($membroTurma['nome'])."', 
					  biblioteca:'".$InteBib."', forum:'".$InteForum."', a2: '".$InteA2."', contato: '".$InteCO."', 
					  wf: '".$InteWF."', bp: '".$InteBP."', colabF: '".$ColabForum."', colabBP: '".$ColabBP."', 
					  colabBib:'".$ColabBib."', colabWF: '".$ColabWF."',  colab: '".$Colab."', isolado: '".$membroTurma['isolado']."', 
					  distR: '".$membroTurma['distR']."', distP: '".$membroTurma['distP']."', popularidade: '0', isSelected:'".$this->isSelected($usuario1)."'} );\n";
			}

			else if($membroTurma['associacao']==='M'){
				echo "nodes.push( {id: ".$usuario1.", name: '".$label."', cor: '".$this->corMonitor."', 
					  nome: '".$this->primeiroNome($membroTurma['nome']).' '.$this->ultimoNome($membroTurma['nome'])."', 
					  biblioteca:'".$InteBib."', forum:'".$InteForum."', a2: '".$InteA2."', contato: '".$InteCO."', 
					  wf: '".$InteWF."', bp: '".$InteBP."', colabF: '".$ColabForum."', colabBP: '".$ColabBP."', 
					  colabBib:'".$ColabBib."', colabWF: '".$ColabWF."', colab: '".$Colab."',  isolado: '".$membroTurma['isolado']."', 
					  distR: '".$membroTurma['distR']."', distP: '".$membroTurma['distP']."', popularidade: '0', isSelected:'".$this->isSelected($usuario1)."'} );\n";
			}

		}
	}

	private function incrementa_interacao($AlunoInterage, $AlunoInteragido, $peso)
	{
		if($AlunoInterage == $AlunoInteragido) return;
		
		if($this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] == 0)
		{
			$this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] = 1.5;
		}
		else if($this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] < 15)
		{
			$this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] += $peso;
		}
	}


	private function primeiroNome($nome){
		return strtok($nome,' ');
	}
	private function ultimoNome($nome){
		$ultimoNome=strtok($nome,' ');
		while($ultimoNome!==FALSE){
			$resultado=$ultimoNome;
			$ultimoNome=strtok(' ');
		}
		return $resultado;
	}
	private function pegaDadosMembros($arrayMembros){
		$pesquisaMembros=db_busca('	SELECT tu.codUsuario,tu.associacao,u.nome
									FROM
										(SELECT codUsuario,associacao
										FROM TurmaUsuario
										WHERE codTurma="'.$this->codTurma.'")
										AS tu
									INNER JOIN
										Usuario
										AS u
									ON tu.codUsuario=u.codUsuario
									ORDER BY u.nome ASC');
		
		if(empty($arrayMembros))
			$todosMembros=TRUE;
		else
			$todosMembros=FALSE;



		foreach($pesquisaMembros as $membro){
			$codUsuario=intval($membro['codUsuario']);

			array_push($this->alunosTurma, $codUsuario);

			//if($todosMembros||in_array($codUsuario,$arrayMembros)){
				$this->dadosMembros[$codUsuario]['codUsuario']=$codUsuario;
				$this->dadosMembros[$codUsuario]['nome']=$membro['nome'];
				$this->dadosMembros[$codUsuario]['associacao']=$membro['associacao'];
				$this->dadosMembros[$codUsuario]['InteForum']= 0;
				$this->dadosMembros[$codUsuario]['InteBP']= 0;
				$this->dadosMembros[$codUsuario]['InteWF']= 0;
				$this->dadosMembros[$codUsuario]['InteBib']= 0;
				$this->dadosMembros[$codUsuario]['InteCO']= 0;
				$this->dadosMembros[$codUsuario]['InteA2']= 0;
				$this->dadosMembros[$codUsuario]['ColabForum'] = 0;		//para salvar alguma soma, por exemplo, no calculo da colaboração
				$this->dadosMembros[$codUsuario]['ColabBP'] = 0;
				$this->dadosMembros[$codUsuario]['ColabWF'] = 0;
				$this->dadosMembros[$codUsuario]['ColabBib'] = 0;
				$this->dadosMembros[$codUsuario]['calcA2'] = 0;
				$this->dadosMembros[$codUsuario]['calcCO'] = 0;
				$this->dadosMembros[$codUsuario]['Colab'] = 0;
			//}
		}
	}
	private function valorMaiorInteracao(){
		$valorMaiorInteracao=1;
		foreach($this->arrayInteracoes as $subinteracoes){
			foreach($subinteracoes as $interacao){
				if($interacao>$valorMaiorInteracao)
					$valorMaiorInteracao=$interacao;
			}
		}
		return $valorMaiorInteracao;
	}
	private function colaboracao()	{
		/*
		* Para calcular a colaboração de um aluno, calculamos o número de interações 
		* de cada aluno dentro das funcionalidades selecionadas e comparamos com a média da turma.
		* A média de colaboração da turma é calculada pelo somatório das médias de colaboração por funcionalidade
		* dividido pelo número de funcionalidades selecionadas
		* A média de colaboração por funcionalidade é calculada pelo somatório do grau de colaboração por aluno
		* dividido pelo número de alunos
		* O grau de colaboração do aluno é simplesmente o número de interações numa funcionalidade multiplicado pelo peso dela (Relativamente Importante, Muito importante, etc...)
		*/

		$arrayAux = array(array());
		$contDist = 0;
		$cont = 0; //contar quantas funcionalidades foram escolhidas
		$alunos = 0; //conta alunos selecionados

		/*
		* As únicas funcionalidades aplicáveis pro cálculo são Fórum, Biblioteca, Webfólio e Bate-Papo
		*/
		$forumColab = 0;		//ver o número de interações da turma por categoria
		$bibColab = 0;
		$wfColab = 0;
		$bpColab = 0;

		/*
		* Calcula os dados pra colaboração, evasão, distanciamentos. 
		* O arrayAux só serve pros cálculos do distanciamentos e evasão.
		* Dentro das funções colaboracaoX($arrayAux) atualiza o array de DadosMembros.
		* O array de dadosMembros é usado para o cálculo de colaboração
		*/
		$arrayAux = $this->colaboracaoForum($arrayAux); 
		$arrayAux = $this->colaboracaoWF($arrayAux);
		$arrayAux = $this->colaboracaoBib($arrayAux);
		$arrayAux = $this->colaboracaoBP($arrayAux);
		$arrayAux = $this->calcA2($arrayAux);
		$arrayAux = $this->calcCO($arrayAux);


		/*
		* Conta quantas funcionalidades foram selecionadas.
		*/
		if($this->interacaoWebfolio !=0) $cont++;
		if($this->interacaoForum !=0) $cont++;
		if($this->interacaoBiblioteca !=0) $cont++;
		if($this->interacaoBatepapo !=0) $cont++;

		/*
		* Para cada aluno, calcula-se o grau de colaboração dele, 
		* soma-se à turma e calcula sua média particular de colaboração.  
		*/
		foreach($this->dadosMembros as $key => $colaboracao)
		{
			//Grau de colaboração do aluno
			$colabAbsolut = $this->interacaoForum*$colaboracao['ColabForum'] + $this->interacaoWebfolio*$colaboracao['ColabWF'] + 
							$this->interacaoBiblioteca*$colaboracao['ColabBib'] + $this->interacaoBatepapo*$colaboracao['ColabBP'];

			//Salva a média do grau de colaboração em seus dados
			if($cont) {
				$this->dadosMembros[$key]['Colab'] = $colabAbsolut/$cont;
			}
			else {
				$this->dadosMembros[$key]['Colab'] = -1;
			}

			//Soma ao número de interações da turma as interações do usuário
			if($this->interacaoForum !=0) $forumColab += $colaboracao['ColabForum'];
			if($this->interacaoBiblioteca !=0) $bibColab += $colaboracao['ColabBib'];
			if($this->interacaoWebfolio !=0) $wfColab += $colaboracao['ColabWF'];
			if($this->interacaoBatepapo !=0) $bpColab += $colaboracao['ColabBP'];

			$alunos++;
		}

		if($cont == 0) $cont = 1;  //caso não tenha sido selecionado nenhuma funcionalidade que entra no cálculo da colab

		//calcula a média de cada funcionalidade
		$forumColab = $forumColab/$alunos;
		$wfColab = $wfColab/$alunos;
		$bibColab = $bibColab/$alunos;
		$bpColab = $bpColab/$alunos;

		//calcula a média de colaboração da turma
		$medGeral = ($forumColab + $wfColab + $bibColab + $bpColab)/$cont;

		$this->distanciamento($arrayAux);

		echo "var mediaColab = ".$medGeral.";";
		echo "var ncategorias = ".$cont.";";
	}

	private function colaboracaoForum($arrayAux){

		$mediaForum = 0; $cont = 0;
		$buscaForum= db_busca('SELECT codForum	 FROM Forum WHERE codTurma="'.$this->codTurma.'"');

		foreach($buscaForum as $Forumzinho)
		{
			$buscaTopico = db_busca('SELECT codTopico FROM ForumTopico WHERE codForum = "'.$Forumzinho['codForum'].'"');
			foreach($buscaTopico as $topico)
			{
				$buscaMensagens = db_busca('SELECT codUsuario, mensagem, citou, ncitadas FROM ForumMensagem
												 WHERE (codTopico="'.$topico['codTopico'].'" AND
														DATE(hora) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
														ORDER BY codMensagem ASC');
				foreach($buscaMensagens as $mensagem)
				{
					$codUsuario = $mensagem['codUsuario'];

					$link=0; $imagem=0; $resposta=0; $topico=0;

					if(intval($mensagem['citou'])==0) $topico = 1; 		//pode multiplicar os valores por um peso
					else $resposta = 1;
					if (strpos($mensagem['mensagem'], "<a href=")) $link = 1;
					if (strpos($mensagem['mensagem'], "<IMG src=")) $imagem = 1;
					$this->dadosMembros[$codUsuario]['ColabForum'] += ($topico+$link+$imagem+$resposta);

					$codUsuario = $mensagem['codUsuario'];


					if(intval($mensagem['citou'], 10) === 0) {
						$arrayAux = verificacaoArray($arrayAux, $codUsuario, 'Frespondeu');
					}  		//pode multiplicar os valores por um peso
					else {
						$arrayAux = verificacaoArray($arrayAux, $codUsuario, 'Ftopico');
					}

					if(intval($mensagem['ncitadas'], 10) !== 0)
						$arrayAux = verificacaoArray($arrayAux, $codUsuario, 'Frecebido');
				}
			}
		}
		return $arrayAux;
	}

	private function colaboracaoWF($arrayAux)
	{
		$mediaWF = 0; $cont = 0;
		$pesquisaWF = db_busca(	'SELECT codArquivo, codUsuario, visivel FROM WFArquivo WHERE codTurma="'.$this->codTurma.'"');
		foreach($pesquisaWF as $arquivo)
		{
			$user = $arquivo['codUsuario'];
			if($arquivo['visivel'] == 2)
			{
				$this->dadosMembros[$user]['ColabWF'] += 1;
			}
			$pesquisaComentadores = db_busca(' SELECT codUsuario
												FROM WFComentario
												WHERE ( codArquivo="'.intval($arquivo['codArquivo']).'" AND
												DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
												ORDER BY codUsuario ASC');

			foreach($pesquisaComentadores as $aluninhos){
				$comentador = $aluninhos['codUsuario'];

				$arrayAux = verificacaoArray($arrayAux, $comentador, 'comentouWF');
				$arrayAux = verificacaoArray($arrayAux, $user, 'recebeuWF');

			}
		}

		return $arrayAux;
	}

	private function colaboracaoBib($arrayAux){
		$mediaBib = 0; $cont = 0;
		$pesquisaBib = db_busca(	'SELECT codUsuario FROM BibliotecaMaterial WHERE codTurma="'.$this->codTurma.'"');
		foreach($pesquisaBib as $arquivo)
		{
			$user = $arquivo['codUsuario'];
			$this->dadosMembros[$user]['ColabBib'] += 1;

			$pesquisaComentadorBiblioteca=db_busca('	SELECT codUsuario
												FROM BibliotecaComentarios
												WHERE( codMaterial="'.intval($arquivo['codMaterial']).'" AND
												DATE(data) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
												ORDER BY codUsuario ASC');

			foreach($pesquisaComentadorBiblioteca as $comentadores){
				$comentador = $comentadores['codUsuario'];

				$arrayAux = verificacaoArray($arrayAux, $comentador, 'comentouBib');
				$arrayAux = verificacaoArray($arrayAux, $user, 'recebeuBib');

			}

		}

		return $arrayAux;
	}

	private function colaboracaoBP($arrayAux){
		$boladaoDeAmor = db_busca('SELECT codSala
									FROM BatePapoSala
									WHERE codTurma="'.$this->codTurma.'"
									ORDER BY codSala ASC');
		foreach($boladaoDeAmor as $salas){
			$pesquisaBP = db_busca('SELECT codUsuario, destino
										FROM BatePapoMensagem
										WHERE (codSala="'.intval($salas['codSala']).'" AND
												DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
										ORDER BY codUsuario ASC');
			foreach($pesquisaBP as $interacao){
				$user = $interacao['codUsuario'];
				$destino = $interacao['destino'];

				$this->dadosMembros[$user]['ColabBP'] += 1;
				
				$arrayAux =	verificacaoArray($arrayAux, $destino, "BPrecebeu");
				
				$arrayAux = verificacaoArray($arrayAux, $user, "BPenviou");
			}
		}

		return $arrayAux;
	}

 	private function calcCO($arrayAux){
 		$pesquisaContatos = db_busca('	SELECT id_mensagem,id_remetente
										FROM c_mensagem
										WHERE (	id_turma="'.$this->codTurma.'" AND
												DATE(data_hora) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
										ORDER BY id_mensagem');
		foreach($pesquisaContatos as $mensagemCO){
			$remetente = $mensagemCO['id_remetente'];
			$idMensagem = $mensagemCO['id_mensagem'];
			$pesquisaDestino = db_busca('	SELECT id_destinatario
												FROM c_mensagem_destinatario
												WHERE id_mensagem="'.$idMensagem.'"');
			if(in_array($remetente, $this->alunosTurma))
			foreach($pesquisaDestino as $destinoCO){

				$destino = $destinoCO['id_destinatario'];

				if(in_array($destino, $this->alunosTurma)){
					$arrayAux = verificacaoArray($arrayAux, $remetente, "mandouCO");
					$arrayAux =	verificacaoArray($arrayAux, $destino, "recebeuCO");
				}

			}
		}
		return $arrayAux;
 	}

 	private function calcA2($arrayAux){
 		foreach($this->dadosMembros as $key => $membro){
			$mandou = $key;
			$Pesquisa_Aluno2 = db_busca('SELECT codUsuario2
								FROM A2
								WHERE ( codUsuario1="'.intval($mandou).'" AND
								DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
								ORDER BY codUsuario2 ASC');
			if(in_array($mandou, $this->alunosTurma))
				foreach($Pesquisa_Aluno2 as $recebeuM){
					$recebeu = $recebeuM['codUsuario2'];

					if(in_array($recebeu, $this->alunosTurma)){
						$arrayAux = verificacaoArray($arrayAux, $mandou, "mandouA2");
						$arrayAux = verificacaoArray($arrayAux, $recebeu, "recebeuA2");
					}
				}
		}

		return $arrayAux;
 	}

	private function distanciamento($arrayAux){
		echo "var mensagens = [];"; //o array mensagens vai ser o array que contem quantas mensagens o sujeito recebeu (index 0) e quantas enviou (index 1)
		foreach($this->dadosMembros as $key => $membros){ 
			$somaRecebidas = $arrayAux[$key]['Frecebido'] +$arrayAux[$key]['BPrecebeu']+$arrayAux[$key]['recebeuCO']
							+$arrayAux[$key]['recebeuBib']+$arrayAux[$key]['recebeuWF']+$arrayAux[$key]['recebeuA2'];

			$somaEnviadas = $arrayAux[$key]['Frespondeu']+$arrayAux[$key]['BPenviou']+$arrayAux[$key]['mandouCO']
							+$arrayAux[$key]['comentouBib']+$arrayAux[$key]['comentouWF']+$arrayAux[$key]['mandouA2'];

			echo "mensagens.push({id:".$key.", recebidas:".$somaRecebidas.", enviadas:".$somaEnviadas."});";

			
			$this->dadosMembros[$key]['isolado'] = $this->verificaIsolado($arrayAux[$key]);
			$this->dadosMembros[$key]['distR']	=	$this->verificaDistR($arrayAux[$key]); //em relação a turma
			$this->dadosMembros[$key]['distP']	=	$this->verificaDistP($arrayAux[$key]);//pela turma
		}
	}

	private function verificaIsolado($arrayAux){
	$soma = 0;

	if($this->interacaoForum)
		$soma += ifNull_isZero($arrayAux['Ftopico']) + ifNull_isZero($arrayAux['Frecebido']) + ifNull_isZero($arrayAux['Frespondeu']);
	if($this->interacaoBatepapo)
		$soma += ifNull_isZero($arrayAux['BPrecebeu']) + ifNull_isZero($arrayAux['BPenviou']);
	if($this->interacaoContatos)
		$soma += ifNull_isZero($arrayAux['mandouCO']) + ifNull_isZero($arrayAux['recebeuCO']);
	if($this->interacaoBiblioteca)
		$soma += ifNull_isZero($arrayAux['comentouBib']) + ifNull_isZero($arrayAux['recebeuBib']);
	if($this->interacaoWebfolio)
		$soma += ifNull_isZero($arrayAux['comentouWF']) + ifNull_isZero($arrayAux['recebeuWF']) ;
	if($this->interacaoA2)
		$soma += ifNull_isZero($arrayAux['mandouA2']) + ifNull_isZero($arrayAux['recebeuA2']);
	if($soma == 0) return 1;
	else return 0;
}

private function verificaDistR($arrayAux){ //verifica o distanciamento em relação a turma

	$recebida = 0; $mandou = 1;
	if($this->interacaoForum){
		$recebida = $recebida || ifNull_isZero($arrayAux['Frecebido']) != 0;
		$mandou = $mandou && (ifNull_isZero($arrayAux['Ftopico'])== 0) && (ifNull_isZero($arrayAux['Frespondeu']) == 0);
	}

	if($this->interacaoBatepapo){
		$recebida = $recebida || ifNull_isZero($arrayAux['BPrecebeu']) != 0;
		$mandou = $mandou && (ifNull_isZero($arrayAux['BPenviou']) == 0);
	}

	if($this->interacaoContatos){
		$recebida = $recebida || ifNull_isZero($arrayAux['recebeuCO']) != 0;
		$mandou = $mandou && (ifNull_isZero($arrayAux['mandouCO']) == 0);
	}

	if($this->interacaoBiblioteca){
		$recebida = $recebida || ifNull_isZero($arrayAux['recebeuBib']) != 0;
		$mandou = $mandou && (ifNull_isZero($arrayAux['comentouBib']) == 0);
	}
	if($this->interacaoWebfolio){
		$recebida = $recebida || ifNull_isZero($arrayAux['recebeuWF']) != 0;
		$mandou = $mandou && (ifNull_isZero($arrayAux['comentouWF']) == 0);
	}

	if($this->interacaoA2){
		$recebida = $recebida || ifNull_isZero($arrayAux['recebeuA2']) != 0;
		$mandou = $mandou && (ifNull_isZero($arrayAux['mandouA2']) == 0) ;
	}

	return $recebida && $mandou;
}

private function verificaDistP($arrayAux){
	$recebida = 1; $mandou = 0;
	if($this->interacaoForum){
		$recebida = $recebida && ifNull_isZero($arrayAux['Frecebido']) == 0;
		$mandou = $mandou || (ifNull_isZero($arrayAux['Ftopico']) != 0) || (ifNull_isZero($arrayAux['Frespondeu']) != 0);
	}

	if($this->interacaoBatepapo){
		$recebida = $recebida && ifNull_isZero($arrayAux['BPrecebeu']) == 0;
		$mandou = $mandou || (ifNull_isZero($arrayAux['BPenviou']) != 0);
	}

	if($this->interacaoContatos){
		$recebida = $recebida && ifNull_isZero($arrayAux['recebeuCO']) == 0;
		$mandou = $mandou || (ifNull_isZero($arrayAux['mandouCO']) != 0);
	}

	if($this->interacaoBiblioteca){
		$recebida = $recebida && ifNull_isZero($arrayAux['recebeuBib']) == 0;
		$mandou = $mandou || (ifNull_isZero($arrayAux['comentouBib']) != 0);
	}
	if($this->interacaoWebfolio){
		$recebida = $recebida && ifNull_isZero($arrayAux['recebeuWF']) == 0;
		$mandou = $mandou || (ifNull_isZero($arrayAux['comentouWF']) != 0);
	}

	if($this->interacaoA2){
		$recebida = $recebida && ifNull_isZero($arrayAux['recebeuA2']) == 0;
		$mandou = $mandou || (ifNull_isZero($arrayAux['mandouA2']) != 0 );
	}

	return $recebida && $mandou;
}

}



function escreveInteracoes($array, $arrayMembers)
{
	$primeiravez = 1;

	echo "edges = [";
	foreach($array as $mandou => $elemento)
	{
		foreach($elemento as $recebeu => $tipo) {
			if($tipo !=0 && in_array($recebeu, $arrayMembers) && in_array($mandou, $arrayMembers))
			{
				if($primeiravez)
				{
						if($recebeu)
						 echo "{target:".$recebeu.", source:".$mandou.", value:".$tipo."}";
				}
				else
				{
						if($recebeu)
						 echo ",{target:".$recebeu.", source:".$mandou.", value:".$tipo."}";
				}
				$primeiravez = 0;
			}

		}
	}
	echo "];";
}


function verificacaoArray($array, $usuario, $campo){
	$usuario = intval($usuario);

	if( !(isset($array[$usuario])) ){
		$array[$usuario] = array();
	}


	if ( !(isset($array[$usuario][$campo])) ) {
		//if ( empty($array[$usuario] ) $array[$usuario] = array();

		$array[$usuario][$campo] = 1;
	} else {
		$array[$usuario][$campo] += 1;
	}

	return $array;
}

function ifNull_isZero($valor){
	if($valor == null)	return 0;
	else return $valor;
}

?>

