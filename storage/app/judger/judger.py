import os
import lorun
import subprocess
from checker import *

JUDGE_RESULT = [
    'Accepted',
    'Wrong Answer',
    'Time Limit Exceeded',
    'Memory Limit Exceeded',
    'Presentation Error',
    'Runtime Error',
    'Judgement Failed',
    'Partially Correct',
]

class Judger:

    def __init__(self, config, src_path, language = 'cpp'):
        self.config = config
        self.src_path = src_path
        self.language = language
        self.compile_src()

    def compile_src(self):
        if (self.language == 'cpp'):
            self.cmd = 'ulimit -t 5 && g++-8 %s -o exec -DONLINE_JUDGE -O2' % self.src_path

    def run(self, stdin, stdout):

        fin = open(stdin)
        fout = open('temp.out', 'w')

        runcfg = {
            'trace' : True,
            'calls' : [0, 1, 2, 3, 4, 5, 8, 9, 10, 11, 12, 21, 59, 158, 231, 257], 
            'files' : {'/etc/ld.so.cache': 0},
            'args' : ['./exec'],
            'fd_in' : fin.fileno(),
            'fd_out' : fout.fileno(),
            'timelimit' : self.config['time_limit'] * 1000,
            'memorylimit' : min(1024 * 1024, self.config['memory_limit'] * 2048),
        }

        res = lorun.run(runcfg)

        fin.close()
        fout.close()

        if res['memoryused'] > self.config['memory_limit'] * 1024:
            res['result'] = 3

        if res['result'] == 0:
            fout = open('temp.out')
            fans = open(stdout)

            try:
                info = self.checker.check(inpath, outpath, anspath)
                fout.close()
                fans.close()
                res['result'] = info.result
                res['score'] = info.score
            except CheckerException as e:
                print (e)
                res['result'] = 6
                res['score'] = 0.
        else:
            res['score'] = 0.

        os.remove('temp.out')
        return res

    def judge(self, problem_id):

        try:
            subprocess.check_output(self.cmd, shell = True, stderr = subprocess.STDOUT, timeout = 5)
        except subprocess.CalledProcessError as e:
            compile_info = e.output.decode('utf-8')
            return { 'result' : 'Compile Error', 'score' : -1, 'judge_info' : "%s" % compile_info }
        except subprocess.TimeoutExpired as time_e:
            return { 'result' : 'Compile Error', 'score' : -1, 'judge_info' : 'Compile Time Exceeded' }

        checker_type = self.config.get('checker_type', 'builtin')

        if checker_type == 'custom':
            checker_name = self.config.get('checker_name', 'spj')
            self.checker = Checker('../problems/%d/%s.cpp' % (problem_id, checker_name))
        else:
            checker_name = self.config.get('checker_name', 'ncmp')
            self.checker = BuiltinChecker(checker_name)

        info = { }
        score = 0
        time = -1
        memory = -1
        judge_info = ""

        if 'subtasks' in self.config:
            pass 
        else:
            score_per_case = 100 // self.config['test_cases']

            for i in range(self.config['test_cases']):
                stdin =  '../problems/%d/%s%d.in'  % (problem_id, self.config['problem_name'], i)
                stdout = '../problems/%d/%s%d.out' % (problem_id, self.config['problem_name'], i)

                if os.path.isfile(stdin) and os.path.isfile(stdout):
                    res = self.run(stdin, stdout)
                    time = max(time, res['timeused'])
                    memory = max(memory, res['memoryused'])
                    info.update({ 'case%d' % i : res })
                    judge_info += "%d,%d,%d,%d;" % (res['result'], res['timeused'], res['memoryused'], int(res['score'] * score_per_case))
                else:
                    info.update({ 'case%d' % i : 'Data Error' })
                    judge_info += "%d,%d,%d,%d;" % (-1, -1, -1, 0)

                score += int(score_per_case * res['score'])

                if res['result'] == 0:
                    pass
                elif 'result' not in info:
                    info['result'] = JUDGE_RESULT[res['result']]

            if 'result' not in info:
                info['result'] = JUDGE_RESULT[0]

            info['score'] = score
            info['time'] = time
            info['memory'] = memory
            info['judge_info'] = judge_info

        os.remove('./exec')
        return info
