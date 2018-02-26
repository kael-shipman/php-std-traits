<?php
namespace KS;

/**
 * This abstract config class is based on a model of runtime configuration that
 * utilizes a global config file (usually `/etc/[name]/config`), optional global config
 * fragment files (usually `/etc/[name]/config.d/*`), an optional user config file
 * (`/home/[user]/.config/[name]/config`), and finally an array of config parameters
 * passed in via the command line.
 */
abstract class AbstractCliConfig extends AbstractConfig
{
    protected $globalConfFile;
    protected $globalConfDir;
    protected $userConfFile;
    protected $commandlineConf = [];
    public function __construct(string $globalConfFile, string $globalConfDir = null, string $userConfFile = null, array $commandlineConf = [])
    {
        $this->globalConfFile = $globalConfFile;
        $this->globalConfDir = $globalConfDir;
        $this->userConfFile = $userConfFile;
        $this->commandlineConf = $commandlineConf;

        $this->reload();
    }

    public function reload(): void
    {
        if (!file_exists($this->globalConfFile)) {
            throw new \RuntimeException("You must provide a global configuration file at `$this->globalConfFile`");
        }
        $config = $this->parseConfig(file_get_contents($this->globalConfFile));

        // Merge in config fragment files
        if ($this->globalConfDir) {
            $globalFragments = [];
            if (is_dir($this->globalConfDir)) {
                $d = dir($this->globalConfDir);
                while (($f = $d->read()) !== false) {
                    if ($f[0] === '.' || is_dir("$this->globalConfDir/$f")) {
                        continue;
                    }
                    $globalFragments = "$this->globalConfDir/$f";
                }
            }
            sort($globalFragments);
            foreach($globalFragments as $c) {
                $config = array_replace_recursive($config, $this->parseConfig(file_get_contents($c)));
            }
        }

        // Merge in user config file
        if ($this->userConfFile && file_exists($this->userConfFile)) {
            $config = array_replace_recursive($config, $this->parseConfig(file_get_contents($this->userConfFile)));
        }

        // Merge in command-line arguments
        $config = array_replace_recursive($config, $this->commandlineConf);

        $this->config = $config;
    }

    /**
     * An overridable method that allows parsing arbitrary strings into config arrays
     *
     * @param string $config The contents of a config file (or sometimes the filename itself)
     * @return array The config array resulting from the parse
     */
    protected function parseConfig(string $config): array
    {
        $config = json_decode($config, true);
        if ($config === null) {
            throw new ConfigFileFormatException("Config files should be written in valid JSON");
        }
        return $config;
    }
}

