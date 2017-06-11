/**
 * Created by bagy on 10.06.17..
 */
var clock = new Clock();




$(document).ready(function () {
    clock.setCont($("div.box.clock"));
    clock.setTime();
});

$(document).on("click","input[name='save']:not(:last)",function () {
   var name = $(this).prev().prop("name");
   var data = {};
   data[name] = $(this).prev().val();
   getXml(data,onChangeSuccess,"admin/service/"+name,"POST");
   showLoading();
});

$(document).on("click", "input[name='open']",function () {
    window.open("http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html","_blank");
});

function onChangeSuccess(response) {
    hideLoading();
    var success = $(response).attr("success");

    if(success === "1"){
        var message = "Uspiješno ažuriranje";
    }else{
        var message = $(response).attr("message");
    }
    showNotif(message);
}
$(document).on("click","#inputSaveInterval",function () {
    getXml({interval:"1"},onIntervalUpdate,"admin/service/interval","POST");
    showLoading();
});
function onIntervalUpdate(response) {
    hideLoading();
    var success = $(response).attr("success");

    if(success === "1"){
        var message = "Uspiješno ažuriranje. <br> Interval je: "+$(response).find("interval").text();
        clock.setTime();
    }else{
        var message = $(response).attr("message");
    }
    showNotif(message);
}