

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
	private $arrayInteracoesTurma;
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
	private $codGrupo;
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
		$this->arrayGrupos = $arrayGrupos;
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
						$this->dadosMembros[$codUsuario]['ColabForum'] = 0;		//para salvar alguma soma, por exemplo, no calculo da colaboração
						$this->dadosMembros[$codUsuario]['ColabBP'] = 0;
						$this->dadosMembros[$codUsuario]['ColabWF'] = 0;
						$this->dadosMembros[$codUsuario]['ColabBib'] = 0;
						$this->dadosMembros[$codUsuario]['calcA2'] = 0;
						$this->dadosMembros[$codUsuario]['calcCO'] = 0;
						$this->dadosMembros[$codUsuario]['colab'] = 0;

						$i++;
					}
					
				}
			}
		}
		//$this->comandoLayout();
		$this->membros = $arrayMembros;

		if($this->podeVisualizar()){
			$arrayDados = array(array());
			$this->corAluno='#'.$corAluno;
			$this->corProfessor='#'.$corProfessor;
			$this->corMonitor='#'.$corMonitor;
			
			if(empty($arrayGrupos)){
				$this->contabilizaInteracoes($interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio);
				$this->desenhaNodos();

			}else{
				$this->contabilizaInteracoesGrupo($interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio);
				$this->desenhaNodosGrupo();
			}

			$agrupamento = new Grupo($this->arrayInteracoes);
			$agrupamento->BronKerbosch(array(), $this->getArrayMembrosComInteiros(), array());
			$agrupamento->toJavascriptArray($this->alunosTurma);

			$popularidadeTurma = new Popularidade($this->arrayInteracoesTurma);
			$popularidadeTurma->calculaPopularidadeTurma();
			$popularidadeTurma->toJavascriptArrayTurma($this->alunosTurma);

			$popularidade = new Popularidade($this->arrayInteracoes);
			$popularidade->calculaPopularidadeTurma();
			$popularidade->toJavascriptArray($this->alunosTurma);

			escreveInteracoes($this->arrayInteracoesTurma, $this->alunosTurma);

		}else {
			echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
			echo 'alert ("Sem permiss\u00e3o para acessar o Mapa Social.")';
			echo '</SCRIPT>';
		}
	}

	public function identificacaoRelatorio($user, $turma){
		$dadosUser = $this->dadosMembros[$user];

		$pesquisaTurma = db_busca('SELECT codDisciplina, nomeTurma FROM Turma WHERE codTurma="'.$turma.'"');
		$pesquisaDisciplina = db_busca('SELECT nomeDisciplina FROM Disciplina WHERE codDisciplina="'.$pesquisaTurma[0]['codDisciplina'].'"');

		echo "<p>Aluno: ".$dadosUser['nome']."</p>";
		echo "<p>Atividade de Ensino: ".$pesquisaDisciplina[0]['nomeDisciplina']."</p>";
		echo "<p>Turma: ".$pesquisaTurma[0]['nomeTurma']."</p>";
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
	private function contabilizaInteracoesGrupo(){
		//para pegar do forum, por exemplo, temos que, para cada codGrupo contido em $arrayGrupos pegar os topicos cujo codForum AND codGrupo batem.
	}
	private function contabilizaInteracoes($interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio){

		$this->arrayInteracoes= array(array());  //array com as chaves
		$this->arrayInteracoesTurma = array(array());

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
			$AlunoInterage=($mensagem['id_remetente']);
			$idMensagem=($mensagem['id_mensagem']);
			//$idMensagemCitada=($mensagem['id_citada']);
			$idRemetenteCitada=0;

			$pesquisaDestinatarios=db_busca('	SELECT id_destinatario
												FROM c_mensagem_destinatario
												WHERE id_mensagem="'.$idMensagem.'"');
			foreach($pesquisaDestinatarios as $destinatario){
				$AlunoInteragido=($destinatario['id_destinatario']);

				if(!in_array($AlunoInterage, $this->alunosTurma) || !in_array($AlunoInteragido, $this->alunosTurma))
					continue;

				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'COTurma');
				$this->incrementa_interacaoTurma($AlunoInterage, $AlunoInteragido, $interacaoContatos_2);

				if(!in_array($AlunoInterage, $this->membros) || !in_array($AlunoInteragido, $this->membros))
					continue;

				$this->incrementa_interacao($AlunoInterage, $AlunoInteragido, $interacaoContatos_2);
				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'CO');
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

				if(!in_array($AlunoInterage, $this->alunosTurma) || !in_array($AlunoInteragido, $this->alunosTurma))
					continue;

				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'BPTurma');
				$this->incrementa_interacaoTurma($AlunoInterage, $AlunoInteragido, $interacaoBatepapoPvt);

				if(!in_array($AlunoInterage, $this->membros) || !in_array($AlunoInteragido, $this->membros))
					continue;
				
				$this->incrementa_interacao($AlunoInterage, $AlunoInteragido, $interacaoBatepapoPvt);
				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'BP');

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
					$AlunoInterage=intval($mensagem['codUsuario']);
					$mensagemCitada=intval($mensagem['citou']);
					$AlunoInteragido=0;
					if($mensagemCitada!==0){
						$pesquisaMensagemCitada=db_busca('	SELECT codUsuario
															FROM ForumMensagem
															WHERE codMensagem="'.$mensagemCitada.'"
															LIMIT 1');
						if(count($pesquisaMensagemCitada)===1){
							$AlunoInteragido=intval($pesquisaMensagemCitada[0]['codUsuario']);
							
							if(!in_array($AlunoInterage, $this->alunosTurma) || !in_array($AlunoInteragido, $this->alunosTurma))
								continue;

							$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'ForumTurma');
							$this->incrementa_interacaoTurma($AlunoInterage, $AlunoInteragido, $interacaoForum_1);
				
							if(!in_array($AlunoInterage, $this->membros) || !in_array($AlunoInteragido, $this->membros))
								continue;

							$this->incrementa_interacao($AlunoInterage, $AlunoInteragido, $interacaoForum_1);
							$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'Forum');
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
				$AlunoInterage = $comentador['codUsuario'];
				$AlunoInteragido = $material['codUsuario'];

				if(!in_array($AlunoInterage, $this->alunosTurma) || !in_array($AlunoInteragido, $this->alunosTurma))
					continue;

				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'BibTurma');
				$this->incrementa_interacaoTurma($AlunoInterage, $AlunoInteragido, $interacaoBiblioteca_1);

				if(!in_array($AlunoInterage, $this->membros) || !in_array($AlunoInteragido, $this->membros))
					continue;
				
				$this->incrementa_interacao($AlunoInterage, $AlunoInteragido, $interacaoBiblioteca_1);
				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'Bib');

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
			$AlunoInterage = $Aluno1['codUsuario'];

			$Pesquisa_Aluno2 = db_busca('SELECT codUsuario2
								FROM A2
								WHERE ( codUsuario1="'.intval($Aluno1['codUsuario']).'" AND
								DATE(quando) BETWEEN "'.$this->dataInicio.'" AND "'.$this->dataFim.'")
								ORDER BY codUsuario2 ASC');
			foreach($Pesquisa_Aluno2 as $aluno2)
			{
				$AlunoInteragido = $aluno2['codUsuario2'];

				if(!in_array($AlunoInterage, $this->alunosTurma) || !in_array($AlunoInteragido, $this->alunosTurma))
					continue;

				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'A2Turma');
				$this->incrementa_interacaoTurma($AlunoInterage, $AlunoInteragido, $interacaoA2);

				if(!in_array($AlunoInterage, $this->membros) || !in_array($AlunoInteragido, $this->membros))
					continue;

				$this->incrementa_interacao($AlunoInterage, $AlunoInteragido, $interacaoA2);
				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'A2');

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

				$AlunoInterage = $aluninhos['codUsuario'];
				$AlunoInteragido = $files['codUsuario'];


				if(!in_array($AlunoInterage, $this->alunosTurma) || !in_array($AlunoInteragido, $this->alunosTurma))
					continue;

				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'WFTurma');
				$this->incrementa_interacaoTurma($AlunoInterage, $AlunoInteragido, $interacaoWebfolio);

				if(!in_array($AlunoInterage, $this->membros) || !in_array($AlunoInteragido, $this->membros))
					continue;
				$this->incrementa_interacao($AlunoInterage, $AlunoInteragido, $interacaoWebfolio);
				$this->incrementaIntensidade($AlunoInterage, $AlunoInteragido, 'WF');
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

	

	private function isSelected($user){
		if(in_array($user, $this->membros))
			return 1;
		return 0;
	}

	private function desenhaNodos()
   {
   		$this->colaboracao();

		foreach($this->dadosMembros as $membroTurma){
			$userData = array();

			$userData['id']   = $membroTurma['codUsuario'];
			$userData['cor']  = $this->userColor($membroTurma['associacao']);


			$userData['name'] = $this->primeiroNome($membroTurma['nome']);
			$userData['nome'] = $this->primeiroNome($membroTurma['nome']).' '.$this->ultimoNome($membroTurma['nome']);

			$userData['isolado'] = $membroTurma['isolado'];
			$userData['distR'] = $membroTurma['distR'];
			$userData['distP'] = $membroTurma['distP'];

			$userData['isSelected'] = $this->isSelected($membroTurma['codUsuario']);

			foreach($membroTurma as $key => $value){
				if(!isset($userData[$key]) && $key != 'codUsuario' &&  $key != 'associacao'){
					$userData[$key] = $value;
				}
			}

			if($userData['id'] != null)
				echo "nodes.push(".json_encode($userData).");";

		}
	}

	private function incrementa_interacao($AlunoInterage, $AlunoInteragido, $peso)
	{
		if($AlunoInterage == $AlunoInteragido) 
			return;
		
		
		if($this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] == 0)
		{
			$this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] = 1.5;
		}
		else if($this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] < 15)
		{
			$this->arrayInteracoes[$AlunoInterage][$AlunoInteragido] += $peso;
		}
	}

	private function incrementa_interacaoTurma($AlunoInterage, $AlunoInteragido, $peso)
	{
		if($AlunoInterage == $AlunoInteragido) 
			return;
		
		
		if($this->arrayInteracoesTurma[$AlunoInterage][$AlunoInteragido] == 0)
		{
			$this->arrayInteracoesTurma[$AlunoInterage][$AlunoInteragido] = 1.5;
		}
		else if($this->arrayInteracoesTurma[$AlunoInterage][$AlunoInteragido] < 15)
		{
			$this->arrayInteracoesTurma[$AlunoInterage][$AlunoInteragido] += $peso;
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

			/*if(!in_array($codUsuario, $this->membros))
				continue;
*/
			array_push($this->alunosTurma, $codUsuario);

			//if($todosMembros||in_array($codUsuario,$arrayMembros)){
				$this->dadosMembros[$codUsuario]['codUsuario']=$codUsuario;
				$this->dadosMembros[$codUsuario]['nome']=$membro['nome'];
				$this->dadosMembros[$codUsuario]['associacao']=$membro['associacao'];
				$this->dadosMembros[$codUsuario]['ColabForum'] = 0;		//para salvar alguma soma, por exemplo, no calculo da colaboração
				$this->dadosMembros[$codUsuario]['ColabBP'] = 0;
				$this->dadosMembros[$codUsuario]['ColabWF'] = 0;
				$this->dadosMembros[$codUsuario]['ColabBib'] = 0;
				$this->dadosMembros[$codUsuario]['calcA2'] = 0;
				$this->dadosMembros[$codUsuario]['calcCO'] = 0;
				$this->dadosMembros[$codUsuario]['colab'] = 0;
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
		$arrayDados = array();
		$arrayDados['colabBP'] = 0;
		$arrayDados['colabForum'] = 0;
		$arrayDados['colabBib'] = 0;
		$arrayDados['colabWF'] = 0;
		$arrayDados['colabBPTurma'] = 0;
		$arrayDados['colabForumTurma'] = 0;
		$arrayDados['colabBibTurma'] = 0;
		$arrayDados['colabWFTurma'] = 0;
		
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
		$i = 0;
		$j = 0;
		foreach($this->dadosMembros as $key => $colaboracao)
		{
			if(!in_array($key, $this->alunosTurma))
				continue;

			$arrayDados = $this->calculoDadosColaboracao($colaboracao, $arrayDados, 'Turma');

			if($cont){
				$this->dadosMembros[$key]['colabTurma'] = $arrayDados['colabAbsolutTurma']/$cont;
			}
			else {
				$this->dadosMembros[$key]['colab'] = -1;
			}


			if(!in_array($key, $this->membros))
				continue;
			
			

			$arrayDados = $this->calculoDadosColaboracao($colaboracao, $arrayDados, '');
			
			//Salva a média do grau de colaboração em seus dados
			if($cont){
				$this->dadosMembros[$key]['colab'] = $arrayDados['colabAbsolut']/$cont;
			}
			else {
				$this->dadosMembros[$key]['colab'] = -1;
			}

			$alunos++;
		}

		if($cont == 0) $cont = 1;  //caso não tenha sido selecionado nenhuma funcionalidade que entra no cálculo da colab

		
		foreach($arrayDados as $key=>$dado){
			if(strpos($key, "Turma"))
				$arrayDados[$key] = $dado/count($this->alunosTurma);
			else 
				$arrayDados[$key] = $dado/count($this->membros);
		}
		$medGeral = ($arrayDados['colabForum'] + $arrayDados['colabWF'] + $arrayDados['colabBib'] + $arrayDados['colabBP'])/$cont;

		$medGeralTurma = ($arrayDados['colabForumTurma'] + $arrayDados['colabWFTurma'] + $arrayDados['colabBibTurma'] + $arrayDados['colabBPTurma'])/$cont;

		$this->distanciamento($arrayAux);
		echo "var mediaColab = ".$medGeral.";";
		echo "var mediaColabTurma = ".$medGeralTurma.";";
		echo "var ncategorias = ".$cont.";";
	}

	private function calculoDadosColaboracao($colaboracao, $arrayDados, $turmaLabel){
		$colabAbsolut = 'colabAbsolut'.$turmaLabel;
		$colabForum = 'colabForum'.$turmaLabel;
		$colabBib = 'colabBib'.$turmaLabel;
		$colabWF = 'colabWF'.$turmaLabel;
		$colabBP = 'colabBP'.$turmaLabel;


		$arrayDados[$colabAbsolut] = $this->interacaoForum*$colaboracao['ColabForum'] +
									 $this->interacaoWebfolio*$colaboracao['ColabWF'] + 
									 $this->interacaoBiblioteca*$colaboracao['ColabBib'] +
									 $this->interacaoBatepapo*$colaboracao['ColabBP'];
		
		if($this->interacaoForum !=0) $arrayDados[$colabForum] += $colaboracao['ColabForum'];
		if($this->interacaoBiblioteca !=0) $arrayDados[$colabBib] += $colaboracao['ColabBib'];
		if($this->interacaoWebfolio !=0) $arrayDados[$colabWF] += $colaboracao['ColabWF'];
		if($this->interacaoBatepapo !=0) $arrayDados[$colabBP] += $colaboracao['ColabBP'];

		return $arrayDados;
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

					if(!in_array($codUsuario, $this->alunosTurma))
						continue;

					$link=0; $imagem=0; $topico=0;

					if(intval($mensagem['citou'])==0) $topico = 1; 		//pode multiplicar os valores por um peso
					if (strpos($mensagem['mensagem'], "<a href=")) $link = 1;
					if (strpos($mensagem['mensagem'], "<IMG src=")) $imagem = 1;
					$this->dadosMembros[$codUsuario]['ColabForum'] += ($topico+$link+$imagem);

					
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

			if(!in_array($user, $this->alunosTurma))
				continue;

			if($arquivo['visivel'] == 2)
			{
				$this->dadosMembros[$user]['ColabWF'] += 1;
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
			if(!in_array($user, $this->alunosTurma))
				continue;
			$this->dadosMembros[$user]['ColabBib'] += 1;

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

				if(!in_array($user, $this->alunosTurma))
					continue;

				$this->dadosMembros[$user]['ColabBP'] += 1;
				
			}
		}

		return $arrayAux;
	}

	private function distanciamento($arrayAux){
		echo "var mensagens = [];"; //o array mensagens vai ser o array que contem quantas mensagens o sujeito recebeu (index 0) e quantas enviou (index 1)
		foreach($this->dadosMembros as $key => $membros){ 
			$somaRecebidas = $membros['recForum'] +$membros['recBP']+$membros['recCO']
							+$membros['recBib']+$membros['recWF']+$membros['recA2'];   

			$somaEnviadas = $membros['envForum']+$membros['envBP']+$membros['envCO']
							+$membros['envBib']+$membros['envWF']+$membros['envA2'];

			echo "mensagens.push({id:".$key.", recebidas:".$somaRecebidas.", enviadas:".$somaEnviadas."});";

			
			$this->dadosMembros[$key]['isolado'] = $this->verificaIsolado($key);
			$this->dadosMembros[$key]['distR']	=	$this->verificaDistR($membros); //em relação a turma
			$this->dadosMembros[$key]['distP']	=	$this->verificaDistP($membros);//pela turma
		}
	}

	private function verificaIsolado($codUsuario){

		$result = db_busca('SELECT * FROM `AcessosDisciplina` WHERE codTurma = '.$this->codTurma.' AND entrada > '.$this->dataInicio.' AND codUsuario = '.$codUsuario.' 
 ORDER BY `AcessosDisciplina`.`entrada` ASC LIMIT 1');
		if($result != NULL)
			return 0;
		else return 1;
		
	}

	private function verificaDistR($arrayAux){ //verifica o distanciamento em relação a turma

		$recebida = 0; $mandou = 1;
		if($this->interacaoForum){
			$recebida = $recebida || ifNull_isZero($arrayAux['recForum']) != 0;
			$mandou = $mandou && (ifNull_isZero($arrayAux['envForum'])== 0);
		}

		if($this->interacaoBatepapo){
			$recebida = $recebida || ifNull_isZero($arrayAux['recBP']) != 0;
			$mandou = $mandou && (ifNull_isZero($arrayAux['envBP']) == 0);
		}

		if($this->interacaoContatos){
			$recebida = $recebida || ifNull_isZero($arrayAux['recCO']) != 0;
			$mandou = $mandou && (ifNull_isZero($arrayAux['envCO']) == 0);
		}

		if($this->interacaoBiblioteca){
			$recebida = $recebida || ifNull_isZero($arrayAux['recBib']) != 0;
			$mandou = $mandou && (ifNull_isZero($arrayAux['envBib']) == 0);
		}
		if($this->interacaoWebfolio){
			$recebida = $recebida || ifNull_isZero($arrayAux['recWF']) != 0;
			$mandou = $mandou && (ifNull_isZero($arrayAux['envWF']) == 0);
		}

		if($this->interacaoA2){
			$recebida = $recebida || ifNull_isZero($arrayAux['recA2']) != 0;
			$mandou = $mandou && (ifNull_isZero($arrayAux['envA2']) == 0) ;
		}

		return $recebida && $mandou;
	}

	private function userColor($associacao){
		switch ($associacao) {
					case 'A':
						return $this->corAluno;
					case 'P':
						return $this->corProfessor;
					case 'R':
						return $this->corProfessor;
					case 'M':
						return $this->corMonitor;
					
		}
	}

	private function incrementaIntensidade($usuarioInterage, $usuarioInteragido, $key){
		$recKey = 'rec'.$key;
		$envKey = 'env'.$key;

		if(isset($this->dadosMembros[$usuarioInterage][$envKey]))
			$this->dadosMembros[$usuarioInterage][$envKey]++;
		else $this->dadosMembros[$usuarioInterage][$envKey] = 1;

		if(isset($this->dadosMembros[$usuarioInteragido][$recKey]))
			$this->dadosMembros[$usuarioInteragido][$recKey]++;
		else $this->dadosMembros[$usuarioInteragido][$recKey] = 1;

	}

	private function verificaDistP($arrayAux){
		$recebida = 1; $mandou = 0;
		if($this->interacaoForum){
			$recebida = $recebida && ifNull_isZero($arrayAux['recForum']) == 0;
			$mandou = $mandou || (ifNull_isZero($arrayAux['envForum']) != 0);
		}

		if($this->interacaoBatepapo){
			$recebida = $recebida && ifNull_isZero($arrayAux['recBP']) == 0;
			$mandou = $mandou || (ifNull_isZero($arrayAux['envBP']) != 0);
		}

		if($this->interacaoContatos){
			$recebida = $recebida && ifNull_isZero($arrayAux['recCO']) == 0;
			$mandou = $mandou || (ifNull_isZero($arrayAux['envCO']) != 0);
		}

		if($this->interacaoBiblioteca){
			$recebida = $recebida && ifNull_isZero($arrayAux['recBib']) == 0;
			$mandou = $mandou || (ifNull_isZero($arrayAux['envBib']) != 0);
		}
		if($this->interacaoWebfolio){
			$recebida = $recebida && ifNull_isZero($arrayAux['recWF']) == 0;
			$mandou = $mandou || (ifNull_isZero($arrayAux['envWF']) != 0);
		}

		if($this->interacaoA2){
			$recebida = $recebida && ifNull_isZero($arrayAux['recA2']) == 0;
			$mandou = $mandou || (ifNull_isZero($arrayAux['envA2']) != 0 );
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

	/*if(!in_array($usuario, $this->membros))
		continue;
*/
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

