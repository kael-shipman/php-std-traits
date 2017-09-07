<?php
namespace KS;

interface ErrorInterface {
    function getStatus();
    function getTitle();
    function getDetail();
}

