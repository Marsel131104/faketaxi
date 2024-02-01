$(document).ready(function(){
    window.flag = false;
});




$( "#btn_change" ).on( "click", function() {
    window.data_old = {};
    $('#data').find ('input').each(function() {
        data_old[this.name] = $(this).val();
    });

    $("[name='name'], [name='surname'], [name='email']").prop('disabled', false);
    $("#btn_change").hide();
    if (!flag) $(".btn-group").removeAttr('hidden');
    else $(".btn-group").show();
    flag = true;
});



$( "#btn_ok" ).on( "click", function() {
    var data_new = {};
    $('#data').find ('input').each(function() {
        data_new[this.name] = $(this).val();
    });


    if ((data_new['name'] === '') || (data_new['surname'] === '') || (data_new['email'] === '')) {
        $('i').html('<i>Заполните все поля</i>');    
    } else if (!data_new['email'].includes('@')) {
        $('i').html('<i>Email должен содержать символ "@"</i>');    
    }  
    
    
    else {
        $('i').empty();


        var id = $("[name='id']").val();
        data_new['id'] = id;

        $.ajax({
            url: '/taxi/passenger/edit.php',
            type: 'POST', // Или 'POST', в зависимости от метода запроса
            data: data_new,
            success: function(result) {
                if (result) $('i').html(result);
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
    $('i').empty();
    $("[name='name']").val(data_old['name']);
    $("[name='surname']").val(data_old['surname']);
    $("[name='email']").val(data_old['email']);
    $("[name='name'], [name='surname'], [name='email']").prop('disabled', true);
    $(".btn-group").hide();
    $("#btn_change").show();
});