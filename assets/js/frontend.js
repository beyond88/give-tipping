(() => {
    var e = {
            743: function(e, a) {
                ! function(r, n) {
                    var t = {
                            version: "0.4.1",
                            settings: {
                                currency: {
                                    symbol: "$",
                                    format: "%s%v",
                                    decimal: ".",
                                    thousand: ",",
                                    precision: 2,
                                    grouping: 3
                                },
                                number: {
                                    precision: 0,
                                    grouping: 3,
                                    thousand: ",",
                                    decimal: "."
                                }
                            }
                        },
                        i = Array.prototype.map,
                        o = Array.isArray,
                        s = Object.prototype.toString;

                    function c(e) {
                        return !!("" === e || e && e.charCodeAt && e.substr)
                    }

                    function f(e) {
                        return o ? o(e) : "[object Array]" === s.call(e)
                    }

                    function u(e) {
                        return e && "[object Object]" === s.call(e)
                    }

                    function d(e, a) {
                        var r;
                        for (r in e = e || {}, a = a || {}) a.hasOwnProperty(r) && null == e[r] && (e[r] = a[r]);
                        return e
                    }

                    function v(e, a, r) {
                        var n, t, o = [];
                        if (!e) return o;
                        if (i && e.map === i) return e.map(a, r);
                        for (n = 0, t = e.length; n < t; n++) o[n] = a.call(r, e[n], n, e);
                        return o
                    }

                    function l(e, a) {
                        return e = Math.round(Math.abs(e)), isNaN(e) ? a : e
                    }

                    function m(e) {
                        var a = t.settings.currency.format;
                        return "function" == typeof e && (e = e()), c(e) && e.match("%v") ? {
                            pos: e,
                            neg: e.replace("-", "").replace("%v", "-%v"),
                            zero: e
                        } : e && e.pos && e.pos.match("%v") ? e : c(a) ? t.settings.currency.format = {
                            pos: a,
                            neg: a.replace("%v", "-%v"),
                            zero: a
                        } : a
                    }
                    var g = t.unformat = t.parse = function(e, a) {
                            if (f(e)) return v(e, (function(e) {
                                return g(e, a)
                            }));
                            if ("number" == typeof(e = e || 0)) return e;
                            a = a || t.settings.number.decimal;
                            var r = new RegExp("[^0-9-" + a + "]", ["g"]),
                                n = parseFloat(("" + e).replace(/\((.*)\)/, "-$1").replace(r, "").replace(a, "."));
                            return isNaN(n) ? 0 : n
                        },
                        p = t.toFixed = function(e, a) {
                            a = l(a, t.settings.number.precision);
                            var r = Math.pow(10, a);
                            return (Math.round(t.unformat(e) * r) / r).toFixed(a)
                        },
                        _ = t.formatNumber = t.format = function(e, a, r, n) {
                            if (f(e)) return v(e, (function(e) {
                                return _(e, a, r, n)
                            }));
                            e = g(e);
                            var i = d(u(a) ? a : {
                                    precision: a,
                                    thousand: r,
                                    decimal: n
                                }, t.settings.number),
                                o = l(i.precision),
                                s = e < 0 ? "-" : "",
                                c = parseInt(p(Math.abs(e || 0), o), 10) + "",
                                m = c.length > 3 ? c.length % 3 : 0;
                            return s + (m ? c.substr(0, m) + i.thousand : "") + c.substr(m).replace(/(\d{3})(?=\d)/g, "$1" + i.thousand) + (o ? i.decimal + p(Math.abs(e), o).split(".")[1] : "")
                        },
                        y = t.formatMoney = function(e, a, r, n, i, o) {
                            if (f(e)) return v(e, (function(e) {
                                return y(e, a, r, n, i, o)
                            }));
                            e = g(e);
                            var s = d(u(a) ? a : {
                                    symbol: a,
                                    precision: r,
                                    thousand: n,
                                    decimal: i,
                                    format: o
                                }, t.settings.currency),
                                c = m(s.format);
                            return (e > 0 ? c.pos : e < 0 ? c.neg : c.zero).replace("%s", s.symbol).replace("%v", _(Math.abs(e), l(s.precision), s.thousand, s.decimal))
                        };
                    t.formatColumn = function(e, a, r, n, i, o) {
                        if (!e) return [];
                        var s = d(u(a) ? a : {
                                symbol: a,
                                precision: r,
                                thousand: n,
                                decimal: i,
                                format: o
                            }, t.settings.currency),
                            p = m(s.format),
                            y = p.pos.indexOf("%s") < p.pos.indexOf("%v"),
                            h = 0,
                            b = v(e, (function(e, a) {
                                if (f(e)) return t.formatColumn(e, s);
                                var r = ((e = g(e)) > 0 ? p.pos : e < 0 ? p.neg : p.zero).replace("%s", s.symbol).replace("%v", _(Math.abs(e), l(s.precision), s.thousand, s.decimal));
                                return r.length > h && (h = r.length), r
                            }));
                        return v(b, (function(e, a) {
                            return c(e) && e.length < h ? y ? e.replace(s.symbol, s.symbol + new Array(h - e.length + 1).join(" ")) : new Array(h - e.length + 1).join(" ") + e : e
                        }))
                    }, e.exports && (a = e.exports = t), a.accounting = t
                }()
            }
        },
        a = {};

    function r(n) {
        var t = a[n];
        if (void 0 !== t) return t.exports;
        var i = a[n] = {
            exports: {}
        };
        return e[n].call(i.exports, i, i.exports, r), i.exports
    }
    r.n = e => {
        var a = e && e.__esModule ? () => e.default : () => e;
        return r.d(a, {
            a
        }), a
    }, r.d = (e, a) => {
        for (var n in a) r.o(a, n) && !r.o(e, n) && Object.defineProperty(e, n, {
            enumerable: !0,
            get: a[n]
        })
    }, r.o = (e, a) => Object.prototype.hasOwnProperty.call(e, a), (() => {
        "use strict";
        var e = r(743),
            a = r.n(e);

        function n(e, a, r, n) {
            var t = 0;
            return "" !== e && "" !== a && !1 === n && (t = e > 0 && a > 0 ? function(e, a, r) {
                return e = parseFloat(e), a = parseFloat(a), ((r = parseFloat(r)) + a) / (1 - e / 100) - r
            }(e, a, r) : function(e, a, r) {
                return e = parseFloat(e), a = parseFloat(a), (r = parseFloat(r)) * (e / 100) + a
            }(e, a, r)), t
        }

        function t(e, r) {
            return r ? Math.abs(parseFloat(a().unformat(e, r))) : "undefined" != typeof give_global_vars && void 0 !== give_global_vars.decimal_separator ? Math.abs(parseFloat(a().unformat(e, give_global_vars.decimal_separator))) : "undefined" != typeof give_vars && void 0 !== give_vars.decimal_separator ? Math.abs(parseFloat(a().unformat(e, give_vars.decimal_separator))) : void 0
        }

        function i(e, r) {
            var n, t;
            "undefined" != typeof give_global_vars ? (n = give_global_vars, t = Give.form.fn.getInfo("number_decimals", r), n.currency_sign = Give.form.fn.getInfo("currency_symbol", r), n.decimal_separator = Give.form.fn.getInfo("decimal_separator", r), n.thousands_separator = Give.form.fn.getInfo("thousands_separator", r), n.currency_code = Give.form.fn.getInfo("currency_code", r)) : (n = give_vars, t = give_vars.currency_decimals, n.decimal_separator = n.decimal_separator, n.currency_code = give_tipping_recovery_object.give_fee_currency_code);
            var i = give_tipping_recovery_object.give_fee_zero_based_currency,
                o = JSON.parse(i);
            1 >= parseInt(t) && -1 === jQuery.inArray(n.currency_code, o) && (t = 2), -1 !== jQuery.inArray(n.currency_code, o) && (t = 0);
            var s = {
                symbol: n.currency_sign,
                decimal: n.decimal_separator,
                thousand: n.thousands_separator,
                precision: t,
                format: "before" === n.currency_pos ? "%s%v" : "%v%s"
            };
            return a().formatMoney(e, s)
        }
        var o, s = window.give_global_vars;
        jQuery.noConflict(), o = jQuery, window.Give_Fee_Recovery = {
            init: function() {
                o(".give-form-wrap").each((function() {
                    var e = o(this).find(".give-form"),
                        a = e.find(".give-fee-disable").val(),
                        r = !!parseInt(a),
                        n = e.find("input.give-gateway:radio:checked").val(),
                        t = e.find('input[name="give-amount"]').val();
                    o(this).find(".give-fee-message").hide(), r && o(this).find(".give-fee-message").show(), void 0 === n && (n = e.attr("data-gateway")), r && Give_Fee_Recovery.give_fee_update(e, !0, t, n)
                }))
            },
            give_fee_update: function(e, a, r, c) {
                var f = e.find(".give-final-total-amount"),
                    u = e.find(".give-fee-message-label-text"),
                    d = e.find(".fee-break-down-message"),
                    v = d.data("breakdowntext"),
                    l = e.find(".give_fee_mode_checkbox").val(),
                    m = Give.form.fn.getInfo("decimal_separator", e),
                    g = t(r, m),
                    p = JSON.parse(e.find('input[name="give-fee-recovery-settings"]').val());
                if (e.has(".give_fee_mode_checkbox").length >= 1 && 0 !== l && "undefined" !== l && (a = e.find(".give_fee_mode_checkbox").is(":checked")), 0 === e.find(".give-fee-message").length) return !1;
                f.show(), d.hide();
                var _ = p.fee_recovery,
                    y = p.fee_data.all_gateways,
                    h = 0,
                    b = 0,
                    w = !0,
                    x = !0,
                    F = !1,
                    G = !1,
                    j = 0;
                if (_) {
                    y ? (h = p.fee_data.all_gateways.base_amount, b = p.fee_data.all_gateways.percentage, w = p.fee_data.all_gateways.is_break_down, x = p.fee_data.all_gateways.give_fee_status, F = p.fee_data.all_gateways.give_fee_disable, G = p.fee_data.all_gateways.maxAmount) : jQuery.each(p.fee_data, (function(e, a) {
                        c === e && (h = a.base_amount, b = a.percentage, w = a.is_break_down, x = a.give_fee_status, F = a.give_fee_disable, G = a.maxAmount)
                    }));
                    var M = give_tipping_recovery_object.give_fee_zero_based_currency,
                        A = JSON.parse(M),
                        k = Give.form.fn.getInfo("currency_code", e),
                        I = Give.form.fn.getInfo("number_decimals", e);
                    1 >= parseInt(I) && -1 === jQuery.inArray(k, A) && (I = 2), j = n(b, h, t(r, m), F), -1 !== jQuery.inArray(k, A) && (I = 0, j = Math.ceil(j)), (G = parseFloat(G)) > 0 && (j = Math.min(j, G)), a && (g += t(Give.fn.formatCurrency(j, {
                        precision: I
                    }, e)));
                    var O = v.replace("{amount}", i(t(r, m), e)).replace("{fee_amount}", i(j, e));
                    if (F) e.find(".give-fee-recovery-donors-choice").hide(), e.find(".fee-coverage-required").hide();
                    else {
                        var C = !1,
                            N = "#give-form-" + e[0].querySelector('input[name="give-form-id"]').value + "-wrap",
                            R = Array.from(document.querySelectorAll(N)).filter((function(e) {
                                return !e.querySelector("form.give-form")
                            })).pop();
                        R && (C = R.classList.contains("give-display-modal")), C && e.parent().hasClass("mfp-content") || e.find(".give-fee-recovery-donors-choice").show(), e.find(".fee-coverage-required").show()
                    }
                    x ? (e.find('input[name="give-fee-status"]').remove(), e.prepend('<input type="hidden" name="give-fee-status" value="enabled"/>')) : (e.find('input[name="give-fee-status"]').remove(), e.prepend('<input type="hidden" name="give-fee-status" value="disabled"/>')), a && w && void 0 !== v && (d.show(), e.find('input[name="give-payment-mode"]').remove(), e.prepend('<input type="hidden" name="give-payment-mode" value="' + c + '"/>'), d.text(O));
                    var Q = e.find(".give-fee-message-label").data("feemessage").replace("{fee_amount}", i(j, e));
                    u.text(Q), I = void 0 !== s ? s.number_decimals : give_vars.currency_decimals, 1 >= parseInt(I) && (I = 2), setTimeout((function() {
                        f.text(i(g, e)).attr("data-total", Give.fn.formatCurrency(g, {
                            precision: I
                        }, e))
                    }), 0), 0 === t(Give.fn.formatCurrency(j, {
                        precision: I
                    }, e)) ? (d.hide(), u.hide(), o(".give-fee-message-label").hide()) : (u.show(), o(".give-fee-message-label").show()), e.find('input[name="give-fee-mode-enable"]').remove(), e.prepend('<input type="hidden" name="give-fee-mode-enable" value="' + a + '"/>'), e.find('input[name="give-fee-amount"]').remove(), e.prepend('<input type="hidden" name="give-fee-amount" value="' + t(Give.fn.formatCurrency(j, {
                        precision: I
                    }, e)) + '"/>')
                } else e.find('input[name="give-fee-status"]').remove(), e.prepend('<input type="hidden" name="give-fee-status" value="disabled"/>')
            },
            percent_calculation: function(a, b){
                var c = (parseFloat(a)*parseFloat(b))/100;
                return parseFloat(c);
            },
            display_percentage_amount: function(target_total_amount){
                o(".give-tipping-list-item").each(function(index, item) {
                    let current_value = parseFloat(o(this).val());
                    o(this).text(`${o(this).data('currency')}${Math.ceil(Give_Fee_Recovery.percent_calculation(target_total_amount, current_value))}`);
                });
            }
        }, o((function() {
            o("body").on("change", ".give_fee_mode_checkbox", (function() {
                var e = o(this).closest("form.give-form"),
                    a = o(this).is(":checked"),
                    r = e.find("input.give-gateway:radio:checked").val(),
                    n = e.find('input[name="give-amount"]').val();
                let tip_amount = 0;
                void 0 === r && (r = e.attr("data-gateway")), 
                o(".give-tipping-list-item").each(function(index, item) {
                    if( o(this).hasClass('give-tip-default-level') ){
                        tip_amount = parseFloat(o(this).val());
                        return tip_amount; 
                    }
                });

                var tip_type = o('.give-tip-mode').val(),
                tip_check_option = o('.give_tip_mode_checkbox').is(':checked');
        
                if(tip_check_option) {
                    if( tip_type === "percentage" ) {
                        tip_amount = parseFloat(Give_Fee_Recovery.percent_calculation(n, tip_amount));
                    }
                    var new_total_amount = parseFloat(parseFloat(n) + tip_amount);
                    Give_Fee_Recovery.give_fee_update(e, a, new_total_amount, r)
                } else {
                    Give_Fee_Recovery.give_fee_update(e, a, n, r)
                }
                
            })).change(), o(document).on("give_donation_value_updated", (function(e, a, r) {
                a || (a = o(this).closest("form.give-form"));
                var n = a.find("input.give-gateway:radio:checked").val(),
                    t = void 0 === r ? a.find('input[name="give-amount"]').val() : r;
                let tip_amount = 0;
                void 0 === n && (n = form.attr("data-gateway")),

                o(".give-tipping-list-item").each(function(index, item) {
                    if( o(this).hasClass('give-tip-default-level') ){
                        tip_amount = parseFloat(o(this).val());
                        return tip_amount; 
                    }
                });
        
                var tip_type = o('.give-tip-mode').val(),
                tip_check_option = o('.give_tip_mode_checkbox').is(':checked');
        
                if(tip_check_option) {
                    if( tip_type === "percentage" ) {
                        tip_amount = parseFloat(Give_Fee_Recovery.percent_calculation(t, tip_amount));

                        //console.log("total, tip", t, tip_amount);
                        Give_Fee_Recovery.display_percentage_amount(t);
                        
                    }

                    o('#give-tip-amount').val(tip_amount)
                    var new_total_amount = parseFloat(t + tip_amount);
                    Give_Fee_Recovery.give_fee_update(a, !0, new_total_amount, n);
                } else {
                    Give_Fee_Recovery.give_fee_update(a, !0, t, n)
                }
            })), o(document).on("give_recurring_donation_amount_updated", (function(e, a, r) {
                a || (a = o(this).closest("form.give-form"));
                let tip_amount = 0;
                var n = a.attr("data-gateway");
                o(".give-tipping-list-item").each(function(index, item) {
                    if( o(this).hasClass('give-tip-default-level') ){
                        tip_amount = parseFloat(o(this).val());
                        return tip_amount; 
                    }
                });

                var tip_type = o('.give-tip-mode').val(),
                tip_check_option = o('.give_tip_mode_checkbox').is(':checked');
                
                if(tip_check_option) {
                    if( tip_type === "percentage" ) {
                        tip_amount = parseFloat(Give_Fee_Recovery.percent_calculation(t, tip_amount));
                    }

                    o('#give-tip-amount').val(tip_amount)
                    var new_total_amount = parseFloat(parseFloat(r) + tip_amount);
                    Give_Fee_Recovery.give_fee_update(a, !0, new_total_amount, n)
                } else {
                    Give_Fee_Recovery.give_fee_update(a, !0, r, n)
                }

            })), o(document).on("give_gateway_loaded", (function(e, a, r) {
                let tip_amount = 0;
                var n = o(e.currentTarget.activeElement).closest("form.give-form");
                0 === n.length && (n = o("#" + r));
                var t = n.find('li.give-gateway-option-selected input[name="payment-mode"]').val(),
                    i = n.find('input[name="give-amount"]').val();
                void 0 === t && (t = n.attr("data-gateway")), 
                
                o(".give-tipping-list-item").each(function(index, item) {
                    if( o(this).hasClass('give-tip-default-level') ){
                        tip_amount = parseFloat(o(this).val());
                        return tip_amount; 
                    }
                });
                
                var tip_type = o('.give-tip-mode').val(),
                tip_check_option = o('.give_tip_mode_checkbox').is(':checked');
                
                if(tip_check_option) {
                    if( tip_type === "percentage" ) {
                        tip_amount = parseFloat(Give_Fee_Recovery.percent_calculation(t, tip_amount));
                    }

                    o('#give-tip-amount').val(tip_amount)
                    var new_total_amount = parseFloat(parseFloat(i) + tip_amount);
                    Give_Fee_Recovery.give_fee_update(n, !0, new_total_amount, t)
                } else {
                    Give_Fee_Recovery.give_fee_update(n, !0, i, t)
                }

            })), Give_Fee_Recovery.init()
        }))
    })()
})();


(function($) {
    'use strict';

    /************************
     * 
     * Click on amount and get selected
     * 
     ************************/
    $(".give-tipping-list-item").click(function() {
        $(".give-tipping-list-item").removeClass("give-tip-default-level");
        $(this).addClass("give-tip-default-level");
    });

    var $body = $('body');

    /************************
     * 
     * Checked and unchecked for giving tips
     * 
     ************************/
    $body
    .on('change', '.give_tip_mode_checkbox', function () {
        // Update donation total when document is loaded.
        var form = $(this).closest('form.give-form'),
            check_option = $('.give_fee_mode_checkbox').is(':checked'),
            gateway = form.find('input.give-gateway:radio:checked').val(),
            give_total = parseFloat(form.find('input[name="give-amount"]').val());
        
        var tip_type = $('.give-tip-mode').val(),
            tip_check_option = $(this).is(':checked');

        // This scenario is for "Edit Amount" or "Update Payment Method" stability.
        if (typeof gateway === 'undefined') {
            gateway = form.attr('data-gateway');
        }
        
        let tip_amount = 0;
        $(".give-tipping-list-item").each(function(index, item) {
            if( $(this).hasClass('give-tip-default-level') ){
                tip_amount = parseFloat($(this).val());
                return tip_amount; 
            }
        });

        if(tip_check_option) {

            if( tip_type === "percentage" ) {
                tip_amount = parseFloat(Give_Fee_Recovery.percent_calculation(give_total, tip_amount));
            }
            var new_total_amount = give_total + tip_amount;
            Give_Fee_Recovery.give_fee_update(form, false, new_total_amount, gateway);

            $('#give-tip-amount').val(tip_amount);

            $('.give-tipping-list-item').removeAttr('disabled');
            $('#give-donation-tip-level-button-wrap').css({"opacity":"1"});

        } else {

            if( tip_type === "percentage" ) {
                let give_total = 0;
                    give_total = parseFloat($('.give-default-level').val());
                tip_amount = parseFloat(Give_Fee_Recovery.percent_calculation(give_total, tip_amount));
            }

            let calculated_total = parseFloat($('.give-final-total-amount').data('total'));
            let subtract_tips = calculated_total - tip_amount;
            
            if(subtract_tips < give_total ) {
                subtract_tips = give_total;
            }
            Give_Fee_Recovery.give_fee_update(form, false, subtract_tips, gateway);

            $('.give-tipping-list-item').attr('disabled', 'disabled');
            $('#give-donation-tip-level-button-wrap').css({"opacity":"0.5"});
        }

    })
    .change();

    window.onload = function(e){
        $('.give_tip_mode_checkbox').click();
    }

    /************************
     * 
     * Click on tip amount and make changes
     * 
     ************************/
    $(".give-tipping-list-item").click(function() {
        
        // Update donation total when document is loaded.
        let tip_amount = parseFloat($(this).val());
        var form = $(this).closest('form.give-form'),
        check_option = $('.give_fee_mode_checkbox').is(':checked'),
        gateway = form.find('input.give-gateway:radio:checked').val(),
        give_total = parseFloat(form.find('input[name="give-amount"]').val());

        // This scenario is for "Edit Amount" or "Update Payment Method" stability.
        if (typeof gateway === 'undefined') {
            gateway = form.attr('data-gateway');
        }

        var tip_type = $('.give-tip-mode').val(),
        tip_check_option = $('.give_tip_mode_checkbox').is(':checked');

        if(tip_check_option) {

            if( tip_type === "percentage" ) {
                tip_amount = parseFloat(Give_Fee_Recovery.percent_calculation(give_total, tip_amount));
            }
            var new_total_amount = give_total + tip_amount;
            Give_Fee_Recovery.give_fee_update(form, false, new_total_amount, gateway);
            $('#give-tip-amount').val(tip_amount);
        } 

    });
    
})(jQuery);