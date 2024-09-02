<?php

function is_input_empty(...$inputs) {
    foreach($inputs as $input) {
        if(empty(trim($input))) {
            return true;
        }
    }
    return false;
}