var rcmColunmResize = new function () {
    var self = this;

    self.totalWidthColumns = 12;

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
    self.getPartWidthColumns = function (totalWidthPx, partWidthPx, minColumns, maxColumns) {

        if (typeof minColumns !== 'number') {
            minColumns = 0;
        }

        if (typeof maxColumns !== 'number') {
            maxColumns = self.totalWidthColumns;
        }

        if (totalWidthPx <= 0) {
            totalWidthPx = 1;
        }

        if (partWidthPx < 0) {
            partWidthPx = 0;
        }

        var columnWidthPx = self.getColumnWidthPx(totalWidthPx);

        var partWidthColumns = Math.ceil(partWidthPx / columnWidthPx);

        if (partWidthColumns > maxColumns) {
            partWidthColumns = maxColumns;
        }

        if (partWidthColumns < minColumns) {
            partWidthColumns = minColumns;
        }

        return partWidthColumns;
    };

    /**
     * getWidthData
     * @param totalWidthPx
     * @param partWidthPx
     * @returns {{}}
     */
    self.getWidthData = function (totalWidthPx, partWidthPx, minColumns, maxColumns) {

        var result = {};

        result.columnWidthPx = self.getColumnWidthPx(totalWidthPx);
        result.partWidthColumns = self.getPartWidthColumns(
            totalWidthPx,
            partWidthPx,
            minColumns,
            maxColumns
        );
        result.partWidthPx = Math.floor(result.partWidthColumns * result.columnWidthPx);

        result.totalWidthColumns = self.totalWidthColumns;

        return result;
    };

    self.addControls = function (elm) {

        elm = jQuery(elm);

        var controlAdd = jQuery('<div class="rcm-colunm-resize-control">&#x021A6;</div>');
        controlAdd.attr(
            'style',
            'position:absolute; top: 5px; right: 0px; background-color: rgba(255,255,255,.5); color:#FFFFFF; width:1em; cursor: col-resize;'
        );

        var controlRemove = jQuery('<div class="rcm-colunm-resize-control">&#x021A4;</div>');
        controlRemove.attr(
            'style',
            'position:absolute; top: 5px; left: 0px; background-color: rgba(255,255,255,.5); color:#FFFFFF; width:1em; cursor: col-resize;'
        );
        /*
         control.attr('draggable', 'true');

         control.bind(
         "dragstart",
         function (e) {
         // @todo Inject this
         console.log('Strat',e, 'positionOffset',e.originalEvent.x);

         e.currentTarget.originalXPos = e.originalEvent.x;
         e.currentTarget.resizeTarget = elm;
         }
         );

         control.bind(
         "drag",
         function (e) {

         // @todo Inject this
         var target = e.currentTarget.resizeTarget;
         var posChangePx =  e.originalEvent.x - e.currentTarget.originalXPos;
         var targetWidth = e.currentTarget.resizeTarget.width();
         var targetChangPx = targetWidth + posChangePx;

         console.log('DraggingPre','posChangePx',posChangePx, 'targetChangPx', targetChangPx);
         if(targetChangPx > targetWidth){
         targetChangPx = targetWidth;
         }

         if(targetChangPx < 0){
         targetChangPx = 0;
         }

         //'event.target',event.target,

         console.log('Dragging','posChangePx',posChangePx, 'targetChangPx', targetChangPx);

         //self.updateColWidth(target, targetChangPx);
         }
         );

         control.bind(
         "dragend",
         function (e) {

         // @todo Inject this
         console.log('Stop',e);
         }
         );
         */

        controlAdd.bind(
            'click',
            function (e) {
                console.log('controlAdd');
                self.changeColumn(elm, 1);
            }
        );

        controlRemove.bind(
            'click', function (e) {
                console.log('controlRemove');
                self.changeColumn(elm, 3);
            }
        );

        elm.append(controlAdd);
        elm.append(controlRemove);
    };


    //////////

    var buildClass = function (widthCols, posCols) {

        var css = 'col-md-' + widthCols;

        if (posCols > 0) {
            css = css + ' col-md-offset-' + posCols;
        }

        return css;
    };

    var updateColumnClass = function (elm, widthCols, posCols) {

        var currentClass = elm.attr('data-rcmplugincolumnclass');
        //var defaultClass = elm.attr('data-rcmplugindefaultclass');

        var newClass = buildClass(widthCols, posCols);

        elm.attr('data-rcmplugincolumnclass', newClass);
        elm.removeClass(currentClass);
        elm.addClass(newClass);
    };

    ////////////////

    self.changeColumn = function (elm, which) {
console.log('w', which);
        elm = jQuery(elm);
        var currentWidthPx = elm.width();
        var totalWidthPx = elm.parent().width();

        var columnWidthPx = self.getColumnWidthPx(totalWidthPx);

        var newWidthPx = currentWidthPx;

        if (which === 1) {
            // Add on column of width
            newWidthPx = currentWidthPx + columnWidthPx;
        }

        if (which === 3) {
            // Add on column of width
            newWidthPx = currentWidthPx - columnWidthPx;
        }

        var result = self.getWidthData(
            totalWidthPx,
            newWidthPx,
            1
        );

        updateColumnClass(elm, result.partWidthColumns, 0);
    };


    self.xxxxupdateColWidth = function (elm, changePx) {

        elm = jQuery(elm);
        var parentElm = elm.parent();

        var widthResult = self.getWidthData(
            parentElm.width(),
            changePx,
            1
        );

        updateColumnClass(elm, widthResult.partWidthColumns, 0);
    };

    // jquery resize method
    self.updateWidth = function (ui) {

        var parentElm = ui.element.parent();
        var elm = ui.element;

        console.log(
            'parentElm ',
            parentElm.width(),
            'elm ',
            elm.width(),
            '% ',
            elm.width() / parentElm.width() * 100
        );

        // Width Calc
        var widthResult = self.getWidthData(
            parentElm.width(),
            elm.width(),
            1
        );

        console.log('widthResult ', widthResult);

        elm.width(null);
        ui.size.width = null; // we sync it just in case

        updateColumnClass(elm, widthResult.partWidthColumns, 0);

        return;

        /* @todo - finish position offest */

        // Positon Calc

        var currentPosPx = elm.position().left;
        var currentOffPx = elm.offset();

        console.log('ui.position ', ui.position);

        console.log(
            'elm',
            ui.position.left,
            'par',
            parentElm.position().left
        );

        var positionResult = self.getWidthData(
            parentElm.width(),
            ui.position.left,
            0,
            11
        );

        //elm.offset({left: positionResult.partWidthPx});
        console.log('currentPosPx ', currentPosPx);
        console.log('currentOffPx ', currentOffPx);
        console.log('off ', elm.offset());
        elm.offset(currentOffPx);
        ui.position.left = 0;
        ui.position.top = 0;

        var finalWidthColumns = widthResult.partWidthColumns - positionResult.partWidthColumns;

        console.log('final ' + finalWidthColumns + ' = ' + widthResult.partWidthColumns + ' - ' + positionResult.partWidthColumns);

        var totalCalcColumns = finalWidthColumns + positionResult.partWidthColumns;

        console.log('check ' + totalCalcColumns + ' = ' + finalWidthColumns + ' + ' + positionResult.partWidthColumns);

        if (totalCalcColumns > widthResult.totalWidthColumns) {
            console.warn('Calculated cols ' + totalCalcColumns + ' exceeds max ' + widthResult.totalWidthColumns);
        }
        //

        updateColumnClass(elm, finalWidthColumns, positionResult.partWidthColumns);
    };
};

