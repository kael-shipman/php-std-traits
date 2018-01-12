<?php
namespace KS;

interface BaseConfigInterface {

    /**
     * A method that may be used to check that all necessary configuration keys are present.
     *
     * Works by iterating through all `get*` method defined by all implemented interfaces and calling them. If
     * these methods use the internal `get` method of this class to get their values, then a NonexistentConfigException
     * will be thrown in the event that a key is not defined in the config.
     *
     * @param bool $force Whether or not to force checking, regardless of environment
     * @return void
     * @throws MissingConfigException (@see `get`)
     */
    public function checkConfig(bool $force=false) : void;

    /**
     * Get the execution profile of the current app instance
     *
     * @return string The execution profile string (usually 'production', 'development', 'staging', 'sandbox', etc...)
     */
    public function getExecutionProfile(): string;

    /**
     * Dump the full parsed configuration to string.
     *
     * @return string The dumped configuration
     */
    public function dump(): string;

    /**
     * Merges the given array into the internal configuration, allowing runtime overrides of configuration.
     *
     * This is mainly to accommodate scenarios in which you might have a default file, a local override, and then
     * perhaps some command-line flags that override even the local override file.
     *
     * @param array $config An array of key-value pairs to merge
     * @return void
     */
    public function overrideConfig(array $config) : void;
}

