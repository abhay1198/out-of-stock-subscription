define([
    "jquery",
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function($, confirmation, alert, $t) {
    'use strict';
    $('.oos-table .oos-table-action-delete a').on('click',function(e){
        var self=this;
        e.preventDefault();
        confirmation({
            title: $t('Delete?'),
            content: $t('Are you sure you want to delete this subscribed product?'),
            actions: {
                confirm: function(){
                    var url = $(self).attr('href');
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: url,
                        data: {},
                        beforeSend: function() {
                            $('body').trigger("processStart");
                        },
                        success: function (response) {
                            $('body').trigger("processStop");
                            $(self).closest('.oos-table-row').remove();
                            alert({
                                content: response.message
                            });
                            location.reload();
                        },
                        error: function (response) {
                            $('body').trigger("processStop");
                            alert({
                                content: $t('Something went wrong.')
                            });
                        }
            
                    });
                }
            }
        });
    });
});
