<?php
require_once(dirname(__FILE__).'/../sistema.inc.php');
require_once(dirname(__FILE__).'/sociograma.php');
require_once(dirname(__FILE__).'/Settings.php');

$pagina=new Pagina();
$settings = new Settings();
$codUsuario=intval($pagina->sessao->codUsuario);
$codTurma=intval($pagina->sessao->codTurma);

if(isset($_POST['dataInicio']))
	$settings->setDataInicio(db_escape($_POST['dataInicio']));

if(isset($_POST['dataFim']))
	$settings->setDataFim(db_escape($_POST['dataFim']));

if(isset($_POST['checkboxMembros'])) {
  $arrayMembros=($_POST['checkboxMembros']);
  $arrayGrupos=($_POST['checkboxGrupos']);
}
else{
	$pesquisaMembros=db_busca(' SELECT tu.codUsuario
                                FROM   (SELECT codUsuario
                                        FROM TurmaUsuario
                                        WHERE codTurma="'.$codTurma.'")
                                        AS tu INNER JOIN Usuario
                                        AS u ON tu.codUsuario=u.codUsuario
                                        ORDER BY u.nome ASC');
	$arrayMembros = array();
	foreach($pesquisaMembros as $membro){
        array_push($arrayMembros, $membro['codUsuario']);
    }
    $arrayGrupos = null;  
}

if(isset($_POST['corAluno']))
	$settings->setCorAluno($_POST['corAluno']);

if(isset($_POST['corProfessor']))
	$settings->setCorProf($_POST['corProfessor']);

if(isset($_POST['corMonitor']))
	$settings->setCorMonitor($_POST['corMonitor']);

if(isset($_POST['relcontatos']))
	$settings->setContatos($_POST['relcontatos']);

if(isset($_POST['relbatepapo']))
	$settings->setBatePapo($_POST['relbatepapo']);

if(isset($_POST['relforum']))
	$settings->setForum($_POST['relforum']);

if(isset($_POST['relbiblioteca']))
	$settings->setBiblioteca($_POST['relbiblioteca']);

if(isset($_POST['rela2']))
	$settings->setA2($_POST['rela2']);

if(isset($_POST['relwebfolio']))
	$settings->setWebfolio($_POST['relwebfolio']);

//$pagina=new Pagina();
//$pagina->cabecalho('MapaSocial');



?>
<!--Gera a imagem do mapa social, passando os parâmetros pelo link-->
<link rel="stylesheet" type="text/css" href= "css/mostragrafo.css">
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

<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
<?php 
 require_once( 'future/header.php' ); 
 require_once( 'future/content.php');
?>

<script id="Grafo">
   var edges = [];
   var nodes = [];
   var links = [];



   <?
		$interacaoA2 = $settings->getA2();
		$interacaoBatepapo = $settings->getBatePapo();
		$interacaoBiblioteca = $settings->getBiblioteca();
		$interacaoContatos = $settings->getContatos();
		$interacaoForum = $settings->getForum();
		$interacaoWebfolio = $settings->getWebfolio();
   
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
	 $sociograma=new Sociograma($codUsuario,$codTurma,$settings,$arrayMembros,$arrayGrupos);
   ?>
   


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
var width = document.getElementById("tabs-1").offsetWidth;
var height = 0.9*document.getElementById("tabs-1").offsetHeight;


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


var zoom = d3.behavior.zoom()
			 .scaleExtent([1, 8])
			 .on("zoom", zoomHandler);


var mapInt = d3.select("#tabs-1").append("svg")
	            .attr("width", width)
	            .attr("height", height)
	 			.style("transform-origin", "50% 50% 0")
	            .call(zoom)
	            .on("dblclick.zoom", null)
	            .on("wheel.zoom", null)
	            .append("g");

d3.selectAll(".zoom_button").on("click", function(){
	var clicked = this,
        direction = 1,
        factor = 0.2,
        target_zoom = 1,
        center = [width / 2, height / 2],
        extent = zoom.scaleExtent(),
        translate = zoom.translate(),
        translate0 = [],
        l = [],
        view = {x: translate[0], y: translate[1], k: zoom.scale()};

    d3.event.preventDefault();
    switch(this.id){
    	case 'z_in':  target_zoom = zoom.scale()*(1+factor);break;
    	case 'z_out': target_zoom = zoom.scale()*(1-factor); break;

    	case 'z_def': target_zoom = 1; break;
    }
    if (target_zoom < extent[0] || target_zoom > extent[1]) { return false; }

    translate0 = [(center[0] - view.x) / view.k, (center[1] - view.y) / view.k];
    view.k = target_zoom;
    l = [translate0[0] * view.k + view.x, translate0[1] * view.k + view.y];

    view.x += center[0] - l[0];
    view.y += center[1] - l[1];

    interpolateZoom([view.x, view.y], view.k);
})


mapInt.append("defs").selectAll("marker")
      .data(["suit"])
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

var circleInt = mapInt.append("g").selectAll("circleInt")
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

var textInt = mapInt.append("g").selectAll("text")
    .data(force.nodes())
    .enter().append("text")
    .attr("x", 8)
    .attr("y", ".31em")
    .style("opacity", function(d){if(d.isSelected == '1') return 1; else return highlight_trans})
    .style("font-size", 16)
    .text(function(d) { return d.name; });

grupos.forEach(function(d, i){
  var buttonName = "Grupo "+grupoString(i+1);
  d3.select("#buttons")
  .append("button")
  .attr("class", "mdl-button mdl-js-button mdl-button--raised botao-grupo")
  .text(buttonName)
  .on("click", selectGroup);
});

d3.select("#buttons").style("visibility", "hidden");

d3.select("#tabs-1").on("click", function(){
	if(tabela.getState() == "ON" && !circleClick){
		tabela.hideTable();
		tabela.setState("OFF");
	}
	circleClick = false;
})

d3.select("#mapInt").on("click", function(){
	circleInt.style("opacity", 1);
    setaInt.style("opacity", 1);
    pathInt.style("opacity", 1);
    textInt.style("opacity", 1);
	d3.select("#buttons").style("visibility", "hidden")
 	d3.select("#Selecao").style("visibility", "hidden");
 	d3.select("#nivel-relevancia").style("display", "none");
	d3.select("#help-frame").attr("src", "help_interacao.html");
  	tabela.typeMap = 0
});

d3.select("#mapCat").on("click", function(){    
	d3.select("#Selecao").style("visibility", "visible");
 	d3.select("#nivel-relevancia").style("display", "inline");
	d3.select("#help-frame").attr("src", "help_indicador.html");
	modo = d3.select("#Selecao").property("value");
  tabela.typeMap = 1

	switch(modo){
		case '0':circleInt.style("opacity", highlight_trans);
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 textInt.style("opacity", highlight_trans);
                 hideButtons();
                 break;
		case '1':circleInt.style("opacity", function(d){if((d.colab >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if((d.colab   >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Colaboração': "+ estrategiaColab[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '2':circleInt.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Ausência': " + estrategiaDSRT[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '3':circleInt.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Distanciamento pela Turma': " + estrategiaDSPT[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '4':circleInt.style("opacity", function(d){if((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if ((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Popularidade': " + estrategiaPop[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '5':circleInt.style("opacity", highlight_trans);
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 textInt.style("opacity", highlight_trans);
                 d3.select("#buttons").style("visibility", "visible");
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Grupos Informais': " +estrategiaAgrup[Math.floor(Math.random()*6)];
                 break;
        case '6':circleInt.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
            		 textInt.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Evasão': " +estrategiaEvasao[Math.floor(Math.random()*6)];
            		 hideButtons();
	}
})

d3.select("#Selecao").on("change", function(){
      modo = d3.select("#Selecao").property("value");
      textInt.style("font-weight", "normal");

      switch(modo)
      {
		    case '0':circleInt.style("opacity", highlight_trans);
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
				 textInt.style("opacity", highlight_trans);
                 hideButtons();
                 break;
        case '1':circleInt.style("opacity", function(d){if((d.colab >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if((d.colab   >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Colaboração': "+ estrategiaColab[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '2':circleInt.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Ausência': " + estrategiaDSRT[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '3':circleInt.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica o para indicador de interação 'Distanciamento pela Turma': " + estrategiaDSPT[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '4':circleInt.style("opacity", function(d){if((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 textInt.style("opacity", function(d){if ((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Popularidade': " + estrategiaPop[Math.floor(Math.random()*6)];
                 hideButtons();
                 break;
        case '5':circleInt.style("opacity", highlight_trans);
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 textInt.style("opacity", highlight_trans);
                 d3.select("#buttons").style("visibility", "visible");
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para a indicador de interação social 'Grupos Informais': " +estrategiaAgrup[Math.floor(Math.random()*6)];
                 break;
        case '6':circleInt.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
            		 textInt.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                 setaInt.style("opacity", highlight_trans);
                 pathInt.style("opacity", highlight_trans);
                 tabela.textoEstrategia = "Sugestão de estratégia pedagógica para o indicador de interação social 'Evasão': " +estrategiaEvasao[Math.floor(Math.random()*6)];
                 hideButtons();
      }
});


circleInt.on("click", function(d){

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
  tabela.setDadosRelatorio(dadosRelatorio);
  tabela.setInteracoes(edges);
  
  if(tabela.typeMap == 0){
    tabela.setHeaders(["Funcionalidade", "Enviadas", "Recebidas"])
    tabela.setData([[" Biblioteca", d.envBib, d.recBib], 
            [" Contatos", d.envCO, d.recCO], 
            [" A2", d.envA2, d.recA2], 
            [" Fórum", d.envForum, d.recForum], 
            [" Webfólio", d.envWF, d.recWF], 
            [" Bate-Papo", d.envBP, d.recBP]])

    tabela.createTable();
    tabela.position();
    tabela.setState("ON");
  }
  else{
    var dados = []
    for(var i = 0; i < grupos.length; i++){
      if(searchInGroup(d.id, grupos[i])){
        var nomes = participantesGrupo(d.id, grupos[i]);
        dados.push([i+1, nomes]);
      }
    }
    if(dados.length == 0)
      dados = [["Nenhum Grupo", "Sem Participantes"]];

    tabela.grupos = dados;
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
        tabela.setHeaders(["Grupos Informais", "Participantes"])
        
        tabela.setData(dados);
        tabela.createTable();
      }
    }
    tabela.position();
    tabela.setState("ON");
  }

  
})

circleInt.on("mouseup", function(d) {

      if(focus_node!==null)
      {
        focus_node = null;
        if (highlight_trans<1)
        {
          if(tabela.typeMap == 1){
            switch(modo)
            {
              case '0':circleInt.style("opacity", highlight_trans);
                   pathInt.style("opacity", highlight_trans);
                   textInt.style("opacity", highlight_trans);
                   setaInt.style("opacity", highlight_trans); break;
              case '1':circleInt.style("opacity", function(d){if((d.colab >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                   textInt.style("opacity", function(d){if((d.colab   >= mediaColab) && d.isSelected == '1') return 1; else return highlight_trans;});
                   setaInt.style("opacity", highlight_trans);
                   pathInt.style("opacity", highlight_trans); break;
             case '2':circleInt.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                   textInt.style("opacity", function(d){if((d.distR== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                   setaInt.style("opacity", highlight_trans);
                   pathInt.style("opacity", highlight_trans); break;
             case '3':circleInt.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                   textInt.style("opacity", function(d){if((d.distP== '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                   setaInt.style("opacity", highlight_trans);
                   pathInt.style("opacity", highlight_trans); break;
             case '4':circleInt.style("opacity", function(d){if((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                   textInt.style("opacity", function(d){if ((d.popularidade >= desvioPopular + mediaPop) && d.isSelected == '1') return 1; else return highlight_trans;});
                   setaInt.style("opacity", highlight_trans);
                   pathInt.style("opacity", highlight_trans); break;
             case '5': break;
             case '6': circleInt.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                   textInt.style("opacity", function(d){if((d.isolado == '1') && d.isSelected == '1') return 1; else return highlight_trans;});
                   setaInt.style("opacity", highlight_trans);
                   pathInt.style("opacity", highlight_trans); break;
            }
          }
          else{
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
    }

      if (highlight_node === null) exit_highlight(tabela.typeMap);
          d.fixed = true;
});

circleInt.on("mouseover", function(d){
   set_highlight(d, tabela.typeMap);
});

circleInt.on("mousedown", function(d){
  d3.event.stopPropagation();
  if (highlight_node === null) set_highlight(d, tabela.typeMap)
  focus_node = d;
  if(modo != 5)
   set_focus(d, tabela.typeMap)
})
.on("mouseout", function(d) {
  exit_highlight(tabela.typeMap);
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

<?php
if($interacaoContatos==0 && $interacaoBatepapo==0 && $interacaoForum==0 && $interacaoBiblioteca==0 && $interacaoA2==0 && $interacaoWebfolio==0) {
	echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
	echo 'alert ("Nenhum n\u00edvel de relev\u00e2ncia atribu\u00eddo.")';
	echo '</SCRIPT>';
}
// }else if(count($_POST['checkboxMembros'])==0 || count($_POST['checkboxGrupos'])==0){
// 	// echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
// 	// echo 'alert ("Nenhum membro ou grupo selecionado.")';
// 	// echo '</SCRIPT>';
// }
//$pagina->rodape();

require_once( 'future/footer.php' );

?>
