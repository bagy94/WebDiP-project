/**
 * Created by bagy on 02.06.17..
 */


$(document).on("focusout","#inputUserNameLogIn",function (e) {
    if($(this).val().length >3){
        getXml(
            {user_name:$(this).val()},
            onUserNameCheckDone,
            "login/check",
            "POST"
        );
    }
});

function onUserNameCheckDone(xmlResponse) {

}
