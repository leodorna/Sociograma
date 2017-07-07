<?php
	class Grafo{
		private $grafo;
		private $stack;
		public $grupos;

		public function __construct(){
			$this->grafo = array(array());
			$this->stack = array();
			$this->grupos = array();
			$this->indexGrupo = 0;
		}

		public function addEdge($v, $w, $valor){
			$this->grafo[$v][$w] = $valor;
		}

		public function setGrafo($grafo){
			$this->grafo = $grafo;
		}

		public function getGrupos(){
			return $this->grupos;
		}

		public function getGrupoAluno($aluno){
			if(isset($this->grupos[$aluno]))
				return $this->grupos[$aluno];
			else
				return 0;
		}

		public function DFSutil($v, $visitados, $indexGrupo){
			$visitados[$v] = true;

/*			if(!isset($this->grupos[$v]))
				$this->grupos[$v] = array();
*/
//			array_push($this->grupos[$v], $indexGrupo);

			$this->grupos[$v] = $indexGrupo;

			if(isset($this->grafo[$v]))
				foreach($this->grafo[$v] as $key => $adjacentes){

					if(!$this->wasVisited($key, $visitados) && $adjacentes > 0){
						$visitados = $this->DFSutil($key, $visitados, $indexGrupo);
					}
				}

			return $visitados;
		}

		private function wasVisited($key, $visitados){
			if(isset($visitados[$key])) return $visitados[$key];
			else return false;
		}

		public function getGrafo(){
			return $this->grafo;
		}

		public function getTranspose(){

	        $g = new Grafo();

	            // Recur for all the vertices adjacent to this vertex
	        foreach($this->grafo as $mandou => $adjacentes){
	       		foreach($adjacentes as $recebeu => $conexao){
	       			$g->addEdge($recebeu, $mandou, $conexao);
	       		}
	        }

	        return $g;
    	}

    	private function fillOrder($v, $visitados){
    		$visitados[$v] = true;

    		if(isset($this->grafo[$v]))
	    		foreach($this->grafo[$v] as $key => $adjacentes){
	    			if(!$this->wasVisited($key, $visitados) && $adjacentes > 0)
	    				$visitados = $this->fillOrder($key, $visitados);
	    		}

    		array_push($this->stack, $v);

    		return $visitados;
    	}

    	public function toJavascriptArray($visitados, $turma){
			$g = $this->getTranspose();

    		foreach($visitados as $key => $adjacentes){
    			$visitados[$key] = false;
    		}

			$indexGrupo = 0;
			while(!empty($this->stack)){

				$size = sizeof($this->stack);
				$v = $this->stack[$size-1];
				unset($this->stack[$size-1]);

				if(!$visitados[$v]){
					$visitados = $g->DFSutil($v, $visitados, $indexGrupo);
					$indexGrupo++;
				}
			}

			foreach($turma as $index => $aluno){
				echo "nodes[".$index."].grupo = ".$g->getGrupoAluno($aluno).";";					
			}
				
			
		}


    	public function printAgrupamento($turma){
    		$visitados = array();
    		foreach($this->grafo as $key => $adjacentes){
    			$visitados[$key] = false;
    		}

    		foreach($this->grafo as $key => $adjacentes){
    			if($visitados[$key] == false)
    				$visitados = $this->fillOrder($key, $visitados);
    		}

			$this->toJavascriptArray($visitados, $turma);
    	}

	}

?>