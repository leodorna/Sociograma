<?php
require_once(dirname(__FILE__).'/../sistema.inc.php');
require_once(dirname(__FILE__).'/sociograma.php');
$pagina=new Pagina();
$codUsuario=intval($pagina->sessao->codUsuario);
$codTurma=intval($pagina->sessao->codTurma);
if(isset($_GET['layout']))
  $layout=db_escape($_GET['layout']);
else
  $layout='';
if(isset($_GET['directed']))
  $directed=db_escape($_GET['directed']);
else
  $directed='';
if(isset($_GET['dataInicio']))
  $dataInicio=db_escape($_GET['dataInicio']);
else
  $dataInicio='';
if(isset($_GET['dataFim']))
  $dataFim=db_escape($_GET['dataFim']);
else
  $dataFim='';
if(get_magic_quotes_gpc()===1) {
  $arrayMembros=unserialize(stripslashes(rawurldecode($_GET['checkboxMembros'])));
  $arrayGrupos=unserialize(stripslashes(rawurldecode($_GET['checkboxGrupos'])));
  }
else{
  $arrayMembros=unserialize(rawurldecode($_GET['checkboxMembros']));
  $arrayGrupos=unserialize(rawurldecode($_GET['checkboxGrupos']));
}
if(isset($_GET['corAluno']))
  $corAluno=db_escape($_GET['corAluno']);
else
  $corAluno='';
if(isset($_GET['corProfessor']))
  $corProfessor=db_escape($_GET['corProfessor']);
else
  $corProfessor='';
if(isset($_GET['corMonitor']))
  $corMonitor=db_escape($_GET['corMonitor']);
else
  $corMonitor='';

if(isset($_GET['interacaoContatos']))
  $interacaoContatos=db_escape($_GET['interacaoContatos']);

if(isset($_GET['interacaoBatepapo']))
  $interacaoBatepapo=db_escape($_GET['interacaoBatepapo']);

if(isset($_GET['interacaoForum']))
  $interacaoForum=db_escape($_GET['interacaoForum']);

if(isset($_GET['interacaoBiblioteca']))
  $interacaoBiblioteca=db_escape($_GET['interacaoBiblioteca']);

if(isset($_GET['interacaoA2']))
  $interacaoA2=db_escape($_GET['interacaoA2']);

if(isset($_GET['interacaoWebfolio']))
  $interacaoWebfolio=db_escape($_GET['interacaoWebfolio']);
?>
<!DOCTYPE html>
<html>
<head>

<style>

.link {
  fill: none;
  stroke: #666;
  stroke-width: 1.5px;
}

#suit {
  fill: #880012;
}


circle {
  fill: #ccc;
  stroke: #333;
  stroke-width: 1.5px;
}

text {
  font: 10px sans-serif;
  pointer-events: none;
  text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, 0 -1px 0 #fff, -1px 0 0 #fff;
}

</style>
<script src="<?php echo __DIR__.'/js/librarys/d3.min.js' ?>" charset="utf-8"></script>
<script id="code">
   var edges = [];
   var nodes = [];
   var links = [];

   <?php

   $sociograma=new Sociograma($codUsuario,$codTurma,$layout,$directed,$dataInicio,$dataFim,$corAluno,$corProfessor,$corMonitor,$interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio,$arrayMembros,$arrayGrupos);

   ?>

edges.forEach(function(e) {
    // Get the source and target nodes
    var sourceNode = nodes.filter(function(n) { return n.id === e.source; })[0],
        targetNode = nodes.filter(function(n) { return n.id === e.target; })[0],
        valueLink = e.value,
        typeLink = e.type;

    // Add the edge to the array
    links.push({source: sourceNode, target: targetNode, value: valueLink, type: typeLink });
});

var width = 960,
    height = 500;

function zoomHandler() {
  svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
}


var force = d3.layout.force()
    .nodes(d3.values(nodes))
    .links(links)
    .size([width, height])
    .linkDistance(60)
    .charge(-300)
    .on("tick", tick)
    .start();

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height)
  .call(d3.behavior.zoom().scaleExtent([1, 8]).on("zoom", zoomHandler))
        .append("g");

// Per-type markers, as they don't inherit styles.
svg.append("defs").selectAll("marker")
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

var path = svg.append("g").selectAll("path")
    .data(force.links())
  .enter().append("path")
    .attr("class", function(d) { return "link "})
    .attr("marker-end", function(d) { return "url(#suit)"; })
    .style("stroke-width", function(d) { return Math.sqrt(d.value); });

var circle = svg.append("g").selectAll("circle")
    .data(force.nodes())
  .enter().append("circle")
    .attr("r", 6)
    .call(force.drag);

var text = svg.append("g").selectAll("text")
    .data(force.nodes())
  .enter().append("text")
    .attr("x", 8)
    .attr("y", ".31em")
    .text(function(d) { return d.name; });

// Use elliptical arc path segments to doubly-encode directionality.
function tick() {
  path.attr("d", linkArc);
  circle.attr("transform", transform);
  text.attr("transform", transform);
}

function linkArc(d) {
  var dx = d.target.x - d.source.x,
      dy = d.target.y - d.source.y,
      dr = Math.sqrt(dx * dx + dy * dy);
  return "M" + d.source.x + "," + d.source.y + "A" + dr + "," + dr + " 0 0,1 " + d.target.x + "," + d.target.y;
}

function transform(d) {
  return "translate(" + d.x + "," + d.y + ")";
}

  </script>
</head>
<body onload="code">
 <!-- <input type = "button" value = "Isolados" onclick = "Solitarios()"></input>-->
  <div id="myDiagram" style="background-color: #e1e1e1 width:700px; height:500px"></div>
</body>
</html>
