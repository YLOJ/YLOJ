import os
import yaml
import pymysql
import time
from oj.env import *

cmd_select = "SELECT * FROM submission WHERE `result` = 'Waiting' ORDER BY `id` ASC"
cmd_update = """
UPDATE submission SET 
`result` = 'Running'
WHERE `id` = {}
"""
cnt = 1
while cnt:
    try:
        time.sleep(1)
        conn = pymysql.connect(
            host = host,
            user = user,
            password = password,
            database = database,
            charset = 'utf8'
        )
        cursor = conn.cursor(cursor = pymysql.cursors.DictCursor)
        cursor.execute(cmd_select)
        sub = cursor.fetchone()

        if (sub == None):
            continue
        os.system("rm -rf data user temp 2>/dev/null")
        os.system("cp -r ../data/{} data".format(sub['problem_id']))
        os.system("mkdir temp user")
        with open("user/code.cpp","w") as f:
            f.write(sub['source_code'])
        with open("user/lang","w") as f:
            f.write("0\n")
        cursor.execute(cmd_update.format(sub['id']))
        conn.commit()
        print("start")
        os.system("python3 judger.py {}".format(sub['id']))
        print("done")
    except Exception as e:
        print (e)
