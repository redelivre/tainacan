<script>
    $(function (){});
    //funcao que recupera o tipo para realizar o mapeamento
    function extract_metadata(string){
        if(verify_is_url(string)){
            var split_url = string.replace('http://','').replace('https://','').split('/');
            //a url quebrada nos caminhos
            if(split_url.length>0){
                //se possuir o handle na url
                if(split_url.indexOf('handle')>=0){
                    get_handle_metadata(split_url);
                }else if(split_url.indexOf('article')>=0&&split_url.indexOf('view')>=0){
                    
                }
            }
        }
    }
    //funcao que verifica se eh uma url
    function verify_is_url(url){
        if (url != undefined || url != '') {
            var regExp = /(^|\s)((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/gi;
            var match = url.match(regExp);
            if (match) {
                return match;
            }
            else {
                // Do anything for not being valid
                return false;
            }
        }
    }
    // funcao que busca os metadados de um link handle
    /**
     * 
     * @param {array} split_url A url quebrada
     * @returns {undefined}
     */
    function get_handle_metadata(split_url){
        var index = split_url.indexOf('handle');
        var id = split_url[index+2];
        var tag = split_url[index+1];
        var base = split_url[0];
        show_modal_main();
        $.ajax({
            url: $('#src').val() + '/controllers/mapping/mapping_controller.php',
            type: 'POST',
            data: {
                operation: 'get_metadata_handle',
                id: id,
                url: base,
                tag: tag,
                collection_id: $("#collection_id").val()}
        }).done(function (result) {
           var json = JSON.parse(result);
           if(json.hasMapping){
              $.ajax({
                    type: "POST",
                    url: $('#src').val() + "/controllers/object/object_controller.php",
                    data: {collection_id: $('#collection_id').val(), operation: 'edit', object_id: json.object_id}
                }).done(function (result) {
                    $("#form").html('');
                    $('#main_part').hide();
                    $('#display_view_main_page').hide();
                    $('#loader_collections').hide();
                    $('#configuration').html(result).show();
                    $('.dropdown-toggle').dropdown();
                    $('.nav-tabs').tab();
                    hide_modal_main();
                    wpquery_filter();
                });
           }else if(json.html){
               hide_modal_main();
               $('#modal_mapping_metadata').modal('show');
               $('#mapping_metadata_content').html(json.html);
           }else{
               $('.modal').modal('hide');
               showAlertGeneral('<?php _e('Attention', 'tainacan') ?>', '<?php _e('Metadatas not found', 'tainacan') ?>', 'info');
           }
        });
    }
    // funcao que busca os metadados de um link OJS
    /**
     * 
     * @param {array} split_url A url quebrada
     * @returns {undefined}
     */
    function get_ojs_metadata(split_url){
        var article = split_url.indexOf('article');
        var view = split_url.indexOf('view');
        var id = split_url[index+2];
        var tag = split_url[index+1];
        var base = split_url[0];
        show_modal_main();
        $.ajax({
            url: $('#src').val() + '/controllers/mapping/mapping_controller.php',
            type: 'POST',
            data: {
                operation: 'get_metadata_ojs',
                id: id,
                url: base,
                tag: tag,
                collection_id: $("#collection_id").val()}
        }).done(function (result) {
           var json = JSON.parse(result);
           if(json.hasMapping){
              $.ajax({
                    type: "POST",
                    url: $('#src').val() + "/controllers/object/object_controller.php",
                    data: {collection_id: $('#collection_id').val(), operation: 'edit', object_id: json.object_id}
                }).done(function (result) {
                    $("#form").html('');
                    $('#main_part').hide();
                    $('#display_view_main_page').hide();
                    $('#loader_collections').hide();
                    $('#configuration').html(result).show();
                    $('.dropdown-toggle').dropdown();
                    $('.nav-tabs').tab();
                    hide_modal_main();
                    wpquery_filter();
                });
           }else if(json.html){
               hide_modal_main();
               $('#modal_mapping_metadata').modal('show');
               $('#mapping_metadata_content').html(json.html);
           }else{
               $('.modal').modal('hide');
               showAlertGeneral('<?php _e('Attention', 'tainacan') ?>', '<?php _e('Metadatas not found', 'tainacan') ?>', 'info');
           }
        });
    }
    
</script>