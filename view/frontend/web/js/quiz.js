(function (factory) {
    if (typeof define === "function" && define.amd) {
        define([
            'jquery',
            'underscore',
            'jquery/ui',
            'jquery/jquery.cookie',
            'jquery/jquery-storageapi',
            'Magento_Ui/js/modal/modal',
            'Buildateam_Quiz/js/quiz',
            'Buildateam_Quiz/js/slickquiz'
        ], factory);
    } else {
        factory(jQuery);
    }
}(function ($, _, ui, cookie, storageapi) {
    "use strict";

    if(window['loadScript'] === undefined){
        window['loadScript'] = false;
    }

    $.widget('bt.quiz', {
        optionsModal: {
            buttons: [],
            responsive: true,
            modalClass: 'modal-quiz',
            clickableOverlay: true,
            closed: function () {
                $('body').removeClass('quiz-showed');
            }
        },

        /* DOM Elements */
        quizWrapperId : '#modal-wrapper-quiz',
        processedElements: ['.quiz-button'],

        /* Quiz Settings */
        storage: $.sessionStorage,
        expires: 365,
        quizStorageName: 'bt-quiz',
        questionsStorageName: 'bt-quiz.questions',
        questionsNotSelect: 'bt-quiz.questionsNotSelect',
        customerRegisterCookie: 'bt-quiz-customer-register',

        _create: function () {
            if(window['loadScript']===true) {
                return;
            }
            window['loadScript'] = true;
            this.quizStorageName = this.quizStorageName + '-' + this.options.quizId;
            this.questionsStorageName = this.options.quizId + '-' + this.questionsStorageName;
            if (!this.quizLocalStorageDataManager.isCheckedQuiz(this.options.quizId)) {
                this._initQuizAjax(true);
            }

            if (this._isQuizNotFinished()) {
                /* Set custom options to slickquiz */
                this.options['questionsData'] = this.storage.get(this.quizStorageName);
                this.options['customerRegisterCookie'] = this.customerRegisterCookie;
                this.options['expires'] = this.expires;

                $.cookie(this.customerRegisterCookie) ? this._initQuizAfterRegister() : this._initQuizBeforeRegister();
            }
        },

        _initClickEvents: function (elements, fn) {
            $.each(elements, function (indx, el) {
                $(el).on('click', function (e) {
                    fn ? fn(e) : e.stopPropagation();
                });
            });
        },

        _loadModal: function () {
            $('.products-grid').parent().parent().parent().hide();
            $('.beauty-for-women').show();
            this.quizLocalStorageDataManager
                .pullCheckedQuiz(this.options.quizId)
                .save();
        },

        _initQuiz: function () {
            if (this._isQuizNotFinished()) {
                this._scrollToTopPage();
                this._showQuiz();
                this._setToModalClassName();
                this._endQuizHandler();
                this._initSaveToStorage();
                this._initQuestionSwitchAutomatic();
            }
        },

        _initQuizAjax: function (save) {
            var self = this;
            self.options['doneFx'] = function (questionId) {
                if ($(self.quizWrapperId + ' .message.error').is(':hidden')) {
                    self._initClickEvents(self.processedElements);
                    self.__saveQuestionsToStorage(questionId);
                    self._saveResult();
                    $(self.quizWrapperId).modal('closeModal');
                }
            };
            $.post(self.options.getQuizUrl, {id: self.options.quizId}, function (response) {
                if (response.success) {
                    self.options['json'] = response.quiz;
                    $(self.element).slickQuiz(self.options);
                    if(+response.quiz['info']['only_logged'] === 0) {
                        self._loadModal();
                        self._initQuiz();
                    } else if(this.options && this.options.isLoggedIn) {
                        self._loadModal();
                        self._initQuiz();
                    }
                }
            });
        },

        _initQuizClickEvent: function (e) {
            e.preventDefault();
            this._initQuizAjax();
        },

        _initQuizBeforeRegister: function () {
            this._initClickEvents(this.processedElements, $.proxy(this._initQuizClickEvent, this));
        },

        _initQuizAfterRegister: function () {
            this._initQuizAjax();
            this._initClickEvents(this.processedElements, $.proxy(this._initQuizClickEvent, this));
        },

        _showQuiz: function () {
            $('body').addClass('quiz-showed');
            $(this.quizWrapperId).modal(this.optionsModal).modal('openModal');
        },

        _setToModalClassName: function () {

            /* If quiz have lastQuestionId set correct class name to modal */
            if (this.storage.isSet(this.quizStorageName + '.lastQuestionId') || this.options.skipStartButton) {
                $(this.quizWrapperId).closest('.modal-inner-wrap').addClass('modal-quiz-questions');
            }
        },

        _isQuizNotFinished: function () {
            return !$.cookie('bt-quiz-modal-'+this.options.quizId);
        },

        _scrollToTopPage: function () {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        },

        _initSaveToStorage: function () {
            var self = this;

            $(this.quizWrapperId + ' .nextQuestion').on('click', function (e) {
                self._saveQuestionsToStorage(e.currentTarget);
            });

            $(this.quizWrapperId + ' .penultimate').on('click', function (e) {
                self._saveQuestionsToStorage(e.currentTarget);
            });
        },

        _endQuizHandler: function () {
            var self = this;

            $(this.quizWrapperId).on('click', 'a.done', function (e) {
                var target = e.currentTarget;

                setTimeout(function () {
                    /* If not errors */
                    if ($(self.quizWrapperId + ' .message.error').is(':hidden')) {
                        self._initClickEvents(self.processedElements);
                        self._saveQuestionsToStorage(target);

                        /* Save Quiz Result */
                        self._saveResult();

                        $(self.quizWrapperId).modal('closeModal');
                    }
                }, 200);
            });
        },

        __saveQuestionsToStorage: function (questionId) {
            this._removeQuestionsNotSelected();

            /* Removal of old answers from question */
            if (this.storage.isSet(this.questionsStorageName + '.' + questionId)) {
                this.storage.remove(this.questionsStorageName + '.' + questionId);
            }

            /* Save all answers to storage from current question */
            $(document).find('.answers li input:checked').each(function (indx, answer) {
                var currentAnswerId = $(answer).data('id'),
                    storagePath = [questionId, currentAnswerId].join('.');

                this.storage.set(this.questionsStorageName + '.' + storagePath, true);
            }.bind(this));
        },

        _saveQuestionsToStorage: function (target) {
            this._removeQuestionsNotSelected();

            var currentQuestion = $(target).closest('.question'),
                questionId = currentQuestion.data('relation-id');

            /* Removal of old answers from question */
            if (this.storage.isSet(this.questionsStorageName + '.' + questionId)) {
                this.storage.remove(this.questionsStorageName + '.' + questionId);
            }

            /* Save all answers to storage from current question */
            currentQuestion.find('.answers li input:checked').each(function (indx, answer) {
                var currentAnswerId = $(answer).data('id'),
                    storagePath = [questionId, currentAnswerId].join('.');

                this.storage.set(this.questionsStorageName + '.' + storagePath, true);
            }.bind(this));
        },

        _initQuestionSwitchAutomatic: function () {
            $(this.quizWrapperId).on('change', '.answers li input', $.proxy(function (e) {
                var currentAnswer = $(e.currentTarget);

                if (currentAnswer.attr('type') == 'radio') {
                    /* If answer have relation set neighbors to questionsNotSelect */
                    if (currentAnswer.data('relation')) {
                        currentAnswer.closest('.answers').find('li input').each(function (indx, el) {
                            var elRelation = $(el).data('relation');

                            /*
                            * If current answer is checked and was already in the questionsNotSelect
                            * remove it from there and the rest add to storage
                            */

                            if ($(el).prop('checked') && this.storage.isSet(this.questionsNotSelect)) {
                                this.storage.remove([this.questionsNotSelect, elRelation].join('.'));
                            } else {
                                this.storage.set([this.questionsNotSelect, elRelation].join('.'), elRelation);
                            }
                        }.bind(this));
                    }

                    currentAnswer.closest('.question').find('.nextQuestion').click();
                }
            }, this));
        },

        _removeQuestionsNotSelected: function () {
            if (this.storage.isSet(this.questionsNotSelect) && this.storage.isSet(this.questionsStorageName)) {
                $.each(this.storage.get(this.questionsNotSelect), function (questionId) {
                    this.storage.remove(this.questionsStorageName + '.' + questionId);
                }.bind(this));
            }
        },

        _saveResult: function () {
            var self = this;
            var data = this._prepareQuizResult();

            $.post(this.options.saveResultUrl, data, function (response) {
                if (response.success) {
                    $('.quiz-button').remove();
                    $.cookie('bt-quiz-modal-'+self.options.quizId, '1', {expires: this.expires, path: '/'});
                } else {
                    self.storage.remove(self.quizStorageName);
                    $.cookie(self.customerRegisterCookie, '1', {expires: -1});
                }

                if (response.url) {
                    location.replace(response.url);
                }

            });
        },

        _prepareQuizResult: function () {
            var answers = this.storage.get(this.questionsStorageName),
                result = {
                    quiz: this.options.quizId,
                    answers: {}
                };

            $.each(answers, function (indx, answer) {
                result['answers'][indx] = Object.getOwnPropertyNames(answer);
            });

            return result;
        },

        quizLocalStorageDataManager: {
            storage: $.localStorage,
            quiz_data: $.localStorage.get('quiz_data') ? $.localStorage.get('quiz_data') : {
                checkedQuestions: []
            },
            save: function() {
                this.storage.set('quiz_data', this.quiz_data);
                return this;
            },
            isCheckedQuiz: function(quizId) {
                return this.quiz_data.checkedQuestions.includes(+quizId);
            },
            pullCheckedQuiz: function(quizId) {
                this.quiz_data.checkedQuestions.push(+quizId);
                return this;
            }


        }

    });

    return $.bt.quiz;
}));
