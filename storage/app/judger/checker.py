import os
import subprocess
import tempfile

class CheckerException(Exception):
    pass

class CheckerResult:

    def __init__(self, code):
        self.score = 0.

        if code == 0:
            self.result = 0
            self.score = 1.
        elif code == 1 or code == 4 or code == 5:
            self.result = 1
        elif code == 2 or code == 8:
            self.result = 4
        elif code == 3:
            self.result = 6
        elif code >= 16 and code <= 116:
            self.result = 7
            self.score = (code - 16.) / 100

class BuiltinChecker:
    builtin_checkers = ["acmp", "caseicmp", "casencmp", "casewcmp", "dcmp", "fcmp", "hcmp", "icmp", "lcmp", "ncmp", "pointscmp", "rcmp", "rcmp4", "rcmp6", "rcmp9", "rncmp", "uncmp", "wcmp", "yesno"]

    def __init__(self, checker = 'ncmp'):
        self.checker = checker

        if not checker in self.builtin_checkers:
            raise CheckerException("Unknown builtin checker: %s" % checker)

    def check(self, inpath, outpath, anspath):
        checker_path = os.path.join("./checkers", "%s.cpp" % self.checker)
        checker_exec = os.path.join("./checkers", "%s" % self.checker)

        if not os.path.exists(checker_exec):
            subprocess.run(['g++-8', checker_path, '-o', checker_exec, '-O2'], check = True)

        result_file = tempfile.NamedTemporaryFile()

        try:
            subprocess.run([checker_exec, inpath, outpath, anspath, result_file.name], check=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            code = 0
        except subprocess.CalledProcessError as err:
            code = err.returncode

        return CheckerResult(code)

class Checker:

    def __init__(self, checker_path):
        self.checker_exec = tempfile.NamedTemporaryFile()
        try:
            subprocess.run(['g++-8', checker_path, '-o', self.checker_exec, '-O2'], check = True)
        except subprocess.CalledProcessError as e:
            raise CheckerException('Invalid checker')

    def check(self, inpath, outpath, anspath):
        result_file = tempfile.NamedTemporaryFile()

        try:
            subprocess.run([self.checker_exec, inpath, outpath, anspath, result_file.name], check=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            code = 0
        except subprocess.CalledProcessError as err:
            code = err.returncode

        return CheckerResult(code)
