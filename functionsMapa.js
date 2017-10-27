function updateLinks(nodes, edges, links){
  edges.forEach(function(e) {
    // Get the source and target nodes

    var sourceNode = nodes.filter(function(n) { return n.id === e.source; })[0],
        targetNode = nodes.filter(function(n) { return n.id === e.target; })[0],
        valueLink = e.value;

    // Add the edge to the array
    links.push({source: sourceNode, target: targetNode, value: valueLink});
  });
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

function selectGroup(){
  var grupo = d3.select(this).text();
  grupo = grupo.split("Grupo ")[1] - 1;

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
        string = string+nameByID(grupo[i]) +";";
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


function groupStringtoArray(string){
  var arrayVazio = [];
  if(string)
      return string.split(",");
    return arrayVazio;
}



function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


function isConnected(a, b) {
    return linkedByIndex[a.index + "," + b.index] || linkedByIndex[b.index + "," + a.index] || a.index == b.index;
}

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

function sizetoNumber(size){
  return Number(size.split("px")[0]);
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
