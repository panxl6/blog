# 使用Go语言构建Python模块(翻译)

[原文链接](https://blog.filippo.io/building-python-modules-with-go-1-5/#thecompletedemosource)

使用Go语言(1.5版本)，你可以构建`.so`对象，并且将它们导入为Python模块，进而通过Python直接运行Go语言的代码，而非C语言。

Go1.5发行版带来了一些一致性方面的改变。其中一个我们今天正在使用的一个特效，是构建运行库(.so)并将其导出为C语言二进制文件(ABI)的工具链(这只是一系列新的或者已经在计划中的构建模式的一个而已)。
