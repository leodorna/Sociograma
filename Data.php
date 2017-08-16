<?php
	class Data{
		private $data;

		public function __construct($dataArray){
			$this->data = $dataArray;
		}

		public function putData($input){
			array_push($this->data, $input);
		}

		public function setDataArray($dataArray){
			$this->data = $dataArray;
		}

		public function setData($index, $data){
			$this->data[$index] = $data;
		}

		public function getElement($element){
			if(isset($this->data[$element])) return $this->data[$element];
			else return 0;
		}

		public function getData(){
			return $this->data;
		}

		public function getCount(){
			return count($this->data);
		}

		public function getMedia(){
			$soma = 0;
			$size = $this->getCount();
			foreach($this->data as $dado){
				$soma += $dado;
			}
			return $soma/$size;
		}


		public function getVariancia(){
			$soma = 0;
			$media = $this->getMedia();
			$size = $this->getCount();
			foreach($this->data as $dado){
				$soma += $dado*$dado;
			}
			return ($soma/$size) - ($media*$media);
		}

		public function getDesvioPadrao(){
			return sqrt($this->getVariancia());
		}

	} 
?>
