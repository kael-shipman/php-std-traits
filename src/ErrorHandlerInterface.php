<?php
namespace KS;

interface ErrorHandlerInterface {
    function hasErrors(string $field=null);
    function numErrors(string $field=null);
    function getErrors(string $field=null);
}

