# 帮助
## 编译命令
当前只支持C++，编译命令为`g++ code.cpp -o code -O2`
## 题目数据
本OJ的checker基于testlib
### 自动匹配
点击“生成”，就会自动递归匹配zip中所有对应的.in 与.out或者.ans(ans优先)，并且按照长度第一关键字，字典序第二关键字排序。并且会显示在右边栏中。点确认就会匹配成功，并且生成默认配置文件，格式见“手动格式化”。

如果是子任务测试或者一些其它格式的数据，则要在“规则”下写内容，格式类似于lemon的数据自动匹配：

第一行为输入文件的路径，第二行为输出文件的路径，其中如果要使用正则表达式则用<1> <2>等

接下来一行为<1>的内容，接下来一行为<2>的内容，etc.

最后一行为一个数x，表示前x项匹配内容相同则在同一个子任务中。

点击“生成”即会生成出数据列表，按照<1> <2> <3>..顺序排序，其中每个字符串都按长度第一关键字字典序第二关键字排序。

如果自定义checker，则存为chk.cpp，会自动匹配。否则默认为fcmp。

### 手动格式化
请将数据存储为形如X/dataY.in / X/dataY.ans的格式（其中X是subtask编号，从1开始，Y是数据点编号，从1开始）

还要写个config.yml，然后打包上传。

如果自定义checker，则存为chk.cpp
示例：
```yaml
time_limit: 2000 #时间限制，单位为毫秒
memory_limit: 256000 #空间限制，单位为KB
input_file: sequence.in #可选，输入文件名，默认为标准输入
output_file: sequence.out #可选，输出文件名，默认为标准输出
subtask_num: 3 #subtask编号
subtask1: #表示第1个subtask的信息
checker: fcmp #使用的内置checker，自定义则不需要这一行
data_num: 3 #测试点数 
 type: min #类型：sum表示得分取每个点得分的平均数,min表示取每个点得分的最小值
 score: 10 #subtask满分
subtask2:
 dependency: #可选，表示子任务依赖（仅类型为min时有效），该子任务的得分和这些子任务的得分取min
  - 1 # 列表形式
 data_num: 7  
 type: min
 score: 30
subtask3:
 dependency: 
   - 2
 data_num: 8  
 type: min
 score: 60
```
**注意要按照yaml的语法，格式**
