<?php
namespace KS;

abstract class AbstractConfig implements ConfigInterface {
    protected $config;

    /**
     * This method works by iterating through all interfaces implemented by this class and aggregating
     * their `get*` methods. All such methods will be called once, with the expectation being that
     * if their keys are not defined, they will fail accordingly. Thus, config will be checked.
     *
     * @inheritdoc
     */
    public function checkConfig() : void
    {
        $ref = new \ReflectionClass($this);
        $interfaces = $ref->getInterfaces();
        $check = array();
        foreach($interfaces as $name => $i) {
            if ($name == 'KS\ConfigInterface') continue;
            $methods = $i->getMethods();
            foreach($methods as $m) {
                $m = $m->getName();
                if (substr($m,0,3) == 'get' && strlen($m) > 3) $check[$m] = $m;
            }
        }

        $errors = array();
        foreach($check as $m) {
            try {
                $this->$m();
            } catch (InvalidConfigException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            $e = new InvalidConfigException("Your configuration is incomplete:\n\n  ".implode("\n  ", $errors));
            foreach ($errors as $err) {
                $e->addConfigError($err);
            }
            throw $e;
        }
    }

    /**
     * An internal method that ensures an error is thrown if the given key is not found in the configuration.
     *
     * @param string $key The key of the configuration value to get
     * @return mixed Returns the configuration value at `$key`
     * @throws InvalidConfigException in the even that a given config key isn't loaded
     */
    protected function get(string $key)
    {
        if (!array_key_exists($key, $this->config)) throw new InvalidConfigException("Your configuration doesn't have a value for the key `$key`");
        return $this->config[$key];
    }


    /** @inheritDoc */
    public function __toString(): string
    {
        $config = $this->toArray();
        return json_encode($config, JSON_PRETTY_PRINT);
    }


    /** @inheritDoc */
    public function toArray(): array
    {
        return $this->config;
    }


    abstract public function reload(): void;
}


