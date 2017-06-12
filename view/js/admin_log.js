/**
 * Created by bagy on 10.06.17..
 */


$(document).ready(function () {
    getLogs(1,1);
});



function getLogs(page,sortColumn,search) {
    var path = "admin/service/log/"+page;
    search = defaultArg(search,0);
    if (search !== 0 && $("#selectColumn").val() !== "-1"){
        var data = {sort:sortColumn,q:search,col:$("#selectColumn").children(":selected").attr("id")};
    }else{
        var data = {sort:sortColumn};
    }
    showLoading();
    getXml(data,onLogsGetListener,path,"POST");
}
function onLogsGetListener(response) {
    hideLoading();
    var log = $(response).find("log");

    fillTable("tableLog",log);
    var contextIndex = $("th.medium-cell").index();
    $("#tableLog>tbody>tr").each(function (index,row) {
        var item = $(row).children().eq(contextIndex);
        $(row).children().eq(contextIndex).addClass("medium-cell");
    });
}


$(document).on("dblclick","th",function (event) {
    var index = $(this).index();
    var p = $("table")[0].dataset.page;
    $(".sort").removeClass("sort");
    $(this).addClass("sort");
    getLogs(p,index);
});
$(document).on("click","input[name='navbutton']",function () {
    //alert($(this).val());
    var sorted = $(".sort").length >0?$(".sort").index():1;
    var p = $("table")[0].dataset.page;
    switch($(this).val()){
        case "F":
            p = 1;
            break;
        case "P":
            p = p > "1"?parseInt(p)-1:1;
            break;
        case "N":
            p = parseInt(p)+1;
            break;
        case "L":
            p=-1;
    }
    var table = $("table").first();
    table[0].dataset.page = p;
    getLogs(p,sorted,"admin/service/log",onLogsGetListener,"POST");
});

$(document).on("keyup","#inputSrch",function () {
   var value = $(this).val();
   if(value.length > 4){
       var page = $(".table")[0].dataset.page;
       var sort = $(".sort").index();
       if(sort === -1){
           sort = 1;
       }
       getLogs(page,sort,value);
   }

});
$(document).on("change","#selectColumn",function () {
    var selexted = $(this).children(":selected").attr("id");
   /*if($(this).children(":selected").attr() !== "-1"){
       $(this).next().prop("disabled",false);
   }*/
});

