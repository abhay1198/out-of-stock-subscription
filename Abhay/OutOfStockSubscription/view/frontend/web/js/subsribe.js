define([
    "jquery",
    'Magento_Ui/js/modal/alert',
    "jquery/ui",
], function ($, alert) {
    'use strict';
    $.widget(
        'mage.subsribe',
        {
            _create: function () {
                var self = this;
                var button = self.options.button;
                var stockStatus = self.options.stockStatus;
                var productId = self.options.productId;
                var productName = self.options.productName;
                var action = self.options.action;
                var errorMsg = self.options.errorMsg;
                var error = self.options.error;
                var emptyMsg = self.options.emptyMsg;

                if (stockStatus == 0) {
                    $(".oos-container").removeClass("oos-display-none");
                }

                function validateEmail($email)
                {
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    return emailReg.test($email);
                }

                $(button).click(function() {
                    var email = $('#oos-email').val();
                    if (validateEmail(email) && email != "") {
                       jQuery.ajax({
                            url: action,
                            data : {
                                'email' : email,
                                'productId' : productId,
                                'productName': productName
                            },
                            type: 'POST',
                            success:function(){
                                console.log("saved");
                            }
                       });
                    } else{
                        var errorMessage;
                        if(email == "" ) {
                            errorMessage = emptyMsg;
                        } else {
                            errorMessage = errorMsg;
                        }
                        $(error).append("<b>"+errorMessage+"</b>");
                        $(error).css("display","inline-block");
                    }
                });
            }
        });
    return $.mage.subsribe;
});