import os
import yaml
import lorun

JUDGE_RESULT = [
    'Accepted',
    'Wrong Answer',
    'Time Limit Exceeded',
    'Memory Limit Exceeded',
    'Wrong Answer',
    'Runtime Error',
    'Wrong Answer',
    'Compile Error',
    'System Error'
]

class Judger:

    def __init__(self, config, src_path, language = 'cpp'):
        self.config = config
        self.src_path = src_path
        self.language = language

    def compile_src(self):
        if (self.language == 'cpp'):
            if os.system('g++ %s -o exec -DONLINE_JUDGE -O2' % self.src_path) != 0:
                return False
            return True

    def run(self, stdin, stdout):

        fin = open(stdin)
        ftemp = open('temp.out', 'w')

        runcfg = {
                'args' : ['./exec'],
                'fd_in' : fin.fileno(),
                'fd_out' : ftemp.fileno(),
                'timelimit' : self.config['time_limit'] * 1000,
                'memorylimit' : self.config['memory_limit'] * 1024,
                }

        res = lorun.run(runcfg);

        fin.close()
        ftemp.close()

        if res['result'] == 0:
            ftemp = open('temp.out')
            fout = open(stdout)

            info = lorun.check(fout.fileno(), ftemp.fileno())

            ftemp.close()
            fout.close()

            os.remove('temp.out')

            if info != 0:
                res.update({ 'result' : info })

        return res

    def judge(self, problem_id):

        if self.compile_src() == 0:
            return { 'result' : 'Compile Error', 'score' : 0 }

        info = { }

        if 'subtasks' in self.config:
            pass 
        else:
            score = 0
            score_per_test = 100 // self.config['test_cases']

            for i in range(self.config['test_cases']):
                stdin =  os.path.join('../problems/%d' % problem_id, '%s%d.in'  % (self.config['problem_name'], i));
                stdout = os.path.join('../problems/%d' % problem_id, '%s%d.out' % (self.config['problem_name'], i));

                if os.path.isfile(stdin) and os.path.isfile(stdout):
                    res = self.run(stdin, stdout)
                    info.update({ 'case%d' % i : res })
                else:
                    info.update({ 'case%d' % i : 'Data Error' })

                if res['result'] == 0:
                    score += score_per_test
                elif 'result' not in info:
                    info.update({ 'result' : JUDGE_RESULT[res['result']] })

            if 'result' not in info:
                score = 100
                info.update({ 'result' : JUDGE_RESULT[0] })

            info.update({ 'score' : score })

        os.remove('./exec')

        return info
