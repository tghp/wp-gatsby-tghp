<?php

namespace TGHP\WPGatsbyTGHP;

class WPGatsbyTGHP
{

    /**
     * @var WPGatsby
     */
    public $wpGatsby;

    /**
     * @var Database
     */
    public $database;

    /**
     * @var Actions
     */
    protected $actions;

    /**
     * @var Dashboard
     */
    protected $dashboard;

    /**
     * @var AdminPages
     */
    protected $adminPages;

    /**
     * The single instance of the class.
     *
     * @var WPGatsbyTGHP
     */
    protected static $_instance = null;

    /**
     * Main WPGatsby Instance.
     *
     * Ensures only one instance of WPGatsby is loaded or can be loaded.
     *
     * @return WPGatsbyTGHP
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Site constructor.
     */
    public function __construct()
    {
        spl_autoload_register([$this, 'autoload']);

        $this->wpGatsby = new WPGatsby();
        $this->database = new Database();
        $this->actions = new Actions();
        $this->dashboard = new Dashboard();
        $this->adminPages = new AdminPages();
    }

    /**
     * Autoload additional classes
     *
     * @param $className
     * @return void
     */
    public function autoload($className): void
    {
        $namespace = __NAMESPACE__;
        $namespaceCount = count(explode('\\', $namespace));

        $classNamespace = array_slice(explode('\\', $className), 0, $namespaceCount);
        $classNamespace = implode('\\', $classNamespace);

        if ($classNamespace !== $namespace && !str_starts_with($className, 'WeDevs\ORM\Eloquent')) {
            return;
        }

        $originalClassName = $className;
        $className = str_replace($namespace . '\\', '', $className);
        $classParts = explode('\\', $className);

        if (str_starts_with($originalClassName, 'WeDevs\ORM\Eloquent')) {
            $file = __DIR__ . '/../vendor/' . implode('/', $classParts) . '.php';
        } else {
            $file = __DIR__ . '/' . implode('/', $classParts) . '.php';
        }

        if (file_exists($file)) {
            include_once($file);
        }
    }

}