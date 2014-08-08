/**
 * Angular JS module used to shoe HTML editor and toolbar on a page
 * @require:
 *  AngularJS
 *  TinyMce
 */
angular.module('RcmHtmlEditor', [])

    .factory(
    'rcmHtmlEditorConfig',
    function () {

        var self = this;

        self.language = 'en';
        self.baseUrl = "/"; //"<?php echo $baseUrl; ?>";
        self.fixed_toolbar_container = '#externalToolbarWrapper';
        self.toolbar_container_prefix = '#htmlEditorToolbar-';

        self.htmlEditorOptions = {
            defaults: {
                optionsName: 'defaults',
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',

                inline: true,
                fixed_toolbar_container: self.fixed_toolbar_container,
                language: self.language,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, link, paste, table",
                relative_urls: true,
                document_base_url: self.baseUrl,
                statusbar: false,

                toolbar: [
                    "code | undo redo | styleselect | " +
                        "bold italic underline strikethrough subscript superscript removeformat | " +
                        "alignleft aligncenter alignright alignjustify | " +
                        "bullist numlist outdent indent | cut copy pastetext | " +
                        "image table hr charmap | link unlink anchor"
                ]
            },
            text: {
                optionsName: 'text',
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',

                inline: true,
                fixed_toolbar_container: self.fixed_toolbar_container,
                language: self.language,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, link, paste, table",
                relative_urls: true,
                document_base_url: self.baseUrl,
                statusbar: false,

                toolbar: [
                    "code | undo redo | " +
                        "bold italic underline strikethrough subscript superscript removeformat | " +
                        "outdent indent | cut copy pastetext | " +
                        "image charmap | link unlink anchor"
                ]
            },
            simpleText: {
                optionsName: 'simpleText',
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',

                inline: true,
                fixed_toolbar_container: self.fixed_toolbar_container,
                language: self.language,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, link, paste, table",
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
    .factory(
        'rcmHtmlEditorState',
        [
            function () {

                var RcmHtmlEditorState = function () {

                    var self = this;
                    self.isEditing = false;
                    self.toolbarLoading = false;
                    self.editorsLoading = [];
                    self.showFixedToolbar = false;
                    self.editors = {};
                    self.hasEditors = false;

                    self.updateState = function (onUpdateComplete) {

                        var hasEditors = false;

                        for (var id in self.editors) {

                            if (self.editors[id].hasTinyMce()) {

                                hasEditors = true;

                            } else {
                                // this causes things to get out of sync
                                //self.editors[id].destroy();
                                //self.editors[id] = null;
                                //delete self.editors[id];
                            }
                        }

                        self.hasEditors = hasEditors;

                        if (typeof onUpdateComplete === 'function') {

                            onUpdateComplete();
                        }
                    }

                    self.hasTinyMce = function (id) {

                        if (self.editors[id]) {

                            return self.editors[id].hasTinyMce();
                        }

                        return false;
                    }
                };

                var rcmHtmlEditorState = new RcmHtmlEditorState();

                return rcmHtmlEditorState;
            }
        ]
    )
    .factory(
        'rcmHtmlEditorLoading',
        [
            'rcmHtmlEditorState',
            function (rcmHtmlEditorState) {

                return function (editorId, loading, msg) {

                    if (loading) {

                        if (rcmHtmlEditorState.editorsLoading.indexOf(editorId) < 0) {
                            rcmHtmlEditorState.editorsLoading.push(editorId);
                        }
                    } else {

                        if (rcmHtmlEditorState.editorsLoading.indexOf(editorId) > -1) {

                            rcmHtmlEditorState.editorsLoading.splice(
                                rcmHtmlEditorState.editorsLoading.indexOf(editorId),
                                1
                            )
                        }
                    }

                    rcmHtmlEditorState.toolbarLoading = rcmHtmlEditorState.editorsLoading.length > 0;
                }
            }
        ]
    )
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


                // build settings based on the attrs and config
                self.buildHtmlOptions = function (id, scope, attrs, config) {

                    var options = {};
                    var settings = {};

                    if (typeof config !== 'object') {

                        config = {};
                    }

                    if (attrs.htmlEditorOptions) {
                        try {
                            var attrConfig = scope.$eval(attrs.htmlEditorOptions);
                        } catch (e) {

                        }

                        if (typeof attrConfig === 'object') {

                            config = angular.extend(attrConfig, config);
                        }
                    }

                    options = angular.copy(self.getHtmlOptions(attrs.htmlEditorType));

                    settings = angular.extend(options, config); // copy(options);

                    settings.mode = 'exact';
                    settings.elements = id;
                    settings.fixed_toolbar = true;

                    // set some overrides based on attr html-editor-attached-toolbar
                    if (typeof attrs.htmlEditorAttachedToolbar !== 'undefined') {
                        settings.inline = true;
                        settings.fixed_toolbar_container = rcmHtmlEditorConfig.toolbar_container_prefix + id;
                        settings.fixed_toolbar = false;

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
        'rcmHtmlEditorFactory',
        [
            'RcmHtmlEditor',
            'rcmHtmlEditorState',
            function (RcmHtmlEditor, rcmHtmlEditorState) {

                var self = this;

                self.build = function (id, scope, elm, attrs, ngModel, settings) {

                    rcmHtmlEditorState.editors[id] = new RcmHtmlEditor(id);
                    rcmHtmlEditorState.editors[id].init(
                        scope,
                        elm,
                        attrs,
                        ngModel,
                        settings,
                        function (rcmHtmlEditor) {

                            rcmHtmlEditorState.updateState();
                        }
                    );

                    return rcmHtmlEditorState.editors[id];
                }

                self.destroy = function (id) {

                    if (rcmHtmlEditorState.editors[id]) {

                        rcmHtmlEditorState.editors[id].destroy();
                        rcmHtmlEditorState.editors[id] = null;
                        delete rcmHtmlEditorState.editors[id];

                        rcmHtmlEditorState.updateState();
                    }

                    return null;
                }

                return self;
            }
        ]
    )
    .factory(
        'RcmHtmlEditor',
        [
            'rcmHtmlEditorState',
            'rcmHtmlEditorLoading',
            function (rcmHtmlEditorState, rcmHtmlEditorLoading) {

                var RcmHtmlEditor = function (id) {
                    var self = this;
                    self.id = id;
                    self.scope;
                    self.elm;
                    self.attrs;
                    self.ngModel;

                    self.settings = {};
                    self.tinyInstance;
                    self.tagName = "";

                    self.init = function (scope, elm, attrs, ngModel, settings, onInitComplete) {

                        self.scope = scope;
                        self.elm = elm;
                        self.ngModel = ngModel;
                        self.settings = settings;
                        self.attrs = attrs;

                        self.buildEditor(onInitComplete);
                    }

                    self.getTagName = function () {

                        if ((self.elm && self.elm[0]) && !self.tagName) {
                            self.tagName = self.elm[0].tagName;
                        }

                        return self.tagName;
                    }

                    self.getElmValue = function () {

                        if (self.isFormControl()) {

                            return self.elm.val();
                        }

                        return self.elm.html();
                    }

                    self.isFormControl = function () {

                        if (self.getTagName() == "TEXTAREA") {

                            return true;
                        }

                        return false;
                    }

                    self.updateView = function () {

                        if (self.ngModel) {
                            self.ngModel.$setViewValue(self.getElmValue());
                        }
                        if (!self.scope.$root.$$phase) {
                            self.scope.$apply();
                        }
                    };

                    self.buildEditor = function (onBuildComplete) {

                        self.settings.setup = function (ed) {
                            var args;
                            //
                            //ed.on('click', function (args) {
                            //
                            //    if (self.elm.click) {
                            //        self.elm.click();
                            //    }
                            //});
                            ed.on('init', function (args) {

                                if (self.ngModel) {
                                    self.ngModel.$render();
                                    self.ngModel.$setPristine();
                                }

                                if (!self.scope.$root.$$phase) {
                                    self.scope.$apply();
                                }

                                if (typeof onBuildComplete === 'function') {

                                    onBuildComplete(self);
                                }
                            });
                            //
                            ed.on('postrender', function (args) {

                                // will show default toolbar on init
                                if (ed.settings.fixed_toolbar) {

                                    rcmHtmlEditorState.showFixedToolbar = false;
                                }
                                rcmHtmlEditorLoading(self.id, false, 'postrender');
                            });
                            // Update model on button click
                            ed.on('ExecCommand', function (e) {

                                ed.save();
                                self.updateView();
                            });
                            // Update model on keypress
                            ed.on('KeyUp', function (e) {

                                ed.save();
                                self.updateView();
                            });
                            // Update model on change, i.e. copy/pasted text, plugins altering content
                            ed.on('SetContent', function (e) {

                                if (!e.initial) {

                                    if (self.ngModel) {

                                        if (self.ngModel.$viewValue !== e.content) {
                                            ed.save();
                                            self.updateView();
                                        }
                                    } else {

                                        ed.save();
                                        self.updateView();
                                    }
                                }
                            });
                            //
                            ed.on('blur', function (e) {

                                rcmHtmlEditorState.isEditing = false;
                                if (ed.settings.fixed_toolbar) {

                                    rcmHtmlEditorState.showFixedToolbar = false;
                                }

                                if (self.elm.blur) {
                                    self.elm.blur();
                                }
                                self.updateView();
                            });
                            //
                            ed.on('focus', function (e) {

                                rcmHtmlEditorState.isEditing = true;
                                if (ed.settings.fixed_toolbar) {

                                    rcmHtmlEditorState.showFixedToolbar = true;
                                }

                                if (self.elm.focus) {
                                    self.elm.focus();
                                }
                                self.updateView();
                            });
                            // Update model when an object has been resized (table, image)
                            ed.on('ObjectResized', function (e) {

                                ed.save();
                                self.updateView();
                            });
                            // This might be needed if setup can be passed in
                            //if (settings) {
                            //    settings(ed);
                            //}
                        };

                        setTimeout(function () {
                            tinymce.init(self.settings);
                        });

                        if (self.ngModel) {

                            self.ngModel.$render = function () {

                                if (!self.tinyInstance) {
                                    self.tinyInstance = tinymce.get(self.id);
                                }
                                if (self.tinyInstance) {
                                    self.tinyInstance.setContent(self.ngModel.$viewValue || self.getElmValue());
                                }
                            };
                        }

                        self.scope.$on('$destroy', function () {

                            self.destroy();
                        });
                    };

                    self.destroy = function () {

                        if (!self.tinyInstance) {
                            self.tinyInstance = tinymce.get(self.id);
                        }
                        if (self.tinyInstance) {
                            self.tinyInstance.remove();
                            self.tinyInstance = null;
                        }

                        delete self;
                    }

                    self.hasTinyMce = function () {

                        var tinyInstance = tinymce.get(self.id);

                        if (tinyInstance) {
                            return true;
                        }

                        return false;
                    }
                };

                return RcmHtmlEditor;
            }
        ]
    )
    .factory(
        'rcmHtmlEditorInit',
        [
            'guid',
            'htmlEditorOptions',
            'rcmHtmlEditorLoading',
            'rcmHtmlEditorFactory',
            function (guid, htmlEditorOptions, rcmHtmlEditorLoading, rcmHtmlEditorFactory) {

                return function (scope, elm, attrs, ngModel, config) {

                    // generate an ID if not present
                    if (!attrs.id) {
                        attrs.$set('id', guid());
                    }
                    var id = attrs.id;

                    // this is to hide the default toolbar before init
                    rcmHtmlEditorLoading(id, true, 'rcmHtmlEditorInit');

                    // get settings from attr or config
                    var settings = htmlEditorOptions.buildHtmlOptions(
                        id,
                        scope,
                        attrs,
                        config
                    );

                    var rcmHtmlEditor = rcmHtmlEditorFactory.build(id, scope, elm, attrs, ngModel, settings);

                    return rcmHtmlEditor;
                }
            }
        ]
    )
    .factory(
        'rcmHtmlEditorDestroy',
        [
            'rcmHtmlEditorLoading',
            'rcmHtmlEditorFactory',
            function (rcmHtmlEditorLoading, rcmHtmlEditorFactory) {

                return function (id) {

                    if (id) {
                        rcmHtmlEditorFactory.destroy(id);
                        rcmHtmlEditorLoading(id, false, 'rcmHtmlEditorDestroy');
                    }
                }
            }
        ]
    )
    /*
     * rcmHtmlEdit - rcm-html-edit
     *
     * Attributes options:
     *  html-editor-options
     *  html-editor-type
     *  html-editor-attachedToolbar
     *  html-editor-base-url
     *  html-editor-size
     *  id
     */
    .directive(
        'rcmHtmlEdit',
        [
            'rcmHtmlEditorInit',
            function (rcmHtmlEditorInit) {

                return {
                    priority: 10,
                    require: '?ngModel',
                    link: rcmHtmlEditorInit
                }
            }
        ]
    )
    /*
     * htmlEditorToolbar - html-editor-toolbar
     * Example:
     * <div html-editor-toolbar></div>
     */
    .directive(
        'htmlEditorToolbar',
        [
            'rcmHtmlEditorState',
            function (rcmHtmlEditorState) {

                var thislink = function (scope, element, attrs, htmlEditorState) {

                    var self = this;

                    scope.rcmHtmlEditorState = rcmHtmlEditorState;
                }

                return {
                    link: thislink,
                    restrict: 'A',
                    template: '' +
                        //'<pre>' +
                        //'isEditing: {{rcmHtmlEditorState.isEditing | json}}\n' +
                        //'toolbarLoading: {{rcmHtmlEditorState.toolbarLoading | json}}\n' +
                        //'showFixedToolbar: {{rcmHtmlEditorState.showFixedToolbar | json}}\n' +
                        //'hasEditors: {{rcmHtmlEditorState.hasEditors | json}}\n' +
                        ////'editors: ' + JSON.stringify(rcmHtmlEditorState.editors) + '\n' +
                        //'editorsLoading: {{rcmHtmlEditorState.editorsLoading | json}}\n' +
                        //'</pre>' +
                        '<div class="htmlEditorToolbar" ng-cloak ng-hide="rcmHtmlEditorState.toolbarLoading || !rcmHtmlEditorState.hasEditors">' +
                        ' <div ng-hide="rcmHtmlEditorState.showFixedToolbar">' +
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
                        //'            <button role="presentation" type="button" disabled tabindex="-1">Select text to show controls</button>' +
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
            }
        ]
    );