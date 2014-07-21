/**
 * Angular JS module used to shoe HTML editor and toolbar on a page
 * @require:
 *  TinyMce
 */
var rcmHtmlEditorState = {
    isEditing: false,
    toolbarLoading: true,
    showToolbar: false
};
angular.module('RcmHtmlEditor', [])
    .factory(
    'rcmHtmlEditorConfig',
    function () {
        self = this;

        self.baseUrl = "/"; //"<?php echo $baseUrl; ?>";
        self.fixed_toolbar_container = '#externalToolbarWrapper';
        self.toolbar_container_prefix = '#htmlEditorToolbar-'

        self.htmlEditorOptions = {
            defaults: {
                optionsName: 'defaults',
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',

                inline: true,
                fixed_toolbar_container: self.fixed_toolbar_container,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, link, paste, spellchecker, template, table",
                relative_urls: true,
                document_base_url: self.baseUrl,
                statusbar: false,

                toolbar: [
                    "code | undo redo | spellchecker | styleselect | " +
                        "bold italic underline strikethrough subscript superscript removeformat | " +
                        "alignleft aligncenter alignright alignjustify | " +
                        "bullist numlist outdent indent | cut copy paste pastetext | ",
                    "image table hr charmap template | link unlink anchor"
                ]
            },
            text: {
                optionsName: 'text',
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',

                inline: true,
                fixed_toolbar_container: self.fixed_toolbar_container,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, link, paste, spellchecker, template, table",
                relative_urls: true,
                document_base_url: self.baseUrl,
                statusbar: false,

                toolbar: [
                    "code | undo redo | spellchecker | " +
                        "bold italic underline strikethrough subscript superscript removeformat | " +
                        "outdent indent | cut copy paste pastetext | ",
                    "image charmap template | link unlink anchor"
                ]
            },
            simpleText: {
                optionsName: 'simpleText',
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',

                inline: true,
                fixed_toolbar_container: self.fixed_toolbar_container,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, link, paste, spellchecker, template, table",
                relative_urls: true,
                document_base_url: self.baseUrl,
                statusbar: false,

                toolbar: [
                    "code | " +
                        "bold italic underline strikethrough subscript superscript removeformat | " +
                        "link unlink anchor"
                ]
            }
        }

        return self;
    }
)
    .value('toolbar_container', '#externalToolbarWrapper')
    .factory(
        'htmlEditorOptions',
        [
            'rcmHtmlEditorConfig',
            function (rcmHtmlEditorConfig) {

                var self = this;

                // get options based on the config settings
                self.getHtmlOptions = function (type) {

                    if (!type) {

                        return rcmHtmlEditorConfig.htmlEditorOptions.defaults;
                    }

                    if (rcmHtmlEditorConfig.htmlEditorOptions[type]) {

                        return rcmHtmlEditorConfig.htmlEditorOptions[type]
                    }

                    return rcmHtmlEditorConfig.htmlEditorOptions.defaults;
                }

                self.getConfigSetup = function (attrs) {
                }

                // build settings based on the attrs
                self.buildHtmlOptions = function (scope, attrs) {

                    var config = null;
                    var options = {};
                    var settings = {};
                    try {
                        var config = scope.$eval(attrs.htmlEditorOptions);
                    } catch (e) {
                    }

                    if (typeof config !== 'object') {

                        config = {};
                    }

                    options = angular.copy(self.getHtmlOptions(attrs.htmlEditorType));

                    settings = angular.extend(options, config); // copy(options);

                    settings.mode = 'exact';
                    settings.elements = attrs.id;

                    // set some overrides based on attr html-editor-attached-toolbar
                    if (typeof attrs.htmlEditorAttachedToolbar !== 'undefined') {
                        settings.inline = true;
                        settings.fixed_toolbar_container = rcmHtmlEditorConfig.toolbar_container_prefix + attrs.id;

                        // @todo NOT SUPPORTED: attr html-editor-show-hide-toolbar
                        //if (typeof attrs.htmlEditorShowHideToolbar !== 'undefined') {
                        //    settings.show_hide_toolbar = true;
                        //}
                    }

                    // set some overrides based on attr html-editor-base-url
                    if (attrs.htmlEditorBaseUrl) {
                        settings.baseUrl = attrs.htmlEditorBaseUrl;
                    }

                    if (attrs.htmlEditorSize) {
                        settings.toolbar_items_size = attrs.htmlEditorSize; // 'small'
                    }


                    return settings
                }

                return self;
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
            'guid',
            'htmlEditorOptions',
            function (guid, htmlEditorOptions) {

                return function () {

                    return function (scope, elm, attrs, ngModel) {
                        var settings = {};
                        var tinyInstance;
                        var tagName = elm[0].tagName;
                        var isFormControl = function () {
                            if (tagName == "TEXTAREA") {

                                return true;
                            }

                            return false;
                        }

                        var getElmValue = function () {

                            if (isFormControl()) {

                                return elm.val();
                            }

                            return elm.html();
                        }

                        var updateView = function () {

                            ngModel.$setViewValue(getElmValue());
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

                        // get settings from attr or config
                        settings = htmlEditorOptions.buildHtmlOptions(scope, attrs);

                        settings.setup = function (ed) {
                            var args;
                            ed.on('init', function (args) {

                                ngModel.$render();
                                ngModel.$setPristine();

                                // will show default toolbar on init
                                rcmHtmlEditorState.showToolbar = false;
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
                                rcmHtmlEditorState.showToolbar = false;

                                if (elm.blur) {
                                    elm.blur();
                                }
                                updateView();
                            });
                            ed.on('focus', function (e) {

                                rcmHtmlEditorState.isEditing = true;
                                rcmHtmlEditorState.showToolbar = true;

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
                            // This might be needed if setup can be passed in
//                            if (settings) {
//                                settings(ed);
//                            }
                        };

                        setTimeout(function () {
                            tinymce.init(settings);
                        });

                        ngModel.$render = function () {

                            if (!tinyInstance) {
                                tinyInstance = tinymce.get(attrs.id);
                            }
                            if (tinyInstance) {
                                tinyInstance.setContent(ngModel.$viewValue || getElmValue());
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
        'rcmHtmlEdit',
        [
            'rcmHtmlEdit',
            function (rcmHtmlEdit) {

                return {
                    priority: 10,
                    require: 'ngModel',
                    link: rcmHtmlEdit()
                }
            }
        ]
    )
    /* @deprecated */
    .directive(
        'richedit',
        [
            'rcmHtmlEdit',
            function (rcmHtmlEdit) {

                return {
                    priority: 10,
                    require: 'ngModel',
                    link: rcmHtmlEdit()
                }
            }
        ]
    )
    /* @deprecated */
    .directive(
        'textedit',
        [
            'rcmHtmlEdit',
            function (rcmHtmlEdit) {

                return {
                    priority: 10,
                    require: 'ngModel',
                    link: rcmHtmlEdit()
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
//                'showToolbar:{{rcmHtmlEditorState.toolbarLoading | json}} -- showToolbar:{{rcmHtmlEditorState.showToolbar | json}} ' +
                '<div class="htmlEditorToolbar" ng-cloak ng-hide="rcmHtmlEditorState.toolbarLoading">' +
                ' <div ng-hide="rcmHtmlEditorState.showToolbar">' +
                '  <div class="mce-tinymce mce-tinymce-inline mce-container mce-panel" role="presentation" style="border-width: 1px; left: 0px; top: 0px; width: 100%; height: 34px;">' +
                '   <div class="mce-container-body mce-abs-layout">' +
                '    <div class="mce-toolbar-grp mce-container mce-panel mce-first mce-last">' +
                '     <div class="mce-container-body mce-stack-layout">' +
                '      <div class="mce-container mce-toolbar mce-first mce-last mce-stack-layout-item">' +
                '       <div class="mce-container-body mce-flow-layout">' +
                '        <div class="mce-container mce-first mce-flow-layout-item mce-btn-group">' +
                '         <div>' +
                '          <div class="mce-widget mce-btn mce-first mce-last mce-disabled" tabindex="-1" aria-labelledby="mceu_0" role="button" aria-label="Source code">' +
                '           <button role="presentation" type="button" tabindex="-1" disabled="disabled"><i class="mce-ico mce-i-code"></i></button>' +
//              '            <button role="presentation" type="button" disabled tabindex="-1">Select text to show controls</button>' +
                '          </div>' +
                '         </div>' +
                '        </div>' +
                '       </div>' +
                '      </div>' +
                '     </div>' +
                '    </div>' +
                '   </div>' +
                '  </div>' +
                ' </div>' +
                ' <div id="externalToolbarWrapper"></div>' +
                '</div>'
        };
    })
    /* @EXAMPLE */
    .controller(
        'RcmHtmlEditorTestController',
        [
            '$scope',
            function ($scope) {

                $scope.myModel = {
                    a: "My Test Text",
                    b: "Other Test Text",
                    c: "More Test Text",
                    d: "More Great Test Text",
                    e: "tttttttttt"
                }

                // EXAMPLE of adding custom options using attribute
                $scope.tinymceOptions = {
                    optionsName: 'fromScope',
                    force_br_newlines: false,
                    force_p_newlines: false,
                    forced_root_block: '',

                    inline: true,
                    fixed_toolbar_container: '#externalToolbarWrapper',

                    menubar: false,
                    plugins: "anchor, charmap, code, image, link, paste, spellchecker, template",
                    relative_urls: true,
                    document_base_url: '/',
                    statusbar: false,

                    toolbar: [
                        // "code | undo redo | spellchecker | " +
                        "bold italic underline strikethrough subscript superscript removeformat | "
                        // "outdent indent | cut copy paste pastetext | " +
                        // "image charmap template | link unlink anchor"
                    ]
                };
            }
        ]
    );