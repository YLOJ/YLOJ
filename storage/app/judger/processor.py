import os
import yaml
import pymysql
import time
import redis
from oj.env import *

cmd_select = "SELECT * FROM submission WHERE `id` = {}"
cmd_update = """
UPDATE submission SET 
`result` = 'Running'
WHERE `id` = {}
"""
r=redis.Redis(host=redishost,port=redisport,password=redispassword)
while True:
    try:
        Type,sid=r.blpop('submission')[1].split()
        conn = pymysql.connect(
            host = host,
            user = user,
            password = password,
            database = database,
            charset = 'utf8'
        )
        cursor = conn.cursor(cursor = pymysql.cursors.DictCursor)

        Type=Type.decode()
        sid=int(sid)
        if Type=='test':
            cursor.execute(cmd_select.format(sid))
            sub = cursor.fetchone()
            os.system("rm -rf data user temp 2>/dev/null")
            os.system("cp -r ../data/{} data".format(sub['problem_id']))
            os.system("mkdir temp user")
            if('pragma' in sub['source_code']):
                cursor.execute("""
UPDATE submission SET
`result` = 'Judgement Failed',
`score` = 0,
`judge_info` = '拒绝评测'
WHERE `id` = {}
""".format(sid))
                conn.commit()
                continue
            with open("user/code.cpp","w") as f:
                f.write(sub['source_code'])
            with open("user/lang","w") as f:
                f.write("0\n")
            cursor.execute(cmd_update.format(sid))
            conn.commit()
            print('start judging submission',sid)
            os.system("python3 judger.py {}".format(sid))
            print("done")
    except Exception as e:
        print (e)
