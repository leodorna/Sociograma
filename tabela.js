const INTERACOES = 0;
const INDICADOR_SOCIAL = 1;
var tabela = {
    tooltip: "",
    user: "",
    grupos: [],
    state: "",            //precisamos de um state pra ver se a tabela está sendo mostrada na tela ou não
    typeMap: INTERACOES,
    textoEstrategia: "",
    dadosRelatorio: [],
    interacoes: [],
    getState: function(){
      return this.state;
    },
    setState: function(state){
      this.state = state;
    },
    getTooltip: function(){
      return this.tooltip;
    },
    container: function(container){
      if(this.tooltip){
        this.tooltip.remove();
      }
      this.tooltip = d3.select("#"+container).append("div").attr("id", "tabela-fora")
    },
    setDadosRelatorio: function(dados){
      this.dadosRelatorio = dados;
    },
    setInteracoes: function(interacoes){
      this.interacoes = interacoes;
    },
    position: function(){
      var top = (-sizetoNumber(this.tooltip.style("height"))+ d3.event.y - 10);
      var left = d3.event.x;

      if(top < 0)
    		top = 0;
    	if(d3.event.x + sizetoNumber(this.tooltip.style("width")) > width)
    		left = width - sizetoNumber(this.tooltip.style("width"));
      
      this.tooltip.style("position", "absolute").style("top", top).style("left", left);
    },
    boxTitle: function(){
      this.tooltip.append("div").attr("id", "titulo-tabela")
    },
    width: 0,
    tooltipSize: function(width){
      this.tooltip.style("width", width);
      this.width = width;
    },
    backgroundColor: function(color){
      this.tooltip.style("background-color", color);
    },
    setTitle: function(name){
      d3.select("#titulo-tabela").text(name);
    },
    headers: [],
    setHeaders: function(array){
      this.headers = array;
    },
    data: [],
    setData: function(array){
      this.data = array;
    },
    setUser: function(dataUser){
      this.user = dataUser;
    },
    elements: [],
    createTable: function(){
      modo = d3.select("#Selecao").property("value");
      widthElements = Math.round(100/this.headers.length);
      widthElements = widthElements+"%";

      if(this.elements.length > 0){   //se this.elements tiver cheio devemos apagar para criar uma nova tabela
        for(var i = 0; i < this.elements.length; i++){
          this.elements[i].remove();
        }
        this.elements = [];
      }

      if(this.elements.length == 0){  //cria uma nova tabela a partir do array data, title e headers;
        for(var i = 0; i < this.headers.length; i++){
          this.elements.push(this.tooltip.append("div")
                      .attr("class", "headers-tabela")
                      .style("width", widthElements)
                      .text(this.headers[i]));
        }

        for(var i = 0; i < this.data.length; i++){
          for(var j = 0; j < this.headers.length; j++){
            if(this.data[i][j])
              this.elements.push(this.tooltip.append("div").attr("class", "elementos-tabela").style("width", widthElements).text(this.data[i][j]));
            else
              this.elements.push(this.tooltip.append("div").attr("class", "elementos-tabela").style("width", widthElements).text(0));

          	if(j == 0 && modo != '5'){ //modo == 5 é AGRUPAMENTO
          		this.elements[this.elements.length-1].style("text-align", "left")
          	}
            if(j == 1)
              this.elements[this.elements.length-2].style("height", (this.elements[this.elements.length-1].style("height"))); /*os elementos da primeira coluna devem ter a mesma altura da segunda coluna (corrige o problema da quebra de linha que ocorre as vezes na segunda coluna)*/
          }
        }

      }
      var dataUser = this.user;
      var globalData = this.dadosRelatorio;
      dataUser['turma'] = this.dadosRelatorio['turma'];

	  if(this.typeMap == INTERACOES){
		  this.tooltip.append("div")
          .attr("class", "report-line")
          .style("height", 30)
          .append("p")
          .attr("onclick", "postUserInteracoes("+JSON.stringify(dataUser)+", "+JSON.stringify(this.interacoes)+")")
          .text("Exibir Relatórios");
	  }
	  
	  
      if(this.typeMap == INDICADOR_SOCIAL){  
        this.tooltip.append("div")
            .style("border", "1px solid #bbb")
            .append("p")
            .style("padding", "5 5 0 5")
            .style("margin", 0)
            .style("text-align", "justify")
            .text(this.textoEstrategia)
      

      this.tooltip.append("div")
          .attr("class", "report-line")
          .style("height", 30)
          .append("p")
          .attr("onclick", "postUserIndicadores("+JSON.stringify(dataUser)+", "+JSON.stringify(globalData)+")")
          .text("Exibir Relatórios");

      }
    },
    changeDataFromTable: function(i, j, text){
      var index = this.headers.length*i + j;
      this.elements[index].text(text);
    },
    hideTable: function(){
      this.tooltip.style("visibility", "hidden");
    },
    showTable: function(){
      this.tooltip.style("visibility", "visible")
    }
  };

function postUserInteracoes(user, interacoes){
   arrayGrupos = [];
   form=document.createElement('FORM');
   form.name='formInteracoes';
   form.method='POST';
   form.action='./relatorioInteracoes.php';
   form.target='_blank';

   for(var prop in user) {
      if(user.hasOwnProperty(prop)) { 
  			input = document.createElement('INPUT');
  			input.type='HIDDEN';
  			input.name= prop;
  			input.value=user[prop];
  			form.appendChild(input);
      }
    }
    console.log(interacoes);

    input = document.createElement('INPUT');
    input.type= 'HIDDEN';
    input.name= 'interacoes';
    input.value= JSON.stringify(interacoes);
    form.appendChild(input);

	document.body.appendChild(form);

   form.submit();
}  
  
function postUserIndicadores(user, globalData){
   arrayGrupos = [];
   form=document.createElement('FORM');
   form.name='myForm';
   form.method='POST';
   form.action='./relatorioIndicador.php';
   form.target='_blank';

   for(var prop in globalData) {
      if(globalData.hasOwnProperty(prop)) { 
        input = document.createElement('INPUT');
        input.type='HIDDEN';
        input.name= prop;
        input.value=globalData[prop];
        form.appendChild(input);
      }
    }

   for(var prop in user) {
      if(user.hasOwnProperty(prop)) { 
  			input = document.createElement('INPUT');
  			input.type='HIDDEN';
  			input.name= prop;
  			input.value=user[prop];
  			form.appendChild(input);
      }
    }

    
    
    
    for(var i = 0; i < tabela.grupos.length; i++){
      input = document.createElement('INPUT');
      input.type='HIDDEN';
      input.name= 'grupos[]';
      arrayGrupos[i] = tabela.grupos[i];
      input.value = arrayGrupos[i];
     form.appendChild(input);
    }
    console.log(arrayGrupos);
    
  
   document.body.appendChild(form);

   form.submit();
}