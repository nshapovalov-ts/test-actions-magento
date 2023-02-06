/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_CsMultiShipping
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

define([
        'jquery',
        'underscore',
        'Magento_Ui/js/form/form',
        'ko',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-address/form-popup-state',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Checkout/js/checkout-data',
        'uiRegistry',
        'Magento_Catalog/js/price-utils',
        'mage/translate',
        'Magento_Checkout/js/model/shipping-rate-service'
    ], function (
    $,
    _,
    Component,
    ko,
    customer,
    addressList,
    addressConverter,
    quote,
    createShippingAddress,
    selectShippingAddress,
    shippingRatesValidator,
    formPopUpState,
    shippingService,
    selectShippingMethodAction,
    rateRegistry,
    setShippingInformationAction,
    stepNavigator,
    modal,
    checkoutDataResolver,
    checkoutData,
    registry,
    priceUtils,
    $t
    ) {
        'use strict';

        return function (Shipping) {
            return Shipping.extend({
                defaults: {
                    template: 'Ced_CsMultiShipping/shipping'
                },
                rates: shippingService.getShippingRates(),
                shippingRateGroups: ko.observableArray([]),
                initialize: function () {
                    var self = this;
                    this._super();
                    this.rates.subscribe(
                        function (grates) {
                            self.shippingRateGroups([]);
                            _.each(
                                grates, function (rate) {
                                    var carrierTitle = rate['carrier_title'];
                                    var carrier_code = rate['carrier_code'];

                                    if (self.shippingRateGroups.indexOf(carrierTitle) === -1
                                        && carrier_code != 'vendor_rates') {
                                        self.shippingRateGroups.push(carrierTitle);
                                    }
                                }
                            );
                        }
                    );
                },

                getFormattedPrice: function (price) {
                    return priceUtils.formatPrice(price, quote.getPriceFormat());
                },
                getRatesForGroup: function (shippingRateGroupTitle) {
                    return _.filter(
                        this.rates(), function (rate) {
                            return shippingRateGroupTitle === rate['carrier_title'];
                        }
                    );
                },
                selectVirtualMethod: function(shippingMethod) {
                    var flagg = true;
                    var METHOD_SEPARATOR = ':';
                    var SEPARATOR = '~';
                    var rates = new Array();
                    var sortedrate = new Array();
                    jQuery('.vendor-rates').each(
                        function(indx,elm){
                            var flag = false;
                            jQuery(elm).find('.radio').each(
                                function(i,inpt){
                                    if(inpt.checked) {
                                        flag = true;
                                        rates.push(inpt.value);
                                    }
                                }
                            );
                            if(!flag) {
                                flagg = false;
                            }
                        }
                    );
                    if(flagg) {
                        for(var i = 0; i < rates.length; i ++){
                            var sortedValue = rates[i].split(SEPARATOR);
                            var pos = isNaN(parseInt(sortedValue[1])) ? 0 : parseInt(sortedValue[1]);
                            sortedrate[pos] = rates[i];
                        }
                        var rate = '';
                        for(var i=0;i< sortedrate.length;i++){
                            if(sortedrate[i]!=undefined) {
                                if(rate) {
                                    rate = rate + METHOD_SEPARATOR + sortedrate[i];
                                }else{
                                    rate =  sortedrate[i];
                                }
                            }
                        }
                        if(document.getElementById('s_method_vendor_rates_'+rate)) {
                            var event = new Event('click');
                            document.getElementById('s_method_vendor_rates_'+rate).dispatchEvent(event);
                        }
                    }
                    return true;
                },

                validateShippingInformation: function () {
                    /*latest changes */
                    var flagg = true;
                    var rates = new Array();
                    jQuery('.vendor-rates').each(
                        function(indx,elm){
                            var flag = false;
                            jQuery(elm).find('.radio').each(
                                function(i,inpt){
                                    if(inpt.checked) {
                                        flag = true;
                                        rates.push(inpt.value);
                                    }
                                }
                            );
                            if(!flag) {
                                flagg = false;
                            }
                        }
                    );
                    if(!flagg) {
                        this.errorValidationMessage('Please select shipping method for each vendor.');
                        return false;
                    }
                    return this._super();
                }
            });
        };
    }
);
