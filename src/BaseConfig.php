<?php
namespace KS;

/**
 * This is copied from https://github.com/kael-shipman/skelphp-config and redefined here to
 * provide an implementation that doesn't depend on the Skel header package.
 */
class BaseConfig implements BaseConfigInterface {
    protected $config;
    protected $defaultFile;
    protected $localFile;
    const PROFILE_PROD = 'production';


    /**
     * Loads configuration keys into memory from config files, optionally overriding values from `$defaultConfigFile`
     * with values from `$localConfigFile`.
     *
     * If the given configuration mode is determined to be anything other than production, configuration is
     * checked and errors possibly thrown.
     *
     * @param string $defaultConfigFile The default (global) configuration file
     * @param string $localConfigFile A local config file that overrides default configurations. Note that this parameter
     * is required, though the file does not have to exist in every installation.
     */
    public function __construct(string $defaultConfigFile, string $localConfigFile) {
        if (!is_file($defaultConfigFile)) throw new NonexistentFileException("You must have a global configurations file at `$defaultConfigFile`. You should define all default configurations in this file, and it should be version controlled. You may optionally provide a second file, `$localConfigFile`, for overriding config locally, and this file should NOT be version controlled.");

        $defaultConfig = require $defaultConfigFile;
        $localConfig = @include $localConfigFile;

        if (!is_array($defaultConfig)) throw new ConfigFileFormatException("Default configuration file MUST return an array.");
        if ($localConfig && !is_array($localConfig)) throw new ConfigFileFormatException("Local configuration file MUST return an array.");
        if (!$localConfig) $localConfig = [];

        $this->config = array_replace($defaultConfig, $localConfig);
        $this->checkConfig();

        $this->defaultFile = $defaultConfigFile;
        $this->localFile = $localConfigFile;
    }

    /** @inheritDoc */
    public function checkConfig(bool $force=false) : void {
        if (!$force && $this->getExecutionProfile() == static::PROFILE_PROD) return;
        $ref = new \ReflectionClass($this);
        $interfaces = $ref->getInterfaces();
        $check = array();
        foreach($interfaces as $name => $i) {
            if ($name == 'KS\BaseConfigInterface') continue;
            $methods = $i->getMethods();
            foreach($methods as $m) {
                $m = $m->getName();
                if (substr($m,0,3) == 'get' && strlen($m) > 3) $check[$m] = $m;
            }
        }

        $errors = array();
        foreach($check as $m) {
            try {
                $testParams = $this->getTestParams();
                if (!array_key_exists($m, $testParams)) $this->$m();
                else {
                    if (!is_array($testParams[$m])) $testParams[$m] = array($testParams[$m]);
                    call_user_func_array(array($this, $m), $testParams[$m]);
                }
            } catch (MissingConfigException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) > 0) throw new MissingConfigException("Your configuration is incomplete:\n\n".implode("\n  ", $errors));
    }

    /**
     * An internal method that ensures an error is thrown if the given key is not found in the configuration.
     *
     * @param string $key The key of the configuration value to get
     * @return mixed Returns the configuration value at `$key`
     * @throws MissingConfigException in the even that a given config key isn't loaded
     */
    protected function get(string $key) {
        if (!isset($this->config[$key])) throw new MissingConfigException("Your configuration doesn't have a value for the key `$key`");
        return $this->config[$key];
    }

    /** @inheritDoc */
    public function getExecutionProfile(): string {
        return $this->get('exec-profile');
    }

    /** @inheritDoc */
    public function dump(): string {
        $dump = array();
        foreach ($this->config as $k => $v) $dump[] = "$k: `$v`;";
        return implode("\n", $dump);
    }

    /**
     * If a certain config getter requires parameters, you can provide parameter stubs for by overriding
     * this method and using it to return a map of method names to parameter arrays. For example, if your
     * `getComplexThing` method requires parameters `$x` and `$y`, you can provide an array like this:
     *
     *     return [
     *         'getComplexThing' => [ 3, 6 ],
     *     ];
     *
     * @return array
     */
    protected function getTestParams() {
        return [];
    }
}


