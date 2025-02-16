<?php

if (!function_exists('pre_print')) {
    /**
     * Print any array or string in a readable format and optionally stop execution.
     *
     * @param mixed $data The data to print.
     * @param bool $exit Whether to stop execution after printing (default: true).
     * @return void
     */
    function pre_print($data, $exit = true)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        if ($exit) {
            exit; // Stop execution if $exit is true
        }
    }
}