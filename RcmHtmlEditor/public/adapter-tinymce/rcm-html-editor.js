/**
 * RcmHtmlEditor - Main adapter to an actual tinymce
 * @param id
 * @param rcmHtmlEditorService
 * @constructor
 */
var RcmHtmlEditor = function (id, rcmHtmlEditorService) {

    var self = this;
    self.id = id;
    self.scope;
    self.elm;
    self.attrs;
    self.ngModel;

    self.settings = {};
    self.tinyInstance;
    self.tagName = "";
    self.initTimeout;

    self.init = function (scope, elm, attrs, ngModel, settings) {

        self.scope = scope;
        self.elm = elm;
        self.ngModel = ngModel;
        self.settings = settings;
        self.attrs = attrs;

        // is dom has changed, init may not complete
        self.initTimeout = setTimeout(
            function () {
                self.onInitTimout();
            },
            2000
        );

        self.buildEditor();
    };

    /**
     * onInit
     */
    self.onInit = function (editor) {

        rcmHtmlEditorService.eventManager.trigger(
            'RcmHtmlEditor.onInit',
            {
                rcmHtmlEditor: self,
                editorInstance: editor
            }
        );
        clearTimeout(self.initTimeout);
    };

    /**
     * onInitTimout
     */
    self.onInitTimout = function () {

        rcmHtmlEditorService.eventManager.trigger(
            'RcmHtmlEditor.onInitTimeout',
            self
        );
        //console.warn('RcmHtmlEditor: ' + id + ' failed to init.');
        self.destroy();
    };

    /**
     * onDestroy
     */
    self.onDestroy = function () {

        rcmHtmlEditorService.eventManager.trigger(
            'RcmHtmlEditor.onDestroy',
            self
        );
        clearTimeout(self.initTimeout);
    };

    /**
     * onApply
     */
    self.onApply = function () {

        rcmHtmlEditorService.eventManager.trigger(
            'RcmHtmlEditor.onApply',
            self
        );
    };

    /**
     * getTagName
     * @returns {string}
     */
    self.getTagName = function () {

        if ((self.elm && self.elm[0]) && !self.tagName) {
            self.tagName = self.elm[0].tagName;
        }

        return self.tagName;
    };

    /**
     * getElmValue
     * @returns {*}
     */
    self.getElmValue = function () {

        if (self.isFormControl()) {

            return self.elm.val();
        }

        return self.elm.html();
    };

    /**
     * isFormControl
     * @returns {boolean}
     */
    self.isFormControl = function () {

        return (self.getTagName() == "TEXTAREA");
    };

    /**
     * updateView
     */
    self.updateView = function () {

        if (self.ngModel) {
            self.ngModel.$setViewValue(self.tinyInstance.getContent());
        }

        self.apply();
    };

    /**
     * apply
     */
    self.apply = function () {

        if (!self.scope.$root.$$phase) {

            self.scope.$apply(
                function () {
                    self.onApply();
                }
            );
        } else {

            self.onApply();
        }
    };

    /**
     * buildEditor
     */
    self.buildEditor = function () {

        self.settings.setup = function (editor) {
            var args;
            //
            //editor.on('click', function (args) {
            //
            //    if (self.elm.click) {
            //        self.elm.click();
            //    }
            //});
            editor.on(
                'init',
                function (args) {

                    if (self.ngModel) {
                        self.ngModel.$render();
                        self.ngModel.$setPristine();
                    }

                    self.onInit(editor);
                    self.apply();
                }
            );
            //
            editor.on(
                'postrender',
                function (args) {
                }
            );
            // Update model on button click
            editor.on(
                'ExecCommand',
                function (e) {
                    editor.save();
                    self.updateView();
                }
            );
            // Update model on keypress
            editor.on(
                'KeyUp',
                function (e) {
                    editor.save();
                    self.updateView();
                }
            );
            // Update model on change, i.e. copy/pasted text, plugins altering content
            editor.on(
                'SetContent',
                function (e) {

                    if (!e.initial) {

                        if (self.ngModel) {

                            if (self.ngModel.$viewValue !== e.content) {
                                editor.save();
                                self.updateView();
                            }
                        } else {

                            editor.save();
                            self.updateView();
                        }
                    }
                }
            );
            // blur
            editor.on(
                'blur',
                function (e) {

                    rcmHtmlEditorService.isEditing = false;

                    self.updateView();
                }
            );
            // focus
            editor.on(
                'focus',
                function (e) {

                    rcmHtmlEditorService.isEditing = true;

                    self.updateView();
                }
            );
            // Update model when an object has been resized (table, image)
            editor.on(
                'ObjectResized',
                function (e) {

                    editor.save();
                    self.updateView();
                }
            );
            // change
            editor.on(
                'change',
                function (e) {

                    self.updateView();
                }
            );
            // cahnge selection
            //editor.on(
            //    'SelectionChange',
            //    function () {
            //
            //        self.updateView();
            //    }
            //);
            // This might be needed if setup can be passed in
            //if (settings) {
            //    settings(editor);
            //}
        };

        setTimeout(
            function () {

                tinymce.init(self.settings);
            }
        );

        if (self.ngModel) {

            self.ngModel.$render = function () {

                if (!self.tinyInstance) {
                    self.tinyInstance = tinymce.get(self.id);
                }
                if (self.tinyInstance) {
                    self.tinyInstance.setContent(self.ngModel.$viewValue || self.getElmValue());
                } else {
                    // self.destroy(null, 'tinyInstance not found')
                }
            };
        }

        self.elm.on(
            '$destroy', function () {

                self.destroy();
            }
        );

        self.scope.$on(
            '$destroy', function () {

                // this can cause issues with editors that are on the page dynamically
                // might be caused by element being destroyed and scope is part on elm.
                // self.destroy();
            }
        );
    };

    /**
     * destroy
     */
    self.destroy = function () {

        if (!self.tinyInstance) {
            self.tinyInstance = tinymce.get(self.id);
        }

        if (self.tinyInstance) {
            self.tinyInstance.remove();
        }

        self.onDestroy();

        self.apply();
    };

    /**
     * hasEditorInstance
     * @returns {boolean}
     */
    self.hasEditorInstance = function () {

        var tinyInstance = tinymce.get(self.id);

        return (tinyInstance);
    };
};