var rcmColunmResize = new function () {
    var self = this;

    self.totalWidthColumns = 12;

    self.defaultClass = 'col-sm-12';

    /**
     * getColumnWidth
     * @param totalWidth
     * @returns {number}
     */
    self.getColumnWidthPx = function (totalWidth) {
        return (totalWidth / self.totalWidthColumns);
    };

    /**
     * getPartWidthColumns
     * @param totalWidthPx
     * @param partWidthPx
     * @param minColumns
     * @param maxColumns
     * @returns {number}
     */
    self.getPartWidthColumns = function (totalWidthPx, partWidthPx) {

        var columnWidthPx = self.getColumnWidthPx(totalWidthPx);

        var partWidthColumns = Math.ceil(partWidthPx / columnWidthPx);

        return partWidthColumns;
    };

    /**
     * getMediaView
     * @returns {string}
     */
    self.getMediaView = function () {
        // @todo Make this work by getting the data from some source
        return 'sm'
    };

    /**
     *
     * @param elm
     * @param view
     * @param widthCols
     */
    self.setWidth = function (elm, view, widthCols) {

        var columnData = self.getElmColumnData(elm);

        var maxWidthColumns = self.totalWidthColumns - columnData[view].offset;

        if (widthCols > maxWidthColumns) {
            widthCols = maxWidthColumns;
        }

        if (widthCols < 1) {
            widthCols = 1;
        }

        var widthAndOffset = widthCols + columnData[view].offset;

        if (widthAndOffset > self.totalWidthColumns) {
            columnData[view].offset = self.totalWidthColumns - widthCols;
        }

        columnData[view].width = widthCols;

        self.updateColumnClass(
            elm,
            columnData
        );
    };

    /**
     * setOffset in columns
     * @param elm
     * @param view
     * @param offsetCols
     */
    self.setOffset = function (elm, view, offsetCols) {

        var columnData = self.getElmColumnData(elm);

        var maxOffsetColumns = self.totalWidthColumns - 1; //columnData[view].width;

        if (offsetCols > maxOffsetColumns) {
            offsetCols = maxOffsetColumns;
        }

        if (offsetCols < 0) {
            offsetCols = 0;
        }

        var widthAndOffset = offsetCols + columnData[view].width;

        if (widthAndOffset > self.totalWidthColumns) {
            columnData[view].width = self.totalWidthColumns - offsetCols;
        }

        columnData[view].offset = offsetCols;

        self.updateColumnClass(
            elm,
            columnData
        );
    };

    /**
     * getElmColumnData
     * @param elm
     * @returns {{xs: {width: number, offset: number}, sm: {width: number, offset: number}, md: {width: number, offset: number}, lg: {width: number, offset: number}}}
     */
    self.getElmColumnData = function (elm) {

        var currentClass = self.getCurrentClass(elm);

        currentClass = currentClass.replace(/^\s+|\s+$/g, '');

        var classes = currentClass.split(' ');

        var data = {
            'xs': {
                width: 0,
                offset: 0
            },
            'sm': {
                width: 0,
                offset: 0
            },
            'md': {
                width: 0,
                offset: 0
            },
            'lg': {
                width: 0,
                offset: 0
            }
        };

        var part;

        for (var index in classes) {

            part = classes[index].split('-');

            if (part.length === 3) {
                data[part[1]].width = Number(part[2]);
            }

            if (part.length === 4) {
                data[part[1]][part[2]] = Number(part[3]);
            }
        }

        return data;
    };

    /**
     * Destroy resize bits
     * @param elm
     */
    self.destroy = function (elm) {
        elm.parent().unbind('mousemove');
        var controls = elm.find('.rcm-colunm-resize-control');
        controls.unbind('mousedown');
        controls.remove();
    };

    /**
     * Add draggy controls
     * @param elm
     */
    self.addControls = function (elm) {

        elm = jQuery(elm);

        var controlOffset = jQuery('<div class="rcm-colunm-resize-control offset"><div> </div></div>');

        var controlWidth = jQuery('<div class="rcm-colunm-resize-control width"><div> </div></div>');

        elm.append(controlOffset);
        elm.append(controlWidth);

        controlOffset.mousedown(
            function (e) {
                e.preventDefault();
                elm.currentColumnData = self.getElmColumnData(elm);
                elm.offsetStartPositonX = e.pageX;

                elm.parent().mousemove(
                    function (e) {
                        var changePx = e.pageX - elm.offsetStartPositonX;

                        var changeCols = self.getPartWidthColumns(
                            elm.parent().width(),
                            changePx
                        );

                        var mediaView = self.getMediaView();

                        var cols = elm.currentColumnData[mediaView].offset + changeCols;

                        self.setOffset(elm, mediaView, cols);
                    }
                );
            }
        );

        controlWidth.mousedown(
            function (e) {
                e.preventDefault();
                elm.currentColumnData = self.getElmColumnData(elm);
                elm.widthStartPositonX = e.pageX;

                elm.parent().mousemove(
                    function (e) {
                        var changePx = e.pageX - elm.widthStartPositonX;

                        var changeCols = self.getPartWidthColumns(
                            elm.parent().width(),
                            changePx
                        );

                        var mediaView = self.getMediaView();

                        var cols = elm.currentColumnData[mediaView].width + changeCols;

                        self.setWidth(elm, mediaView, cols);
                    }
                );
            }
        );

        jQuery(document).mouseup(
            function (e) {
                elm.parent().unbind('mousemove');
            }
        );
    };

    /**
     * buildClass
     * @param columnData
     * @returns {string}
     */
    self.buildClass = function (columnData) {

        var classPrefix = 'col';

        var classes = '';

        var className = '';

        for (var mediaView in columnData) {
            for (var detail in columnData[mediaView]) {

                className = '';

                if (columnData[mediaView][detail] !== 0 && columnData[mediaView][detail] !== null && columnData[mediaView][detail] !== undefined) {

                    if (detail == 'width') {
                        className = classPrefix + '-' + mediaView + '-' + columnData[mediaView][detail];
                    } else {

                        className = classPrefix + '-' + mediaView + '-' + detail + '-' + columnData[mediaView][detail];
                    }

                }

                if (className !== '') {
                    classes = classes + className + ' ';
                }
            }
        }

        classes = classes.replace(/^\s+|\s+$/g, '');

        if (classes == '') {
            classes = self.defaultClass;
        }

        return classes;
    };

    /**
     * updateColumnClass
     * @param elm
     * @param columnData
     */
    self.updateColumnClass = function (elm, columnData) {
        var newClass = self.buildClass(columnData);
        self.setClass(elm, newClass);
    };

    /**
     *
     * @param elm
     * @returns {*}
     */
    self.getCurrentClass = function (elm) {

        return elm.attr('data-rcmplugincolumnclass');
    };

    /**
     * Set Class
     * @param elm
     * @param newClass
     */
    self.setClass = function(elm, newClass) {
        //var defaultClass = elm.attr('data-rcmplugindefaultclass');
        var currentClass = elm.attr('data-rcmplugincolumnclass');
        elm.attr('data-rcmplugincolumnclass', newClass);
        elm.removeClass(currentClass);
        elm.addClass(newClass);
    };

    self.init = self.addControls;
};

