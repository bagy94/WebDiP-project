/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var NavigationBox = function(pages,parent,handler){
    
    var btnNext='<input type="image" id="btnNext" src="'+window.location.protocol+'//barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x003/asset/nav_next.png" alt="Sljedeća" width="44" height="44" title="Sljedeća">';
    var btnPrev='<input type="image" id="btnPrev" src="'+window.location.protocol+'//barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x003/asset/nav_prev.png" alt="Prethodna" width="44" height="44" title="Prethodna">';;
    var btnFirst='<input type="image" id="btnFirst" src="'+window.location.protocol+'//barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x003/asset/nav_first.png" alt="Prva" width="44" height="44" title="Prva">';
    var btnLast='<input type="image" id="btnLast" src="'+window.location.protocol+'//barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x003/asset/nav_last.png" alt="Zadnja" width="44" height="44" title="Zadnja">';
    var status ='<p style="display:inline;width:44px;height:44px"> od </p>';
    var last = pages;
    if($(parent).children('#navigationTableBox').length){
        $(parent).children('#navigationTableBox').remove();
    }
    $(parent).append('<div id="navigationTableBox">'+btnFirst+btnPrev+status+btnNext+btnLast+'</div>');
    this.addEventListener=function(){
        $('#btnNext').click(onNextClick);
        $('#btnPrev').click(onPrevClick);
        $('#btnFirst').click(onFirstClick);
        $('#btnLast').click(onLastClick);
        update();
        //alert(currentPage);
    };
    var currentPage = 1;
    
    var onNextClick = function(){
        //alert(currentPage);
        //alert("NextClicked");
        if(currentPage === last){
            $('#btnNext').hide();
            $('#btnLast').hide();
        }
        else{
            $('#btnNext').show();
            $('#btnLast').show();
            currentPage++;
        }
        $('#btnPrev').show();
        $('#btnFirst').show();
        handler(currentPage);
        update();
    };
    var onPrevClick = function(){
        if(currentPage === 1){
            $('#btnPrev').hide();
            $('#btnFirst').hide();
        }
        else{
            $('#btnPrev').show();
            $('#btnFirst').show();
            currentPage--;
        }
        $('#btnNext').show();
        $('#btnLast').show();
        handler(currentPage);
        update();
    };
    var onFirstClick = function(){
        $('#btnPrev').hide();
        $('#btnFirst').hide();
        currentPage=1;
        $('#btnNext').show();
        $('#btnLast').show();
        handler(1);
        update();
    };
    var onLastClick = function(){
        $('#btnNext').hide();
        $('#btnLast').hide();
        currentPage=last;
        $('#btnPrev').show();
        $('#btnFirst').show();
        handler("last");
        update();
    };
    var update=function(){
        $('#navigationTableBox').children('p').html(currentPage+" od "+pages);
    }
};
