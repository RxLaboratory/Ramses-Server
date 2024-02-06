import os
import zipfile
from .file_deploy import deploy as deploy_files
from .utils import (
    get_deploy_path,
    zip_dir,
    get_project_name
)
from .jsx_build import (
    get_api_build_path,
    get_jsx_build_path
)
from .environment import Environment

E = Environment.instance()    

def get_types_path():
    """!
    @brief Gets the 'types/scriptName folder, containing the type defs for the API
    @returns The path
    """
    p = os.path.join(E.REPO_DIR, 'types')
    if 'jsx' in E.ENV:
        p = os.path.join(p, get_project_name().lower())
    return p

def deploy_jsx(name, version):
    print(">> Deploying Scripts...")

    jsx_path = get_jsx_build_path(name)
    deploy_path = get_deploy_path(name)

    if not os.path.isdir(deploy_path):
        os.makedirs(deploy_path)

    zip_file = os.path.join(deploy_path, os.path.basename(jsx_path) + version + '.zip')

    with zipfile.ZipFile(zip_file, 'w', zipfile.ZIP_DEFLATED) as zip:
        zip_dir(jsx_path, zip)

def deploy_api(name, version):

    print(">> Deploying API...")

    api_path = get_api_build_path(name)
    deploy_path = get_deploy_path(name)

    if not os.path.isdir(deploy_path):
        os.makedirs(deploy_path)

    zip_file = os.path.join(deploy_path, os.path.basename(api_path) + version + '.zip')

    with zipfile.ZipFile(zip_file, 'w', zipfile.ZIP_DEFLATED) as zip:
        zip_dir(api_path, zip)

def deploy(name="Adobe Script"):

    print("> Deploying...")

    deploy_files()

    version = ""
    if 'meta' in E.ENV:
        version = E.ENV['meta'].get('version', '')
        if version != '':
            version = '_' + version

    deploy_api(name, version)
    deploy_jsx(name, version)

    print(">> Deployed!")

    