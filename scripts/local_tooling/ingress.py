"""Operate shared HTTPS for this framework's own registered worktree."""
import argparse
from pathlib import Path
import subprocess
import sys

from .preparation import resolve
from .resources import Hold, require


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('action', choices=('preview', 'certificate', 'herd-config'))
    parser.add_argument('--supplier', required=True, type=Path, help='Absolute Fight Agent OS checkout')
    parser.add_argument('--worktree', help='Exact isolated checkout path. Defaults to this linked checkout.')
    parser.add_argument('--certificate-action', choices=('setup', 'status', 'remove'))
    args = parser.parse_args()

    supplier = args.supplier
    require(supplier.is_absolute() and supplier == supplier.resolve()
            and (supplier / 'scripts/local_ingress.py').is_file(), 'Select an exact Fight Agent OS supplier checkout')
    root = Path.cwd().resolve()
    selector = args.worktree or (None if args.action == 'herd-config' else str(root))
    safe, checkout, _, _ = resolve(root, selector)
    command = [supplier / 'bin/ingress', args.action, '--consumer-root', str(safe)]
    if args.action == 'herd-config':
        require(not args.worktree, 'Herd config includes all registered previews for this framework')
    else:
        require(checkout != safe and str(checkout) == selector,
                'Run from an isolated worktree or select its exact path with --worktree')
        command += ['--worktree', str(checkout)]
    if args.certificate_action:
        require(args.action == 'certificate', 'Certificate action belongs to the certificate command')
        command += ['--certificate-action', args.certificate_action]
    return subprocess.run(command, cwd=supplier).returncode


if __name__ == '__main__':
    try:
        sys.exit(main())
    except (Hold, OSError, ValueError, subprocess.SubprocessError) as error:
        print('Framework ingress incomplete: ' + str(error), file=sys.stderr)
        sys.exit(1)
