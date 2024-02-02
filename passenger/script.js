$(document).ready(function(){
    window.flag = false;
});


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
}

ymaps.ready(init);

function route(data) {
    var address1 = data["address1"];
    var address2 = data["address2"];
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

    // удаляем существующий маршрут, если он есть
    if (typeof multiRoute != "undefined") map.geoObjects.remove(multiRoute);

    // создаем объект который хранит 2 адреса
    window.addresses = {};
    $('#div_form').find('input').each(function() {
    addresses[this.name] = $(this).val();
    });
    if (addresses["address1"] == "" || addresses["address2"] == "") {
        $('#free_cars').empty();
        $('i').html('<i>Введите оба адреса</i>');
    }


    else {
        $('i').empty();
        $("[name='address1'], [name='address2']").prop('disabled', true);
        // если данные корректны строим маршрут и выводим кол-во свободных машин
        $.ajax({
            url: 'free_drivers.php',
            type: 'GET', // Или 'POST', в зависимости от метода запроса
            dataType: 'json', // Ожидаемый формат данных
            success: function(result) {
                if (result.length != 0) {
                    route(addresses);
                    $('i').html('<i>Если маршрут построен неверно, проверьте корретность адресов</i>');

                    $( "#btn_ok" ).on( "click", function() {
                        $('.btn-group').hide();
                        $('#btn').show();
                        $("[name='address1'], [name='address2']").prop('disabled', false);
                        // var paramsString = document.location.search;
                        // var searchParams = new URLSearchParams(paramsString);


                        var session_id = $("[name='session_id']").val();
                        $.ajax({
                            url: 'isset_order.php',
                            type: 'POST',
                            dataType: 'json',
                            data: {session_id: session_id},
                            success: function(result2){
                                if (!result2['new_order']) {
                                    $('i').html('<i>Заказ принят в обработку. Вы можете посмотреть его статус в <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="active_orders.php">активных поездках</a></i>');
                                    $('#free_cars').empty();
                                    map.geoObjects.remove(multiRoute);
                                    order(result, session_id);
                                } else {
                                    $('i').html('<i>У вас уже есть активный заказ</i>');
                                    $('#free_cars').empty();
                                    map.geoObjects.remove(multiRoute);
                                }
                            }
                        });




                        
                        
                    
                    });
                    
                    $( "#btn_cancel" ).on( "click", function() { 
                        $('.btn-group').hide();
                        $('#btn').show();
                        $("[name='address1'], [name='address2']").prop('disabled', false);
                        $('i').empty();
                        $('#free_cars').empty();
                        map.geoObjects.remove(multiRoute);
                    });
                    
                } else {
                    $( "#btn_ok" ).on( "click", function() {
                        $('.btn-group').hide();
                        $('#btn').show();
                        $("[name='address1'], [name='address2']").prop('disabled', false);
                        $('i').empty();
                        $('#free_cars').empty();
                        $('i').html('<i>На данный момент нет свободных машин</i>');        
                    });
                    
                    $( "#btn_cancel" ).on( "click", function() { 
                        $('.btn-group').hide();
                        $('#btn').show();
                        $("[name='address1'], [name='address2']").prop('disabled', false);
                        $('i').empty();
                        $('#free_cars').empty();
                    });
                }
                
                $('#free_cars').html('Свободных машин: ' + result.length);
                
            }
        });

        if (!flag) {
            $('#btn').hide();
            $('.btn-group').removeAttr('hidden');    
            flag = true;
        } else {
            $('#btn').hide();
            $('.btn-group').show();
        }
        
    
    }
    
});


function order(data, session_id) {
    if (data.length < 2) {
        var cost; // стоимость поездки
        var dist; // расстояние
        // получаем расстояние от точки А до точки Б
        ymaps.route(["Санкт-Петербург, " + addresses['address1'], "Санкт-Петербург, " + addresses['address2']]).then(function (route) {
            dist = route.getLength();
        }).then(function() {
            if (parseInt(dist) / 1000 <= 1) cost = 100;
            else cost = 50 * (parseInt(dist) / 1000);    

            // подготавливаем объект вида (адрес1, адрес2, расстояние, стоимость) для добавление его в бд 
            data = data[0];
            data['new_order'] = addresses['address1'] + "-" +  addresses['address2'] + 
            "-" + (parseInt(dist) / 1000).toFixed(2) + "-" + parseInt(cost);
            
            data['id_user'] = session_id;
            data['status_order'] = 'Идёт поиск машин'; 

            $.ajax({
                url: 'add_order_for_driver.php',
                type: 'POST', 
                data: data
            });
        });



    } else {
        
        new_order = addresses['address1'] + "-" + addresses['address2'];
        var minId = data[0]['session_id'];
        var minAddress = data[0]['address'];
        ymaps.route([minAddress, "Санкт-Петербург, " + addresses['address1']]).then(function (route) {
            var time1 = route.getJamsTime();
            distanse_routes(time1);
        });

        // передаем посчитанное время пути от первого таксиста до адреса отправления (пассажира) 
        function distanse_routes(time1) {
            $.each(data, function(key, value) {
                if (key != 0) {
                    ymaps.route([value['address'], "Санкт-Петербург, " + addresses['address1']]).then(function (route) {
                        var time2 = route.getJamsTime();
                        if (parseInt(time2) < parseInt(time1)) {
                            time1 = time2;
                            minId = value['session_id'];
                            minAddress = value['address'];
                        }
                        // т.к функция yamp.route() асинхронная, ждем окночания ее выполнения и идем дальше
                    }).then(function() {
                        if (key == data.length - 1) {
                            // считаем стоимость поездки
                            var cost;
                            var dist;
                            // получаем расстояние от точки А до точки Б
                            ymaps.route(["Санкт-Петербург, " + addresses['address1'], "Санкт-Петербург, " + addresses['address2']]).then(function (route) {
                                dist = route.getLength();
                            }).then(function() {

                                if (parseInt(dist) / 1000 <= 1) cost = 100;
                                else cost = 50 * (parseInt(dist) / 1000);    

                                // подготавливаем объект вида (адрес1, адрес2, расстояние, стоимость) для добавление его в бд 
                                var data_new = {id: minId, new_order: addresses['address1'] + "-" + 
                                addresses['address2'] + "-" + (parseInt(dist) / 1000).toFixed(2) + "-" + parseInt(cost)};
                                data_new['id_user'] = id;
                                data_new['status_order'] = 'Идёт поиск машин'; 
                                $.ajax({
                                    url: '/taxi/passenger/add_order_for_driver.php',
                                    type: 'POST', // Или 'POST', в зависимости от метода запроса
                                    data: data_new
                                });
                            });
                        }
                    });
                }
            });
            
        }


    }
        


}





