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
        $errors = array();
        foreach($interfaces as $name => $i) {
            if ($name == 'KS\ConfigInterface') continue;
            $methods = $i->getMethods();
            foreach($methods as $m) {
                $m = $m->getName();
                if (substr($m,0,3) == 'get' && strlen($m) > 3) {
                    try {
                        $this->$m();
                    } catch (InvalidConfigException $e) {
                        $errors[] = "Interface $name: {$e->getMessage()}";
                    }
                }
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
     * Get this config's list of known profiles.
     *
     * The `getExecProfile` function should use this list to check the given profile against.
     * This is a function that returns a simple array, such that derivative classes may easily
     * add/change profiles by simply overriding the function and merging in new profile names
     * or returning a different array.
     *
     * In the returned array, the key should represent the _name_ of the profile, i.e., what you
     * would call the profile in conversation, while the value represents the config string value
     * that would appear as the value of `exec-profile` in your config.
     *
     * @param string | null $profileName The named profile value to get
     * @return string | string[] Returns a single string if $profileName specified, or an array
     * of strings if no $profileName specified.
     */
    protected function getValidExecProfiles(string $profileName = null)
    {
        $validProfs = [
            'production' => 'production',
            'dev' => 'dev',
            'staging' => 'staging',
            'sandbox' => 'sandbox',
            'demo' => 'demo',
            'debug' => 'debug'
        ];

        if ($profileName) {
            if (!isset($validProfs[$profileName])) {
                throw new \RuntimeException("Programmer: you've requested a profile by name `$profileName`, but that profile is not defined");
            } else {
                return $validProfs[$profileName];
            }
        }

        return $validProfs;
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


