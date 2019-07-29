#!/usr/bin/env python3
# -*- coding: utf-8 -*-
__author__ = 'QAQ AutoMaton'
# lang=["C++"]
import yaml,sys,json
from oj import *
RunCommand=["/tmp/{}"]
with open("data/config.yml") as f: 
    config=yaml.load(f,Loader=yaml.SafeLoader)
with open("user/lang") as f: 
    lang=int(f.read())

def compileCode():
    init()
    if lang==0:
        code=moveIntoSandbox("user/code.cpp")
        status=runCommand("g++ /tmp/{} -o /tmp/{} -O2".format(code,code[:-4]))
    if(status.status==0):
        pass
    elif(status.status==2):
        report(Result(score=0,result="Compiler Time Limit Exceeded"))
        sys.exit()
    else:
        report(Result(score=0,result="Compile Error",judge_info=status.message))
        sys.exit()
    moveOutFromSandbox(code[:-4],"code")

def compileSpj():
    init()
    moveIntoSandbox("data/chk.cpp",newName="chk.cpp")
    moveIntoSandbox("data/testlib.h",newName="testlib.h")
    status=runCommand("g++ /tmp/chk.cpp -o /tmp/chk -O2")
    if(status.status != 0):
        report(Result(score=0,result="Special Judge Compile Error",message=status.message))
        sys.exit()
    else:
        moveOutFromSandbox("chk")

def runSpecialJudge(Input,Output,Answer,dataid):
    init()
    Input=moveIntoSandbox(Input)
    Output=moveIntoSandbox(Output)
    Answer=moveIntoSandbox(Answer)
    spj=moveIntoSandbox("temp/chk")
    status=runCommand("/tmp/{} /tmp/{} /tmp/{} /tmp/{}".format(spj,Input,Output,Answer))
    if status.code==0:
        # AC
        return AC,100,status.message
    elif status.code==1 or status.code==4 or status.code==5:
        # WA
        return WA,0,status.message 
    elif status.code==2 or status.code==8:
        # PE
        return PE,0,status.message 
    elif status.code==3:
        # JF
        return JF,0,status.message
    elif status.code==7:
        # ???
        return JF,0,status.message
    else:
        # PC
        return PC,status.code-16,message

def runProgram(Input,Answer,dataid):
    global inputFile,outputFile,lang,timeLimit,memoryLimit
    init()
    if not(inputFile is None):
        moveIntoSandbox(Input,inputFile)
    Output="temp/output"
    code=moveIntoSandbox("temp/code")
    status=runCommand(RunCommand[lang].format(code),
        timeLimit=timeLimit,memoryLimit=memoryLimit,
        stdin=open(Input,"r") if inputFile is None else None,
        stdout=open(Output,"w") if outputFile is None else None,
        )
    if status.status==OK:
        if(not (outputFile is None)):
            moveOutFromSandbox(outputFile,"output")
        status.status,status.score,status.message=runSpecialJudge(Input,Output,Answer,dataid)

    return status 
def toList(status):
    return [status.status,status.time,status.memory,status.code,status.message,status.score]

totalScore=0
totalTime=0
maxMemory=0
try:
    compileSpj()
    #judgingMessage("Compiling")
    compileCode()
    timeLimit=config.get("time_limit",1000)
    memoryLimit=config.get("memory_limit",256000)
    subtaskNum=config.get("subtask_num",0)
    inputFile=config.get("input_file",None)
    outputFile=config.get("output_file",None)
    score=0
    subScore=[0]*(subtaskNum+1)
    info=[]
    for subId in range(1,subtaskNum+1):
        sub=config.get("subtask{}".format(subId),{})
        Full=sub.get("score",0)
        Type=sub.get("type","sum") 
        subScore[subId]=100 if Type=="min" else 0
        if(Type=="min"):
            dependency=sub.get("dependency",[])
            for Id in dependency:
                subScore[subId]=min(subScore[subId],subScore[Id])
        dataNum=sub.get("data_num",0)
        subInfo=[[SKIP,0]]+[[SKIP,0,0,0,"",0]]*dataNum
        for dataId in range(1,dataNum+1):
            if Type=="min" and subScore[subId]==0:
                break
#            judgingMessage("Judging Test {} of Subtask {}".format(dataId,subId))
            dataStatus=runProgram("data/{}/data{}.in".format(subId,dataId),"data/{}/data{}.ans".format(subId,dataId),"{}.{}".format(subId,dataId))
            subScore[subId]=min(subScore[subId],dataStatus.score) if Type=="min" else score + dataStatus.score
            subInfo[dataId]=toList(dataStatus)
            totalTime+=dataStatus.time
            maxMemory=max(maxMemory,dataStatus.memory)
        subtaskScore=Full*subScore[subId]//100
        if Type=="sum":
            subtaskScore//=dataNum
        subInfo[0][0]=AC if subtaskScore==Full else PC if subtaskScore>0 else WA
        subInfo[0][1]=subtaskScore
        totalScore+=subtaskScore
        for i in subInfo:
            i[0]=judgeStatus[i[0]]
        info.append(subInfo)
    report(Result(
        result="Accepted" if totalScore==100 else "Unaccepted",
        score=totalScore,
        time=totalTime,
        memory=maxMemory,
        judge_info=json.dumps(info)))
except:
    report(Result(score=0,result="Judgement Failed"))

