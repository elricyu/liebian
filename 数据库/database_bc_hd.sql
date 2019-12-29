/*
Navicat MySQL Data Transfer

Source Server         : up_line
Source Server Version : 50727
Source Host           : 123.56.12.189:3306
Source Database       : database_bc_hd

Target Server Type    : MYSQL
Target Server Version : 50727
File Encoding         : 65001

Date: 2019-11-29 11:07:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for a_money
-- ----------------------------
DROP TABLE IF EXISTS `a_money`;
CREATE TABLE `a_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `centre_id` int(11) NOT NULL COMMENT '中心id',
  `total` varchar(255) NOT NULL DEFAULT '0' COMMENT '充值总金额',
  `remaining` varchar(255) NOT NULL DEFAULT '0' COMMENT '充值余额',
  `red_total` varchar(255) NOT NULL DEFAULT '0' COMMENT '红包总金额',
  `red_remaining` varchar(255) NOT NULL DEFAULT '0' COMMENT '红包余额',
  `discount` decimal(11,2) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '数据创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=278 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for a_money_order
-- ----------------------------
DROP TABLE IF EXISTS `a_money_order`;
CREATE TABLE `a_money_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hao` varchar(255) NOT NULL DEFAULT '' COMMENT '交易号',
  `money` varchar(255) NOT NULL DEFAULT '' COMMENT '支付金额',
  `centre_id` int(11) NOT NULL COMMENT '缴费中心id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '缴费时间',
  `status` varchar(255) NOT NULL DEFAULT '' COMMENT '支付状态',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '支付人openid',
  `czzt` int(1) NOT NULL COMMENT '充值状态，是获取用户信息的充值还是红包充值,1是信息充值，2是红包充值',
  `transaction_id` varchar(255) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `bz` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for a_order
-- ----------------------------
DROP TABLE IF EXISTS `a_order`;
CREATE TABLE `a_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `consumption` decimal(11,2) NOT NULL COMMENT '消费积分',
  `order_num` varchar(255) DEFAULT NULL COMMENT '订单号',
  `centre_id` int(11) DEFAULT NULL COMMENT '中心id',
  `ruleid` varchar(11) DEFAULT NULL COMMENT '兑换规则表 ID',
  `gid` varchar(11) DEFAULT NULL COMMENT '商品id',
  `hd_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `open_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `is_rec` int(11) DEFAULT NULL COMMENT '是否已领取 1未领取 2已领取',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '订单创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for crm_goods
-- ----------------------------
DROP TABLE IF EXISTS `crm_goods`;
CREATE TABLE `crm_goods` (
  `s_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `l_id` int(11) NOT NULL COMMENT '类别id',
  `s_name` varchar(255) NOT NULL COMMENT '商品名',
  `price` varchar(255) NOT NULL COMMENT '价格',
  `k_shu` varchar(50) DEFAULT NULL COMMENT '课时数量',
  `centre_id` int(11) NOT NULL COMMENT '中心ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `create_name` varchar(255) DEFAULT NULL COMMENT '创建人',
  `save_time` varchar(255) DEFAULT NULL COMMENT '修改时间',
  `save_name` varchar(255) DEFAULT NULL COMMENT '修改人',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '安全删除，删除变成0',
  `source` varchar(255) DEFAULT NULL COMMENT '数据来源平台',
  `item_id` int(11) DEFAULT '1' COMMENT '合约项目ID  默认1 运动宝贝',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5525 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for crm_hd_bm
-- ----------------------------
DROP TABLE IF EXISTS `crm_hd_bm`;
CREATE TABLE `crm_hd_bm` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '安全状态 1正常 0删除',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `status` int(11) DEFAULT '1' COMMENT '安全状态 1正常 0删除',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `centre_id` int(11) DEFAULT NULL COMMENT '中心id',
  `hd_id` int(11) DEFAULT NULL COMMENT '活动id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for crm_hd_order
-- ----------------------------
DROP TABLE IF EXISTS `crm_hd_order`;
CREATE TABLE `crm_hd_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动订单表',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `status` int(11) DEFAULT '0' COMMENT '安全状态 0 未付款，1已付款，2已使用',
  `price` double(11,2) DEFAULT NULL COMMENT '付款金额',
  `s_id` int(11) DEFAULT NULL COMMENT '商品id',
  `order_num` varchar(255) DEFAULT NULL COMMENT '订单号',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `hd_id` int(11) DEFAULT NULL COMMENT '活动id',
  `centre_id` int(11) DEFAULT NULL COMMENT '中心id',
  `spend_code` varchar(255) DEFAULT NULL COMMENT '消费码',
  `openid` varchar(255) DEFAULT NULL COMMENT '用户唯一的openids',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for crm_huodong
-- ----------------------------
DROP TABLE IF EXISTS `crm_huodong`;
CREATE TABLE `crm_huodong` (
  `hd_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动id',
  `template_id` int(10) unsigned NOT NULL COMMENT '模板id',
  `hd_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1砍价活动  2拼团活动 3促单(线上) 4促单(线上+线下) ',
  `jc_name` varchar(255) NOT NULL DEFAULT '' COMMENT '基础-活动名称',
  `jc_describe` text CHARACTER SET utf8mb4 NOT NULL COMMENT '基础-活动描述',
  `hd_site` varchar(255) NOT NULL COMMENT '活动地址',
  `hd_start_time` varchar(255) DEFAULT NULL COMMENT '活动开始日期',
  `hd_end_time` varchar(255) DEFAULT NULL COMMENT '活动结束日期',
  `bm_start_time` varchar(255) DEFAULT NULL COMMENT '报名开始日期',
  `bm_end_time` varchar(255) NOT NULL COMMENT '报名结束日期',
  `xiaohao` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消耗课时',
  `s_id` int(11) unsigned NOT NULL COMMENT '课时包id',
  `s_price` varchar(255) NOT NULL COMMENT '课时价格',
  `is_free` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1免费赠送  2购买',
  `centre_id` int(11) unsigned NOT NULL COMMENT '中心id',
  `c_phone` varchar(255) NOT NULL COMMENT '联系电话',
  `c_site` varchar(255) NOT NULL COMMENT '中心地址',
  `title` varchar(255) DEFAULT '' COMMENT '分享-活动标题',
  `fx_describe` varchar(255) DEFAULT NULL COMMENT '分享-活动描述',
  `img` varchar(255) CHARACTER SET utf8mb4 DEFAULT 'http://api.gymbaby.org/Public/activi.png' COMMENT '分享图片地址',
  `red_type` tinyint(2) DEFAULT '3' COMMENT '红包类型  1随机金额 2固定金额 3无红包',
  `red_single` int(11) DEFAULT '0' COMMENT '单一红包金额  固定类型使用',
  `red_total` decimal(11,2) DEFAULT '0.00' COMMENT '红包总金额',
  `red_num` int(11) DEFAULT '0' COMMENT '红包个数',
  `red_yfnum` int(11) DEFAULT '0' COMMENT '已发红包个数',
  `red_yftotal` int(11) unsigned DEFAULT '0' COMMENT '已发红包金额',
  `red_rand` longtext CHARACTER SET utf8mb4 COMMENT '随机红包的金额',
  `red_balance` decimal(10,2) DEFAULT '0.00' COMMENT '红包金额',
  `discount` decimal(11,2) DEFAULT NULL COMMENT '会员折扣 0-9.99  0代表不打折',
  `integral_type1` varchar(225) DEFAULT '' COMMENT '分享每增加一次点击可为分享者增加',
  `integral_type2` varchar(225) DEFAULT '' COMMENT '每增加一个到访(试听)客增加',
  `integral_type3` varchar(225) DEFAULT '' COMMENT '签订合同增加',
  `integral_type4` varchar(225) DEFAULT '' COMMENT '每拼团成功一次增加',
  `eimg` varchar(255) CHARACTER SET utf8mb4 DEFAULT './fenxiangt.png' COMMENT '二维码套图',
  `hd_qrcode` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '活动初始访问二维码',
  `is_kai` int(11) DEFAULT '1' COMMENT '开课变为0',
  `yuy_id` longtext COMMENT '预约会员id',
  `qian_id` longtext COMMENT '签到会员id',
  `fb_type` tinyint(11) NOT NULL DEFAULT '1' COMMENT '状态  1未发布  2发布  3已禁用',
  `share_num` int(11) DEFAULT '0' COMMENT '分享总次数',
  `guize` varchar(255) DEFAULT NULL COMMENT '活动规则',
  `create_user` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建人id',
  `create_time` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT '创建时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '安全删除  1正常  2删除',
  `linkhref` varchar(255) DEFAULT '' COMMENT '了解更多',
  PRIMARY KEY (`hd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=235 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for crm_kjilu
-- ----------------------------
DROP TABLE IF EXISTS `crm_kjilu`;
CREATE TABLE `crm_kjilu` (
  `jl_id` int(11) NOT NULL AUTO_INCREMENT,
  `centre_id` int(11) DEFAULT NULL COMMENT '中心id',
  `user_id` varchar(255) NOT NULL COMMENT '会员ID',
  `card_num` varchar(255) DEFAULT NULL,
  `s_keshi` varchar(50) DEFAULT '' COMMENT '购买课时包课时',
  `y_keshi` varchar(50) DEFAULT NULL COMMENT '剩余课时',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '安全删除，删除变成0 3=合约完成 1=正常  9=退款',
  `zeng_ke` varchar(255) DEFAULT NULL COMMENT '赠送课时',
  `shishou` varchar(255) NOT NULL COMMENT '实收金额',
  `now_shishou` varchar(255) DEFAULT '' COMMENT '当前剩余实收合约价格',
  `avg_price` varchar(255) DEFAULT '' COMMENT '平均课价',
  `hetong` varchar(255) DEFAULT NULL COMMENT '合同编号',
  `s_id` int(11) NOT NULL,
  `receivable` varchar(255) NOT NULL COMMENT '应收金额',
  `youhui_name` varchar(255) DEFAULT NULL COMMENT '优惠卡名称',
  `youhui_money` varchar(255) DEFAULT NULL COMMENT '优惠卡金额',
  `shoudong_money` varchar(255) DEFAULT NULL COMMENT '人工优惠金额',
  `receipt_type` varchar(255) DEFAULT NULL COMMENT '收款方式',
  `dindan` varchar(255) NOT NULL DEFAULT '' COMMENT '收据编号',
  `guwen` int(11) DEFAULT NULL COMMENT '顾问ID',
  `e_time` varchar(50) NOT NULL COMMENT '协议截止日期',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `create_time` varchar(50) DEFAULT NULL COMMENT '合约开始日期',
  `create_name` varchar(255) DEFAULT NULL COMMENT '创建人',
  `save_time` varchar(255) DEFAULT NULL COMMENT '修改时间',
  `save_name` varchar(255) DEFAULT NULL COMMENT '修改人',
  `source` varchar(255) DEFAULT NULL COMMENT '数据来源平台',
  `shou_s` tinyint(2) NOT NULL DEFAULT '0' COMMENT '会计是否收款',
  `bz` varchar(255) DEFAULT NULL COMMENT '备注',
  `bao_name` varchar(255) DEFAULT NULL,
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT 'flexo表的合约类型xuhao',
  `ceo_status` int(11) DEFAULT '1' COMMENT ' 总裁报表统计状态 1正常 2需重新统计  ',
  `gift` varchar(255) DEFAULT NULL,
  `pay_time` varchar(255) DEFAULT NULL,
  `item_id` int(11) DEFAULT '1' COMMENT '合约项目ID  默认1 运动宝贝',
  `hy_type` int(11) DEFAULT '1' COMMENT '1.正常合约 2.赠送合约',
  `p_id` int(11) DEFAULT '0' COMMENT '赠送合约ID',
  `hy_price` double(11,2) DEFAULT '0.00' COMMENT '合约总金额 用于扣课使用',
  `cd_ren` varchar(255) DEFAULT NULL COMMENT '菜单人id',
  PRIMARY KEY (`jl_id`),
  KEY `查询索引` (`status`,`card_num`) USING BTREE,
  KEY `查询索引2` (`user_id`) USING BTREE,
  KEY `index` (`item_id`) USING BTREE,
  KEY `index2` (`card_num`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=24962 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for crm_rule
-- ----------------------------
DROP TABLE IF EXISTS `crm_rule`;
CREATE TABLE `crm_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hd_id` int(11) DEFAULT '0' COMMENT '对应活动ID',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `centre_id` int(11) NOT NULL COMMENT '中心id',
  `is_use` int(11) NOT NULL DEFAULT '1' COMMENT '是否启用规则 1启用 2禁用',
  `rules` int(11) NOT NULL DEFAULT '0' COMMENT '规则消耗积分',
  `notes` varchar(255) DEFAULT '' COMMENT '介绍备注',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '安全删除 1正常 0删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for crm_shoucang
-- ----------------------------
DROP TABLE IF EXISTS `crm_shoucang`;
CREATE TABLE `crm_shoucang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏表',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `template_id` int(11) unsigned NOT NULL COMMENT '模板ID',
  `sc_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '收藏状态  1收藏 2取消',
  `create_time` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for crm_template
-- ----------------------------
DROP TABLE IF EXISTS `crm_template`;
CREATE TABLE `crm_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模板表',
  `img` text CHARACTER SET utf8mb4 NOT NULL COMMENT '模板图片',
  `text1` text CHARACTER SET utf8mb4 COMMENT '上边文本内容',
  `left1` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '上边左距离',
  `top1` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '上边 上距离',
  `underline1` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '下划线',
  `ziti1` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '上边字体',
  `zihao1` varchar(255) DEFAULT NULL COMMENT '上边文本字号',
  `jianju1` varchar(255) DEFAULT NULL COMMENT '上边文本间距',
  `color1` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '上边文本颜色',
  `yangshi1` varchar(255) DEFAULT NULL COMMENT '上边文本样式',
  `text2` text COMMENT '中间文本内容',
  `left2` varchar(255) DEFAULT NULL COMMENT '中间文本左距离',
  `top2` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '中间文本上（距离）',
  `underline2` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '下划线',
  `ziti2` varchar(255) DEFAULT NULL COMMENT '中间文本字体',
  `zihao2` varchar(255) DEFAULT NULL,
  `jianju2` varchar(255) DEFAULT NULL COMMENT '中间文本间距',
  `color2` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '中间文本颜色',
  `yangshi2` varchar(255) DEFAULT NULL COMMENT '中间文本样式',
  `text3` varchar(255) DEFAULT NULL COMMENT '下边文本内容',
  `left3` varchar(255) DEFAULT NULL COMMENT '下边文本 左距离',
  `top3` varchar(255) DEFAULT NULL COMMENT '下边文本 上距离',
  `underline3` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '下划线',
  `ziti3` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '下边文本 字体',
  `zihao3` varchar(255) DEFAULT NULL COMMENT '下边文本字号',
  `jianju3` varchar(255) DEFAULT NULL COMMENT '下边文本间距',
  `color3` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '下边文本颜色',
  `yangshi3` varchar(255) DEFAULT NULL COMMENT '下边文本样式',
  `type` tinyint(2) unsigned DEFAULT NULL COMMENT '类型  0创建  1主题活动 2节日活动 ',
  `baocun` tinyint(2) DEFAULT '1' COMMENT '1未保存  2已保存',
  `centre_id` int(11) NOT NULL COMMENT '中心id',
  `create_user` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `create_time` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '安全状态  1正常  2删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=476 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hd_access_record
-- ----------------------------
DROP TABLE IF EXISTS `hd_access_record`;
CREATE TABLE `hd_access_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '访问详情',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户id',
  `parentopenid` varchar(255) NOT NULL DEFAULT '' COMMENT '分享给这个人的openid',
  `cz_name` varchar(255) NOT NULL DEFAULT '' COMMENT '操作地方名字',
  `cz_time` varchar(255) NOT NULL DEFAULT '' COMMENT '操作时间：格式：10：10',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `hd_id` int(11) NOT NULL COMMENT '活动id',
  `centre_id` int(11) NOT NULL COMMENT '中心id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22958 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hd_red_record
-- ----------------------------
DROP TABLE IF EXISTS `hd_red_record`;
CREATE TABLE `hd_red_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '红包推送记录表',
  `hd_id` int(11) NOT NULL COMMENT '活动id',
  `openid` varchar(255) NOT NULL COMMENT '微信用户openid',
  `money` varchar(255) NOT NULL COMMENT '红包金额',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '红包类型 1随机金额 2固定金额',
  `centre_id` int(11) NOT NULL COMMENT '中心id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `mch_billno` varchar(255) NOT NULL COMMENT '商户订单号',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '红包领取状态 1是已发送待领取，2是发送中，3是退款中，4是发放失败，5是已领取，6是已退款',
  `send_listid` varchar(255) NOT NULL DEFAULT '' COMMENT '红包单号',
  `zt` int(11) NOT NULL DEFAULT '1' COMMENT '1为正常状态，2为已退款给中心',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=175 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hd_user
-- ----------------------------
DROP TABLE IF EXISTS `hd_user`;
CREATE TABLE `hd_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_id` varchar(255) NOT NULL COMMENT '用户openID 唯一',
  `vip_card` varchar(255) NOT NULL DEFAULT '' COMMENT 'VIP卡号',
  `user_name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `head_image` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `real_name` varchar(255) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号码',
  `birthday` varchar(255) NOT NULL DEFAULT '' COMMENT '出生日期',
  `sheng` varchar(255) NOT NULL DEFAULT '' COMMENT '所在省',
  `shi` varchar(255) NOT NULL DEFAULT '' COMMENT '所在市',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址/收货地址',
  `is_first` int(11) NOT NULL DEFAULT '1' COMMENT '是否首次分享 1首次分享 2非首次,已分享，3是已发出红包',
  `is_active` int(11) NOT NULL DEFAULT '1' COMMENT '是否激活 1未激活 2已激活',
  `zjf` int(11) NOT NULL DEFAULT '0' COMMENT '总积分',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分余额',
  `use_integral` int(11) NOT NULL DEFAULT '0' COMMENT '已用积分',
  `hd_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `centre_id` int(11) NOT NULL COMMENT '中心id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_fa` int(11) NOT NULL DEFAULT '0' COMMENT '是否发过红包了，1为发过了',
  `effect` int(11) DEFAULT '0' COMMENT '影响力指数',
  `influence` int(11) NOT NULL DEFAULT '0' COMMENT '影响力',
  `is_qianke` int(11) NOT NULL DEFAULT '1' COMMENT '是否报名 1临时用户  2潜客',
  `final_visit` varchar(255) DEFAULT '' COMMENT '最后访问地址 XX省,XX市,经纬度',
  `parent_openid` varchar(255) NOT NULL DEFAULT '' COMMENT '父类分享人ID',
  `share_num` int(11) NOT NULL DEFAULT '0' COMMENT '分享次数',
  `is_baoming` int(11) DEFAULT '1' COMMENT '是否为报名方式转为潜客 1不是 2是',
  PRIMARY KEY (`id`),
  KEY `1` (`open_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3872 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hd_visit_log
-- ----------------------------
DROP TABLE IF EXISTS `hd_visit_log`;
CREATE TABLE `hd_visit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '微信活动访问记录日志表主键ID',
  `open_id` varchar(255) NOT NULL DEFAULT '' COMMENT '用户唯一标识',
  `hd_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `centre_id` int(11) NOT NULL DEFAULT '0' COMMENT '中心ID',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '市',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT '经纬度',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '安全删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '日志创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8661 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for region
-- ----------------------------
DROP TABLE IF EXISTS `region`;
CREATE TABLE `region` (
  `REGION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `REGION_CODE` varchar(100) NOT NULL,
  `REGION_NAME` varchar(100) NOT NULL,
  `PARENT_ID` double NOT NULL,
  `REGION_LEVEL` double NOT NULL,
  `REGION_ORDER` double NOT NULL,
  `REGION_NAME_EN` varchar(100) NOT NULL,
  `REGION_SHORTNAME_EN` varchar(10) NOT NULL,
  PRIMARY KEY (`REGION_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5007 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for saas_login
-- ----------------------------
DROP TABLE IF EXISTS `saas_login`;
CREATE TABLE `saas_login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `centre_id` int(11) NOT NULL DEFAULT '0' COMMENT '中心ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '登录人id',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户姓名',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号码',
  `role` varchar(255) NOT NULL DEFAULT '' COMMENT '登录人角色',
  `gangwei` varchar(255) NOT NULL DEFAULT '' COMMENT '登录人岗位',
  `ip` varchar(255) DEFAULT '',
  `token` varchar(255) NOT NULL DEFAULT '' COMMENT '登录验证token',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1登录成功,0登录失败，2是已阅读向导',
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登录时间',
  `expire_time` varchar(255) DEFAULT '1500000000' COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1148 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sw_auth
-- ----------------------------
DROP TABLE IF EXISTS `sw_auth`;
CREATE TABLE `sw_auth` (
  `auth_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限表',
  `auth_name` varchar(20) NOT NULL COMMENT '权限名称',
  `auth_pid` smallint(6) unsigned NOT NULL COMMENT '父级权限id',
  `auth_c` varchar(255) NOT NULL COMMENT '权限控制器名称',
  `auth_a` varchar(255) NOT NULL COMMENT '权限操作方法名称',
  `auth_path` varchar(255) NOT NULL COMMENT '全路径：用户权限信息排序用 1如果是顶级权限，全路径等于本记录主键值',
  `auth_level` tinyint(4) DEFAULT '0' COMMENT '权限级别：0顶级权限 1次级权限 2次次级别权限',
  `url` varchar(100) DEFAULT '' COMMENT '菜单权限的URL访问地址',
  PRIMARY KEY (`auth_id`)
) ENGINE=MyISAM AUTO_INCREMENT=866 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for sw_manager
-- ----------------------------
DROP TABLE IF EXISTS `sw_manager`;
CREATE TABLE `sw_manager` (
  `mg_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '所属省份ID',
  `mg_u_name` varchar(50) DEFAULT NULL COMMENT '登录人姓名',
  `mg_name` varchar(32) NOT NULL COMMENT '用户名',
  `mg_pwd` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `mg_role_id` tinyint(3) NOT NULL DEFAULT '0',
  `centre_id` int(11) NOT NULL DEFAULT '0',
  `sheng_ids` varchar(500) DEFAULT NULL COMMENT '所需省份ID',
  `quan` varchar(255) NOT NULL DEFAULT '1,2,3,4,5,6' COMMENT '中心资料权限，1是金额全部正常显示 2是中心基本信息，3,是投资人信息，4，加盟信息，5是系统信息，5是中心档案',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '安全删除，0为已删除',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `login_time` varchar(255) NOT NULL DEFAULT '' COMMENT '扫码登录,登录时间',
  `login_status` int(11) NOT NULL DEFAULT '1' COMMENT '扫码登录，登录变成2',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '招商短信专用',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '招商短信专用',
  PRIMARY KEY (`mg_id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sw_role
-- ----------------------------
DROP TABLE IF EXISTS `sw_role`;
CREATE TABLE `sw_role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL COMMENT '角色名称 ',
  `role_auth_ids` text NOT NULL COMMENT '权限ids :1,2,3  4,5,6 关联权限的逐渐值用逗号链接的信息（如果有上级权限，也把上级权限id关联起来） ',
  `role_auth_ac` text NOT NULL COMMENT '模块-操作  关联权限的控制器，方法链接的信息 ',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wx_centre
-- ----------------------------
DROP TABLE IF EXISTS `wx_centre`;
CREATE TABLE `wx_centre` (
  `centre_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '中心基本信息表',
  `sequence` int(4) unsigned zerofill DEFAULT NULL COMMENT '中心序号',
  `centre` varchar(255) NOT NULL COMMENT '中心名称',
  `site` varchar(255) DEFAULT NULL COMMENT '中心地址',
  `c_phone` varchar(255) DEFAULT NULL COMMENT '中心电话',
  `l_name` varchar(255) DEFAULT NULL COMMENT '中心联系人',
  `l_phone` bigint(11) DEFAULT NULL COMMENT '常用联系人电话',
  `jieshao` varchar(255) DEFAULT 'GYMBABY 运动宝贝中心于2008年创立于北京。致力于全面开发婴幼儿的潜能为目标，为为中国0-6岁的宝宝提供科学的早期潜能开发以及成长指导早期教育课程和服务。运动宝贝透过适龄一致，双向互动式的欢乐课堂，帮助宝宝建立健康、自信、快乐、想象力丰富、善于表达的人生！' COMMENT '中心介绍',
  `sid` int(11) unsigned NOT NULL COMMENT '省份ID',
  `shi_id` int(11) DEFAULT '0' COMMENT '市ID',
  `qu_id` int(11) DEFAULT NULL COMMENT '区id',
  PRIMARY KEY (`centre_id`)
) ENGINE=MyISAM AUTO_INCREMENT=624 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wx_centre_z
-- ----------------------------
DROP TABLE IF EXISTS `wx_centre_z`;
CREATE TABLE `wx_centre_z` (
  `z_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '中心资料表',
  `centre_id` int(11) NOT NULL COMMENT '中心表',
  `yi_fei` varchar(255) DEFAULT NULL COMMENT '意向金',
  `jm_fei` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '加盟费',
  `b_fei` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '保证金',
  `jj_fei` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '教具费用',
  `gl_fei` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '应缴纳管理费/权益金金额（万）',
  `gl_time` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '管理费到期时间',
  `wz_fei` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '物资款',
  `kd_site` text CHARACTER SET gbk COMMENT '快递地址',
  `jyxz` tinyint(1) DEFAULT NULL COMMENT '1:独立经营 2:合作经营 3:公司经营 4:政府单位经营',
  `jyxz_n` tinyint(3) DEFAULT NULL COMMENT '合作经营（投资人数）',
  `xt_fei` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '系统使用费用',
  `hy_user` varchar(255) CHARACTER SET gbk DEFAULT '手机号码' COMMENT '会员管理系统账号',
  `hy_m` varchar(255) CHARACTER SET gbk DEFAULT 'gym123456' COMMENT '会员管理系统密码',
  `vi_user` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT 'VI应用管理系统账号',
  `vi_m` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT 'VI应用管理系统密码',
  `gw_user` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '官网新闻管理系统账号',
  `gw_m` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '官网新闻管理系统密码',
  `xt_bz1` text CHARACTER SET gbk COMMENT '特殊情况',
  `xt_bz2` text CHARACTER SET gbk COMMENT '系统使用情况',
  `z_bz1` text CHARACTER SET gbk COMMENT '备注1',
  `z_bz2` text CHARACTER SET gbk COMMENT '备注2',
  `z_bz3` text CHARACTER SET gbk COMMENT '备注3',
  `fp_tou` varchar(255) DEFAULT NULL COMMENT '发票抬头',
  `fp_shui` varchar(255) DEFAULT NULL COMMENT '税务登记证号',
  `fp_yin_name` varchar(255) DEFAULT NULL COMMENT '基本开户银行名称',
  `fp_yin_num` varchar(255) DEFAULT NULL COMMENT '基本开户账号',
  `fp_site` varchar(255) DEFAULT NULL COMMENT '注册场所地址',
  `fp_phone` varchar(255) DEFAULT NULL COMMENT '注册固定电话',
  `fp_ying_p` varchar(255) DEFAULT NULL COMMENT '营业执照复印件',
  `fp_shui_p` varchar(255) DEFAULT NULL COMMENT '税务登记复印件',
  `fp_ren_p` varchar(255) DEFAULT NULL COMMENT '一般纳税人资格认证复印件',
  `wu1` longtext COMMENT '一期物资发放记录',
  `wu2` longtext COMMENT '二期物资发放记录',
  `wu3` longtext COMMENT '三期物资发放记录',
  `wu4` longtext COMMENT '语言课培训物资',
  `jw_p` varchar(255) DEFAULT NULL COMMENT '教具费，电子报价单',
  `wz_p` varchar(255) DEFAULT NULL COMMENT '物资确认单',
  `dd_fei` varchar(255) DEFAULT NULL COMMENT '地垫费',
  `px_fei` varchar(255) DEFAULT NULL COMMENT '培训费',
  `ymy_fei` varchar(255) DEFAULT NULL COMMENT '亚麻油地板',
  `dd_p` text COMMENT '地垫面积确认单',
  `dd_w` text COMMENT '地垫完工确认单',
  `ymy_p` text COMMENT '亚麻油面积确认单',
  `ymy_w` text COMMENT '亚麻油完工确认单',
  `wz_j` text COMMENT '教辅物资发货单',
  `wz_w` text COMMENT '教辅物资收货单',
  `jj_j` text COMMENT '教具发货单',
  `jj_w` text COMMENT '教具收货单',
  `qt_fei` varchar(255) DEFAULT NULL COMMENT '其他收费项目',
  `stream_perc` float unsigned NOT NULL DEFAULT '0' COMMENT '流水收取权益金百分比:1%-100%',
  `stream_year` float NOT NULL DEFAULT '0' COMMENT '免流水年限',
  `stream_year_end` varchar(255) NOT NULL DEFAULT '' COMMENT '免流水年限截止日期',
  PRIMARY KEY (`z_id`)
) ENGINE=MyISAM AUTO_INCREMENT=573 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wx_guanxi
-- ----------------------------
DROP TABLE IF EXISTS `wx_guanxi`;
CREATE TABLE `wx_guanxi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '关系表',
  `user_id` varchar(255) DEFAULT NULL COMMENT 'user_id',
  `jz_name` varchar(255) DEFAULT NULL COMMENT '家长的姓名',
  `openid` varchar(255) DEFAULT NULL COMMENT 'openid',
  `guanxi` varchar(255) DEFAULT NULL COMMENT '家长关系',
  `centre_id` int(11) NOT NULL COMMENT '中心id',
  `phone` varchar(255) DEFAULT NULL,
  `pwd` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `source` varchar(255) DEFAULT NULL COMMENT '数据来源',
  `source_type` varchar(255) DEFAULT NULL COMMENT '数据来源类型：大众点评，宣传单',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0是潜客，1是会员',
  `create_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人',
  `gw_id` int(11) DEFAULT NULL COMMENT '顾问id',
  `unionid` varchar(255) DEFAULT '' COMMENT '用户微信唯一标识',
  `vip_openid` varchar(255) DEFAULT '',
  `push_status` int(11) DEFAULT '1' COMMENT '是否推送通知  1.未开启 2.开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=372 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wx_user
-- ----------------------------
DROP TABLE IF EXISTS `wx_user`;
CREATE TABLE `wx_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `jl_id` int(11) DEFAULT NULL COMMENT '缴费记录ID',
  `belong` varchar(255) DEFAULT NULL COMMENT '中心ID',
  `openid` varchar(255) DEFAULT NULL,
  `jz_name` varchar(255) DEFAULT NULL,
  `jz_phone` varchar(255) DEFAULT NULL,
  `baobao_name` varchar(255) DEFAULT NULL,
  `baobao_name2` varchar(20) DEFAULT NULL COMMENT '小名',
  `baobao_sex` varchar(255) DEFAULT NULL,
  `baobao_birthday` varchar(255) DEFAULT NULL COMMENT '宝宝生日',
  `jzsf` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `consignee_name` varchar(255) DEFAULT NULL,
  `consignee_phone` varchar(255) DEFAULT NULL,
  `consignee_site` varchar(255) DEFAULT NULL,
  `integral` int(11) DEFAULT '0' COMMENT '积分',
  `sign` int(11) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `yon` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户所属组:1=职员培训系统，2=会员管理系统',
  `name1` varchar(20) DEFAULT NULL COMMENT '父亲姓名',
  `name2` varchar(20) DEFAULT NULL COMMENT '母亲姓名',
  `name3` varchar(20) DEFAULT NULL COMMENT '爷爷姓名',
  `name4` varchar(20) DEFAULT NULL COMMENT '奶奶姓名',
  `name5` varchar(20) DEFAULT NULL COMMENT '姥爷姓名',
  `name6` varchar(20) DEFAULT NULL COMMENT '姥姥姓名',
  `phone1` varchar(11) DEFAULT NULL COMMENT '父亲电话',
  `phone2` varchar(11) DEFAULT NULL COMMENT '母亲电话',
  `phone3` varchar(11) DEFAULT NULL COMMENT '爷爷电话',
  `phone4` varchar(11) DEFAULT NULL COMMENT '奶奶电话',
  `phone5` varchar(11) DEFAULT NULL COMMENT '姥爷电话',
  `phone6` varchar(11) DEFAULT NULL COMMENT '姥姥电话',
  `openid1` varchar(255) DEFAULT NULL COMMENT '父亲openid',
  `openid2` varchar(255) DEFAULT NULL COMMENT 'openid',
  `openid3` varchar(255) DEFAULT NULL COMMENT 'openid',
  `openid4` varchar(255) DEFAULT NULL COMMENT 'openid',
  `openid5` varchar(255) DEFAULT NULL COMMENT 'openid',
  `openid6` varchar(255) DEFAULT NULL COMMENT 'openid',
  `site` varchar(255) DEFAULT NULL COMMENT '家庭地址',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '安全删除 1=存在 0=删除',
  `gd_id` text COMMENT '固定班，固定课程id',
  `vip` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '0为潜客，1为会员，2为VIP',
  `sc_id` int(11) NOT NULL DEFAULT '0' COMMENT '市场ID',
  `gw_id` int(11) NOT NULL DEFAULT '0' COMMENT '顾问ID',
  `gj_type` enum('','新客户','已预约试听','未预约试听','试听未跟进','已试听','已预约测评','已预约活动','未到访','已到访','已预约到店','持续跟进','新分配','预约未到访','重点关注','放弃','无效用户','新用户','预约当日','定金','鱼池','已签约','已跟进') NOT NULL DEFAULT '新客户' COMMENT '跟进状态',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `qiandao` longtext COMMENT '签到记录',
  `laiyuan` varchar(255) DEFAULT NULL COMMENT '会员来源',
  `beizhu` text COMMENT '备注',
  `hetong` varchar(255) DEFAULT NULL COMMENT '合约编号',
  `kahao` varchar(255) DEFAULT NULL COMMENT '会员卡号',
  `a` text,
  `b` text,
  `c` text,
  `d` text,
  `e` text,
  `cp_num` int(11) DEFAULT '0' COMMENT '测评次数',
  `email` varchar(255) DEFAULT '' COMMENT '家长电子邮件地址',
  `jl_ids` varchar(255) DEFAULT '' COMMENT '记录合约ID',
  `is_upload` int(11) DEFAULT '1' COMMENT '是否excel导入用户  1 不是 2.是',
  `tmp_status` int(11) DEFAULT '1' COMMENT '1未执行  2已执行',
  `usertype` varchar(255) DEFAULT '' COMMENT '用户分类',
  `hd_id` int(11) DEFAULT '0' COMMENT '通过活动增加潜客  活动ID',
  `last_gj_time` varchar(255) DEFAULT '' COMMENT '最后跟进时间',
  PRIMARY KEY (`user_id`),
  KEY `2` (`openid`) USING BTREE,
  KEY `3` (`kahao`) USING BTREE,
  KEY `1` (`belong`,`status`) USING BTREE,
  KEY `index` (`kahao`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=277837 DEFAULT CHARSET=utf8 COMMENT='会员信息';

-- ----------------------------
-- Table structure for xueyuan_baoming
-- ----------------------------
DROP TABLE IF EXISTS `xueyuan_baoming`;
CREATE TABLE `xueyuan_baoming` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '姓名',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `phone` varchar(255) NOT NULL,
  `centre_id` int(11) NOT NULL COMMENT '分中心id',
  `img` varchar(255) NOT NULL DEFAULT 'http://www.yundongbaobei.cn/Uploads/touxiang.png' COMMENT '默认头像',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '是否离职，离职变成0',
  `pad` varchar(50) NOT NULL DEFAULT 'a9e9a81003adc4f8ce4d15a027347ff5' COMMENT '密码',
  `nav_id` varchar(255) DEFAULT NULL COMMENT '权限ID,1是会员，2是排课，3是选课。5是活动，6是跟进，7是签到，8是耗课，9是合约，10是员工设置，11是商品设置，12是课程设置，27是进销存，26是教案，28是绘本，29是微营销',
  `erwm` varchar(255) DEFAULT NULL COMMENT '二维码图片地址',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6173 DEFAULT CHARSET=utf8 COMMENT='学员_报名';

-- ----------------------------
-- Table structure for yanzhengma
-- ----------------------------
DROP TABLE IF EXISTS `yanzhengma`;
CREATE TABLE `yanzhengma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) NOT NULL,
  `yzm` varchar(255) NOT NULL,
  `shijian` varchar(255) NOT NULL,
  `result` text COMMENT '发送结果集',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=585 DEFAULT CHARSET=utf8;
