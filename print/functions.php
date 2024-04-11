<?php

function get_sub_option_text($obj)
{
    if (!empty($obj)) {

        $result = $obj['name'] . ' - ' . $obj['price'];

        if (!empty($obj['sub'])) {

            $result = get_sub_option_text($obj['sub']);
        }
        return $result;
    }
    return null;
}
