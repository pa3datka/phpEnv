<?php

namespace Pa3datka;


final class Env
{
    private static $instance;

    private array $environment;


    private static function getInstance(): Env
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function env(string $value, string $default = ''): string
    {
        $envClass = self::getInstance();

        return $envClass->getValue($value, $default);
    }

    private function getValue(string $value, string $default): string
    {
       !isset($this->environment) && $this->getEnvironment();

       return $this->environment[$value] ?? $default;
    }

    private function getEnvironment(): void
    {
        $env = $this->getEnv();
        $this->setEnvironment($env);
    }

    private function getEnv(): string
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '.env';

        return file_get_contents($path);
    }

    private function setEnvironment($env): void
    {
        $this->environment = [];

        if (preg_match_all('#([A-Z\d_]*)=(.*)\n#', $env, $math)) {
            $cntArr = count($math[1]);
            for ($i = 0; $i < $cntArr; $i++) {
                $this->environment[$math[1][$i]] = $math[2][$i];
            }
        }
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    protected function __clone() { }
}
