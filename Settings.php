<?php
	define('COR_ALUNO_PADRAO', '#3395FF');
	define('COR_PROF_PADRAO', '#FF8000');
	define('COR_MONI_PADRAO', '#38bc48');
	define('DATA_I_PADRAO', '2000-01-01');
	define('DATA_F_PADRAO', '2099-12-31');
	
	class Settings{
		private $dataInicio;
		private $dataFim;
		private $corNodo;
		private $pesoFunc;
		
		public function __construct(){
			$this->dataInicio = DATA_I_PADRAO;
			$this->dataFim = DATA_F_PADRAO;
			$this->corNodo = Array('A'=>COR_ALUNO_PADRAO, 'P'=>COR_PROF_PADRAO,'M'=>COR_MONI_PADRAO);
			$this->pesoFunc = Array('W'=>5, 'BP'=>5, 'Bib'=>5, 'C'=>5, 'A2'=>5, 'F'=>5);
		}
		
		public function setDataInicio($data){
			if(($this->dataInicio = inverteData($data)) == '')
				$this->dataInicio = DATA_I_PADRAO;	
		}
		
		public function setDataFim($data){
			if(($this->dataFim = inverteData($data)) == '')
				$this->dataFim = DATA_F_PADRAO;	
		}
		
		public function setCorAluno($cor){
			$this->corNodo['A'] = '#'.$cor;
		}
		
		public function setCorProf($cor){
			$this->corNodo['P'] = '#'.$cor;
		}
		
		public function setCorMonitor($cor){
			$this->corNodo['M'] = '#'.$cor;
		}
		
		public function setContatos($peso){
			$this->pesoFunc['C'] = $peso;
		}
		
		public function setBatePapo($peso){
			$this->pesoFunc['BP'] = $peso;
		}
		
		public function setBiblioteca($peso){
			$this->pesoFunc['Bib'] = $peso;
		}
		
		public function setWebfolio($peso){
			$this->pesoFunc['W'] = $peso;
		}
		
		public function setForum($peso){
			$this->pesoFunc['F'] = $peso;
		}
		
		public function setA2($peso){
			$this->pesoFunc['A2'] = $peso;
		}
		
		public function getDataInicio(){
			return $this->dataInicio;
		}
		
		public function getDataFim(){
			return $this->dataFim;
		}
				
		public function getCorAluno(){
			return $this->corNodo['A'];
		}
		
		public function getCorProf(){
			return $this->corNodo['P'];
		}
		
		public function getCorMonitor(){
			return $this->corNodo['M'];
		}
		
		public function getA2(){
			return $this->pesoFunc['A2'];
		}
		
		public function getBiblioteca(){
			return $this->pesoFunc['Bib'];
		}
		
		public function getBatePapo(){
			return $this->pesoFunc['BP'];
		}
		
		public function getContatos(){
			return $this->pesoFunc['C'];
		}
		
		public function getWebfolio(){
			return $this->pesoFunc['W'];
		}
		
		public function getForum(){
			return $this->pesoFunc['F'];
		}
		
		/*private function ajustaDatas($dataInicio,$dataFim){
			$this->dataInicio= inverteData($dataInicio);
			$this->dataFim= inverteData($dataFim);
			if((strtotime($this->dataFim)<=strtotime($this->dataInicio))){
				$this->dataInicio=DATA_INICIO_PADRAO;
				$this->dataFim=DATA_FIM_PADRAO;
			}
		}*/
		
	}
	
	function inverteData($data){
		$dia=intval(substr($data,0,2));
		$mes=intval(substr($data,3,2));
		$ano=intval(substr($data,6,4));
		if(checkdate($mes,$dia,$ano))
			return ($ano.'-'.$mes.'-'.$dia);
		else
			return '';
	}

?>