
## Git常用命令解释

#### git 切换分支

git checkout branch_name
意为切换到分支 branch_name 

### git 创建分支

```shell
git checkout -b branch_name #基于本地当前分支创建新分支 branch_name
```

如果是想基于dev分支去创建分支，但本地又还没有dev分支（比方说我们重新git clone时就会碰到），那我们就需要使用到
下面这个组合了。

```shell
git fetch #拿到最新的云端分支名册
git checkout dev #
git checkout -b branch_name
```
**Git 的隐藏智能**：只要你执行了 git fetch，当你在本地敲一个本地不存在、但远程刚好有的分支名（比如 dev）时，Git 会非常聪明地触发“自动追踪机制”。它会自动在本地创建一个 dev，并把它和远程的 origin/dev 连起来。

演化成的take命令:

```shell
take switch branch_name => { 
        [git fetch] # 当检查不到本地分支branch_name时，执行 git fetch 再重新执行下一个命令。
        git checkout branch_name
    }
take new branch_name => { 
        git checkout -b branch_name 
    }
```

#### git 提交代码

```shell
git add .
git commit -m "xxx"
#这两个命令是一个组合，几本上是一起用的。 对应的take命令是 take commit


git pull
git push
#这两个命令是一个组合，几本上也是一起用的。 对应的take命令是 take push
```

但开发过程中，我们可以 git commit 多次再 git push。
git commit 的本质就是你本地仓库的提交记录，可以看作是本地仓库的临时存档。
git push是什么作用呢，git push的作用是把本地的所有 commit记录 同步到远程仓库。你会发面git push后，你本地的所有commit记录在
远程仓库都能显示出来。也就是你本地commit一次，远程显示一条commit记录，你本地commit三次，远程显示三条commit记录。

#### git 提交代码-保持多次commit却只有一条commit记录

```shell
git add .
git commit --amend --no-edit
```

git commit --amend --no-edit 中的 --amend意思就是修正的意思，意为在上一条commit的基础上进行修正，而
--no-edit的意思是不要叫我写注释沿用上一次commit的注释。
每次这个命令组合执行一次，就能保持一直只有一条commit记录。不会因为你commit多次而产生多条commit记录。因为
多条commit记录很难看。
--amend的实际效果：这并不是在原有的 Commit 上“追加”内容，而是 Git 在暗中每一次都做了一个全新的 Commit，
然后把老 Commit 悄悄顶替掉。
（这也说明了，我们写take命令时应该有这种修正提交的命令，暂时就用 take save 吧）

#### git 合并代码

git merge branch_name
这条命令是把 branch_name分支 的代码合并到 当前分支。合并时它默认会进行一次git commit，所以一般使用git merge后不需要再去打
什么git add . & git commit 这种。真正应该和它组合的命令不是git commit。

git merge正确的打开方式是：

```shell
git merge branch_name
```

对，本就是和 pull push组合使用的，不需要commit。 
对应的take命令为 take merge branch_name

#### git 推送 同步到远程

```shell
git pull
git push
```

=> take push

#### git 强推复盖远程最后一个提交

这种操作一般来说只在自己的开发分支也操作比较安全，强推之前一定确认一下远程分支最后的commitMsg是不是你正在开发的功能。避免强推
错了commitID。强推的命令如下

```shell
git pull
git push --force
```

但是上面这种组合还是有风险，如果你和同事在同一个分支开发。你下午 2 点拉了代码，然后开始补写 README.md。在你补写文档的这 5 分钟里，同事小明刚好修复了一个紧急 Bug 并 push 到了云端。
此时如果你执行 --force，Git 完全不会提示你，它会冷酷地把你本地的内容拍上去，瞬间把小明刚刚修复的 Bug 代码在云端物理蒸发。
所以推荐用下面这种 --force-with-lease的组合。
--force-with-lease（字面意思是“带着租约/契约的强推”）是一个高智商的安全锁。它在强推之前，会先悄悄做一次对账。
它的逻辑：“如果云端的那个 Commit ID，刚好是我上次看到的那个，说明这期间没有别人动过这个分支，我同意强推；
如果云端的 Commit ID 变了，说明有人在我之后偷偷提交了新东西，我立刻拒绝强推！”
安全画面：同样是上面的场景。你在补写 README.md 的时候，小明偷偷推了 Bug 修复。当你敲下 take pushForce
（底层跑 --force-with-lease）时，Git 会瞬间触发拦截并报错：
error: failed to push some refs to '...'\nhint: Updates were rejected because the remote contains work that you do...
它会明确告诉你：云端已经被偷家了！ 这样就绝对不会误伤小明的代码。

```shell
git pull
git push --force-with-lease
```

=> take pushForce

#### git 查看最后几个提交记录

```shell
git log -5 # 只查看最后5个Commit
git log -7 # 只查看最后7个Commit
```

还有个更利害的
```shell
git log -5 --oneline
```
它打印出的是这样的：
```text
7a1b2c3 (HEAD -> dev) 修复了用户登录时空指针的 Bug
f4e5d6c 补充了 README.md 中的工具链部署说明
9c8b7a6 实现了 take switch 的自动 fetch 懒加载逻辑
3a2b1c0 完成了 take save 的无脑存盘覆盖功能
d9e8f7a 初始化项目，搭建 Go 语言基础脚手架
```

演化出来的take命令：

```shell
take history -n => {
        git log -5 --oneline #-n 对应的是 这里的-5，可以自己定列出条数，不写-n 默认为-5
    }
```

#### git 撒消本地所有修改

git reset --hard HEAD

=> take reset

#### 最终的take命令清单

```shell
take commit "xxx"   ## 踩出新脚印，大方留下正式提交记录。
take save           ## 疯狂无脑 Ctrl + S，无限往最新的那个 Commit 里塞代码，绝不产生垃圾节点。
take push           ## 常规稳妥推送上云。
take pushForce      ## 带安全锁的强推，完美覆盖远端错别字。
take switch <分支名> ## 极速横跳分支，本地找不到时极速报错并自动触发 fetch 懒加载。
take merge <分支名>  ## 合并分支<分支名>
take reset          ## 撒消本地所有修改
take history        ## 极其清爽地一行一条，只看最近 5 条大作。
```