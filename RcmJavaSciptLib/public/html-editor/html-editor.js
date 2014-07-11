/**
 * Angular JS module used to shoe HTML editor and toolbar on a page
 * @require:
 *  TinyMce
 */
var rcmHtmlEditorState = {
    isEditing: false,
    toolbarLoading: true
};
angular.module('RcmHtmlEditor', ['ui.tinymce'])
    .factory(
    'rcmHtmlEditorConfig',
    function () {
        self = this;

        self.baseUrl = "/"; //"<?php echo $baseUrl; ?>";

        return self;
    }
)
    .value('toolbar_container', '#externalToolbarWrapper')
    .factory(
        'htmlEditorDefaultOptions',
        [
            'rcmHtmlEditorConfig',
            'toolbar_container',
            function (rcmHtmlEditorConfig, toolbar_container) {
                var tinymceOptions = {
                    force_br_newlines: false,
                    force_p_newlines: false,
                    forced_root_block: '',

                    inline: true,
                    fixed_toolbar_container: toolbar_container,

                    menubar: false,
                    plugins: "anchor, charmap, code, hr, image, link, paste, spellchecker, template, table",
                    relative_urls: true,
                    document_base_url: rcmHtmlEditorConfig.baseUrl,
                    statusbar: false,
                    //selector: "[data-richEdit]", //"div.editor",

                    //setup: function (theEditor) {
                    /* hide/show toolbar on focus div.mce-toolbar-grp *
                     theEditor.on('focus', function () {
                     $('#externalToolbarWrapper').find("div.mce-panel").show();
                     });
                     theEditor.on('blur', function () {
                     $('#externalToolbarWrapper').find("div.mce-panel").show();
                     });
                     theEditor.on("init", function() {
                     $('#externalToolbarWrapper').find("div.mce-panel").show();
                     });
                     //*/
                    //},

                    toolbar: [
                        "code | undo redo | spellchecker | styleselect | " +
                            "bold italic underline strikethrough subscript superscript removeformat | " +
                            "alignleft aligncenter alignright alignjustify | " +
                            "bullist numlist outdent indent | cut copy paste pastetext | " +
                            "image table hr charmap template | link unlink anchor"
                    ]
                };

                return tinymceOptions;
            }
        ]
    )
    .factory(
        'htmlEditorTextOptions',
        [
            'rcmHtmlEditorConfig',
            'toolbar_container',
            function (rcmHtmlEditorConfig, toolbar_container) {
                var tinymceOptions = {
                    force_br_newlines: false,
                    force_p_newlines: false,
                    forced_root_block: '',

                    inline: true,
                    fixed_toolbar_container: toolbar_container,

                    menubar: false,
                    plugins: "anchor, charmap, code, image, link, paste, spellchecker, template",
                    relative_urls: true,
                    document_base_url: rcmHtmlEditorConfig.baseUrl,
                    statusbar: false,
                    //selector: "[data-textEdit]", //"div.editor",

                    //setup: function (theEditor) {
                    //},

                    toolbar: [
                        "code | undo redo | spellchecker | " +
                            "bold italic underline strikethrough subscript superscript removeformat | " +
                            "outdent indent | cut copy paste pastetext | " +
                            "image charmap template | link unlink anchor"
                    ]
                };

                return tinymceOptions;
            }
        ]
    )
    .factory(
        'guid',
        [
            function () {

                var guid = (function () {
                    function s4() {
                        return Math.floor((1 + Math.random()) * 0x10000)
                            .toString(16)
                            .substring(1);
                    }

                    return function () {
                        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                            s4() + '-' + s4() + s4() + s4();
                    };
                })();

                return guid;
            }
        ]

    )
    .factory(
        'rcmHtmlEdit',
        [
            '$log',
            '$cacheFactory',
            'guid',
            function ($log, $cacheFactory, guid) {

                return function (htmlEditorOptions) {

                    return function (scope, elm, attrs, ngModel) {
                        var expression;
                        var options;
                        var tinyInstance;
                        var updateView = function () {
                            ngModel.$setViewValue(elm.html());
                            if (!scope.$root.$$phase) {
                                scope.$apply();
                            }
                        };

                        // this is to hide the default toolbar before init
                        rcmHtmlEditorState.toolbarLoading = true;

                        // generate an ID if not present
                        if (!attrs.id) {
                            attrs.$set('id', guid());
                        }

                        if (attrs.htlmEditorOptions) {
                            expression = scope.$eval(attrs.htlmEditorOptions);
                        } else {
                            expression = {};
                        }

                        // make config'ed setup method available
                        if (expression.setup) {
                            var configSetup = expression.setup;
                            delete expression.setup;
                        }

                        options = {
                            // Update model when calling setContent (such as from the source editor popup)
                            setup: function (ed) {
                                var args;
                                ed.on('init', function (args) {
                                    $log.log('init');
                                    ngModel.$render();
                                    ngModel.$setPristine();
                                    // will show default toolbar on init
                                    rcmHtmlEditorState.toolbarLoading = false;
                                    if (!scope.$root.$$phase) {
                                        scope.$apply();
                                    }
                                });
                                // Update model on button click
                                ed.on('ExecCommand', function (e) {

                                    ed.save();
                                    updateView();
                                });
                                // Update model on keypress
                                ed.on('KeyUp', function (e) {

                                    ed.save();
                                    updateView();
                                });
                                // Update model on change, i.e. copy/pasted text, plugins altering content
                                ed.on('SetContent', function (e) {

                                    if (!e.initial && ngModel.$viewValue !== e.content) {
                                        ed.save();
                                        updateView();
                                    }
                                });
                                ed.on('blur', function (e) {

                                    rcmHtmlEditorState.isEditing = false;
                                    if (elm.blur) {
                                        elm.blur();
                                    }
                                    updateView();
                                });
                                ed.on('focus', function (e) {

                                    rcmHtmlEditorState.isEditing = true;
                                    if (elm.focus) {
                                        elm.focus();
                                    }
                                    updateView();
                                });
                                // Update model when an object has been resized (table, image)
                                ed.on('ObjectResized', function (e) {

                                    ed.save();
                                    updateView();
                                });
                                if (configSetup) {
                                    configSetup(ed);
                                }
                            },
                            mode: 'exact',
                            elements: attrs.id
                        };
                        // extend options with initial htmlEditorOptions and options from directive attribute value
                        angular.extend(options, htmlEditorOptions, expression);

                        setTimeout(function () {
                            tinymce.init(options);
                        });

                        ngModel.$render = function () {
                            if (!tinyInstance) {
                                tinyInstance = tinymce.get(attrs.id);
                            }
                            if (tinyInstance) {
                                tinyInstance.setContent(ngModel.$viewValue || '');
                            }
                        };

                        scope.$on('$destroy', function () {
                            if (!tinyInstance) {
                                tinyInstance = tinymce.get(attrs.id);
                            }
                            if (tinyInstance) {
                                tinyInstance.remove();
                                tinyInstance = null;
                            }
                        });
                    }


                }
            }
        ]
    )
    .directive(
        'richedit',
        [
            'rcmHtmlEdit',
            'htmlEditorDefaultOptions',
            function (rcmHtmlEdit, htmlEditorDefaultOptions) {

                return {
                    priority: 10,
                    require: 'ngModel',
                    link: rcmHtmlEdit(htmlEditorDefaultOptions)
                }
            }
        ]
    )
    .directive(
        'textedit',
        [
            'rcmHtmlEdit',
            'htmlEditorTextOptions',
            function (rcmHtmlEdit, htmlEditorTextOptions) {

                return {
                    priority: 10,
                    require: 'ngModel',
                    link: rcmHtmlEdit(htmlEditorTextOptions)
                }
            }
        ]
    )
    .directive('htmlEditorToolbar', function () {
        /*
         * Example:
         * <div html-editor-toolbar></div>
         */

        var thislink = function (scope, element, attrs, htmlEditorState) {

            var self = this;

            scope.rcmHtmlEditorState = rcmHtmlEditorState;
        }

        return {
            link: thislink,
            restrict: 'A',
            template: '' +
//                'ToolbarLoading:{{rcmHtmlEditorState.toolbarLoading | json}} -- isEditing:{{rcmHtmlEditorState.isEditing | json}} ' +
                '<div class="htmlEditorToolbar" ng-cloak ng-hide="rcmHtmlEditorState.toolbarLoading">' +
                ' <div ng-hide="rcmHtmlEditorState.isEditing">' +
                '  <div id="externalToolbarNone" class="mce-tinymce mce-tinymce-inline mce-container mce-panel" role="presentation" style="border-width: 1px; left: 0px; top: 0px; width: 100%; height: 34px;">' +
                '    <div class="mce-container-body mce-abs-layout">' +
                '     <div class="mce-toolbar-grp mce-container mce-panel mce-first mce-last">' +
                '      <div class="mce-container-body mce-stack-layout">' +
                '       <div class="mce-container mce-toolbar mce-first mce-last mce-stack-layout-item">' +
                '        <div class="mce-container-body mce-flow-layout">' +
                '         <div class="mce-container mce-first mce-flow-layout-item mce-btn-group">' +
                '          <div id="mceu_35-body">' +
                '           <div id="mceu_0" class="mce-widget mce-btn mce-first mce-last" tabindex="-1" aria-labelledby="mceu_0" role="button" aria-label="Source code">' +
                '            <button role="presentation" type="button" tabindex="-1" disabled="disabled"><i class="mce-ico mce-i-code"></i></button>' +
//                '            <button role="presentation" type="button" disabled tabindex="-1">Select text to show controls</button>' +
                '           </div>' +
                '          </div>' +
                '         </div>' +
                '        </div>' +
                '       </div>' +
                '      </div>' +
                '     </div>' +
                '    </div>' +
                '   </div>' +
                '  </div>' +
                ' <div id="externalToolbarWrapper"></div>' +
                '</div>'
        };
    })
    .controller(
        'RcmHtmlEditorTestController',
        [
            '$scope',
            function ($scope) {

                $scope.myModel = {
                    a: "My Test Text",
                    b: "Other Test Text",
                    c: "More Test Text"
                }
            }
        ]
    );