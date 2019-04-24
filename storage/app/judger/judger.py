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

            try:
                info = self.checker.check(fin.name, fout.name, fans.name)
                fout.close()
                fans.close()
                res['result'] = info.result
                res['score'] = info.score
            except Exception as e:
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
            checker_name = self.config.get('checker_name', 'fcmp')
            self.checker = BuiltinChecker(checker_name)

        info = { }
        score = 0
        time = -1
        memory = -1
        judge_info = ""

        if 'subtasks' in self.config:
            judge_info = "1|"

            idx = 0
            sub_time = [] 
            sub_score = []
            sub_memory = []
            sub_result = []

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

                sub_memory.append(-1)
                sub_time.append(-1)
                sub_score.append(1)
                sub_result.append(0)

                if 'dependency' in sub and type(sub['dependency']) == dict:
                    for i in sub['dependency']:
                        if type(i) != int or i >= idx:
                            raise ConfigError('invalid subtask dependency')
                        sub_memory[idx] = max(sub_memory[idx], sub_memory[i])
                        sub_time[idx] = max(sub_time[idx], sub_time[i])
                        sub_score[idx] = min(sub_score[idx], sub_score[i])
                        if sub_result[idx] == 0 and sub_result[i] != 0:
                            sub_result[idx] = sub_result[i]

                sub_info = ""
                for i in cases:
                    print ('running on Case #%d in Subtask #%d' % (i, idx))
                    stdin =  '../problems/%d/%s%d.in'  % (problem_id, self.config['problem_name'], i)
                    stdout = '../problems/%d/%s%d.out' % (problem_id, self.config['problem_name'], i)

                    res = self.run(stdin, stdout)
                    print(res)
                    info.update({ 'case%d' % i : res })
                    sub_info += "%d,%d,%d,%d;" % (res['result'], res['timeused'], res['memoryused'], int(res['score'] * sub['score']))
                    sub_time[idx] = max(sub_time[idx], res['timeused'])
                    sub_memory[idx] = max(sub_memory[idx], res['memoryused'])
                    sub_score[idx] = min(sub_score[idx], int(sub['score'] * res['score']))

                    if res['result'] == 0:
                        pass
                    elif sub_result[idx] == 0:
                        sub_result[idx] = res['result']

                judge_info += "%d,%d,%d,%d;" % (sub_result[idx], sub_time[idx], sub_memory[idx], int(sub_score[idx] * sub['score']))
                judge_info += sub_info
                judge_info = judge_info[:-1] + '|'
                time = max(time, sub_time[idx])
                memory = max(memory, sub_memory[idx])
                score += int(sub_score[idx] * sub['score'])

                if sub_result[idx] == 0:
                    pass
                elif 'result' not in info:
                    info['result'] = JUDGE_RESULT[sub_result[idx]]
                idx = idx + 1


        else:
            judge_info = "0|"

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
        print(judge_info)

        os.remove('./exec')
        return info
