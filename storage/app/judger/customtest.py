import os
import lorun
import yaml
import config
import pymysql

RESULT_STR = [
    'Successed',
    'Presentation Error',
    'Time Limit Exceeded',
    'Memory Limit Exceeded',
    'Wrong Answer',
    'Runtime Error',
    'Output Limit Exceeded',
    'Compile Error',
    'System Error'
]

def run(p_path, in_path, out_path):
    if os.system('g++ %s -o customtest_exec -O2 -std=c++11' % src_path) != 0:
        return {
			'result' : 7,
			'time' : 0,
			'memory' : 0,
		}

    fin = open(in_path)
    fout = open(out_path, 'w')
    runcfg = {
        'args':['./customtest_exec'],
        'fd_in':fin.fileno(),
        'fd_out':fout.fileno(),
        'timelimit':10000, #in MS
        'memorylimit':1024*1024, #in KB
    }
    
    rst = lorun.run(runcfg)
    fin.close()
    fout.close()

    os.remove('./customtest_exec');

    return rst


cmd_select = "SELECT * FROM custom_test_submission  WHERE `result` = 'Waiting' ORDER BY `id` ASC"
cmd_update = """
UPDATE custom_test_submission SET 
`result` = '%s', 
`time_used` = %d, 
`memory_used` = %d
WHERE `id` = %d
"""

while 1:
    try:
        conn = pymysql.connect(
            host = config.table['host'],
            user = config.table['user'],
            password = config.table['password'],
            database = config.table['database'],
            charset = 'utf8'
        )
        cursor = conn.cursor(cursor = pymysql.cursors.DictCursor)
        cursor.execute(cmd_select)
        sub = cursor.fetchone()

        print (sub)

        if (sub == None):
            continue

        id=sub['id']
        src_path = '../customtest/%d.cpp' % id
        in_path = '../customtest/%d.in' % id
        out_path = '../customtest/%d.out' % id

        res = run(src_path, in_path, out_path);

#print(res)

        res['result'] = RESULT_STR[res['result']]

        cmd = cmd_update % (res['result'],res['timeused'],res['memoryused'],id)

#print(cmd)

        cursor.execute(cmd)

        conn.commit()

    except Exception as e:
        print (e)
        break
