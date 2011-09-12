<?php

    class uf_plugin {

        /**
         * Plugin owner
         * 
         * @var uf_controller
         */
        private $controller;

        /**
         * Gets the plugin owning controller
         * 
         * @return uf_controller
         */
        public function get_controller() {
            return $this->controller;
        }

        /**
         * Loads plugin
         * 
         * @param uf_controller $controller   Controller plugin is loaded for
         * @param string $plugin_name         Plugin name
         * @return plugin_class 
         */
        public static function load_plugin($controller, $plugin_name) {
            $plugin_name = uf_controller::str_to_controller($plugin_name);

            if (isset($controller->$plugin_name)) {
                return $controller->$plugin_name;
            }

            $plugin_file = uf_application::app_dir() . '/lib/plugins/' . $plugin_name . '.php';
            if (!file_exists($plugin_file)) {
                $plugin_file = UF_BASE . '/core/plugins/' . $plugin_name . '.php';
                if (!file_exists($plugin_file)) {
                    trigger_error('Missing plugin: ' . $plugin_name, E_USER_ERROR);
                }
            }

            require($plugin_file);

            $plugin_class = $plugin_name . '_plugin';
            $plugin = new $plugin_class($controller);
            //This unusual tricky way of setting private property helps avoid using
            //parent::__construct($controller) in plugin constructor
            $plugin->controller = $controller;

            return $plugin;
        }

        /**
         * Returns the list of magick methods
         * 
         * @return array
         */
        public function get_magic_methods() {
            return array();
        }

    }

    /* EOF */