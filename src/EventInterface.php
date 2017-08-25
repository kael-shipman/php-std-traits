<?php
namespace KS;

interface EventInterface {
    function getName();
    function getData();
    function getTarget();
    function setTarget($object);
}

