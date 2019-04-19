import os
import subprocess
import tempfile

class CheckerException(Exception):
    pass

class CheckerResult:
    def __init__(self, result, score = 0.):
        self.result = result
        self.score = score

class BuiltinChecker:
    builtin_checkers = ["acmp", "caseicmp", "casencmp", "casewcmp", "dcmp", "fcmp", "hcmp", "icmp", "lcmp", "ncmp", "pointscmp", "rcmp", "rcmp4", "rcmp6", "rcmp9", "rncmp", "uncmp", "wcmp", "yesno"]

    def __init__(self, checker, inpath, outpath, anspath):
        self.checker = checker
        self.inpath = inpath
        self.outpath = outpath
        self.anspath = anspath

        if not checker in self.builtin_checkers:
            raise CheckerException("Unknown builtin checker type %s" % config['type'])

    def check(self):
        checker_path = os.path.join("./checkers", "%s.cpp" % self.checker)
        checker_exec = os.path.join("./checkers", "%s" % self.checker)

        if not os.path.exists(checker_exec):
            subprocess.run(['g++-8', checker_path, '-o', checker_exec, '-O2'], check = True)

        result_file = tempfile.NamedTemporaryFile()

        try:
            subprocess.run([checker_exec, self.inpath, self.outpath, self.anspath, result_file.name], check=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            code = 0
        except subprocess.CalledProcessError as err:
            code = err.returncode
    
        return get_result(code)

class Checker:
    def __init__(self, checker, outpath, anspath):
        pass

def get_result(code):

    if code == 0:
        return CheckerResult(0, 1.)
    elif code == 1:
        return CheckerResult(1)
    elif code == 2 or code == 8:
        return CheckerResult(4)
    elif code == 3:
        return CheckerResult(6)
    elif code == 4:
        return CheckerResult(4)
    elif code == 5:
        return CheckerResult(1)
    elif code >= 16 and code <= 116:
        return CheckerResult(7, (code - 16.) / 100)
