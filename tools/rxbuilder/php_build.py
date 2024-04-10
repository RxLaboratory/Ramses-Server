"""! @brief Build PHP projects
 @file php_build.py
 @section authors Author(s)
  - Created by Nicolas Dufresne on 2/6/2024 .
"""

import os
import shutil
from .utils import (
    get_build_path,
    replace_vars
    )
from .environment import Environment

E = Environment.instance()

def copy_folder(src, dst):
    """Copies a folder,
    ignoring files listed in the PHP environment"""

    if not os.path.isdir(dst):
        os.makedirs(dst)

    for f in os.listdir(src):
        if f in E.ENV['php'].get('ignore', ()):
            continue

        path = os.path.join(src, f)
        dst_path = os.path.join(dst, f)
        if os.path.isfile(path):
            if os.path.splitext(f)[-1].lower() == 'php':
                replace_vars(path, dst_path)
            else:
                shutil.copy( path, dst_path)
            continue
        if os.path.isdir(path):
            copy_folder( path, dst_path)

def build_php(name="Server"):
    """Builds the PHP server and docker folders if any"""

    print("> Building PHP...")

    build_path = get_build_path(name)
    src_path = E.ENV['php'].get('src_path', "")

    # Copy the main folder
    print(">> Building server")
    copy_folder(
        src_path,
        os.path.join(build_path, "www")
        )

    # Copy the docker folders
    for d in E.ENV['php'].get('docker_folders', ()):
        print(">> Building docker folder: " + d['path'])
        copy_folder(
            os.path.join(E.REPO_DIR, d['path']),
            os.path.join(build_path, d['path'])
        )
        copy_folder(
            src_path,
            os.path.join(build_path, d['path'], d['www'])
        )

def build(name="Server"):
    """Builds all"""

    if 'php' not in E.ENV:
        raise ValueError("Environement doesn't contain PHP data.")

    wipe(name)
    build_php(name)

def wipe(name="Server"):
    """!
    @brief Removes previous builds to start over.
    """

    print("> Cleaning...")
    p = get_build_path(name)
    if os.path.isdir(p):
        shutil.rmtree(p)
