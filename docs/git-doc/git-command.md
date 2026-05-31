
## Git常用命令解释

#### git 提交代码

git add .
git commit -m "xxx"
这两个命令是一个组合，几本上是一起用的

git pull
git push
这两个命令是一个组合，几本上也是一起用的。

但开发过程中，我们可以 git commit 多次再 git push。
git commit 的本质就是你本地仓库的提交记录，可以看作是本地仓库的临时存档。
git push是什么作用呢，git push的作用是把本地的所有 commit记录 同步到远程仓库。你会发面git push后，你本地的所有commit记录在
远程仓库都能显示出来。也就是你本地commit一次，远程显示一条commit记录，你本地commit三次，远程显示三条commit记录。

#### git 合并代码

git merge branch_name
这条命令是把 branch_name分支 的代码合并到 当前分支。合并时它默认会进行一次git commit，所以一般使用git merge后不需要再去打
什么git add . & git commit 这种。真正应该和它组合的命令不是git commit。

git merge正确的打开方式是：
git merge branch_name
git pull
git push

对，本就是对

