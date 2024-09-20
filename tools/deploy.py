import os
import rxbuilder.utils as utils
import rxbuilder.py as py
import shutil
from _config import (
    REPO_PATH,
    BUILD_PATH,
)

STANDARD_PATH = os.path.join(BUILD_PATH, 'www')
MYSQL_PATH = os.path.join(BUILD_PATH, 'docker-mysql')
SQLITE_PATH = os.path.join(BUILD_PATH, 'docker-sqlite')
VERSION = utils.read_version(REPO_PATH)

def build():
    # Standard
    src_path = os.path.join(REPO_PATH, 'src')
    shutil.copytree(
        src_path,
        STANDARD_PATH,
    )
    # MySQL Docker
    shutil.copytree(
        os.path.join(REPO_PATH, 'docker-mysql'),
        MYSQL_PATH,
    )
    shutil.copytree(
        src_path,
        os.path.join(MYSQL_PATH, 'www', 'ramses'),
    )
    # SQLite Docker
    shutil.copytree(
        os.path.join(REPO_PATH, 'docker-sqlite'),
        SQLITE_PATH,
    )
    shutil.copytree(
        src_path,
        os.path.join(SQLITE_PATH, 'www', 'ramses'),
    )

    # Update meta
    utils.replace_in_file( {
        "#version#": VERSION
    }, os.path.join(STANDARD_PATH, 'global.php'))

    utils.replace_in_file( {
        "#version#": VERSION
    }, os.path.join(MYSQL_PATH, 'www', 'ramses', 'global.php'))

    utils.replace_in_file( {
        "#version#": VERSION
    }, os.path.join(SQLITE_PATH, 'www', 'ramses', 'global.php'))

    # Update Docker configs
    utils.replace_in_file( {
        "sqlMode = 'sqlite'": "sqlMode = 'mysql'"
    }, os.path.join(
        MYSQL_PATH,
        'www',
        'ramses',
        'config',
        'config_sql.php'
    ))
    utils.replace_in_file( {
        "forceSSL = true": "forceSSL = false"
    }, os.path.join(
        MYSQL_PATH,
        'www',
        'ramses',
        'config',
        'config_session.php'
    ))
    utils.replace_in_file( {
        "sqlMode = 'mysql'": "sqlMode = 'sqlite'"
    }, os.path.join(
        SQLITE_PATH,
        'www',
        'ramses',
        'config',
        'config_sql.php'
    ))
    utils.replace_in_file( {
        "forceSSL = true": "forceSSL = false"
    }, os.path.join(
        SQLITE_PATH,
        'www',
        'ramses',
        'config',
        'config_session.php'
    ))

def zip_modules():
    zip_file = os.path.join(
        BUILD_PATH,
        'ramses-server_' + VERSION + '.zip'
    )
    utils.zip_dir(STANDARD_PATH, zip_file)

    zip_file = os.path.join(
        BUILD_PATH,
        'ramses-server_' + VERSION + '-docker-mysql.zip'
    )
    utils.zip_dir(MYSQL_PATH, zip_file)

    zip_file = os.path.join(
        BUILD_PATH,
        'ramses-server_' + VERSION + '-docker-sqlite.zip'
    )
    utils.zip_dir(SQLITE_PATH, zip_file)

if __name__ == '__main__':
    utils.wipe(BUILD_PATH)
    build()
    zip_modules()
