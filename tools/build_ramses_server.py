import os
import _setup_env as ramses
ramses.init()
B = ramses.builder

if __name__ == '__main__':

    # Build
    B.build_php('')

    # Update Docker configs
    mysql_config_path = os.path.join(
        B.get_build_path(''),
        'docker-mysql',
        'www',
        'ramses',
        'config',
        'config.php'
    )

    B.replace_in_file(mysql_config_path, "sqlMode = 'sqlite'", "sqlMode = 'mysql'")
    B.replace_in_file(mysql_config_path, "forceSSL = true", "forceSSL = false")

    sqlite_config_path = os.path.join(
        B.get_build_path(''),
        'docker-sqlite',
        'www',
        'ramses',
        'config',
        'config.php'
    )

    B.replace_in_file(sqlite_config_path, "sqlMode = 'mysql'", "sqlMode = 'sqlite'")
    B.replace_in_file(sqlite_config_path, "forceSSL = true", "forceSSL = false")

    # Deploy

    B.deploy_php('')
