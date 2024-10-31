<?php
if ( ! class_exists( 'FBOO_Singleton' ) ):

	/**
	 * Class FBOO_Singleton
	 *
	 * Extends other classes to handle the singelton pattern.
	 */
	class FBOO_Singleton {
		private static $instances = array();

		/**
		 * Return the instance of this object. (Singleton)
		 *
		 * @return bool|static False if something went wrong, otherwise the object.
		 */
		public static function getInstance() {
			$class = get_called_class();

			if ( ! isset( self::$instances[ $class ] ) ) {
				self::$instances[ $class ] = new static;
			}

			return self::$instances[ $class ];
		}

		/**
		 * FBOO_Singleton constructor.
		 *
		 * Make constructor private, so nobody can call "new Class".
		 */
		protected function __construct() {
		}

		/**
		 * Make clone magic method private, so nobody can clone instance.
		 */
		protected function __clone() {
			throw new Exception( "Cannot clone singleton." );
		}

		/**
		 * Make sleep magic method private, so nobody can serialize instance.
		 */
		public function __sleep() {
			throw new Exception( "Cannot serialize singleton." );
		}

		/**
		 * Make wakeup magic method private, so nobody can unserialize instance.
		 */
		protected function __wakeup() {
			throw new Exception( "Cannot unserialize singleton." );
		}

	}

endif;