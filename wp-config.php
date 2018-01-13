// ** MySQL settings - You can get this info from your web host ** //
$url = parse_url(getenv('C:/Users/ese_s/project-program/splc2017.sql') ? getenv('C:/Users/ese_s/project-program/splc2017.sql') : getenv('C:/Users/ese_s/project-program/splc2017.sql'));

/** The name of the database for WordPress */
define('splc2017', trim($url['path'], '/'));

/** MySQL database username */
define('root', $url['user']);

/** MySQL database password */
define('', $url['pass']);

/** MySQL hostname */
define('DB_HOST', $url['host']);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('AUTH_KEY',         getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY'));
define('NONCE_KEY',        getenv('NONCE_KEY'));
define('AUTH_SALT',        getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT'));
define('NONCE_SALT',       getenv('NONCE_SALT'));