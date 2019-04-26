# YLOJ

## 配置题目数据

在题目数据上传页面下上传数据的压缩包, 
包含所有测试文件以及配置文件 `config.yml`, 其中:

- `test_cases` 表示题目数据组数, 从 0 开始标号.
- `problem_name` 表示测试数据的前缀, 
输入数据的格式形如 `{problem_name}{id}.in`, 
输出数据的格式形如 `{problem_name}{id}.out`.
- `time_limit` 和 `memory_limit` 表示时空限制, 
单位分别为 s 和 mb.

### 子任务

如需配置子任务，应在配置文件中加入`subtasks`一项. 对于每个subtask：

- `score`表示该subtask满分，必须为整数.
- `test_cases`表示该subtask包含的测试点编号的集合. 可用`[l, r]`表示一段区间内的所有测试点，或用`[l1, r1, l2, r2, ...]`指定多段区间，也可直接用`{a, b, c, ...}`表示测试点编号的集合.
- `dependency`表示该subtask依赖的subtask的集合. subtask从0开始标号，必须保证依赖的subtask在这个subtask之前. 如没有依赖可省略这一项.

```yaml
subtasks:
    -score: 30
     test_cases: [0, 2] # same as {0, 1, 2}
    -score: 37
     test_cases: [5, 6, 8, 10] # same as {5, 6, 8, 9, 10}
    -score: 33
     test_cases: {3, 4, 233, 666}
     dependency: {0, 1}
```

### 修改题目类型

更改 `problem_type` 来配置不同类型的题目, 
可选项有: `[traditional, interactive, answer-only]`, 
分别表示传统题, 交互题和提交答案题, 默认值为 `traditional`.

#### 配置交互题

将交互库命名为 `grader.cpp` 以及其他依赖文件和数据一起上传, 
评测机在测试的时候会将 `grader.cpp` 和选手提交的代码共同编译.

### 配置比较器

比较器基于 [testlib](https://github.com/MikeMirzayanov/testlib).

更改 `checker_type` 来使用不同的比较器, 
可选项有: `[builtin, custom]`, 默认值为 `builtin`.

#### 内置比较器

更改 `checker_name` 来指定 [特定的比较器](https://github.com/MikeMirzayanov/testlib/tree/master/checkers),
默认值为 `ncmp`.

#### 自定义比较器

将写好的比较器与数据一起上传, 并在 `checker_name` 一栏中填上比较器程序的名称.

### 示例

一般传统题示例

```yaml
problem_name: aplusb
test_cases: 2
time_limit: 1
memory_limit: 128
checker_type: builtin
checker_name: ncmp
```

自定义比较器示例

```yaml
problem_name: aplusb
test_cases: 2
time_limit: 1
memory_limit: 128
checker_type: custom
checker_name: spj
```

有subtask的题示例

```yaml
problem_name: aplusb
time_limit: 1
memory_limit: 128
checker_type: builtin
checker_name: fcmp
subtasks:
    - score: 30
      test_cases: [0, 2] # same as {0, 1, 2}
    - score: 37
      test_cases: [5, 6, 8, 10] # same as {5, 6, 8, 9, 10}
    - score: 33
      test_cases: {3, 4, 233, 666}
      dependency: {0, 1}
```
