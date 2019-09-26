#!/usr/bin/env python3
# -*- coding: utf-8 -*-
__author__ = 'QAQ AutoMaton'

import os,random,sys,re
from functools import cmp_to_key
def str_cmp(a,b):
    if len(a)<len(b):
        return -1
    if len(a)>len(b):
        return 1
    return -1 if a<b else 0 if a==b else 1
def dict_cmp(a,b):
    a=a[0]
    b=b[0]
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

if os.path.exists("./type"):
    with open("type","r") as f:
        type=f.readlines()
    for i in range(len(type)):
        if(type[i][-1]=='\n'):
            type[i]=type[i][:-1]
else:
    type=["0"]
    
for i in range(len(s)):
    if(s[i][-1]=='\n'):
        s[i]=s[i][:-1]

ls=list(os.popen("find ./{}".format(s[0])))
os.system('rm -rf {}-new 2>/dev/null'.format(s[0]))
os.mkdir(s[0]+'-new')
sys.stdout=open("{}-new/log".format(s[0]),"w")
for i in range(len(ls)):
    if(ls[i][-1]=='\n'):
        ls[i]=ls[i][:-1]
if type[0]=='0':
    config="""time_limit: 1000
memory_limit: 256000
type: 0
# input_file: 
# output_file: 
"""
elif type[0]=='1':
    header=type[1]
    if "./{}/{}".format(s[0],header) in ls:
        config="""time_limit: 1000
memory_limit: 256000
header: {}
type: 1
# token: 
""".format(header)
        print("Header File Found")
        if "./{}/grader.cpp".format(s[0]) in ls:
            print("Grader Found")
            os.system("cp ./{0}/grader.cpp ./{0}-new/".format(s[0]))
            os.system("cp ./{0}/{1} ./{0}-new/".format(s[0],header))
        else:
            print("Grader Not Found")
            sys.exit()
    else:
        print("Header File Not Found")
        sys.exit()

if './{}/chk.cpp'.format(s[0]) in ls:
    os.system("cp {0}/chk.cpp {0}-new/chk.cpp".format(s[0]))
    print("checker found") 
else:
    config+='checker: fcmp\n'
if len(s)==1 or len(s)==2:
    allin1= len(s)==1
    s=s[0]
    data=[]
    for i in ls:
        if i[-3:]=='.in':
            if (i[:-3]+'.out' in ls) or (i[:-3]+'.ans' in ls):
                data.append(i[:-3])
    data.sort(key=cmp_to_key(str_cmp))
    t=0 
    if allin1:
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
    else:
        config+="subtask_num: {}\n".format(len(data))
        for i in data:
            if i+'.ans' in ls:
                out=i+'.ans'
            else:
                out=i+'.out'
            t+=1

            os.mkdir('{}-new/{}'.format(s,t))
            print("Subtask",t)
            print(i+'.in,'+out)
            os.system("cp {1}.in {0}-new/{2}/data{2}.in".format(s,i,t))
            os.system("cp {1} {0}-new/{2}/data{2}.ans".format(s,out,t))
            config+="""subtask{}: 
  data_num: 1
  type: sum 
  score: 1
""".format(t);

    with open(s+'-new/config.yml',"w") as f:
        f.write(config)
else:
    patt='./'+s[0]+'/'+s[1].replace('<','({tuple[').replace('>',']})')
    Input='./'+s[0]+'/'+s[1].replace('<','{tuple[').replace('>',']}')
    Output='./'+s[0]+'/'+s[2].replace('<','{tuple[').replace('>',']}')
    while(s[-1]==''):
        s=s[:-1]
    t=len(s)-4
    a={}
    for i in range(t):
        a[i+1]=s[i+3]
    patt=patt.format(tuple=a)
    datas={}
    match=[0]*(t+1)
    def push(l,r,mp,g):
        if l>r:
            pass
        else:
            if mp.get(g[l])==None:
                mp[g[l]]={}
            push(l+1,r,mp[g[l]],g)
    config+='subtask_num: {sub_num}\n'
    subid=dataid=0
    def dfs(l,r,mp,g,sub):
        global subid,match,dataid,s
        if l==sub+1:
            if(subid>0):
                global config
                config+="""subtask{}:
# dependency:
#  - 
 data_num: {}  
""".format(subid,dataid)+""" type: {type}
 score: 1
"""
                
            subid+=1
            print('Subtask',subid)
            os.mkdir('{}-new/{}'.format(s[0],subid))
            dataid=0

        if(l>r):
            dataid+=1 
            os.system("cp {} {}-new/{}/data{}.in".format(Input.format(tuple=match),s[0],subid,dataid)) 
            os.system("cp {} {}-new/{}/data{}.ans".format(Output.format(tuple=match),s[0],subid,dataid)) 
            print(Input.format(tuple=match),Output.format(tuple=match))
            return
        mp=list(mp.items())
        mp.sort(key=cmp_to_key(dict_cmp)) 
        for x,y in mp:
            match[l]=x
            dfs(l+1,r,y,g,sub)
            
    for str in ls:
        if re.match(patt+'$',str):
            g=tuple('0')+re.match(patt+'$',str).groups()
            if Output.format(tuple=g) in ls:
                push(1,t,datas,g)
    dfs(1,t,datas,g,int(s[-1]))
    config+="""subtask{}:
 data_num: {}  
# dependency:
#  - 
""".format(subid,dataid)+""" type: {type}
 score: 1
"""
    with open(s[0]+'-new/config.yml',"w") as f:
        f.write(config.format(sub_num=subid,type='sum' if subid==1 else 'min'))



