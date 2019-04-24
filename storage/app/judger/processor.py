import os
import yaml
import config
import pymysql

from judger import *
from checker import *

cmd_select = "SELECT * FROM submission WHERE `result` = 'Waiting' ORDER BY `id` ASC"
cmd_update = """
UPDATE submission SET 
`result` = '%s', 
`score` = %d, 
`time_used` = %d, 
`memory_used` = %d, 
`judge_info` = "%s" 
WHERE `id` = %d
"""

cnt = 1
while cnt:
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

        src_path = os.path.join('./', 'temp.cpp')
        conf_path = os.path.join('../problems/%d/config.yml' % sub['problem_id'])

        print (sub['source_code'], file = open('./temp.cpp', 'w'))

        try:
            with open(conf_path, 'r') as stream:
                conf = yaml.load(stream)
                judger = Judger(sub['problem_id'], conf, src_path)
                res = judger.judge()
        except FileNotFoundError as e:
            res = { 'score' : -1, 'result' : 'Data Error', 'judge_info' : 'Data not found : \n %s' % e }
        except ConfigError as e:
            res = { 'score' : -1, 'result' : 'Data Error', 'judge_info' : 'Invalid config.yml : \n %s' % e }
        except CheckerError as e:
            res = { 'score' : -1, 'result' : 'Data Error', 'judge_info' : '%s' % e }
        except CompileError as e:
            res = { 'score' : -1, 'result' : 'Compile Error', 'judge_info' : '%s' % e }

        res['time'] = res.get('time', -1)
        res['memory'] = res.get('memory', -1)
        res['judge_info'] = res['judge_info'].replace('\\n', '\\\\n').replace('\"', '\\"')

        cmd = cmd_update % (res['result'],res['score'],res['time'],res['memory'],res['judge_info'],sub['id'])
        cursor.execute(cmd)

        conn.commit()
        os.remove('./temp.cpp')
    except Exception as e:
        print (e)
