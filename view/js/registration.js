/**
 * Created by bagy on 02.06.17..
 */


$(document).on("focusout","input,select",function(event){
    check($(this).attr("id"));
});


$(document).on("")





function onUserNameCheck(response) {
    var check = $(response).first();
    if($(response).first().attr("success")==="1"){
        removeError($("#inputUserName"),"format");
        if($(response).first().attr("exist") === "1"){
            addError($("#inputUserName"),"exist",$(response).first().attr("message"));
        }else{
            removeError($("#inputUserName"),"exist");
        }
    }else{
        addError($("#inputUserName"),"format",$(response).first().attr("message"));
    }
}

function checkUserName() {
    if($(this).val().length >3){
        removeError(this,"length");
        var un = $(this).val();
        getXml({"user-name":un},onUserNameCheck,"registration/service_check/user-name","POST");
    }else{
        addError(this,"length","Korisničko ime mora sadržavati barem 4 znaka");
    }
}
function checkEmail() {
    
}

function check(elementId){
    console.log("Trenutni element: "+elementId);
    switch (elementId){
        case "inputUserName":
            checkUserName();
            break;

    }
}