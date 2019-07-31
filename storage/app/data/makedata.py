#!/usr/bin/env python3
# -*- coding: utf-8 -*-
__author__ = 'QAQ AutoMaton'

import os,random,sys
from functools import cmp_to_key
config="""time_limit: 1000
memory_limit: 256000
# input_file: 
# output_file: 
"""
def str_cmp(a,b):
    if len(a)<len(b):
        return -1
    if len(a)>len(b):
        return 1
    return -1 if a<b else 0 if a==b else 1

def randomString():
    s=""
    for i in range(20):
        s+=chr(ord('a')+random.randint(0,25))
    return s
with open("dataconfig","r") as f:
    s=f.readlines()

for i in range(len(s)):
    if(s[i][-1]=='\n'):
        s[i]=s[i][:-1]
os.system("ls")
ls=list(os.popen("find ./{}".format(s[0])))
os.system('rm -rf {}-new 2>/dev/null'.format(s[0]))
os.mkdir(s[0]+'-new')
sys.stdout=open("{}-new/log".format(s[0]),"w")
if '{}/chk.cpp'.format(s[0]) in ls:
    os.system("cp {0}/chk.cpp {0}-new/chk.cpp".format(s[0]))
    print("checker found")
else:
    config+='checker: fcmp\n'
if len(s)==1:
    s=s[0]
    for i in range(len(ls)):
        if(ls[i][-1]=='\n'):
            ls[i]=ls[i][:-1]
    data=[]
    for i in ls:
        if i[-3:]=='.in':
            if (i[:-3]+'.out' in ls) or (i[:-3]+'.ans' in ls):
                data.append(i[:-3])
    data.sort(key=cmp_to_key(str_cmp))
    t=0 
    os.mkdir('{}-new/1'.format(s))
    for i in data:
        if i+'.ans' in ls:
            out=i+'.ans'
        else:
            out=i+'.out'
        print(i+'.in,'+out)
        t+=1
        os.system("cp {1}.in {0}-new/1/data{2}.in".format(s,i,t))
        os.system("cp {1} {0}-new/1/data{2}.ans".format(s,out,t))
    config+="""subtask_num: 1
subtask1:
 data_num: {}
 type: sum
 score: 100
""".format(t)
    with open(s[0]+'-new/config.yml',"w") as f:
        f.write(config)
else:
    pass
    Input=s[0]+'/'+s[1].replace('<','({tuple[').replace('>',']})')
    Output=s[0]+'/'+s[2].replace('<','({tuple[').replace('>',']})')
    
    t=len(s)-3
    a={}
    for i in range(t):
        a[i+1]=s[i+3][2:]
    print(Input.format(tuple=a))


