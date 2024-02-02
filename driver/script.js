function init(){
    window.map = new ymaps.Map("map", {
        center: [59.92979063170493,30.289700614602534],
        zoom: 15
    });

    map.controls.remove('searchControl'); // удаляем поиск
    map.controls.remove('trafficControl'); // удаляем контроль трафика
    map.controls.remove('typeSelector'); // удаляем тип
    map.controls.remove('fullscreenControl'); // удаляем кнопку перехода в полноэкранный режим
    map.controls.remove('rulerControl'); // удаляем контрол правил 



    if ($("[name='status_order']").val() == 'Идёт поиск машин') route($("[name='address1']").val(), $("[name='address2']").val());
    else if ($("[name='status_order']").val() == 'Заказ принят') route($("[name='address']").val(), $("[name='address1']").val());
    else if ($("[name='status_order']").val() == 'Поездка начата') route($("[name='address1']").val(), $("[name='address2']").val());

}

ymaps.ready(init);


function route(address1, address2) {
    window.multiRoute = new ymaps.multiRouter.MultiRoute({
        referencePoints: [
            "Санкт-Петербург, " + address1,
            "Санкт-Петербург, " + address2
        ],
        params: {
            avoidTrafficJams: false
        }
    }, {
            // Автоматически устанавливать границы карты так,
            // чтобы маршрут был виден целиком.
            boundsAutoApply: true
        });

    // Добавление маршрута на карту.
    map.geoObjects.add(multiRoute);
}

$( "#btn" ).on( "click", function() {
    var address = {};
    $('#div_form').find ('input').each(function() {
        address[this.name] = 'Санкт-Петерберг, ' + $(this).val();
    });   

    if (address["address"] == "Санкт-Петерберг, ") {
        $('#err').html('Перед отправкой введите адрес');
    }
    else {



        var session_id = $("[name='session_id']").val();
        address['session_id'] = session_id;
        


        $.ajax({
            url: 'add_address.php',
            type: 'POST',
            data: address,
            success: function(response) {
                $('#success_string').html('<div id="success_string" style="text-align: center; margin-top: 20px;" class="alert alert-success">✔️ Адрес успешно изменен</div>')
                $('#actual_address').html('Ваш текущий адрес: ' + address['address']);
            }
        });

    }

});

$( "#btn_cancel" ).on( "click", function() {
    $.ajax({
        url: 'free_drivers.php',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            
            if (result.length == 0) {

                $.ajax({
                    url: 'delete_order.php',
                    type: 'POST',
                    data: {id_user: $('[name="id_user"]').val(), cost: $('[name="cost"]').val()},
                    success: function() {
                        $(".card").hide();
                        $(".form-label").removeAttr('hidden');
                        $("#address").removeAttr('hidden');
                        $("#btn").removeAttr('hidden');
                        $("#alert").removeAttr('hidden');
                        $("#alert").html('Заказ отменён');
                        map.geoObjects.remove(multiRoute);
                    }
                                     
                });
            } else {
                var session_id = $("[name='session_id']").val();

                $.ajax({
                    url: 'transfer_order.php',
                    type: 'POST',
                    data: {session_id: session_id, cost: $('[name="cost"]').val()},
                    success: function() {
                        $(".card").hide();
                        $(".form-label").removeAttr('hidden');
                        $("#address").removeAttr('hidden');
                        $("#btn").removeAttr('hidden');
                        $("#alert").removeAttr('hidden');
                        $("#alert").html('Заказ отменён');
                        map.geoObjects.remove(multiRoute);
                    }      
                });
            }
        }

        
    });



});

$( "#btn_ok" ).on( "click", function() {
    var session_id = $("[name='session_id']").val();
    $.ajax({
        url: 'take_order.php',
        type: 'POST',
        data: {session_id: session_id, id_user: $("[name='id_user']").val(), address2: $("[name='address2']").val()},
        success: function(result) {
            if (result == 'Заказ был отменен или принят другим водителем') {
                $("#alert").removeAttr('hidden');
                $("#alert").html(result);
                $(".card").hide();
                map.geoObjects.remove(multiRoute);
                $(".form-label").removeAttr('hidden');
                $("#address").removeAttr('hidden');
                $("#btn").removeAttr('hidden');
                $("span").html("Ваш текущий адрес: " + $("[name='address']").val() + "");


            } else if (result == 'Заказ принят') {
                $("#btn_ok").html("На месте");
                $("#btn_cancel").html("Отменить");
                $("span").html("Вы выполняете заказ");
                $("h5").html("Заказ");
                map.geoObjects.remove(multiRoute);
                route($("[name='address']").val(), $("[name='address1']").val());
            } else if (result == 'На месте') {
                $("#btn_ok").html("Начать поездку");
                map.geoObjects.remove(multiRoute);
            } else if (result == 'Поездка начата') {
                $("#btn_ok").html("Завершить поездку");
                $("#btn_cancel").hide();
                route($("[name='address1']").val(), $("[name='address2']").val());
            } else if (result == 'Поездка завершена') {
                $(".card").hide();
                map.geoObjects.remove(multiRoute);
                $("#alert").removeAttr('hidden');
                $("#alert").html('Вы завершили поездку');

                $(".form-label").removeAttr('hidden');
                $("#address").removeAttr('hidden');
                $("#btn").removeAttr('hidden');
                $("span").html("Ваш текущий адрес: " + $("[name='address2']").val() + "");
            }
        } 
    });


});