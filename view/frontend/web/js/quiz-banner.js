define([
    'jquery',
    'jquery/ui',
    'jquery/jquery.cookie',
    'jquery/jquery-storageapi'
], function ($) {
    "use strict";

    $.widget('bt.quizbanner', {
        storage:              $.localStorage,
        $quizButton:          $('.quiz-button'),
        quizComplete:         $.cookie('bt-quiz-modal'),
        needCustomerRegister: $.cookie('bt-quiz-customer-register'),
        questionsStorageName: 'bt-quiz.questions',

        _create: function () {
            this.removeQuizButton();
            this.redirectToRegister();
        },

        removeQuizButton: function () {
            var self = this;

            if (self.quizComplete) {
                self.$quizButton.remove();
            }
        },

        redirectToRegister: function () {
            var self = this;

            if (!self.options.isLoggedIn && self.needCustomerRegister && self.storage.isSet(self.questionsStorageName)) {
                self.$quizButton.on('click', function (e) {
                    e.preventDefault();
                });
            }
        }

    });

    return $.bt.quizbanner;
});