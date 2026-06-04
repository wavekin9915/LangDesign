
## 优化take命令

#### 优化 take commit 

场景一：开荒时刻（今天第一次提交，或者刚 take push 完）
你写完了代码，敲下 take commit "feat: 增加用户核心资产解密面板"。
由于 IsTodayHasUnpushedCommits() 敏锐地察觉到本地代码和远端是百分之百完全同步的（push 过），工具一个字都不多问，清爽放行！

🔹 正在提交代码 [feat: 增加用户核心资产解密面板] ...
🎉 代码本地提交成功！可放心执行 take push 同步至云端。

场景二：高频微调时刻（本地憋了提交，还没来得及 take push）
由于刚刚修了一个错别字或者调整了间距，你又敲了一句 take commit "fix"。
工具发现你今天本地还有没 push 的代码，立刻进入防御状态，弹出精美的淡黄色提示：

💡 侦测到你今天有未 push 的本地提交，上一次描述为: [feat: 增加用户核心资产解密面板]
? 是否坚持使用本次的新描述: [fix] ？
[ ] 坚持用新的  [ ] 沿用上一次

用到的命令有：

```shell
# 取得今天当前分支你自己提交的最后一条 Commit Message 文本
git log -1 --author="$(git config user.name)" --since="00:00:00" --format="%s"

# 对比当前本地分支（HEAD）比远程追踪分支（Upstream）多了哪些提交
git log @{u}..HEAD --since="00:00:00" --oneline
```

#### 优化 take save

git fetch 确实没必要，现实中别人提交的Commit Message与我一样的几乎不可能发生，属于无效操作。

所以优化 take save 就是先判断远端（远端镜像）最后一条Commit Message是否与本最后一条Commit Message相同，
相同就直接阻止并提交【远端已有 Commit Message “XXX” 了】

用到的命令有：

```shell
# 抓取本地最新的 Commit Message
git log -1 --format="%s" HEAD

# 抓取远端（镜像缓存中）最新的 Commit Message
git log -1 --format="%s" @{u}
```