#!/usr/bin/env python3
# -*- coding: utf-8 -*-
__author__ = 'QAQ AutoMaton'

import os,sys,re
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

ls=list(os.popen('find ./{} -printf "%P\n"'.format(s[0])))
sys.stdout=open("{}/config-new.yml".format(s[0]),"w")

for i in range(len(ls)):
    if(ls[i][-1]=='\n'):
        ls[i]=ls[i][:-1]
if type[0]=='0':
    config="""config:
  time_limit: 1000
  memory_limit: 256000
  type: 0
  # input_file: 
  # output_file:
"""
elif type[0]=='1':
    header=type[1]
    if header in ls:
        config="""config:
  time_limit: 1000
  memory_limit: 256000
  header: {}
  type: 1
  # token: 
""".format(header)
        if not "grader.cpp" in ls:
            print("Grader Not Found")
            sys.exit()
    else:
        print("Header File Not Found")
        sys.exit()
elif type[0]=='2':
    if "interactor.cpp" in ls:
        config="""config:
  time_limit: 1000
  memory_limit: 256000
  type: 2
"""
    else:
        print("Interactor Not Found")
        sys.exit()
if type[0]!='2':
    if not 'chk.cpp' in ls:
        config+='  checker: noip\n'

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
        testdata="""testdata:
  subtask1:
"""
        for i in data:
            if i+'.ans' in ls:
                out=i+'.ans'
            else:
                out=i+'.out'

            testdata+="    - "+str([i+'.in',out])+"\n"
        config+="""  subtask_num: 1
  subtask1:
    type: sum
    score: 100
""".format(t)
    else:
        config+="  subtask_num: {}\n".format(len(data))
        testdata="testdata:\n"
        c=len(data)
        for i in data:
            if i+'.ans' in ls:
                out=i+'.ans'
            else:
                out=i+'.out'
            t+=1
            testdata+="  subtask{}: \n    - ".format(t)+str([i+".in",out])+"\n"
            config+="""  subtask{}: 
    type: sum 
    score: {}
""".format(t,(100+t-1)//c)
            if t>1:
                config+="    # dependency: []\n"

else:
    patt=s[1].replace('<','({tuple[').replace('>',']})')
    Input=s[1].replace('<','{tuple[').replace('>',']}')
    Output=s[2].replace('<','{tuple[').replace('>',']}')
    testdata="testdata:\n"
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
    subid=dataid=0
    def dfs(l,r,mp,g,sub):
        global subid,match,dataid,s,testdata
        if l==sub+1:
            subid+=1
            testdata+=" subtask{}:\n".format(subid)
            dataid=0
        if(l>r):
            dataid+=1 
            In=Input.format(tuple=match)
            Out=Output.format(tuple=match)
            #print(In+Out)
            #li=[In,Out]
            #print(str([In,Out]))
            testdata+="   - "+str([Input.format(tuple=match),Output.format(tuple=match)])+'\n'
            return
        mp=list(mp.items())
        mp.sort(key=cmp_to_key(dict_cmp)) 
        for x,y in mp:
            match[l]=x
            dfs(l+1,r,y,g,sub)
            
    for Str in ls:
        if re.match(patt+'$',Str):
            g=tuple('0')+re.match(patt+'$',Str).groups()
            if Output.format(tuple=g) in ls:
                push(1,t,datas,g)
    dfs(1,t,datas,g,int(s[-1]))

    config+="  subtask_num: {}\n".format(subid)
    for i in range(1,subid+1):
        config+="""  subtask{}: 
    type: sum 
    score: {}
""".format(i,(100+i-1)//subid)
        if i>1:
            config+="    # dependency: []\n"
print(config+testdata)
