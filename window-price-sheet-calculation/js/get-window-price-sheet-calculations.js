$(function() {

    $.ajax({
        url: "window-price-sheet-calculation/scripts/get-window-price-sheet-calculations.php",
        type: "POST",
        dataType: "json",
        data: { cid: cid },
        beforeSend: function() {
            //loadingPannel.show();
        },
        success: function(data) {
            if (data[0] === 4) {
                document.location.replace(data[1]);
            } else
            if (data[0] === 2) {
                alert(data[1]);
            } else
            if (data[0] === 1) {

                $.each(data[1], function(index, value) {

                    var window_price_sheet_calculation_code = value.code;

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
                                                    <td><input type="button" data-mc-db="window_price_sheet_calculation" data-sc-code="' + value.code + '" data-sc-name="' + value.name + '" class="bttn-input etape-btn" value="☯"></td>\n\
                                                </tr>\n\
                                            </table>\n\
                                        </td>';
                    }

                    if (value.location_select === 1) {
                        var location_header = '<th>Locations</th>';
                        var location_options = "";
                        $.each(data[2], function(location_index, location_value) {
                            location_options += '<option value="' + location_value.name + '<->' + location_value.code + '">' + location_value.name + '</option>';
                        });
                        var location_element = '<td>\n\
                                                    <select name="location" data-column="location" class="window-price-sheet-calculation-location data-field">\n\
                                                        <option value=""></option>\n\
                                                        ' + location_options + '\
                                                    </select>\n\
                                                </td>';
                    } else {
                        location_header = "";
                        location_options = "";
                        location_element = "";
                    }

                    var type_header = '<th>Types</th>',
                        type_options = "",
                        type_element = "";
                    $.each(value.types, function(type_index, type_value) {

                        var min_max_dimensions = JSON.stringify(type_value.min_max_dimensions);
                        var materials_and_colours = JSON.stringify(type_value.materials_and_colours);

                        type_options += '<option value="' + type_value.name + '<->' + type_value.code + '" data-materials-and-colours=\'' + materials_and_colours + '\' data-min-max-dimensions=\'' + min_max_dimensions + '\' data-code="' + type_value.code + '">' + type_value.name + '</option>';
                    });
                    type_element = '<td>\n\
                                        <select name="type" data-column="type" class="window-price-sheet-calculation-type data-field approved-disabled">\n\
                                            <option value=""></option>\n\
                                            ' + type_options + '\
                                        </select>\n\
                                    </td>';

                    if (value.materials_and_colour_select === 1) {
                        var material_header = '<th>Materials</th>';
                        var material_element = '<td>\n\
                                                    <select name="material" data-column="material" class="window-price-sheet-calculation-material data-field">\n\
                                                        <option value=""></option>\n\
                                                    </select>\n\
                                                </td>';
                        var colour_header = '<th>Colours</th>';
                        var colour_element = '<td>\n\
                                                    <select name="colour" data-column="colour" class="window-price-sheet-calculation-colour data-field">\n\
                                                        <option value=""></option>\n\
                                                    </select>\n\
                                                    <span class="window-price-sheet-calculation-add-colour-wrapper">\n\
                                                        <input type="text" class="window-price-sheet-calculation-add-colour text-input">\n\
                                                        <input type="button" class="window-price-sheet-calculation-add-colour-submit bttn-input" value="✚">\n\
                                                        <input type="button" class="window-price-sheet-calculation-add-colour-close bttn-input" value="✖">\n\
                                                    </span>\n\
                                                </td>';

                    } else {
                        material_header = "";
                        colour_header = "";
                        material_element = "";
                        colour_element = "";
                    }

                    var field_header_left = "",
                        field_element_left = "";
                    var field_header_right = "",
                        field_element_right = "";
                    $.each(value.fields, function(field_index, field_value) {

                        if (field_value.side === 1) { //left side

                            field_header_left += '<th>' + field_value.name + '</th>';

                            if (field_value.field_type === 0) {

                                var field_options = "";
                                $.each(field_value.options, function(field_option_index, field_option_value) {
                                    var price_tag = field_option_value.price > 0 ? ' | ' + field_option_value.price : '';
                                    field_options += '<option value="' + field_option_value.name + '<->' + field_option_value.code + '<->' + field_option_value.price + '">' + field_option_value.name + price_tag + '</option>';
                                });
                                field_options += '<option class="window-price-sheet-calculation-add-field-option-x" data-field-code="' + field_value.code + '" value="add-new">Add New</option>';

                                field_element_left += '<td>\n\
                                                        <select name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-name="' + field_value.name + '" data-column="field-' + field_value.code + '" class="window-price-sheet-calculation-field data-field">\n\
                                                            <option value=""></option>\n\
                                                            ' + field_options + '\
                                                        </select>\n\
                                                        <span class="window-price-sheet-calculation-add-field-option-wrapper">\n\
                                                            <input type="text" class="window-price-sheet-calculation-add-field-option text-input">\n\
                                                            <input type="button" class="window-price-sheet-calculation-add-field-option-submit bttn-input" value="✚">\n\
                                                            <input type="button" class="window-price-sheet-calculation-add-field-option-close bttn-input" value="✖">\n\
                                                        </span>\n\
                                                    </td>';
                            } else {
                                field_element_left += '<td>\n\
                                                        <input type="text" name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-name="' + field_value.name + '" data-column="field-' + field_value.code + '" class="window-price-sheet-calculation-field data-field text-field" autocomplete="off">\n\
                                                    </td>';
                            }
                        }

                        if (field_value.side === 0) { //right side

                            field_header_right += '<th>' + field_value.name + '</th>';

                            if (field_value.field_type === 0) {

                                var field_options = "";
                                $.each(field_value.options, function(field_option_index, field_option_value) {
                                    var price_tag = field_option_value.price > 0 ? ' | ' + field_option_value.price : '';
                                    field_options += '<option value="' + field_option_value.name + '<->' + field_option_value.code + '<->' + field_option_value.price + '">' + field_option_value.name + price_tag + '</option>';
                                });
                                field_options += '<option class="window-price-sheet-calculation-add-field-option-x" data-field-code="' + field_value.code + '" value="add-new">Add New</option>';

                                field_element_right += '<td>\n\
                                                            <select name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-name="' + field_value.name + '" data-column="field-' + field_value.code + '" class="window-price-sheet-calculation-field data-field">\n\
                                                                <option value=""></option>\n\
                                                                ' + field_options + '\
                                                            </select>\n\
                                                            <span class="window-price-sheet-calculation-add-field-option-wrapper">\n\
                                                                <input type="text" class="window-price-sheet-calculation-add-field-option text-input">\n\
                                                                <input type="button" class="window-price-sheet-calculation-add-field-option-submit bttn-input" value="✚">\n\
                                                                <input type="button" class="window-price-sheet-calculation-add-field-option-close bttn-input" value="✖">\n\
                                                            </span>\n\
                                                        </td>';
                            } else {
                                field_element_right += '<td>\n\
                                                            <input type="text" name="field[' + field_value.code + ']" data-code="' + field_value.code + '" data-name="' + field_value.name + '" data-column="field-' + field_value.code + '" class="window-price-sheet-calculation-field data-field text-field" autocomplete="off">\n\
                                                        </td>';
                            }
                        }
                    });

                    if (value.accessories_select === 1) {

                        var accessory_element = "";
                        var accessory_options = "";
                        var accessory_option_array = '<input type="hidden" class="accessory-option-array" value=\'' + JSON.stringify(value.accessory_options) + '\'>';

                        $.each(value.accessory_options, function(accessory_option_index, accessory_option_value) {
                            accessory_options += '<option data-code="' + accessory_option_value.code + '" value="' + accessory_option_value.code + '<->' + accessory_option_value.name + '<->' + accessory_option_value.price + '">' + accessory_option_value.name + ' | ' + accessory_option_value.price + '</option>';
                        });
                        accessory_element = '<span class="window-price-sheet-calculation-accessories-wrapper">\n\
                                                <select class="window-price-sheet-calculation-accessories">\n\
                                                    <option value="">Accessories</option>\n\
                                                    ' + accessory_options + '\
                                                </select>\n\
                                                <input type="tel" class="add-window-price-sheet-calculation-accessory-mm qty-input" placeholder="mm">\n\
                                                <input type="tel" class="add-window-price-sheet-calculation-accessory-qty qty-input" placeholder="Qty">\n\
                                                <input type="button" class="add-window-price-sheet-calculation-accessories bttn-input" value="✚">\n\
                                            </span>';

                    } else {
                        accessory_element = '';
                        accessory_option_array = '';
                    }

                    if (value.per_meters_select === 1) {

                        var per_meter_element = "";
                        var per_meter_options = "";
                        var per_meter_option_array = '<input type="hidden" class="per-meter-option-array" value=\'' + JSON.stringify(value.per_meter_options) + '\'>';

                        $.each(value.per_meter_options, function(per_meter_option_index, per_meter_option_value) {
                            per_meter_options += '<option data-code="' + per_meter_option_value.code + '" value="' + per_meter_option_value.code + '<->' + per_meter_option_value.name + '<->' + per_meter_option_value.price + '">' + per_meter_option_value.name + ' | ' + per_meter_option_value.price + '</option>';
                        });
                        per_meter_element = '<span class="window-price-sheet-calculation-accessories-wrapper">\n\
                                                <select class="window-price-sheet-calculation-per-meters">\n\
                                                    <option value="">Per Meters</option>\n\
                                                    ' + per_meter_options + '\
                                                </select>\n\
                                                <input type="tel" class="add-window-price-sheet-calculation-per-meter-width qty-input" placeholder="Width">\n\
                                                <input type="button" class="add-window-price-sheet-calculation-per-meters bttn-input" value="✚">\n\
                                            </span>';

                    } else {
                        per_meter_element = '';
                        per_meter_option_array = '';
                    }

                    if (value.fitting_charges_select === 1) {

                        var fitting_charge_element = "";
                        var fitting_charge_options = "";
                        var fitting_charge_option_array = '<input type="hidden" class="fitting-charge-option-array" value=\'' + JSON.stringify(value.fitting_charge_options) + '\'>';

                        $.each(value.fitting_charge_options, function(fitting_charge_option_index, fitting_charge_option_value) {
                            fitting_charge_options += '<option data-code="' + fitting_charge_option_value.code + '" value="' + fitting_charge_option_value.code + '<->' + fitting_charge_option_value.name + '<->' + fitting_charge_option_value.price + '">' + fitting_charge_option_value.name + ' | ' + fitting_charge_option_value.price + '</option>';
                        });
                        fitting_charge_element = '<span class="window-price-sheet-calculation-fitting-charges-wrapper">\n\
                                                    <select class="window-price-sheet-calculation-fitting-charges">\n\
                                                        <option value="">Fitting Charges</option>\n\
                                                        ' + fitting_charge_options + '\
                                                    </select>\n\
                                                    <input type="button" class="add-window-price-sheet-calculation-fitting-charges bttn-input" value="✚">\n\
                                                </span>';

                    } else {
                        fitting_charge_element = '';
                        fitting_charge_option_array = '';
                    }

                    if (value.status === 1) {

                        $('<li><a href="#tab-' + value.code + '" class="active-calcs" data-calc-code="window_price_sheet_calculation"  data-calc-name="' + value.name + '">' + value.name + '</a></li>').prependTo("#window-price-sheet-calculation-tabs .tabs ul");
                        $('<div id="tab-' + value.code + '" class="window-price-sheet-calculation-tab-div"></div>')
                            .append($('<form class="add-window-price-sheet-calculation-quote-item-form ' + etape_activated + '" action="./" method="POST"></form>')
                                .append('<input type="hidden" name="calc-name" class="calc-name" value="window_price_sheet">')
                                .append('<input type="hidden" class="cid" name="cid" value="' + cid + '">')
                                .append('<input type="hidden" class="window-price-sheet-calculation-code" name="window-price-sheet-calculation-code" value="' + window_price_sheet_calculation_code + '">')
                                .append($('<table class="window-price-sheet-calculation-table"></table>')
                                    .append($('<thead class="thead"></thead>')
                                        .append($('<tr class="thead-tr"></tr>')
                                            .append(etape_header)
                                            .append(field_header_left)
                                            .append(location_header)
                                            .append('<th>Width</th>')
                                            .append('<th>Height</th>')
                                            .append(type_header)
                                            .append(material_header)
                                            .append(colour_header)
                                            .append(field_header_right)
                                            .append('<th>\n\
                                                                            <table class="right-fixed-column">\n\
                                                                                <tr>\n\
                                                                                    <th>Price</th>\n\
                                                                                    <th>Qty</th>\n\
                                                                                    <th>Add</th>\n\
                                                                                </tr>\n\
                                                                            </table>\n\
                                                                        </th>')
                                        )
                                    )
                                    .append($('<tbody></tbody>')
                                        .append($('<tr class="tbody-tr"></tr>')
                                            .append(etape_element)
                                            .append(field_element_left)
                                            .append(location_element)
                                            .append('<td><input type="number" name="width" data-column="width_x" class="window-price-sheet-calculation-width number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="mm"></td>')
                                            .append('<td><input type="number" name="drop" data-column="drop_x" class="window-price-sheet-calculation-drop number-input data-field" maxlength="4" autocomplete="off" min="0" placeholder="mm"></td>')
                                            .append(type_element)
                                            .append(material_element)
                                            .append(colour_element)
                                            .append(field_element_right)
                                            .append('<td>\n\
                                                                            <table class="right-fixed-column" style="margin-top: -20px;">\n\
                                                                                <tr>\n\
                                                                                    <td><input type="tel" name="price" class="window-price-sheet-calculation-price price-input data-field" autocomplete="off"></td>\n\
                                                                                    <td><input type="number" name="qty" class="window-price-sheet-calculation-qty number-input" autocomplete="off" min="1" value="1"></td>\n\
                                                                                    <td><input type="submit" class="bttn-input" value="✚"></td>\n\
                                                                                </tr>\n\
                                                                            </table>\n\
                                                                        </td>')
                                        )
                                    )
                                )
                                .append(accessory_option_array)
                                .append(per_meter_option_array)
                                .append(fitting_charge_option_array)
                            ).prependTo("#window-price-sheet-calculation-tabs .calculation-results").find(".bttn-input").button();
                    }
                    // End of prepend Calculation methods

                    if (JSON.stringify(value.quote_items) !== '[]') {

                        $('<div id="window-price-sheet-calculation-quote-items-wrapper-' + window_price_sheet_calculation_code + '" class="window-price-sheet-calculation-quote-items-wrapper"></div>')
                            .append('<h2 class="window-price-sheet-calculation-header">' + value.name + '</h2>')
                            .append('<input type="hidden" class="window-price-sheet-calculation-name" name="window-price-sheet-calculation-name" value="' + value.name + '">')
                            .append('<div id="quote-items-body-' + window_price_sheet_calculation_code + '" class="window-price-sheet-calculation-quote-items-body added-calcs" data-calc-code="window_price_sheet_calculation" data-calc-name="' + value.name + '"></div>')
                            .append('<div class="bulk-action-button-wrapper">\n\
                                            <input type="button" class="bulk-action-button window-price-sheet-calculation-select-all-bttn" value="✔">\n\
                                            <input type="button" class="bulk-action-button window-price-sheet-calculation-bulk-delete-bttn" value="Delete">\n\
                                            <input type="button" class="bulk-action-button window-price-sheet-calculation-bulk-copy-bttn" value="Copy">\n\
                                            <button class="fa-btn window-price-sheet-calculation-bulk-copy-quote-bttn copy-quote-bttn tltip bulk-action-button" style="padding-left:5px; padding-right:5px;" data-title="Copy Quote">Copy Quote</button>\n\
                                            <button class="fa-btn quote-item-sort-bttn tltip bulk-action-button" style="padding-left:5px; padding-right:5px;" data-calc-code="'+ window_price_sheet_calculation_code + '" data-title="Move">Move<i class="fa fa-sort" aria-hidden="true"></i></button>\n\
                                            <a href="print/label-window-price-sheet-calculation-' + window_price_sheet_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 110px;">Print Label</a>\n\
                                            <a href="print/order-sheet-window-price-sheet-calculation-' + window_price_sheet_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 130px;">Print Order Sheet</a>\n\
                                            <a href="https://file-gen.blinq.com.au/clients/' + user_account + '/order-sheet-xlsx-window-price-sheet-calculation-' + window_price_sheet_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 160px;">Print Order Sheet Xlsx</a>\n\
                                            <form style="display: contents; margin-right: -6px; font-size: 14px;">\n\
                                            <input type="hidden" class="window-price-sheet-calculation-code" value="' + window_price_sheet_calculation_code + '">\n\
                                            <input type="tel" class="production-date quote_details" id="production-date" data-customer-id="' + customer_id + '" data-customer-no="' + customer_no + '" data-quote-id="' + quote_id + '" data-quote-no="' + quote_no + '" data-job-no="' + job_no + '"  data-pro-code="' + window_price_sheet_calculation_code + '" placeholder="Production Date" autocomplete="off" value="' + value.production_date + '"style="width:100px; height:35px !important; margin-left:2px; margin-right:2px; border-radius: 6px;border: 1px solid #cdd5da;  ' +  approved_date + ' text-align: center;">\n\
                                            <input type="tel" class="install-date quote_details" id="install-date" data-customer-id="' + customer_id + '" data-customer-no="' + customer_no + '" data-quote-id="' + quote_id + '" data-quote-no="' + quote_no + '" data-job-no="' + job_no + '"  data-pro-code="' + window_price_sheet_calculation_code + '" placeholder="Install Date" autocomplete="off" value="' + value.install_date + '"style="width:100px; height:35px !important; margin-left:2px; margin-right:2px; border-radius: 6px; border: 1px solid #cdd5da;  ' +  approved_date + ' text-align: center;">\n\
                                            </form>\n\
                                            <form class="group-discount-form" style="float: right; margin-right: -6px; font-size: 12px;">\n\
                                                <input type="tel" class="group-total price-input ui-state-default" autocomplete="off" style="float: left;" disabled>\n\
                                                <input type="tel" class="group-discount price-input ui-state-default" autocomplete="off" placeholder="%" style="float: left; margin-left: 1px !important; margin-right: 1px !important; text-align: center; width: 33px;" required>\n\
                                                <input type="hidden" class="window-price-sheet-calculation-code" value="' + window_price_sheet_calculation_code + '">\n\
                                                <input type="submit" class="bttn-input ui-button ui-widget ui-state-default ui-corner-all" value="✔" role="button" aria-disabled="false" style="float: left;">\n\
                                            </form>\n\
                                            <div class="group-discount-total" style="float: right; line-height: 40px; margin-right: 40px; font-size: 22px; font-weight: bold;"></div>\n\
                                        </div>').prependTo("#window-price-sheet-calculation-quote-items-results").find(".bttn-input, .bulk-action-button").button().end()
                                        .find(".production-date").datepicker({ dateFormat: "dd/mm/yy" }).end()
                                        .find(".install-date").datepicker({ dateFormat: "dd/mm/yy" });

                        var table_header = '<tr class="thead-tr">\n\
                                                <th class="left-fixed-column">#</th>\n\
                                                ' + field_header_left + '\
                                                ' + location_header + '\
                                                <th>Width</th>\n\
                                                <th>Height</th>\n\
                                                ' + type_header + '\
                                                ' + material_header + '\
                                                ' + colour_header + '\
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
                        $.each(value.quote_items, function(quote_item_index, quote_item_value) {

                            // if(!quote_item_value.row_no){
                                var order_quote_no = quote_item_no;
                            // }else{
                            //     var order_quote_no = quote_item_value.row_no;
                            // }

                            $('<form id="calculation-form-' + quote_item_value.code + '" class="update-window-price-sheet-calculation-quote-item-form" action="./" method="POST">\n\
                                    <input type="hidden" name="calc-name" class="calc-name" value="window_price_sheet">\n\
                                    <input type="hidden" name="cid" class="cid" value="' + cid + '">\n\
                                    <input type="hidden" name="window-price-sheet-calculation-code" class="window-price-sheet-calculation-code" value="' + window_price_sheet_calculation_code + '">\n\
                                    <input type="hidden" name="window-price-sheet-calculation-quote-item-code" class="window-price-sheet-calculation-quote-item-code" value="' + quote_item_value.code + '">\n\
                                    <table class="window-price-sheet-calculation-table row_item_table">\n\
                                        <thead class="thead"></thead>\n\
                                        <tbody>\n\
                                            <tr>\n\
                                                <td class="left-fixed-column">\n\
                                                    <label class="checkbox-label" for="checkbox-' + quote_item_value.code + '">' + order_quote_no + '</label>\n\
                                                    <input type="checkbox" id="checkbox-' + quote_item_value.code + '"class="checkbox-input" name="quote-item-codes[]" value="' + quote_item_value.code + '">\n\
                                                    <input type="hidden" class="quote-item-row-no" name="quote-item-row-no[]" value="' + quote_item_value.row_no + '">\n\
                                                </td>\n\
                                                ' + field_element_left + '\
                                                ' + location_element + '\
                                                <td><input type="number" name="width" data-column="width_x" class="window-price-sheet-calculation-width number-input data-field" maxlength="4" autocomplete="off" min="0" value="' + quote_item_value.width + '" placeholder="mm"></td>\n\
                                                <td><input type="number" name="drop" data-column="drop_x" class="window-price-sheet-calculation-drop number-input data-field" maxlength="4" autocomplete="off" min="0" value="' + quote_item_value.drop + '" placeholder="mm"></td>\n\
                                                ' + type_element + '\
                                                ' + material_element + '\
                                                ' + colour_element + '\
                                                ' + field_element_right + '\
                                                <td>\n\
                                                    <table class="right-fixed-column" style="margin-top: -20px;">\n\
                                                        <tr>\n\
                                                            <td><input type="tel" name="price" data-column="price" class="window-price-sheet-calculation-price price-input data-field ui-state-default" autocomplete="off" value="' + quote_item_value.price + '"></td>\n\
                                                            <td><input type="button" class="window-price-sheet-calculation-table-more-bttn bttn-input" value="★"></td>\n\
                                                            <td><input type="button" class="bttn-input delete-window-price-sheet-calculation-quote-item" value="✖"></td>\n\
                                                        </tr>\n\
                                                    </table>\n\
                                                </td>\n\
                                            </tr>\n\
                                        </tbody>\n\
                                    </table>\n\
                                    <table id="calculation-table-more-' + quote_item_value.code + '"  class="window-price-sheet-calculation-table-more">\n\
                                        <tr>\n\
                                            <td class="table-more-notes" colspan="3">\n\
                                                <textarea name="notes" data-column="notes" class="window-price-sheet-calculation-notes data-field" placeholder="Notes">' + quote_item_value.notes + '</textarea>\n\
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
                                </form>').appendTo("#quote-items-body-" + window_price_sheet_calculation_code).queue(function() {

                                var this_form = $(this);

                                this_form.find("[name='location']").val(quote_item_value.location);

                                this_form.find("[name='type']").val(quote_item_value.type).queue(function() {
                                    update_material(this);
                                    $(this).dequeue();
                                });
                                this_form.find("[name='material']").val(quote_item_value.material).queue(function() {
                                    update_colour(this);
                                    $(this).dequeue();
                                });
                                this_form.find("[name='colour']").val(quote_item_value.colour);

                                if (quote_status === '1') {
                                    this_form.find(".approved-disabled").css({ "pointer-events": "none", "background": "#f2f2f2" });
                                }

                                $.each(quote_item_value.fields, function(quote_item_fields_index, quote_item_fields_value) {
                                    var field_code = quote_item_fields_value.code;
                                    var field_name = quote_item_fields_value.name;
                                    var field_price = quote_item_fields_value.price;
                                    var window_price_sheet_calculation_field_code = quote_item_fields_value.window_price_sheet_calculation_field_code;
                                    //this_form.find("[name='field[" + window_price_sheet_calculation_field_code + "]']").val(field_name + '<->' + field_code);

                                    if (this_form.find("[name='field[" + window_price_sheet_calculation_field_code + "]']").is('input')) {
                                        this_form.find("[name='field[" + window_price_sheet_calculation_field_code + "]']").val(field_name);
                                    } else {
                                        this_form.find("[name='field[" + window_price_sheet_calculation_field_code + "]']").val(field_name + '<->' + field_code + '<->' + field_price);
                                    }
                                });

                                $.each(quote_item_value.accessories, function(quote_item_accessories_index, quote_item_accessories_value) {

                                    var accessory_code = quote_item_accessories_value.code;
                                    var accessory_name = quote_item_accessories_value.name;
                                    var accessory_price = +quote_item_accessories_value.price;
                                    var accessory_qty = +quote_item_accessories_value.qty;
                                    var accessory_total = (accessory_price * accessory_qty).toFixed(2);

                                    this_form.find(".table-more-accessories").append('<span class="window-price-sheet-calculation-accessories-wrapper">\n\
                                                                                            <input type="text" class="window-price-sheet-calculation-accessory" readonly disabled value="' + accessory_name + ' | ' + accessory_price + ' x ' + accessory_qty + ' = ' + accessory_total + '">\n\
                                                                                            <input type="button" class="delete-window-price-sheet-calculation-accessory bttn-input" data-code="' + accessory_code + '" value="✖">\n\
                                                                                        </span>');

                                    // this_form.find('.window-price-sheet-calculation-accessories option[data-code="' + accessory_code + '"]').hide();
                                });

                                $.each(quote_item_value.per_meters, function(quote_item_per_meters_index, quote_item_per_meters_value) {

                                    var per_meter_code = quote_item_per_meters_value.code;
                                    var per_meter_name = quote_item_per_meters_value.name;
                                    var per_meter_price = +quote_item_per_meters_value.price;
                                    var per_meter_width = +quote_item_per_meters_value.width;
                                    var per_meter_total = (per_meter_price * per_meter_width).toFixed(2);

                                    this_form.find(".table-more-per-meters").append('<span class="window-price-sheet-calculation-per-meters-wrapper">\n\
                                                                                            <input type="text" class="window-price-sheet-calculation-per-meter" readonly disabled value="' + per_meter_name + ' | ' + per_meter_price + ' x ' + per_meter_width + ' = ' + per_meter_total + '">\n\
                                                                                            <input type="button" class="delete-window-price-sheet-calculation-per-meter bttn-input" data-code="' + per_meter_code + '" value="✖">\n\
                                                                                        </span>');

                                    this_form.find('.window-price-sheet-calculation-per-meters option[data-code="' + per_meter_code + '"]').hide();
                                });

                                $.each(quote_item_value.fitting_charges, function(quote_item_fitting_charges_index, quote_item_fitting_charges_value) {

                                    var fitting_charge_code = quote_item_fitting_charges_value.code;
                                    var fitting_charge_name = quote_item_fitting_charges_value.name;
                                    var fitting_charge_price = quote_item_fitting_charges_value.price;
                                    this_form.find(".table-more-fitting-charges").append('<span class="window-price-sheet-calculation-fitting-charges-wrapper">\n\
                                                                                                <input type="text" class="window-price-sheet-calculation-fitting-charge" readonly disabled value="' + fitting_charge_name + ' | ' + fitting_charge_price + '">\n\
                                                                                                <input type="button" class="delete-window-price-sheet-calculation-fitting-charge bttn-input" data-code="' + fitting_charge_code + '" value="✖">\n\
                                                                                            </span>');

                                    this_form.find('.window-price-sheet-calculation-fitting-charges option[data-code="' + fitting_charge_code + '"]').hide();
                                });

                                var note_length = quote_item_value.notes;
                                var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                                var per_meter_length = this_form.find('.window-price-sheet-calculation-per-meter').length;
                                var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                                if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                    this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                                } else {
                                    this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");
                                }

                                quote_item_no++;
                                group_total = +group_total + (+quote_item_value.price);
                                group_discount = quote_item_value.discount;
                                production_date = quote_item_value.production_date;
                                install_date = quote_item_value.install_date;

                                this_form.find(".bttn-input, .checkbox-input").button();
                                this_form.dequeue();

                            }).clearQueue().html();
                        });

                        $("#window-price-sheet-calculation-quote-items-wrapper-" + window_price_sheet_calculation_code)
                            .find(".group-total").val(group_total.toFixed(2)).end()
                            .find(".group-discount").val(group_discount).end()
                            .find(".group-discount-total").html((group_total - (group_total * group_discount / 100)).toFixed(2));
                        $(".window-price-sheet-calculation-quote-items-body .window-price-sheet-calculation-table .thead").first().html(table_header); // Add Table headers for each calculation
                    } else {

                        $('<div id="window-price-sheet-calculation-quote-items-wrapper-' + window_price_sheet_calculation_code + '" class="window-price-sheet-calculation-quote-items-wrapper"></div>')
                            .append('<h2 class="window-price-sheet-calculation-header">' + value.name + '</h2>')
                            .append('<div id="quote-items-body-' + window_price_sheet_calculation_code + '" class="window-price-sheet-calculation-quote-items-body added-calcs" data-calc-code="window_price_sheet_calculation" data-calc-name="' + value.name + '"></div>')
                            .append('<div class="bulk-action-button-wrapper"></div>')
                            .prependTo("#window-price-sheet-calculation-quote-items-results").hide();
                    }
                });
                $(".tabs").tabs("refresh").tabs("option", "active", 0);
            } else {
                alert(JSON.stringify(data));
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(JSON.stringify(jqXHR));
        }
    });

    $(document).on("click", ".window-price-sheet-calculation-select-all-bttn", function() {
        var this_bttn = $(this);
        var this_text = this_bttn.val();
        var this_parent = this_bttn.parents(".window-price-sheet-calculation-quote-items-wrapper");

        if (this_text === "✔") {
            this_parent.find(".checkbox-input").prop("checked", true).change().button("refresh");
            this_bttn.val("✖");
        } else {
            this_parent.find(".checkbox-input").prop("checked", false).change().button("refresh");
            this_bttn.val("✔");
        }
    });

    $(document).on("click", ".window-price-sheet-calculation-bulk-delete-bttn", function() {

        var this_bttn = $(this);
        var this_parent = this_bttn.parents(".window-price-sheet-calculation-quote-items-wrapper");
        var table_header = this_parent.find(".window-price-sheet-calculation-table .thead .thead-tr").clone();
        var quote_item_codes = this_parent.find(".checkbox-input:checked");
        var datas = quote_item_codes.serialize();

        if (confirm("Are you sure you want to delete this item?")) {
        if (datas) {

            $.ajax({
                url: "window-price-sheet-calculation/scripts/delete-bulk-window-price-sheet-calculation-quote-items.php",
                type: 'POST',
                dataType: 'json',
                data: datas + '&cid=' + cid,
                beforeSend: function() {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                },
                success: function(data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        $.each(quote_item_codes.serializeArray(), function(index, code) {
                            $("#calculation-form-" + code.value).remove();
                        });

                        if (!this_parent.find('.update-window-price-sheet-calculation-quote-item-form').length) {
                            this_parent.hide().find(".bulk-action-button-wrapper").empty();
                        } else {
                            this_parent.find(".checkbox-label").each(function(i) {
                                $(this).find(".ui-button-text").html(i + 1);
                            });
                            this_parent.find(".window-price-sheet-calculation-table .thead").first().html(table_header);
                                    
                            // quote_item_sort(window_price_sheet_calculation_code, true);
                        }

                        this_bttn.prop('disabled', false).removeClass('field-loader');

                        window_price_sheet_calculation_group_discount(this_bttn);
                        $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @delete-bulk-window-price-sheet-calculation-quote-item");
                }
            });
        }
    }
    });


    $(document).on("click", ".window-price-sheet-calculation-bulk-copy-bttn", function() {

        var this_bttn = $(this);
        var this_parent = this_bttn.parents(".window-price-sheet-calculation-quote-items-wrapper");
        var quote_item_codes = this_parent.find(".checkbox-input:checked");
        var datas = quote_item_codes.serialize();

        var window_price_sheet_calculation_code = this_bttn.siblings('.group-discount-form').find('.window-price-sheet-calculation-code').val();
    
        var max_row_no = -Infinity;
        $(document).find("#quote-items-body-" + window_price_sheet_calculation_code).find(".quote-item-row-no").each(function () {
            max_row_no = Math.max(max_row_no, parseFloat(this.value));
        });
    
        // console.log("max_row_no", max_row_no);
    
        var this_row_no = max_row_no + 1;

        if (confirm("Are you sure you want to copy this item?")) {
            if (datas) {

                $.ajax({
                    url: "window-price-sheet-calculation/scripts/copy-bulk-window-price-sheet-calculation-quote-items.php",
                    type: 'POST',
                    dataType: 'json',
                    data: datas + '&cid=' + cid,
                    beforeSend: function() {
                        this_bttn.prop('disabled', false).addClass('field-loader');
                    },
                    success: function(data) {
                        if (data[0] === 4) {
                            document.location.replace(data[1]);
                        } else
                        if (data[0] === 2) {
                            this_bttn.prop('disabled', false).removeClass('field-loader');
                            alert(data[1]);
                        } else
                        if (data[0] === 1) {
                            $.each(data[1], function(old_code, new_code) {

                                var calculation_form = $("#calculation-form-" + old_code).clone();

                                calculation_form.attr("id", "calculation-form-" + new_code);
                                calculation_form.find(".window-price-sheet-calculation-quote-item-code").val(new_code);
                                // calculation_form.find(".checkbox-label").attr("for", "checkbox-" + new_code + "");
                                calculation_form.find(".checkbox-label").attr("for", "checkbox-" + new_code + "").html(this_row_no);
                                calculation_form.find(".checkbox-input").attr("id", "checkbox-" + new_code).val(new_code);
                                calculation_form.find(".checkbox-input").button();
                                calculation_form.find(".quote-item-row-no").val(this_row_no);
                                calculation_form.find(".window-price-sheet-calculation-table-more").attr("id", "calculation-table-more-" + new_code).hide();
                                calculation_form.find(".window-price-sheet-calculation-table .thead").empty();

                                this_row_no++;

      
                                $(calculation_form).appendTo(this_parent.find('.window-price-sheet-calculation-quote-items-body')).queue(function() {
                                    var this_element = $(this);
                                    var old_form = $("#calculation-form-" + old_code);
                                    $.each(old_form.serializeArray(), function(i, form_field) {
                                        this_element.find(".data-field[name='" + form_field.name + "']").val(form_field.value);
                                    });
                                    this_element.dequeue();
                                }).clearQueue();
                            });

                            // 22-09-2022
                            // this_parent.find(".checkbox-label").each(function(i) {
                            //     $(this).find(".ui-button-text").html(i + 1);
                            // });

                            this_bttn.prop('disabled', false).removeClass('field-loader');

                            window_price_sheet_calculation_group_discount(this_bttn);
                            //$('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                            //$('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                            quote_item_sort(window_price_sheet_calculation_code, true);

                        } else {
                            this_bttn.prop('disabled', false).removeClass('field-loader');
                            alert(JSON.stringify(data));
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(JSON.stringify(jqXHR));
                        alert("Error! @copy-bulk-window-price-sheet-calculation-quote-item");
                    }
                });
            }
        }
    });

    $(document).on("click", ".delete-window-price-sheet-calculation-quote-item", function() {

        var this_bttn = $(this);
        var this_form = this_bttn.parents(".update-window-price-sheet-calculation-quote-item-form");
        var code = this_form.find(".window-price-sheet-calculation-quote-item-code").val();

        var this_parent = this_form.parents(".window-price-sheet-calculation-quote-items-wrapper");
        var table_header = this_parent.find(".window-price-sheet-calculation-table .thead .thead-tr").clone();


        if (confirm("Are you sure you want to delete this item?")) {

            $.ajax({
                url: "window-price-sheet-calculation/scripts/delete-window-price-sheet-calculation-quote-item.php",
                type: 'POST',
                dataType: 'json',
                data: { cid: cid, code: code },
                beforeSend: function() {
                    this_bttn.prop('disabled', true).addClass('field-loader');
                },
                success: function(data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        this_form.hide();

                        this_parent.find(".window-price-sheet-calculation-table .thead").first().html(table_header);

                        this_bttn.prop('disabled', false).removeClass('field-loader');

                        window_price_sheet_calculation_group_discount(this_form);
                        $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                        this_form.remove();

                        if (!this_parent.find('.update-window-price-sheet-calculation-quote-item-form').length) {
                            this_parent.hide().find(".bulk-action-button-wrapper").empty();
                        } else {
                            this_parent.find(".checkbox-label").each(function(i) {
                                $(this).find(".ui-button-text").html(i + 1);
                            });
                                
                            // quote_item_sort(window_price_sheet_calculation_code, true);
                        }
                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @delete-window-price-sheet-calculation-quote-item");
                }
            });
        }
    });

    $(document).on("click", ".window-price-sheet-calculation-table-more-bttn", function() {
        var this_bttn = $(this);
        var this_form = this_bttn.parents("form");

        this_form.find(".window-price-sheet-calculation-table-more").toggle("slide", { direction: "up" }, "fast");
    });



    $(document).on("change", ".add-window-price-sheet-calculation-quote-item-form .window-price-sheet-calculation-width,\n\
                                .add-window-price-sheet-calculation-quote-item-form .window-price-sheet-calculation-drop,\n\
                                .add-window-price-sheet-calculation-quote-item-form .window-price-sheet-calculation-type,\n\
                                .add-window-price-sheet-calculation-quote-item-form .window-price-sheet-calculation-field", function() {

        check_window_price_sheet_calculation_max_min_dimensions(this);
        get_window_price_sheet_calculation_price(this);

    });

    function check_window_price_sheet_calculation_max_min_dimensions(this_elelemnt) {

        var this_form = $(this_elelemnt).parents("form");

        var width_element = this_form.find(".window-price-sheet-calculation-width");
        var drop_element = this_form.find(".window-price-sheet-calculation-drop");
        var type_element = this_form.find(".window-price-sheet-calculation-type");

        width_element.tooltip();
        drop_element.tooltip();

        if (type_element.val()) {

            var min_max_dimensions = JSON.parse(type_element.find("option:selected").attr("data-min-max-dimensions"));
            var max_width = min_max_dimensions.max_width;
            var max_drop = min_max_dimensions.max_drop;

            var width = width_element.val();
            var drop = drop_element.val();

            if (max_width < width) {
                var text = "Max Width: " + max_width;
                width_element.attr("title", text).addClass("tooltip-active").tooltip({ content: text, tooltipClass: "min_max_dimensions", position: { my: "center top+7", at: "center bottom", collision: "flipfit" }, show: { effect: "bounce", delay: 1 } }).tooltip('close').tooltip('open').off("mouseout mouseover focusout mouseleave");
                drop_element.tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
            } else
            if (max_drop < drop) {
                var text = "Max Height: " + max_drop;
                drop_element.attr("title", text).addClass("tooltip-active").tooltip({ content: text, tooltipClass: "min_max_dimensions", position: { my: "center top+7", at: "center bottom", collision: "flipfit" }, show: { effect: "bounce", delay: 1 } }).tooltip('close').tooltip('open').off("mouseout mouseover focusout mouseleave");
                width_element.tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
            } else {
                drop_element.tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
                width_element.tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
            }
        } else {
            drop_element.tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
            width_element.tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
        }
    }


    var ajax_get_price_request = $.ajax(); // empty function to avoid some errors

    function get_window_price_sheet_calculation_price(this_elelemnt) {

        var this_form = $(this_elelemnt).parents("form");
        var price_element = this_form.find(".window-price-sheet-calculation-price");
        var dyn_field = this_form.find(".window-price-sheet-calculation-field");

        var code = this_form.find(".window-price-sheet-calculation-code").val();
        var type_code = this_form.find(".window-price-sheet-calculation-type").val().split("<->")[1];
        var width = this_form.find(".window-price-sheet-calculation-width").val();
        var drop = this_form.find(".window-price-sheet-calculation-drop").val();

        if (code && type_code && width && drop) {

            ajax_get_price_request = $.ajax({
                url: "window-price-sheet-calculation/scripts/get-window-price-sheet-calculation-price.php",
                type: 'POST',
                dataType: 'json',
                data: { width: width, drop: drop, type_code: type_code, code: code },
                success: function(data) {

                    var price = 0;

                    $.each(dyn_field, function() {
                        var field_price = $(this).val().split("<->")[2];
                        price += field_price > 0 ? +field_price : 0;
                    });

                    price += +data;
                    var price_x = price.toFixed(2);
                    price_element.val(price_x);

                    if (price === 0) {
                        var text = "No Price Available";
                        price_element.attr("title", text).addClass("tooltip-active").tooltip({ content: text, tooltipClass: "min_max_dimensions", position: { my: "center top+7", at: "center bottom", collision: "flipfit" }, show: { effect: "bounce", delay: 1 } }).tooltip('close').tooltip('open').off("mouseout mouseover focusout mouseleave");
                    } else {
                        price_element.tooltip().tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error! @get-window-price-sheet-calculation-price");
                }
            });
        } else {
            //price_element.val( "" );
        }
    }

    $(document).on("change", ".window-price-sheet-calculation-width, .window-price-sheet-calculation-type", function() {
        if (quote_status !== '1' || $(this).parents("form").hasClass('add-window-price-sheet-calculation-quote-item-form')) {
            update_material(this);
        }
    });

    function update_material(this_elelemnt) {

        var this_form = $(this_elelemnt).parents("form");
        var width = +this_form.find(".window-price-sheet-calculation-width").val();

        var material_element = this_form.find(".window-price-sheet-calculation-material");
        material_element.val("");
        material_element.find('option').not(':first').remove();

        var colour_element = this_form.find(".window-price-sheet-calculation-colour");
        colour_element.val("");
        colour_element.find('option').not(':first').remove();

        var type_element = this_form.find(".window-price-sheet-calculation-type");
        var materials_and_colours = type_element.find('option:selected').attr('data-materials-and-colours');

        if (materials_and_colours) {

            $.each(JSON.parse(materials_and_colours), function(material_index, material_value) {
                var max_width = +material_value.max_width;
                var colours = JSON.stringify(material_value.colours);
                if (max_width >= width) {
                    material_element.append('<option value="' + material_value.name + '<->' + material_value.code + '" data-code="' + material_value.code + '" data-colours=\'' + colours + '\'>' + material_value.name + '</option>');
                }
            });
        }
    }

    $(document).on("change", ".window-price-sheet-calculation-material", function() {
        if (quote_status !== '1' || $(this).parents("form").hasClass('add-window-price-sheet-calculation-quote-item-form')) {
            update_colour(this);
        }
    });

    function update_colour(this_elelemnt) {
        var this_form = $(this_elelemnt).parents("form");

        var colour_element = this_form.find(".window-price-sheet-calculation-colour");
        colour_element.val("");
        colour_element.find('option').not(':first').remove();

        var type_code = this_form.find('.window-price-sheet-calculation-type option:selected').attr('data-code');
        var material_code = $(this_elelemnt).find('option:selected').attr('data-code');
        var colours = $(this_elelemnt).find('option:selected').attr('data-colours');

        if (colours) {
            $.each(JSON.parse(colours), function(colour_index, colour_value) {
                colour_element.append('<option value="' + colour_value.name + '<->' + colour_value.code + '">' + colour_value.name + '</option>');
            });
            colour_element.append('<option class="window-price-sheet-calculation-add-colour-option" data-material-code="' + material_code + '" data-type-code="' + type_code + '" value="add-new">Add New</option>');
        }
    }
    $(document).on("change", ".window-price-sheet-calculation-colour", function(event) {
        var this_element = $(this);
        var this_form = this_element.parents("form");
        var add_colour_element_parent = this_form.find(".window-price-sheet-calculation-add-colour-wrapper");
        var add_colour_element = this_form.find(".window-price-sheet-calculation-add-colour");
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        var type_code = this_element.find('option:selected').attr('data-type-code');
        var material_code = this_element.find('option:selected').attr('data-material-code');

        if (type_code && material_code) {
            this_element.toggle();
            add_colour_element_parent.toggle();
            add_colour_element.attr({ "data-material-code": material_code, "data-type-code": type_code }).focus();
            data_fields.prop("disabled", true);
        }
    });

    function window_price_sheet_calculation_add_colour(type_code, material_code, colour_name, this_elelemnt) {

        var this_element = $(this_elelemnt);
        var this_form = this_element.parents("form");
        var type_element = this_form.find(".window-price-sheet-calculation-type");
        var material_element = this_form.find(".window-price-sheet-calculation-material");
        var colour_element = this_form.find(".window-price-sheet-calculation-colour");
        var add_colour_element_parent = this_form.find(".window-price-sheet-calculation-add-colour-wrapper");
        var add_colour_element = this_form.find(".window-price-sheet-calculation-add-colour");
        var add_colour_submit = this_form.find(".window-price-sheet-calculation-add-colour-submit");
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        var type_code = type_code;
        var material_code = material_code;
        var colour_name = colour_name;

        var materials_and_colours = JSON.parse(type_element.find('option:selected').attr('data-materials-and-colours'));
        var colours = JSON.parse(material_element.find('option:selected').attr('data-colours'));

        if (material_code && colour_name) {

            $.ajax({
                url: "window-price-sheet-calculation/scripts/add-window-price-sheet-calculation-colour.php",
                type: "POST",
                dataType: "json",
                data: { material_code: material_code, colour_name: colour_name },
                beforeSend: function() {
                    add_colour_element.prop('disabled', true).addClass('field-loader');
                    add_colour_submit.prop('disabled', true).addClass('field-loader');
                },
                success: function(data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        $.each(materials_and_colours, function(material_index, material_value) {
                            if (material_value.code === material_code) {
                                material_value.colours.push({ code: data[1], name: data[2] });
                                materials_and_colours.colours = material_value.colours;
                                $(".window-price-sheet-calculation-type option[data-code=" + type_code + "]").attr('data-materials-and-colours', JSON.stringify(materials_and_colours));
                            }
                        });

                        colours.push({ code: data[1], name: data[2] });

                        $(".window-price-sheet-calculation-material option[data-code=" + material_code + "]").attr('data-colours', JSON.stringify(colours));
                        $(".window-price-sheet-calculation-add-colour-option[data-material-code=" + material_code + "][data-type-code=" + type_code + "]").before('<option value="' + data[2] + '<->' + data[1] + '">' + data[2] + '</option>');

                        add_colour_element.removeAttr("data-material-code").val("");
                        add_colour_element_parent.hide();
                        data_fields.prop("disabled", false);
                        colour_element.show().val(data[2] + '<->' + data[1]).change();
                    } else {
                        alert(data);
                    }
                    add_colour_element.prop('disabled', false).removeClass('field-loader');
                    add_colour_submit.prop('disabled', false).removeClass('field-loader');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    add_colour_element.prop('disabled', false).removeClass('field-loader');
                    add_colour_submit.prop('disabled', false).removeClass('field-loader');
                }
            });
        }
    }

    $(document).on("keypress", ".window-price-sheet-calculation-add-colour", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();

            var this_element = $(this);
            var this_form = this_element.parents("form");
            var add_colour_element = this_form.find(".window-price-sheet-calculation-add-colour");

            var type_code = add_colour_element.attr('data-type-code');
            var material_code = add_colour_element.attr('data-material-code');
            var colour_name = add_colour_element.val();


            window_price_sheet_calculation_add_colour(type_code, material_code, colour_name, this_element);
            return false;
        }
    });

    $(document).on("click", ".window-price-sheet-calculation-add-colour-submit", function(event) {
        event.preventDefault();

        var this_element = $(this);
        var this_form = this_element.parents("form");
        var add_colour_element = this_form.find(".window-price-sheet-calculation-add-colour");

        var type_code = add_colour_element.attr('data-type-code');
        var material_code = add_colour_element.attr('data-material-code');
        var colour_name = add_colour_element.val();


        window_price_sheet_calculation_add_colour(type_code, material_code, colour_name, this_element);
        return false;
    });

    $(document).on("click", ".window-price-sheet-calculation-add-colour-close", function() {
        var this_element = $(this);
        var this_form = this_element.parents("form");
        var colour_element = this_form.find(".window-price-sheet-calculation-colour");
        var add_colour_element_parent = this_form.find(".window-price-sheet-calculation-add-colour-wrapper");
        var add_colour_element = this_form.find(".window-price-sheet-calculation-add-colour");
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        colour_element.toggle().val("").change();
        add_colour_element_parent.toggle();
        add_colour_element.removeAttr("data-material-code").val("");
        data_fields.prop("disabled", false);

    });


    // Add new fields

    $(document).on("change", ".window-price-sheet-calculation-field", function(event) {
        var this_element = $(this);
        var this_form = this_element.parents("form");
        var add_field_option_element_parent = this_element.siblings(".window-price-sheet-calculation-add-field-option-wrapper");
        var add_field_option_element = add_field_option_element_parent.find(".window-price-sheet-calculation-add-field-option");
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        // If bulk Selected
        var this_chcekedbox = this_form.find(".checkbox-input");
        var checked_checkbox = this_form.parents(".window-price-sheet-calculation-quote-items-body").find(".checkbox-input:checked");

        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

            checked_checkbox.not(this_chcekedbox).each(function() {
                var this_form_x = $(this).parents(".update-window-price-sheet-calculation-quote-item-form");
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

    function window_price_sheet_calculation_add_field_option(field_option_name, this_elelemnt) {

        var this_element = $(this_elelemnt);
        var this_form = this_element.parents("form");
        var add_field_option_element_parent = this_element.parents(".window-price-sheet-calculation-add-field-option-wrapper");
        var add_field_option_element = add_field_option_element_parent.find(".window-price-sheet-calculation-add-field-option");
        var add_field_option_submit = add_field_option_element_parent.find(".window-price-sheet-calculation-add-field-option-submit");

        var field_element = add_field_option_element_parent.siblings(".window-price-sheet-calculation-field");
        var field_code = field_element.attr("data-code");
        var window_price_sheet_calculation_code = this_form.find(".window-price-sheet-calculation-code").val();
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        var field_option_name = field_option_name;

        if (field_option_name) {

            $.ajax({
                url: "window-price-sheet-calculation/scripts/add-window-price-sheet-calculation-field-option.php",
                type: "POST",
                dataType: "json",
                data: { field_code: field_code, field_option_name: field_option_name, window_price_sheet_calculation_code: window_price_sheet_calculation_code },
                beforeSend: function() {
                    add_field_option_element.prop('disabled', true).addClass('field-loader');
                    add_field_option_submit.prop('disabled', true).addClass('field-loader');
                },
                success: function(data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        $(".window-price-sheet-calculation-add-field-option-x[data-field-code=" + field_code + "]").before('<option value="' + data[2] + '<->' + data[1] + '<->0.00">' + data[2] + '</option>');

                        // If bulk Selected
                        var this_chcekedbox = this_form.find(".checkbox-input");
                        var checked_checkbox = this_form.parents(".window-price-sheet-calculation-quote-items-body").find(".checkbox-input:checked");

                        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

                            checked_checkbox.not(this_chcekedbox).each(function() {
                                var this_form_x = $(this).parents(".update-window-price-sheet-calculation-quote-item-form");
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
                error: function(jqXHR, textStatus, errorThrown) {
                    add_field_option_element.prop('disabled', false).removeClass('field-loader');
                    add_field_option_submit.prop('disabled', false).removeClass('field-loader');
                }
            });
        }
    }

    $(document).on("keypress", ".window-price-sheet-calculation-add-field-option", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();

            var this_element = $(this);
            var add_field_option_element_parent = this_element.parents(".window-price-sheet-calculation-add-field-option-wrapper");
            var add_field_option_element = add_field_option_element_parent.find(".window-price-sheet-calculation-add-field-option");

            var field_option_name = add_field_option_element.val();

            window_price_sheet_calculation_add_field_option(field_option_name, this_element);
            return false;
        }
    });

    $(document).on("click", ".window-price-sheet-calculation-add-field-option-submit", function(event) {
        event.preventDefault();

        var this_element = $(this);
        var add_field_option_element = this_element.siblings(".window-price-sheet-calculation-add-field-option");

        var field_option_name = add_field_option_element.val();


        window_price_sheet_calculation_add_field_option(field_option_name, this_element);
        return false;
    });



    $(document).on("click", ".window-price-sheet-calculation-add-field-option-close", function() {
        var this_element = $(this);
        var this_form = this_element.parents("form");
        var add_field_option_element_parent = this_element.parents(".window-price-sheet-calculation-add-field-option-wrapper");
        var add_field_option_element = add_field_option_element_parent.find(".window-price-sheet-calculation-add-field-option");
        var field_element = add_field_option_element_parent.siblings(".window-price-sheet-calculation-field");
        var data_fields = this_form.find(".data-field, .number-input, select, [type='submit']");

        // If bulk Selected
        var this_chcekedbox = this_form.find(".checkbox-input");
        var checked_checkbox = this_form.parents(".window-price-sheet-calculation-quote-items-body").find(".checkbox-input:checked");

        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

            checked_checkbox.not(this_chcekedbox).each(function() {
                var this_form_x = $(this).parents(".update-window-price-sheet-calculation-quote-item-form");
                var data_fields_x = this_form_x.find(".data-field, .number-input, select, [type='submit']");
                data_fields_x.prop("disabled", false);
            });
        }

        add_field_option_element.val("");
        add_field_option_element_parent.hide();
        data_fields.prop("disabled", false);
        field_element.show().val("").change().focus();
    });

    function window_price_sheet_calculation_group_discount(this_element) {

        var result_parent = $(this_element).parents(".window-price-sheet-calculation-quote-items-wrapper");
        var quote_items_parent = result_parent.find(".window-price-sheet-calculation-quote-items-body");
        var price_field = quote_items_parent.find("input[name='price']:not(:hidden)");
        var group_discount = $.isNumeric(+result_parent.find(".group-discount").val()) ? +result_parent.find(".group-discount").val() : 0;

        var total_price = 0;

        price_field.each(function() {
            total_price += Number($(this).val().replace(/,/g, ''));
        });

        var group_discount_total = total_price - (total_price * group_discount / 100);


        result_parent.find(".group-total").val(total_price.toFixed(2)).end()
            .find(".group-discount").val(group_discount).end()
            .find(".group-discount-total").html(group_discount_total.toFixed(2)).end()
            .find(".group-discount-form").submit();

    }

    $(document).on("submit", ".add-window-price-sheet-calculation-quote-item-form", function(event) {
        event.preventDefault();

        var this_form = $(this);
        var this_submit_bttn = this_form.find('[type="submit"]');
        var this_table_thead_tr = this_form.find(".window-price-sheet-calculation-table .thead-tr");
        var this_table_tbody_tr = this_form.find(".window-price-sheet-calculation-table .tbody-tr");
        var window_price_sheet_calculation_code = this_form.find(".window-price-sheet-calculation-code").val();
        var accessory_option_array = this_form.find(".accessory-option-array").val();
        var per_meter_option_array = this_form.find(".per-meter-option-array").val();
        var fitting_charge_option_array = this_form.find(".fitting-charge-option-array").val();

        var result_parent = $("#window-price-sheet-calculation-quote-items-wrapper-" + window_price_sheet_calculation_code);

        var group_discount = $.isNumeric(+result_parent.find(".group-discount").val()) ? +result_parent.find(".group-discount").val() : 0;

        if (user_type === 4) {

            var main_cal_code = $("ul.main-calculations li.ui-tabs-active a.ui-tabs-anchor").attr("data-calc-code");
            console.log("main_cal_code", main_cal_code);

            var row_length = result_parent.find(".update-window-price-sheet-calculation-quote-item-form").length;
            if (!row_length) {
                group_discount = trader_calcs.find(x => x.code === main_cal_code)['discount'];
                console.log("group_discount", group_discount);
            }
        }

        var datas = this_form.serialize();

        // console.log(this_form.serializeArray());
    
        var max_row_no = -Infinity;
        $(document).find("#quote-items-body-" + window_price_sheet_calculation_code).find(".quote-item-row-no").each(function () {
            max_row_no = Math.max(max_row_no, parseFloat(this.value));
        });
    
        // console.log("max_row_no", max_row_no)
    
        var this_row_no = max_row_no == -Infinity ? 1 : max_row_no + 1;

        $.ajax({
            url: "window-price-sheet-calculation/scripts/add-window-price-sheet-calculation-quote-item.php",
            type: 'POST',
            dataType: 'json',
            data: datas,
            beforeSend: function(xhr) {
                this_submit_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function(data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_submit_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    $(this_form).find('.tooltip-active').tooltip().tooltip('close').tooltip("destroy").removeAttr("title").removeClass("tooltip-active");
                    $(this_form).find(".data-field:first").focus();

                    $.each(data[1], function(index, code) {

                        if (!$("#quote-items-body-" + window_price_sheet_calculation_code + " .window-price-sheet-calculation-table").length) {

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
                            $("#window-price-sheet-calculation-quote-items-wrapper-" + window_price_sheet_calculation_code).show();
                        } else {
                            newe_table_head_tr_x = '';
                        }

                        var newe_table_body_tr = this_table_tbody_tr.clone();

                        newe_table_body_tr.prepend('<td class="left-fixed-column">\n\
                                <label class="checkbox-label" for="checkbox-' + code + '">' + this_row_no + '</label>\n\
                                <input type="checkbox" id="checkbox-' + code + '" class="checkbox-input" name="quote-item-codes[]" value="' + code + '">\n\
                                <input type="hidden" class="quote-item-row-no" name="quote-item-row-no[]" value="' + this_row_no + '">\n\
                            </td>');

                        // newe_table_body_tr.prepend('<td class="left-fixed-column">\n\
                        //                                 <label class="checkbox-label" for="checkbox-' + code + '"></label>\n\
                        //                                 <input type="checkbox" id="checkbox-' + code + '" class="checkbox-input" name="quote-item-codes[]" value="' + code + '">\n\
                        //                             </td>');
                        newe_table_body_tr.find(".right-fixed-column").html('<tr>\n\
                                                                                <td><input type="tel" name="price" data-column="price" class="window-price-sheet-calculation-price price-input data-field ui-state-default" autocomplete="off"></td>\n\
                                                                                <td><input type="button" class="window-price-sheet-calculation-table-more-bttn bttn-input" value="★"></td>\n\
                                                                                <td><input type="button" class="bttn-input delete-window-price-sheet-calculation-quote-item" value="✖"></td>\n\
                                                                            </tr>');

                        if (quote_status === '1') {
                            newe_table_body_tr.find(".approved-disabled").css({ "pointer-events": "none", "background": "#f2f2f2" });
                        }
                        if (etape === 'on') {
                            newe_table_body_tr.find(".etape-td").remove();
                        }

                        if (accessory_option_array) {

                            var accessory_element = "";
                            var accessory_options = "";

                            $.each(JSON.parse(accessory_option_array), function(accessory_option_index, accessory_option_value) {
                                accessory_options += '<option data-code="' + accessory_option_value.code + '" value="' + accessory_option_value.code + '<->' + accessory_option_value.name + '<->' + accessory_option_value.price + '">' + accessory_option_value.name + ' | ' + accessory_option_value.price + '</option>';
                            });
                            accessory_element = '<span class="window-price-sheet-calculation-accessories-wrapper">\n\
                                                    <select class="window-price-sheet-calculation-accessories">\n\
                                                        <option value="">Accessories</option>\n\
                                                        ' + accessory_options + '\
                                                    </select>\n\
                                                    <input type="tel" class="add-window-price-sheet-calculation-accessory-mm qty-input" placeholder="mm">\n\
                                                    <input type="tel" class="add-window-price-sheet-calculation-accessory-qty qty-input" placeholder="Qty">\n\
                                                    <input type="button" class="add-window-price-sheet-calculation-accessories bttn-input" value="✚">\n\
                                                </span>';

                        } else {
                            accessory_element = '';
                        }

                        if (per_meter_option_array) {

                            var per_meter_element = "";
                            var per_meter_options = "";

                            $.each(JSON.parse(per_meter_option_array), function(per_meter_option_index, per_meter_option_value) {
                                per_meter_options += '<option data-code="' + per_meter_option_value.code + '" value="' + per_meter_option_value.code + '<->' + per_meter_option_value.name + '<->' + per_meter_option_value.price + '">' + per_meter_option_value.name + ' | ' + per_meter_option_value.price + '</option>';
                            });
                            per_meter_element = '<span class="window-price-sheet-calculation-per-meters-wrapper">\n\
                                                    <select class="window-price-sheet-calculation-per-meters">\n\
                                                        <option value="">Per Meters</option>\n\
                                                        ' + per_meter_options + '\
                                                    </select>\n\
                                                    <input type="tel" class="add-window-price-sheet-calculation-per-meter-width qty-input" placeholder="Width">\n\
                                                    <input type="button" class="add-window-price-sheet-calculation-per-meters bttn-input" value="✚">\n\
                                                </span>';

                        } else {
                            per_meter_element = '';
                        }

                        if (fitting_charge_option_array) {

                            var fitting_charge_element = "";
                            var fitting_charge_options = "";

                            $.each(JSON.parse(fitting_charge_option_array), function(fitting_charge_option_index, fitting_charge_option_value) {
                                fitting_charge_options += '<option data-code="' + fitting_charge_option_value.code + '" value="' + fitting_charge_option_value.code + '<->' + fitting_charge_option_value.name + '<->' + fitting_charge_option_value.price + '">' + fitting_charge_option_value.name + ' | ' + fitting_charge_option_value.price + '</option>';
                            });
                            fitting_charge_element = '<span class="window-price-sheet-calculation-fitting-charges-wrapper">\n\
                                                        <select class="window-price-sheet-calculation-fitting-charges">\n\
                                                            <option value="">Fitting Charges</option>\n\
                                                            ' + fitting_charge_options + '\
                                                        </select>\n\
                                                        <input type="button" class="add-window-price-sheet-calculation-fitting-charges bttn-input" value="✚">\n\
                                                    </span>';

                        } else {
                            fitting_charge_element = '';
                        }

                        $('<form id="calculation-form-' + code + '" class="update-window-price-sheet-calculation-quote-item-form" action="./" method="POST">\n\
                                <input type="hidden" name="calc-name" class="calc-name" value="window_price_sheet">\n\
                                <input type="hidden" name="cid" class="cid" value="' + cid + '">\n\
                                <input type="hidden" name="window-price-sheet-calculation-code" class="window-price-sheet-calculation-code" value="' + window_price_sheet_calculation_code + '">\n\
                                <input type="hidden" name="window-price-sheet-calculation-quote-item-code" class="window-price-sheet-calculation-quote-item-code" value="' + code + '">\n\
                                <table class="window-price-sheet-calculation-table row_item_table">\n\
                                    <thead class="thead">\n\
                                        <tr class="thead-tr">' + newe_table_head_tr_x + '</tr>\n\
                                    </thead>\n\
                                    <tbody>\n\
                                        ' + newe_table_body_tr.html() + '\
                                    </tbody>\n\
                                </table>\n\
                                <table id="calculation-table-more-' + code + '"  class="window-price-sheet-calculation-table-more">\n\
                                    <tr>\n\
                                        <td class="table-more-notes" colspan="3">\n\
                                            <textarea name="notes" data-column="notes" class="window-price-sheet-calculation-notes data-field" placeholder="Notes"></textarea>\n\
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
                            </form>').appendTo("#quote-items-body-" + window_price_sheet_calculation_code).queue(function() {

                            var this_element = $(this);
                            $.each(this_form.serializeArray(), function(i, form_field) {
                                this_element.find("[name='" + form_field.name + "']").val(form_field.value);
                            });

                            this_element.find(".bttn-input, .checkbox-input").button();
                            this_element.dequeue();

                        }).clearQueue();

                        this_row_no++;
                    });
                    result_parent.find('.bulk-action-button-wrapper').html('<input type="button" class="bulk-action-button window-price-sheet-calculation-select-all-bttn" value="✔">\n\
                                                                            <input type="button" class="bulk-action-button window-price-sheet-calculation-bulk-delete-bttn" value="Delete">\n\
                                                                            <input type="button" class="bulk-action-button window-price-sheet-calculation-bulk-copy-bttn" value="Copy">\n\
                                                                            <button class="fa-btn window-price-sheet-calculation-bulk-copy-quote-bttn copy-quote-bttn tltip bulk-action-button" style="padding-left:5px; padding-right:5px;" data-title="Copy Quote">Copy Quote</button>\n\
                                                                            <button class="fa-btn quote-item-sort-bttn tltip bulk-action-button" style="padding-left:5px; padding-right:5px;" data-calc-code="'+ window_price_sheet_calculation_code + '" data-title="Move">Move<i class="fa fa-sort" aria-hidden="true"></i></button>\n\
                                                                            <a href="print/order-sheet-window-price-sheet-calculation-' + window_price_sheet_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 110px;">Print Order Sheet</a>\n\
                                                                            <a href="https://file-gen.blinq.com.au/clients/' + user_account + '/order-sheet-xlsx-window-price-sheet-calculation-' + window_price_sheet_calculation_code + '-' + cid + '" target="_blank" class="bulk-action-button print-bttn" style="width: 140px;">Print Order Sheet Xlsx</a>\n\
                                                                            <form style="display: contents; margin-right: -6px; font-size: 14px;">\n\
                                                                            <input type="tel" class="production-date quote_details" id="production-date" data-customer-id="' + customer_id + '" data-customer-no="' + customer_no + '" data-quote-id="' + quote_id + '" data-quote-no="' + quote_no + '" data-job-no="' + job_no + '"  data-pro-code="' + window_price_sheet_calculation_code + '"A placeholder="Production Date" autocomplete="off" value=""style="width:100px; height:35px !important; margin-left:2px; margin-right:2px;text-align: center; border-radius: 6px; border: 1px solid #cdd5da;  ' +  approved_date + '">\n\
                                                                            <input type="tel" class="install-date quote_details" id="install-date" data-customer-id="' + customer_id + '" data-customer-no="' + customer_no + '" data-quote-id="' + quote_id + '" data-quote-no="' + quote_no + '" data-job-no="' + job_no + '"  data-pro-code="' + window_price_sheet_calculation_code + '"  placeholder="Install Date" autocomplete="off" value=""style="width:100px; height:35px !important; margin-left:2px; margin-right:2px;text-align: center; border-radius: 6px; border: 1px solid #cdd5da;  ' +  approved_date + '">\n\
                                                                            </form>\n\
                                                                            <form class="group-discount-form" style="float: right; margin-right: -6px; font-size: 12px;">\n\
                                                                                <input type="tel" class="group-total price-input ui-state-default" autocomplete="off" style="float: left;" disabled value="">\n\
                                                                                <input type="tel" class="group-discount price-input ui-state-default" autocomplete="off" placeholder="%" value="' + group_discount + '" style="float: left; margin-left: 1px !important; margin-right: 1px !important; text-align: center; width: 33px;" required value="">\n\
                                                                                <input type="hidden" class="window-price-sheet-calculation-code" value="' + window_price_sheet_calculation_code + '">\n\
                                                                                <input type="submit" class="bttn-input ui-button ui-widget ui-state-default ui-corner-all" value="✔" role="button" aria-disabled="false" style="float: left;">\n\
                                                                            </form>\n\
                                                                            <div class="group-discount-total" style="float: right; line-height: 40px; margin-right: 40px; font-size: 22px; font-weight: bold;"></div>\n\
                                                                        ').find(".bulk-action-button").button().end()
                                                                        .find(".production-date").datepicker({ dateFormat: "dd/mm/yy" }).end()
                                                                        .find(".install-date").datepicker({ dateFormat: "dd/mm/yy" });

                    result_parent.find(".checkbox-label").each(function(i) {
                        $(this).find(".ui-button-text").html(i + 1);
                    });

                    quote_item_sort(window_price_sheet_calculation_code, true);

                    this_form[0].reset();
                    this_form.find(".window-price-sheet-calculation-material").val("").find('option').not(':first').remove();
                    this_form.find(".window-price-sheet-calculation-colour").val("").find('option').not(':first').remove();
                    this_submit_bttn.prop('disabled', false).removeClass('field-loader');

                    window_price_sheet_calculation_group_discount(result_parent.find(".group-discount-form"));

                    //$('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    //$('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_submit_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                this_submit_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @add-window-price-sheet-calculation-quote-item");
            }
        });
    });

    function update_window_price_sheet_calculation_quote_item(this_element, quote_items_array) {

        var this_field = $(this_element);
        var this_field_value = this_field.val();

        if (this_field_value === "add-new") {
            return false;
            // Prevent this function to add new colour
        }

        var this_form = this_field.parents(".update-window-price-sheet-calculation-quote-item-form");
        var this_chcekedbox = this_form.find(".checkbox-input");
        var checked_checkbox = this_form.parents(".window-price-sheet-calculation-quote-items-body").find(".checkbox-input:checked");

        $.ajax({
            url: "window-price-sheet-calculation/scripts/update-window-price-sheet-calculation-quote-item.php",
            type: 'POST',
            dataType: 'json',
            data: { quote_items_array: quote_items_array },
            beforeSend: function() {
                //this_field.addClass('field-loader');
            },
            success: function(data) {
                if (data[0] === 4) {
                    document.location.replace(data[1]);
                } else
                if (data[0] === 2) {
                    this_field.removeClass('field-loader');
                    alert(data[1]);
                } else
                if (data[0] === 1) {

                    if (this_chcekedbox.is(":checked") && checked_checkbox.length) {
                        checked_checkbox.each(function() {
                            var this_form_x = $(this).parents(".update-window-price-sheet-calculation-quote-item-form");

                            var note_length = this_form_x.find('.window-price-sheet-calculation-notes').val().length;
                            var accessory_length = this_form_x.find('.window-price-sheet-calculation-accessory').length;
                            var per_meter_length = this_form_x.find('.window-price-sheet-calculation-per-meter').length;
                            var fitting_charge_length = this_form_x.find('.window-price-sheet-calculation-fitting-charge').length;

                            if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                                this_form_x.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                            } else {
                                this_form_x.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");
                            }
                        });

                    } else {

                        var note_length = this_form.find('.window-price-sheet-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                        var per_meter_length = this_form.find('.window-price-sheet-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");
                        }
                    }

                    this_field.removeClass('field-loader');

                    //if (this_field.attr("name") === "width" || this_field.attr("name") === "drop" || this_field.attr("name") === "type" || this_field.attr("name") === "price") {
                    window_price_sheet_calculation_group_discount(this_form);
                    //} else {
                    //$('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    //$('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");
                    //}

                } else {
                    this_field.removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                this_field.removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @update-window-price-sheet-calculation-quote-item");
            }
        });
    }

    $(document).on("change", ".update-window-price-sheet-calculation-quote-item-form .data-field", function(event) {
        event.preventDefault();

        var this_field = $(this);
        var this_field_name = this_field.attr("name");
        var this_field_value = this_field.val();

        if (this_field_value === "add-new") {
            return false;
            // Prevent this function to add new colour
        }

        this_field.toggleClass('field-loader');

        var this_form = this_field.parents(".update-window-price-sheet-calculation-quote-item-form");
        var this_chcekedbox = this_form.find(".checkbox-input");
        var checked_checkbox = this_form.parents(".window-price-sheet-calculation-quote-items-body").find(".checkbox-input:checked");

        var quote_items_array = "";

        if (this_chcekedbox.is(":checked") && checked_checkbox.length) {

            quote_items_array = [];
            var no = 0;
            checked_checkbox.each(function() {
                var this_form_x = $(this).parents(".update-window-price-sheet-calculation-quote-item-form");
                var this_field_x = this_form_x.find("[name='" + this_field_name + "']");

                this_field_x.val(this_field_value);

                if (this_field_x.attr("name") === "width" || this_field_x.attr("name") === "drop" || this_field_x.attr("name") === "type" || this_field_x.hasClass('window-price-sheet-calculation-field')) {
                    if (quote_status !== '1') {
                        get_window_price_sheet_calculation_price(this_field_x);
                    }
                }
                if (this_field_x.attr("name") === "width" || this_field_x.attr("name") === "type") {
                    if (quote_status !== '1') {
                        update_material(this_field_x);
                    }
                }
                if (this_field_x.attr("name") === "material") {
                    update_colour(this_field_x);
                }

                if (this_field_x.attr("name") === "width" || this_field_x.attr("name") === "drop" || this_field_x.attr("name") === "type" || this_field_x.hasClass('window-price-sheet-calculation-field')) {

                    if (quote_status !== '1') {
                        ajax_get_price_request.done(function() {
                            no = no + 1;
                            quote_items_array.push(this_form_x.serialize());
                            if (checked_checkbox.length === no) {
                                update_window_price_sheet_calculation_quote_item(this_field, quote_items_array);
                            }
                        });
                    } else {
                        no = no + 1;
                        quote_items_array.push(this_form_x.serialize());
                        if (checked_checkbox.length === no) {
                            update_window_price_sheet_calculation_quote_item(this_field, quote_items_array);
                        }
                    }
                } else {
                    no = no + 1;
                    quote_items_array.push(this_form_x.serialize());
                    if (checked_checkbox.length === no) {
                        update_window_price_sheet_calculation_quote_item(this_field, quote_items_array);
                    }
                }
            });

        } else {

            if (this_field.attr("name") === "width" || this_field.attr("name") === "drop" || this_field.attr("name") === "type" || this_field.hasClass('window-price-sheet-calculation-field')) {
                if (quote_status !== '1') {
                    get_window_price_sheet_calculation_price(this_field);
                }
            }
            if (this_field.attr("name") === "width" || this_field.attr("name") === "type") {
                if (quote_status !== '1') {
                    update_material(this_field);
                }
            }
            if (this_field.attr("name") === "material") {
                update_colour(this_field);
            }

            if (this_field.attr("name") === "width" || this_field.attr("name") === "drop" || this_field.attr("name") === "type" || this_field.hasClass('window-price-sheet-calculation-field')) {
                if (quote_status !== '1') {
                    ajax_get_price_request.done(function() {
                        quote_items_array = [this_form.serialize()];
                        update_window_price_sheet_calculation_quote_item(this_field, quote_items_array);
                    });
                } else {
                    quote_items_array = [this_form.serialize()];
                    update_window_price_sheet_calculation_quote_item(this_field, quote_items_array);
                }
            } else {
                quote_items_array = [this_form.serialize()];
                update_window_price_sheet_calculation_quote_item(this_field, quote_items_array);
            }
        }
    });

    $(document).on("keypress", ".add-window-price-sheet-calculation-accessory-qty", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var this_element = $(this);
            var add_bttn = this_element.siblings('.add-window-price-sheet-calculation-accessories');
            add_bttn.click();
        }
    });

    $(document).on("click", ".add-window-price-sheet-calculation-accessories", function() {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-price-sheet-calculation-quote-item-form');
        var accessory_element = this_bttn.siblings('.window-price-sheet-calculation-accessories');
        var accessory_qty_element = this_bttn.siblings('.add-window-price-sheet-calculation-accessory-qty');
        var accessory_mm_element = this_bttn.siblings('.add-window-price-sheet-calculation-accessory-mm');

        var window_price_sheet_calculation_code = this_form.find('.window-price-sheet-calculation-code').val();
        var window_price_sheet_calculation_quote_item_code = this_form.find('.window-price-sheet-calculation-quote-item-code').val();
        var accessory = accessory_element.val();
        var accessory_mm = accessory_mm_element.val();
        var accessory_qty = +accessory_qty_element.val();
        accessory_qty = accessory_qty !== 0 ? accessory_qty : 1;

        if (accessory) {

            $.ajax({
                url: "window-price-sheet-calculation/scripts/add-window-price-sheet-calculation-quote-item-accessory.php",
                type: 'POST',
                dataType: 'json',
                data: { cid: cid, window_price_sheet_calculation_code: window_price_sheet_calculation_code, window_price_sheet_calculation_quote_item_code: window_price_sheet_calculation_quote_item_code, accessory: accessory, accessory_qty: accessory_qty, accessory_mm:accessory_mm },
                beforeSend: function() {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                    accessory_element.addClass('field-loader');
                    accessory_qty_element.addClass('field-loader');
                    accessory_mm_element.addClass('field-loader');
                },
                success: function(data) {
                    if (data[0] === 4) {
                        document.location.replace(data[1]);
                    } else
                    if (data[0] === 2) {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        accessory_element.removeClass('field-loader');
                        accessory_qty_element.removeClass('field-loader');
                        accessory_mm_element.removeClass('field-loader');
                        alert(data[1]);
                    } else
                    if (data[0] === 1) {

                        var accessory_code = accessory.split("<->")[0];
                        var accessory_name = accessory.split("<->")[1];
                        var accessory_price = +accessory.split("<->")[2].replace(/,/g, "");
                        var accessory_total = (accessory_price * accessory_qty).toFixed(2);

                        this_form.find(".table-more-accessories").append('<span class="window-price-sheet-calculation-accessories-wrapper">\n\
                                                                                <input type="text" class="window-price-sheet-calculation-accessory" readonly disabled value="' + accessory_name + ' | ' + accessory_mm + ' | ' + accessory_price + ' x ' + accessory_qty + ' = ' + accessory_total + '">\n\
                                                                                <input type="button" class="delete-window-price-sheet-calculation-accessory bttn-input" data-code="' + accessory_code + '" value="✖">\n\
                                                                            </span>').find(".bttn-input").button();

                        // accessory_element.find('option[data-code="' + accessory_code + '"]').hide();
                        accessory_element.val("").focus();
                        accessory_qty_element.val("");
                        accessory_mm_element.val("");

                        var note_length = this_form.find('.window-price-sheet-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                        var per_meter_length = this_form.find('.window-price-sheet-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");
                        }

                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        accessory_element.removeClass('field-loader');
                        accessory_qty_element.removeClass('field-loader');
                        accessory_mm_element.removeClass('field-loader');

                        $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                        $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                    } else {
                        this_bttn.prop('disabled', false).removeClass('field-loader');
                        accessory_element.removeClass('field-loader');
                        accessory_qty_element.removeClass('field-loader');
                        accessory_mm_element.removeClass('field-loader');
                        alert(JSON.stringify(data));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    accessory_element.removeClass('field-loader');
                    accessory_qty_element.removeClass('field-loader');
                    accessory_mm_element.removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @add-window-price-sheet-calculation-quote-item-accessory");
                }
            });
        }
    });

    $(document).on("click", ".delete-window-price-sheet-calculation-accessory", function() {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-price-sheet-calculation-quote-item-form');
        var accessory_element = this_form.find('.window-price-sheet-calculation-accessories');
        var this_wrapper = this_bttn.parent('.window-price-sheet-calculation-accessories-wrapper');

        var window_price_sheet_calculation_code = this_form.find('.window-price-sheet-calculation-code').val();
        var window_price_sheet_calculation_quote_item_code = this_form.find('.window-price-sheet-calculation-quote-item-code').val();
        var accessory_code = this_bttn.attr("data-code");

        $.ajax({
            url: "window-price-sheet-calculation/scripts/delete-window-price-sheet-calculation-quote-item-accessory.php",
            type: 'POST',
            dataType: 'json',
            data: { cid: cid, window_price_sheet_calculation_code: window_price_sheet_calculation_code, window_price_sheet_calculation_quote_item_code: window_price_sheet_calculation_quote_item_code, accessory_code: accessory_code },
            beforeSend: function() {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function(data) {
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

                    var note_length = this_form.find('.window-price-sheet-calculation-notes').val().length;
                    var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                    var per_meter_length = this_form.find('.window-price-sheet-calculation-per-meter').length;
                    var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                    if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                        this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                    } else {
                        this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");
                    }

                    this_bttn.prop('disabled', false).removeClass('field-loader');

                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @delete-window-price-sheet-calculation-quote-item-accessory");
            }
        });
    });

    $(document).on("keypress", ".add-window-price-sheet-calculation-per-meter-width", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var this_element = $(this);
            var add_bttn = this_element.siblings('.add-window-price-sheet-calculation-per-meters');
            add_bttn.click();
        }
    });

    $(document).on("click", ".add-window-price-sheet-calculation-per-meters", function() {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-price-sheet-calculation-quote-item-form');
        var per_meter_element = this_bttn.siblings('.window-price-sheet-calculation-per-meters');
        var per_meter_width_element = this_bttn.siblings('.add-window-price-sheet-calculation-per-meter-width');

        var window_price_sheet_calculation_code = this_form.find('.window-price-sheet-calculation-code').val();
        var window_price_sheet_calculation_quote_item_code = this_form.find('.window-price-sheet-calculation-quote-item-code').val();
        var per_meter = per_meter_element.val();
        var per_meter_width = +per_meter_width_element.val();
        per_meter_width = per_meter_width !== 0 ? per_meter_width : 1;

        if (per_meter) {

            $.ajax({
                url: "window-price-sheet-calculation/scripts/add-window-price-sheet-calculation-quote-item-per-meter.php",
                type: 'POST',
                dataType: 'json',
                data: { cid: cid, window_price_sheet_calculation_code: window_price_sheet_calculation_code, window_price_sheet_calculation_quote_item_code: window_price_sheet_calculation_quote_item_code, per_meter: per_meter, per_meter_width: per_meter_width },
                beforeSend: function() {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                    per_meter_element.addClass('field-loader');
                    per_meter_width_element.addClass('field-loader');
                },
                success: function(data) {
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
                        var per_meter_price = +per_meter.split("<->")[2].replace(/,/g, "");
                        var per_meter_total = (per_meter_price * per_meter_width).toFixed(2);

                        this_form.find(".table-more-per-meters").append('<span class="window-price-sheet-calculation-per-meters-wrapper">\n\
                                                                                <input type="text" class="window-price-sheet-calculation-per-meter" readonly disabled value="' + per_meter_name + ' | ' + per_meter_price + ' x ' + per_meter_width + ' = ' + per_meter_total + '">\n\
                                                                                <input type="button" class="delete-window-price-sheet-calculation-per-meter bttn-input" data-code="' + per_meter_code + '" value="✖">\n\
                                                                            </span>').find(".bttn-input").button();

                        per_meter_element.find('option[data-code="' + per_meter_code + '"]').hide();
                        per_meter_element.val("").focus();
                        per_meter_width_element.val("");

                        var note_length = this_form.find('.window-price-sheet-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                        var per_meter_length = this_form.find('.window-price-sheet-calculation-per-meter').length;
                        var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                        if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");
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
                error: function(jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    per_meter_element.removeClass('field-loader');
                    per_meter_width_element.removeClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @add-window-price-sheet-calculation-quote-item-per-meter");
                }
            });
        }
    });

    $(document).on("click", ".delete-window-price-sheet-calculation-per-meter", function() {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-price-sheet-calculation-quote-item-form');
        var per_meter_element = this_form.find('.window-price-sheet-calculation-per-meters');
        var this_wrapper = this_bttn.parent('.window-price-sheet-calculation-per-meters-wrapper');

        var window_price_sheet_calculation_code = this_form.find('.window-price-sheet-calculation-code').val();
        var window_price_sheet_calculation_quote_item_code = this_form.find('.window-price-sheet-calculation-quote-item-code').val();
        var per_meter_code = this_bttn.attr("data-code");

        $.ajax({
            url: "window-price-sheet-calculation/scripts/delete-window-price-sheet-calculation-quote-item-per-meter.php",
            type: 'POST',
            dataType: 'json',
            data: { cid: cid, window_price_sheet_calculation_code: window_price_sheet_calculation_code, window_price_sheet_calculation_quote_item_code: window_price_sheet_calculation_quote_item_code, per_meter_code: per_meter_code },
            beforeSend: function() {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function(data) {
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

                    var note_length = this_form.find('.window-price-sheet-calculation-notes').val().length;
                    var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                    var per_meter_length = this_form.find('.window-price-sheet-calculation-per-meter').length;
                    var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                    if (note_length || accessory_length || per_meter_length || fitting_charge_length) {
                        this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                    } else {
                        this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");
                    }

                    this_bttn.prop('disabled', false).removeClass('field-loader');

                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @delete-window-price-sheet-calculation-quote-item-accessory");
            }
        });
    });

    $(document).on("click", ".add-window-price-sheet-calculation-fitting-charges", function() {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-price-sheet-calculation-quote-item-form');
        var fitting_charge_element = this_bttn.siblings('.window-price-sheet-calculation-fitting-charges');

        var window_price_sheet_calculation_code = this_form.find('.window-price-sheet-calculation-code').val();
        var window_price_sheet_calculation_quote_item_code = this_form.find('.window-price-sheet-calculation-quote-item-code').val();
        var fitting_charge = fitting_charge_element.val();

        if (fitting_charge) {

            $.ajax({
                url: "window-price-sheet-calculation/scripts/add-window-price-sheet-calculation-quote-item-fitting-charge.php",
                type: 'POST',
                dataType: 'json',
                data: { cid: cid, window_price_sheet_calculation_code: window_price_sheet_calculation_code, window_price_sheet_calculation_quote_item_code: window_price_sheet_calculation_quote_item_code, fitting_charge: fitting_charge },
                beforeSend: function() {
                    this_bttn.prop('disabled', false).addClass('field-loader');
                    fitting_charge_element.toggleClass('field-loader');
                },
                success: function(data) {
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

                        this_form.find(".table-more-fitting-charges").append('<span class="window-price-sheet-calculation-fitting-charges-wrapper">\n\
                                                                                    <input type="text" class="window-price-sheet-calculation-fitting-charge" readonly disabled value="' + fitting_charge_name + ' | ' + fitting_charge_price + '">\n\
                                                                                    <input type="button" class="delete-window-price-sheet-calculation-fitting-charge bttn-input" data-code="' + fitting_charge_code + '" value="✖">\n\
                                                                                </span>').find(".bttn-input").button();

                        fitting_charge_element.find('option[data-code="' + fitting_charge_code + '"]').hide();
                        fitting_charge_element.val("");

                        var note_length = this_form.find('.window-price-sheet-calculation-notes').val().length;
                        var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                        var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                        if (note_length || accessory_length || fitting_charge_length) {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                        } else {
                            this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");;
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
                error: function(jqXHR, textStatus, errorThrown) {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    fitting_charge_element.toggleClass('field-loader');
                    alert(JSON.stringify(jqXHR));
                    alert("Error! @add-window-price-sheet-calculation-quote-item-fitting-charge");
                }
            });
        }
    });

    $(document).on("click", ".delete-window-price-sheet-calculation-fitting-charge", function() {

        var this_bttn = $(this);
        var this_form = this_bttn.parents('.update-window-price-sheet-calculation-quote-item-form');
        var fitting_charge_element = this_form.find('.window-price-sheet-calculation-fitting-charges');
        var this_wrapper = this_bttn.parent('.window-price-sheet-calculation-fitting-charges-wrapper');

        var window_price_sheet_calculation_code = this_form.find('.window-price-sheet-calculation-code').val();
        var window_price_sheet_calculation_quote_item_code = this_form.find('.window-price-sheet-calculation-quote-item-code').val();
        var fitting_charge_code = this_bttn.attr("data-code");

        $.ajax({
            url: "window-price-sheet-calculation/scripts/delete-window-price-sheet-calculation-quote-item-fitting-charge.php",
            type: 'POST',
            dataType: 'json',
            data: { cid: cid, window_price_sheet_calculation_code: window_price_sheet_calculation_code, window_price_sheet_calculation_quote_item_code: window_price_sheet_calculation_quote_item_code, fitting_charge_code: fitting_charge_code },
            beforeSend: function() {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function(data) {
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

                    var note_length = this_form.find('.window-price-sheet-calculation-notes').val().length;
                    var accessory_length = this_form.find('.window-price-sheet-calculation-accessory').length;
                    var fitting_charge_length = this_form.find('.window-price-sheet-calculation-fitting-charge').length;

                    if (note_length || accessory_length || fitting_charge_length) {
                        this_form.find('.window-price-sheet-calculation-table-more-bttn').addClass("red");
                    } else {
                        this_form.find('.window-price-sheet-calculation-table-more-bttn').removeClass("red");;
                    }

                    this_bttn.prop('disabled', false).removeClass('field-loader');

                    $('#getsum').load('getsum.php?cid=' + cid).fadeIn("fast");
                    $('#totalx').load('megaTotal.php?cid=' + cid).fadeIn("fast");

                } else {
                    this_bttn.prop('disabled', false).removeClass('field-loader');
                    alert(JSON.stringify(data));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @delete-window-price-sheet-calculation-quote-item-fitting-charge");
            }
        });
    });

    $(document).on("submit", "#window-price-sheet-calculation-quote-items-results .group-discount-form", function(event) {
        event.preventDefault();

        var this_form = $(this);
        var this_bttn = $(this).find("input[type='submit']");
        var discount = isNaN(this_form.find(".group-discount").val()) ? '0' : this_form.find(".group-discount").val();
        var total = isNaN(this_form.find(".group-total").val()) ? '0' : this_form.find(".group-total").val();
        var window_price_sheet_calculation_code = this_form.find(".window-price-sheet-calculation-code").val();


        var group_discount_total = (total - (total * discount / 100)).toFixed(2);

        $.ajax({
            url: "window-price-sheet-calculation/scripts/update-window-price-sheet-calculation-group-discount.php",
            type: 'POST',
            dataType: 'json',
            data: { cid: cid, discount: discount, window_price_sheet_calculation_code: window_price_sheet_calculation_code },
            beforeSend: function() {
                this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function(data) {
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
            error: function(jqXHR, textStatus, errorThrown) {
                this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @update-window-price-sheet-calculation-group-discount");
            }
        });

    });

    $(document).on("change", "#window-price-sheet-calculation-quote-items-results .production-date", function(event) {
        event.preventDefault();

        var this_form = $(this);
        // var this_bttn = $(this).find("input[type='submit']");
        //  var total = isNaN(this_form.find(".state-approved").val()) ? '0' : this_form.find(".group-total").val();
        var window_price_sheet_calculation_code = this_form.find(".window-price-sheet-calculation-code").val();
        var window_price_sheet_calculation_name = this_form.find(".window-price-sheet-calculation-name").val();
        var state_approved = this_form.val();
        var state_select = this_form.find(".state-select").val();
        var installer_select = this_form.find(".installer-select").val();
        var calc_code = $(this_form).attr('data-pro-code');
        var customer_id = $(this_form).attr('data-customer-id');
        var customer_no = $(this_form).attr('data-customer-no');
        var quote_id = $(this_form).attr('data-quote-id');
        var quote_no = $(this_form).attr('data-quote-no');
        var job_no = $(this_form).attr('data-job-no');
        var attachment_id = "";
        console.log(job_no);


        $.ajax({
            url: "ticket/scripts/update-product-eta-date.php",
            type: 'POST',
            dataType: 'json',
            data: { quote_no: cid, calc_code: calc_code, date: state_approved, customer_id:customer_id, customer_no: customer_no, quote_id: quote_id, quote_no: quote_no, job_no:job_no, attachment_id:attachment_id },
            beforeSend: function() {
                //	alert(window_price_sheet_calculation_code);
                //	alert(name);
                //	alert(state_select);
                //	alert(installer_select);
                // this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function(data) {
                //	alert(window_price_sheet_calculation_name);
                //alert(data);
                // if (data[0] === 1) {

                    // this_bttn.prop('disabled', false).removeClass('field-loader');
                    // this_form.siblings(".group-state-form").html(group_state_form);

                // } else {
                    // this_bttn.prop('disabled', false).removeClass('field-loader');
                    //   alert(JSON.stringify(data));
                // }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                // this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @update-window-price-sheet-calculation-Product-date");
            }
        });

    });
    
    $(document).on("change", "#window-price-sheet-calculation-quote-items-results .install-date", function(event) {
        event.preventDefault();

        var this_form = $(this);
        // var this_bttn = $(this).find("input[type='submit']");
        //  var total = isNaN(this_form.find(".state-approved").val()) ? '0' : this_form.find(".group-total").val();
        var window_price_sheet_calculation_code = this_form.find(".window-price-sheet-calculation-code").val();
        var window_price_sheet_calculation_name = this_form.find(".window-price-sheet-calculation-name").val();
        var install_date = this_form.val();
        var state_select = this_form.find(".state-select").val();
        var installer_select = this_form.find(".installer-select").val();
        var calc_code = $(this_form).attr('data-pro-code');
        var customer_id = $(this_form).attr('data-customer-id');
        var customer_no = $(this_form).attr('data-customer-no');
        var quote_id = $(this_form).attr('data-quote-id');
        var quote_no = $(this_form).attr('data-quote-no');
        var job_no = $(this_form).attr('data-job-no');
        var attachment_id = "";
        console.log(job_no);


        $.ajax({
            url: "ticket/scripts/update-product-install-date.php",
            type: 'POST',
            dataType: 'json',
            data: { quote_no: cid, calc_code: calc_code, date: install_date, customer_id:customer_id, customer_no: customer_no, quote_id: quote_id, quote_no: quote_no, job_no:job_no, attachment_id:attachment_id },
            beforeSend: function() {
                //	alert(window_price_sheet_calculation_code);
                //	alert(name);
                //	alert(state_select);
                //	alert(installer_select);
                // this_bttn.prop('disabled', false).addClass('field-loader');
            },
            success: function(data) {
                //	alert(window_price_sheet_calculation_name);
                //alert(data);
                // if (data[0] === 1) {

                    // this_bttn.prop('disabled', false).removeClass('field-loader');
                    // this_form.siblings(".group-state-form").html(group_state_form);

                // } else {
                    // this_bttn.prop('disabled', false).removeClass('field-loader');
                    //   alert(JSON.stringify(data));
                // }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                // this_bttn.prop('disabled', false).removeClass('field-loader');
                alert(JSON.stringify(jqXHR));
                alert("Error! @update-window-price-sheet-calculation-Product-date");
            }
        });

    });
});