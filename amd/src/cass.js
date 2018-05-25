/**
 * This file is part of Moodle - http://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   theme_cass
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* exported cassInit */
/* eslint no-invalid-this: "warn"*/

/**
 * Main cass initialising function.
 */

require.config({
    enforceDefine: false,
    paths: {
        'dependency': 'TweenMax'
        //'dependency': 'https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js'
    }
});

require.config({
    enforceDefine: false,
    paths: {
        'dependency': 'TweenLite'
        //'dependency': 'https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.1/TweenMax.min.js'
    }
});


define(['jquery', 'core/log', 'theme_cass/headroom', 'theme_cass/util', 'theme_cass/personal_menu',
        'theme_cass/cover_image', 'theme_cass/progressbar', 'core/templates', 'core/str', 'theme_cass/TweenMax'],
    function($, log, Headroom, util, personalMenu, coverImage, ProgressBar, templates, str) {

        'use strict';

        /* eslint-disable camelcase */
        M.theme_cass = M.theme_cass || {};
        /* eslint-enable camelcase */

        /**
         * master switch for logging
         * @type {boolean}
         */
        var loggingenabled = false;

        if (!loggingenabled) {
            log.disableAll(true);
        } else {
            log.enableAll(true);
        }

        /**
         * Get all url parameters from href
         * @param {string} href
         * @returns {Array}
         */
        var getURLParams = function(href) {
            // Create temporary array from href.
            var ta = href.split('?');
            if (ta.length < 2) {
                return false; // No url params
            }
            // Get url params full string from href.
            var urlparams = ta[1];

            // Strip out hash component
            urlparams = urlparams.split('#')[0];

            // Get urlparam items.
            var items = urlparams.split('&');

            // Create params array of values hashed by key.
            var params = [];
            for (var i = 0; i < items.length; i++) {
                var item = items[i].split('=');
                var key = item[0];
                var val = item[1];
                params[key] = val;
            }
            return (params);
        };

        /**
         * move PHP errors into header
         *
         * @author Guy Thomas
         * @date 2014-05-19
         */
        var movePHPErrorsToHeader = function() {
            // Remove <br> tags inserted before xdebug-error.
            var xdebugs = $('.xdebug-error');
            if (xdebugs.length) {
                for (var x = 0; x < xdebugs.length; x++) {
                    var el = xdebugs[x];
                    var fontel = el.parentNode;
                    var br = $(fontel).prev('br');
                    $(br).remove();
                }
            }

            // Get messages using the different classes we want to use to target debug messages.
            var msgs = $('.xdebug-error, .php-debug, .debuggingmessage');

            if (msgs.length) {
                // OK we have some errors - lets shove them in the footer.
                $(msgs).addClass('php-debug-footer');
                var errorcont = $('<div id="footer-error-cont"><h3>' +
                    M.util.get_string('debugerrors', 'theme_cass') +
                    '</h3><hr></div>');
                $('#page-footer').append(errorcont);
                $('#footer-error-cont').append(msgs);
                // Add rulers
                $('.php-debug-footer').after($('<hr>'));
                // Lets also add the error class to the header so we know there are some errors.
                $('#mr-nav').addClass('errors-found');
                // Lets add an error link to the header.
                var errorlink = $('<a class="footer-error-link btn btn-danger" href="#footer-error-cont">' +
                    M.util.get_string('problemsfound', 'theme_cass') + ' <span class="badge">' + (msgs.length) + '</span></a>');
                $('#page-header').append(errorlink);
            }
        };

        /**
         * Are we on the course page?
         * Note: This doesn't mean that we are in a course - Being in a course could mean that you are on a module page.
         * This means that you are actually on the course page.
         * @returns {boolean}
         */
        var onCoursePage = function() {
            return $('body').attr('id').indexOf('page-course-view-') === 0;
        };

        /**
         * Apply block hash to form actions etc if necessary.
         */
        /* eslint-disable no-invalid-this */
        var applyBlockHash = function() {
            // Add block hash to add block form.
            if (onCoursePage()) {
                $('.block_adminblock form').each(function() {
                    /* eslint-disable no-invalid-this */
                    $(this).attr('action', $(this).attr('action') + '#coursetools');
                });
            }

            if (location.hash !== '') {
                return;
            }

            var urlParams = getURLParams(location.href);

            // If calendar navigation has been clicked then go back to calendar.
            if (onCoursePage() && typeof (urlParams.time) !== 'undefined') {
                location.hash = 'coursetools';
                if ($('.block_calendar_month')) {
                    util.scrollToElement($('.block_calendar_month'));
                }
            }

            // Form selectors for applying blocks hash.
            var formselectors = [
                'body.path-blocks-collect #notice form'
            ];

            // There is no decent selector for block deletion so we have to add the selector if the current url has the
            // appropriate parameters.
            var paramchecks = ['bui_deleteid', 'bui_editid'];
            for (var p in paramchecks) {
                var param = paramchecks[p];
                if (typeof (urlParams[param]) !== 'undefined') {
                    formselectors.push('#notice form');
                    break;
                }
            }

            // If required, apply #coursetools hash to form action - this is so that on submitting form it returns to course
            // page on blocks tab.
            $(formselectors.join(', ')).each(function() {
                // Only apply the blocks hash if a hash is not already present in url.
                var formurl = $(this).attr('action');
                if (formurl.indexOf('#') === -1
                    && (formurl.indexOf('/course/view.php') > -1)
                ) {
                    $(this).attr('action', $(this).attr('action') + '#coursetools');
                }
            });
        };

        /**
         * Set forum strings because there isn't a decent renderer for mod/forum
         * It would be great if the official moodle forum module used a renderer for all output
         *
         * @author Guy Thomas
         * @date 2014-05-20
         */
        var setForumStrings = function() {
            $('.path-mod-forum tr.discussion td.topic.starter').attr('data-cellname',
                M.util.get_string('forumtopic', 'theme_cass'));
            $('.path-mod-forum tr.discussion td.picture:not(\'.group\')').attr('data-cellname',
                M.util.get_string('forumauthor', 'theme_cass'));
            $('.path-mod-forum tr.discussion td.picture.group').attr('data-cellname',
                M.util.get_string('forumpicturegroup', 'theme_cass'));
            $('.path-mod-forum tr.discussion td.replies').attr('data-cellname',
                M.util.get_string('forumreplies', 'theme_cass'));
            $('.path-mod-forum tr.discussion td.lastpost').attr('data-cellname',
                M.util.get_string('forumlastpost', 'theme_cass'));
        };

        /**
         * Process toc search string - trim, remove case sensitivity etc.
         *
         * @author Guy Thomas
         * @param {string} searchString
         * @returns {string}
         */
        var processSearchString = function(searchString) {
            searchString = searchString.trim().toLowerCase();
            return (searchString);
        };

        /**
         * search course modules
         *
         * @author Stuart Lamour
         * @param {array} dataList
         */
        var tocSearchCourse = function(dataList) {
            // keep search input open
            var i;
            var ua = window.navigator.userAgent;
            if (ua.indexOf('MSIE ') || ua.indexOf('Trident/')) {
                // We have reclone datalist over again for IE, or the same search fails the second time round.
                dataList = $("#toc-searchables").find('li').clone(true);
            }

            // TODO - for 2.7 process search string called too many times?
            var searchString = $("#toc-search-input").val();
            searchString = processSearchString(searchString);

            if (searchString.length === 0) {
                $('#toc-search-results').html('');
                $("#toc-search-input").removeClass('state-active');

            } else {
                $("#toc-search-input").addClass('state-active');
                var matches = [];
                for (i = 0; i < dataList.length; i++) {
                    var dataItem = dataList[i];
                    if (processSearchString($(dataItem).text()).indexOf(searchString) > -1) {
                        matches.push(dataItem);
                    }
                }
                $('#toc-search-results').html(matches);
            }
        };

        /**
         * Apply body classes which could not be set by renderers - e.g. when a notice was outputted.
         * We could do this in plain CSS if there was such a think as a parent selector.
         */
        var bodyClasses = function() {
            var extraclasses = [];
            if ($('#notice.cass-continue-cancel').length) {
                extraclasses.push('hascontinuecancel');
            }
            $('body').addClass(extraclasses.join(' '));
        };

        /**
         * Listen for hash changes / popstates.
         * @param {CourseLibAmd} courseLib
         */
        var listenHashChange = function(courseLib) {
            var lastHash = location.hash;
            $(window).on('popstate hashchange', function(e) {
                var newHash = location.hash;
                log.info('hashchange');
                if (newHash !== lastHash) {
                    if (location.hash === '#primary-nav') {
                        personalMenu.update();
                    } else {
                        $('#page, #moodle-footer, #js-cass-pm-trigger, #logo, .skiplinks').css('display', '');
                        if (onCoursePage()) {
                            log.info('show section', e.target);
                            courseLib.showSection();
                        }
                    }
                }
                lastHash = newHash;
            });
        };

        /**
         * Course footer recent activity dom re-order.
         */
        var recentUpdatesFix = function() {
            $('#cass-course-footer-recent-activity .info').each(function() {
                $(this).appendTo($(this).prev());
            });
            $('#cass-course-footer-recent-activity .head .name').each(function() {
                $(this).prependTo($(this).closest(".head"));
            });
        };

        /**
         * Apply progressbar.js for circular progress displays.
         */
        var progressbarcircle = function() {
            $('.js-progressbar-circle').each(function() {
                var circle = new ProgressBar.Circle(this, {
                    color: 'inherit', // @gray.
                    easing: 'linear',
                    strokeWidth: 6,
                    trailWidth: 3,
                    duration: 1400,
                    text: {
                        value: '0'
                    }
                });

                var value = ($(this).attr('value') / 100);
                var endColor = '#8BC34A'; // green @brand-success.
                if (value === 0 || $(this).attr('value') === '-') {
                  circle.setText('-');
                } else {
                  if ($(this).attr('value') < 50) {
                      endColor = '#FF9800'; // @brand-warning orange.
                  } else {
                      endColor = '#8BC34A'; // green @brand-success.
                  }
                  circle.setText($(this).attr('value') + '<small>%</small>');
                }

                circle.animate(value, {
                    from: {
                        color: '#999' // @gray-light.
                    },
                    to: {
                        color: endColor
                    },
                    step: function(state, circle) {
                        circle.path.setAttribute('stroke', state.color);
                    }
                });
            });
        };

        var scrollIn = function(selector) {
            $(selector).show().animate({right: "20px", opacity: 1}, 1000);
        };

        var scrollOut = function(selector) {
            $(selector).animate({right: "-600px", opacity: 0.5}, 200, function(){ $(selector).hide();});
        };

        var slideNextActivity = function() {
            scrollIn('#activitycompletemodal');
        };

        var popCompletion = function() {
            $(".next_activity_overlay").fadeTo("slow", 0, function() {
                $(this).hide();
            });

            TweenLite.to($("#darkBackground"),  0,      {display:"block"});
            TweenLite.to($("#darkBackground"),  0.3,    {background:"rgba(0,0,0,0.4)", force3D:true});
            TweenLite.to($("#alertBox"),        0,      {left:"calc(50% - 150px)", top:"calc(50% - 150px)", delay:"0.2"});
            TweenLite.to($("#alertBox"),        0,      {display:"block", opacity: 1, delay:"0.2"});
            TweenLite.to($("#alertBox"),        0,      {display:"block", scale:0.2, opacity: 0, delay:"0.2"});
            TweenLite.to($("#alertBox"),        0.3,    {opacity: 1, force3D:true, delay:"0.2"});
            TweenLite.to($("#alertBox"),        0.3,    {scale:1, force3D:true, delay:"0.2"});
            TweenLite.to($("#darkBackground"),  0.2,    {backgroundColor: "rgba(0,0,0,0)", force3D:true, delay:"2"});
            TweenLite.to($("#darkBackground"),  0.2,    {display: "none", force3D:true, delay:"2"});
            TweenLite.to($("#alertBox"),        0.2,    {opacity: 0, display:"none", force3D:true, delay:"2", onComplete:slideNextActivity});

        };

        var addPopCompletion = function() {
            if (typeof M.cassTheme.settings.nextactivitymodaldialogdelay != 'undefined') {
                var manualPopActivities = ['page', 'book', 'wiki', 'feedback'];
                if (manualPopActivities.indexOf(M.cassTheme.mod.modname) == -1) {

                    setTimeout(
                        function() {
                            popCompletion();
                        },

                        M.cassTheme.settings.nextactivitymodaldialogdelay
                    );
                }
            }
        };

        var bindHsuforumCompletion = function() {
            $(".hsuforum-reply").submit(function(event) {
                setTimeout(
                    function() {
                        loadCompletion();
                    }, 3000
                );

            });
        };

        var loadCompletion = function() {
            var type = 'completion';
            var container = $('#completion-region');
            try {

                console.log(M.cfg.wwwroot + '/theme/cass/rest.php?action=get_' + type +'&contextid=' + M.cassTheme.mod.contextid);

                $.ajax({
                    type: "GET",
                    async:  true,
                    url: M.cfg.wwwroot + '/theme/cass/rest.php?action=get_' + type +'&contextid=' + M.cassTheme.mod.contextid, // M.cfg.context,
                    success: function(data) {
                        $('.completion-region').attr('data-content-loaded', '1');
                        $('.completion-region').html(data.html);
                        addPopCompletion();
                    }
                });
            } catch(err) {
                console.log(err);
            }

            //bind to the control again
            bindHsuforumCompletion();
        };

        /**
         * Add listeners.
         *
         * just a wrapper for various snippets that add listeners
         */
        var addListeners = function() {
            var selectors = [
                '.chapters a',
                '.section_footer a',
                ' #toc-search-results a'
            ];

            $(document).on('click', selectors.join(', '), function(e) {
                var href = this.getAttribute('href');
                if (window.history && window.history.pushState) {
                    history.pushState(null, null, href);
                    // Force hashchange fix for FF & IE9.
                    $(window).trigger('hashchange');
                    // Prevent scrolling to section.
                    e.preventDefault();
                } else {
                    location.hash = href;
                }
            });

            // Alternate up/down chevron direction based on collapsable bootstrap elements
            $('[data-toggle="collapse"]').on('click', function() {
                $(this).find("span.glyphicon").toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
            });

            //listener for completion button click
            //#completeclick
            $('.completeclick').on('click', function(e) {
                popCompletion();
                e.preventDefault();
            });

            //listener for completion modal close
            //#completeclick
            $('.completeclose').on('click', function(e) {
                scrollOut('#activitycompletemodal');
                e.preventDefault();
            });

            // Use headroom.js ? Check the theme setting
            // settings property may not exist during lazy init
            if (typeof M.cassTheme.settings != 'undefined' && M.cassTheme.settings != null) {
                if (typeof M.cassTheme.settings.fixheadertotopofpage === 'undefined' ||
                    M.cassTheme.settings.fixheadertotopofpage == "0") {

                    // Show fixed header on scroll down
                    // using headroom js - http://wicky.nillia.ms/headroom.js/
                    var myElement = document.querySelector("#mr-nav");
                    // Construct an instance of Headroom, passing the element.
                    var headroom = new Headroom(myElement, {
                        "tolerance": 5,
                        "offset": 100,
                        "classes": {
                            // when element is initialised
                            initial: "headroom",
                            // when scrolling up
                            pinned: "headroom--pinned",
                            // when scrolling down
                            unpinned: "headroom--unpinned",
                            // when above offset
                            top: "headroom--top",
                            // when below offset
                            notTop: "headroom--not-top"
                        }
                    });
                    // When not signed in always show mr-nav?
                    if (!$('.notloggedin').length) {
                        headroom.init();
                    }

                }
            }


            //Attach specific events for specific mod pages
            if (typeof M.cassTheme.mod != 'undefined' && M.cassTheme.mod != null) {
                if (M.cassTheme.mod.modname == 'hsuforum') {

                    //posting a reply inline via hsuforum:
                    // - Uses a YUI ajax call
                    // - replaces the article node tagged with the hsuforum-post-target class
                    // - this node is a child of the mod-hsuforum-posts-container div
                    // - this node can not be easily targetted by an event listener for a change event
                    // - can be targeted

                    // Unforunately can not easily attach an event to the hsuform-post-target div
                    // bind this function to the reply form submit that adds a timer
                    // to lazy check completion via ajax

                    bindHsuforumCompletion();
                }
            }

            // Listener for toc search.
            var dataList = $("#toc-searchables").find('li').clone(true);
            $('#course-toc').on('keyup', '#toc-search-input', function() {
                tocSearchCourse(dataList);
            });

            // Handle keyboard navigation of search items.
            $('#course-toc').on('keydown', '#toc-search-input', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 9) {
                    // 9 tab
                    // 13 enter
                    // 40 down arrow
                    // Register listener for exiting search result.
                    $('#toc-search-results a').last().blur(function() {
                        $(this).off('blur'); // unregister listener
                        $("#toc-search-input").val('');
                        $('#toc-search-results').html('');
                        $("#toc-search-input").removeClass('state-active');
                    });

                }
            });

            $('#course-toc').on("click", '#toc-search-results a', function() {
                $("#toc-search-input").val('');
                $('#toc-search-results').html('');
                $("#toc-search-input").removeClass('state-active');
            });

            /**
             * When the document is clicked, if the closest object that was clicked was not the search input then close
             * the search results.
             * Note that this is triggered also when you click on a search result as the results should no longer be
             * required at that point.
             */
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#toc-search-input').length) {
                    $("#toc-search-input").val('');
                    $('#toc-search-results').html('');
                    $("#toc-search-input").removeClass('state-active');
                }
            });

            // Onclick for toggle of state-visible of admin block and mobile menu.
            $(document).on("click", "#admin-menu-trigger, #toc-mobile-menu-toggle", function(e) {
                var href = this.getAttribute('href');
                // Make this only happen for settings button
                if (this.getAttribute('id') == 'admin-menu-trigger') {
                    $(this).toggleClass('active');
                    $('#page').toggleClass('offcanvas');
                }
                $(href).attr('tabindex', '0');
                $(href).toggleClass('state-visible').focus();
                e.preventDefault();
            });

            // Mobile menu button.
            $(document).on("click", "#course-toc.state-visible a", function() {
                $('#course-toc').removeClass('state-visible');
            });

            $(document).on("click", ".news-article .toggle", function(e) {
                var $news = $(this).closest('.news-article');
                util.scrollToElement($news);
                $(".news-article").not($news).removeClass('state-expanded');
                $(".news-article-message").css('display', 'none');

                $news.toggleClass('state-expanded');
                $('.state-expanded').find('.news-article-message').slideDown("fast", function() {
                    // Animation complete.
                    if ($news.is('.state-expanded')) {
                        $news.find('.news-article-message').focus();
                        $news.attr('aria-expanded', 'true');
                    } else {
                        $news.focus();
                        $news.attr('aria-expanded', 'false');
                    }
                    $(document).trigger('cassContentRevealed');
                });
                e.preventDefault();
            });

            // Bootstrap js elements

            // Iniitalise core bootsrtap tooltip js
            $(function() {
                var supportsTouch = false;
                if ('ontouchstart' in window) {
                    // iOS & android
                    supportsTouch = true;
                } else if (window.navigator.msPointerEnabled) {
                    // Win8
                    supportsTouch = true;
                }
                if (!supportsTouch) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        };

        /**
         * AMD return object.
         */
        return {
            /**
             * Cass initialise function.
             * @param {object} courseConfig
             * @param {bool} pageHasCourseContent
             * @param {int} siteMaxBytes
             * @param {bool} forcePassChange
             * @param {bool} messageBadgeCountEnabled
             * @param {int} userId
             * @param {bool} sitePolicyAcceptReqd
             * @param {bool} inAlternativeRole
             */
            cassInit: function(courseConfig, pageHasCourseContent, siteMaxBytes, forcePassChange,
                               messageBadgeCountEnabled, userId, sitePolicyAcceptReqd, inAlternativeRole, themeSettings, mod) {

                // Set up.
                M.cfg.context = courseConfig.contextid;

                // To hold theme settings required by javascript
                // 1. Whether to use headroom
                // 2. Completion modal dialog delay
                //themeSettings

                // To hold various pieces of mod information required by javascript
                // 1. Modname. (Different mods have different options for completion)
                //mod

                M.cassTheme = {
                    forcePassChange: forcePassChange,
                    settings: themeSettings,
                    mod: mod
                };

                // General AMD modules.
                personalMenu.init(sitePolicyAcceptReqd);

                // Course related AMD modules (note, site page can technically have course content too).
                if (pageHasCourseContent) {
                    require(
                        [
                            'theme_cass/course-lazy'
                        ], function(CourseLibAmd) {
                            // Instantiate course lib.
                            var courseLib = new CourseLibAmd(courseConfig);

                            // Hash change listener goes here because it requires courseLib.
                            listenHashChange(courseLib);
                        }
                    );
                }

                // When document has loaded.
                /* eslint-disable complexity */
                $(document).ready(function() {
                    movePHPErrorsToHeader(); // boring
                    setForumStrings(); // whatever
                    addListeners(); // essential
                    applyBlockHash(); // change location hash if necessary
                    bodyClasses(); // add body classes

                    // Add a class to the body to show js is loaded.
                    $('body').addClass('cass-js-loaded');
                    // Apply progressbar.js for circluar progress display.
                    progressbarcircle();
                    // Course footer recent updates dom fixes.
                    recentUpdatesFix();

                    if ($('body').hasClass('pagelayout-course') || $('body').hasClass('pagelayout-frontpage')) {
                        coverImage.courseImage(courseConfig.shortname, siteMaxBytes);
                    } else if ($('body').hasClass('pagelayout-coursecategory')) {
                        if (courseConfig.categoryid) {
                            coverImage.categoryImage(courseConfig.categoryid, siteMaxBytes);
                        }
                    }

                    // Allow deeplinking to bs tabs on cass settings page.
                    if ($('#page-admin-setting-themesettingcass').length) {
                        var tabHash = location.hash;
                        // Check link is to a tab hash.
                        if (tabHash && $('.nav-link[href="' + tabHash + '"]').length) {
                            $('.nav-link[href="' + tabHash + '"]').tab('show');
                            $(window).scrollTop(0);
                        }
                    }

                    if ($('body').hasClass('cass-pm-open')) {
                        personalMenu.update();
                    }

                    // SHAME - make section name creation mandatory
                    if ($('#page-course-editsection.format-topics').length) {
                        var usedefaultname = document.getElementById('id_name_customize'),
                            sname = document.getElementById('id_name_value');
                        usedefaultname.value = '1';
                        usedefaultname.checked = true;
                        sname.required = "required";
                        $(usedefaultname).parent().css('display', 'none');

                        // Enable the cancel button.
                        $('#id_cancel').on('click', function() {
                            $(sname).removeAttr('required');
                            return true;
                        });
                    }

                    // Book mod print button, only show if print link already present.
                    if ($('#page-mod-book-view a[href*="mod/book/tool/print/index.php"]').length) {
                        var urlParams = getURLParams(location.href);
                        if (urlParams) {
                            $('[data-block="_fake"]').append('<p>' +
                                '<hr><a target="_blank" href="/mod/book/tool/print/index.php?id=' + urlParams.id + '">' +
                                M.util.get_string('printbook', 'booktool_print') +
                                '</a></p>');
                        }
                    }

                    var modSettingsIdRe = /^page-mod-.*-mod$/; // e.g. #page-mod-resource-mod or #page-mod-forum-mod
                    var onModSettings = modSettingsIdRe.test($('body').attr('id')) && location.href.indexOf("modedit") > -1;
                    if (!onModSettings) {
                        modSettingsIdRe = /^page-mod-.*-general$/;
                        onModSettings = modSettingsIdRe.test($('body').attr('id')) && location.href.indexOf("modedit") > -1;
                    }
                    var onCourseSettings = $('body').attr('id') === 'page-course-edit';
                    var onSectionSettings = $('body').attr('id') === 'page-course-editsection';
                    var pageBlacklist = ['page-mod-hvp-mod'];
                    var pageNotInBlacklist = pageBlacklist.indexOf($('body').attr('id')) === -1;

                    if ((onModSettings || onCourseSettings || onSectionSettings) && pageNotInBlacklist) {
                        // Wrap advanced options in a div
                        var vital = [
                            ':first',
                            '#page-course-edit #id_descriptionhdr',
                            '#id_contentsection',
                            '#id_general + #id_general', // Turnitin duplicate ID bug.
                            '#id_content',
                            '#page-mod-choice-mod #id_optionhdr',
                            '#page-mod-workshop-mod #id_gradingsettings',
                            '#page-mod-choicegroup-mod #id_miscellaneoussettingshdr',
                            '#page-mod-choicegroup-mod #id_groups',
                            '#page-mod-scorm-mod #id_packagehdr'
                        ];
                        vital = vital.join();

                        $('#mform1 > fieldset').not(vital).wrapAll('<div class="cass-form-advanced col-md-4" />');

                        // Add expand all to advanced column.
                        $(".cass-form-advanced").append($(".collapsible-actions"));
                        // Add collapsed to all fieldsets in advanced, except on course edit page.
                        if (!$('#page-course-edit').length) {
                            $(".cass-form-advanced fieldset").addClass('collapsed');
                        }

                        // Sanitize required input into a single fieldset
                        var mainForm = $("#mform1 fieldset:first");
                        var appendTo = $("#mform1 fieldset:first .fcontainer");

                        var required = $("#mform1 > fieldset").not("#mform1 > fieldset:first");
                        for (var i = 0; i < required.length; i++) {
                            var content = $(required[i]).find('.fcontainer');
                            $(appendTo).append(content);
                            $(required[i]).remove();
                        }
                        $(mainForm).wrap('<div class="cass-form-required col-md-8" />');

                        var description = $("#mform1 fieldset:first .fitem_feditor:not(.required)");

                        if (onModSettings && description) {
                            var editingassignment = $('body').attr('id') == 'page-mod-assign-mod';
                            var editingchoice = $('body').attr('id') == 'page-mod-choice-mod';
                            var editingturnitin = $('body').attr('id') == 'page-mod-turnitintool-mod';
                            var editingworkshop = $('body').attr('id') == 'page-mod-workshop-mod';
                            if (!editingchoice && !editingassignment && !editingturnitin && !editingworkshop) {
                                $(appendTo).append(description);
                                $(appendTo).append($('#fitem_id_showdescription'));
                            }
                        }

                        // Resources - put description in common mod settings.
                        description = $("#page-mod-resource-mod [data-fieldtype='editor']").closest('.form-group');
                        var showdescription = $("#page-mod-resource-mod [id='id_showdescription']").closest('.form-group');
                        $("#page-mod-resource-mod .cass-form-advanced #id_modstandardelshdr .fcontainer").append(description);
                        $("#page-mod-resource-mod .cass-form-advanced #id_modstandardelshdr .fcontainer").append(showdescription);

                        // Assignment - put due date in required, and attatchments in common settings.
                        var filemanager = $("#page-mod-assign-mod [data-fieldtype='filemanager']").closest('.form-group');
                        var duedate = $("#page-mod-assign-mod [for='id_duedate']").closest('.form-group');
                        $("#page-mod-assign-mod .cass-form-advanced #id_modstandardelshdr .fcontainer").append(filemanager);
                        $("#page-mod-assign-mod .cass-form-required .fcontainer").append(duedate);

                        // Move availablity at the top of advanced settings.
                        var availablity = $('#id_visible').closest('.form-group').addClass('cass-form-visibility');
                        var label = $(availablity).find('label');
                        var select = $(availablity).find('select');
                        $(label).insertBefore(select);

                        // SHAME - rewrite visibility form lang string to be more user friendly.
                        $(label).text(M.util.get_string('visibility', 'theme_cass') + ' ');

                        if ($("#page-course-edit").length) {
                            // We are in course editing form.
                            // Removing the "Show all sections in one page" from the course format form.
                            str.get_strings([
                                {key: 'showallsectionsdisabled', component: 'theme_cass'},
                                {key: 'disabled', component: 'theme_cass'}
                            ]).then(function(strings) {
                                var strMessage = strings[0], strDisabled = strings[1];
                                templates.render('theme_cass/form_alert', {
                                    type: 'warning',
                                    classes: '',
                                    message: strMessage
                                }).then(function(html) {
                                    var op0 = $('[name="coursedisplay"] > option[value="0"]');
                                    var op1 = $('[name="coursedisplay"] > option[value="1"]');
                                    var selectNode =  $('[name="coursedisplay"]');
                                    // Disable option 0
                                    op0.attr('disabled','disabled');
                                    // Add "(Disabled)" to option text
                                    op0.append(' (' + strDisabled + ')');
                                    // Remove selection attribute
                                    op0.removeAttr("selected");
                                    // Select option 1
                                    op1.attr('selected','selected');
                                    // Add warning
                                    selectNode.parent().append(html);
                                });
                            });
                        }

                        $('.cass-form-advanced').prepend(availablity);

                        // Add save buttons.
                        var savebuttons = $("#mform1 > .form-group:last");
                        $(mainForm).append(savebuttons);

                        // Expand collapsed fieldsets when editing a mod that has errors in it.
                        var errorElements = $('.form-group.has-danger');
                        if (onModSettings && errorElements.length) {
                            errorElements.closest('.collapsible').removeClass('collapsed');
                        }
                    }

                    // Conversation counter for user badge.
                    if (messageBadgeCountEnabled) {
                        require(
                            [
                                'theme_cass/conversation_badge_count-lazy'
                            ], function(conversationBadgeCount) {
                                conversationBadgeCount.init(userId);
                            }
                        );
                    }

                    // Listen to cover image label key press for accessible usage.
                    var focustarget = $('#cass-coverimagecontrol label');
                    if (focustarget && focustarget.length) {
                        focustarget.keypress(function (e) {
                            if (e.which === 13) {
                                $('#cass-coverfiles').trigger('click');
                            }
                        });
                    }

                    // Review if settings block is missing.
                    if (!$('.block_settings').length) {
                        // Hide admin icon.
                        $('#admin-menu-trigger').hide();
                        if (inAlternativeRole) {
                            // Handle possible alternative role.
                            require(
                                [
                                    'theme_cass/alternative_role_handler-lazy'
                                ], function(alternativeRoleHandler) {
                                    alternativeRoleHandler.init(courseConfig.id);
                                }
                            );
                        }
                    }
                });
            },

            addPopCompletion: function() {
                addPopCompletion();
            },

            popCompletion: function() {
                popCompletion();
            }
        };
    }
);
