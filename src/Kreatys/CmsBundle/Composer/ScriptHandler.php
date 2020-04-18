<?php

namespace Kreatys\CmsBundle\Composer;

//use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\CommandEvent;


class ScriptHandler
{
    protected static $options = array(
        'symfony-app-dir' => 'app',
        'symfony-web-dir' => 'web',
        'symfony-src-dir' => 'src'
    );



    /**
     * Updated the requirements file.
     *
     * @param $event CommandEvent A instance
     */
    public static function installRequirementsFile(CommandEvent $event)
    {
        $options = static::getOptions($event);
        $appDir = $options['symfony-app-dir'];
        $srcDir = $options['symfony-src-dir'];
        $webDir = $options['symfony-web-dir'];
        $fs = new Filesystem();
        
        
        // TODO gérer la structure Symfony 3
        
        
        
        if (!static::hasDirectory($event, 'symfony-src-dir', $srcDir, 'install the requirements files')) {
            return;
        }
        $fs->mirror(__DIR__.'/../dist/bundle/Application', $srcDir."/Application");
        $fs->mirror(__DIR__.'/../dist/bundle/Kreatys', $srcDir."/Kreatys");
        

        $newDirectoryStructure = static::useNewDirectoryStructure($options);

        if (!$newDirectoryStructure) {
            if (!static::hasDirectory($event, 'symfony-app-dir', $appDir, 'install the requirements files')) {
                return;
            }
            
            $fs->mirror(__DIR__.'/../dist/config/fos', $appDir."/config/fos");
            $fs->mirror(__DIR__.'/../dist/config/kcms', $appDir."/config/kcms");
            $fs->mirror(__DIR__.'/../dist/config/knp', $appDir."/config/knp");
            $fs->mirror(__DIR__.'/../dist/config/mopa_bootstrap', $appDir."/config/mopa_bootstrap");
            $fs->mirror(__DIR__.'/../dist/config/sonata', $appDir."/config/sonata");
            $fs->mirror(__DIR__.'/../dist/config/stof', $appDir."/config/stof");
            
            $fs->mirror(__DIR__.'/../dist/config/config_preprod.yml', $appDir."/config/config_preprod.yml");
            
            $fs->mirror(__DIR__.'/../dist/java', $appDir."/Resources/java");
            
            $fs->copy(__DIR__.'/../dist/config/fos_user_routing.yml', $appDir."/config/fos_user_routing.yml");
            $fs->copy(__DIR__.'/../dist/config/kreatys_routing.yml', $appDir."/config/kreatys_routing.yml");
            
            $fs->copy(__DIR__.'/../dist/web/.htaccess_preprod', $webDir."/.htaccess_preprod");
            $fs->copy(__DIR__.'/../dist/web/app_preprod.php', $webDir."/app_preprod.php");

        } else {
            $binDir = $options['symfony-bin-dir'];
            $varDir = $options['symfony-var-dir'];
            if (!static::hasDirectory($event, 'symfony-var-dir', $varDir, 'install the requirements files')) {
                return;
            }
            if (!static::hasDirectory($event, 'symfony-bin-dir', $binDir, 'install the requirements files')) {
                return;
            }

            /*$fs->copy(__DIR__.'/../Resources/skeleton/app/SymfonyRequirements.php', $varDir.'/SymfonyRequirements.php', true);
            $fs->copy(__DIR__.'/../Resources/skeleton/app/check.php', $binDir.'/symfony_requirements', true);
            $fs->remove(array($appDir.'/check.php', $appDir.'/SymfonyRequirements.php', true));

            $fs->dumpFile($binDir.'/symfony_requirements', '#!/usr/bin/env php'.PHP_EOL.str_replace(".'/SymfonyRequirements.php'", ".'/".$fs->makePathRelative($varDir, $binDir)."SymfonyRequirements.php'", file_get_contents($binDir.'/symfony_requirements')));
            $fs->chmod($binDir.'/symfony_requirements', 0755);*/
        }
    }
    
    /**
     * Update schema database.
     *
     * @param $event CommandEvent A instance
     */
    public static function updateSchema(CommandEvent $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'update schema');

        if (null === $consoleDir) {
            return;
        }
        
        static::executeCommand($event, $consoleDir, 'doctrine:schema:update --force', $options['process-timeout']);
    }
    
    protected static function hasDirectory(CommandEvent $event, $configName, $path, $actionName)
    {
        if (!is_dir($path)) {
            $event->getIO()->write(sprintf('The %s (%s) specified in composer.json was not found in %s, can not %s.', $configName, $path, getcwd(), $actionName));

            return false;
        }

        return true;
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(static::$options, $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find($includeArgs)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    protected static function getPhpArguments()
    {
        $arguments = array();

        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }

        if (false !== $ini = php_ini_loaded_file()) {
            $arguments[] = '--php-ini='.$ini;
        }

        return $arguments;
    }

    /**
     * Returns a relative path to the directory that contains the `console` command.
     *
     * @param CommandEvent $event      The command event.
     * @param string       $actionName The name of the action
     *
     * @return string|null The path to the console directory, null if not found.
     */
    protected static function getConsoleDir(CommandEvent $event, $actionName)
    {
        $options = static::getOptions($event);

        if (static::useNewDirectoryStructure($options)) {
            if (!static::hasDirectory($event, 'symfony-bin-dir', $options['symfony-bin-dir'], $actionName)) {
                return;
            }

            return $options['symfony-bin-dir'];
        }

        if (!static::hasDirectory($event, 'symfony-app-dir', $options['symfony-app-dir'], 'execute command')) {
            return;
        }

        return $options['symfony-app-dir'];
    }

    /**
     * Returns true if the new directory structure is used.
     *
     * @param array $options Composer options
     *
     * @return bool
     */
    protected static function useNewDirectoryStructure(array $options)
    {
        return isset($options['symfony-var-dir']) && is_dir($options['symfony-var-dir']);
    }
    
    protected static function executeCommand(CommandEvent $event, $consoleDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(static::getPhp(false));
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $console = escapeshellarg($consoleDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.($phpArgs ? ' '.$phpArgs : '').' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($event) { $event->getIO()->write($buffer, false); });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s.", escapeshellarg($cmd), $process->getOutput(), $process->getErrorOutput()));
        }
    }
}
