function zoomHandler() {
    svgs = d3.selectAll("svg");
    svgInteracao = svgs[0][0];
    
    var posX = 0;
    var posY = 0;

    if(d3.select(svgInteracao).select("g").attr("transform") == null)
     scale = 1;
    else{
      translate = d3.select(svgInteracao).select("g").attr("transform").replace("translate(", "");
      translate = translate.replace(")", "");
      posX = parseFloat(pegaX(translate));    //posX e posY é do container, e não dos nodos
      translate = translate.replace(posX+",", "");
      posY = parseFloat(pegaY(translate));
      translate = translate.replace(posY+"scale(", "");
      scale = parseFloat(getScale(translate));
    }
    
    mapInt.attr("transform", "translate(" + d3.event.translate + ")scale(" + scale + ")");

}

function zoomed(){
  mapInt.attr("transform",
        "translate(" + zoom.translate() + ")" +
        "scale(" + zoom.scale() + ")"
    );
}

function interpolateZoom (translate, scale) {
    var self = this;
    return d3.transition().duration(350).tween("zoom", function () {
        var iTranslate = d3.interpolate(zoom.translate(), translate),
            iScale = d3.interpolate(zoom.scale(), scale);
        return function (t) {
            zoom
                .scale(iScale(t))
                .translate(iTranslate(t));
            zoomed();
        };
    });
}

function zoomClick(zm){
    svgs = d3.selectAll("svg");
    svgInteracao = svgs[0][0];
    
    var posX = 0;
    var posY = 0;

    if(d3.select(svgInteracao).select("g").attr("transform") == null)
     scale = 1;
    else{
      translate = d3.select(svgInteracao).select("g").attr("transform").replace("translate(", "");
      translate = translate.replace(")", "");
      posX = parseFloat(pegaX(translate));    //posX e posY é do container, e não dos nodos
      translate = translate.replace(posX+",", "");
      posY = parseFloat(pegaY(translate));
      translate = translate.replace(posY+"scale(", "");
      scale = parseFloat(getScale(translate));
    }
    if(zm != 0){
     d3.select(svgInteracao).select("g").attr("transform", "translate("+(posX -810*scale*Math.log(zm))+","+(posY -300*scale*Math.log(zm))+")scale("+scale*zm+")")        
    } 
    else{ 
      d3.select(svgInteracao).select("g").attr("transform", "translate("+0+","+0+")scale(1)")
    }

}