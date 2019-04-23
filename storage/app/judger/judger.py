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

class ConfigError(Exception):

    def __init__(self, info):
        self.info = info
    
    def __str__(self):
        return repr(self.info)

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

            checker = BuiltinChecker('fcmp', fin.name, fout.name, fans.name)
            info = checker.check()

            fout.close()
            fans.close()

            if info.result != 0:
                res.update({ 'result' : info.result, 'score' : info.score })
            else:
                res['score'] = 1.
        else:
            res['score'] = 0.

        os.remove('temp.out')
        return res

    def judge(self, problem_id):

        try:
            subprocess.check_output(self.cmd, shell=True, stderr=subprocess.STDOUT, timeout=5)
        except subprocess.CalledProcessError as e:
            compile_info = e.output.decode('utf-8')
            return { 'result' : 'Compile Error', 'score' : 0, 'judge_info' : "%s" % compile_info }
        except subprocess.TimeoutExpired as time_e:
            return { 'result' : 'Compile Error', 'score' : 0, 'judge_info' : 'Compile Time Exceeded' }

        info = { }
        score = 0
        time = -1
        memory = -1
        judge_info = ""

        if 'subtasks' in self.config:
            #judge_info = "1"
            for sub in self.config['subtasks']:

                cases = []
                if 'score' not in sub or type(sub['score']) != int:
                    raise ConfigError('invalid subtask info : \'score\'')
                elif 'test_cases' not in sub:
                    raise ConfigError('invalid subtask info : \'test_cases\'')
                elif type(sub['test_cases']) == list:
                    for i in range(len(sub['test_cases']) // 2):
                        l = sub['test_cases'][i * 2]
                        r = sub['test_cases'][i * 2 + 1]
                        if type(l) != int or type(r) != int or l > r:
                            raise ConfigError('invalid subtask info : invalid interval in test set')
                        cases = cases + list(range(l, r + 1))
                elif type(sub['test_cases']) == dict:
                    for i in sub['test_cases']:
                        if type(i) != int:
                            raise ConfigError('invalid subtask info : invalid element in test set')
                        cases.append(i)
                else:
                    raise ConfigError('invalid subtask info : \'test_cases\'')

                sub_score = sub['score']
                sub_result = 0;

                for i in cases:
                    print ('#%d' % i)
                    stdin =  '../problems/%d/%s%d.in'  % (problem_id, self.config['problem_name'], i)
                    stdout = '../problems/%d/%s%d.out' % (problem_id, self.config['problem_name'], i)

                    res = self.run(stdin, stdout)
                    print (res)
                    info.update({ 'case%d' % i : res })
                    #judge_info += "%d,%d,%d,%d;" % (res['result'], res['timeused'], res['memoryused'], int(res['score'] * sub['score']))
                    sub_time = max(time, res['timeused'])
                    sub_memory = max(memory, res['memoryused'])
                    sub_score = min(sub_score, int(sub['score'] * res['score']))

                    if res['result'] == 0:
                        pass
                    elif sub_result == 0:
                        sub_result = res['result']

                judge_info += "%d,%d,%d,%d;" % (sub_result, sub_time, sub_memory, sub_score)
                time = max(time, sub_time)
                memory = max(memory, sub_memory)
                score += sub_score

                if sub_result == 0:
                    pass
                elif 'result' not in info:
                    info['result'] = JUDGE_RESULT[sub_result]


        else:
            #judge_info = "0"

            if 'test_cases' not in self.config or type(self.config['test_cases']) != int:
                raise ConfigError('invalid info : \'test_cases\'')

            score_per_test = 100 // self.config['test_cases']

            for i in range(self.config['test_cases']):
                stdin =  '../problems/%d/%s%d.in'  % (problem_id, self.config['problem_name'], i)
                stdout = '../problems/%d/%s%d.out' % (problem_id, self.config['problem_name'], i)

                res = self.run(stdin, stdout)
                info.update({ 'case%d' % i : res })
                judge_info += "%d,%d,%d,%d;" % (res['result'], res['timeused'], res['memoryused'], int(res['score'] * score_per_test))
                time = max(time, res['timeused'])
                memory = max(memory, res['memoryused'])
                score += int(score_per_test * res['score'])

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
