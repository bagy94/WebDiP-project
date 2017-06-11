/**
 * Created by bagy on 07.06.17..
 */


$(document).ready(function () {
    getPrivateXml({page:1,sort:1},onUsersRetrieve,"users","GET");
    showLoading();
});

$(document).on("keyup","#inputSearch",function () {
    var value = $(this).val();
    if(!(value === "" || value.length < 4)){
        alert(value);
    }
});




function onUsersRetrieve(response) {
    var users = $(response).find("user");

    var passwdIndex = $("th.password").index();
    $("tbody").children().remove();
    fillTable("tableUserControl",users);

    $("tr").each(function (index,row) {
        var lockedCell = $(row).children("td").last();
        if($(".sort").length > 0){
            var si = $(".sort").index();
            $(row).children().eq(si).addClass("sort");
            var foo = $(row).children();
        }
        if($(lockedCell).html() === "1"){
            changeStatus(1,row)
        }else{
            changeStatus(0,row)
        }
        var foo = $(lockedCell).prev().html();
        if(foo === "NULL"){
            $(lockedCell).prev().html(
                '<img src="../view/asset/ic_not_active.png" alt="Nije aktiviran" class="icon-status">'
            );
        }else{
            $(lockedCell).prev().html(
                '<img src="../view/asset/ic_activated.png" alt="Aktiviran" class="icon-status">'
            );
        }


        $(row).children().eq(passwdIndex).addClass("password");

        hideLoading();
    });
}
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
    getPrivateXml({page:p,sort:sorted},onUsersRetrieve,"users","GET");
});


$(document).on("dblclick","th",function (event) {
    var index = $(this).index();
    var p = $("table").data("page");
    $(".sort").removeClass("sort");
    $(this).addClass("sort");
    getPrivateXml({page:p,sort:index},onUsersRetrieve,"users","GET");
});




function onLockActionClickListener(element) {
    var parent = $(element).parents("tr");
    var value = $(element).val();
    var indexUserName = $("th[name='user_name']").index();
    var un = $(parent).children().eq(indexUserName).html();
    showLoading();
    getPrivateXml({uid:un},onUserStatusChangedListener,"users/"+value,"POST");
}



/**
 * Retrive XML
 * @param data
 * @param handler
 * @param url
 * @param method
 */
function getPrivateXml(data,handler,url,method){
    var m = method ==="undefind"?"POST":method.toUpperCase();
    var path = window.location.protocol+"//barka.foi.hr/WebDiP/2016_projekti/WebDiP2016x005/private/service/"+url;
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

function changeStatus(status,row){
    var lockedCell = $(row).children("td").last();
    if(status === 1){
        $(lockedCell).html(
            '<img src="../view/asset/ic_locked.png" alt="Zaključan" class="icon-status">'
        );
        $('<td><input type="image" src="../view/asset/ic_unlock_action.png" alt="Otključaj" class="icon-status" name="action" value="0" onclick="onLockActionClickListener(this);"></td>').insertAfter(lockedCell);

    }else{
        $(lockedCell).html(
            '<img src="../view/asset/ic_unlocked.png" alt="Otključan" class="icon-status">'
        );
        $('<td><input type="image" src="../view/asset/ic_lock_action.png" alt="Zaključaj" class="icon-status" name="action" value="1" onclick="onLockActionClickListener(this);"></td>').insertAfter(lockedCell);

    }
}
function onUserStatusChangedListener(response){
    var success = $(response).attr("success");
    showNotif($(response).attr("message"));
    $("tbody").children().remove();
    var sorted = $(".sort").length >0?$(".sort").index():1;
    var p = $("table")[0].dataset.page;
    getPrivateXml({page:p,sort:sorted},onUsersRetrieve,"users","GET");
}