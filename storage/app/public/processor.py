import os
import yaml
import pymysql
import config

from judger import Judger

conn = pymysql.connect(
        host = config.table['host'],
        user = config.table['user'],
        password = config.table['password'],
        database = config.table['database'],
        charset = 'utf8'
        )

cursor = conn.cursor(cursor = pymysql.cursors.DictCursor)

cnt = 10
while cnt:
    print ('begin')
    cursor = conn.cursor(cursor = pymysql.cursors.DictCursor)
    cursor.execute("SELECT * FROM submission WHERE `result` = 'waiting' ORDER BY `id` ASC")
    sub = cursor.fetchone()

    print (sub)

    if (sub == None):
        continue

    judge_id = sub['id']
    problem_id = sub['problem_id']
    source_code = sub['source_code']

    print (source_code, file = open('./temp.cpp', 'w'))

    src_path = os.path.join('./', 'temp.cpp')

    if (os.path.isfile(os.path.join('../problems/%d/config.yml' % problem_id)) != 1):
        res = {
            'result' : 'Data Error',
            'score' : 0,
            }
    else:    
        judger = Judger(yaml.load(open('../problems/%d/config.yml' % problem_id)), src_path)
        res = judger.judge(problem_id)

    os.remove('./temp.cpp')
    print (res)

    cmd = "UPDATE submission SET `result` = '%s', `score` = %d WHERE `id` = %d" % (res['result'], res['score'], judge_id)

    print (cmd)

    ret = cursor.execute("UPDATE submission SET `result` = '%s', `score` = %d WHERE `id` = %d" % (res['result'], res['score'], judge_id))
    print (ret)

    conn.commit()
