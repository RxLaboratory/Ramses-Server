import os
import shutil
from .utils import get_deploy_path
from .environment import Environment

E = Environment.instance()

def deploy_file(src, dest, build_path):

    print(">> Deploying: " + src)

    src = os.path.expanduser(src)

    if not os.path.isabs(src):
        src = os.path.join(E.REPO_DIR, src)

    if not os.path.isabs(dest):
        dest = os.path.join(build_path, dest)

    if os.path.isdir(src):
        if not os.path.isdir(dest):
            os.makedirs(dest)
        for f in os.listdir(src):
            deploy_file(
                os.path.join(src, f),
                os.path.join(dest, f),
                build_path
            )
        return

    if os.path.isfile(dest):
        os.remove(dest)

    dest_dir = os.path.dirname(dest)
    if not os.path.isdir(dest_dir):
        os.makedirs(dest_dir)

    if not os.path.isfile(src):
        print(">> This file can't be found: " + src)
        return

    try:
        shutil.copy(src, dest)
    except PermissionError:
        print(">> This file can't be deployed (permission error): " + os.path.basename(src))

def deploy():

    print(">> Deploying additional files...")
    if not "deploy" in E.ENV:
        print(">> No other files to deploy!")
        return
    
    build_path = get_deploy_path("")
    deploy = E.ENV['deploy']
    for d in deploy:
        src = d.get('src','')
        if src == '':
            continue
        dest = d.get('dest', '')
        if dest == '':
            continue

        deploy_file(src, dest, build_path )
