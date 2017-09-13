# 签到表类型博士合唱团签到小程序-MySQL数据库设计

## 数据库表列值
园区 = 中关村 | 雁栖湖
声部 = S1 | S2 | A1 | A2 | T1 | T2 | B1 | B2
日期 = 2018-09-12
签到表类型 = 大排 | 小排 | 周日晚 | 声乐课

## 日常排练签到

数据库名：phdChorusRegist

建库：

```
create DATABASE phdChorusRegist
```



#### contact表

| 列名                 | 属性                                   | 备注        |
| ------------------ | ------------------------------------ | --------- |
| id                 | INT UNSIGNED NOT NULL AUTO_INCREMENT | 主键        |
| name               | TINYTEXT NOT NULL                    | 姓名        |
| part               | TINYTEXT NOT NULL                    | 所在声部      |
| location           | TINYTEXT NOT NULL                    | 所在园区      |
| include_in_statics | BOOLEAN NOT NULL DEFAULT TRUE        | 是否纳入统计范围内 |

__建表__

```
CREATE TABLE phdChorusRegist.contact ( id INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' , name TINYTEXT NOT NULL COMMENT '姓名' , part TINYTEXT NOT NULL COMMENT '声部' , location TINYTEXT NOT NULL COMMENT '所在园区' , include_in_statics BOOLEAN NOT NULL DEFAULT TRUE COMMENT '是否纳入统计范围' , PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '博士合唱团团员';
```



#### regist_table签到表

| 列名       | 属性                                   | 备注     |
| -------- | ------------------------------------ | ------ |
| id       | INT UNSIGNED NOT NULL AUTO_INCREMENT | 主键     |
| date     | DATE NOT NULL                        | 日期     |
| type     | TINYTEXT NOT NULL                    | 类型     |
| location | TINYTEXT NOT NULL                    | 签到所在园区 |

__建表__

```
CREATE TABLE phdChorusRegist.regist_table ( id INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' , date DATE NOT NULL , type TINYTEXT NOT NULL , location TINYTEXT NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '签到表';
```



#### regist_info签到信息表

| 列名              | 属性                             | 备注                 |
| --------------- | ------------------------------ | ------------------ |
| id              | INT NOT NULL AUTO_INCREMENT    | 主键                 |
| regist_table_id | INT UNSIGNED NOT NULL          | 外键-连接regist_table表 |
| contact_id      | INT UNSIGNED NOT NULL          | 外键-连接contact表      |
| attend          | BOOLEAN NOT NULL DEFAULT FALSE | 是否到场               |

__建表__

```
CREATE TABLE `phdChorusRegist`.`regist_info` ( `id` INT NOT NULL AUTO_INCREMENT COMMENT '主键' , `regist_table_id` INT UNSIGNED NOT NULL COMMENT '外键regsit_table' , `contact_id` INT UNSIGNED NOT NULL COMMENT '外键contact' , `attend` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '是否到场' , PRIMARY KEY (`id`) , FOREIGN KEY(regist_table_id) REFERENCES regist_table(id) ON DELETE CASCADE, FOREIGN KEY(contact_id) REFERENCES contact(id) ON DELETE CASCADE) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '签到信息';
```

添加级联删除策略，删除contact或删除regist_table时，删除regist_info中所有相关的信息

```
ALTER TABLE regist_info ADD FOREIGN KEY(regist_table_id) REFERENCES regist_table(id) ON DELETE CASCADE;
ALTER TABLE regist_info ADD FOREIGN KEY(contact_id) REFERENCES contact(id) ON DELETE CASCADE;
```



#### 授权用户列表

仅授权用户可查看数据

__表结构__

| 列名          | 属性                                   | 备注                                       |
| ----------- | ------------------------------------ | ---------------------------------------- |
| id          | INT UNSIGNED NOT NULL AUTO_INCREMENT | 主键                                       |
| wx_nickname | TINYTEXT NOT NULL COMMENT            | 微信昵称，用以鉴权                                |
| authority   | TINYTEXT NOT NULL                    | 用户权限（S \| A \| T \| B \| ALL），声部长只可修改自己声部相关的信息，团长可以修改所有的信息 |

```
CREATE TABLE phdChorusRegist.authorized_user ( id INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' , wx_nickname TINYTEXT NOT NULL COMMENT '微信昵称' , authority TINYTEXT NOT NULL COMMENT '用户权限' , PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '授权(修改数据库内容)用户列表';
```



## 招新

数据库名：phdChorusRecruit

建库

```
create DATABASE phdChorusRecruit;
```



#### authorized_user授权用户列表

仅授权用户可查看数据

__表结构__

| 列名          | 属性                                   | 备注                                       |
| ----------- | ------------------------------------ | ---------------------------------------- |
| id          | INT UNSIGNED NOT NULL AUTO_INCREMENT | 主键                                       |
| wx_nickname | TINYTEXT NOT NULL COMMENT            | 微信昵称，用以鉴权                                |
| authority   | TINYTEXT NOT NULL                    | 用户权限（S \| A \| T \| B \| ALL），声部长只可修改自己声部相关的信息，团长可以修改所有的信息 |

```
CREATE TABLE test_phdChorusRecruit.authorized_user ( id INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' , wx_nickname TINYTEXT NOT NULL COMMENT '微信昵称' , authority TINYTEXT NOT NULL COMMENT '用户权限' , PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '授权(修改数据库内容)用户列表';
```



#### regist_table签到表

__表结构__

| 列名       | 属性                                    | 备注                                       |
| -------- | ------------------------------------- | ---------------------------------------- |
| id       | INT UNSIGNED NOT NULL AUTO_INCREMENT  | 主键                                       |
| date     | DATE NOT NULL                         | 签到表日期                                    |
| location | TINYTEXT NOT NULL                     | 签到所在园区                                   |
| status   | TINYINT UNSIGNED NOT NULL DEFAULT '0' | 签到表状态（0-禁用 \| 1-可用于报名&确认参加面试 \| 2-可用于现场签到面试） |

__建表语句__

```
CREATE TABLE phdChorusRecruit.regist_table ( id INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' , date DATE NOT NULL COMMENT '签到日期' , location TINYTEXT NOT NULL COMMENT '签到地点' , status TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '签到表状态' ，PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '面试签到表';
```



#### contact_info人员信息表

__表结构__

| 列名          | 属性                                   | 备注                     |
| ----------- | ------------------------------------ | ---------------------- |
| id          | INT UNSIGNED NOT NULL AUTO_INCREMENT | 主键                     |
| name        | TINYTEXT NOT NULL                    | 姓名                     |
| sex         | BOOLEAN NOT NULL                     | 性别（0-女 \| 1-男）         |
| nation      | TINYTEXT NOT NULL                    | 民族                     |
| studentId   | TINYTEXT NOT NULL                    | 学号                     |
| location    | TINYTEXT NOT NULL                    | 目前所在园区                 |
| company     | TINYTEXT NOT NULL                    | 培养单位                   |
| grade       | TINYTEXT NOT NULL                    | 年级                     |
| phone       | TINYTEXT NOT NULL                    | 手机号                    |
| email       | TINYTEXT NOT NULL                    | 邮箱                     |
| vocal       | TINYTEXT NOT NULL                    | 声乐水平                   |
| instruments | TINYTEXT NOT NULL                    | 乐器水平                   |
| readMusic   | TINYTEXT NOT NULL                    | 是否识谱（不识谱 \| 简谱 \| 五线谱） |
| pianist     | BOOLEAN NOT NULL                     | 是否有意愿担任钢伴              |
| interest    | TINYTEXT NOT NULL                    | 兴趣爱好                   |
| skill       | TINYTEXT NOT NULL                    | 技能                     |
| experience  | TINYTEXT NOT NULL                    | 艺术团体经历                 |
| expect      | TINYTEXT NOT NULL                    | 对合唱团的期望                |

__建表__

```
CREATE TABLE phdChorusRecruit.contact_info ( id INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' , name TINYTEXT NOT NULL COMMENT '姓名' , sex BOOLEAN NOT NULL COMMENT '性别' , nation TINYTEXT NOT NULL COMMENT '民族' , studentId TINYTEXT NOT NULL COMMENT '学号' , location TINYTEXT NOT NULL COMMENT '所在园区' , company TINYTEXT NOT NULL COMMENT '培养单位' , grade TINYTEXT NOT NULL COMMENT '年级' , phone TINYTEXT NOT NULL COMMENT '手机号' , email TINYTEXT NOT NULL COMMENT '邮箱' , vocal TINYTEXT NOT NULL COMMENT '声乐水平' , instruments TINYTEXT NOT NULL COMMENT '乐器水平' , readMusic TINYTEXT NOT NULL COMMENT '识谱水平' , pianist BOOLEAN NOT NULL COMMENT '是否钢伴' , interest TINYTEXT NOT NULL COMMENT '兴趣爱好' , skill TINYTEXT NOT NULL COMMENT '技能' , experience TINYTEXT NOT NULL COMMENT '艺术团体经历' , expect TINYTEXT NOT NULL COMMENT '对合唱团的期望' , PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '面试者个人信息';
```



### interview_info面试信息

__表结构__

| 列名              | 属性                               | 备注                                |
| --------------- | -------------------------------- | --------------------------------- |
| id              | UNSIGNED NOT NULL AUTO_INCREMENT | 主键                                |
| contact_info_id | INT UNSIGNED NOT NULL            | 外键-连接contact_info表                |
| regist_table_id | INT UNSIGNED NOT NULL            | 外键-连接regist_table表                |
| status          | TINYINT NOT NULL                 | 面试状态（0-已报名 \| 1-已确认参加 \| 2-已面试签到） |
| waiterID        | SMALLINT NOT NULL                | 面试签到ID (每张签到表的面试者都从1开始编号)         |
| pass            | BOOLEAN NOT NULL                 | 是否通过考核（0-未通过 \| 1-通过）             |
| part            | TINYTEXT NOT NULL                | 分配至声部                             |

__建表__

```
CREATE TABLE phdChorusRecruit.interview_info ( id INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' , contact_info_id INT UNSIGNED NOT NULL COMMENT 'contact_info外键' , regist_table_id INT UNSIGNED NOT NULL COMMENT 'regist_table外键' , status TINYINT NOT NULL COMMENT '面试状态' , waiterID SMALLINT NOT NULL COMMENT '面试签到ID' , pass BOOLEAN NOT NULL DEFAULT FALSE COMMENT '是否通过考核' , part TINYTEXT NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT = '面试信息';
```

设置级联删除策略，删除regist_table或contact_info时，级联删除所有相应的interview_info信息

```
ALTER TABLE interview_info ADD FOREIGN KEY(regist_table_id) REFERENCES regist_table(id) ON DELETE CASCADE;
ALTER TABLE interview_info ADD FOREIGN KEY(contact_info_id) REFERENCES contact_info(id) ON DELETE CASCADE;
```

