

安装说明:
服务器环境 PHP5.3 + Mysql  数据库

修改文件要用PHP编辑器修改

1.将文件解压后放在根目录里

2.修改数据库链接参数，修改好后，导入数据库sql文件  
数据库链接文件 data\config\config.ini.php
修改下面子段
$config['db']['1']['dbhost'] = 'localhost';
$config['db']['1']['dbport'] = '3306';
$config['db']['1']['dbuser'] = 'xxxxxx';  数据库用户名
$config['db']['1']['dbpwd'] = 'xxxxxx';      数据库密码
$config['db']['1']['dbname'] = 'xxxxxx';  数据库名

3.修改 data\config\config.ini.php  第21行 
这个网址http://posw.98hsd.com/admin 改成你的网址
$config['mobile_modules_url'] = 'http://posw.98hsd.com/admin/modules/mobile;

4.修改文件 control\net.php 第30行
这个网址http://posw.98hsd.com/ 改成你的网址
 $url = "http://http://posw.98hsd.com/ys/lay.php?uid=" . $this->userid . "&r=" . time();

后台地址   
你的网址 http://www.xxxxxx.com/admin
账号 admin  密码 1
前台 http://www.xxxxxx.com
账号 a001 密码1 二级密码 1

制度说明
1、这个程序是卡益源码根据客户要求修改后的版本，本来是支持四个会员级别，但是客户只保留了一个会员级别。、
2、报单费（激活会员需要多少钱）
3、直推奖 推荐一个人得多少钱
4、层碰奖 每层进来的左右各取第一个人，计算层碰1:1得多少
5、量碰奖 每层除了参与层碰的人以外的人的业绩都算量碰，有设置封顶。
6、见点奖 不管下线发展多少人，增加一个人就获得一次见点奖，只发一次
7、服务站补贴  当有会员成为服务站（报单中心）的时候，每激活一个会员就会获得这份补贴
8、重复消费 没启用，也没测试过这个重复消费是什么意思
10、分润工资  这个就相当于每个月发固定工资，这个是按级别来的，在财务里有这个
11、有内部商城，可提供完整的购买和发货流程（不支持在线支付和快递发货的部分，就是一个样子，但是有流程）



其他参考

服务器购买：http://www.sucaihuo.com/servers

源码安装常见问题：http://www.sucaihuo.com/topic/2097.html

更多源码下载：http://www.sucaihuo.com/source



