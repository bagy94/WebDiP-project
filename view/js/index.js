/**
 * Created by bagy on 31.05.17..
 */

$(document).on("change","#selectCategory",function (event) {
    if($(this).val() !== "-1"){
        showLoading();
        getXml(
            {"scid":$(this).val()},
            onServiceSuccess,
            "home/services",
            "POST"
        );
    }
});

function onServiceSuccess(data) {
    hideLoading();
    var services = $(data).find("service");
    $(".box-service-list").remove();
    if(services.length === 0){
        $(".index-content").append(
            '<div class="box-service-list service-box no-data">'+
                '<p>Nema rezerviranih usluga za '+$("#selectCategory option:selected").text()+' kategoriju</p>'+
            '</div>'
        );
    }else{
        var box = document.createElement("div");
        var color = $("header").css("background-color");
        if(color.indexOf("a") == -1){
            var viewcolor = color.replace(")",', 0.64)').replace("rgb","rgba");
        }else{
            var viewcolor = color;
        }


        $(box).addClass();
        $(box).css("box-shadow","3px 4px 9px 1px "+color);
        $('<div class="box-service-list"></div>').appendTo(".index-content");
        $(services).each(function (index, item) {
            $(".box-service-list").append(
                '<div class="service-box" style="box-shadow: 3px 4px 9px 1px '+color+';">' +
                    '<div class="service-box-splitter" name="name">' +
                        '<div name="name">' +
                            $(item).attr("ime")+
                        '</div>' +
                    '</div>'+
                '<div class="service-box-splitter" name="mid">' +
                    '<div class="service-box-inside" name="description">' +
                        $(item).attr("opis")+
                    '</div>'+
                '</div>'+
                    '<div class="service-box-splitter" name="bot" style="">' +
                        '<div class="service-box-inside" name="duration">' +
                            "Trajanje usluge "+$(item).attr("trajanje")+" minuta"+
                        '</div>'+
                        '<div class="service-box-inside" name="price" style="float: right">' +
                            "Cijena usluge: "+$(item).attr("cijena")+
                        '</div>'+
                    '</div>' +
                '</div>'
            );
        });
    }
}

//background-color: '+viewcolor+'