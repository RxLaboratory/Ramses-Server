import os
import shutil
import zipfile
from distutils.dir_util import copy_tree
from .file_deploy import deploy as deploy_files
from .py_build import get_py_build_path
from .environment import Environment
from .utils import (
    get_deploy_path,
    zip_dir
)
E = Environment.instance()

def deploy_mod(path, name, version):
    deploy_path = get_deploy_path(name)
    if not os.path.isdir(deploy_path):
        os.makedirs(deploy_path)

    deploy_path = os.path.join(
        deploy_path,
        os.path.basename(path)
        )

    if not os.path.isdir(deploy_path):
        os.makedirs(deploy_path)

    copy_tree(path, deploy_path)

    zip_file = os.path.join(os.path.dirname(deploy_path), os.path.basename(path) + version + '.zip')
    with zipfile.ZipFile(zip_file, 'w', zipfile.ZIP_DEFLATED) as z:
        zip_dir(path, z)

def deploy(name="Python"):
    print("> Deploying Python...")

    deploy_files()

    version = ""
    if 'meta' in E.ENV:
        version = E.ENV['meta'].get('version', '')
        if version != '':
            version = '_' + version

    build_path = get_py_build_path(name)

    print(">> Deploying Modules...")
    for mod in E.ENV['py'].get('modules', ()):
        modName = mod.get("name", "")
        mod_build_path = build_path
        if modName != "":
            mod_build_path = os.path.join(
                build_path,
                modName
            )
        print(">>> From: " + mod_build_path)
        deploy_mod(mod_build_path, name, version)
