#!/usr/bin/env python3
# -*- coding: utf-8 -*-
__author__ = 'QAQAutoMaton'

import sys
import yaml

name=sys.argv[1]

with open(name,"r") as f:
    config=yaml.load(f,Loader=yaml.SafeLoader)

testdata={}
config.pop("time_limit_same",None)
config.pop("memory_limit_same",None)
if 'checker' in config.keys():
    if config['checker']=='fcmp':
        config['checker']='noip'
c=config['subtask_num']
for i in range(1,c+1):
    d=config['subtask{}'.format(i)]['data_num']
    config['subtask{}'.format(i)].pop('data_num',None)
    datas=[]
    for j in range(1,d+1):
        datas.append(["{}/data{}.in".format(i,j),"{}/data{}.ans".format(i,j)])
    testdata['subtask{}'.format(i)]=datas

nw={'config':config,"testdata":testdata}
#print(nw)
         
with open(name,"w") as f:
    yaml.dump(nw,f)
    #,Loader=yaml.SafeLoader)
