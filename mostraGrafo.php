<?php/* 
require_once(dirname(__FILE__).'/../sistema.inc.php');
require_once(dirname(__FILE__).'/sociograma.php');
$pagina=new Pagina();
$codUsuario=intval($pagina->sessao->codUsuario);
$codTurma=intval($pagina->sessao->codTurma);





if(isset($_POST['layout']))
	$layout=db_escape($_POST['layout']);
else
	$layout='';
if(isset($_POST['directed']))
	$directed=db_escape($_POST['directed']);
else
	$directed='';
if(isset($_POST['dataInicio']))
	$dataInicio=db_escape($_POST['dataInicio']);
else
	$dataInicio='';
if(isset($_POST['dataFim']))$dataFim=db_escape($_POST['dataFim']);
else
	$dataFim='';


if(isset($_POST['checkboxMembros'])) {
  $arrayMembros=($_POST['checkboxMembros']);
  $arrayGrupos=($_POST['checkboxGrupos']);
  }
else{
  $arrayMembros=($_POST['checkboxMembros']);
  $arrayGrupos=($_POST['checkboxGrupos']);
}

if(isset($_POST['corAluno']))
	$corAluno=$_POST['corAluno'];
else
	$corAluno="#A999cc";

if(isset($_POST['corProfessor']))
	$corProfessor=$_POST['corProfessor'];
else
	$corProfessor="#FF8000";

if(isset($_POST['corMonitor']))
	$corMonitor=$_POST['corMonitor'];
else
	$corMonitor="#CCCCCC";

if(isset($_POST['relcontatos']))	//post dos niveis de relevancia que serão obtidos em 'geraGrafo.php'
	$interacaoContatos=$_POST['relcontatos'];

if(isset($_POST['relbatepapo']))
	$interacaoBatepapo=$_POST['relbatepapo'];

if(isset($_POST['relforum']))
	$interacaoForum=$_POST['relforum'];

if(isset($_POST['relbiblioteca']))
	$interacaoBiblioteca=$_POST['relbiblioteca'];

if(isset($_POST['rela2']))
	$interacaoA2=$_POST['rela2'];

if(isset($_POST['relwebfolio']))
	$interacaoWebfolio=$_POST['relwebfolio'];

//$pagina=new Pagina();
//$pagina->cabecalho('MapaSocial');

if((count($_POST['checkboxMembros']) != 0 || count($_POST['checkboxGrupos']) != 0) && !($interacaoContatos==0 && $interacaoBatepapo==0 && $interacaoForum==0 && $interacaoBiblioteca==0 && $interacaoA2==0 && $interacaoWebfolio==0)) {
 */
?>
<!--Gera a imagem do mapa social, passando os parâmetros pelo link-->
<link rel="stylesheet" type="text/css" href= "mostragrafo.css">
<script src="js/libraries/d3.min.js"></script>
<body style="margin:0;">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/resources/demos/style.css">
<link rel="stylesheet" href="/css/framework/jquery-ui.structure.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="js/framework/jquery-3.2.1.min.js"></script>
<script src="js/framework/jquery-ui.min.js"></script>
<script src="js/libraries/material.min.js"></script>
<link rel="stylesheet" href="css/general.css">
<script src = "tabela.js"></script>
<script src = "functionsMapa.js"></script>
<script src = "zoom.js"></script>
 <script>
//   $( function() {
//     $( "#tabs" ).tabs();
//   } );
</script>

<div class="mdl-layout mdl-js-layout">
<div id ="help">
  <div id="closeHelp">x</div>
	<h2>MAPA DE INTERAÇÕES</h2>
	<p>O Mapa Social é um sociograma que apresenta graficamente as interações sociais entre os participantes do ambiente virtual de aprendizagem ROODA. Neste sociograma existem diferentes informações que podem auxiliar o professor a compreender o perfil social de cada sujeito e suas relações em uma determinada atividade de ensino..
Para isso, o Mapa Social apresenta a quantidade mensagens trocadas a partir da participação dos usuários nas seguintes funcionalidades: Mensagens síncronas e assíncronas, Fórum, Bate-Papo, Comentários da Biblioteca e Comentários do Webfólio.
Portanto:</p>
	<p><b>Círculos:</b>  representam os sujeitos. Ao clicar com o mouse em cima dos mesmos, será apresentada a quantidade de trocas de mensagens do usuário em cada funcionalidade, bem como as relações sociais estabelecidas com outros sujeitos. As diferentes cores representam o perfil associado (monitor/tutor, aluno, professor). Estas podem ser alteradas nas configurações do Mapa Social.
</p>
	<p><b>Ligações:</b> são representadas por linhas que apresentam as relações sociais estabelecidas entre os sujeitos. O tamanho da ponteira das setas, nas ligações, simboliza a  quantidade e a direção das trocas de mensagens realizadas no ambiente. </p>
</div>
<?php 
 require_once( 'future/header.php' ); 
 require_once( 'future/content.php');
?>
<!-- <div id="tabs" style="background-color:#dedede">
	<img id ="interrogacao" src="Imagens/interrogacao.png">
    <ul>
      <li id="mapInt"><a href="#tabs-1">Mapa Interações</a></li>
      <li id="mapCat"><a href="#tabs-2">Mapa Categorias</a></li>
    </ul>

  <div>
        <br>
  			<input type="button" value="Aumentar Mapa" OnClick="zoomClick(1.1)" />
        <input type="button" value="Diminuir Mapa" OnClick="zoomClick(0.9)" />
  			<input type="button" value="Mapa Original" OnClick="zoomClick(0)" />
  </div>
      <div id="tabs-1" style="width:100%; height:100%">

      </div>
    <div id="tabs-2" style="padding:4pt">
      <form style="margin:0;">
        <select style="margin:0;font-size:14pt;" id="Selecao">
          <option value="0">Todas</option>
          <option value="5">Agrupamento</option>
          <option value="2">Ausência</option>
          <option value="1">Colaboração</option>
          <option value="3">Distanciamento pela Turma</option>
          <option value="6">Evasão</option>
          <option value="4">Popularidade</option>
        </select>
      </form>
      <div id="boxEstrategia" style="visibility:hidden;position:absolute;top:80;right:3;width:300;height:100;background-color:rgba(0, 0, 0, 0.4)">
      	<div id="closeBox">x</div>
         <p id="textBox" style="color:white;font-size:16px;margin:10; font-weight: 400">
         </p>
      </div>
      <div id="buttons" style="position:absolute; top:350; left:30; width: 20; height:400;">
      </div>
    </div>
  </div>
</div>
 -->


<script id="Grafo">
   var edges = [];
   var nodes = [];
   var links = [];



   <?php/* 
     function printEstrategias($estrategias){
       echo "'".$estrategias[0]['Estrategias']."'";
        for($i = 1; $i < 6; $i++){
          echo ",'".$estrategias[$i]['Estrategias']."'";
       }
     }

     echo "var estrategiaColab = [";
     printEstrategias(db_busca("SELECT `Estrategias`
                              FROM  `Estrategias&Categorias`
                              WHERE  `Categorias` =  'Colaboracao'"));
     echo "];";

     echo "var estrategiaAgrup = [";
     printEstrategias(db_busca("SELECT `Estrategias`
                              FROM  `Estrategias&Categorias`
                              WHERE  `Categorias` =  'Agrupamento'"));
     echo "];";

     echo "var estrategiaPop = [";
     printEstrategias(db_busca("SELECT `Estrategias`
                              FROM  `Estrategias&Categorias`
                              WHERE  `Categorias` =  'Popularidade'"));
     echo "];";

     echo "var estrategiaDSRT = [";
     printEstrategias(db_busca("SELECT `Estrategias`
                              FROM  `Estrategias&Categorias`
                              WHERE  `Categorias` =  'DSRT'"));
     echo "];";

     echo "var estrategiaDSPT= [";
     printEstrategias(db_busca("SELECT `Estrategias`
                              FROM  `Estrategias&Categorias`
                              WHERE  `Categorias` =  'DSPT'"));
     echo "];";

     echo "var estrategiaEvasao= [";
     printEstrategias(db_busca("SELECT `Estrategias`
                              FROM  `Estrategias&Categorias`
                              WHERE  `Categorias` =  'Evasao'"));
     echo "];";

     echo "var pesoWF = ".$interacaoWebfolio.";\n";
     echo "var pesoBP = ".$interacaoBatepapo.";\n";
     echo "var pesoCo = ".$interacaoContatos.";\n";
     echo "var pesoA2 = ".$interacaoA2.";\n";
     echo "var pesoFo = ".$interacaoForum.";\n";
     echo "var pesoBib = ".$interacaoBiblioteca.";\n";

     echo "var dadosRelatorio = {'turma':".$pagina->sessao->codTurma.", 'pesoBP':".$interacaoBatepapo.", 'pesoWF':".$interacaoWebfolio.", 'pesoCo': ".$interacaoContatos.", 'pesoA2': ".$interacaoA2.", 'pesoFo':".$interacaoForum.", 'pesoBib': ".$interacaoBiblioteca.", 'mediaPop': 0, 'mediaPopTurma': 0};"; 

     $sociograma=new Sociograma($codUsuario,$codTurma,$layout,$directed,$dataInicio,$dataFim,$corAluno,$corProfessor,$corMonitor,$interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio,$arrayMembros,$arrayGrupos);
    */?>


updateLinks(nodes, edges, links)

var circleClick = false;
var modo = '0';
var default_link_color = "#888";
var highlight_node = null;
var highlight_color = "blue";
var highlight_trans = 0.1;
var focus_node = null;
var tocolor = "fill";
var towhite = "stroke";
var width = document.getElementById("tabs-2").offsetWidth;
var height = document.getElementById("tabs").offsetHeight;


var k = Math.sqrt(nodes.length/(width*height));

var force = d3.layout.force()
    .nodes(d3.values(nodes))
    .links(links)
    .size([width, height])
    .linkDistance(100)
    .charge(function(d, i){
    	if(d.isolado == '1')
    		return -50;
    	else return -10/k;
    })
    .gravity(80*k)
    .on("tick", tick)
    .start();

dadosRelatorio.mediaPop = mediaPop;
dadosRelatorio.desvioPopular = desvioPopular;
dadosRelatorio.mediaPopTurma = mediaPopTurma;
dadosRelatorio.desvioPopularTurma = desvioPopularTurma;


var zoom = d3.behavior.zoom().scaleExtent([1, 8])
                             .on("zoom", zoomHandler);


var mapInt = d3.select("#tabs-1").append("svg")
               .attr("width", width)
               .attr("height", height)
               .call(zoom)
               .on("dblclick.zoom", null)
               .on("wheel.zoom", null)
               .append("g");

var mapCat = d3.select("#tabs-2").append("svg")
               .attr("width", width)
               .attr("height", height)
			         .call(zoom)
               .on("dblclick.zoom", null)
               .on("wheel.zoom", null)
               .append("g");


mapCat.append("defs").selectAll("marker")
  .data(["suit", "licensing", "resolved"])
  .enter().append("marker")
  .attr("id", function(d) { return d; })
  .attr("viewBox", "0 -5 10 10")
  .attr("refX", 15)
  .attr("refY", -1.5)
  .attr("markerWidth", 6)
  .attr("markerHeight", 6)
  .attr("orient", "auto")
  .append("path")
  .attr("d", "M0,-5L10,0L0,5");

mapInt.append("defs").selectAll("marker")
      .data(["suit", "licensing", "resolved"])
      .enter().append("marker")
      .attr("id", function(d) { return d; })
      .attr("viewBox", "0 -5 10 10")
      .attr("refX", 15)
      .attr("refY", -1.5)
      .attr("markerWidth", 6)
      .attr("markerHeight", 6)
      .attr("orient", "auto")
      .append("path")
      .attr("d", "M0,-5L10,0L0,5");

var pathInt = mapInt.append("g").selectAll("pathInt")
          .data(force.links())
          .enter().append("polyline")
          .style("opacity", function(d){
                      if(isSelectedById(d.source.id) =='0' || isSelectedById(d.target.id) == '0')
                        return highlight_trans;
                      else return 1;
          })
          .attr("class", function(d) { return "link "});

var pathCat = mapCat.append("g").selectAll("pathCat")
          .data(force.links())
          .enter().append("polyline")
          .style("opacity", function(d){
                      if(isSelectedById(d.source.id) =='0' || isSelectedById(d.target.id) == '0')
                        return highlight_trans;
                      else return 1;
          })
          .attr("class", function(d) { return "link "});

var circleInt = mapInt.append("g").selectAll("circleInt")
                   .data(force.nodes())
                   .enter().append("circle")
                   .attr("r", 9)
                   .style("fill", function(d) { if(d.isolado != '1') return d.cor; else return '902020'})
                   .style("opacity", function(d){if(d.isSelected == '1') return 1; else return highlight_trans})
                   .call(force.drag);

var circleCat = mapCat.append("g").selectAll("circleCat")
                   .data(force.nodes())
                   .enter().append("circle")
                   .attr("r", 9)
                   .style("fill", function(d) { if(d.isolado != '1') return d.cor; else return '902020'})
                   .style("opacity", function(d){if(d.isSelected == '1') return 1; else return highlight_trans})
                   .call(force.drag);

var setaInt = mapInt.append("g").selectAll("setaInt")
                    .data(force.links())
                    .enter().append("polygon")
                    .attr("fill", "#9c9c9c")
                    .style("stroke-width", "1px")
                    .style("stroke", "black")
                    .style("opacity", function(d){
                      if(isSelectedById(d.source.id) =='0' || isSelectedById(d.target.id) == '0')
                        return highlight_trans;
                      else return 1;
                    });

var setaCat = mapCat.append("g").selectAll("setaCat")
                    .data(force.links())
                    .enter().append("polygon")
                    .attr("fill", "#9c9c9c")
                    .style("stroke-width", "1px")
                    .style("stroke", "black")
                    .style("opacity", function(d){
                      if(isSelectedById(d.source.id) =='0' || isSelectedById(d.target.id) == '0')
                        return highlight_trans;
                      else return 1;
                    });


var textInt = mapInt.append("g").selectAll("text")
    .data(force.nodes())
    .enter().append("text")
    .attr("x", 8)
    .attr("y", ".31em")
    .style("opacity", function(d){if(d.isSelected == '1') return 1; else return highlight_trans})
    .style("font-size", 16)
    .text(function(d) { return d.name; });

var textCat = mapCat.append("g").selectAll("text")
    .data(force.nodes())
    .enter().append("text")
    .attr("x", 8)
    .attr("y", ".31em")
    .style("opacity", function(d){if(d.isSelected == '1') return 1; else return highlight_trans})
    .style("font-size", 16)
    .text(function(d) { return d.name; });


var textBox = d3.select("#tabs-2>#boxEstrategia");

grupos.forEach(function(d, i){
  var buttonName = "Grupo "+(i+1);
  d3.select("#buttons")
  .append("button")
  //.attr("type", "button")
  .attr("class", "mdl-button mdl-js-button mdl-button--raised mdl-button--colored")
  .style("background-color", "#2180D3")
  .style("color", "white")
  .style("margin-bottom", 4)
  .text(buttonName)
  .on("click", selectGroup);
});

d3.select("#buttons").style("visibility", "hidden");

textBox.on("mousedown", function(){
  textBox.on("mousemove", function(){

   height = sizetoNumber(textBox.style("height"));
   width = sizetoNumber(textBox.style("width"));
    d3.select("#boxEstrategia").style("top", d3.event.y-60-height/2).style("left", d3.event.x-width/2);
  });
});

textBox.on("mouseup", function(){
	textBox.on("mousemove", null);
});
d3.select("body").on("mouseup", function(){
	textBox.on("mousemove", null);
})


d3.select("#tabs-1").on("click", function(){
	if(tabela.getState() == "ON" && !circleClick){
		tabela.hideTable();
		tabela.setState("OFF");
	}
	circleClick = false;
})
d3.select("#tabs-2").on("click", function(){
	if(tabela.getState() == "ON" && !circleClick){
		tabela.hideTable();
		tabela.setState("OFF");
	}
	circleClick = false;
})

d3.select("#mapInt").on("click", function(){
	d3.select("#tabs-2").style("visibility", "hidden");
	d3.select("#tabs-1").style("visibility", "visible");
});

d3.select("#mapCat").on("click", function(){
	d3.select("#tabs-1").style("visibility", "hidden");
	d3.select("#tabs-2").style("visibility", "visible");
	modo = d3.select("#Selecao").property("value");
	textBox = d3.select("#textBox");
    box = d3.select("#boxEstrategia");

	switch(modo){
		case '0':circleCat.style("opacity", highlight_trans);
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textCat.style("opacity", highlight_trans);
                 box.style("height", textBoxHeight+20).style("visibility", "hidden");
				 hideButtons();
                 break;
		case '1':circleCat.style("opacity", function(d){if((d.colab >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.colab   >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Colaboração': "+ estrategiaColab[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '2':circleCat.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Ausência': " + estrategiaDSRT[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '3':circleCat.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Distanciamento pela Turma': " + estrategiaDSPT[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '4':circleCat.style("opacity", function(d){if((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if ((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Popularidade': " + estrategiaPop[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '5':circleCat.style("opacity", highlight_trans);
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textCat.style("opacity", highlight_trans);
                 d3.select("#buttons").style("visibility", "visible");
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Agrupamento': " +estrategiaAgrup[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 break;
        case '6':circleCat.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
        		 textCat.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
             setaCat.style("opacity", highlight_trans);
             pathCat.style("opacity", highlight_trans);
             textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Evasão': " +estrategiaEvasao[Math.floor(Math.random()*6)]);
             textBoxHeight = Number(textBox.style("height").replace("px",""));
             box.style("height", textBoxHeight+20).style("visibility", "visible");
        		 hideButtons();
	}
})

d3.select("#Selecao").on("change", function(){
      modo = d3.select("#Selecao").property("value");
      textBox = d3.select("#textBox");
      box = d3.select("#boxEstrategia");
      textCat.style("font-weight", "normal");

      switch(modo)
      {
		    case '0':circleCat.style("opacity", highlight_trans);
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
				         box.style("height", textBoxHeight+20).style("visibility", "hidden");
                 textCat.style("opacity", highlight_trans);
                 break;
        case '1':circleCat.style("opacity", function(d){if((d.colab >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.colab   >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Colaboração': "+ estrategiaColab[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '2':circleCat.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Ausência': " + estrategiaDSRT[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '3':circleCat.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Distanciamento pela Turma': " + estrategiaDSPT[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '4':circleCat.style("opacity", function(d){if((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if ((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Popularidade': " + estrategiaPop[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 hideButtons();
                 break;
        case '5':circleCat.style("opacity", highlight_trans);
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textCat.style("opacity", highlight_trans);
                 d3.select("#buttons").style("visibility", "visible");
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Agrupamento': " +estrategiaAgrup[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
                 break;
        case '6':circleCat.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
            		 textCat.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Evasão': " +estrategiaEvasao[Math.floor(Math.random()*6)]);
                 textBoxHeight = Number(textBox.style("height").replace("px",""));
                 box.style("height", textBoxHeight+20).style("visibility", "visible");
            		 hideButtons();
      }
  });


d3.select("#closeBox").on("click", function(){
	d3.select("#boxEstrategia").style("visibility", "hidden");
});
d3.select("#closeModal").on("click", function(){
  d3.select("#modal").style("visibility", "hidden");
})

circleInt.on("click", function(d){
  var transform;
  var posX = 0;
  var posY = 0;
  var escala = 1;
  circleClick = true;

  if(tabela.getState() == "ON"){
    tabela.hideTable();
    tabela.setState("OFF");
    return;
  }

  tabela.container("tabs-1");
  tabela.boxTitle();
  tabela.setUser(d);
  tabela.setTitle(d.nome);
  tabela.setHeaders(["Funcionalidade", "Enviadas", "Recebidas"])
  tabela.setData([[" Biblioteca", d.envBib, d.recBib], 
  				  [" Contatos", d.envCO, d.recCO], 
  				  [" A2", d.envA2, d.recA2], 
  				  [" Fórum", d.envForum, d.recForum], 
  				  [" Webfólio", d.envWF, d.recWF], 
  				  [" Bate-Papo", d.envBP, d.recBP]])
  tabela.setDadosRelatorio(dadosRelatorio);
  tabela.createTable();
  tabela.position();
  tabela.setState("ON");
})

circleCat.on("click", function(d){
  var transform;
  var posX = 0;
  var posY = 0;
  var escala = 1;
  circleClick = true;

  /*
  * lógica para encontrar os grupos do usuário
  */
  var dados = [];
  for(var i = 0; i < grupos.length; i++){
    if(searchInGroup(d.id, grupos[i])){
      var nomes = participantesGrupo(d.id, grupos[i]);
      dados.push([i+1, nomes]);
    }
  }
  if(dados.length == 0)
    dados = [["Nenhum Grupo", "Sem Participantes"]];


  if(tabela.getState() == "ON"){
    tabela.hideTable();
    tabela.setState("OFF");
    return;
  }

  tabela.container("tabs-2");
  tabela.boxTitle();
  tabela.setUser(d);
  tabela.grupos = dados;
  tabela.setDadosRelatorio(dadosRelatorio);
  tabela.setTitle(d.nome);
 

  switch(modo)
  {
    case '1':{
      tabela.setHeaders(["Funcionalidade", "Grau de Colaboração"]);
      tabela.setData([[" Biblioteca", d.ColabBib], [" Fórum", d.ColabForum], [" Webfólio", d.ColabWF], [" Bate-Papo", d.ColabBP]])
      tabela.createTable();
      break;
    }
    case '2':{
      var recebidas_enviadas = getMensagensInfo(d.id);
      
      if(d.distR == '1'){
      	var dado =  recebidas_enviadas[0];
      	tabela.setHeaders(["Quantidade de mensagens recebidas e não respondidas: "+dado]);
      	tabela.setData([]);
      	tabela.createTable(); 
      }else{
      	tabela.setHeaders(["O usuário não é distanciado em relação a turma"]);
      	tabela.createTable();
      }
      break;
    }
    case '3':{      
      var recebidas_enviadas = getMensagensInfo(d.id);
      tabela.setData([]);
      if(d.distP == '1'){
      		var dado =  recebidas_enviadas[1];
      		tabela.setHeaders(["Quantidade de mensagens enviadas e não respondidas: "+dado]);
      		tabela.createTable();
      	}else{
      	tabela.setHeaders(["O usuário não é distanciado pela turma"]);
      	tabela.createTable();
      } 
      break;
    }
    case '4': {
      tabela.setHeaders(["Grau de Popularidade", d.popularidade])
      tabela.setData([]);
      tabela.createTable();
      break;
    }
    case '5': {
      tabela.setHeaders(["Agrupamentos", "Participantes"])
      
      tabela.setData(dados);
      tabela.createTable();
    }
  }
  tabela.position();
  tabela.setState("ON");
})

circleCat.on("mouseup", function(d) {

      if(focus_node!==null)
      {
        focus_node = null;
        if (highlight_trans<1)
        {
          switch(modo)
          {
            case '0':circleCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans);
                 textCat.style("opacity", highlight_trans);
                 setaCat.style("opacity", highlight_trans); break;
            case '1':circleCat.style("opacity", function(d){if((d.colab >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.colab   >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans); break;
           case '2':circleCat.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans); break;
           case '3':circleCat.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans); break;
           case '4':circleCat.style("opacity", function(d){if((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if ((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans); break;
            case '5': break;
            case '6': circleCat.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textCat.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaCat.style("opacity", highlight_trans);
                 pathCat.style("opacity", highlight_trans); break;
          }
        }
      }

      if (highlight_node === null) exit_highlight("cat");
          d.fixed = true;
});

circleInt.on("mouseup", function(d) {

      if(focus_node!==null)
      {
        focus_node = null;
        if (highlight_trans<1)
        {
            circleInt.style("opacity", function(d){
              if(d.isSelected == '1')
                return 1;
              else return highlight_trans;
            });
            pathInt.style("opacity", function(d){
                      if(isSelectedById(d.source.id) =='0' || isSelectedById(d.target.id) == '0')
                        return highlight_trans;
                      else return 1;
          });
            textInt.style("opacity", function(d){
              if(d.isSelected == '1')
                return 1;
              else return highlight_trans;
            });
            setaInt.style("opacity", function(d){
                      if(isSelectedById(d.source.id) =='0' || isSelectedById(d.target.id) == '0')
                        return highlight_trans;
                      else return 1;
          });
        }
      }

      if (highlight_node === null) exit_highlight("int");
          d.fixed = true;
});

circleInt.on("mouseover", function(d){
  set_highlight(d, "int");

});



circleCat.on("mouseover", function(d){
  set_highlight(d, "cat");
  
})

circleCat.on("mousedown", function(d){
                            d3.event.stopPropagation();
                            if (highlight_node === null) set_highlight(d, "cat")
                            focus_node = d;
                            if(modo != '5')
                              set_focus(d, "cat")
})
.on("mouseout", function(d){
	exit_highlight("cat");
});

circleInt.on("mousedown", function(d){
                            d3.event.stopPropagation();
                            if (highlight_node === null) set_highlight(d, "int")
                            focus_node = d;
                            set_focus(d, "int")
                          }
)
.on("mouseout", function(d) {
  exit_highlight("int");
} );

var linkedByIndex = {};
  links.forEach(function(d) {
  linkedByIndex[d.source.index + "," + d.target.index] = true;
});

   

d3.select("#interrogacao")
  .on("click", function(d){
    d3.select("#help").style("visibility", "visible");
});
d3.select("#closeHelp").on("click", function(d){
  d3.select("#help").style("visibility", "hidden");
})


</script>
</div>

</body>

<?php/* 
}else if($interacaoContatos==0 && $interacaoBatepapo==0 && $interacaoForum==0 && $interacaoBiblioteca==0 && $interacaoA2==0 && $interacaoWebfolio==0) {
	echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
	echo 'alert ("Nenhum n\u00edvel de relev\u00e2ncia atribu\u00eddo.")';
	echo '</SCRIPT>';
}else if(count($_POST['checkboxMembros'])==0 || count($_POST['checkboxGrupos'])==0){
	echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
	echo 'alert ("Nenhum membro ou grupo selecionado.")';
	echo '</SCRIPT>';
} */
//$pagina->rodape();

require_once( 'future/footer.php' );

?>
