  // --------------------------------------------------------------------
        function dialog_material()
        {
            $( "#dialog-material" ).dialog({
                height: 200,
                width: 400,
                modal: true,
                buttons: {
                    "Отменить": function() {
                        {
                            $( "#dialog-material" ).find('label').empty();
                            $( this ).dialog( "close" );    
                        }
                    }
                }
            }); 
        };
        // --------------------------------------------------------------------
        function public_material(bool)
        {
            if($("input[name^=material_]:checked").length == 0)
            {
                $( "#dialog-material" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');    
                dialog_material();
            }
            else
            {
                var data = $("input[name^=material_]:checked");
                var mas = new Array;
                $.each(data, function(i, value){
                    mas[i] =value.defaultValue;
                });
                $.ajax({
                    type: "POST",
                    dataType: 'html',
                    url:"/content/public_material",
                    data: 'data='+mas+'&bool='+bool,
                    success:function(data){
                        if (data)
                        {
                            $('#load_for_ajax').html(data);
                        }
                    }
                }); 
            }
        }
        // --------------------------------------------------------------------
        function favorite()
        {
            var a = this.parent();
        }
        $(function(){
            $('.favorite').click(function(){
                var mas = new Array();
                mas[0] = this.parentElement.parentElement.children[0].children[0].defaultValue;
                $.ajax({
                    type: "POST",
                    dataType: 'html',
                    url:"/content/favorite_material",
                    data: 'data='+mas+'&radio='+1,
                    success:function(data){
                        if (data)
                        {
                            $('#load_for_ajax').html(data);
                        }
                    }
                }); 
            }); 
        });
        // --------------------------------------------------------------------
        $(function() {
            $('#create').click(function(){
                $.ajax({
                    dataType: 'html',
                    url:"/content/create",
                    success:function(data){
                        if (data)
                        {
                            $('#admin-content').html(data);
                        }
                    }
                }); 
            });
        });
        // --------------------------------------------------------------------
        $(function(){
            $('#edit').click(function(){
                if ($("input[name^=material_]:checked").length==1)
                {
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/content/edit",
                        data: 'id_edit='+$("input[name^=material_]:checked")[0].defaultValue,
                        success:function(data){
                            if (data)
                            {
                                $('#admin-content').html(data);
                            }
                        }
                    }); 
                }
                else if($("input[name^=material_]:checked").length > 1)
                {
                    $( "#dialog-material" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');    
                    dialog_material();
                }
                else
                {
                    $( "#dialog-material" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');    
                    dialog_material();
                }
            });
        });
        // --------------------------------------------------------------------
        $(function(){
            $('#favorite_material').click(function(){
                if($("input[name^=material_]:checked").length == 0)
                {
                    $( "#dialog-material" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');    
                    dialog_material();
                }
                else
                {
                    var data = $("input[name^=material_]:checked");
                    var mas = new Array;
                    $.each(data, function(i, value){
                        mas[i] =value.defaultValue;
                    });
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/content/favorite_material",
                        data: 'data='+mas,
                        success:function(data){
                            if (data)
                            {
                                $('#load_for_ajax').html(data);
                            }
                        }
                    }); 
                }
            });
        });
        $(function(){
            $('#trash_material').click(function(){
                if($("input[name^=material_]:checked").length == 0)
                {
                    $( "#dialog-material" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');    
                    dialog_material();
                }
                else
                {
                    var data = $("input[name^=material_]:checked");
                    var mas = new Array;
                    $.each(data, function(i, value){
                        mas[i] =value.defaultValue;
                    });
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/content/trash_material",
                        data: 'data='+mas,
                        success:function(data){
                            if (data)
                            {
                                $('#load_for_ajax').html(data);
                            }
                        }
                    }); 
                }
            });
        });
        // --------------------------------------------------------------------