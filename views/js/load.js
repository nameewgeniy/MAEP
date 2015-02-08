jQuery( function($){

    cont = {
        'load' : $('#publish_maep'), // кнопка загрузки товаров
        'select' : $('#maep_categories'), // выпадающий список категорий
        'result' : $('#cat_result'), // список подкатегорий в блоке
        'load_html' : '<div style=""><div class="loader " id="load_main">Loading...</div></div>', // Html загрузчика
        'loader2' : '<div id="floatingBarsG"><div class="blockG" id="rotateG_01"></div><div class="blockG" id="rotateG_02"></div><div class="blockG" id="rotateG_03"></div><div class="blockG" id="rotateG_04"></div><div class="blockG" id="rotateG_05"></div><div class="blockG" id="rotateG_06"></div><div class="blockG" id="rotateG_07"></div><div class="blockG" id="rotateG_08"></div></div>',
        'load_stat' : $('#load_main'), // загрузчик
        'main_cat' : $('.name_main_cat'),
        'search_custom' : $('#search_custom'),
        'search_key' : $('#search_key'),
        'delete_all_product' : $('#delete_all_product'),
        'publish_maep_key' : $('#publish_maep_key')
    }

    cont.select.select2();

    // выбор главной категории
    cont.select.on('change', function(){
        cont.result.html(cont.load_html);
        var selected = cont.select.find('option:selected');
        cont.main_cat.html(selected.html());
        if (selected.data('id') != 0)
        {
            $('#load_main').css('display','block');
            var ID = selected.data('id');
            data = {
                id : ID,
                action : 'list_cat'
            }
            jQuery.post(ajaxurl, data, function(response) {
                $('#cat_result').html(response);
            });

            QueryPopularSearch(ID,'.popular-search',selected);

        }
        else
            alert("Select main category");
    });

    // загрузка популярных запросов
    function QueryPopularSearch(id_cat, selector, selected)
    {
        $(selector).html(cont.loader2);
        data = {
            id : id_cat,
            action : 'list_pop'
        }
        jQuery.post(ajaxurl, data, function(response) {
            $(selector).html('<h2 style="text-align: center; padding: 20px 0; font-weight: bold;">Popular Searches for : ' + selected.html() + '</h2>'+response);
        });
    }

    // поиск по категории
    cont.search_custom.on('click', function(){
        cont.result.html(cont.load_html);
        if ( $('#custom_id').val() != '')
        {
            $('#load_main').css('display','block');
            var ID = $('#custom_id').val();
            data = {
                id : ID,
                flag : 'custom',
                action : 'list_cat'
            }
            jQuery.post(ajaxurl, data, function(response) {
                $('#cat_result').html(response);
                QueryPopularSearch(ID,'.popular-search',$('body #cat_result .category_name:first'));
            });

        }
        else
            alert("Enter id category ");
    })

    // выбор подкатегории
    $('.info_load').on('click', '.a_click', function(){
        var ID = $(this).data('catid');
        if ($(this).hasClass('glyphicon-plus'))
        {
            $('.loader-'+ID).css('display','block');
            var selected = $(this).parents('.cat_c').find('.category_name');
            $(this).removeClass('glyphicon-plus').addClass('glyphicon-minus');
            $(this).parents('.main_list').find('.'+ID).css('display','block');
            data = {
                id : ID,
                action : 'list_cat'
            }
            jQuery.post(ajaxurl, data, function(response) {
                $('.a_click').parents('.main_list').find('.'+ID).html(response);
            });
            QueryPopularSearch(ID,'.popular-search',selected);
        }else
        {
            $(this).removeClass('glyphicon-minus').addClass('glyphicon-plus');
            $(this).parents('.main_list').find('.'+ID).css('display','none');
        }
    })

    // поиск по категории для баннера
    $('#view_ids').on('click', function(){
            $('#rev_ids').html('<div style=""><div class="loader " id="load_main">Loading...</div></div>');
            $('#rev_ids #load_main').css('display','block');
            data = {
                id : -1,
                flag : 'custom',
                action : 'list_cat_ids'
            }
            console.log(data);
            jQuery.post(ajaxurl, data, function(response) {
                $('#rev_ids').html(response);
            });


    })

    // загрузка гайдов
    $('#load_review').on('click', function(){
        var page = $('#check_review').val();
        var id = $('#input-id-review').val();
        load_review(id,1,page);

    });



    // выбор подкатегории для баннера
    $('.settings-banner').on('click', '.a_click', function(){
        var ID = $(this).data('catid');
        if ($(this).hasClass('glyphicon-plus'))
        {
            $('.loader-'+ID).css('display','block');
            var selected = $(this).parents('.cat_c').find('.category_name');
            $(this).removeClass('glyphicon-plus').addClass('glyphicon-minus');
            $(this).parents('.main_list').find('.'+ID).css('display','block');
            data = {
                id : ID,
                action : 'list_cat_ids'
            }
            jQuery.post(ajaxurl, data, function(response) {
                $('.a_click').parents('.main_list').find('.'+ID).html(response);
            });
        }else
        {
            $(this).removeClass('glyphicon-minus').addClass('glyphicon-plus');
            $(this).parents('.main_list').find('.'+ID).css('display','none');
        }
    })

    // Основная загрузка товаров
    cont.load.on('click', function(){
        var ids = new Array();
        $('#home input:checkbox:checked ').each(function(){
            ids.push($(this).val());
        });
        InsertBar('#cat_result', 0, 'Wait...');
        var per_page = $('#count-per-page').val();
        var keywords = $('#keywords_cat_id').val();
        data = {
            ids : ids,
            action : 'count_cat',
            perpage : per_page,
            keywords : keywords
        }
        console.log(data);
        jQuery.post(ajaxurl, data, function(response) {
            console.log(response);
           var data = JSON.parse(response);
           recurs_ajax(data['ids'], 0, 'load_products', '#cat_result', data['count'], data['cat'], per_page, keywords)
        });

    })



    // Втавка прогресс бара
    function InsertBar(selector, now, message)
    {
        $(selector).html("<div class='load_cont' ><div id='load_stat' style=' width: " + now + "%;' ></div></div><h3 class='bar_message'>" + message + "</h3>");
    }

    // Изменение прогресса загрузчика
    function StatusBar(selector, now, message)
    {
        $(selector).find('#load_stat').css('width',now+'%');
        $('.bar_message').html(message);

    }

     // загрузка ревью
    function load_review(id, step, page)
    {
        data = {
            id : id,
            action : 'upload_reviews',
            perpage : step
        }
        console.log(data);
        InsertBar('#rev_ids', 0, 'Wait...');
        jQuery.post(ajaxurl, data).done( function(response) {
            console.log(response);
            if (response <= page){
                load_review(id,step, page);
                StatusBar('#rev_ids',100/step,'Step - '+step);
            }
            else
                StatusBar('#rev_ids',100,'done');

        }).fail(function(){
            StatusBar('#rev_ids',100,'fail...');
        });
    }

    // проверка количества гайдов
    $('#check_count').on('click', function(){
        var id = $('#input-id-review').val();
        data = {
            id : id,
            action : 'check_review'
        };
        console.log(data);
        jQuery.post(ajaxurl, data).done( function(response) {
            alert('Count Guide '+response);
        }).fail(function(){
            alert('fail');
        });
    });


    // Рекурсивный аякс запрос на сервер
    function recurs_ajax(ids, step, action, wrap, count, custom_par, per_page, keywords)
    {
        if (ids.length > step)
        {
            StatusBar(wrap, step*100/ids.length, 'Count categories: ' + count + ' / in process: ' + custom_par[step][0] );
            id = ids[step][0];
            data = {
                action: action,
                ids: id,
                custom_par: custom_par[step][0],
                perpage: per_page,
                keywords : keywords
            }
            jQuery.post(ajaxurl, data).done(function(response) {
                console.log(response);
                step = step + 1;
                recurs_ajax(ids, step, action, wrap, count, custom_par, per_page, keywords);
            }).fail(function(response) {
                console.log(response);
                step = step + 1;
                recurs_ajax(ids, step, action, wrap, count, custom_par, per_page, keywords);
            })
        }
        else
        {
            StatusBar(wrap, 100, 'Done! Products has been uploaded ' );
        }
    }

    cont.search_key.on('click', function(){
        var keywords = $('#input-keywords').val();
        var count_search = $('#count_search').val();
        $('#list_item').html(cont.load_html);
        $('#load_main').css('display','block');
        var data = {
            action: 'search_keyword',
            keywords: keywords,
            count_search: count_search
        };
        console.log(data);
        jQuery.post(ajaxurl, data, function(response) {
            $('#list_item').html(response);
        });
    })

    // удаление всех товаров
    cont.delete_all_product.on('click', function(){
        var data = {
            action: 'delete_all'
        };
        if (confirm('Do you want to remove all of the products?'))
        {
            $('#delete-products').html(cont.load_html);
            $('#load_main').css('display','block');
            jQuery.post(ajaxurl, data, function(response) {
                $('#delete-products').html(response);
            });
        }

    })

    // добавление товара по keyword
   cont.publish_maep_key.on('click', function(){
       var checked_ids = $('input.list-item-ids:checkbox:checked');
       if (checked_ids.length > 0)
       {
           InsertBar('#list_item', 10, 'Wait...');
           var ids_items = new Array();
           var name_cat = new Array();
           checked_ids.each(function(){
               ids_items.push($(this).val());
               name_cat.push($(this).data('idcat'));
           });
           // post ajax
           var data = {
               action: 'load_one_item',
               id_one: ids_items,
               name_cat: name_cat
           };
           console.log(data);
           jQuery.post(ajaxurl, data, function(response) {
               console.log(response);
                InsertBar('#list_item', 100, 'Done...');
           });
       }
       else alert('Select product');
   })

    $(document).on('click', '#select_all', function(){
        $("input[type=checkbox].list-item-ids").prop('checked', true);
        console.log($(".list-item-ids").length);
    })



});