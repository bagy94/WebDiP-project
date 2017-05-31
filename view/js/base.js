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

function getXml(data,handlerSucces,handlerError,url,method){
    var m = method ==="undefind"?"GET":method.toUpperCase()==="GET"?"GET":"POST";
    $.ajax({
        url: url,
        type: m,
        data:data,
        dataType:"xml",
        success:function(res){
            var success = $(res).children().attr('success');
            if(success ==="1"){
                var d = $(res).children();
                handlerSucces(d);
            }else{
                handlerError(res);
            }

        }
    });
}
function isDataCorrect(){
    return !$('.error').length ;
}