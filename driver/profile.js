$(document).ready(function(){
    window.flag_change = false;
    window.flag_start_finish = false;
});

$( "#btn_change" ).on( "click", function() {
    window.data_old = {};
    $('#data').find ('input').each(function() {
        data_old[this.name] = $(this).val();
    });

    $("[name='name'], [name='surname'], [name='email']").prop('disabled', false);
    $("#btn_change").hide();
    if (!flag_change) $(".btn-group").removeAttr('hidden');
    else $(".btn-group").show();
    flag_change = true;
});



$( "#btn_ok" ).on( "click", function() {
    var data_new = {};
    $('#data').find ('input').each(function() {
        data_new[this.name] = $(this).val();
    });


    if ((data_new['name'] === '') || (data_new['surname'] === '') || (data_new['email'] === '')) {
        $('#top_i').html('<i>Заполните все поля</i>');    
    } else if (!data_new['email'].includes('@')) {
        $('#top_i').html('<i>Email должен содержать символ "@"</i>');    
    }  
    
    
    else {
        $('#top_i').empty();

        var id = $("[name='id']").val(); 
        data_new['id'] = id;

        $.ajax({
            url: '/taxi/driver/edit.php',
            type: 'POST', 
            data: data_new,
            success: function(result) {
                if (result) $('#top_i').html(result);
                else {
                    $("[name='name'], [name='surname'], [name='email']").prop('disabled', true);
                    $(".btn-group").hide();
                    $("#btn_change").show();
                }

                
            }
        });

    }

});


$( "#btn_cancel" ).on( "click", function() {
    $('#top_i').empty();
    $("[name='name']").val(data_old['name']);
    $("[name='surname']").val(data_old['surname']);
    $("[name='email']").val(data_old['email']);
    $("[name='name'], [name='surname'], [name='email']").prop('disabled', true);
    $(".btn-group").hide();
    $("#btn_change").show();
});



$( "#start_shift" ).on( "click", function() {
    console.log($('#hidden_input').val());
    if ($('#hidden_input').val() == '') $('#bottom_i').html('<i>Прежде чем выйти на смену, задайте адрес на главной странице</i>');
    else {

        var id = $("[name='id']").val();
        data = {id: id};
        $.ajax({
            url: '/taxi/driver/add_shift.php',
            type: 'POST',
            data: data,
            success: function() {
                $("#start_shift").hide();
                if (!flag_start_finish) $("#finish_shift").removeAttr('hidden');
                else $("#finish_shift").show();
                
                flag_start_finish = true;
            }
        });
    }  

});


$( "#finish_shift" ).on( "click", function() {
    if ($('#hidden_input_new-order').val() == '') {

        var id = $("[name='id']").val();
        data = {id: id};
        $.ajax({
            url: '/taxi/driver/finish_shift.php',
            type: 'POST',
            data: data,
            success: function() {
                $('#hidden_input').val('');
                $("#finish_shift").hide();
                if (!flag_start_finish) $("#start_shift").removeAttr('hidden');
                else $("#start_shift").show();
                flag_start_finish = true;
            }
        });
    } else {
        $('#bottom_i').html('<i>У вас еще есть заказ</i>');
    }
    

});

