<?php
	include("Data.php");

	class Popularidade{
		private $alunos;
		private $popularidadeAlunos;
		private $media;
		private $desvioPopular; //desvio padrão para o aluno ser popular
	//	private static $desvioDefault = 1;

/*		public function __construct(){
			$this->alunos = array(array());
			$this->popularidadeAlunos = new Data(array()); 
			$this->media = -1;
			$this->desvioPopular = static::$desvioDefault;
		}
*/
		public function __construct($array){
			$this->alunos = $array;
			$this->popularidadeAlunos = new Data(array());
			$this->media = -1;
			//$this->desvioPopular = static::$desvioDefault; //comentei pq tá dando algum erro que eu não sei oq é.
			$this->desvioPopular = 1;
		}

		public function setDesvioPopular($desvio){
			$this->desvioPopular = $desvio;
		}

		public function getDesvioPopular(){
			return $this->desvioPopular;
		}

		private function calculaDesvioPopular(){
			$this->desvioPopular = $this->popularidadeAlunos->getDesvioPadrao();
		}

		private function calculaPopularidadeAluno($aluno){
			foreach($this->alunos[$aluno] as $aluno2 => $mandou){
				$recebeu = $this->alunos[$aluno2][$aluno];
				if($mandou > 1 &&  $recebeu >= 1){
						$currentData = $this->getPopularidadeAluno($aluno);
						$valorInteracao = $mandou < $recebeu ? $mandou : $recebeu;
						$newData = $currentData +$valorInteracao + 100; //a cada novo aluno que ele interage reciprocamente adiciona 100
						$this->popularidadeAlunos->setData($aluno, $newData);
				}
			}
		}

		public function getPopularidadeAluno($aluno){
			return $this->popularidadeAlunos->getElement($aluno);
		}

		public function calculaPopularidadeTurma(){
			$this->inicializaPopularidade();

			foreach($this->alunos as $aluno => $data){
				$this->calculaPopularidadeAluno($aluno);
			}
			$this->calculaDesvioPopular();
			$this->calculaMedia();
		}

		private function inicializaPopularidade(){
			//precisa ver se o aluno está na turma
			foreach($this->alunos as $mandou => $receberam){
				foreach($receberam as $recebeu => $interacao){
					$this->popularidadeAlunos->setData($mandou, 0);
					$this->popularidadeAlunos->setData($recebeu, 0);
				}
			}
		}

		public function getPopularidadeTurma(){

			return $this->popularidadeAlunos->getData();
		}

		private function arrayPopTemAlunos(){
			if(!($this->popularidadeAlunos->getData())) return true;
			else return false;
		}

		private function calculaMedia(){
			$this->media = $this->popularidadeAlunos->getMedia();
		}

		public function getMediaTurma(){
			return $this->media;
		}

		public function alunoIsPopular($aluno){
			if($this->getPopularidadeAluno($aluno) >= $this->getMediaTurma() + $this->getDesvioPopular())
				return true;
			else return false;
		}

		public function toJavascriptArray($turma){
			echo "var desvioPopular = ".$this->getDesvioPopular().";";
			echo "var mediaPop = ".$this->getMediaTurma().";";
			foreach($turma as $index => $aluno){
				echo "nodes[".$index."].popularidade = ".$this->getPopularidadeAluno($aluno).";";					
			}
		}

		public function toJavascriptArrayTurma($turma){
			echo "var desvioPopularTurma = ".$this->getDesvioPopular().";";
			echo "var mediaPopTurma = ".$this->getMediaTurma().";";
			foreach($turma as $index => $aluno){
				echo "nodes[".$index."].popularidadeTurma = ".$this->getPopularidadeAluno($aluno).";";
			}
		}

	}
?>