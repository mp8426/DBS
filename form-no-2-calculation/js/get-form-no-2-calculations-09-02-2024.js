$(function () {

    $.ajax({
        url: "form-no-2-calculation/scripts/get-form-no-2-calculations.php",
        type: "POST",
        dataType: "json",
        data: { cid: cid },
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

                            var form_no_2_calculation_code = value.code;

                            if (value.location_select === 1) {
                                var location_header = '<th>Locations</th>';
                                var location_options = "";
                                $.each(data[2], function (location_index, location_value) {
                                    location_options += '<option value="' + location_value.name + '<->' + location_value.code + '">' + location_value.name + '</option>';
                                });
                                var location_element = '<td>\n\
                                                    <select name="location" data-column="location" class="form-no-2-calculation-location data-field">\n\
                                                        <option value=""></option>\n\
                                                        ' + location_options + '\
                                                    </select>\n\
                                                </td>';
                            } else {
                                location_header = "";
                                location_options = "";
                                location_element = "";
                            }

                            if (value.cost_select === 1) {
                                var cost_header = '<th>Cost</th>';
                                var cost_element = '<td>\n\
                                                <input type="tel" name="cost" data-column="cost" class="form-no-2-calculation-cost number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="">\n\
                                            </td>';
                            } else {
                                cost_header = "";
                                cost_element = "";
                            }

                            if (value.markup_select === 1) {
                                var markup_header = '<th>Markup</th>';
                                var markup_element = '<td>\n\
                                                <input type="tel" name="markup" data-column="markup" class="form-no-2-calculation-markup number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="%">\n\
                                            </td>';
                            } else {
                                markup_header = "";
                                markup_element = "";
                            }

                            if (value.dynamic_field_qty_select === 1) {
                                var dynamic_field_qty_header = '<th>Qty</th>';
                                var dynamic_field_qty_element = '<td>\n\
                                                <input type="tel" name="dynamic_field_qty" data-column="dynamic_field_qty" class="form-no-2-calculation-dynamic-field-qty number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="Qty">\n\
                                            </td>';
                            } else {
                                dynamic_field_qty_header = "";
                                dynamic_field_qty_element = "";
                            }

                            var field_header_left = "",
                                field_element_left = "";
                            var field_header_right = "",
                                field_element_right = "";
                            $.each(value.fields, function (field_index, field_value) {

                                if (field_value.side === 1) { //left side

                                    field_header_left += '<th>' + field_value.name + '</th>';

                                    if (field_value.field_type === 0) {

                                        var sub_option_btn = '';
                                        var sub_option_class = '';
                                        var sub_option_length = 0;

                                        var field_options = "";

                                        $.each(field_value.options, function (field_option_index, field_option_value) {
                                            var price_tag = field_option_value.price > 0 ? ' | ' + field_option_value.price : '';
                                            field_options += '<option value="' + field_option_value.name + '<->' + field_option_value.code + '<->' + field_option_value.price + '" data-price-type="' + field_option_value.price_type + '" data-sub-options=\'' + field_option_value.sub_options + '\'>' + field_option_value.name + price_tag + '</option>';

                                            var sub_options_obj = JSON.parse(field_option_value.sub_options);
                                            sub_option_length += sub_options_obj !== '' && sub_options_obj !== undefined && sub_options_obj !== null ? Number(Object.keys(sub_options_obj).length) : 0;
                                        });
                                        field_options += '<option class="form-no-2-calculation-add-field-option-x" data-field-code="' + field_value.code + '" value="add-new">Add New</option>';

                                        if (sub_option_length) {
                                            sub_option_class = 'have-sub-options';
                                            sub_option_btn = '<button type="button" class="comboTree-btn">☳</button>';
                                        }

                                        field_element_left += '<td>\n\
                                                        <select name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="form-no-2-calculation-field data-field ' + sub_option_class + '">\n\
                                                            <option value=""></option>\n\
                                                            ' + field_options + '\
                                                        </select>\n\
                                                        ' + sub_option_btn + '\
                                                        <span class="form-no-2-calculation-add-field-option-wrapper">\n\
                                                            <input type="text" class="form-no-2-calculation-add-field-option text-input" placeholder="Option Name">\n\
                                                            <input type="number" steps="0.01"  class="form-no-2-calculation-add-field-option-price text-input" placeholder="Price">\n\
                                                            <input type="button" class="form-no-2-calculation-add-field-option-submit bttn-input" value="✚">\n\
                                                            <input type="button" class="form-no-2-calculation-add-field-option-close bttn-input" value="✖">\n\
                                                        </span>\n\
                                                    </td>';
                                    } else {
                                        field_element_left += '<td>\n\
                                                        <input type="text" name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="form-no-2-calculation-field data-field text-field" autocomplete="off">\n\
                                                    </td>';
                                    }
                                }

                                if (field_value.side === 0) { //right side

                                    field_header_right += '<th>' + field_value.name + '</th>';

                                    if (field_value.field_type === 0) {

                                        var sub_option_btn = '';
                                        var sub_option_class = '';
                                        var sub_option_length = 0;

                                        var field_options = "";
                                        $.each(field_value.options, function (field_option_index, field_option_value) {
                                            var price_tag = field_option_value.price > 0 ? ' | ' + field_option_value.price : '';
                                            field_options += '<option value="' + field_option_value.name + '<->' + field_option_value.code + '<->' + field_option_value.price + '" data-price-type="' + field_option_value.price_type + '" data-sub-options=\'' + field_option_value.sub_options + '\'>' + field_option_value.name + price_tag + '</option>';

                                            var sub_options_obj = JSON.parse(field_option_value.sub_options);
                                            sub_option_length += sub_options_obj !== '' && sub_options_obj !== undefined && sub_options_obj !== null ? Number(Object.keys(sub_options_obj).length) : 0;
                                        });
                                        field_options += '<option class="form-no-2-calculation-add-field-option-x" data-field-code="' + field_value.code + '" value="add-new">Add New</option>';

                                        if (sub_option_length) {
                                            sub_option_class = 'have-sub-options';
                                            sub_option_btn = '<button type="button" class="comboTree-btn">☳</button>';
                                        }

                                        field_element_right += '<td>\n\
                                                            <select name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="form-no-2-calculation-field data-field ' + sub_option_class + '">\n\
                                                                <option value=""></option>\n\
                                                                ' + field_options + '\
                                                            </select>\n\
                                                            ' + sub_option_btn + '\
                                                            <span class="form-no-2-calculation-add-field-option-wrapper">\n\
                                                                <input type="text" class="form-no-2-calculation-add-field-option text-input" placeholder="Option Name">\n\
                                                                <input type="number" steps="0.01" class="form-no-2-calculation-add-field-option-price text-input" placeholder="Price">\n\
                                                                <input type="button" class="form-no-2-calculation-add-field-option-submit bttn-input" value="✚">\n\
                                                                <input type="button" class="form-no-2-calculation-add-field-option-close bttn-input" value="✖">\n\
                                                            </span>\n\
                                                        </td>';
                                    } else {
                                        field_element_right += '<td>\n\
                                                            <input type="text" name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-column="field-' + field_value.code + '" class="form-no-2-calculation-field data-field text-field" autocomplete="off">\n\
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
                                accessory_element = '<span class="form-no-2-calculation-accessories-wrapper">\n\
                                                <select class="form-no-2-calculation-accessories">\n\
                                                    <option value="">Accessories</option>\n\
                                                    ' + accessory_options + '\
                                                </select>\n\
                                                <input type="tel" class="add-form-no-2-calculation-accessory-qty qty-input" placeholder="Qty">\n\
                                                <input type="button" class="add-form-no-2-calculation-accessories bttn-input" value="✚">\n\
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
                                per_meter_element = '<span class="form-no-2-calculation-accessories-wrapper">\n\
                                                <select class="form-no-2-calculation-per-meters">\n\
                                                    <option value="">Per Meters</option>\n\
                                                    ' + per_meter_options + '\
                                                </select>\n\
                                                <input type="tel" class="add-form-no-2-calculation-per-meter-width qty-input" placeholder="Width">\n\
                                                <input type="button" class="add-form-no-2-calculation-per-meters bttn-input" value="✚">\n\
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
                                fitting_charge_element = '<span class="form-no-2-calculation-fitting-charges-wrapper">\n\
                                                    <select class="form-no-2-calculation-fitting-charges">\n\
                                                        <option value="">Fitting Charges</option>\n\
                                                        ' + fitting_charge_options + '\
                                                    </select>\n\
                                                    <input type="button" class="add-form-no-2-calculation-fitting-charges bttn-input" value="✚">\n\
                                                </span>';

                            } else {
                                fitting_charge_element = '';
                                fitting_charge_option_array = '';
                            }

                            if (value.qty_select === 1) {

                                var qty_class = 'qty_on';

                                var add_qty_header = "<th>Price</th><th>Qty</th><th>Total</th>";
                                var add_qty_element = '<td><input type="tel" name="price" class="form-no-2-calculation-price price-input data-field" autocomplete="off"></td>' +
                                    '<td><input type="number" name="qty" class="form-no-2-calculation-qty number-input" autocomplete="off" min="1" value="1"></td><td><input type="number" name="total" class="form-no-2-calculation-total number-input" autocomplete="off" min="1" value="" readonly><input type="hidden" class="data-field" name="qty_field" value="on"></td>';

                                var row_qty_header = "<th>Price</th><th>Qty</th><th>Total</th>";
                                var row_qty_element = '<td><input type="tel" name="price" class="form-no-2-calculation-price price-input data-field" autocomplete="off"></td><td><input type="number" name="qty" class="form-no-2-calculation-qty number-input data-field" autocomplete="off" min="1" value="1"></td><td><input type="number" name="total" class="form-no-2-calculation-total number-input ui-state-default" autocomplete="off" min="1" value="" readonly><input type="hidden" class="data-field" name="qty_field" value="on"></td>';

                            } else {

                                qty_class = '';

                                add_qty_header = "<th>Price</th><th>Qty</th>";
                                add_qty_element = '<td><input type="tel" name="price" class="form-no-2-calculation-price price-input data-field" style="width: calc(100% - 12px);" autocomplete="off"></td><td><input type="number" name="qty" class="form-no-2-calculation-qty number-input" autocomplete="off" min="1" value="1" style="min-width: 0;"><input type="hidden" name="total" class="form-no-2-calculation-total number-input" autocomplete="off" min="1" value=""><input type="hidden" class="data-field" name="qty_field" value="off"></td>';

                                row_qty_header = "<th>Price</th>";
                                row_qty_element = '<td><input type="tel" name="price" data-column="price" class="form-no-2-calculation-price price-input data-field ui-state-default" autocomplete="off" value=""><input type="hidden" name="qty" class="form-no-2-calculation-qty number-input data-field" autocomplete="off" min="1" value="1"><input type="hidden" name="total" data-column="total" class="form-no-2-calculation-total price-input data-field" autocomplete="off" value="0" readonly><input type="hidden" class="data-field" name="qty_field" value="off"></td>';
                            }

                            if (value.status === 1) {

                                $('<li><a href="#tab-' + value.code + '">' + value.name + '</a></li>').prependTo("#form-no-2-calculation-tabs .tabs ul");
                                $('<div id="tab-' + value.code + '" class="form-no-2-calculation-tab-div"></div>')
                                    .append($('<form class="add-form-no-2-calculation-quote-item-form ' + qty_class + '" action="./" method="POST"></form>')
                                        .append('<input type="hidden" name="calc-name" class="calc-name" value="form_no_2">')
                                        .append('<input type="hidden" class="cid" name="cid" value="' + cid + '">')
                                        .append('<input type="hidden" class="form-no-2-calculation-code" name="form-no-2-calculation-code" value="' + form_no_2_calculation_code + '">')
                                        .append($('<table class="form-no-2-calculation-table"></table>')
                                            .append($('<thead class="thead"></thead>')
                                                .append($('<tr class="thead-tr"></tr>')
                                                    .append(field_header_left)
                                                    .append(location_header)
                                                    .append(field_header_right)
                                                    .append(cost_header)
                                                    .append(markup_header)
                                                    .append(dynamic_field_qty_header)
                                                    // .append('<th>Cost</th>')
                                                    // .append('<th>Markup</th>')
                                                    .append('<th>\n\
                                                        <table class="right-fixed-column">\n\
                                                            <tr>\n\
                                                            '+ add_qty_header + '<th>Add</th>\n\
                                                            </tr>\n\
                                                        </table>\n\
                                                    </th>')
                                                )
                                            )
                                            .append($('<tbody></tbody>')
                                                .append($('<tr class="tbody-tr"></tr>')
                                                    .append(field_element_left)
                                                    .append(location_element)
                                                    .append(field_element_right)
                                                    .append(cost_element)
                                                    .append(markup_element)
                                                    .append(dynamic_field_qty_element)
                                                    // .append('<td><input type="tel" name="cost" data-column="cost" class="form-no-2-calculation-cost number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder=""></td>')
                                                    // .append('<td><input type="tel" name="markup" data-column="markup" class="form-no-2-calculation-markup number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="%"></td>')
                                                    .append('<td>\n\
                                                        <table class="right-fixed-column" style="margin-top: -20px;">\n\
                                                            <tr>\n\
                                                            '+ add_qty_element +
                                                        '<td><button type="submit" class="bttn-input"><i class="fa fa-plus" aria-hidden="true"></i></button></td>\n\
                                                            </tr>\n\
                                                        </table>\n\
                                                    </td>')
                                                )
                                            )
                                        )
                                        .append(accessory_option_array)
                                        .append(per_meter_option_array)
                                        .append(fitting_charge_option_array)
                                    ).prependTo("#form-no-2-calculation-tabs .calculation-results").find(".bttn-input").button();
                            }
                            // End of prepend Calculation methods

                            if (JSON.stringify(value.quote_items) !== '[]') {

                                $('<div id="form-no-2-calculation-quote-items-wrapper-' + form_no_2_calculation_code + '" class="form-no-2-calculation-quote-items-wrapper"></div>')
                                    .append('<h2 class="form-no-2-calculation-header">' + value.name + '</h2>')
                                    .append('<div id="quote-items-body-' + form_no_2_calculation_code + '" class="form-no-2-calculation-quote-items-body ' + qty_class + '"></div>')
                                    .append('<div class="bulk-action-button-wrapper">\n\
                                    <button class="fa-btn quote-item-select-all-bttn tltip" data-title="Select All"><i class="fa fa-square" aria-hidden="true"></i></button>\n\
                                    <button class="fa-btn quote-item-sort-bttn tltip" data-title="Sort Selection"><i class="fa fa-sort" aria-hidden="true"></i></button>\n\
                                    <button class="fa-btn form-no-2-calculation-bulk-copy-bttn tltip" data-title="Copy Selection"><i class="fa fa-bars" aria-hidden="true"></i></button>\n\
                                    <button class="fa-btn form-no-2-calculation-bulk-delete-bttn tltip" data-title="Delete Selection"><i class="fa fa-trash" aria-hidden="true"></i></button>\n\
                                    <a href="print/order-sheet-form-no-2-calculation-' + form_no_2_calculation_code + '-' + cid + '" target="_blank" class="fa-btn tltip dark" data-title="PDF | Order Sheet"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <small>Order Sheet in PDF</small></a>\n\
                                    <a href="https://file-gen.blinq.com.au/clients/' + user_account + '/order-sheet-xlsx-form-no-2-calculation-' + form_no_2_calculation_code + '-' + cid + '" target="_blank" class="fa-btn tltip dark" data-title="EXL | Order Sheet"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <small>Order Sheet in EXL</small></a>\n\
                                    <form class="group-discount-form" style="float: right; margin-right: -6px; font-size: 12px;">\n\
                                        <input type="tel" class="group-total price-input ui-state-default" autocomplete="off" style="float: left;" disabled>\n\
                                        <input type="tel" class="group-discount price-input ui-state-default" autocomplete="off" placeholder="%" style="float: left; margin-left: 1px !important; margin-right: 1px !important; text-align: center; width: 33px;" required>\n\
                                        <input type="hidden" class="form-no-2-calculation-code" value="' + form_no_2_calculation_code + '">\n\
                                        <input type="submit" class="bttn-input ui-button ui-widget ui-state-default ui-corner-all" value="✔" role="button" aria-disabled="false" style="float: left;">\n\
                                    </form>\n\
                                    <div class="group-discount-total" style="float: right; line-height: 40px; margin-right: 400px; font-size: 22px; font-weight: bold;"></div>\n\
                                </div>').prependTo("#form-no-2-calculation-quote-items-results").find(".bttn-input, .bulk-action-button").button();

                                var table_header = '<tr class="thead-tr">\n\
                                                <th class="left-fixed-column">#</th>\n\
                                                ' + field_header_left + '\
                                                ' + location_header + '\
                                                ' + field_header_right + '\
                                                ' + cost_header + '\
                                                ' + markup_header + '\
                                                ' + dynamic_field_qty_header + '\
                                                <th>\n\
                                                    <table class="right-fixed-column">\n\
                                                        <tr>\n\
                                                        '+ row_qty_header + '\n\
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

                                    $('<form id="calculation-form-' + quote_item_value.code + '" class="update-form-no-2-calculation-quote-item-form" action="./" method="POST">\n\
                                    <input type="hidden" name="calc-name" class="calc-name" value="form_no_2">\n\
                                    <input type="hidden" name="cid" class="cid" value="' + cid + '">\n\
                                    <input type="hidden" name="form-no-2-calculation-code" class="form-no-2-calculation-code" value="' + form_no_2_calculation_code + '">\n\
                                    <input type="hidden" name="form-no-2-calculation-quote-item-code" class="form-no-2-calculation-quote-item-code" value="' + quote_item_value.code + '">\n\
                                    <table class="form-no-2-calculation-table">\n\
                                        <thead class="thead"></thead>\n\
                                        <tbody>\n\
                                            <tr>\n\
                                                <td class="left-fixed-column">\n\
                                                    <label class="checkbox-label" for="checkbox-' + quote_item_value.code + '">' + quote_item_value.row_no + '</label>\n\
                                                    <input type="checkbox" id="checkbox-' + quote_item_value.code + '" class="checkbox-input" name="quote-item-codes[]" value="' + quote_item_value.code + '">\n\
                                                    <input type="hidden" class="quote-item-row-no" name="quote-item-row-no[]" value="' + quote_item_value.row_no + '">\n\
                                                </td>\n\
                                                ' + field_element_left + '\
                                                ' + location_element + '\
                                                ' + field_element_right + '\
                                                ' + cost_element + '\
                                                ' + markup_element + '\
                                                ' + dynamic_field_qty_element + '\
                                                </td>\n\
                                                <td>\n\
                                                    <table class="right-fixed-column" style="margin-top: -20px;">\n\
                                                        <tr>\n\
                                                            '+ row_qty_element + '\n\
                                                            <td><button type="button" class="form-no-2-calculation-table-more-bttn bttn-input table-more-bttn"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button></td>\n\
                                                            <td><button type="button" class="bttn-input delete-form-no-2-calculation-quote-item"><i class="fa fa-trash" aria-hidden="true"></i></button></td>\n\
                                                        </tr>\n\
                                                    </table>\n\
                                                </td>\n\
                                            </tr>\n\
                                        </tbody>\n\
                                    </table>\n\
                                    <table id="calculation-table-more-' + quote_item_value.code + '"  class="form-no-2-calculation-table-more">\n\
                                        <tr>\n\
                                            <td class="table-more-notes" colspan="3">\n\
                                                <textarea name="notes" data-column="notes" class="form-no-2-calculation-notes data-field" placeholder="Notes">' + quote_item_value.notes + '</textarea>\n\
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
                                </form>').appendTo("#quote-items-body-" + form_no_2_calculation_code).queue(function () {

                                        var this_form = $(this);

                                        this_form.find("[name='location']").val(quote_item_value.location);
                                        this_form.find("[name='cost']").val(quote_item_value.cost);
                                        this_form.find("[name='markup']").val(quote_item_value.markup);
                                        this_form.find("[name='dynamic_field_qty']").val(quote_item_value.dynamic_field_qty);

                                        this_form.find("[name='type']").val(quote_item_value.type).queue(function () {
                                            update_material(this);
                                            $(this).dequeue();
                                        });
                                        this_form.find("[name='material']").val(quote_item_value.material).queue(function () {
                                            update_colour(this);
                                            $(this).dequeue();
                                        });
                                        this_form.find("[name='colour']").val(quote_item_value.colour);

                                        if (quote_status === '1') {
                                            this_form.find(".approved-disabled").css({ "pointer-events": "none", "background": "#f2f2f2" });
                                        }

                                        this_form
                                            .find("[name='price']").val(quote_item_value.price).end()
                                            .find("[name='qty']").val(quote_item_value.qty).end()
                                            .find("[name='total']").val(quote_item_value.total);

                                        $.each(quote_item_value.fields, function (quote_item_fields_index, quote_item_fields_value) {
                                            var field_code = quote_item_fields_value.code;
                                            var field_name = quote_item_fields_value.name;
                                            var field_price = quote_item_fields_value.price;
                                            var form_no_2_calculation_field_code = quote_item_fields_value.form_no_2_calculation_field_code;
                                            //this_form.find("[name='field[" + form_no_2_calculation_field_code + "]']").val(field_name + '<->' + field_code);

                                            if (this_form.find("[name='field[" + form_no_2_calculation_field_code + "]']").is('input')) {
                                                this_form.find("[name='field[" + form_no_2_calculation_field_code + "]']").val(field_name);
                                            } else {
                                                this_form.find("[name='field[" + form_no_2_calculation_field_code + "]']").val(field_name + '<->' + field_code + '<->' + field_price).attr('data-selected-sub-options', quote_item_fields_value.sub_options);
                                            }
                                        });

                                        $.each(quote_item_value.accessories, function (quote_item_accessories_index, quote_item_accessories_value) {

                                            var accessory_code = quote_item_accessories_value.code;
                                            var accessory_name = quote_item_accessories_value.name;
                                            var accessory_price = +quote_item_accessories_value.price;
                                            var accessory_qty = +quote_item_accessories_value.qty;
                                            var accessory_total = (accessory_price * accessory_qty).toFixed(2);

                                            this_form.find(".table-more-accessories").append('<span class="form-no-2-calculation-accessories-wrapper">\n\
                                                                                            <input type="text" class="form-no-2-calculation-accessory" readonly disabled value="' + accessory_name + ' | ' + accessory_price + ' x ' + accessory_qty + ' = ' + accessory_total + '">\n\
                                                                                            <input type="button" class="delete-form-no-2-calculation-accessory bttn-input" data-code="' + accessory_code + '" value="✖">\n\
                                                                                        </span>');

                                            this_form.find('.form-no-2-calculation-accessories option[data-code="' + accessory_code + '"]').hide();
                                        });

                                        $.each(quote_item_value.per_meters, function (quote_item_per_meters_index, quote_item_per_meters_value) {

                                            var per_meter_code = quote_item_per_meters_value.code;
                                            var per_meter_name = quote_item_per_meters_value.name;
                                            var per_meter_price = +quote_item_per_meters_value.price;
                                            var per_meter_width = +quote_item_per_meters_value.width;
                                            var per_meter_total = (per_meter_price * per_meter_width).toFixed(2);

                                            this_form.find(".table-more-per-meters").append('<span class="form-no-2-calculation-per-meters-wrapper">\n\
                                                                                            <input type="text" class="form-no-2-calculation-per-meter" readonly disabled value="' + per_meter_name + ' | ' + per_meter_price + ' x ' + per_meter_width + ' = ' + per_meter_total + '">\n\
                                                                                            <input type="button" class="delete-form-no-2-calculation-per-meter bttn-input" data-code="' + per_meter_code + '" value="✖">\n\
                                                                                        </span>');

                                            this_form.find('.form-no-2-calculation-per-meters option[data-code="' + per_meter_code + '"]').hide();
                                        });

                                        $.each(quote_item_value.fitting_charges, function (quote_item_fitting_charges_index, quote_item_fitting_charges_value) {

                                            var fitting_charge_code = quote_item_fitting_charges_value.code;
                                            var fitting_charge_name = quote_item_fitting_charges_value.name;
                                            var fitting_charge_price = quote_item_fitting_charges_value.price;
                                            this_form.find(".table-more-fitting-charges").append('<span class="form-no-2-calculation-fitting-charges-wrapper">\n\
                                                                                                <input type="text" class="form-no-2-calculation-fitting-charge" readonly disabled value="' + fitting_charge_name + ' | ' + fitting_charge_price + '">\n\
                                                                                                <input type="button" class="delete-form-no-2-calculation-fitting-charge bttn-input" data-code="' + fitting_charge_code + '" value="✖">\n\
                                                                                            </span>');

                                            this_form.find('.form-no-2-calculation-fitting-charges option[data-code="' + fitting_charge_code + '"]').hide();
                                        });

                                        var note_length = quote_item_value.notes;
                                        var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                                        var per_meter_length = this_form.find('.form-no-2-calculation-per-meter').length;
                                        var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                            this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                                        } else {
                                            this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");
                                        }

                                        quote_item_no++;
                                        // group_total = +group_total + (+quote_item_value.price);
                                        group_total = +group_total + (+quote_item_value.total);
                                        group_discount = quote_item_value.discount;

                                        this_form.find(".bttn-input, .checkbox-input").button();
                                        this_form.dequeue();

                                    }).clearQueue().html();
                                });

                                $("#form-no-2-calculation-quote-items-wrapper-" + form_no_2_calculation_code)
                                    .find(".group-total").val(group_total.toFixed(2)).end()
                                    .find(".group-discount").val(group_discount).end()
                                    .find(".group-discount-total").html(group_total - (group_total * group_discount / 100));
                                $(".form-no-2-calculation-quote-items-body .form-no-2-calculation-table .thead").first().html(table_header); // Add Table headers for each calculation
                            } else {

                                $('<div id="form-no-2-calculation-quote-items-wrapper-' + form_no_2_calculation_code + '" class="form-no-2-calculation-quote-items-wrapper"></div>')
                                    .append('<h2 class="form-no-2-calculation-header">' + value.name + '</h2>')
                                    .append('<div id="quote-items-body-' + form_no_2_calculation_code + '" class="form-no-2-calculation-quote-items-body ' + qty_class + '"></div>')
                                    .append('<div class="bulk-action-button-wrapper"></div>')
                                    .prependTo("#form-no-2-calculation-quote-items-results").hide();
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
});

// $(document).on("click", ".form-no-2-calculation-select-all-bttn", function () {
//     var this_bttn = $(this);
//     var this_text = this_bttn.val();
//     var this_parent = this_bttn.parents(".form-no-2-calculation-quote-items-wrapper");

//     if (this_text === "✔") {
//         this_parent.find(".checkbox-input").prop("checked", true).change().button("refresh");
//         this_bttn.val("✖");
//     } else {
//         this_parent.find(".checkbox-input").prop("checked", false).change().button("refresh");
//         this_bttn.val("✔");
//     }
// });

$(document).on("click", ".form-no-2-calculation-bulk-delete-bttn", function () {

    var this_bttn = $(this);
    var this_parent = this_bttn.parents(".form-no-2-calculation-quote-items-wrapper");
    var table_header = this_parent.find(".form-no-2-calculation-table .thead .thead-tr:first").clone();
    var quote_item_codes = this_parent.find(".checkbox-input:checked");
    var datas = quote_item_codes.serialize() + '&cid=' + cid;

    if (datas) {

        iziToast.question({
            rtl: false,
            color: '#ed1d24',
            titleColor: 'white',
            messageColor: 'white',
            layout: 1,
            // drag: false,
            timeout: false,
            close: false,
            closeOnEscape: true,
            overlay: true,
            displayMode: 1,
            // id: 'del-bulk-quote-items',
            progressBar: true,
            title: 'Hey!',
            message: 'Wanna Delete?',
            position: 'center',
            buttons: [
                ['<button><b>Yes</b></button>', function (instance, toast, button, e, inputs) {

                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');

                    $.ajax({
                        url: "form-no-2-calculation/scripts/delete-bulk-form-no-2-calculation-quote-items.php",
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

                                        if (!this_parent.find('.update-form-no-2-calculation-quote-item-form').length) {
                                            this_parent.hide().find(".bulk-action-button-wrapper").empty();
                                        } else {
                                            // this_parent.find(".checkbox-label").each(function (i) {
                                            //     $(this).find(".ui-button-text").html(i + 1);
                                            // });
                                            this_parent.find(".form-no-2-calculation-table .thead").first().html(table_header);
                                        }

                                        this_bttn.prop('disabled', false).removeClass('field-loader');

                                        form_no_2_calculation_group_discount(this_bttn);
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
                            alert("Error! @delete-bulk-form-no-2-calculation-quote-item");
                        }
                    });

                }, false], // true to focus
                ['<button><b>No</b></button>', function (instance, toast, button, e) {

                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');

                }, true]
            ],
            onClosing: function (instance, toast, closedBy) {
                // console.info('Closing | closedBy: ' + closedBy);
            },
            onClosed: function (instance, toast, closedBy) {
                // console.info('Closed | closedBy: ' + closedBy);
            }
        });
    }
});


$(document).on("click", ".form-no-2-calculation-bulk-copy-bttn", function () {

    var this_bttn = $(this);
    var this_parent = this_bttn.parents(".form-no-2-calculation-quote-items-wrapper");
    var quote_item_codes = this_parent.find(".checkbox-input:checked");
    var datas = quote_item_codes.serialize();

    var form_no_2_calculation_code = this_bttn.siblings('.group-discount-form').find('.form-no-2-calculation-code').val();

    var max_row_no = -Infinity;
    $(document).find("#quote-items-body-" + form_no_2_calculation_code).find(".quote-item-row-no").each(function () {
        max_row_no = Math.max(max_row_no, parseFloat(this.value));
    });

    // console.log("max_row_no", max_row_no);

    var this_row_no = max_row_no + 1;

    if (datas) {

        $.ajax({
            url: "form-no-2-calculation/scripts/copy-bulk-form-no-2-calculation-quote-items.php",
            type: 'POST',
            dataType: 'json',
            data: datas + '&cid=' + cid,
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

                                // console.log("this_row_no", this_row_no);

                                var calculation_form = $("#calculation-form-" + old_code).clone();

                                calculation_form.attr("id", "calculation-form-" + new_code);
                                calculation_form.find(".form-no-2-calculation-quote-item-code").val(new_code);
                                calculation_form.find(".checkbox-label").attr("for", "checkbox-" + new_code + "").html(this_row_no);
                                calculation_form.find(".checkbox-input").attr("id", "checkbox-" + new_code).val(new_code);
                                calculation_form.find(".checkbox-input").button();
                                calculation_form.find(".quote-item-row-no").val(this_row_no);
                                calculation_form.find(".form-no-2-calculation-table-more").attr("id", "calculation-table-more-" + new_code).hide();
                                calculation_form.find(".form-no-2-calculation-table .thead").empty();

                                this_row_no++;

                                $(calculation_form).appendTo(this_parent.find('.form-no-2-calculation-quote-items-body')).queue(function () {
                                    var this_element = $(this);
                                    var old_form = $("#calculation-form-" + old_code);
                                    $.each(old_form.serializeArray(), function (i, form_field) {
                                        this_element.find(".data-field[name='" + form_field.name + "']").val(form_field.value);
                                    });

                                    update_selected_sub_options(this_element, true);

                                    this_element.dequeue();
                                }).clearQueue();
                            });

                            // this_parent.find(".checkbox-label").each(function (i) {
                            //     $(this).find(".ui-button-text").html(i + 1);
                            // });

                            this_bttn.prop('disabled', false).removeClass('field-loader');

                            form_no_2_calculation_group_discount(this_bttn);
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
                alert("Error! @copy-bulk-form-no-2-calculation-quote-item");
            }
        });
    }
});

$(document).on("click", ".delete-form-no-2-calculation-quote-item", function () {

    var this_bttn = $(this);
    var this_form = this_bttn.parents(".update-form-no-2-calculation-quote-item-form");
    var code = this_form.find(".form-no-2-calculation-quote-item-code").val();

    var this_parent = this_form.parents(".form-no-2-calculation-quote-items-wrapper");
    var table_header = this_parent.find(".form-no-2-calculation-table .thead .thead-tr:first").clone();

    iziToast.question({
        rtl: false,
        color: '#ed1d24',
        titleColor: 'white',
        messageColor: 'white',
        layout: 1,
        // drag: false,
        timeout: false,
        close: false,
        closeOnEscape: true,
        overlay: true,
        displayMode: 1,
        // id: 'del-bulk-quote-items',
        progressBar: true,
        title: 'Hey!',
        message: 'Wanna delete this item?',
        position: 'center',
        buttons: [
            ['<button><b>Yes</b></button>', function (instance, toast, button, e, inputs) {

                instance.hide({
                    transitionOut: 'fadeOut'
                }, toast, 'button');

                $.ajax({
                    url: "form-no-2-calculation/scripts/delete-form-no-2-calculation-quote-item.php",
                    type: 'POST',
                    dataType: 'json',
                    data: { cid: cid, code: code },
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


                                    this_bttn.prop('disabled', false).removeClass('field-loader');

                                    form_no_2_calculation_group_discount(this_form);
                                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                                    this_form.remove();
                                    this_parent.find(".form-no-2-calculation-table .thead").first().html(table_header);

                                    if (!this_parent.find('.update-form-no-2-calculation-quote-item-form').length) {
                                        this_parent.hide().find(".bulk-action-button-wrapper").empty();
                                    } else {
                                        // this_parent.find(".checkbox-label").each(function (i) {
                                        //     $(this).find(".ui-button-text").html(i + 1);
                                        // });
                                    }
                                } else {
                                    this_bttn.prop('disabled', false).removeClass('field-loader');
                                    alert(JSON.stringify(data));
                                }


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(JSON.stringify(jqXHR));
                        alert("Error! @delete-form-no-2-calculation-quote-item");
                    }
                });

            }, false], // true to focus
            ['<button><b>No</b></button>', function (instance, toast, button, e) {

                instance.hide({
                    transitionOut: 'fadeOut'
                }, toast, 'button');

            }, true]
        ],
        onOpening: function (instance, toast, closedBy) {
            // console.info('Closing | closedBy: ' + closedBy);
            this_bttn.find('.fa').removeClass('fa-trash').addClass('fa-spin fa-spinner red');
        },
        onClosing: function (instance, toast, closedBy) {
            // console.info('Closing | closedBy: ' + closedBy);
        },
        onClosed: function (instance, toast, closedBy) {
            // console.info('Closed | closedBy: ' + closedBy);
            this_bttn.find('.fa').addClass('fa-trash').removeClass('fa-spin fa-spinner red');
        }
    });
});

$(document).on("click", ".form-no-2-calculation-table-more-bttn", function () {
    var this_bttn = $(this);
    var this_form = this_bttn.parents("form");

    this_bttn.find('.fa').toggleClass('rotate');
    this_form.find(".form-no-2-calculation-table-more").toggle("slide", { direction: "up" }, "fast");
});

$(document).on("change", ".checkbox-input", function () {
    var this_checkbox = $(this);
    var this_table = this_checkbox.parents(".form-no-2-calculation-table");

    if (this_checkbox.is(':checked')) {
        this_table.find("select, .number-input, .text-field").addClass("ui-state-default");
    } else {
        this_table.find("select, .number-input, .text-field").removeClass("ui-state-default");
    }
});

$(document).on("change", ".add-form-no-2-calculation-quote-item-form .form-no-2-calculation-cost,\n\
                                .add-form-no-2-calculation-quote-item-form .form-no-2-calculation-markup,\n\
                                .add-form-no-2-calculation-quote-item-form .form-no-2-calculation-dynamic-field-qty,\n\
                                .add-form-no-2-calculation-quote-item-form .form-no-2-calculation-field", function () {
    get_form_no_2_calculation_price(this);
});

function get_form_no_2_calculation_price(this_element) {

    var this_form = $(this_element).parents("form");
    var price_element = this_form.find(".form-no-2-calculation-price");
    var dyn_field = this_form.find(".form-no-2-calculation-field");

    var qty_element = this_form.find(".form-no-2-calculation-qty");
    var total_element = this_form.find(".form-no-2-calculation-total");

    var qty_field = this_form.find('input[name="qty_field"]').val();

    var code = this_form.find(".form-no-2-calculation-code").val();
    var cost = this_form.find(".form-no-2-calculation-cost").length ? +this_form.find(".form-no-2-calculation-cost").val() : 0;
    var markup = this_form.find(".form-no-2-calculation-markup").length ? +this_form.find(".form-no-2-calculation-markup").val() : 1;
    var dynamic_field_qty = this_form.find(".form-no-2-calculation-dynamic-field-qty").length ? +this_form.find(".form-no-2-calculation-dynamic-field-qty").val() : 1;

    var price = 0;

    $.each(dyn_field, function () {

        var field_price = $(this).val().split("<->")[2];
        price += field_price > 0 ? +field_price : 0;

        if ($(this).hasClass('have-sub-options') && $(this).attr('data-selected-sub-options')) {

            var selected_sub_options = JSON.parse($(this).attr('data-selected-sub-options'));

            price += get_sub_option_price_total(selected_sub_options, 0)
        }

    });

    if (code !== "" && cost !== "" && markup !== "") {
        price += (cost + (cost * markup / 100));
    }

    price *= dynamic_field_qty;

    price_element.val(price.toFixed(2));

    if (qty_field === 'on') {

        var total_x = (price * qty_element.val()).toFixed(2);
        total_element.val(total_x);
    } else {
        var total_x = price.toFixed(2);
        total_element.val(total_x);
    }
}

$(document).on("change", ".add-form-no-2-calculation-quote-item-form .form-no-2-calculation-price, .add-form-no-2-calculation-quote-item-form .form-no-2-calculation-qty", function () {

    var this_form = $(this).parents("form");
    update_form_no_2_calculation_total_price(this_form);
});

function update_form_no_2_calculation_total_price(this_form) {

    var price_element = this_form.find(".form-no-2-calculation-price");
    var qty_element = this_form.find(".form-no-2-calculation-qty");
    var total_element = this_form.find(".form-no-2-calculation-total");

    var qty_field = this_form.find('input[name="qty_field"]').val();

    var price = +price_element.val();
    var price_x = price.toFixed(2);
    price_element.val(price_x);

    if (qty_field === 'on') {

        var total_x = (price * qty_element.val()).toFixed(2);
        total_element.val(total_x);

    } else {

        var total_x = price.toFixed(2);
        total_element.val(total_x);
    }

}

// Add new fields

$(document).on("change", ".form-no-2-calculation-field", function (event) {
    var this_element = $(this);
    var this_form = this_element.parents("form");
    var add_field_option_element_parent = this_element.siblings(".form-no-2-calculation-add-field-option-wrapper");
    var add_field_option_element = add_field_option_element_parent.find(".form-no-2-calculation-add-field-option");
    var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");
    var comboTree_btn = this_element.siblings('.comboTree-btn');

    // If bulk Selected
    var this_chcekedbox = this_form.find(".checkbox-input");
    var checked_checkbox = this_form.parents(".form-no-2-calculation-quote-items-body").find(".checkbox-input:checked");

    if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

        checked_checkbox.not(this_chcekedbox).each(function () {
            var this_form_x = $(this).parents(".update-form-no-2-calculation-quote-item-form");
            var data_fields_x = this_form_x.find(".data-field, .number-input, select, [type='submit']");
            if (this_element.val() === "add-new") {
                data_fields_x.prop("disabled", true);
            }
        });
    }
    if (this_element.val() === "add-new") {
        this_element.hide();
        comboTree_btn.hide();
        add_field_option_element_parent.show();
        add_field_option_element.focus();
        data_fields.prop("disabled", true);
    }
});

function form_no_2_calculation_add_field_option(field_option_name, field_option_price, this_element) {

    var this_element = $(this_element);
    var this_form = this_element.parents("form");
    var add_field_option_element_parent = this_element.parents(".form-no-2-calculation-add-field-option-wrapper");
    var add_field_option_element = add_field_option_element_parent.find(".form-no-2-calculation-add-field-option");
    var add_field_option_submit = add_field_option_element_parent.find(".form-no-2-calculation-add-field-option-submit");

    var field_element = add_field_option_element_parent.siblings(".form-no-2-calculation-field");
    var field_code = field_element.attr("data-code");
    var form_no_2_calculation_code = this_form.find(".form-no-2-calculation-code").val();
    var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");
    var comboTree_btn = field_element.siblings('.comboTree-btn');

    var field_option_name = field_option_name;
    var field_option_price = field_option_price !== '' ? Number(field_option_price) : 0;

    if (field_option_name) {

        $.ajax({
            url: "form-no-2-calculation/scripts/add-form-no-2-calculation-field-option.php",
            type: "POST",
            dataType: "json",
            data: { field_code: field_code, field_option_name: field_option_name, field_option_price: field_option_price, form_no_2_calculation_code: form_no_2_calculation_code },
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

                            var price = Number(data[3]).toFixed(2);

                            $(".form-no-2-calculation-add-field-option-x[data-field-code=" + field_code + "]").before('<option value="' + data[2] + '<->' + data[1] + '<->' + price + '">' + data[2] + ' | ' + price + '</option>');

                            // If bulk Selected
                            var this_chcekedbox = this_form.find(".checkbox-input");
                            var checked_checkbox = this_form.parents(".form-no-2-calculation-quote-items-body").find(".checkbox-input:checked");

                            if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

                                checked_checkbox.not(this_chcekedbox).each(function () {
                                    var this_form_x = $(this).parents(".update-form-no-2-calculation-quote-item-form");
                                    var data_fields_x = this_form_x.find(".data-field, .number-input, select, [type='submit']");
                                    data_fields_x.prop("disabled", false);
                                });
                            }

                            add_field_option_element.val("");
                            add_field_option_element_parent.hide();
                            data_fields.prop("disabled", false);
                            comboTree_btn.show();
                            field_element.show().val(data[2] + '<->' + data[1] + '<->' + price).change();
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

$(document).on("keypress", ".form-no-2-calculation-add-field-option, .form-no-2-calculation-add-field-option-price", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();

        var this_element = $(this);
        var add_field_option_element_parent = this_element.parents(".form-no-2-calculation-add-field-option-wrapper");
        var add_field_option_element = add_field_option_element_parent.find(".form-no-2-calculation-add-field-option");
        var add_field_option_price_element = add_field_option_element_parent.find(".form-no-2-calculation-add-field-option-price");

        var field_option_name = add_field_option_element.val();
        var field_option_price = add_field_option_price_element.val();

        form_no_2_calculation_add_field_option(field_option_name, field_option_price, this_element);
        return false;
    }
});

$(document).on("click", ".form-no-2-calculation-add-field-option-submit", function (event) {
    event.preventDefault();

    var this_element = $(this);
    var add_field_option_element = this_element.siblings(".form-no-2-calculation-add-field-option");
    var add_field_option_price_element = this_element.siblings(".form-no-2-calculation-add-field-price-option");

    var field_option_name = add_field_option_element.val();
    var field_option_price = add_field_option_price_element.val();


    form_no_2_calculation_add_field_option(field_option_name, field_option_price, this_element);
    return false;
});



$(document).on("click", ".form-no-2-calculation-add-field-option-close", function () {
    var this_element = $(this);
    var this_form = this_element.parents("form");
    var add_field_option_element_parent = this_element.parents(".form-no-2-calculation-add-field-option-wrapper");
    var add_field_option_element = add_field_option_element_parent.find(".form-no-2-calculation-add-field-option");
    var field_element = add_field_option_element_parent.siblings(".form-no-2-calculation-field");
    var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");
    var comboTree_btn = field_element.siblings('.comboTree-btn');

    // If bulk Selected
    var this_chcekedbox = this_form.find(".checkbox-input");
    var checked_checkbox = this_form.parents(".form-no-2-calculation-quote-items-body").find(".checkbox-input:checked");

    if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

        checked_checkbox.not(this_chcekedbox).each(function () {
            var this_form_x = $(this).parents(".update-form-no-2-calculation-quote-item-form");
            var data_fields_x = this_form_x.find(".data-field, .number-input, select, [type='submit']");
            data_fields_x.prop("disabled", false);
        });
    }

    add_field_option_element.val("");
    add_field_option_element_parent.hide();
    data_fields.prop("disabled", false);
    comboTree_btn.show();
    field_element.show().val("").change().focus();
});

function form_no_2_calculation_group_discount(this_element) {

    var result_parent = $(this_element).parents(".form-no-2-calculation-quote-items-wrapper");
    var quote_items_parent = result_parent.find(".form-no-2-calculation-quote-items-body");
    // var price_field = quote_items_parent.find("input[name='price']:not(:hidden)");
    var total_field = quote_items_parent.find(".right-fixed-column:visible input[name='total']");
    var group_discount = $.isNumeric(+result_parent.find(".group-discount").val()) ? +result_parent.find(".group-discount").val() : 0;

    var total_price = 0;
    // price_field.each(function () {
    total_field.each(function () {
        total_price += Number($(this).val().replace(/,/g, ''));
    });
    var group_discount_total = total_price - (total_price * group_discount / 100);


    result_parent.find(".group-total").val(total_price.toFixed(2)).end()
        .find(".group-discount").val(group_discount).end()
        .find(".group-discount-total").html(group_discount_total).end()
        .find(".group-discount-form").submit();

}

$(document).on("submit", ".add-form-no-2-calculation-quote-item-form", function (event) {
    event.preventDefault();

    var this_form = $(this);
    var this_submit_bttn = this_form.find('[type="submit"]');
    var this_table_thead_tr = this_form.find(".form-no-2-calculation-table .thead-tr");
    var this_table_tbody_tr = this_form.find(".form-no-2-calculation-table .tbody-tr");
    var form_no_2_calculation_code = this_form.find(".form-no-2-calculation-code").val();
    var accessory_option_array = this_form.find(".accessory-option-array").val();
    var per_meter_option_array = this_form.find(".per-meter-option-array").val();
    var fitting_charge_option_array = this_form.find(".fitting-charge-option-array").val();
    var qty_class = this_form.hasClass('qty_on') ? 'qty_on' : '';

    var result_parent = $("#form-no-2-calculation-quote-items-wrapper-" + form_no_2_calculation_code);

    var group_discount = $.isNumeric(+result_parent.find(".group-discount").val()) ? +result_parent.find(".group-discount").val() : 0;

    var datas = this_form.serialize();

    // This updates has been added after qty_filed updates by thas
    if (qty_class) {

        var row_qty_header = "<th>Price</th><th>Qty</th><th>Total</th>";
        var row_qty_element = '<td><input type="tel" name="price" class="form-no-2-calculation-price price-input data-field" autocomplete="off"></td><td><input type="number" name="qty" class="form-no-2-calculation-qty number-input data-field" autocomplete="off" min="1" value="1"></td><td><input type="number" name="total" class="form-no-2-calculation-total number-input" autocomplete="off" min="1" value="" readonly><input type="hidden" class="data-field" name="qty_field" value="on"></td>';

    } else {

        row_qty_header = "<th>Price</th>";
        row_qty_element = '<td><input type="tel" name="price" data-column="price" class="form-no-2-calculation-price price-input data-field ui-state-default" autocomplete="off" value=""><input type="hidden" name="qty" class="form-no-2-calculation-qty number-input data-field" autocomplete="off" min="1" value="0"><input type="hidden" name="total" data-column="total" class="form-no-2-calculation-total price-input data-field ui-state-default" autocomplete="off" value="" readonly><input type="hidden" class="data-field" name="qty_field" value="off"></td>';
    }

    var max_row_no = -Infinity;
    $(document).find("#quote-items-body-" + form_no_2_calculation_code).find(".quote-item-row-no").each(function () {
        max_row_no = Math.max(max_row_no, parseFloat(this.value));
    });

    // console.log("max_row_no", max_row_no)

    var this_row_no = max_row_no == -Infinity ? 1 : max_row_no + 1;

    $.ajax({
        url: "form-no-2-calculation/scripts/add-form-no-2-calculation-quote-item.php",
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

                            if (!$("#quote-items-body-" + form_no_2_calculation_code + " .form-no-2-calculation-table").length) {

                                var newe_table_head_tr = this_table_thead_tr.clone();
                                newe_table_head_tr.prepend('<th class="left-fixed-column">#</th>');
                                newe_table_head_tr.find(".right-fixed-column").html('<tr>\n\
                                                                                    '+ row_qty_header + '\n\
                                                                                    <th>More</th>\n\
                                                                                    <th>Del</th>\n\
                                                                                </tr>');
                                var newe_table_head_tr_x = newe_table_head_tr.html();
                                $("#form-no-2-calculation-quote-items-wrapper-" + form_no_2_calculation_code).show();
                            } else {
                                newe_table_head_tr_x = '';
                            }

                            var newe_table_body_tr = this_table_tbody_tr.clone();
                            newe_table_body_tr.prepend('<td class="left-fixed-column">\n\
                                <label class="checkbox-label" for="checkbox-' + code + '">' + this_row_no + '</label>\n\
                                <input type="checkbox" id="checkbox-' + code + '" class="checkbox-input" name="quote-item-codes[]" value="' + code + '">\n\
                                <input type="hidden" class="quote-item-row-no" name="quote-item-row-no[]" value="' + this_row_no + '">\n\
                            </td>');
                            newe_table_body_tr.find(".right-fixed-column").html('<tr>\n\
                                                                                '+ row_qty_element + '\n\
                                                                                <td><button type="button" class="form-no-2-calculation-table-more-bttn bttn-input table-more-bttn"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button></td>\n\
                                                                                <td><button type="button" class="bttn-input delete-form-no-2-calculation-quote-item"><i class="fa fa-trash" aria-hidden="true"></i></button></td>\n\
                                                                            </tr>');

                            if (quote_status === '1') {
                                newe_table_body_tr.find(".approved-disabled").css({ "pointer-events": "none", "background": "#f2f2f2" });
                            }

                            if (accessory_option_array) {

                                var accessory_element = "";
                                var accessory_options = "";

                                $.each(JSON.parse(accessory_option_array), function (accessory_option_index, accessory_option_value) {
                                    accessory_options += '<option data-code="' + accessory_option_value.code + '" value="' + accessory_option_value.code + '<->' + accessory_option_value.name + '<->' + accessory_option_value.price + '">' + accessory_option_value.name + ' | ' + accessory_option_value.price + '</option>';
                                });
                                accessory_element = '<span class="form-no-2-calculation-accessories-wrapper">\n\
                                                    <select class="form-no-2-calculation-accessories">\n\
                                                        <option value="">Accessories</option>\n\
                                                        ' + accessory_options + '\
                                                    </select>\n\
                                                    <input type="tel" class="add-form-no-2-calculation-accessory-qty qty-input" placeholder="Qty">\n\
                                                    <input type="button" class="add-form-no-2-calculation-accessories bttn-input" value="✚">\n\
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
                                per_meter_element = '<span class="form-no-2-calculation-per-meters-wrapper">\n\
                                                    <select class="form-no-2-calculation-per-meters">\n\
                                                        <option value="">Per Meters</option>\n\
                                                        ' + per_meter_options + '\
                                                    </select>\n\
                                                    <input type="tel" class="add-form-no-2-calculation-per-meter-width qty-input" placeholder="Width">\n\
                                                    <input type="button" class="add-form-no-2-calculation-per-meters bttn-input" value="✚">\n\
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
                                fitting_charge_element = '<span class="form-no-2-calculation-fitting-charges-wrapper">\n\
                                                        <select class="form-no-2-calculation-fitting-charges">\n\
                                                            <option value="">Fitting Charges</option>\n\
                                                            ' + fitting_charge_options + '\
                                                        </select>\n\
                                                        <input type="button" class="add-form-no-2-calculation-fitting-charges bttn-input" value="✚">\n\
                                                    </span>';

                            } else {
                                fitting_charge_element = '';
                            }

                            $('<form id="calculation-form-' + code + '" class="update-form-no-2-calculation-quote-item-form" action="./" method="POST">\n\
                                <input type="hidden" name="calc-name" class="calc-name" value="form_no_2">\n\
                                <input type="hidden" name="cid" class="cid" value="' + cid + '">\n\
                                <input type="hidden" name="form-no-2-calculation-code" class="form-no-2-calculation-code" value="' + form_no_2_calculation_code + '">\n\
                                <input type="hidden" name="form-no-2-calculation-quote-item-code" class="form-no-2-calculation-quote-item-code" value="' + code + '">\n\
                                <table class="form-no-2-calculation-table '+ qty_class + '">\n\
                                    <thead class="thead">\n\
                                        <tr class="thead-tr">' + newe_table_head_tr_x + '</tr>\n\
                                    </thead>\n\
                                    <tbody>\n\
                                        ' + newe_table_body_tr.html() + '\
                                    </tbody>\n\
                                </table>\n\
                                <table id="calculation-table-more-' + code + '"  class="form-no-2-calculation-table-more">\n\
                                    <tr>\n\
                                        <td class="table-more-notes" colspan="3">\n\
                                            <textarea name="notes" data-column="notes" class="form-no-2-calculation-notes data-field" placeholder="Notes"></textarea>\n\
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
                            </form>').appendTo("#quote-items-body-" + form_no_2_calculation_code).queue(function () {

                                var this_element = $(this);
                                $.each(this_form.serializeArray(), function (i, form_field) {
                                    this_element.find("[name='" + form_field.name + "']").val(form_field.value);
                                });

                                this_element.find(".bttn-input, .checkbox-input").button();

                                update_selected_sub_options(this_element, true);

                                this_element.dequeue();

                            }).clearQueue();

                            this_row_no++;
                        });

                        result_parent.find('.bulk-action-button-wrapper').html('<button class="fa-btn quote-item-select-all-bttn tltip" data-title="Select All"><i class="fa fa-square" aria-hidden="true"></i></button>\n\
                        <button class="fa-btn quote-item-sort-bttn tltip" data-title="Sort Selection"><i class="fa fa-sort" aria-hidden="true"></i></button>\n\
                        <button class="fa-btn form-no-2-calculation-bulk-copy-bttn tltip" data-title="Copy Selection"><i class="fa fa-bars" aria-hidden="true"></i></button>\n\
                        <button class="fa-btn form-no-2-calculation-bulk-delete-bttn tltip" data-title="Delete Selection"><i class="fa fa-trash" aria-hidden="true"></i></button>\n\
                        <a href="print/order-sheet-form-no-2-calculation-' + form_no_2_calculation_code + '-' + cid + '" target="_blank" class="fa-btn tltip dark" data-title="PDF | Order Sheet"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <small>Order Sheet in PDF</small></a>\n\
                        <a href="https://file-gen.blinq.com.au/clients/' + user_account + '/order-sheet-xlsx-form-no-2-calculation-' + form_no_2_calculation_code + '-' + cid + '" target="_blank" class="fa-btn tltip dark" data-title="EXL | Order Sheet"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <small>Order Sheet in EXL</small></a>\n\
                        <form class="group-discount-form" style="float: right; margin-right: -6px; font-size: 12px;">\n\
                            <input type="tel" class="group-total price-input ui-state-default" autocomplete="off" style="float: left;" disabled value="">\n\
                            <input type="tel" class="group-discount price-input ui-state-default" autocomplete="off" placeholder="%" value="' + group_discount + '" style="float: left; margin-left: 1px !important; margin-right: 1px !important; text-align: center; width: 33px;" required value="">\n\
                            <input type="hidden" class="form-no-2-calculation-code" value="' + form_no_2_calculation_code + '">\n\
                            <input type="submit" class="bttn-input ui-button ui-widget ui-state-default ui-corner-all" value="✔" role="button" aria-disabled="false" style="float: left;">\n\
                        </form>\n\
                        <div class="group-discount-total" style="float: right; line-height: 40px; margin-right: 400px; font-size: 22px; font-weight: bold;"></div>\n\
                    ').find(".bulk-action-button").button();

                        // result_parent.find(".checkbox-label").each(function (i) {
                        //     $(this).find(".ui-button-text").html(i + 1);
                        // });

                        this_form[0].reset();
                        this_form.find(".form-no-2-calculation-material").val("").find('option').not(':first').remove();
                        this_form.find(".form-no-2-calculation-colour").val("").find('option').not(':first').remove();
                        this_submit_bttn.prop('disabled', false).removeClass('field-loader');

                        form_no_2_calculation_group_discount(result_parent.find(".group-discount-form"));

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
            alert("Error! @add-form-no-2-calculation-quote-item");
        }
    });
});

function update_form_no_2_calculation_quote_item(this_element, quote_items_array) {

    var this_field = $(this_element);
    var this_field_value = this_field.val();

    if (this_field_value === "add-new") {
        return false;
        // Prevent this function to add new colour
    }

    var this_form = this_field.parents(".update-form-no-2-calculation-quote-item-form");
    var this_chcekedbox = this_form.find(".checkbox-input");
    var checked_checkbox = this_form.parents(".form-no-2-calculation-quote-items-body").find(".checkbox-input:checked");

    $.ajax({
        url: "form-no-2-calculation/scripts/update-form-no-2-calculation-quote-item.php",
        type: 'POST',
        dataType: 'json',
        data: { quote_items_array: quote_items_array },
        beforeSend: function () {
            //this_field.addClass('field-loader');
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
                                var this_form_x = $(this).parents(".update-form-no-2-calculation-quote-item-form");

                                var note_length = this_form_x.find('.form-no-2-calculation-notes').val().length;
                                var accessory_length = this_form_x.find('.form-no-2-calculation-accessory').length;
                                var per_meter_length = this_form_x.find('.form-no-2-calculation-per-meter').length;
                                var fitting_charge_length = this_form_x.find('.form-no-2-calculation-fitting-charge').length;

                                if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                    this_form_x.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                                } else {
                                    this_form_x.find('.form-no-2-calculation-table-more-bttn').removeClass("red");
                                }
                            });

                        } else {

                            var note_length = this_form.find('.form-no-2-calculation-notes').val().length;
                            var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                            var per_meter_length = this_form.find('.form-no-2-calculation-per-meter').length;
                            var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                            if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                            } else {
                                this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");
                            }
                        }

                        this_field.removeClass('field-loader');

                        // if (this_field.attr("name") === "cost" || this_field.attr("name") === "markup" || this_field.attr("name") === "price" || this_field.hasClass('form-no-2-calculation-field')) {
                        form_no_2_calculation_group_discount(this_form);
                        // } else {
                        //     //$('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        //     //$('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");
                        // }

                    } else {
                        this_field.removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            this_field.removeClass('field-loader');
            alert(JSON.stringify(jqXHR));
            alert("Error! @update-form-no-2-calculation-quote-item");
        }
    });
}

$(document).on("change", ".update-form-no-2-calculation-quote-item-form .data-field", function (event) {
    event.preventDefault();

    var this_field = $(this);
    var this_field_name = this_field.attr("name");
    var this_field_value = this_field.val();

    if (this_field_value === "add-new") {
        return false;
        // Prevent this function to add new colour
    }

    this_field.toggleClass('field-loader');

    var this_form = this_field.parents(".update-form-no-2-calculation-quote-item-form");
    var this_chcekedbox = this_form.find(".checkbox-input");
    var checked_checkbox = this_form.parents(".form-no-2-calculation-quote-items-body").find(".checkbox-input:checked");

    var quote_items_array = "";

    if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

        quote_items_array = [];
        var no = 0;
        checked_checkbox.each(function () {
            var this_form_x = $(this).parents(".update-form-no-2-calculation-quote-item-form");
            var this_field_x = this_form_x.find("[name='" + this_field_name + "']");

            this_field_x.val(this_field_value);

            if (this_field_x.attr("name") === "cost" || this_field_x.attr("name") === "markup" || this_field_x.attr("name") === "dynamic_field_qty" || this_field_x.hasClass('form-no-2-calculation-field')) {
                if (quote_status !== '1') {
                    get_form_no_2_calculation_price(this_field_x);
                }
            }

            if (this_field_x.attr("name") === "price" || this_field_x.attr("name") === "qty" || this_field_x.hasClass('form-no-2-calculation-field')) {
                if (quote_status !== '1') {
                    update_form_no_2_calculation_total_price(this_form_x);
                }
            }

            if (this_field_x.attr("name") === "cost" || this_field_x.attr("name") === "markup" || this_field_x.attr("name") === "dynamic_field_qty" || this_field_x.hasClass('form-no-2-calculation-field')) {

                if (quote_status !== '1') {
                    get_form_no_2_calculation_price(this_field_x);
                    no = no + 1;
                    quote_items_array.push(this_form_x.serialize());
                    if (checked_checkbox.length === no) {
                        update_form_no_2_calculation_quote_item(this_field, quote_items_array);
                    }
                    //});
                } else {
                    no = no + 1;
                    get_form_no_2_calculation_price(this_field_x);
                    quote_items_array.push(this_form_x.serialize());
                    if (checked_checkbox.length === no) {
                        update_form_no_2_calculation_quote_item(this_field, quote_items_array);
                    }
                }
            } else {
                no = no + 1;
                quote_items_array.push(this_form_x.serialize());
                if (checked_checkbox.length === no) {
                    update_form_no_2_calculation_quote_item(this_field, quote_items_array);
                }
            }
        });

    } else {

        if (this_field.attr("name") === "cost" || this_field.attr("name") === "markup" || this_field.attr("name") === "dynamic_field_qty" || this_field.hasClass('form-no-2-calculation-field')) {
            if (quote_status !== '1') {
                get_form_no_2_calculation_price(this_field);
            }
        }

        if (this_field.attr("name") === "price" || this_field.attr("name") === "qty" || this_field.hasClass('form-no-2-calculation-field')) {
            if (quote_status !== '1') {
                update_form_no_2_calculation_total_price(this_form);
            }
        }

        if (this_field.attr("name") === "cost" || this_field.attr("name") === "markup" || this_field.attr("name") === "dynamic_field_qty" || this_field.hasClass('form-no-2-calculation-field')) {
            if (quote_status !== '1') {
                //ajax_get_price_request.done(function () {
                get_form_no_2_calculation_price(this_field);
                quote_items_array = [this_form.serialize()];
                update_form_no_2_calculation_quote_item(this_field, quote_items_array);
                //});
            } else {
                get_form_no_2_calculation_price(this_field);
                quote_items_array = [this_form.serialize()];
                update_form_no_2_calculation_quote_item(this_field, quote_items_array);
            }
        } else {
            quote_items_array = [this_form.serialize()];
            update_form_no_2_calculation_quote_item(this_field, quote_items_array);
        }
    }
});

$(document).on("keypress", ".add-form-no-2-calculation-accessory-qty", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        var this_element = $(this);
        var add_bttn = this_element.siblings('.add-form-no-2-calculation-accessories');
        add_bttn.click();
    }
});

$(document).on("click", ".add-form-no-2-calculation-accessories", function () {

    var this_bttn = $(this);
    var this_form = this_bttn.parents('.update-form-no-2-calculation-quote-item-form');
    var accessory_element = this_bttn.siblings('.form-no-2-calculation-accessories');
    var accessory_qty_element = this_bttn.siblings('.add-form-no-2-calculation-accessory-qty');

    var form_no_2_calculation_code = this_form.find('.form-no-2-calculation-code').val();
    var form_no_2_calculation_quote_item_code = this_form.find('.form-no-2-calculation-quote-item-code').val();
    var accessory = accessory_element.val();
    var accessory_qty = +accessory_qty_element.val();
    accessory_qty = accessory_qty !== 0 ? accessory_qty : 1;

    if (accessory) {

        $.ajax({
            url: "form-no-2-calculation/scripts/add-form-no-2-calculation-quote-item-accessory.php",
            type: 'POST',
            dataType: 'json',
            data: { cid: cid, form_no_2_calculation_code: form_no_2_calculation_code, form_no_2_calculation_quote_item_code: form_no_2_calculation_quote_item_code, accessory: accessory, accessory_qty: accessory_qty },
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

                            this_form.find(".table-more-accessories").append('<span class="form-no-2-calculation-accessories-wrapper">\n\
                                                                                <input type="text" class="form-no-2-calculation-accessory" readonly disabled value="' + accessory_name + ' | ' + accessory_price + ' x ' + accessory_qty + ' = ' + accessory_total + '">\n\
                                                                                <input type="button" class="delete-form-no-2-calculation-accessory bttn-input" data-code="' + accessory_code + '" value="✖">\n\
                                                                            </span>').find(".bttn-input").button();

                            accessory_element.find('option[data-code="' + accessory_code + '"]').hide();
                            accessory_element.val("").focus();
                            accessory_qty_element.val("");

                            var note_length = this_form.find('.form-no-2-calculation-notes').val().length;
                            var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                            var per_meter_length = this_form.find('.form-no-2-calculation-per-meter').length;
                            var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                            if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                            } else {
                                this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");
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
                alert("Error! @add-form-no-2-calculation-quote-item-accessory");
            }
        });
    }
});

$(document).on("click", ".delete-form-no-2-calculation-accessory", function () {

    var this_bttn = $(this);
    var this_form = this_bttn.parents('.update-form-no-2-calculation-quote-item-form');
    var accessory_element = this_form.find('.form-no-2-calculation-accessories');
    var this_wrapper = this_bttn.parent('.form-no-2-calculation-accessories-wrapper');

    var form_no_2_calculation_code = this_form.find('.form-no-2-calculation-code').val();
    var form_no_2_calculation_quote_item_code = this_form.find('.form-no-2-calculation-quote-item-code').val();
    var accessory_code = this_bttn.attr("data-code");

    $.ajax({
        url: "form-no-2-calculation/scripts/delete-form-no-2-calculation-quote-item-accessory.php",
        type: 'POST',
        dataType: 'json',
        data: { cid: cid, form_no_2_calculation_code: form_no_2_calculation_code, form_no_2_calculation_quote_item_code: form_no_2_calculation_quote_item_code, accessory_code: accessory_code },
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

                        var note_length = this_form.find('.form-no-2-calculation-notes').val().length;
                        var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                        var per_meter_length = this_form.find('.form-no-2-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");
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
            alert("Error! @delete-form-no-2-calculation-quote-item-accessory");
        }
    });
});

$(document).on("keypress", ".add-form-no-2-calculation-per-meter-width", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        var this_element = $(this);
        var add_bttn = this_element.siblings('.add-form-no-2-calculation-per-meters');
        add_bttn.click();
    }
});

$(document).on("click", ".add-form-no-2-calculation-per-meters", function () {

    var this_bttn = $(this);
    var this_form = this_bttn.parents('.update-form-no-2-calculation-quote-item-form');
    var per_meter_element = this_bttn.siblings('.form-no-2-calculation-per-meters');
    var per_meter_width_element = this_bttn.siblings('.add-form-no-2-calculation-per-meter-width');

    var form_no_2_calculation_code = this_form.find('.form-no-2-calculation-code').val();
    var form_no_2_calculation_quote_item_code = this_form.find('.form-no-2-calculation-quote-item-code').val();
    var per_meter = per_meter_element.val();
    var per_meter_width = +per_meter_width_element.val();
    per_meter_width = per_meter_width !== 0 ? per_meter_width : 1;

    if (per_meter) {

        $.ajax({
            url: "form-no-2-calculation/scripts/add-form-no-2-calculation-quote-item-per-meter.php",
            type: 'POST',
            dataType: 'json',
            data: { cid: cid, form_no_2_calculation_code: form_no_2_calculation_code, form_no_2_calculation_quote_item_code: form_no_2_calculation_quote_item_code, per_meter: per_meter, per_meter_width: per_meter_width },
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

                            this_form.find(".table-more-per-meters").append('<span class="form-no-2-calculation-per-meters-wrapper">\n\
                                                                                <input type="text" class="form-no-2-calculation-per-meter" readonly disabled value="' + per_meter_name + ' | ' + per_meter_price + ' x ' + per_meter_width + ' = ' + per_meter_total + '">\n\
                                                                                <input type="button" class="delete-form-no-2-calculation-per-meter bttn-input" data-code="' + per_meter_code + '" value="✖">\n\
                                                                            </span>').find(".bttn-input").button();

                            per_meter_element.find('option[data-code="' + per_meter_code + '"]').hide();
                            per_meter_element.val("").focus();
                            per_meter_width_element.val("");

                            var note_length = this_form.find('.form-no-2-calculation-notes').val().length;
                            var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                            var per_meter_length = this_form.find('.form-no-2-calculation-per-meter').length;
                            var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                            if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                            } else {
                                this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");
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
                alert("Error! @add-form-no-2-calculation-quote-item-per-meter");
            }
        });
    }
});

$(document).on("click", ".delete-form-no-2-calculation-per-meter", function () {

    var this_bttn = $(this);
    var this_form = this_bttn.parents('.update-form-no-2-calculation-quote-item-form');
    var per_meter_element = this_form.find('.form-no-2-calculation-per-meters');
    var this_wrapper = this_bttn.parent('.form-no-2-calculation-per-meters-wrapper');

    var form_no_2_calculation_code = this_form.find('.form-no-2-calculation-code').val();
    var form_no_2_calculation_quote_item_code = this_form.find('.form-no-2-calculation-quote-item-code').val();
    var per_meter_code = this_bttn.attr("data-code");

    $.ajax({
        url: "form-no-2-calculation/scripts/delete-form-no-2-calculation-quote-item-per-meter.php",
        type: 'POST',
        dataType: 'json',
        data: { cid: cid, form_no_2_calculation_code: form_no_2_calculation_code, form_no_2_calculation_quote_item_code: form_no_2_calculation_quote_item_code, per_meter_code: per_meter_code },
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

                        var note_length = this_form.find('.form-no-2-calculation-notes').val().length;
                        var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                        var per_meter_length = this_form.find('.form-no-2-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");
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
            alert("Error! @delete-form-no-2-calculation-quote-item-accessory");
        }
    });
});

$(document).on("click", ".add-form-no-2-calculation-fitting-charges", function () {

    var this_bttn = $(this);
    var this_form = this_bttn.parents('.update-form-no-2-calculation-quote-item-form');
    var fitting_charge_element = this_bttn.siblings('.form-no-2-calculation-fitting-charges');

    var form_no_2_calculation_code = this_form.find('.form-no-2-calculation-code').val();
    var form_no_2_calculation_quote_item_code = this_form.find('.form-no-2-calculation-quote-item-code').val();
    var fitting_charge = fitting_charge_element.val();

    if (fitting_charge) {

        $.ajax({
            url: "form-no-2-calculation/scripts/add-form-no-2-calculation-quote-item-fitting-charge.php",
            type: 'POST',
            dataType: 'json',
            data: { cid: cid, form_no_2_calculation_code: form_no_2_calculation_code, form_no_2_calculation_quote_item_code: form_no_2_calculation_quote_item_code, fitting_charge: fitting_charge },
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

                            this_form.find(".table-more-fitting-charges").append('<span class="form-no-2-calculation-fitting-charges-wrapper">\n\
                                                                                    <input type="text" class="form-no-2-calculation-fitting-charge" readonly disabled value="' + fitting_charge_name + ' | ' + fitting_charge_price + '">\n\
                                                                                    <input type="button" class="delete-form-no-2-calculation-fitting-charge bttn-input" data-code="' + fitting_charge_code + '" value="✖">\n\
                                                                                </span>').find(".bttn-input").button();

                            fitting_charge_element.find('option[data-code="' + fitting_charge_code + '"]').hide();
                            fitting_charge_element.val("");

                            var note_length = this_form.find('.form-no-2-calculation-notes').val().length;
                            var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                            var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                            if (note_length || accessory_length || fitting_charge_length) {
                                this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                            } else {
                                this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");;
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
                alert("Error! @add-form-no-2-calculation-quote-item-fitting-charge");
            }
        });
    }
});

$(document).on("click", ".delete-form-no-2-calculation-fitting-charge", function () {

    var this_bttn = $(this);
    var this_form = this_bttn.parents('.update-form-no-2-calculation-quote-item-form');
    var fitting_charge_element = this_form.find('.form-no-2-calculation-fitting-charges');
    var this_wrapper = this_bttn.parent('.form-no-2-calculation-fitting-charges-wrapper');

    var form_no_2_calculation_code = this_form.find('.form-no-2-calculation-code').val();
    var form_no_2_calculation_quote_item_code = this_form.find('.form-no-2-calculation-quote-item-code').val();
    var fitting_charge_code = this_bttn.attr("data-code");

    $.ajax({
        url: "form-no-2-calculation/scripts/delete-form-no-2-calculation-quote-item-fitting-charge.php",
        type: 'POST',
        dataType: 'json',
        data: { cid: cid, form_no_2_calculation_code: form_no_2_calculation_code, form_no_2_calculation_quote_item_code: form_no_2_calculation_quote_item_code, fitting_charge_code: fitting_charge_code },
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

                        var note_length = this_form.find('.form-no-2-calculation-notes').val().length;
                        var accessory_length = this_form.find('.form-no-2-calculation-accessory').length;
                        var fitting_charge_length = this_form.find('.form-no-2-calculation-fitting-charge').length;

                        if (note_length || accessory_length || fitting_charge_length) {
                            this_form.find('.form-no-2-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.form-no-2-calculation-table-more-bttn').removeClass("red");;
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
            alert("Error! @delete-form-no-2-calculation-quote-item-fitting-charge");
        }
    });
});

$(document).on("submit", "#form-no-2-calculation-quote-items-results .group-discount-form", function (event) {
    event.preventDefault();

    var this_form = $(this);
    var this_bttn = $(this).find("input[type='submit']");
    var discount = isNaN(this_form.find(".group-discount").val()) ? '0' : this_form.find(".group-discount").val();
    var total = isNaN(this_form.find(".group-total").val()) ? '0' : this_form.find(".group-total").val();
    var form_no_2_calculation_code = this_form.find(".form-no-2-calculation-code").val();


    var group_discount_total = (total - (total * discount / 100)).toFixed(2);

    $.ajax({
        url: "form-no-2-calculation/scripts/update-form-no-2-calculation-group-discount.php",
        type: 'POST',
        dataType: 'json',
        data: { cid: cid, discount: discount, form_no_2_calculation_code: form_no_2_calculation_code },
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
            alert("Error! @update-form-no-2-calculation-group-discount");
        }
    });

});