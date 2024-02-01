$( "#btn_cancel" ).on( "click", function() { 
    var id = $("[name='id']").val();
    var data = {id: id, cost: $("[name='cost']").val()};
    $.ajax({
        url: '/taxi/passenger/delete_order.php',
        type: 'POST', // Или 'POST', в зависимости от метода запроса
        data: data,
        success: function(result) {
            if (result == 'Машин не нашлось, ваш заказ отменен') {
                $(".card").hide();
                $("#alert").removeAttr('hidden');
                $("#alert").html(result);
            } else if (result == 'Заказ нельзя отменить когда водитель начал поездку') {
                $("#alert").removeAttr('hidden');
                $("#alert").html(result);
            }
            
            else {
                $(".card").hide();
                $("#alert").removeAttr('hidden');
                $("span").hide();
            }

        }
    });
});