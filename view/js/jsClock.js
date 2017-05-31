var Clock = function(conta){
    var timeInterval;
    var cont = conta !== "undefined"?conta:null;
    var start=function(){
        var time = new Date();
        var t = time.getHours()+parseInt(timeInterval)>=24?time.getHours()+parseInt(timeInterval)-24:time.getHours()+parseInt(timeInterval);
        var h = chTime(t);
        var m = chTime(time.getMinutes());
        var s = chTime(time.getSeconds());
        $(cont).html(h+":"+m+":"+s);
        setTimeout(start,600);
    };
    this.setTime = function(){
      $.ajax({
          url:window.location.protocol+"//barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x003/utils/scriptConfiguration.php",
          type: "GET",
          data: {req:"timeintv"},
          dataType:"xml",
          success: function(xml){
              timeInterval =$(xml).children("configuration").children("timeintv").text();
              create();
              start();
          }
      }); 
      
    }
    
    var update=function(intv){
        if(intv==="undefind"){
            stop();
            setTime();
        }else{
            timeInterval = intv;
        }
    }
    
    var stop=function(){
        clearTimeout();
    };
    
    var chTime = function(i){
        return i<10?"0"+i:i;
    }
    this.setCont = function(container){
        cont = container;
    }
    function create(){
        $(cont).css({
            "background-color":"#CCCCCC",
            "border":"2px solid #808080",
            "border-radius": "2px",
            "font-family":'"Arial Black", Gadget, sans-serif',
            "letter-spacing":"3px",
            "width":"120px",
            "max-width":"100%"
        });
    }
};
