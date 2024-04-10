"""! @brief Build Python project
 @file py_build.py
 @section authors Author(s)
  - Created by Nicolas Dufresne on 1/18/2024 .
"""

import os
import shutil
from .utils import (
    get_build_path,
    get_project_name,
    replace_vars
)
from .environment import Environment

E = Environment.instance()

TEXT_EXTS = (
    ".py",
    ".txt",
    ".mod"
)

IGNORE_NAMES = (
    "__pycache__",
)

IGNORE_EXTS = (
    ".pyc",
    ".pyo",
    ".pyd",
)

def get_py_build_path(name="Python"):
    """!
    @brief Gets the path where the scripts are built
    @param name = "Python" => The name of the project
    @returns The path
    """
    return os.path.join(
        get_build_path(name),
        get_project_name()
    )

def build_folder(src, dest):
    """Builds a given folder to the destination"""
    if not os.path.isdir(dest):
        os.makedirs(dest)

    for f in os.listdir(src):
        if f in IGNORE_NAMES:
            continue
        ext = os.path.splitext(f)[1].lower()
        if ext in IGNORE_EXTS:
            continue
        src_f = os.path.join(src, f)
        dest_f = os.path.join(dest, f)

        if src_f == dest_f:
            continue

        if os.path.isdir(src_f):
            build_folder(src_f, dest_f)
            continue

        if not ext in TEXT_EXTS:
            shutil.copy(src_f, dest_f)
            continue

        replace_vars(src_f, dest_f)

def build(name="Python"):
    """Builds all"""

    if 'py' not in E.ENV:
        raise ValueError("Environement doesn't contain Python data.")

    wipe(name)

    build_path = get_py_build_path(name)
    if not os.path.isdir(build_path):
        os.makedirs(build_path)

    print("> Building Python Modules...")
    for mod in E.ENV['py'].get('modules', ()):
        mod_build_path = os.path.join(
            build_path,
            mod.get("name", "")
        )
        print(">> Building Module: " + mod['src'])
        print(">>> To: " + mod_build_path)
        build_folder(mod['src'], mod_build_path)

    print(">> Modules built!")

def wipe(name="Python"):
    """!
    @brief Removes previous builds to start over.
    """
    p = get_build_path(name)
    if os.path.isdir(p):
        shutil.rmtree(p)
