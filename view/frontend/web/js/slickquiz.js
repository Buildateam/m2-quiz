/*!
 * SlickQuiz jQuery Plugin
 * http://github.com/QuickenLoans/SlickQuiz
 *
 * @updated September 23, 2013
 *
 * @author Julie Cameron - http://www.juliecameron.com
 * @copyright (c) 2013 Quicken Loans - http://www.quickenloans.com
 * @license MIT
 */

(function($) {
    $.slickQuiz = function (element, options) {
        var plugin   = this,
            $element = $(element),
            _element = '#' + $element.attr('id'),

            defaults = {
                checkAnswerText:  'Check My Answer!',
                nextQuestionText: 'Next &raquo;',
                doneButtonText: 'Done',
                backButtonText: '',
                displayEndBlock: true,
                displayQuizResult: false,
                tryAgainText: '',
                skipStartButton: false,
                numberOfQuestions: null,
                randomSort: false,
                randomSortQuestions: false,
                randomSortAnswers: false,
                preventUnanswered: false,
                completionResponseMessaging: false,
                disableResponseMessaging: false,
                accountUrl: ''
            },

        // Class Name Strings (Used for building quiz and for selectors)
            questionCountClass     = 'questionCount',
            questionGroupClass     = 'questions',
            questionClass          = 'question',
            answersClass           = 'answers',
            responsesClass         = 'responses',
            correctClass           = 'correctResponse',
            correctResponseClass   = 'correct',
            incorrectResponseClass = 'incorrect',
            checkAnswerClass       = 'checkAnswer',
            nextQuestionClass      = 'nextQuestion',
            backToQuestionClass    = 'backToQuestion',
            tryAgainClass          = 'tryAgain',
            penultimateQuestionClass = 'penultimate',

        // Sub-Quiz / Sub-Question Class Selectors
            _questionCount         = '.' + questionCountClass,
            _questions             = '.' + questionGroupClass,
            _question              = '.' + questionClass,
            _answers               = '.' + answersClass,
            _responses             = '.' + responsesClass,
            _correct               = '.' + correctClass,
            _correctResponse       = '.' + correctResponseClass,
            _incorrectResponse     = '.' + incorrectResponseClass,
            _checkAnswerBtn        = '.' + checkAnswerClass,
            _nextQuestionBtn       = '.' + nextQuestionClass,
            _prevQuestionBtn       = '.' + backToQuestionClass,
            _tryAgainBtn           = '.' + tryAgainClass,
            _penultimateQuestionBtn = '.' + penultimateQuestionClass,

        // Top Level Quiz Element Class Selectors
            _quizStarter           = _element + ' .startQuiz',
            _quizName              = _element + ' .quizName',
            _quizArea              = _element + ' .quizArea',
            _quizResults           = _element + ' .quizResults',
            _quizResultsCopy       = _element + ' .quizResultsCopy',
            _quizHeader            = _element + ' .quizHeader',
            _quizScore             = _element + ' .quizScore',
            _quizLevel             = _element + ' .quizLevel',
            _quizEndBlock          = _element + ' .quizEndBlock',
            _questionsHeader       = _element + ' .questionsHeader',
            _message               = _element + ' .message',

        // Top Level Quiz Element Objects
            $quizStarter           = $(_quizStarter),
            $quizName              = $(_quizName),
            $quizArea              = $(_quizArea),
            $quizResults           = $(_quizResults),
            $quizResultsCopy       = $(_quizResultsCopy),
            $quizHeader            = $(_quizHeader),
            $quizScore             = $(_quizScore),
            $quizLevel             = $(_quizLevel),
            $quizEndBlock          = $(_quizEndBlock),
            $questionsHeader       = $(_questionsHeader),
            $message               = $(_message),

            questionsNotSelectName = 'bt-quiz.questionsNotSelect';

        // Reassign user-submitted deprecated options
        var depMsg = '';

        if (options && typeof options.disableNext != 'undefined') {
            if (typeof options.preventUnanswered == 'undefined') {
                options.preventUnanswered = options.disableNext;
            }
            depMsg += 'The \'disableNext\' option has been deprecated, please use \'preventUnanswered\' in it\'s place.\n\n';
        }

        if (depMsg !== '') {
            if (typeof console != 'undefined') {
                console.warn(depMsg);
            } else {
                alert(depMsg);
            }
        }
        // End of deprecation reassignment


        plugin.config = $.extend(defaults, options);

        // Set via json option or quizJSON variable (see slickQuiz-config.js)
        var quizValues = (plugin.config.json ? plugin.config.json : typeof quizJSON != 'undefined' ? quizJSON : null);

        // Get questions, possibly sorted randomly
        var questions = _.sortBy(quizValues.questions, function(obj) {
            return +obj.sort;
        });

        var questionsNotSort = quizValues.questions;

        // Count the number of questions
        var questionCount = Object.getOwnPropertyNames(questionsNotSort).length;

        // Select X number of questions to load if options is set
        if (plugin.config.numberOfQuestions && questionCount >= plugin.config.numberOfQuestions) {
            questions = questions.slice(0, plugin.config.numberOfQuestions);
            questionCount = Object.getOwnPropertyNames(questionsNotSort).length;
        }

        plugin.method = {
            // Sets up the questions and answers based on above array
            setupQuiz: function() {
                $quizName.hide().html(quizValues.info.name).fadeIn(1000);
                $quizHeader.hide().prepend(quizValues.info.main).fadeIn(1000);
                $quizResultsCopy.append(quizValues.info.results);
                var questionsData = plugin.config.questionsData;

                // add retry button to results view, if enabled
                if (plugin.config.tryAgainText && plugin.config.tryAgainText !== '') {
                    $quizResultsCopy.before('<a class="button ' + tryAgainClass + '" href="#">' + plugin.config.tryAgainText + '</a>');
                }

                // Setup questions
                var quiz  = $('<ol class="' + questionGroupClass + '"></ol>'),
                    count = 1;

                // Loop through questions object
                for (i in questions) {


                    if (questions.hasOwnProperty(i)) {
                        var question = questions[i];
                        var questionId = 'question' + question.id;

                        var questionHTML = $('<li class="' + questionClass +'" id="' + questionId +
                            '" data-relation-id="' + question.id + '"></li>');

                        questionHTML.append('<h3>' + question.q + '</h3>');

                        // Now let's append the answers with checkboxes or radios depending on truth count
                        var answerHTML = $('<ul class="' + answersClass + '"></ul>');

                        // Get the answers
                        var answers = plugin.config.randomSort || plugin.config.randomSortAnswers ?
                            question.a.sort(function() { return (Math.round(Math.random())-0.5); }) :
                            question.a;

                        // prepare a name for the answer inputs based on the question
                        var selectAny  = question.select_any ? question.select_any : false,
                            inputName  = 'question' + question.id,
                            inputType  = question.multiple ? 'checkbox' : 'radio';

                        for (i in answers) {
                            if (answers.hasOwnProperty(i)) {
                                answer   = answers[i];
                                optionId = inputName + '_' + answer.id;
                                var checked = '';

                                if (questionsData && questionsData.questions && (question.id in questionsData.questions)) {
                                    var isChecked = (answer.id in questionsData.questions[question.id]);
                                    checked = isChecked ? 'checked="checked"' : '';
                                }

                                var relation = answer.relation ? 'data-relation="' + answer.relation + '"' : '';

                                // If question has >1 true answers and is not a select any, use checkboxes; otherwise, radios
                                var input = '<input id="' + optionId + '" name="' + question.id +
                                    '" type="' + inputType + '" ' + checked + ' data-id="'+ answer.id +'" ' + relation + ' />';

                                // Parse answer type of string with __ in classes
                                var answerTypeClasses = answer.type.replace(new RegExp('__','g'), ' ');

                                var classes = [answerTypeClasses, answer.img ? 'with-img' : 'not-img'].join(' ');

                                var background = answer.img ? "background: " + "url('"+ answer.img +"')" :
                                    answer.color ? "background: " + answer.color : "";

                                var title = answer.title ? answer.title : "";

                                var optionLabelTemplate = '<label for="{optionId}" style="{background}">{title}</label>';

                                var optionLabel = optionLabelTemplate.replace('{optionId}', optionId)
                                    .replace('{background}', background)
                                    .replace('{title}', title);

                                var answerContent = $('<li class="' + classes + '"></li>')
                                    .append(input)
                                    .append(optionLabel);
                                answerHTML.append(answerContent);
                            }
                        }

                        // Append answers to question
                        questionHTML.append(answerHTML);

                        // If response messaging is NOT disabled, add it
                        if (!plugin.config.disableResponseMessaging) {
                            // Now let's append the correct / incorrect response messages
                            var responseHTML = $('<ul class="' + responsesClass + '"></ul>');
                            responseHTML.append('<li class="' + correctResponseClass + '">' + question.correct + '</li>');
                            responseHTML.append('<li class="' + incorrectResponseClass + '">' + question.incorrect + '</li>');

                            // Append responses to question
                            questionHTML.append(responseHTML);
                        }

                        // Appends check answer / back / next question buttons
                        if (plugin.config.backButtonText && plugin.config.backButtonText !== '') {
                            questionHTML.append('<a href="#" class="button ' + backToQuestionClass + '">' + plugin.config.backButtonText + '</a>');
                        }

                        var procentProgress = (count / questionCount * 100) + '%';
                        var btnText = (count == questionCount) ? plugin.config.doneButtonText : plugin.config.nextQuestionText;
                        var classDone = (count == questionCount) ? 'done' : '';

                        var isPenultimate = (count == questionCount - 1);
                        var penultimateClass = isPenultimate ? penultimateQuestionClass : '';
                        var penultimateUrl = isPenultimate ? plugin.config.accountUrl : '#';

                        var nextClass = isPenultimate ? '' : nextQuestionClass;
                        var checkClass = isPenultimate ? '' : checkAnswerClass;

                        // If response messaging is disabled or hidden until the quiz is completed,
                        // make the nextQuestion button the checkAnswer button, as well
                        if (plugin.config.disableResponseMessaging || plugin.config.completionResponseMessaging) {
                            questionHTML.append('<a href="#" class="button ' + nextQuestionClass + ' ' + checkAnswerClass + '">' + plugin.config.nextQuestionText + '</a>');
                        } else {
                            questionHTML.append('<a href="#" class="button ' + nextQuestionClass + '">' + plugin.config.nextQuestionText + '</a>');
                            questionHTML.append('<a href="#" class="button ' + checkAnswerClass + '">' + plugin.config.checkAnswerText + '</a>');
                        }

                        // Append question & answers to quiz
                        quiz.append(questionHTML);

                        count++;
                    }
                }

                // Add the quiz content to the page
                $quizArea.append(quiz);

                // Toggle the start button OR start the quiz if start button is disabled
                if (plugin.config.skipStartButton || $quizStarter.length == 0) {
                    $quizStarter.hide();
                    plugin.method.startQuiz(this);
                } else {
                    $quizStarter.fadeIn(500);
                }

                if (questionsData && questionsData.lastQuestionId) {

                    var lastQuestionId = 'question' + questionsData.lastQuestionId,
                        currentQuestion = $('#' + lastQuestionId),
                        nextQuestion = currentQuestion;

                    if (currentQuestion.find('a').is(_penultimateQuestionBtn) && $.cookie('bt-quiz-customer-register')) {
                        nextQuestion = currentQuestion.next();
                    }

                    $quizName.hide();
                    $quizHeader.hide();
                    $questionsHeader.fadeIn(500);
                    nextQuestion.find(_prevQuestionBtn).show().end().fadeIn(500);
                }
            },

            // Starts the quiz (hides start button and displays first question)
            startQuiz: function() {
                function start() {
                    var firstQuestion = $(_element + ' ' + _questions + ' li').first();
                    if (firstQuestion.length) {
                        $(_element).closest('.modal-inner-wrap').addClass('modal-quiz-questions');
                        $quizName.hide();
                        $quizHeader.hide();
                        firstQuestion.find(_prevQuestionBtn).remove();
                        $questionsHeader.fadeIn(500);
                        firstQuestion.fadeIn(500);
                    }
                    $('body').removeClass('quiz-showed');
                }

                if (plugin.config.skipStartButton || $quizStarter.length == 0) {
                    start();
                } else {
                    $quizStarter.fadeOut(300, function(){
                        start();
                    });
                }
            },

            // Resets (restarts) the quiz (hides results, resets inputs, and displays first question)
            resetQuiz: function(startButton) {
                $quizResults.fadeOut(300, function() {
                    $(_element + ' input').prop('checked', false).prop('disabled', false);

                    $quizLevel.attr('class', 'quizLevel');
                    $(_element + ' ' + _correct).removeClass(correctClass);

                    $(_element + ' ' + _question          + ',' +
                        _element + ' ' + _responses         + ',' +
                        _element + ' ' + _correctResponse   + ',' +
                        _element + ' ' + _incorrectResponse + ',' +
                        _element + ' ' + _nextQuestionBtn   + ',' +
                        _element + ' ' + _prevQuestionBtn
                    ).hide();

                    $(_element + ' ' + _questionCount + ',' +
                        _element + ' ' + _answers + ',' +
                        _element + ' ' + _checkAnswerBtn
                    ).show();

                    $quizArea.append($(_element + ' ' + _questions)).show();

                    plugin.method.startQuiz($quizResults);
                });
            },

            // Validates the response selection(s), displays explanations & next question button
            checkAnswer: function(checkButton) {
                var questionLI    = $($(checkButton).parents(_question)[0]),
                    answerInputs  = questionLI.find('input:checked'),
                    questionIndex = parseInt(questionLI.attr('id').replace(/(question)/, ''), 10),
                    answers       = questionsNotSort[questionIndex].a,
                    selectAny     = questionsNotSort[questionIndex].select_any ? questionsNotSort[questionIndex].select_any : false;

                // Collect the true answers needed for a correct response
                var trueAnswers = [];
                for (i in answers) {
                    if (answers.hasOwnProperty(i)) {
                        var answer = answers[i];

                        if (answer.correct) {
                            trueAnswers.push($('<div />').html(answer.title).text());
                        }
                    }
                }

                // NOTE: Collecting .text() for comparison aims to ensure that HTML entities
                // and HTML elements that may be modified by the browser match up

                // Collect the answers submitted
                var selectedAnswers = [];
                answerInputs.each( function() {
                    var inputValue = $(this).next('label').text();
                    selectedAnswers.push(inputValue);
                });

                if (plugin.config.preventUnanswered && selectedAnswers.length === 0) {
                    $message.fadeIn(300);
                    return false;
                } else {
                    $message.fadeOut(100);
                }

                // Verify all/any true answers (and no false ones) were submitted
                var correctResponse = plugin.method.compareAnswers(trueAnswers, selectedAnswers, selectAny);

                if (correctResponse) {
                    questionLI.addClass(correctClass);
                }

                // If response messaging hasn't been disabled, toggle the proper response
                if (!plugin.config.disableResponseMessaging) {
                    // If response messaging hasn't been set to display upon quiz completion, show it now
                    if (!plugin.config.completionResponseMessaging) {
                        questionLI.find(_answers).hide();
                        questionLI.find(_responses).show();

                        $(checkButton).hide();
                        questionLI.find(_nextQuestionBtn).fadeIn(300);
                        questionLI.find(_prevQuestionBtn).fadeIn(300);
                    }

                    // Toggle responses based on submission
                    questionLI.find(correctResponse ? _correctResponse : _incorrectResponse).fadeIn(300);
                }

                return true;
            },

            // Moves to the next question OR completes the quiz if on last question
            nextQuestion: function(nextButton) {
                var currentQuestion = $($(nextButton).parents(_question)[0]),
                    nextQuestion    = currentQuestion.next(_question),
                    answerInputs    = currentQuestion.find('input:checked'),
                    nextQuestionLength = nextQuestion.length;

                // If response messaging has been disabled or moved to completion,
                // make sure we have an answer if we require it, let checkAnswer handle the alert messaging
                if (plugin.config.preventUnanswered && answerInputs.length === 0) {
                    return false;
                }

                if (answerInputs.data('relation')) {
                    var relation = answerInputs.data('relation');
                    nextQuestion = $(_question + '[data-relation-id="' + relation + '"]');
                } else if ($.sessionStorage.isSet(questionsNotSelectName)) {

                    var questionsNotSelect = $.sessionStorage.get(questionsNotSelectName),
                        startScan = false;

                    $(_question).each(function (indx, el) {
                        var elRelation = $(el).data('relation-id');

                        if (elRelation == nextQuestion.data('relation-id')) {
                            startScan = true;
                        }

                        if (startScan && !(elRelation in questionsNotSelect)) {
                            nextQuestion = $(el);
                            return false;
                        }
                    });
                }

                if (nextQuestionLength) {
                    currentQuestion.fadeOut(300, function(){

                        /* Save last question to storage */
                        $.sessionStorage.set('bt-quiz.lastQuestionId', nextQuestion.data('relation-id'));

                        nextQuestion.find(_prevQuestionBtn).show().end().fadeIn(500);
                    });
                } else {
                    plugin.method.completeQuiz();
                }
            },

            // Go back to the last question
            backToQuestion: function(backButton) {
                var questionLI = $($(backButton).parents(_question)[0]),
                    answers    = questionLI.find(_answers);

                // Back to previous question
                if (answers.css('display') === 'block' ) {

                    var prevQuestion = questionLI.prev(_question);

                    if ($.sessionStorage.get('bt-quiz'+plugin.config.quizId) && $.sessionStorage.get(questionsNotSelectName)) {

                        var questionsNotSelect = $.sessionStorage.get(questionsNotSelectName),
                            startScan = false,
                            reverseQuestions = $(_question).get().reverse();

                        $(reverseQuestions).each(function (indx, el) {
                            var elRelation = $(el).data('relation-id');

                            if (elRelation == prevQuestion.data('relation-id')) {
                                startScan = true;
                            }

                            if (startScan && !(elRelation in questionsNotSelect)) {
                                prevQuestion = $(el);
                                return false;
                            }
                        });
                    }

                    $message.fadeOut(300);

                    questionLI.fadeOut(300, function() {
                        prevQuestion.removeClass(correctClass);
                        prevQuestion.find(_responses + ', ' + _responses + ' li').hide();
                        prevQuestion.find(_answers).show();
                        prevQuestion.find(_checkAnswerBtn).show();

                        // If response messaging hasn't been disabled or moved to completion, hide the next question button
                        // If it has been, we need nextQuestion visible so the user can move forward (there is no separate checkAnswer button)
                        if (!plugin.config.disableResponseMessaging && !plugin.config.completionResponseMessaging) {
                            prevQuestion.find(_nextQuestionBtn).hide();
                        }

                        if (prevQuestion.attr('id') != $(_question).first().attr('id')) {
                            prevQuestion.find(_prevQuestionBtn).show();
                        } else {
                            prevQuestion.find(_prevQuestionBtn).hide();
                        }

                        prevQuestion.fadeIn(500);
                    });

                    // Back to question from responses
                } else {
                    questionLI.find(_responses).fadeOut(300, function(){
                        questionLI.removeClass(correctClass);
                        questionLI.find(_responses + ' li').hide();
                        answers.fadeIn(500);
                        questionLI.find(_checkAnswerBtn).fadeIn(500);
                        questionLI.find(_nextQuestionBtn).hide();

                        // if question is first, don't show back button on question
                        if (questionLI.attr('id') != $(_question).first().attr('id')) {
                            questionLI.find(_prevQuestionBtn).show();
                        } else {
                            questionLI.find(_prevQuestionBtn).hide();
                        }
                    });
                }
            },

            // Hides all questions, displays the final score and some conclusive information
            completeQuiz: function() {
                if(plugin.config.doneFx){
                    plugin.config.doneFx(plugin.config.quizId);
                }
            },

            // Compares selected responses with true answers, returns true if they match exactly
            compareAnswers: function(trueAnswers, selectedAnswers, selectAny) {
                if ( selectAny ) {
                    return $.inArray(selectedAnswers[0], trueAnswers) > -1;
                } else {
                    // crafty array comparison (http://stackoverflow.com/a/7726509)
                    return ($(trueAnswers).not(selectedAnswers).length === 0 && $(selectedAnswers).not(trueAnswers).length === 0);
                }
            },

            // Calculates knowledge level based on number of correct answers
            calculateLevel: function(correctAnswers) {
                var percent = (correctAnswers / questionCount).toFixed(2),
                    level   = null;

                if (plugin.method.inRange(0, 0.20, percent)) {
                    level = 4;
                } else if (plugin.method.inRange(0.21, 0.40, percent)) {
                    level = 3;
                } else if (plugin.method.inRange(0.41, 0.60, percent)) {
                    level = 2;
                } else if (plugin.method.inRange(0.61, 0.80, percent)) {
                    level = 1;
                } else if (plugin.method.inRange(0.81, 1.00, percent)) {
                    level = 0;
                }

                return level;
            },

            // Determines if percentage of correct values is within a level range
            inRange: function(start, end, value) {
                return (value >= start && value <= end);
            }
        };

        plugin.init = function() {
            // Setup quiz
            plugin.method.setupQuiz();

            // Bind "start" button
            $quizStarter.on('click', function(e) {
                e.preventDefault();
                plugin.method.startQuiz();
            });

            // Bind "try again" button
            $(_element + ' ' + _tryAgainBtn).on('click', function(e) {
                e.preventDefault();
                plugin.method.resetQuiz(this);
            });

            // Bind "check answer" buttons
            $(_element + ' ' + _checkAnswerBtn).on('click', function(e) {
                e.preventDefault();
                plugin.method.checkAnswer(this);
            });

            // Bind "back" buttons
            $(_element + ' ' + _prevQuestionBtn).on('click', function(e) {
                e.preventDefault();
                plugin.method.backToQuestion(this);
            });

            // Bind "next" buttons
            $(_element + ' ' + _nextQuestionBtn).on('click', function(e) {
                e.preventDefault();
                plugin.method.nextQuestion(this);
            });

            // Bind "penultimate" button
            $(_element + ' ' + _penultimateQuestionBtn).on('click', function(e) {
                // e.preventDefault();
                if (!plugin.config.isLoggedIn) {
                    if (plugin.method.checkAnswer(this)) {
                        $.cookie(plugin.config.customerRegisterCookie, '1', {expires: plugin.config.expires});
                    }
                } else {
                    plugin.method.nextQuestion(this);
                }
            });
        };

        plugin.init();
    };

    $.fn.slickQuiz = function(options) {
        return this.each(function() {
            if (undefined === $(this).data('slickQuiz')) {
                var plugin = new $.slickQuiz(this, options);
                $(this).data('slickQuiz', plugin);
            }
        });
    };
})(jQuery);
