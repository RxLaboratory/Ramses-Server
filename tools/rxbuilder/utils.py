"""! @brief Misc. useful functions
 @file utils.py
 @section libs Librairies/Modules
 @section authors Author(s)
  - Created by Nicolas Dufresne on 1/3/2024 .
"""

import os
from .environment import Environment

E = Environment.instance()

def abs_path( rel_path ):
    """!
    @brief Returns the absolute path of a path relative to this py file

    Parameters : 
        @param rel_path => The relative path to convert

    """
    return os.path.abspath(
        os.path.join( E.THIS_DIR, rel_path)
        ).replace('/', os.sep)

def add_to_PATH( p:str ): # pylint: disable=invalid-name
    """!
    @brief Adds a path to the PATH environment variable
    @param p The path to add
    """
    os.environ["PATH"] = (
            p +
            os.pathsep +
            os.environ["PATH"]
        )

def get_build_path(subdir:str):
    """!
    @brief Gets the build path
    """
    build_path = os.path.join(E.REPO_DIR, 'build')

    if E.IS_WIN:
        build_path = os.path.join(build_path, "windows", subdir)
    elif E.IS_LINUX:
        build_path = os.path.join(build_path, "linux", subdir)
    elif E.IS_MAC:
        build_path = os.path.join(build_path, "mac", subdir)

    return abs_path(build_path)

def get_deploy_path(subdir:str):
    """!
    @brief Gets the deploy path
    """
    deploy_path = os.path.join(E.REPO_DIR, 'build', )

    if E.IS_WIN:
        deploy_path = os.path.join(deploy_path, "windows", 'deploy', subdir)
    elif E.IS_LINUX:
        deploy_path = os.path.join(deploy_path, "linux", 'deploy', subdir)
    elif E.IS_MAC:
        deploy_path = os.path.join(deploy_path, "mac", 'deploy', subdir)

    return deploy_path

def replace_vars(src_f:str, dest_f:str):
    """Copies a text file while replacing the environment vars"""
    with open(src_f, 'r', encoding='utf8') as in_file:
        content = in_file.read()

    # Replace vars
    if 'meta' in E.ENV:
        for key, value in E.ENV['meta'].items():
            content = content.replace("%"+key+"%", str(value))

    with open(dest_f, 'w', encoding='utf8') as out_file:
        out_file.write(content)

def replace_in_file(file:str, what_str:str, with_str:str):
    """Replaces an exact substring in a file"""
    content = ""
    with open(file, 'r', encoding='utf8') as in_file:
        content = in_file.read()
    content = content.replace(what_str, with_str)
    with open(file, 'w', encoding='utf8') as out_file:
        out_file.write(content)

def zip_dir( dir, zip_file_handler ):
    for root, dirs, files in os.walk(dir):
        for file in files:
            zip_file_handler.write(os.path.join(root, file),
                                  os.path.join(root.replace(dir, ''), file)
                                  )

def get_project_name():
    p = E.ENV['meta'].get("name","")
    if p == "":
        p = E.ENV['src'].get("project", "")
    p = os.path.basename(p)
    p = os.path.splitext(p)[0]
    return p
