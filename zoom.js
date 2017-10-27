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