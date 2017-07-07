<?php
	class Grupo{
		private $grafo;

		private $grupos;
		
		public function __construct($grafo){
			$this->grafo = $grafo; //insert a bidimensional array here
			$this->grupos = array();
		}
		
		public function getGrupos(){
			return $this->grupos;
		}
		
		private function hasConnection($a, $b){
			if(isset($this->grafo[$a][$b]))
				return $this->grafo[$a][$b];
			
			return 0;
		}
		
		private function hasDoubleConnection($a, $b){
			if($this->hasConnection($a, $b) && $this->hasConnection($b, $a))
				return true;
			
			return false;
		}
		
		private function vertexNeighborhood($v){
			$vizinhos = array();
			
			
			if(isset($this->grafo[$v]))			
				foreach($this->grafo[$v] as $key => $vizinho){
					
					if($this->hasDoubleConnection($v, $key))
						array_push($vizinhos, $key);
				
				}
				
			return $vizinhos;
		}
		

		private function union($setA, $setB){
			foreach($setB as $element){
				if(!in_array($element, $setA))
					array_push($setA, $element);
			}
			
			return $setA;
		}
		
		private function unionVertex($set_A, $vertex){
			
			if(!in_array($vertex, $set_A))
				array_push($set_A, $vertex);
			
			return $set_A;
			
		}
		
		private function intersection($set_A, $set_B){
			$intersection = array();
			
			foreach($set_B as $element){
				if(in_array($element, $set_A))
					array_push($intersection, $element);
			}

			return $intersection;
		}
		
		
		
		private function relativeComplement($set_A, $set_B){ 	
		//elementos que pertencem a A e nao pertencem a B
			$belongtoB_A = array();
			$complement = array();
			foreach($set_B as $element){
				if(in_array($element, $set_A))
					array_push($belongtoB_A, $element);
			}

			foreach($set_A as $elementA){
				if(!in_array($elementA, $belongtoB_A))
					array_push($complement, $elementA);
			}

			return $complement;
		}
		
		private function countNeighbors_in_P($v, $P){
			return count(array_intersect($this->vertexNeighborhood($v), $P));
		}
		
		private function findOptimalNode($P, $X){
			$unionPX = $this->union($P, $X);
			
						
			$maiorVizinhanca = $unionPX[0];
			foreach($unionPX as $vertice){
				if($this->countNeighbors_in_P($vertice, $P) > $this->countNeighbors_in_P($maiorVizinhanca, $P))
					$maiorVizinhanca = $vertice;
			}
			
			
			return $maiorVizinhanca;
		}
		
		public function BronKerbosch($R, $P, $X){   //$R é pra receber nulo, $P recebe os vértices do array
													//$X recebe nulo. Tudo isso na primeira chamada da função
			
			if(count($P) ==0 && count($X) == 0){
				if(count($R) > 2)
					return $R;
				else
					return null;
			}
			
			$optimalNode = $this->findOptimalNode($P, $X);
			
			$P_minus_N = $this->relativeComplement($P, $this->vertexNeighborhood($optimalNode));
			
			foreach($P_minus_N as $vertex){ 
				
				$grupo = $this->BronKerbosch(	 $this->unionVertex($R, $vertex), 
												 $this->intersection($P, $this->vertexNeighborhood($vertex)), 
												 $this->intersection($X, $this->vertexNeighborhood($vertex)));
												 
				
				if($grupo != null)
					array_push( $this->grupos, $grupo); 
						   
				
				$P = $this->relativeComplement($P, array($vertex));
				
				$X = $this->unionVertex($X, $vertex);

			}
		}
		
		public function toJavascriptArray($membros){
			/*
			$arrayAlunosComSeusGrupos = array();
			$idGrupo = 0;
			*/
			echo "grupos = [];";
			foreach($this->grupos as $grupos){
				$string = "";
				foreach($grupos as $index=>$aluno){
					if($index == 0)
						$string = $string.$aluno;
					else
						$string = $string.",".$aluno;
					/*if(!isset($arrayAlunosComSeusGrupos[$aluno])){
						$arrayAlunosComSeusGrupos[$aluno] = array();
						array_push($arrayAlunosComSeusGrupos[$aluno], $idGrupo);
					}
					else 
						array_push($arrayAlunosComSeusGrupos[$aluno], $idGrupo);
					*/
				}
				echo "grupos.push([".$string."]);";
				//$idGrupo++;
			}
			
			/*foreach($membros as $key=>$aluno){
				$stringGrupo = "";
				if(isset($arrayAlunosComSeusGrupos[$aluno]))
					foreach($arrayAlunosComSeusGrupos[$aluno] as $index=>$grupo){
						if($index == 0)
							$stringGrupo = $stringGrupo.$grupo;
						else 
							$stringGrupo = $stringGrupo.",".$grupo;
						
					}
					if($stringGrupo != "")
						echo "nodes[".$key."].grupo = '".$stringGrupo."';";
			}*/
		}
	}
?>