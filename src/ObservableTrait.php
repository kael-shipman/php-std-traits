<?php
namespace KS;

trait ObservableTrait {
    protected $_listeners = array();

    public function triggerEvent(EventInterface $e) {
        if (!isset($this->_listeners[$e->getName()])) return true;
        $e->setTarget($this);
        foreach ($this->_listeners[$e->getName()] as $l) {
            $listener = $l[0];
            $handler = $l[1];
            try {
                $result = call_user_func_array(array($listener, $handler), [ $e ]);
            } catch (HaltEventPropagationException $e) {
                break;
            }
        }
        return true;
    }
    public function registerListener(string $event, $listener, string $handler) {
        if (!isset($this->_listeners[$event])) $this->_listeners[$event] = array();
        foreach ($this->_listeners[$event] as $l) {
            if ($l[0] == $listener && $l[1] == $handler) return $this;
        }
        $this->_listeners[$event][] = array($listener, $handler);
        return $this;
    }
    public function removeListener(string $event, $listener, string $handler) {
        if (!isset($this->_listeners[$event])) return $this;
        foreach ($this->_listeners[$event] as $k => $l) {
            if ($l[0] == $listener && $l[1] == $handler) {
                unset($this->_listeners[$event][$k]);
                return $this;
            }
        }
        return $this;
    }
}

