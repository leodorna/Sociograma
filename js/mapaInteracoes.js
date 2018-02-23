   var nodes = [];
   var edges = [];
   var links = [];

  //  <?
  //      function printEstrategias($estrategias){
  //        echo "'".$estrategias[0]['Estrategias']."'";
  //         for($i = 1; $i < 6; $i++){
  //           echo ",'".$estrategias[$i]['Estrategias']."'";
  //        }
  //      }
   //
  //      echo "var estrategiaColab = [";
  //      printEstrategias(db_busca("SELECT `Estrategias`
  //                               FROM  `Estrategias&Categorias`
  //                               WHERE  `Categorias` =  'Colaboracao'"));
  //      echo "];";
   //
  //      echo "var estrategiaAgrup = [";
  //      printEstrategias(db_busca("SELECT `Estrategias`
  //                               FROM  `Estrategias&Categorias`
  //                               WHERE  `Categorias` =  'Agrupamento'"));
  //      echo "];";
   //
  //      echo "var estrategiaPop = [";
  //      printEstrategias(db_busca("SELECT `Estrategias`
  //                               FROM  `Estrategias&Categorias`
  //                               WHERE  `Categorias` =  'Popularidade'"));
  //      echo "];";
   //
  //      echo "var estrategiaDSRT = [";
  //      printEstrategias(db_busca("SELECT `Estrategias`
  //                               FROM  `Estrategias&Categorias`
  //                               WHERE  `Categorias` =  'DSRT'"));
  //      echo "];";
   //
  //      echo "var estrategiaDSPT= [";
  //      printEstrategias(db_busca("SELECT `Estrategias`
  //                               FROM  `Estrategias&Categorias`
  //                               WHERE  `Categorias` =  'DSPT'"));
  //      echo "];";
   //
  //      echo "var pesoWF = ".$interacaoWebfolio.";\n";
  //      echo "var pesoBP = ".$interacaoBatepapo.";\n";
  //      echo "var pesoCo = ".$interacaoContatos.";\n";
  //      echo "var pesoA2 = ".$interacaoA2.";\n";
  //      echo "var pesoFo = ".$interacaoForum.";\n";
  //      echo "var pesoBib = ".$interacaoBiblioteca.";\n";
   //
  //      $sociograma=new Sociograma($codUsuario,$codTurma,$layout,$directed,$dataInicio,$dataFim,$corAluno,$corProfessor,$corMonitor,$interacaoContatos,$interacaoBatepapo,$interacaoForum,$interacaoBiblioteca,$interacaoA2,$interacaoWebfolio,$arrayMembros,$arrayGrupos);
  //    ?>


edges.forEach(function(e) {
    // Get the source and target nodes
    var sourceNode = nodes.filter(function(n) { return n.id === e.source; })[0],
        targetNode = nodes.filter(function(n) { return n.id === e.target; })[0],
        valueLink = e.value;

    // Add the edge to the array
    links.push({source: sourceNode, target: targetNode, value: valueLink});
});

var modo = '0';
var default_link_color = "#888";
var highlight_node = null;
var highlight_color = "blue";
var highlight_trans = 0.1;
var focus_node = null;
var outline = false;
var tocolor = "fill";
var towhite = "stroke";
var width = 1400,
    height = 750;



if (outline) {
    tocolor = "stroke"
    towhite = "fill"
  }

function zoomHandler() {
    svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
}


var force = d3.layout.force()
    .nodes(d3.values(nodes))
    .links(links)
    .size([width, height])
     .linkDistance(120)
    .charge(-600)
    .on("tick", tick)
    .start();

var svg = d3.select("#tabs-1").append("svg")
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
          .enter().append("polyline")
          .attr("class", function(d) { return "link "});
    //.style("stroke-width", function(d) { return Math.sqrt(d.value); });

var circle = svg.append("g").selectAll("circle")
    .data(force.nodes())
  .enter().append("circle")
    .attr("r", 9)
    .style("fill", function(d) { if(d.isolado != '1') return d.cor; else return 'cccccc'})
    .style("opacity", function(d){if(d.isSelected == '1') return 1; else return highlight_trans})
    .call(force.drag);

var seta = svg.append("g").selectAll("seta")
          .data(force.links())
          .enter().append("polygon")
          .attr("fill", "#9c9c9c")
          .style("stroke-width", "1px")
          .style("stroke", "black");

 var div = d3.select("#myDiagram").append("div")   //é a tooltip
                    .attr("class", "tooltip")
                    .style("opacity", 0);
                    div.append("p").attr("class", "toolheader");
 var biblioteca = div.append("p").attr("class", "itens");
 var forum =  div.append("p").attr("class", "itens");
 var A2 = div.append("p").attr("class", "itens");
 var contatos = div.append("p").attr("class", "itens");
 var BP = div.append("p").attr("class", "itens");
 var WF = div.append("p").attr("class", "itens");

var text = svg.append("g").selectAll("text")
    .data(force.nodes())
  .enter().append("text")
    .attr("x", 8)
    .attr("y", ".31em")
    .style("opacity", function(d){if(d.isSelected == '1') return 1; else return highlight_trans})
    .style("font-size", 16)
    .text(function(d) { return d.name; });

var textBox = d3.select("#myDiagram>#boxEstrategia");

grupos.forEach(function(d, i){
                                  d3.select("#buttons")
                                  .append("input")
                                  .attr("type", "button")
                                  .attr("value", "Grupo "+i)
                                  .on("click", selectGroup);
                                });

d3.select("#buttons").style("visibility", "hidden");

  //textBox.append("p").attr("class", "text").text("oioioi oi oo io io io io ioi oioi oi oi o io io io ioi oi o ioi oi oi oi oi oi oi oi oi oioi").style("color", 'white');

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
    return size.split("px")[0];
  }
/*
  var estrategia = svg.append("rect")
                      .attr("x", 1)
                      .attr("y", 10)
                      .attr("width", 300)
                      .attr("height", 100)
                      .attr("fill", "#000000")
                      .style("opacity", 1)
                      .call(force.drag);
  var estrategiaText = svg.append('foreignObject')
                        .attr('x', 4)
                        .attr('y', 15)
                        .attr('width', 290)
                        .attr('height', 80)
                        .append("xhtml:body")
                        .text("HAHAHAHAHAHA HAHAHAHAHAHA AJHAHAHHA AHA HA HA HA HA HA HA HA HA HA HA HA HA AH AH AH A")
                        .style("color", "white");
*/
//  estrategia.on("mousedown", function(d){ d.fixed = false;})

//TESTAR O INNERHTML ETC PARA MODIFICAR O TEXTO
d3.select("#mapInt").on("click", function(){

});

d3.select("#mapCat").on("click", function(){

})
d3.select("#Selecao").on("change", function(){
    modo = d3.select("#Selecao").property("value");
    textBox = d3.select("#textBox");
    box = d3.select("#boxEstrategia");
    switch(modo)
    {
      case '0':circle.style("opacity", 1);
               path.style("opacity", 1);
               text.style("opacity", 1);
               seta.style("opacity", 1);
               box.style("visibility", "hidden");
               hideButtons();
               break;
      case '1':circle.style("opacity", function(d){if(d.colab >= mediaColab || d.isolado == '1') return 1; else return highlight_trans;});
               text.style("opacity", function(d){if(d.colab   >= mediaColab|| d.isolado == '1') return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans);
               textBox.text(estrategiaColab[Math.floor(Math.random()*6)]);
               textBoxHeight = Number(textBox.style("height").replace("px",""));
               box.style("height", textBoxHeight+20).style("visibility", "visible");
               hideButtons();
               break;
      case '2':circle.style("opacity", function(d){if(d.distR== '1'|| d.isolado == '1') return 1; else return highlight_trans;});
               text.style("opacity", function(d){if(d.distR== '1' || d.isolado == '1') return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans);
               textBox.text(estrategiaDSRT[Math.floor(Math.random()*6)]);
               textBoxHeight = Number(textBox.style("height").replace("px",""));
               box.style("height", textBoxHeight+20).style("visibility", "visible");
               hideButtons();
               break;
      case '3':circle.style("opacity", function(d){if(d.distP== '1' || d.isolado == '1') return 1; else return highlight_trans;});
               text.style("opacity", function(d){if(d.distP== '1' || d.isolado == '1') return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans);
               textBox.text(estrategiaDSPT[Math.floor(Math.random()*6)]);
               textBoxHeight = Number(textBox.style("height").replace("px",""));
               box.style("height", textBoxHeight+20).style("visibility", "visible");
               hideButtons();
               break;
      case '4':circle.style("opacity", function(d){if(d.popularidade >= desvioPopular + mediaPop) return 1; else return highlight_trans;});
               text.style("opacity", function(d){if (d.popularidade >= desvioPopular + mediaPop) return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans);
               textBox.text(estrategiaPop[Math.floor(Math.random()*6)]);
               textBoxHeight = Number(textBox.style("height").replace("px",""));
               box.style("height", textBoxHeight+20).style("visibility", "visible");
               hideButtons();
               break;
      case '5':circle.style("opacity", highlight_trans);
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans);
               text.style("opacity", highlight_trans);
               d3.select("#buttons").style("visibility", "visible");

               textBox.text(estrategiaAgrup[Math.floor(Math.random()*6)]);
               textBoxHeight = Number(textBox.style("height").replace("px",""));
               box.style("height", textBoxHeight+20).style("visibility", "visible");
    }
  });



circle.on("mouseover", function(d)
                       {
                          set_highlight(d);
                          var transform;
                          var posX = 0;
                          var posY = 0;
                          var escala = 1;

                              if((transform = document.getElementsByTagName("svg")[0].getElementsByTagName("g")[0].getAttribute("transform")))
                              {
                                  transform = transform.replace("translate(", "");
                                  transform = transform.replace(")", "");
                                  //console.log(transform);
                                  posX = parseFloat(pegaX(transform));    //posX e posY é do container, e não dos nodos
                                  transform = transform.replace(posX+",", "");
                                  posY = parseFloat(pegaY(transform));
                                  transform = transform.replace(posY+"scale(", "");
                                  escala = parseFloat(getScale(transform));
                            //console.log(escala);

                             }
                              else transform = 0;

                             div.transition()
                                      .duration(1)
                                     .style("top", (posY+ (d.y-10)*escala-100)+"px")
                                      .style("left", (posX+(d.x-10)*escala-150)+"px") //vai plotar a tooltip nesse lugar
                                      .style("opacity", 9);
                              document.getElementsByClassName("tooltip")[0].getElementsByTagName("p")[0].innerHTML = d.nome;
                            switch(modo)
                            {
                              case '0': {
                                biblioteca.text("Biblioteca: "+d.biblioteca);
                                forum.text("Forum: "+d.forum);
                                A2.text("A2: "+d.a2);
                                contatos.text("Contatos: "+d.contato);
                                BP.text("Bate-Papo: "+d.bp);
                                WF.text("Webfolio: "+d.wf);
                                break;
                              }
                              case '1':{
                                if(pesoBib> 0) biblioteca.text("Biblioteca("+getRelevancia(pesoBib)+"): "+ d.colabBib);
                                else biblioteca.text("Biblioteca: N/A");
                                if(pesoFo > 0) forum.text("Forum("+getRelevancia(pesoFo)+"): "+ d.colabF);
                                else forum.text("Forum: N/A");
                                if(pesoBP > 0) BP.text("Bate-Papo("+getRelevancia(pesoBP)+"): " + d.colabBP);
                                else BP.text("Bate-Papo: N/A");
                                if(pesoWF > 0) WF.text("Webfolio("+getRelevancia(pesoWF)+"): " + d.colabWF);
                                else WF.text("Webfolio: N/A");
                                A2.text("");
                                contatos.text("");
                                break;
                              }
                              case '2':{
                                if(pesoBib> 0) biblioteca.text("Biblioteca");
                                else biblioteca.text("Biblioteca: N/A");
                                if(pesoFo > 0) forum.text("Forum");
                                else forum.text("Forum: N/A");
                                if(pesoBP > 0) BP.text("Bate-Papo");
                                else BP.text("Bate-Papo: N/A");
                                if(pesoWF > 0) WF.text("Webfolio");
                                else WF.text("Webfolio: N/A");
                                if(pesoA2 > 0) A2.text("A2");
                                else A2.text("A2: N/A");
                                if(pesoCo > 0) contatos.text("Contatos");
                                else contatos.text("Contatos: N/A");
                                break;
                              }
                              case '3':{
                                if(pesoBib> 0) biblioteca.text("Biblioteca");
                                else biblioteca.text("Biblioteca: N/A");
                                if(pesoFo > 0) forum.text("Forum");
                                else forum.text("Forum: N/A");
                                if(pesoBP > 0) BP.text("Bate-Papo");
                                else BP.text("Bate-Papo: N/A");
                                if(pesoWF > 0) WF.text("Webfolio");
                                else WF.text("Webfolio: N/A");
                                if(pesoA2 > 0) A2.text("A2");
                                else A2.text("A2: N/A");
                                if(pesoCo > 0) contatos.text("Contatos");
                                else contatos.text("Contatos: N/A");
                                break;
                              }
                              case '4': {
                                biblioteca.text("Popularidade: "+ d.popularidade);
                                forum.text("Media: " + mediaPop);
                                A2.text("Desvio: "+ desvioPopular);
                              /*  contatos.text("Contatos: "+d.contato);
                                BP.text("Bate-Papo: "+d.bp);
                                WF.text("Webfolio: "+d.wf);*/
                                break;
                              }
                              case '5': {
                                biblioteca.text("Grupo:"+ d.grupo);
                                forum.text("");
                                A2.text("");
                                BP.text("");
                                WF.text("");
                                contatos.text("");
                              }
                            }


                          })
      .on("mousedown", function(d)
                                {
                                  d3.event.stopPropagation();
                                  if (highlight_node === null) set_highlight(d)
                                  focus_node = d;
                                  if(modo != '5')
                                    set_focus(d)
                                }
         )

      .on("mouseout", function(d) {
      exit_highlight();
      div.transition()
      .duration(150)
      .style("opacity", 0);
} );

    circle.on("mouseup",
    function(d) {

    if (focus_node!==null)
    {
      focus_node = null;
      if (highlight_trans<1)
      {
        switch(modo)
          {
          case '0':circle.style("opacity", 1);
               path.style("opacity", 1);
               text.style("opacity", 1);
               seta.style("opacity", 1); break;
          case '1':circle.style("opacity", function(d){if(d.colab >= mediaColab || d.isolado == '1') return 1; else return highlight_trans;});
               text.style("opacity", function(d){if(d.colab   >= mediaColab || d.isolado == '1') return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans); break;
         case '2':circle.style("opacity", function(d){if(d.distR== '1'|| d.isolado == '1') return 1; else return highlight_trans;});
               text.style("opacity", function(d){if(d.distR== '1'|| d.isolado == '1') return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans); break;
         case '3':circle.style("opacity", function(d){if(d.distP== '1'|| d.isolado == '1') return 1; else return highlight_trans;});
               text.style("opacity", function(d){if(d.distP== '1'|| d.isolado == '1') return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans); break;
         case '4':circle.style("opacity", function(d){if(d.popularidade >= desvioPopular + mediaPop) return 1; else return highlight_trans;});
               text.style("opacity", function(d){if (d.popularidade >= desvioPopular + mediaPop) return 1; else return highlight_trans;});
               seta.style("opacity", highlight_trans);
               path.style("opacity", highlight_trans); break;
          case '5': break;
          }
      }
    }

  if (highlight_node === null) exit_highlight();
    d.fixed = true;
    });


// Use elliptical arc path segments to doubly-encode directionality.
function tick() {

        path.attr("points", fazLinks);
        seta.attr("points", fazSeta);
        circle.attr("transform", transform);
        text.attr("transform", transform);
      }

function fazLinks(d)
{
  var deltaX = d.target.x - d.source.x;
  var deltaY = d.target.y - d.source.y;
  var distance = Math.sqrt(deltaY*deltaY+deltaX*deltaX);
  var raio = Number(circle.attr("r")) + 1.5;
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
    var raio = Number(circle.attr("r")) + 1;
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
  //console.log(groupStringtoArray(d.grupo).length);
  return "translate(" + d.x + "," + d.y + ")";
}

 function zoomHandler() {
        svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
      }

  function set_highlight(d)
  {
      svg.style("cursor","pointer");
      if (focus_node!==null) d = focus_node;
      highlight_node = d;

      if (highlight_color!="white")
      {
        circle.style(towhite, function(o) {
        return isConnected(d, o) ? highlight_color : "white";});
        text.style("font-weight", function(o) {
        return isConnected(d, o) ? "bold" : "normal";});
        path.style("stroke", function(o) {
        return o.source.index == d.index || o.target.index == d.index ? highlight_color : ((isNumber(o.score) && o.score>=0)?color(o.score):default_link_color);
      });
      }
  }

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

    function exit_highlight()
    {
       highlight_node = null;
       if (focus_node===null)
        {
          svg.style("cursor","move");
          if (highlight_color!="white")
          {
            circle.style(towhite, "white");
            text.style("font-weight", "normal");
            path.style("stroke", function(o) {return (isNumber(o.score) && o.score>=0)?color(o.score):default_link_color});
          }
        }
    }

function set_focus(d)
{
 // console.log(d);
  if (highlight_trans<1)
  {
    circle.style("opacity", function(o) { //console.log(o);
            if(isConnected(d,o)) return  1;
            else return highlight_trans;
       });

    text.style("opacity", function(o) {
                return isConnected(d, o) ? 1 : highlight_trans;
            });

    path.style("opacity", function(o) {
                return o.source.index == d.index || o.target.index == d.index ? 1 : highlight_trans;
            });

    seta.style("opacity", function(o) {
                return o.source.index == d.index || o.target.index == d.index ? 1 : highlight_trans;
            });
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
      grupo = grupo.split("Grupo ")[1];

      circle.style("opacity", highlight_trans);
      path.style("opacity", highlight_trans);
      seta.style("opacity", highlight_trans);
      text.style("opacity", highlight_trans);
      for(var j = 0; j < grupos[grupo].length; j++){
        circle.each(function(d, i){
          if(grupos[grupo][j] == d.id){
            d3.select(this).style("opacity", 1);
            return;
          }
        });
        text.each(function(d, i){
          if(grupos[grupo][j] == d.id){
            d3.select(this).style("opacity", 1);
            return;
          }
        })
      }

      path.each(function(d, i){
        if(searchInGroup(d.source.id, grupos[grupo]) && searchInGroup(d.target.id, grupos[grupo])){
          d3.select(this).style("opacity", 1);
        }
      })


      seta.each(function(d, i){
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
