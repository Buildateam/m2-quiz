define([
    'jquery',
    'jquery/ui',
    'jquery/jquery.cookie'
], function ($) {
    "use strict";
        $.widget('bt.quizaccount', {
            _create: function () {
                if (!$.cookie('bt-quiz-modal-'+this.options.quizId) && $.cookie('bt-quiz-customer-register')) {
                       location.replace(this.options.url);
                }
            }
        });
    return $.bt.quizaccount;
});