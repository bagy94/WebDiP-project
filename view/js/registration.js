/**
 * Created by bagy on 02.06.17..
 */


$(document).on("focusout","input,select",function(event){
    check(this);
});


$(document).on("submit","#form-registration",function (event) {
    $("input:not([type='submit']),select").each(function (i,item) {
        check(this);
    });

    if($(".form-inline-element-wrapper.has-error").length !== 0){
        event.preventDefault();
    }
});





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
    var value = $("#inputUserName").val();
    removeError($("#inputUserName"),"exist");
    if(value.length < 4){
        addError($("#inputUserName"),"length","Korisničko ime mora sadržavati barem 5 znakova");
        return false;
    }else{
        removeError($("#inputUserName"),"length");
    }
    if(!hasSpecialChars(value)){
        addError($("#inputUserName"),"spec-chars","Korisničko ime mora sadržavati barem 1 specijalan znak");
        return;
    }else{
        removeError($("#inputUserName"),"spec-chars");
    }
    if(!hasBigLetter(value)){
        addError($("#inputUserName"),"big-letter","Korisničko ime mora sadržavati barem 1 veliko slovo");
        return;
    }else{
        removeError($("#inputUserName"),"big-letter");
    }
    if(!hasNumber(value)){
        addError($("#inputUserName"),"have-number","Korisničko ime mora sadržavati barem 1 broj");
        return;
    }else{
        removeError($("#inputUserName"),"have-number");
    }
    getXml({"username":value},onUserNameCheck,"registration/service/username","POST");
}

function check(element){
    if(isEmpty(element)){
        addError(element,"empty","Vrijednost mora biti unesena");
        return;
    }else{
        removeError(element,"empty");
    }
    var elementId = $(element).attr("id");
    console.log("Trenutni element: "+elementId);
    switch (elementId){
        case "inputUserName":
            checkUserName();
            break;
        case "inputSurname":
        case "inputName":
            if(!isFirstBigLetter($(element).val())){
                addError(element,"first-big-letter","Prvo slovo mora biti veliko");
            }else{
                removeError(element,"first-big-letter");
            }
            break;
        case "inputEmail":
            checkEmail();
            break;
        case "inputPassword":
            var value = $(element).val();
            if(!hasSpecialChars(value,2)){
                addError(element,"spec-chars","Password mora sadržavati barem dva specijalna znaka");
            }else{
                removeError(element,"spec-chars");
            }
            if(!hasNumber(value,2)){
                addError(element,"numbers","Password mora sadržavati barem dva broja");
            }else{
                removeError(element,"numbers");
            }
            if(!hasBigLetter(value)){
                addError(element,"uppercase","Password mora sadržavati barem jedno veliko slovo");
            }else{
                removeError(element,"uppercase");

            }
            break;
        case "inputPasswordCheck":
            if($("#inputPasswordCheck").val() !== $("#inputPassword").val()){
                addError(element,"equal","Lozinke se ne podudaraju");
            }else{
                removeError(element,"equal");
            }
            break;
        case "inputBirthday":
            if(!/^\d{2}\.\d{2}\.\d{4}\.?$/.test($(element).val())){
                addError(element,"format","Datum mora biti u formatu dd.mm.gggg.");
            }else{
                removeError(element,"format");
            }
            break;
    }
}

function onEmailCheck(response) {
    var check = $(response).first();
    if($(response).first().attr("success")==="1"){
        removeError($("#inputEmail"),"format");
        if($(response).first().attr("exist") === "1"){
            addError($("#inputEmail"),"exist",$(response).first().attr("message"));
        }else{
            removeError($("#inputEmail"),"exist");
        }
    }else{
        addError($("#inputEmail"),"format",$(response).first().attr("message"));
    }
}

function checkEmail() {
    var element = $("#inputEmail");
    if(!/^([\w\d\_\-\.\?]+)@{1}([\w\d]+\.){1,5}\w+$/.test($(element).val())){
        removeError($("#inputEmail"),"exist");
        addError(element,"format","Email mora bit u formatu nest@nest.ne..");
        return;
    }else{
        removeError(element,"format");
    }
    getXml({"email":$(element).val()},onEmailCheck,"registration/service/email","POST");

}

function hasSpecialChars(value,numberOfSpecialChars) {
    var num = defaultArg(numberOfSpecialChars,1);
    var regex = new RegExp('(.*[\.\(\)\{\}\'\!\#\“]+.*){'+num+',}');
    return  regex.test(value);
}

function hasBigLetter(value,number){
    number = defaultArg(number,1);
    var regex = new RegExp("(.*[A-Z]+.*){"+number+",}");
    return regex.test(value);
}
function hasNumber(value,number){
    number = defaultArg(number,1);
    var regex = new RegExp("(.*[0-9]+.*){"+number+",}");
    return regex.test(value);
}
function redirectToLogin(redirectTo){
    window.setTimeout(function(){
        window.location.href = "https://barka.foi.hr/WebDiP/2016_projekti/WebDiP2016x005/?req=login";
    }, 3000);


}

