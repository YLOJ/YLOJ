# YLOJ
## 编译命令
当前只支持C++，编译命令为`g++ code.cpp -o code -O2`
## 题目数据
### 手动格式化
请将数据存储为形如X/dataY.in / X/dataY.ans的格式（其中X是subtask编号，从1开始，Y是数据点编号，从1开始）

还要写个config.yml，然后打包上传。

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
### 自动匹配
点击“生成”，就会自动递归匹配zip中所有对应的.in 与.out或者.ans(ans优先)，并且按照长度第一关键字，字典序第二关键字匹配。并且会显示
### 数据校验器(checker)
内置校验器有以下一些(转载自 [link](https://universaloj.github.io/post/传统题配置.html)):

|校验器|功能|
|------|----|
|`ncmp`|（单行整数序列）比较有序64位整数序列|
|`wcmp`|（单行字符串序列）比较字符串序列|
|`fcmp`|（多行数据）逐行进行全文比较，**不忽略行末空格**，忽略文末回车。|
|`icmp`|比较单个整数|
|`ncmp`|（单行整数序列）比较有序64位整数序列|
|`uncmp`|（单行整数序列）比较无序64位整数序列，即排序后比较|
|`acmp`或`rcmp`|比较单个双精度浮点数，最大绝对误差为 1.5e-6|
|`dcmp`|比较单个双精度浮点数，最大绝对或相对误差为 1.0e-6|
|`rcmp4`|比较双精度浮点数序列，最大绝对或相对误差为 1.0e-4|
|`rcmp6`|比较双精度浮点数序列，最大绝对或相对误差为 1.0e-6|
|`rcmp9`|比较双精度浮点数序列，最大绝对或相对误差为 1.0e-9|
|`rncmp`|比较双精度浮点数序列，最大绝对误差为 1.5e-5|
|`hcmp`|比较单个有符号大整数|
|`lcmp`|逐行逐字符串进行全文比较，多个空白字符视为一个|
|`caseicmp`|多组数据，比较单个整数，输出形如：`Case <caseNumber>: <number>`|
|`casencmp`|多组数据，比较整数序列，输出形如：`Case <caseNumber>: <number> <number> ... <number>`|
|`casewcmp`|多组数据，比较字符串序列，输出形如：`Case <caseNumber>: <token> <token> ... <token>`|
|`yesno`|比较单个`YES`和`NO`|

当然你也可以自己写，使用testlib。


## 
