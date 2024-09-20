import os
from pathlib import Path
from rxbuilder.utils import normpath

REPO_PATH = normpath(Path(__file__).parent.parent.resolve())
BUILD_PATH = os.path.join(REPO_PATH, 'build')
