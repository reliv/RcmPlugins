/**
 * Ajax Number Formatter
 *
 * This class helps format numbers for countries like germany with numbers like
 * EUR1.000.000,99 vs $1,000,000.99
 *
 * PHP version 5.4
 *
 * LICENSE: New BSD License
 *
 * @category  Reliv
 * @package   RcmPlugins\RcmNumberFormat
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2013 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 *
 * @param $http the angular http object
 * @constructor
 */
var AjaxNumberFormatter = function($http){

    this.formatNumber = function(value, callback){
        $http.get('/rcm-number-format-http-api/number/' + value).success(
            function (data) {
                callback(data.result);
            }
        );
    };

    this.formatCurrency = function(value, callback){
        $http.get('/rcm-number-format-http-api/currency/' + value).success(
            function (data) {
                callback(data.result);
            }
        );
    };
};