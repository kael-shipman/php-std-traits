<?php
namespace KS;

class WebappConfig extends AbstractConfig implements WebappConfigInterface {
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

        $this->defaultFile = $defaultConfigFile;
        $this->localFile = $localConfigFile;

        $this->reload();
    }

    /** @inheritDoc */
    public function getExecutionProfile(): string {
        return $this->get('exec-profile');
    }

    /**
     * @inheritdoc
     */
    public function reload(): void
    {
        $defaultConfig = require $this->defaultFile;
        $localConfig = @include $this->localFile;

        if (!is_array($defaultConfig)) throw new ConfigFileFormatException("Default configuration file MUST return an array.");
        if ($localConfig && !is_array($localConfig)) throw new ConfigFileFormatException("Local configuration file MUST return an array.");
        if (!$localConfig) $localConfig = [];

        $this->config = array_replace_recursive($defaultConfig, $localConfig);
        if ($this->getExecutionProfile() !== static::PROFILE_PROD) {
            $this->checkConfig();
        }
    }
}

