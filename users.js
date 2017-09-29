$(document).ready(function(){
    $(".ajax-save").on('click', function(e){
        e.preventDefault();
        var form = $(this).parent('form');
        var data = form.serialize();
        $.ajax({
            type: 'post',
            data: data,
            url: '/',
            dataType: 'json',
            success: function(data) {
                data = $.parseJSON(data);
                console.log(data);
                form.find('.error').empty();
                if(data['status'] == 'error'){
                    var errors = data['errors']
                    for(var key in errors){
                        $('#error-'+key).text(errors[key]);
                    }
                }

                if(data['status'] == 'ok'){
                    refreshListUsers();
                    if(form.attr('id') == 'form-user'){
                        refreshDropListUsers();
                    }
                }
            },
            error: function(){
                alert("error");
            }
        });
    });

    function refreshDropListUsers(){
        var users = getDataUsers('list');
        var listUsers = getTemplateDropListUsers(users);
        $("#Accounts-user_id").html(listUsers);
    }

    function refreshListUsers(){
        var users = getDataUsers('data');
        var listUsers = getTemplateListUsers(users);
        $("#list-users tbody").html(listUsers);
    }

    function getDataUsers(type){
        if(type == undefined){
            type = 'data';
        }
        var users = null;
        $.ajax({
            type: 'GET',
            data: {refreshListUsers: type},
            url: '/',
            dataType: 'json',
            async: false,
            success: function(data) {
                users = data;
            },
            error: function(){
                return null;
            }
        })

        return users;
    }

    function getTemplateListUsers(data){
        var trs = '';
        console.log(data);
        for(var key in data){
            trs += '<tr>'+
                '<td>'+data[key].usr_name+'</td>'+
                '<td>'+data[key].usr_email+'</td>'+
                '<td>'+data[key].usr_address+'</td>'+
                '<td>'+data[key].account+'</td>'+
                '<td>'+data[key].added+'</td>'+
                '</tr>'
        }
        return trs;
    }

    function getTemplateDropListUsers(data){
        var options = '<option value="0">Выберите пользователя</option>';
        console.log(data);
        for(var key in data){
            options += '<option value="'+data[key].id+'">'+data[key].usr_name+'</option>'
        }

        return options;
    }
});