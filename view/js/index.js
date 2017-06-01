/**
 * Created by bagy on 31.05.17..
 */

$(document).on("change","#selectCategory",function (event) {
    if($(this).val() !== "-1"){
        getXml(
            {"scid":$(this).val()},
            onServiceSuccess,
            "home/services",
            "POST"
        );
    }
});

function onServiceSuccess(data) {
    var services = $(data).find("services");
}