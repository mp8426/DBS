$(function () {

    $.ajax({
        url: "window-shutter-calculation/scripts/get-window-shutter-calculations.php",
        type: "POST",
        dataType: "json",
        data: {cid: cid},
        beforeSend: function () {
            //loadingPannel.show();
        },
        success: function (data) {
            if (data[0] === 4) {
                document.location.replace(data[1]);
            } else
            if (data[0] === 2) {
                alert(data[1]);
            } else
            if (data[0] === 1) {

                $.each(data[1], function (index, value) {

                    var window_shutter_calculation_code = value.code;

                    var etape_activated = '';
                    var etape_header = '';
                    var etape_element = '';

                    if (etape === 'on') {

                        //if etape === 1, etape_header and etape_element will be appednd to add_new form below and .etape-activated class will be added for styles.
                        etape_activated = 'etape-activated';
                        etape_header = '<th class="etape-th">\n\
                                            <table class="left-fixed-column">\n\
                                                <tr>\n\
                                                    <th></th>\
                                                </tr>\n\
                                            </table>\n\
                                        </th>';

                        etape_element = '<td class="etape-td">\n\
                                            <table class="left-fixed-column">\n\
                                                <tr>\n\
                                                    <td><input type="button" data-mc-db="window_shutter_calculation" data-sc-code="' + value.code + '" data-sc-name="' + value.name + '" class="bttn-input etape-btn" value="☯"></td>\n\
                                                </tr>\n\
                                            </table>\n\
                                        </td>';
                    }

                    if (value.location_select === 1) {
                        var location_header = '<th>Locations</th>';
                        var location_options = "";
                        $.each(data[2], function (location_index, location_value) {
                            location_options += '<option value="' + location_value.name + '<->' + location_value.code + '">' + location_value.name + '</option>';
                        });
                        var location_element = '<td>\n\
                                                    <select name="location" data-column="location" class="window-shutter-calculation-location data-field">\n\
                                                        <option value=""></option>\n\
                                                        ' + location_options + '\
                                                    </select>\n\
                                                </td>';
                    } else {
                        location_header = "";
                        location_options = "";
                        location_element = "";
                    }

                    var type_header = '<th>Types</th>', type_options = "", type_element = "";
                    $.each(value.types, function (type_index, type_value) {

                        type_options += '<option value="' + type_value.name + '<->' + type_value.code + '" data-code="' + type_value.code + '" data-price="' + type_value.price + '">' + type_value.name + '</option>';
                    });
                    type_element = '<td>\n\
                                        <select name="type" data-column="type" class="window-shutter-calculation-type data-field approved-disabled">\n\
                                            <option value=""></option>\n\
                                            ' + type_options + '\
                                        </select>\n\
                                    </td>';

                    var field_header_left = "", field_element_left = "";
                    var field_header_right = "", field_element_right = "";
                    $.each(value.fields, function (field_index, field_value) {

                        if (field_value.side === 1) { //left side

                            field_header_left += '<th>' + field_value.name + '</th>';

                            if (field_value.field_type === 0) {

                                var field_options = "";
                                $.each(field_value.options, function (field_option_index, field_option_value) {
                                    var price_tag = field_option_value.price > 0 ? ' | ' + field_option_value.price : '';
                                    field_options += '<option value="' + field_option_value.name + '<->' + field_option_value.code + '<->' + field_option_value.price + '">' + field_option_value.name + price_tag + '</option>';
                                });
                                field_options += '<option class="window-shutter-calculation-add-field-option-x" data-field-code="' + field_value.code + '" value="add-new">Add New</option>';

                                field_element_left += '<td>\n\
                                                        <select name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="window-shutter-calculation-field data-field">\n\
                                                            <option value=""></option>\n\
                                                            ' + field_options + '\
                                                        </select>\n\
                                                        <span class="window-shutter-calculation-add-field-option-wrapper">\n\
                                                            <input type="text" class="window-shutter-calculation-add-field-option text-input">\n\
                                                            <input type="button" class="window-shutter-calculation-add-field-option-submit bttn-input" value="✚">\n\
                                                            <input type="button" class="window-shutter-calculation-add-field-option-close bttn-input" value="✖">\n\
                                                        </span>\n\
                                                    </td>';
                            } else {
                                field_element_left += '<td>\n\
                                                        <input type="text" name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="window-shutter-calculation-field data-field text-field" autocomplete="off">\n\
                                                    </td>';
                            }
                        }

                        if (field_value.side === 0) { //right side

                            field_header_right += '<th>' + field_value.name + '</th>';

                            if (field_value.field_type === 0) {

                                var field_options = "";
                                $.each(field_value.options, function (field_option_index, field_option_value) {
                                    var price_tag = field_option_value.price > 0 ? ' | ' + field_option_value.price : '';
                                    field_options += '<option value="' + field_option_value.name + '<->' + field_option_value.code + '<->' + field_option_value.price + '">' + field_option_value.name + price_tag + '</option>';
                                });
                                field_options += '<option class="window-shutter-calculation-add-field-option-x" data-field-code="' + field_value.code + '" value="add-new">Add New</option>';

                                field_element_right += '<td>\n\
                                                            <select name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="window-shutter-calculation-field data-field">\n\
                                                                <option value=""></option>\n\
                                                                ' + field_options + '\
                                                            </select>\n\
                                                            <span class="window-shutter-calculation-add-field-option-wrapper">\n\
                                                                <input type="text" class="window-shutter-calculation-add-field-option text-input">\n\
                                                                <input type="button" class="window-shutter-calculation-add-field-option-submit bttn-input" value="✚">\n\
                                                                <input type="button" class="window-shutter-calculation-add-field-option-close bttn-input" value="✖">\n\
                                                            </span>\n\
                                                        </td>';
                            } else {
                                field_element_right += '<td>\n\
                                                            <input type="text" name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="window-shutter-calculation-field data-field text-field" autocomplete="off">\n\
                                                        </td>';
                            }
                        }
                    });

                    if (value.accessories_select === 1) {

                        var accessory_element = "";
                        var accessory_options = "";
                        var accessory_option_array = '<input type="hidden" class="accessory-option-array" value=\'' + JSON.stringify(value.accessory_options) + '\'>';

                        $.each(value.accessory_options, function (accessory_option_index, accessory_option_value) {
                            accessory_options += '<option data-code="' + accessory_option_value.code + '" value="' + accessory_option_value.code + '<->' + accessory_option_value.name + '<->' + accessory_option_value.price + '">' + accessory_option_value.name + ' | ' + accessory_option_value.price + '</option>';
                        });
                        accessory_element = '<span class="window-shutter-calculation-accessories-wrapper">\n\
                                                <select class="window-shutter-calculation-accessories">\n\
                                                    <option value="">Accessories</option>\n\
                                                    ' + accessory_options + '\
                                                </select>\n\
                                                <input type="tel" class="add-window-shutter-calculation-accessory-qty qty-input" placeholder="Qty">\n\
                                                <input type="button" class="add-window-shutter-calculation-accessories bttn-input" value="✚">\n\
                                            </span>';

                    } else {
                        accessory_element = '';
                        accessory_option_array = '';
                    }

                    if (value.per_meters_select === 1) {

                        var per_meter_element = "";
                        var per_meter_options = "";
                        var per_meter_option_array = '<input type="hidden" class="per-meter-option-array" value=\'' + JSON.stringify(value.per_meter_options) + '\'>';

                        $.each(value.per_meter_options, function (per_meter_option_index, per_meter_option_value) {
                            per_meter_options += '<option data-code="' + per_meter_option_value.code + '" value="' + per_meter_option_value.code + '<->' + per_meter_option_value.name + '<->' + per_meter_option_value.price + '">' + per_meter_option_value.name + ' | ' + per_meter_option_value.price + '</option>';
                        });
                        per_meter_element = '<span class="window-shutter-calculation-per-meters-wrapper">\n\
                                                <select class="window-shutter-calculation-per-meters">\n\
                                                    <option value="">Per Meters</option>\n\
                                                    ' + per_meter_options + '\
                                                </select>\n\
                                                <input type="tel" class="add-window-shutter-calculation-per-meter-width qty-input" placeholder="Width">\n\
                                                <input type="button" class="add-window-shutter-calculation-per-meters bttn-input" value="✚">\n\
                                            </span>';

                    } else {
                        per_meter_element = '';
                        per_meter_option_array = '';
                    }

                    if (value.fitting_charges_select === 1) {

                        var fitting_charge_element = "";
                        var fitting_charge_options = "";
                        var fitting_charge_option_array = '<input type="hidden" class="fitting-charge-option-array" value=\'' + JSON.stringify(value.fitting_charge_options) + '\'>';

                        $.each(value.fitting_charge_options, function (fitting_charge_option_index, fitting_charge_option_value) {
                            fitting_charge_options += '<option data-code="' + fitting_charge_option_value.code + '" value="' + fitting_charge_option_value.code + '<->' + fitting_charge_option_value.name + '<->' + fitting_charge_option_value.price + '">' + fitting_charge_option_value.name + ' | ' + fitting_charge_option_value.price + '</option>';
                        });
                        fitting_charge_element = '<span class="window-shutter-calculation-fitting-charges-wrapper">\n\
                                                    <select class="window-shutter-calculation-fitting-charges">\n\
                                                        <option value="">Fitting Charges</option>\n\
                                                        ' + fitting_charge_options + '\
                                                    </select>\n\
                                                    <input type="button" class="add-window-shutter-calculation-fitting-charges bttn-input" value="✚">\n\
                                                </span>';

                    } else {
                        fitting_charge_element = '';
                        fitting_charge_option_array = '';
                    }

                    if (value.status === 1) {

                        $('<li><a href="#tab-' + value.code + '">' + value.name + '</a></li>').prependTo("#window-shutter-calculation-tabs .tabs ul");
                        $('<div id="tab-' + value.code + '" class="window-shutter-calculation-tab-div"></div>')
                                .append($('<form class="add-window-shutter-calculation-quote-item-form ' + etape_activated + '" action="./" method="POST"></form>')
                                        .append('<input type="hidden" class="cid" name="cid" value="' + cid + '">')
                                        .append('<input type="hidden" class="window-shutter-calculation-code" name="window-shutter-calculation-code" value="' + window_shutter_calculation_code + '">')
                                        .append($('<table class="window-shutter-calculation-table"></table>')
                                                .append($('<thead class="thead"></thead>')
                                                        .append($('<tr class="thead-tr"></tr>')
                                                                .append(etape_header)
                                                                .append(field_header_left)
                                                                .append(location_header)
                                                                .append('<th>Width</th>')
                                                                .append('<th>Height</th>')
                                                                .append(type_header)
                                                                .append(field_header_right)
                                                                .append('<th>\n\
                                                        <table class="right-fixed-column">\n\
                                                            <tr>\n\
                                                                <th>Price</th>\n\
                                                                <th>Qty</th>\n\
                                                                <th>Add</th>\n\
                                                            </tr>\n\
                                                        </table>\n\
                                                    </th>'
                                                                        )
                                                                )
                                                        )
                                                .append($('<tbody></tbody>')
                                                        .append($('<tr class="tbody-tr"></tr>')
                                                                .append(etape_element)
                                                                .append(field_element_left)
                                                                .append(location_element)
                                                                .append('<td><input type="tel" name="width" data-column="width_x" class="window-shutter-calculation-width number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="mm"></td>')
                                                                .append('<td><input type="tel" name="drop" data-column="drop_x" class="window-shutter-calculation-drop number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="mm"></td>')
                                                                .append(type_element)
                                                                .append(field_element_right)
                                                                .append('<td>\n\
                                                        <table class="right-fixed-column" style="margin-top: -20px;">\n\
                                                            <tr>\n\
                                                                <td><input type="tel" name="price" class="window-shutter-calculation-price price-input data-field" autocomplete="off"></td>\n\
                                                                <td><input type="number" name="qty" class="window-shutter-calculation-qty number-input" autocomplete="off" min="1" value="1"></td>\n\
                                                                <td><input type="submit" class="bttn-input" value="✚"></td>\n\
                                                            </tr>\n\
                                                        </table>\n\
                                                    </td>'
                                                                        )
                                                                )
                                                        )
                                                )
                                        .append(accessory_option_array)
                                        .append(per_meter_option_array)
                                        .append(fitting_charge_option_array)
                                        ).prependTo("#window-shutter-calculation-tabs .calculation-results").find(".bttn-input").button();
                    }
                    // End of prepend Calculation methods

                    if (JSON.stringify(value.quote_items) !== '[]') {

                        $('<div id="window-shutter-calculation-quote-items-wrapper-' + window_shutter_calculation_code + '" class="window-shutter-calculation-quote-items-wrapper"></div>')
                                .append('<h2 class="window-shutter-calculation-header">' + value.name + '</h2>')
                                .append('<div id="quote-items-body-' + window_shutter_calculation_code + '" class="window-shutter-calculation-quote-items-body"></div>')
                                .append('<div class="bulk-action-button-wrapper">\n\
                                            <input type="button" class="bulk-action-button window-shutter-calculation-select-all-bttn" value="✔">\n\
                                            <input type="button" class="bulk-action-button window-shutter-calculation-bulk-delete-bttn" value="Delete">\n\
                                            <input type="button" class="bulk-action-button window-shutter-calculation-bulk-copy-bttn" value="Copy">\n\
                                            <a href="print/order-sheet-window-shutter-calculation-' + window_shutter_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 145px;">Print Order Sheet</a>\n\
                                            <a href="https://file-gen.blinq.com.au/clients/' + user_account + '/order-sheet-xlsx-window-shutter-calculation-' + window_shutter_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 175px;">Print Order Sheet Xlsx</a>\n\
                                            <form class="group-discount-form" style="float: right; margin-right: -6px; font-size: 12px;">\n\
                                                <input type="tel" class="group-total price-input ui-state-default" autocomplete="off" style="float: left;" disabled>\n\
                                                <input type="tel" class="group-discount price-input ui-state-default" autocomplete="off" placeholder="%" style="float: left; margin-left: 1px !important; margin-right: 1px !important; text-align: center; width: 33px;" required>\n\
                                                <input type="hidden" class="window-shutter-calculation-code" value="' + window_shutter_calculation_code + '">\n\
                                                <input type="submit" class="bttn-input ui-button ui-widget ui-state-default ui-corner-all" value="✔" role="button" aria-disabled="false" style="float: left;">\n\
                                            </form>\n\
                                            <div class="group-discount-total" style="float: right; line-height: 40px; margin-right: 400px; font-size: 22px; font-weight: bold;"></div>\n\
                                        </div>'
                                        ).prependTo("#window-shutter-calculation-quote-items-results").find(".bttn-input, .bulk-action-button").button();

                        var table_header = '<tr class="thead-tr">\n\
                                                <th class="left-fixed-column">#</th>\n\
                                                ' + field_header_left + '\
                                                ' + location_header + '\
                                                <th>Width</th>\n\
                                                <th>Height</th>\n\
                                                ' + type_header + '\
                                                ' + field_header_right + '\
                                                <th>\n\
                                                    <table class="right-fixed-column">\n\
                                                        <tr>\n\
                                                            <th>Price</th>\n\
                                                            <th>More</th>\n\
                                                            <th>Del</th>\n\
                                                        </tr>\n\
                                                    </table>\n\
                                                </th>\n\
                                            </tr>';

                        var quote_item_no = 1;
                        var group_total = 0;
                        var group_discount = 0;
                        $.each(value.quote_items, function (quote_item_index, quote_item_value) {

                            $('<form id="calculation-form-' + quote_item_value.code + '" class="update-window-shutter-calculation-quote-item-form" action="./" method="POST">\n\
                                    <input type="hidden" name="cid" class="cid" value="' + cid + '">\n\
                                    <input type="hidden" name="window-shutter-calculation-code" class="window-shutter-calculation-code" value="' + window_shutter_calculation_code + '">\n\
                                    <input type="hidden" name="window-shutter-calculation-quote-item-code" class="window-shutter-calculation-quote-item-code" value="' + quote_item_value.code + '">\n\
                                    <table class="window-shutter-calculation-table">\n\
                                        <thead class="thead"></thead>\n\
                                        <tbody>\n\
                                            <tr>\n\
                                                <td class="left-fixed-column">\n\
                                                    <label class="checkbox-label" for="checkbox-' + quote_item_value.code + '">' + quote_item_no + '</label>\n\
                                                    <input type="checkbox" id="checkbox-' + quote_item_value.code + '"class="checkbox-input" name="quote-item-codes[]" value="' + quote_item_value.code + '">\n\
                                                </td>\n\
                                                ' + field_element_left + '\
                                                ' + location_element + '\
                                                <td><input type="tel" name="width" data-column="width_x" class="window-shutter-calculation-width number-input data-field" maxlength="4" autocomplete="off" min="0" value="' + quote_item_value.width + '" placeholder="mm"></td>\n\
                                                <td><input type="tel" name="drop" data-column="drop_x" class="window-shutter-calculation-drop number-input data-field" maxlength="4" autocomplete="off" min="0" value="' + quote_item_value.drop + '" placeholder="mm"></td>\n\
                                                ' + type_element + '\
                                                ' + field_element_right + '\
                                                <td>\n\
                                                    <table class="right-fixed-column" style="margin-top: -20px;">\n\
                                                        <tr>\n\
                                                            <td><input type="tel" name="price" data-column="price" class="window-shutter-calculation-price price-input data-field ui-state-default" autocomplete="off" value="' + quote_item_value.price + '"></td>\n\
                                                            <td><input type="button" class="window-shutter-calculation-table-more-bttn bttn-input" value="★"></td>\n\
                                                            <td><input type="button" class="bttn-input delete-window-shutter-calculation-quote-item" value="✖"></td>\n\
                                                        </tr>\n\
                                                    </table>\n\
                                                </td>\n\
                                            </tr>\n\
                                        </tbody>\n\
                                    </table>\n\
                                    <table id="calculation-table-more-' + quote_item_value.code + '"  class="window-shutter-calculation-table-more">\n\
                                        <tr>\n\
                                            <td class="table-more-notes" colspan="3">\n\
                                                <textarea name="notes" data-column="notes" class="window-shutter-calculation-notes data-field" placeholder="Notes">' + quote_item_value.notes + '</textarea>\n\
                                            </td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td class="table-more-accessories">\n\
                                                ' + accessory_element + '\
                                            </td>\n\
                                            <td class="table-more-per-meters">\n\
                                                ' + per_meter_element + '\
                                            </td>\n\
                                            <td class="table-more-fitting-charges">\n\
                                                ' + fitting_charge_element + '\
                                            </td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </form>').appendTo("#quote-items-body-" + window_shutter_calculation_code).queue(function () {

                                var this_form = $(this);

                                this_form.find("[name='location']").val(quote_item_value.location);
                                this_form.find("[name='type']").val(quote_item_value.type);

                                if (quote_status === '1') {
                                    this_form.find(".approved-disabled").css({"pointer-events": "none", "background": "#f2f2f2"});
                                }

                                $.each(quote_item_value.fields, function (quote_item_fields_index, quote_item_fields_value) {
                                    var field_code = quote_item_fields_value.code;
                                    var field_name = quote_item_fields_value.name;
                                    var field_price = quote_item_fields_value.price;
                                    var window_shutter_calculation_field_code = quote_item_fields_value.window_shutter_calculation_field_code;
                                    //this_form.find("[name='field[" + window_shutter_calculation_field_code + "]']").val(field_name + '<->' + field_code);

                                    if (this_form.find("[name='field[" + window_shutter_calculation_field_code + "]']").is('input')) {
                                        this_form.find("[name='field[" + window_shutter_calculation_field_code + "]']").val(field_name);
                                    } else {
                                        this_form.find("[name='field[" + window_shutter_calculation_field_code + "]']").val(field_name + '<->' + field_code + '<->' + field_price);
                                    }
                                });

                                $.each(quote_item_value.accessories, function (quote_item_accessories_index, quote_item_accessories_value) {

                                    var accessory_code = quote_item_accessories_value.code;
                                    var accessory_name = quote_item_accessories_value.name;
                                    var accessory_price = +quote_item_accessories_value.price;
                                    var accessory_qty = +quote_item_accessories_value.qty;
                                    var accessory_total = (accessory_price * accessory_qty).toFixed(2);

                                    this_form.find(".table-more-accessories").append('<span class="window-shutter-calculation-accessories-wrapper">\n\
                                                                                            <input type="text" class="window-shutter-calculation-accessory" readonly disabled value="' + accessory_name + ' | ' + accessory_price + ' x ' + accessory_qty + ' = ' + accessory_total + '">\n\
                                                                                            <input type="button" class="delete-window-shutter-calculation-accessory bttn-input" data-code="' + accessory_code + '" value="✖">\n\
                                                                                        </span>');

                                    this_form.find('.window-shutter-calculation-accessories option[data-code="' + accessory_code + '"]').hide();
                                });

                                $.each(quote_item_value.per_meters, function (quote_item_per_meters_index, quote_item_per_meters_value) {

                                    var per_meter_code = quote_item_per_meters_value.code;
                                    var per_meter_name = quote_item_per_meters_value.name;
                                    var per_meter_price = +quote_item_per_meters_value.price;
                                    var per_meter_width = +quote_item_per_meters_value.width;
                                    var per_meter_total = (per_meter_price * per_meter_width).toFixed(2);

                                    this_form.find(".table-more-per-meters").append('<span class="window-shutter-calculation-per-meters-wrapper">\n\
                                                                                            <input type="text" class="window-shutter-calculation-per-meter" readonly disabled value="' + per_meter_name + ' | ' + per_meter_price + ' x ' + per_meter_width + ' = ' + per_meter_total + '">\n\
                                                                                            <input type="button" class="delete-window-shutter-calculation-per-meter bttn-input" data-code="' + per_meter_code + '" value="✖">\n\
                                                                                        </span>');

                                    this_form.find('.window-shutter-calculation-per-meters option[data-code="' + per_meter_code + '"]').hide();
                                });

                                $.each(quote_item_value.fitting_charges, function (quote_item_fitting_charges_index, quote_item_fitting_charges_value) {

                                    var fitting_charge_code = quote_item_fitting_charges_value.code;
                                    var fitting_charge_name = quote_item_fitting_charges_value.name;
                                    var fitting_charge_price = quote_item_fitting_charges_value.price;
                                    this_form.find(".table-more-fitting-charges").append('<span class="window-shutter-calculation-fitting-charges-wrapper">\n\
                                                                                                <input type="text" class="window-shutter-calculation-fitting-charge" readonly disabled value="' + fitting_charge_name + ' | ' + fitting_charge_price + '">\n\
                                                                                                <input type="button" class="delete-window-shutter-calculation-fitting-charge bttn-input" data-code="' + fitting_charge_code + '" value="✖">\n\
                                                                                            </span>');

                                    this_form.find('.window-shutter-calculation-fitting-charges option[data-code="' + fitting_charge_code + '"]').hide();
                                });

                                var note_length = quote_item_value.notes;
                                var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                                var per_meter_length = this_form.find('.window-shutter-calculation-per-meter').length;
                                var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                                if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                    this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                                } else {
                                    this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                                }

                                quote_item_no++;
                                group_total = +group_total + (+quote_item_value.price);
                                group_discount = quote_item_value.discount;

                                this_form.find(".bttn-input, .checkbox-input").button();
                                this_form.dequeue();

                            }).clearQueue().html();
                        });

                        $("#window-shutter-calculation-quote-items-wrapper-" + window_shutter_calculation_code)
                                .find(".group-total").val(group_total.toFixed(2)).end()
                                .find(".group-discount").val(group_discount).end()
                                .find(".group-discount-total").html((group_total - (group_total * group_discount / 100)).toFixed(2));

                        $(".window-shutter-calculation-quote-items-body .window-shutter-calculation-table .thead").first().html(table_header); // Add Table headers for each calculation
                    } else {

                        $('<div id="window-shutter-calculation-quote-items-wrapper-' + window_shutter_calculation_code + '" class="window-shutter-calculation-quote-items-wrapper"></div>')
                                .append('<h2 class="window-shutter-calculation-header">' + value.name + '</h2>')
                                .append('<div id="quote-items-body-' + window_shutter_calculation_code + '" class="window-shutter-calculation-quote-items-body"></div>')
                                .append('<div class="bulk-action-button-wrapper"></div>')
                                .prependTo("#window-shutter-calculation-quote-items-results").hide();
                    }
                });
                $(".tabs").tabs("refresh").tabs("option", "active", 0);
            } else {
                alert(JSON.stringify(data));
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(JSON.stringify(jqXHR));
        }
    });

    $(document).on("click", ".window-shutter-calculation-select-all-bttn", function () {
        var this_bttn = $(this);
        var this_text = this_bttn.val();
        var this_parent = this_bttn.parents(".window-shutter-calculation-quote-items-wrapper");

        if (this_text === "✔") {
            this_parent.find(".checkbox-input").prop("checked", true).change().button("refresh");
            this_bttn.val("✖");
        } else {
            this_parent.find(".checkbox-input").prop("checked", false).change().button("refresh");
            this_bttn.val("✔");
        }
    });

    $(document).on("click", ".window-shutter-calculation-bulk-delete-bttn", function () {

        var this_bttn = $(this);
        var this_parent = this_bttn.parents(".window-shutter-calculation-quote-items-wrapper");
        var table_header = this_parent.find(".window-shutter-calculation-table .thead .thead-tr").clone();
        var quote_item_codes = this_parent.find(".checkbox-input:checked");
        var datas = quote_item_codes.serialize() + '&cid=' + cid;

        if (datas) {

            $.ajax({
                url: "window-shutter-calculation/scripts/delete-bulk-window-shutter-calculation-quote-items.php",
                type: 'POST',
                dataType: 'json',
                data: datas,
                beforeSend: function () {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                },
                success: function (data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        $.each(quote_item_codes.serializeArray(), function (index, code) {
                            $("#calculation-form-" + code.value).remove();
                        });

                        if (!this_parent.find('.update-window-shutter-calculation-quote-item-form').length) {
                            this_parent.hide().find(".bulk-action-button-wrapper").empty();
                        } else {
                            this_parent.find(".checkbox-label").each(function (i) {
                                $(this).find(".ui-button-text").html(i + 1);
                            });
                            this_parent.find(".window-shutter-calculation-table .thead").first().html(table_header);
                        }

                        this_bttn.prop('disabled', false).removeClass('field-loader');

                        window_shutter_calculation_group_discount(this_bttn);
                        $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @delete-bulk-window-shutter-calculation-quote-item");
                }
            });
        }
    });


    $(document).on("click", ".window-shutter-calculation-bulk-copy-bttn", function () {

        var this_bttn = $(this);
        var this_parent = this_bttn.parents(".window-shutter-calculation-quote-items-wrapper");
        var quote_item_codes = this_parent.find(".checkbox-input:checked");
        var datas = quote_item_codes.serialize() + '&cid=' + cid;

        console.log(datas);

        if (datas) {

            $.ajax({
                url: "window-shutter-calculation/scripts/copy-bulk-window-shutter-calculation-quote-items.php",
                type: 'POST',
                dataType: 'json',
                data: datas,
                beforeSend: function () {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                },
                success: function (data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {
                        $.each(data[1], function (old_code, new_code) {

                            var calculation_form = $("#calculation-form-" + old_code).clone();

                            calculation_form.attr("id", "calculation-form-" + new_code);
                            calculation_form.find(".window-shutter-calculation-quote-item-code").val(new_code);
                            calculation_form.find(".checkbox-label").attr("for", "checkbox-" + new_code + "");
                            calculation_form.find(".checkbox-input").attr("id", "checkbox-" + new_code).val(new_code);
                            calculation_form.find(".checkbox-input").button();
                            calculation_form.find(".window-shutter-calculation-table-more").attr("id", "calculation-table-more-" + new_code).hide();
                            calculation_form.find(".window-shutter-calculation-table .thead").empty();

                            $(calculation_form).appendTo(this_parent.find('.window-shutter-calculation-quote-items-body')).queue(function () {
                                var this_element = $(this);
                                var old_form = $("#calculation-form-" + old_code);
                                $.each(old_form.serializeArray(), function (i, form_field) {
                                    this_element.find(".data-field[name='" + form_field.name + "']").val(form_field.value);
                                });
                                this_element.dequeue();
                            }).clearQueue();
                        });

                        this_parent.find(".checkbox-label").each(function (i) {
                            $(this).find(".ui-button-text").html(i + 1);
                        });

                        this_bttn.prop('disabled', false).removeClass('field-loader');

                        window_shutter_calculation_group_discount(this_bttn);
                        //$('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        //$('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @delete-bulk-window-shutter-calculation-quote-item");
                }
            });
        }
    });

    $(document).on("click", ".delete-window-shutter-calculation-quote-item", function () {

        var this_bttn = $(this);
        var this_form = this_bttn.parents(".update-window-shutter-calculation-quote-item-form");
        var this_form = this_bttn.parents(".update-window-shutter-calculation-quote-item-form");
        var code = this_form.find(".window-shutter-calculation-quote-item-code").val();

        var this_parent = this_form.parents(".window-shutter-calculation-quote-items-wrapper");
        var table_header = this_parent.find(".window-shutter-calculation-table .thead .thead-tr").clone();

        $.ajax({
            url: "window-shutter-calculation/scripts/delete-window-shutter-calculation-quote-item.php",
            type: 'POST',
            dataType: 'json',
            data: {cid: cid, code: code},
            beforeSend: function () {
                this_bttn.prop('disabled', true).addClass('field-loader');
            },
            success: function (data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    this_form.hide();

                    this_parent.find(".window-shutter-calculation-table .thead").first().html(table_header);

                    this_bttn.prop('disabled', false).removeClass('field-loader');

                    window_shutter_calculation_group_discount(this_form);
                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    this_form.remove();

                    if (!this_parent.find('.update-window-shutter-calculation-quote-item-form').length) {
                        this_parent.hide().find(".bulk-action-button-wrapper").empty();
                    } else {
                        this_parent.find(".checkbox-label").each(function (i) {
                            $(this).find(".ui-button-text").html(i + 1);
                        });
                    }

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @delete-bulk-window-shutter-calculation-quote-item");
            }
        });
    });

    $(document).on("click", ".window-shutter-calculation-table-more-bttn", function () {
        var this_bttn = $(this);
        var this_form = this_bttn.parents("form");

        this_form.find(".window-shutter-calculation-table-more").toggle("slide", {direction: "up"}, "fast");
    });

    $(document).on("change", ".checkbox-input", function () {
        var this_checkbox = $(this);
        var this_table = this_checkbox.parents(".window-shutter-calculation-table");

        if (this_checkbox.is(':checked')) {
            this_table.find("select, .number-input, .text-field").addClass("ui-state-default");
        } else {
            this_table.find("select, .number-input, .text-field").removeClass("ui-state-default");
        }
    });

    $(document).on("change", ".add-window-shutter-calculation-quote-item-form .window-shutter-calculation-width,\n\
                                .add-window-shutter-calculation-quote-item-form .window-shutter-calculation-drop,\n\
                                .add-window-shutter-calculation-quote-item-form .window-shutter-calculation-type,\n\
                                .add-window-shutter-calculation-quote-item-form .window-shutter-calculation-field", function () {

        get_window_shutter_calculation_price(this);
    });

    function get_window_shutter_calculation_price(this_elelemnt) {

        var this_form = $(this_elelemnt).parents("form");
        var price_element = this_form.find(".window-shutter-calculation-price");
        var dyn_field = this_form.find(".window-shutter-calculation-field");

        var code = this_form.find(".window-shutter-calculation-code").val();
        var width = +this_form.find(".window-shutter-calculation-width").val();
        var drop = +this_form.find(".window-shutter-calculation-drop").val();
        var unit_price = +this_form.find(".window-shutter-calculation-type option:selected").attr("data-price");

        if (code !== "" && unit_price !== "" && width !== "" && drop !== "") {

            var price = 0;

            $.each(dyn_field, function () {
                var field_price = $(this).val().split("<->")[2];
                price += field_price > 0 ? +field_price : 0;
            });

            price += unit_price * width * drop / 1000 / 1000;
            var price_x = isNaN(price) ? "0.00" : price.toFixed(2);
            price_element.val(price_x);

        } else {
            price_element.val("");
        }
    }

    // Add new fields

    $(document).on("change", ".window-shutter-calculation-field", function (event) {
        var this_element = $(this);
        var this_form = this_element.parents("form");
        var add_field_option_element_parent = this_element.siblings(".window-shutter-calculation-add-field-option-wrapper");
        var add_field_option_element = add_field_option_element_parent.find(".window-shutter-calculation-add-field-option");
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        // If bulk Selected
        var this_chcekedbox = this_form.find(".checkbox-input");
        var checked_checkbox = this_form.parents(".window-shutter-calculation-quote-items-body").find(".checkbox-input:checked");

        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

            checked_checkbox.not(this_chcekedbox).each(function () {
                var this_form_x = $(this).parents(".update-window-shutter-calculation-quote-item-form");
                var data_fields_x = this_form_x.find(".data-field, .number-input, select, [type='submit']");
                if (this_element.val() === "add-new") {
                    data_fields_x.prop("disabled", true);
                }
            });
        }

        if (this_element.val() === "add-new") {
            this_element.hide();
            add_field_option_element_parent.show();
            add_field_option_element.focus();
            data_fields.prop("disabled", true);
        }
    });

    function window_shutter_calculation_add_field_option(field_option_name, this_elelemnt) {

        var this_element = $(this_elelemnt);
        var this_form = this_element.parents("form");
        var add_field_option_element_parent = this_element.parents(".window-shutter-calculation-add-field-option-wrapper");
        var add_field_option_element = add_field_option_element_parent.find(".window-shutter-calculation-add-field-option");
        var add_field_option_submit = add_field_option_element_parent.find(".window-shutter-calculation-add-field-option-submit");

        var field_element = add_field_option_element_parent.siblings(".window-shutter-calculation-field");
        var field_code = field_element.attr("data-code");
        var window_shutter_calculation_code = this_form.find(".window-shutter-calculation-code").val();
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        var field_option_name = field_option_name;

        if (field_option_name) {

            $.ajax({
                url: "window-shutter-calculation/scripts/add-window-shutter-calculation-field-option.php",
                type: "POST",
                dataType: "json",
                data: {field_code: field_code, field_option_name: field_option_name, window_shutter_calculation_code: window_shutter_calculation_code},
                beforeSend: function () {
                    add_field_option_element.prop('disabled', true).addClass('field-loader');
                    add_field_option_submit.prop('disabled', true).addClass('field-loader');
                },
                success: function (data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        $(".window-shutter-calculation-add-field-option-x[data-field-code=" + field_code + "]").before('<option value="' + data[2] + '<->' + data[1] + '<->0.00">' + data[2] + '</option>');

                        // If bulk Selected
                        var this_chcekedbox = this_form.find(".checkbox-input");
                        var checked_checkbox = this_form.parents(".window-shutter-calculation-quote-items-body").find(".checkbox-input:checked");

                        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

                            checked_checkbox.not(this_chcekedbox).each(function () {
                                var this_form_x = $(this).parents(".update-window-shutter-calculation-quote-item-form");
                                var data_fields_x = this_form_x.find(".data-field, .number-input, select, [type='submit']");
                                data_fields_x.prop("disabled", false);
                            });
                        }

                        add_field_option_element.val("");
                        add_field_option_element_parent.hide();
                        data_fields.prop("disabled", false);
                        field_element.show().val(data[2] + '<->' + data[1] + '<->0.00').change();
                    } else {
                        alert(data);
                    }
                    add_field_option_element.prop('disabled', false).removeClass('field-loader');
                    add_field_option_submit.prop('disabled', false).removeClass('field-loader');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    add_field_option_element.prop('disabled', false).removeClass('field-loader');
                    add_field_option_submit.prop('disabled', false).removeClass('field-loader');
                }
            });
        }
    }

    $(document).on("keypress", ".window-shutter-calculation-add-field-option", function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();

            var this_element = $(this);
            var add_field_option_element_parent = this_element.parents(".window-shutter-calculation-add-field-option-wrapper");
            var add_field_option_element = add_field_option_element_parent.find(".window-shutter-calculation-add-field-option");

            var field_option_name = add_field_option_element.val();

            window_shutter_calculation_add_field_option(field_option_name, this_element);
            return false;
        }
    });

    $(document).on("click", ".window-shutter-calculation-add-field-option-submit", function (event) {
        event.preventDefault();

        var this_element = $(this);
        var add_field_option_element = this_element.siblings(".window-shutter-calculation-add-field-option");

        var field_option_name = add_field_option_element.val();


        window_shutter_calculation_add_field_option(field_option_name, this_element);
        return false;
    });



    $(document).on("click", ".window-shutter-calculation-add-field-option-close", function () {
        var this_element = $(this);
        var this_form = this_element.parents("form");
        var add_field_option_element_parent = this_element.parents(".window-shutter-calculation-add-field-option-wrapper");
        var add_field_option_element = add_field_option_element_parent.find(".window-shutter-calculation-add-field-option");
        var field_element = add_field_option_element_parent.siblings(".window-shutter-calculation-field");
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        // If bulk Selected
        var this_chcekedbox = this_form.find(".checkbox-input");
        var checked_checkbox = this_form.parents(".window-shutter-calculation-quote-items-body").find(".checkbox-input:checked");

        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

            checked_checkbox.not(this_chcekedbox).each(function () {
                var this_form_x = $(this).parents(".update-window-shutter-calculation-quote-item-form");
                var data_fields_x = this_form_x.find(".data-field, .number-input, select, [type='submit']");
                data_fields_x.prop("disabled", false);
            });
        }

        add_field_option_element.val("");
        add_field_option_element_parent.hide();
        data_fields.prop("disabled", false);
        field_element.show().val("").change().focus();
    });

    function window_shutter_calculation_group_discount(this_element) {

        var result_parent = $(this_element).parents(".window-shutter-calculation-quote-items-wrapper");
        var quote_items_parent = result_parent.find(".window-shutter-calculation-quote-items-body");
        var price_field = quote_items_parent.find("input[name='price']:not(:hidden)");
        var group_discount = $.isNumeric(+result_parent.find(".group-discount").val()) ? +result_parent.find(".group-discount").val() : 0;

        var total_price = 0;
        price_field.each(function () {
            total_price += Number($(this).val().replace(/,/g, ''));
        });
        var group_discount_total = total_price - (total_price * group_discount / 100);


        result_parent.find(".group-total").val(total_price.toFixed(2)).end()
                .find(".group-discount").val(group_discount).end()
                .find(".group-discount-total").html(group_discount_total.toFixed(2)).end()
                .find(".group-discount-form").submit();

    }

    $(document).on("submit", ".add-window-shutter-calculation-quote-item-form", function (event) {
        event.preventDefault();

        var this_form = $(this);
        var this_submit_bttn = this_form.find('[type="submit"]');
        var this_table_thead_tr = this_form.find(".window-shutter-calculation-table .thead-tr");
        var this_table_tbody_tr = this_form.find(".window-shutter-calculation-table .tbody-tr");
        var window_shutter_calculation_code = this_form.find(".window-shutter-calculation-code").val();
        var accessory_option_array = this_form.find(".accessory-option-array").val();
        var per_meter_option_array = this_form.find(".per-meter-option-array").val();
        var fitting_charge_option_array = this_form.find(".fitting-charge-option-array").val();

        var result_parent = $("#window-shutter-calculation-quote-items-wrapper-" + window_shutter_calculation_code);

        var group_discount = $.isNumeric(+result_parent.find(".group-discount").val()) ? +result_parent.find(".group-discount").val() : 0;

        var datas = this_form.serialize();

        $.ajax({
            url: "window-shutter-calculation/scripts/add-window-shutter-calculation-quote-item.php",
            type: 'POST',
            dataType: 'json',
            data: datas,
            beforeSend: function (xhr) {
                this_submit_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function (data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_submit_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    $(this_form).find(".data-field:first").focus();

                    $.each(data[1], function (index, code) {

                        if (!$("#quote-items-body-" + window_shutter_calculation_code + " .window-shutter-calculation-table").length) {

                            var newe_table_head_tr = this_table_thead_tr.clone();
                            newe_table_head_tr.prepend('<th class="left-fixed-column">#</th>');
                            newe_table_head_tr.find(".right-fixed-column").html('<tr>\n\
                                                                                    <th>Price</th>\n\
                                                                                    <th>More</th>\n\
                                                                                    <th>Del</th>\n\
                                                                                </tr>');


                            if (etape === 'on') {
                                newe_table_head_tr.find(".etape-th").remove();
                            }

                            var newe_table_head_tr_x = newe_table_head_tr.html();
                            $("#window-shutter-calculation-quote-items-wrapper-" + window_shutter_calculation_code).show();
                        } else {
                            newe_table_head_tr_x = '';
                        }

                        var newe_table_body_tr = this_table_tbody_tr.clone();
                        newe_table_body_tr.prepend('<td class="left-fixed-column">\n\
                                                        <label class="checkbox-label" for="checkbox-' + code + '"></label>\n\
                                                        <input type="checkbox" id="checkbox-' + code + '" class="checkbox-input" name="quote-item-codes[]" value="' + code + '">\n\
                                                    </td>');
                        newe_table_body_tr.find(".right-fixed-column").html('<tr>\n\
                                                                                <td><input type="tel" name="price" data-column="price" class="window-shutter-calculation-price price-input data-field ui-state-default" autocomplete="off"></td>\n\
                                                                                <td><input type="button" class="window-shutter-calculation-table-more-bttn bttn-input" value="★"></td>\n\
                                                                                <td><input type="button" class="bttn-input delete-window-shutter-calculation-quote-item" value="✖"></td>\n\
                                                                            </tr>');

                        if (quote_status === '1') {
                            newe_table_body_tr.find(".approved-disabled").css({"pointer-events": "none", "background": "#f2f2f2"});
                        }
                        if (etape === 'on') {
                            newe_table_body_tr.find(".etape-td").remove();
                        }

                        if (accessory_option_array) {

                            var accessory_element = "";
                            var accessory_options = "";

                            $.each(JSON.parse(accessory_option_array), function (accessory_option_index, accessory_option_value) {
                                accessory_options += '<option data-code="' + accessory_option_value.code + '" value="' + accessory_option_value.code + '<->' + accessory_option_value.name + '<->' + accessory_option_value.price + '">' + accessory_option_value.name + ' | ' + accessory_option_value.price + '</option>';
                            });
                            accessory_element = '<span class="window-shutter-calculation-accessories-wrapper">\n\
                                                    <select class="window-shutter-calculation-accessories">\n\
                                                        <option value="">Accessories</option>\n\
                                                        ' + accessory_options + '\
                                                    </select>\n\
                                                    <input type="tel" class="add-window-shutter-calculation-accessory-qty qty-input" placeholder="Qty">\n\
                                                    <input type="button" class="add-window-shutter-calculation-accessories bttn-input" value="✚">\n\
                                                </span>';

                        } else {
                            accessory_element = '';
                        }

                        if (per_meter_option_array) {

                            var per_meter_element = "";
                            var per_meter_options = "";

                            $.each(JSON.parse(per_meter_option_array), function (per_meter_option_index, per_meter_option_value) {
                                per_meter_options += '<option data-code="' + per_meter_option_value.code + '" value="' + per_meter_option_value.code + '<->' + per_meter_option_value.name + '<->' + per_meter_option_value.price + '">' + per_meter_option_value.name + ' | ' + per_meter_option_value.price + '</option>';
                            });
                            per_meter_element = '<span class="window-shutter-calculation-per-meters-wrapper">\n\
                                                    <select class="window-shutter-calculation-per-meters">\n\
                                                        <option value="">Per Meters</option>\n\
                                                        ' + per_meter_options + '\
                                                    </select>\n\
                                                    <input type="tel" class="add-window-shutter-calculation-per-meter-width qty-input" placeholder="Width">\n\
                                                    <input type="button" class="add-window-shutter-calculation-per-meters bttn-input" value="✚">\n\
                                                </span>';

                        } else {
                            per_meter_element = '';
                        }

                        if (fitting_charge_option_array) {

                            var fitting_charge_element = "";
                            var fitting_charge_options = "";

                            $.each(JSON.parse(fitting_charge_option_array), function (fitting_charge_option_index, fitting_charge_option_value) {
                                fitting_charge_options += '<option data-code="' + fitting_charge_option_value.code + '" value="' + fitting_charge_option_value.code + '<->' + fitting_charge_option_value.name + '<->' + fitting_charge_option_value.price + '">' + fitting_charge_option_value.name + ' | ' + fitting_charge_option_value.price + '</option>';
                            });
                            fitting_charge_element = '<span class="window-shutter-calculation-fitting-charges-wrapper">\n\
                                                        <select class="window-shutter-calculation-fitting-charges">\n\
                                                            <option value="">Fitting Charges</option>\n\
                                                            ' + fitting_charge_options + '\
                                                        </select>\n\
                                                        <input type="button" class="add-window-shutter-calculation-fitting-charges bttn-input" value="✚">\n\
                                                    </span>';

                        } else {
                            fitting_charge_element = '';
                        }

                        $('<form id="calculation-form-' + code + '" class="update-window-shutter-calculation-quote-item-form" action="./" method="POST">\n\
                                <input type="hidden" name="cid" class="cid" value="' + cid + '">\n\
                                <input type="hidden" name="window-shutter-calculation-code" class="window-shutter-calculation-code" value="' + window_shutter_calculation_code + '">\n\
                                <input type="hidden" name="window-shutter-calculation-quote-item-code" class="window-shutter-calculation-quote-item-code" value="' + code + '">\n\
                                <table class="window-shutter-calculation-table">\n\
                                    <thead class="thead">\n\
                                        <tr class="thead-tr">' + newe_table_head_tr_x + '</tr>\n\
                                    </thead>\n\
                                    <tbody>\n\
                                        ' + newe_table_body_tr.html() + '\
                                    </tbody>\n\
                                </table>\n\
                                <table id="calculation-table-more-' + code + '"  class="window-shutter-calculation-table-more">\n\
                                    <tr>\n\
                                        <td class="table-more-notes" colspan="3">\n\
                                            <textarea name="notes" data-column="notes" class="window-shutter-calculation-notes data-field" placeholder="Notes"></textarea>\n\
                                        </td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="table-more-accessories">\n\
                                            ' + accessory_element + '\
                                        </td>\n\
                                        <td class="table-more-per-meters">\n\
                                            ' + per_meter_element + '\
                                        </td>\n\
                                        <td class="table-more-fitting-charges">\n\
                                            ' + fitting_charge_element + '\
                                        </td>\n\
                                    </tr>\n\
                                </table>\n\
                            </form>').appendTo("#quote-items-body-" + window_shutter_calculation_code).queue(function () {

                            var this_element = $(this);
                            $.each(this_form.serializeArray(), function (i, form_field) {
                                this_element.find("[name='" + form_field.name + "']").val(form_field.value);
                            });

                            this_element.find(".bttn-input, .checkbox-input").button();
                            this_element.dequeue();

                        }).clearQueue();
                    });
                    result_parent.find('.bulk-action-button-wrapper').html('<input type="button" class="bulk-action-button window-shutter-calculation-select-all-bttn" value="✔">\n\
                                                                            <input type="button" class="bulk-action-button window-shutter-calculation-bulk-delete-bttn" value="Delete">\n\
                                                                            <input type="button" class="bulk-action-button window-shutter-calculation-bulk-copy-bttn" value="Copy">\n\
                                                                            <a href="print/order-sheet-window-shutter-calculation-' + window_shutter_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 145px;">Print Order Sheet</a>\n\
                                                                            <a href="https://file-gen.blinq.com.au/clients/' + user_account + '/order-sheet-xlsx-window-shutter-calculation-' + window_shutter_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 175px;">Print Order Sheet Xlsx</a>\n\
                                                                            <form class="group-discount-form" style="float: right; margin-right: -6px; font-size: 12px;">\n\
                                                                                <input type="tel" class="group-total price-input ui-state-default" autocomplete="off" style="float: left;" disabled value="">\n\
                                                                                <input type="tel" class="group-discount price-input ui-state-default" autocomplete="off" placeholder="%" value="' + group_discount + '" style="float: left; margin-left: 1px !important; margin-right: 1px !important; text-align: center; width: 33px;" required value="">\n\
                                                                                <input type="hidden" class="window-shutter-calculation-code" value="' + window_shutter_calculation_code + '">\n\
                                                                                <input type="submit" class="bttn-input ui-button ui-widget ui-state-default ui-corner-all" value="✔" role="button" aria-disabled="false" style="float: left;">\n\
                                                                            </form>\n\
                                                                            <div class="group-discount-total" style="float: right; line-height: 40px; margin-right: 400px; font-size: 22px; font-weight: bold;"></div>\n\
                                                                        ').find(".bulk-action-button").button();
                    ;

                    result_parent.find(".checkbox-label").each(function (i) {
                        $(this).find(".ui-button-text").html(i + 1);
                    });

                    this_form[0].reset();
                    this_submit_bttn.prop('disabled', false).removeClass('field-loader');

                    window_shutter_calculation_group_discount(result_parent.find(".group-discount-form"));

                    //$('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    //$('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_submit_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                this_submit_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @add-window-shutter-calculation-quote-item");
            }
        });
    });

    $(document).on("change", ".update-window-shutter-calculation-quote-item-form .data-field", function (event) {
        event.preventDefault();

        var this_field = $(this);
        var this_field_name = this_field.attr("name");
        var this_field_value = this_field.val();

        if (this_field_value === "add-new") {
            return false;
            // Prevent this function to add new Option
        }

        var this_form = this_field.parents(".update-window-shutter-calculation-quote-item-form");
        var this_chcekedbox = this_form.find(".checkbox-input");
        var checked_checkbox = this_form.parents(".window-shutter-calculation-quote-items-body").find(".checkbox-input:checked");

        var quote_items_array = "";

        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

            quote_items_array = [];

            checked_checkbox.each(function () {
                var this_form_x = $(this).parents(".update-window-shutter-calculation-quote-item-form");
                var this_field_x = this_form_x.find("[name='" + this_field_name + "']");

                this_field_x.val(this_field_value);

                if (this_field_x.attr("name") === "width" || this_field_x.attr("name") === "drop" || this_field_x.attr("name") === "type" || this_field_x.hasClass('window-shutter-calculation-field')) {
                    if (quote_status !== '1') {
                        get_window_shutter_calculation_price(this_field_x);
                    }
                }
                quote_items_array.push(this_form_x.serialize());
            });

        } else {
            if (this_field.attr("name") === "width" || this_field.attr("name") === "drop" || this_field.attr("name") === "type" || this_field.hasClass('window-shutter-calculation-field')) {
                if (quote_status !== '1') {
                    get_window_shutter_calculation_price(this_field);
                }
            }
            quote_items_array = [this_form.serialize()];
        }

        $.ajax({
            url: "window-shutter-calculation/scripts/update-window-shutter-calculation-quote-item.php",
            type: 'POST',
            dataType: 'json',
            data: {quote_items_array: quote_items_array},
            beforeSend: function () {
                this_field.addClass('field-loader');
            },
            success: function (data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_field.removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    if (this_chcekedbox.is(":checked") && checked_checkbox.length) {
                        checked_checkbox.each(function () {
                            var this_form_x = $(this).parents(".update-window-shutter-calculation-quote-item-form");

                            var note_length = this_form_x.find('.window-shutter-calculation-notes').val().length;
                            var accessory_length = this_form_x.find('.window-shutter-calculation-accessory').length;
                            var per_meter_length = this_form_x.find('.window-shutter-calculation-per-meter').length;
                            var fitting_charge_length = this_form_x.find('.window-shutter-calculation-fitting-charge').length;

                            if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                this_form_x.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                            } else {
                                this_form_x.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                            }
                        });

                    } else {

                        var note_length = this_form.find('.window-shutter-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                        var per_meter_length = this_form.find('.window-shutter-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                        }
                    }

                    this_field.removeClass('field-loader');

                    if (this_field.attr("name") === "width" || this_field.attr("name") === "drop" || this_field.attr("name") === "type" || this_field.attr("name") === "price" || this_field.hasClass('window-shutter-calculation-field')) {
                        window_shutter_calculation_group_discount(this_form);
                    } else {
                        //$('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        //$('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");
                    }

                } else {
                    this_field.removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this_field.removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @update-window-shutter-calculation-quote-item");
            }
        });
    });

    $(document).on("keypress", ".add-window-shutter-calculation-accessory-qty", function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var this_element = $(this);
            var add_bttn = this_element.siblings('.add-window-shutter-calculation-accessories');
            add_bttn.click();
        }
    });

    $(document).on("click", ".add-window-shutter-calculation-accessories", function () {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-shutter-calculation-quote-item-form');
        var accessory_element = this_bttn.siblings('.window-shutter-calculation-accessories');
        var accessory_qty_element = this_bttn.siblings('.add-window-shutter-calculation-accessory-qty');

        var window_shutter_calculation_code = this_form.find('.window-shutter-calculation-code').val();
        var window_shutter_calculation_quote_item_code = this_form.find('.window-shutter-calculation-quote-item-code').val();
        var accessory = accessory_element.val();
        var accessory_qty = +accessory_qty_element.val();
        accessory_qty = accessory_qty !== 0 ? accessory_qty : 1;

        if (accessory) {

            $.ajax({
                url: "window-shutter-calculation/scripts/add-window-shutter-calculation-quote-item-accessory.php",
                type: 'POST',
                dataType: 'json',
                data: {cid: cid, window_shutter_calculation_code: window_shutter_calculation_code, window_shutter_calculation_quote_item_code: window_shutter_calculation_quote_item_code, accessory: accessory, accessory_qty: accessory_qty},
                beforeSend: function () {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                    accessory_element.addClass('field-loader');
                    accessory_qty_element.addClass('field-loader');
                },
                success: function (data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        accessory_element.removeClass('field-loader');
                        accessory_qty_element.removeClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        var accessory_code = accessory.split("<->")[0];
                        var accessory_name = accessory.split("<->")[1];
                        var accessory_price = +accessory.split("<->")[2].replace(/,/g, '');
                        var accessory_total = (accessory_price * accessory_qty).toFixed(2);

                        this_form.find(".table-more-accessories").append('<span class="window-shutter-calculation-accessories-wrapper">\n\
                                                                                <input type="text" class="window-shutter-calculation-accessory" readonly disabled value="' + accessory_name + ' | ' + accessory_price + ' x ' + accessory_qty + ' = ' + accessory_total + '">\n\
                                                                                <input type="button" class="delete-window-shutter-calculation-accessory bttn-input" data-code="' + accessory_code + '" value="✖">\n\
                                                                            </span>').find(".bttn-input").button();

                        accessory_element.find('option[data-code="' + accessory_code + '"]').hide();
                        accessory_element.val("").focus();
                        accessory_qty_element.val("");

                        var note_length = this_form.find('.window-shutter-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                        var per_meter_length = this_form.find('.window-shutter-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                        }

                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        accessory_element.removeClass('field-loader');
                        accessory_qty_element.removeClass('field-loader');

                        $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        accessory_element.removeClass('field-loader');
                        accessory_qty_element.removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    accessory_element.removeClass('field-loader');
                    accessory_qty_element.removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @add-window-shutter-calculation-quote-item-accessory");
                }
            });
        }
    });

    $(document).on("click", ".delete-window-shutter-calculation-accessory", function () {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-shutter-calculation-quote-item-form');
        var accessory_element = this_form.find('.window-shutter-calculation-accessories');
        var this_wrapper = this_bttn.parent('.window-shutter-calculation-accessories-wrapper');

        var window_shutter_calculation_code = this_form.find('.window-shutter-calculation-code').val();
        var window_shutter_calculation_quote_item_code = this_form.find('.window-shutter-calculation-quote-item-code').val();
        var accessory_code = this_bttn.attr("data-code");

        $.ajax({
            url: "window-shutter-calculation/scripts/delete-window-shutter-calculation-quote-item-accessory.php",
            type: 'POST',
            dataType: 'json',
            data: {cid: cid, window_shutter_calculation_code: window_shutter_calculation_code, window_shutter_calculation_quote_item_code: window_shutter_calculation_quote_item_code, accessory_code: accessory_code},
            beforeSend: function () {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function (data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    this_wrapper.remove();
                    accessory_element.find('option[data-code="' + accessory_code + '"]').show();
                    accessory_element.val("");

                    var note_length = this_form.find('.window-shutter-calculation-notes').val().length;
                    var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                    var per_meter_length = this_form.find('.window-shutter-calculation-per-meter').length;
                    var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                    if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                        this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                    } else {
                        this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                    }

                    this_bttn.prop('disabled', false).removeClass('field-loader');

                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @delete-window-shutter-calculation-quote-item-accessory");
            }
        });
    });

    $(document).on("keypress", ".add-window-shutter-calculation-per-meter-width", function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var this_element = $(this);
            var add_bttn = this_element.siblings('.add-window-shutter-calculation-per-meters');
            add_bttn.click();
        }
    });

    $(document).on("click", ".add-window-shutter-calculation-per-meters", function () {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-shutter-calculation-quote-item-form');
        var per_meter_element = this_bttn.siblings('.window-shutter-calculation-per-meters');
        var per_meter_width_element = this_bttn.siblings('.add-window-shutter-calculation-per-meter-width');

        var window_shutter_calculation_code = this_form.find('.window-shutter-calculation-code').val();
        var window_shutter_calculation_quote_item_code = this_form.find('.window-shutter-calculation-quote-item-code').val();
        var per_meter = per_meter_element.val();
        var per_meter_width = +per_meter_width_element.val();
        per_meter_width = per_meter_width !== 0 ? per_meter_width : 1;

        if (per_meter) {

            $.ajax({
                url: "window-shutter-calculation/scripts/add-window-shutter-calculation-quote-item-per-meter.php",
                type: 'POST',
                dataType: 'json',
                data: {cid: cid, window_shutter_calculation_code: window_shutter_calculation_code, window_shutter_calculation_quote_item_code: window_shutter_calculation_quote_item_code, per_meter: per_meter, per_meter_width: per_meter_width},
                beforeSend: function () {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                    per_meter_element.addClass('field-loader');
                    per_meter_width_element.addClass('field-loader');
                },
                success: function (data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        per_meter_element.removeClass('field-loader');
                        per_meter_width_element.removeClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        var per_meter_code = per_meter.split("<->")[0];
                        var per_meter_name = per_meter.split("<->")[1];
                        var per_meter_price = +per_meter.split("<->")[2].replace(/,/g, '');
                        var per_meter_total = (per_meter_price * per_meter_width).toFixed(2);

                        this_form.find(".table-more-per-meters").append('<span class="window-shutter-calculation-per-meters-wrapper">\n\
                                                                                <input type="text" class="window-shutter-calculation-per-meter" readonly disabled value="' + per_meter_name + ' | ' + per_meter_price + ' x ' + per_meter_width + ' = ' + per_meter_total + '">\n\
                                                                                <input type="button" class="delete-window-shutter-calculation-per-meter bttn-input" data-code="' + per_meter_code + '" value="✖">\n\
                                                                            </span>').find(".bttn-input").button();

                        per_meter_element.find('option[data-code="' + per_meter_code + '"]').hide();
                        per_meter_element.val("").focus();
                        per_meter_width_element.val("");

                        var note_length = this_form.find('.window-shutter-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                        var per_meter_length = this_form.find('.window-shutter-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                        }

                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        per_meter_element.removeClass('field-loader');
                        per_meter_width_element.removeClass('field-loader');

                        $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        per_meter_element.removeClass('field-loader');
                        per_meter_width_element.removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    per_meter_element.removeClass('field-loader');
                    per_meter_width_element.removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @add-window-shutter-calculation-quote-item-per-meter");
                }
            });
        }
    });

    $(document).on("click", ".delete-window-shutter-calculation-per-meter", function () {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-shutter-calculation-quote-item-form');
        var per_meter_element = this_form.find('.window-shutter-calculation-per-meters');
        var this_wrapper = this_bttn.parent('.window-shutter-calculation-per-meters-wrapper');

        var window_shutter_calculation_code = this_form.find('.window-shutter-calculation-code').val();
        var window_shutter_calculation_quote_item_code = this_form.find('.window-shutter-calculation-quote-item-code').val();
        var per_meter_code = this_bttn.attr("data-code");

        $.ajax({
            url: "window-shutter-calculation/scripts/delete-window-shutter-calculation-quote-item-per-meter.php",
            type: 'POST',
            dataType: 'json',
            data: {cid: cid, window_shutter_calculation_code: window_shutter_calculation_code, window_shutter_calculation_quote_item_code: window_shutter_calculation_quote_item_code, per_meter_code: per_meter_code},
            beforeSend: function () {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function (data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    this_wrapper.remove();
                    per_meter_element.find('option[data-code="' + per_meter_code + '"]').show();
                    per_meter_element.val("");

                    var note_length = this_form.find('.window-shutter-calculation-notes').val().length;
                    var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                    var per_meter_length = this_form.find('.window-shutter-calculation-per-meter').length;
                    var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                    if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                        this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                    } else {
                        this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                    }

                    this_bttn.prop('disabled', false).removeClass('field-loader');

                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @delete-window-shutter-calculation-quote-item-per-meter");
            }
        });
    });

    $(document).on("click", ".add-window-shutter-calculation-fitting-charges", function () {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-shutter-calculation-quote-item-form');
        var fitting_charge_element = this_bttn.siblings('.window-shutter-calculation-fitting-charges');

        var window_shutter_calculation_code = this_form.find('.window-shutter-calculation-code').val();
        var window_shutter_calculation_quote_item_code = this_form.find('.window-shutter-calculation-quote-item-code').val();
        var fitting_charge = fitting_charge_element.val();

        if (fitting_charge) {

            $.ajax({
                url: "window-shutter-calculation/scripts/add-window-shutter-calculation-quote-item-fitting-charge.php",
                type: 'POST',
                dataType: 'json',
                data: {cid: cid, window_shutter_calculation_code: window_shutter_calculation_code, window_shutter_calculation_quote_item_code: window_shutter_calculation_quote_item_code, fitting_charge: fitting_charge},
                beforeSend: function () {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                    fitting_charge_element.toggleClass('field-loader');
                },
                success: function (data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        fitting_charge_element.toggleClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        var fitting_charge_code = fitting_charge.split("<->")[0];
                        var fitting_charge_name = fitting_charge.split("<->")[1];
                        var fitting_charge_price = fitting_charge.split("<->")[2];

                        this_form.find(".table-more-fitting-charges").append('<span class="window-shutter-calculation-fitting-charges-wrapper">\n\
                                                                                    <input type="text" class="window-shutter-calculation-fitting-charge" readonly disabled value="' + fitting_charge_name + ' | ' + fitting_charge_price + '">\n\
                                                                                    <input type="button" class="delete-window-shutter-calculation-fitting-charge bttn-input" data-code="' + fitting_charge_code + '" value="✖">\n\
                                                                                </span>').find(".bttn-input").button();

                        fitting_charge_element.find('option[data-code="' + fitting_charge_code + '"]').hide();
                        fitting_charge_element.val("");

                        var note_length = this_form.find('.window-shutter-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                        var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                        if (note_length || accessory_length || fitting_charge_length) {
                            this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                            ;
                        }

                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        fitting_charge_element.toggleClass('field-loader');

                        $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        fitting_charge_element.toggleClass('field-loader');
                        alert(JSON.stringify(data));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    fitting_charge_element.toggleClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @add-window-shutter-calculation-quote-item-fitting-charge");
                }
            });
        }
    });

    $(document).on("click", ".delete-window-shutter-calculation-fitting-charge", function () {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-shutter-calculation-quote-item-form');
        var fitting_charge_element = this_form.find('.window-shutter-calculation-fitting-charges');
        var this_wrapper = this_bttn.parent('.window-shutter-calculation-fitting-charges-wrapper');

        var window_shutter_calculation_code = this_form.find('.window-shutter-calculation-code').val();
        var window_shutter_calculation_quote_item_code = this_form.find('.window-shutter-calculation-quote-item-code').val();
        var fitting_charge_code = this_bttn.attr("data-code");

        $.ajax({
            url: "window-shutter-calculation/scripts/delete-window-shutter-calculation-quote-item-fitting-charge.php",
            type: 'POST',
            dataType: 'json',
            data: {cid: cid, window_shutter_calculation_code: window_shutter_calculation_code, window_shutter_calculation_quote_item_code: window_shutter_calculation_quote_item_code, fitting_charge_code: fitting_charge_code},
            beforeSend: function () {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function (data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    this_wrapper.remove();
                    fitting_charge_element.find('option[data-code="' + fitting_charge_code + '"]').show();
                    fitting_charge_element.val("");

                    var note_length = this_form.find('.window-shutter-calculation-notes').val().length;
                    var accessory_length = this_form.find('.window-shutter-calculation-accessory').length;
                    var fitting_charge_length = this_form.find('.window-shutter-calculation-fitting-charge').length;

                    if (note_length || accessory_length || fitting_charge_length) {
                        this_form.find('.window-shutter-calculation-table-more-bttn').addClass("red");
                    } else {
                        this_form.find('.window-shutter-calculation-table-more-bttn').removeClass("red");
                        ;
                    }

                    this_bttn.prop('disabled', false).removeClass('field-loader');

                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @delete-window-shutter-calculation-quote-item-fitting-charge");
            }
        });
    });

    $(document).on("submit", "#window-shutter-calculation-quote-items-results .group-discount-form", function (event) {
        event.preventDefault();

        var this_form = $(this);
        var this_bttn = $(this).find("input[type='submit']");
        var discount = isNaN(this_form.find(".group-discount").val()) ? '0' : this_form.find(".group-discount").val();
        var total = isNaN(this_form.find(".group-total").val()) ? '0' : this_form.find(".group-total").val();
        var window_shutter_calculation_code = this_form.find(".window-shutter-calculation-code").val();


        var group_discount_total = (total - (total * discount / 100)).toFixed(2);

        $.ajax({
            url: "window-shutter-calculation/scripts/update-window-shutter-calculation-group-discount.php",
            type: 'POST',
            dataType: 'json',
            data: {cid: cid, discount: discount, window_shutter_calculation_code: window_shutter_calculation_code},
            beforeSend: function () {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function (data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    this_form.siblings(".group-discount-total").html(group_discount_total);
                    this_bttn.prop('disabled', false).removeClass('field-loader');

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }

                $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @update-window-shutter-calculation-group-discount");
            }
        });

    });
});