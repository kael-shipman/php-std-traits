<?php
namespace Test;

class TestArray implements \ArrayAccess, \Iterator, \Countable {
    use \KS\ArrayAccessTrait;
    use \KS\IteratorTrait;
    use \KS\CountableTrait;
}

