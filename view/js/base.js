/**
 * Created by bagy on 30.05.17..
 */

function isEmptyText(element){
    if($(element).val().length === 0){
        return true;
    }
    return false;
}
function isEmptyArray(element){
    if($(element).length === 0){
        return true;
    }
    return false;
}
function isEmpty(element){
    var type = $(element).prop('type');
    var tag = $(element).prop('tagName').toLowerCase();
    switch(tag){
        case "input":
            switch(type){
                case "text":
                    return isEmptyText(element);
                case "file":
                    return  $(element).prop('files').length === 0;
                case "image":
                    return $(element).prop("src")==="";
                case "number":
                    return $(element).val() === ""?1:0;
            }
        case "select":
            var id = $(element).prop('id');
            var v = $(element).val();
            return parseInt(v) === parseInt('-1') || id==="-1";
        case "textarea":
            return isEmptyText(element);
    }
}
function isImage(file){
    var exp = file.name.split(".");
    var last = $(exp).last();
    switch(last[0]){
        case "jpeg":
        case "png":
        case "gif":
        case "jpg":
            return true;
    }
    return false;
}
function isPDF(file){
    return file.name.split(".").last() === "pdf";
}

function getXml(data,handler,url,method){
    var m = method ==="undefind"?"POST":method.toUpperCase();
    var path = window.location.protocol+"://barka.foi.hr/WebDiP/2016_projekti/WebDiP2016x005/?req="+url;
    $.ajax({
        url: path,
        type: m,
        data:data,
        dataType:"xml",
        success:function(res){
            handler(res);
        }
    });
}
function isDataCorrect(){
    return !$('.error').length ;
}