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
                case "password":
                case "email":
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
/**
 * Retrive XML
 * @param data
 * @param handler
 * @param url
 * @param method
 */
function getXml(data,handler,url,method){
    var m = method ==="undefind"?"POST":method.toUpperCase();
    var path = window.location.protocol+"//barka.foi.hr/WebDiP/2016_projekti/WebDiP2016x005/?req="+url;
    $.ajax({
        url: path,
        type: m,
        data:data,
        dataType:"xml",
        success:function(res){
            handler($(res).children());
        }
    });
}
function isDataCorrect(){
    return !$('.error').length ;
}
/**
 * Retrive JSON.
 * @param url
 * @param method
 * @param handler
 */
function getJson(url,method,handler) {
    var m = method ==="undefind"?"POST":method.toUpperCase();
    var path = window.location.protocol+"//barka.foi.hr/WebDiP/2016_projekti/WebDiP2016x005/index.php?req="+url;
    $.ajax({
        url: path,
        type: m,
        data:data,
        dataType:"json",
        success:function(res){
            handler(res);
        }
    });
}

function getActiveTheme(onThemeFetched){
    var url = window.location.protocol+"://barka.foi.hr/WebDiP/2016_projekti/WebDiP2016x005/?req=theme/service";
    $.getJSON(url,onThemeFetched);
}

function addError(element,errorId,errorMsg) {
    if($(element).parent().children("#errno-"+errorId).length === 0){
        $(
            '<div class="error" id="errno-'+errorId+'">' + errorMsg + '</div>'
        ).insertAfter(element);
    }
    $(element).parent().addClass("has-error");
}
function removeError(element,errorId) {
    $(element).parent().children("#errno-"+errorId).remove();
    if($(element).parent().children(".error").length === 0){
        $(element).parent().removeClass("has-error");
    }
}

function isFirstBigLetter(value) {
    return value[0] === value[0].toUpperCase();
}

function defaultArg(arg,val) {
    return typeof arg !== 'undefined' ? arg : val;
}

function setCookie(name,value,exp) {
    var ck = name+"="+value;
    var expires = " expires="+exp;
    document.cookie = ck+";"+expires+";path=/";
}

function getCookie(name){
    var cookies =document.cookie.split(";")
    for(var i =0;i<cookies.length;i++){
        var split = cookies[i].split("=");
        var cookieName = split[0].replace(/\s+/,"");
        if(cookieName == name){
            return split[1];
        }
    }
    return -1;
}
function isCookieExpired(name) {
    return document.cookie.indexOf(name) === -1;
}

$(document).ready(function () {
    var cookie = getCookie("master_cookie");
    if(cookie === -1){
        showTermsOfAgreementCookie();
        /**/
    }
});

function showTermsOfAgreementCookie(){
    var color = $("header").css("background-color");
    $('<div class="box" id="terms-of-cookie">' +
        '<p>Koriste se kolačići</p>' +
            '<input type="button" id="btn-cookie-accept" value="Oke" onclick="onCookieButtonClick();">'+
        '</div>').insertBefore("footer");
}
function onCookieButtonClick() {
    var date = new Date();
    var expire = 3*24*60*60*1000;
    date.setTime(date.getTime()+expire);
    setCookie("master_cookie","accepted",date.toUTCString());
    $("#terms-of-cookie").css("display","none");
}



