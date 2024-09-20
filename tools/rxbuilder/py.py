import os
import shutil

IGNORE_NAMES = (
    "__pycache__",
)

IGNORE_EXTS = (
    ".pyc",
    ".pyo",
    ".pyd",
)

TEXT_EXTS = (
    ".py",
    ".txt",
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

        shutil.copy(src_f, dest_f)
        continue
