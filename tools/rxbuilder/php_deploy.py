"""! @brief Deploys the PHP Server and docker folders
 @file php_deploy.py
 @section authors Author(s)
  - Created by Nicolas Dufresne on 2/6/2024 .
"""

import os
import zipfile
from .file_deploy import deploy as deploy_files
from .utils import (
    get_deploy_path,
    get_build_path,
    zip_dir,
    get_project_name
)
from .environment import Environment

E = Environment.instance()  

def deploy(name='Server'):
    """Deploys all"""

    print("> Deploying...")

    deploy_files()

    version = ""
    if 'meta' in E.ENV:
        version = E.ENV['meta'].get('version', '')
        if version != '':
            version = '_' + version

    build_path = get_build_path(name)
    deploy_path = get_deploy_path(name)

    if not os.path.isdir(deploy_path):
        os.makedirs(deploy_path)

    # The main server
    zip_file = os.path.join(
        deploy_path,
        get_project_name().lower().replace(' ', '-') + version + '.zip'
        )

    with zipfile.ZipFile(zip_file, 'w', zipfile.ZIP_DEFLATED) as zip:
        zip_dir(
            os.path.join(build_path, 'www'),
            zip
            )

    # The docker folders
    for d in E.ENV['php'].get('docker_folders', ()):
        d_folder = os.path.join(build_path, d['path'])
        folder_name = os.path.basename(d_folder)

        zip_file = os.path.join(
            deploy_path,
            get_project_name().lower().replace(' ', '-') + version +
                '_' + folder_name + '.zip'
            )

        with zipfile.ZipFile(zip_file, 'w', zipfile.ZIP_DEFLATED) as zip:
            zip_dir(
                d_folder,
                zip
                )
