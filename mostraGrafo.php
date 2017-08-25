<?php
// require_once(dirname(__FILE__).'/../sistema.inc.php');
require_once(dirname(__FILE__).'/sociograma.php');

// $pagina=new Pagina();
// $codUsuario=intval($pagina->sessao->codUsuario);
// $codTurma=intval($pagina->sessao->codTurma);

// if(isset($_POST['layout']))
// 	$layout=db_escape($_POST['layout']);
// else
// 	$layout='';
// if(isset($_POST['directed']))
// 	$directed=db_escape($_POST['directed']);
// else
// 	$directed='';
// if(isset($_POST['dataInicio']))
// 	$dataInicio=db_escape($_POST['dataInicio']);
// else
// 	$dataInicio='';
// if(isset($_POST['dataFim']))$dataFim=db_escape($_POST['dataFim']);
// else
// 	$dataFim='';
//
//
// if(isset($_POST['checkboxMembros'])) {
//   $arrayMembros=($_POST['checkboxMembros']);
//   $arrayGrupos=($_POST['checkboxGrupos']);
//   }
// else{
//   $arrayMembros=($_POST['checkboxMembros']);
//   $arrayGrupos=($_POST['checkboxGrupos']);
// }
//
//
//
//
// if(isset($_POST['corAluno']))
// 	$corAluno=$_POST['corAluno'];
// else
// 	$corAluno="#A999cc";
//
// if(isset($_POST['corProfessor']))
// 	$corProfessor=$_POST['corProfessor'];
// else
// 	$corProfessor="#FF8000";
//
// if(isset($_POST['corMonitor']))
// 	$corMonitor=$_POST['corMonitor'];
// else
// 	$corMonitor="#CCCCCC";
//
// if(isset($_POST['relcontatos']))	//post dos niveis de relevancia que serão obtidos em 'geraGrafo.php'
// 	$interacaoContatos=$_POST['relcontatos'];
//
// if(isset($_POST['relbatepapo']))
// 	$interacaoBatepapo=$_POST['relbatepapo'];
//
// if(isset($_POST['relforum']))
// 	$interacaoForum=$_POST['relforum'];
//
// if(isset($_POST['relbiblioteca']))
// 	$interacaoBiblioteca=$_POST['relbiblioteca'];
//
// if(isset($_POST['rela2']))
// 	$interacaoA2=$_POST['rela2'];
//
// if(isset($_POST['relwebfolio']))
// 	$interacaoWebfolio=$_POST['relwebfolio'];
//
// //$pagina=new Pagina();
// //$pagina->cabecalho('MapaSocial');
//
// if((count($_POST['checkboxMembros']) != 0 || count($_POST['checkboxGrupos']) != 0) && !($interacaoContatos==0 && $interacaoBatepapo==0 && $interacaoForum==0 && $interacaoBiblioteca==0 && $interacaoA2==0 && $interacaoWebfolio==0)) {

?>
<!--Gera a imagem do mapa social, passando os parâmetros pelo link-->
<?php
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
 ?>

<link rel="stylesheet" type="text/css" href= "<?php echo $actual_link . '/sociograma/css/mostragrafo.css' ?>">
<script src="<?php echo $actual_link . '/sociograma/js/librarys/d3.min.js'; ?>" charset="utf-8"></script>

<body style="margin:0;">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<link rel="stylesheet" href="/resources/demos/style.css">
<link rel="stylesheet" href="/css/framework/jquery-ui.structure.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="js/framework/jquery-3.2.1.min.js"></script>
<script src="js/framework/jquery-ui.min.js"></script>
<script src="js/librarys/material.min.js"></script>
<link rel="stylesheet" href="css/general.css">

<script src="tabela.js"></script>
<script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
</script>
<div class="mdl-layout mdl-js-layout  mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
	<div id = "modal">
		<div id="closeModal">X</div>
		Aqui vai o Relatório e o link para abrir um pdf.
	</div>
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

	<?php require_once( 'future/header.php' ); ?>

	<?php require_once( 'future/content.php' ); ?>

		<!-- Menu comentado -->

	<!-- <div id="tabs" style="background-color: #dedede">
		<img id ="interrogacao" src="Imagens/interrogacao.png" style="display:none;">
		<ul>
			<li id="mapInt" class="mdl-button mdl-js-button  mdl-button--colored"><a href="#tabs-1">Mapa Interações</a></li>
			<li id="mapCat" class="mdl-button mdl-js-button  mdl-button--colored"><a href="#tabs-2">Mapa Categorias</a></li>
		</ul>

		<div>
			<br>
			<input type="button" value="Aumentar Mapa" OnClick="zoomClick(1.1)" />
			<input type="button" value="Diminuir Mapa" OnClick="zoomClick(0.9)" />
			<input type="button" value="Mapa Original" OnClick="zoomClick(0)" />
			<button name="button" OnClick="zoomClick(1.1)"> coisas </button>
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
	</div> -->

	<script type="text/javascript" src="js/json/links.json"></script>
	<script type="text/javascript" src="js/json/edges.json"></script>
	<script type="text/javascript" src="js/json/nodes.json"></script>
	<script type="text/javascript" src="js/json/mensagens.json"></script>
	<script id="Grafo">




		<?php
		//  function printEstrategias($estrategias){
		//    echo "'".$estrategias[0]['Estrategias']."'";
		//     for($i = 1; $i < 6; $i++){
		//       echo ",'".$estrategias[$i]['Estrategias']."'";
		//    }
		//  }
		//
		//  echo "var estrategiaColab = [";
		//  printEstrategias(db_busca("SELECT `Estrategias`
		//                           FROM  `Estrategias&Categorias`
		//                           WHERE  `Categorias` =  'Colaboracao'"));
		//  echo "];";
		//
		//  echo "var estrategiaAgrup = [";
		//  printEstrategias(db_busca("SELECT `Estrategias`
		//                           FROM  `Estrategias&Categorias`
		//                           WHERE  `Categorias` =  'Agrupamento'"));
		//  echo "];";
		//
		//  echo "var estrategiaPop = [";
		//  printEstrategias(db_busca("SELECT `Estrategias`
		//                           FROM  `Estrategias&Categorias`
		//                           WHERE  `Categorias` =  'Popularidade'"));
		//  echo "];";
		//
		//  echo "var estrategiaDSRT = [";
		//  printEstrategias(db_busca("SELECT `Estrategias`
		//                           FROM  `Estrategias&Categorias`
		//                           WHERE  `Categorias` =  'DSRT'"));
		//  echo "];";
		//
		//  echo "var estrategiaDSPT= [";
		//  printEstrategias(db_busca("SELECT `Estrategias`
		//                           FROM  `Estrategias&Categorias`
		//                           WHERE  `Categorias` =  'DSPT'"));
		//  echo "];";
		//
		//  echo "var pesoWF = ".$interacaoWebfolio.";\n";
		//  echo "var pesoBP = ".$interacaoBatepapo.";\n";
		//  echo "var pesoCo = ".$interacaoContatos.";\n";
		//  echo "var pesoA2 = ".$interacaoA2.";\n";
		//  echo "var pesoFo = ".$interacaoForum.";\n";
		//  echo "var pesoBib = ".$interacaoBiblioteca.";\n";
		//
		//  $sociograma=new Sociograma($codUsuario,$codTurma,$layout,$directed,$dataInicio,$dataFim,$corAluno,$corProfessor,$corMonitor,$interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio,$arrayMembros,$arrayGrupos);
		?>

		edges.forEach(function(e) {
			// Get the source and target nodes

			var sourceNode = nodes.filter(function(n) { return n.id === e.source; })[0],
			targetNode = nodes.filter(function(n) { return n.id === e.target; })[0],
			valueLink = e.value;

			// Add the edge to the array
			links.push({source: sourceNode, target: targetNode, value: valueLink});
		});

		var circleClick = false;
		var modo = '0';
		var default_link_color = "#888";
		var highlight_node = null;
		var highlight_color = "blue";
		var highlight_trans = 0.1;
		var focus_node = null;
		var outline = false;
		var tocolor = "fill";
		var towhite = "stroke";
		var width = document.getElementById("tabs-2").offsetWidth;
		var height = window.innerHeight;

		var wheelScale;
		var wheelTranslate;

		if (outline) {
			tocolor = "stroke"
			towhite = "fill"
		}


		function zoomHandler() {
			svgs = d3.selectAll("svg");
			svgInteracao = svgs[0][0];
			svgCategoria = svgs[0][1];

			var posX = 0;
			var posY = 0;

			if(d3.select(svgCategoria).select("g").attr("transform") == null)
			scale = 1;
			else{
				translate = d3.select(svgCategoria).select("g").attr("transform").replace("translate(", "");
				translate = translate.replace(")", "");
				posX = parseFloat(pegaX(translate));    //posX e posY é do container, e não dos nodos
				translate = translate.replace(posX+",", "");
				posY = parseFloat(pegaY(translate));
				translate = translate.replace(posY+"scale(", "");
				scale = parseFloat(getScale(translate));
			}

			mapCat.attr("transform", "translate(" + d3.event.translate + ")scale(" + scale + ")");
			mapInt.attr("transform", "translate(" + d3.event.translate + ")scale(" + scale + ")");

		}

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
		var grupos = [];
		grupos.forEach(function(d, i){
			d3.select("#buttons")
			.append("input")
			.attr("type", "button")
			.attr("value", "Agrupamento "+(i+1))
			.on("click", selectGroup);
		});

		d3.select("#buttons").style("visibility", "hidden");

		textBox.on("mousedown", function(){
			textBox.on("mousemove", function(){

				height = sizetoNumber(textBox.style("height"));
				width = sizetoNumber(textBox.style("width"));
				d3.select("#boxEstrategia").style("top", d3.event.y-height/2).style("left", d3.event.x-width/2);
			});
		});

		textBox.on("mouseup", function(){
			textBox.on("mousemove", null);
		});
		d3.select("body").on("mouseup", function(){
			textBox.on("mousemove", null);
		})

		function sizetoNumber(size){
			return Number(size.split("px")[0]);
		}

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

		d3.select("#mapCat").on("click", function(){
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
				textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Distanciamento em Relação a Turma': " + estrategiaDSRT[Math.floor(Math.random()*6)]);
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
				box.style("visibility", visible);
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
				textBox.text("Sugestão de estratégia pedagógica para a categoria de informação social 'Distanciamento em Relação a Turma': " + estrategiaDSRT[Math.floor(Math.random()*6)]);
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
				box.style("visibility", "hidden");
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

			if((transform = d3.select("#tabs-1").select("svg").select("g").attr("transform")))
			{
				transform = transform.replace("translate(", "");
				transform = transform.replace(")", "");
				posX = parseFloat(pegaX(transform));    //posX e posY é do container, e não dos nodos
				transform = transform.replace(posX+",", "");
				posY = parseFloat(pegaY(transform));
				transform = transform.replace(posY+"scale(", "");
				escala = parseFloat(getScale(transform));
			}
			else transform = 0;

			if(tabela.getState() == "ON"){
				tabela.hideTable();
				tabela.setState("OFF");
				return;
			}

			tabela.container("tabs-1");
			tabela.boxTitle();
			tabela.setTitle(d.nome);
			tabela.setHeaders(["Funcionalidade", "nº de Interações"])
			tabela.setData([[" Biblioteca", d.biblioteca], [" Contatos", d.contato], [" A2", d.a2], [" Fórum", d.forum], [" Webfólio", d.wf], [" Bate-Papo", d.bp]])
			tabela.createTable();
			tabela.position(((-posY+ (-d.y+820)*escala-50)+"px"), ((posX+(d.x)*escala+30)+"px"));
			tabela.setState("ON");
		})

		circleCat.on("click", function(d){
			var transform;
			var posX = 0;
			var posY = 0;
			var escala = 1;
			circleClick = true;

			if((transform = d3.select("#tabs-2").select("svg").select("g").attr("transform")))
			{
				transform = transform.replace("translate(", "");
				transform = transform.replace(")", "");
				posX = parseFloat(pegaX(transform));    //posX e posY é do container, e não dos nodos
				transform = transform.replace(posX+",", "");
				posY = parseFloat(pegaY(transform));
				transform = transform.replace(posY+"scale(", "");
				escala = parseFloat(getScale(transform));
			}
			else transform = 0;

			if(tabela.getState() == "ON"){
				tabela.hideTable();
				tabela.setState("OFF");
				return;
			}

			tabela.container("tabs-2");
			tabela.boxTitle();
			tabela.setTitle(d.nome);


			switch(modo)
			{
				case '1':{
					tabela.setHeaders(["Funcionalidade", "Grau de Colaboração"]);
					tabela.setData([[" Biblioteca", d.colabBib],  [" A2", 0], [" Fórum", d.colabF], [" Webfólio", d.colabWF], [" Bate-Papo", d.colabBP]])
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
					var dados = [];
					for(var i = 0; i < grupos.length; i++){
						if(searchInGroup(d.id, grupos[i])){
							var nomes = participantesGrupo(d.id, grupos[i]);
							dados.push([i+1, nomes]);
						}
					}
					if(dados.length == 0)
					dados = [["Nenhum Grupo", "Sem Participantes"]];
					tabela.setData(dados);
					tabela.createTable();
				}
			}
			tabela.position(((-posY+ (-d.y+820)*escala-50)+"px"), ((posX+(d.x)*escala+30)+"px"));
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

			/*  divInt.transition()
			.duration(1)
			.style("top", (posY+ (d.y-10)*escala-100)+"px")
			.style("left", (posX+(d.x-10)*escala-150)+"px") //vai plotar a tooltip nesse lugar
			.style("opacity", 9);
			*/

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


		// Use elliptical arc path segments to doubly-encode directionality.
		function tick() {

			pathInt.attr("points", fazLinks);
			setaInt.attr("points", fazSeta);
			circleInt.attr("transform", transform);
			textInt.attr("transform", transform);

			pathCat.attr("points", fazLinks);
			setaCat.attr("points", fazSeta);
			circleCat.attr("transform", transform);
			textCat.attr("transform", transform);
		}

		function fazLinks(d)
		{
			var deltaX = d.target.x - d.source.x;
			var deltaY = d.target.y - d.source.y;
			var distance = Math.sqrt(deltaY*deltaY+deltaX*deltaX);
			var raio = Number(circleCat.attr("r")) + 1.5;
			var sinA = deltaY/distance;
			var cosA = deltaX/distance;
			var dy = raio*sinA;
			var dx = raio*cosA;
			return  (d.source.x + dx) + "," + (d.source.y+dy) + " " +
			(d.target.x-dx) + "," + (d.target.y-dy);
		}

		function fazSeta(d)
		{
			var cos30 = 0.866025;
			var value = d.value;
			var deltaX = d.target.x - d.source.x;
			var deltaY = d.target.y - d.source.y;
			var distance = Math.sqrt(deltaY*deltaY+deltaX*deltaX);
			var raio = Number(circleCat.attr("r")) + 1;
			var sinA = deltaY/distance;
			var cosA = deltaX/distance;
			var x0 = d.target.x - raio*cosA;
			var y0 = d.target.y - raio*sinA;
			var dy = cos30*sinA + cosA/2;
			var dx = cos30*cosA - sinA/2;
			var y1 = y0 - dy*value/cos30;
			var x1 = x0 - dx*value/cos30;
			var xa = x0 - value*cosA;
			var ya = y0 - value*sinA;
			var x2 = xa - (x1 - xa);
			var y2 = ya - (y1 - ya);

			return x0+","+y0+" "+x1+","+y1+" "+x2+","+y2;
		}

		function procuraPorID(array, id){
			for(var i = 0; i < array.lenght; i++){
				if(array[i].id == id) return i;
			}
			return false;
		}

		function corGrupo(grupo){
			var arrayCores = ["#FF5733", "#33B2FF", "#4B6D81", "#F065E5", "#9B9CA6", "#6AD340", "#265A11", "#ECF018", "#000000",
			"#E6B3F9", "#FF0000", "#0000FF", "#C9EAAC", "#6000FE", "#AFADB2", "#C3FFE9", "#4C5A55", "#17107E"];

			return arrayCores[grupo];
		}

		function groupStringtoArray(string){
			var arrayVazio = [];
			if(string)
			return string.split(",");
			return arrayVazio;
		}

		function transform(d) {
			return "translate(" + d.x + "," + d.y + ")";
		}

		function set_highlight(d, mapa){
			mapCat.style("cursor","pointer");
			mapInt.style("cursor","pointer");

			if (focus_node!==null) d = focus_node;
			highlight_node = d;

			if (highlight_color!="white")
			{
				if(mapa == "cat"){
					circleCat.style(towhite, function(o) {
						return isConnected(d, o) ? highlight_color : "white";});
						textCat.style("font-weight", function(o) {
							return isConnected(d, o) ? "bold" : "normal";});
							pathCat.style("stroke", function(o) {
								return o.source.index == d.index || o.target.index == d.index ? highlight_color : ((isNumber(o.score) && o.score>=0)?color(o.score):default_link_color);
							});
						}
						if(mapa == "int"){
							circleInt.style(towhite, function(o) {
								return isConnected(d, o) ? highlight_color : "white";});
								textInt.style("font-weight", function(o) {
									return isConnected(d, o) ? "bold" : "normal";});
									pathInt.style("stroke", function(o) {
										return o.source.index == d.index || o.target.index == d.index ? highlight_color : ((isNumber(o.score) && o.score>=0)?color(o.score):default_link_color);
									});
								}
							}
						}

						function isNumber(n) {
							return !isNaN(parseFloat(n)) && isFinite(n);
						}

						function exit_highlight(mapa){
							highlight_node = null;
							if(mapa == "cat"){
								if (focus_node===null)
								{
									mapCat.style("cursor","move");

									if (highlight_color!="white")
									{
										circleCat.style(towhite, "white");
										pathCat.style("stroke", function(o) {return (isNumber(o.score) && o.score>=0)?color(o.score):default_link_color});
									}
								}
							}
							if(mapa == "int"){
								if (focus_node===null)
								{
									mapInt.style("cursor", "move");

									if (highlight_color!="white")
									{
										circleInt.style(towhite, "white");
										pathInt.style("stroke", function(o) {return (isNumber(o.score) && o.score>=0)?color(o.score):default_link_color});
									}
								}
							}
						}

						function set_focus(d, mapa){

							if (highlight_trans<1)
							{
								if(mapa == "cat"){
									circleCat.style("opacity", function(o) {
										if(isConnected(d,o)) return  1;
										else return highlight_trans;
									});

									textCat.style("opacity", function(o) {
										return isConnected(d, o) ? 1 : highlight_trans;
									});

									pathCat.style("opacity", function(o) {
										return o.source.index == d.index || o.target.index == d.index ? 1 : highlight_trans;
									});

									setaCat.style("opacity", function(o) {
										return o.source.index == d.index || o.target.index == d.index ? 1 : highlight_trans;
									});
								}
								if(mapa == "int"){
									circleInt.style("opacity", function(o) {
										if(isConnected(d,o)) return  1;
										else return highlight_trans;
									});

									textInt.style("opacity", function(o) {
										return isConnected(d, o) ? 1 : highlight_trans;
									});

									pathInt.style("opacity", function(o) {
										return o.source.index == d.index || o.target.index == d.index ? 1 : highlight_trans;
									});

									setaInt.style("opacity", function(o) {
										return o.source.index == d.index || o.target.index == d.index ? 1 : highlight_trans;
									});
								}
							}
						}
						function isConnected(a, b) {
							return linkedByIndex[a.index + "," + b.index] || linkedByIndex[b.index + "," + a.index] || a.index == b.index;
						}

						var linkedByIndex = {};
						links.forEach(function(d) {
							linkedByIndex[d.source.index + "," + d.target.index] = true;
						});

						function pegaX(string)
						{                               //o array transform vai ser algo como "x.xxxxx,y.yyyyyyscale(s.ssss)"
						var i = 0;                    //vai utilizar o array transform e percorre-lo até achar a virgula
						var retorno = [];             // e pondo no array de retorno
						while(string[i] != ',')
						{
							retorno.push(string[i]);
							i++;
						}

						return retorno.join('');   //o retorno.join vai fazer com que todos os caracteres dentro do array formem uma string única.
					}

					function pegaY(string)
					{
						var i =0;                   //o array transform vai ser algo como "y.yyyyyyscale(s.ssss)"
						var retorno = [];           //faz a mesma coisa que o pegaX (ver em cima dessa função)
						while(string[i] != 's')
						{
							retorno.push(string[i]);
							i++;
						}

						return retorno.join('');
					}

					function verColab(d)
					{
						if(d.colabF != 0)
						{
							return true
						}
					}

					function getScale(string)
					{
						var i =0;
						var retorno = [];
						while(string[i] != ')')
						{
							retorno.push(string[i]);
							i++;
						}
						return retorno.join('');
					}

					function getRelevancia(peso){
						var nivel = "";
						for(var i = 0; i < peso; i++){
							nivel = nivel + "*";
						}

						return nivel;
					}

					function isSelected(d){
						if(d.isSelected == 1) return 1;
						else return highlight_trans;
					}

					function selectGroup(){
						var grupo = d3.select(this).attr("value");
						grupo = grupo.split("Agrupamento ")[1] - 1;

						circleCat.style("opacity", highlight_trans);
						pathCat.style("opacity", highlight_trans);
						setaCat.style("opacity", highlight_trans);
						textCat.style("opacity", highlight_trans);

						for(var j = 0; j < grupos[grupo].length; j++){
							circleCat.each(function(d, i){
								if(grupos[grupo][j] == d.id){
									d3.select(this).style("opacity", 1);
									return;
								}
							});
							textCat.each(function(d, i){
								if(grupos[grupo][j] == d.id){
									d3.select(this).style("opacity", 1);
									return;
								}
							})
						}

						pathCat.each(function(d, i){
							if(searchInGroup(d.source.id, grupos[grupo]) && searchInGroup(d.target.id, grupos[grupo])){
								d3.select(this).style("opacity", 1);
							}
						})


						setaCat.each(function(d, i){
							if(searchInGroup(d.source.id, grupos[grupo]) && searchInGroup(d.target.id, grupos[grupo])){
								d3.select(this).style("opacity", 1);
							}
						})

					}


					function searchInGroup(key, grupo){
						for(var i = 0; i < grupo.length; i++){
							if(key == grupo[i])
							return true;
						}
						return false;
					}

					function hideButtons(){
						d3.select("#buttons").style("visibility", "hidden");
					}

					function nameByID(id){
						var nome;
						nodes.forEach(function(d, i){
							if(id == d.id){
								nome = d.nome;
								return;
							}
						});
						return nome;
					}

					function isSelectedById(id){
						var isSelected;
						nodes.forEach(function(d, i){
							if(id == d.id){
								isSelected = d.isSelected;
								return;
							}
						});
						return isSelected;
					}

					function participantesGrupo(id, grupo){
						var string = "";
						for(var i = 0; i<grupo.length; i++){
							if(id != grupo[i])
							string = string+nameByID(grupo[i]) +",";
						}
						string[string.length-1] = ' ';

						return string;
					}

					function getMensagensInfo(id){
						for(var i = 0; i < mensagens.length; i++){
							if(mensagens[i].id == id){
								return [mensagens[i].recebidas, mensagens[i].enviadas];
							}
						}
					}

					d3.select("#interrogacao")
					.on("click", function(d){
						d3.select("#help").style("visibility", "visible");
					});
					d3.select("#closeHelp").on("click", function(d){
						d3.select("#help").style("visibility", "hidden");
					})


				</script>




				<script type="text/javascript">
					function zoomClick(zm){
						svgs = d3.selectAll("svg");
						svgInteracao = svgs[0][0];
						svgCategoria = svgs[0][1];

						var posX = 0;
						var posY = 0;

						if(d3.select(svgCategoria).select("g").attr("transform") == null)
						scale = 1;
						else{
							translate = d3.select(svgCategoria).select("g").attr("transform").replace("translate(", "");
							translate = translate.replace(")", "");
							posX = parseFloat(pegaX(translate));    //posX e posY é do container, e não dos nodos
							translate = translate.replace(posX+",", "");
							posY = parseFloat(pegaY(translate));
							translate = translate.replace(posY+"scale(", "");
							scale = parseFloat(getScale(translate));
						}

						if(zm != 0){
							d3.select(svgCategoria).select("g").attr("transform", "translate("+posX+","+posY+")scale("+scale*zm+")")
							d3.select(svgInteracao).select("g").attr("transform", "translate("+posX+","+posY+")scale("+scale*zm+")")
						}
						else{
							d3.select(svgCategoria).select("g").attr("transform", "translate("+posX+","+posY+")scale(1)")
							d3.select(svgInteracao).select("g").attr("transform", "translate("+posX+","+posY+")scale(1)")
						}

					}
				</script>

</div>


</body>
<?php
// }else if($interacaoContatos==0 && $interacaoBatepapo==0 && $interacaoForum==0 && $interacaoBiblioteca==0 && $interacaoA2==0 && $interacaoWebfolio==0) {
// 	echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
// 	echo 'alert ("Nenhum n\u00edvel de relev\u00e2ncia atribu\u00eddo.")';
// 	echo '</SCRIPT>';
// }else if(count($_POST['checkboxMembros'])==0 || count($_POST['checkboxGrupos'])==0){
// 	echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">';
// 	echo 'alert ("Nenhum membro ou grupo selecionado.")';
// 	echo '</SCRIPT>';
// }
//$pagina->rodape();

?>

<?php require_once( 'future/footer.php' ); ?>
