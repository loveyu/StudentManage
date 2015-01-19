<?php
/**
 * User: loveyu
 * Date: 2015/1/12
 * Time: 16:22
 */

namespace UView;


use Core\Page;

class DataCreate extends Page{
	private $city_list = [
		"北京市" => [],
		"天津市" => [],
		"河北省" => [
			"石家庄市",
			"唐山市",
			"秦皇岛市",
			"邯郸市",
			"邢台市",
			"保定市",
			"张家口市",
			"承德市",
			"沧州市",
			"廊坊市",
			"衡水市"
		],
		"山西省" => [
			"太原市",
			"大同市",
			"阳泉市",
			"长治市",
			"晋城市",
			"朔州市",
			"晋中市",
			"运城市",
			"忻州市",
			"临汾市",
			"吕梁市"
		],
		"内蒙古自治区" => [
			"呼和浩特市",
			"包头市",
			"乌海市",
			"赤峰市",
			"通辽市",
			"鄂尔多斯市",
			"呼伦贝尔市",
			"巴彦淖尔市",
			"乌兰察布市",
			"兴安盟",
			"锡林郭勒盟",
			"阿拉善盟"
		],
		"辽宁省" => [
			"沈阳市",
			"大连市",
			"鞍山市",
			"抚顺市",
			"本溪市",
			"丹东市",
			"锦州市",
			"营口市",
			"阜新市",
			"辽阳市",
			"盘锦市",
			"铁岭市",
			"朝阳市",
			"葫芦岛市"
		],
		"吉林省" => [
			"长春市",
			"吉林市",
			"四平市",
			"辽源市",
			"通化市",
			"白山市",
			"松原市",
			"白城市",
			"延边朝鲜族自治州"
		],
		"黑龙江省" => [
			"哈尔滨市",
			"齐齐哈尔市",
			"鸡西市",
			"鹤岗市",
			"双鸭山市",
			"大庆市",
			"伊春市",
			"佳木斯市",
			"七台河市",
			"牡丹江市",
			"黑河市",
			"绥化市",
			"大兴安岭地区"
		],
		"上海市" => [],
		"江苏省" => [
			"南京市",
			"无锡市",
			"徐州市",
			"常州市",
			"苏州市",
			"南通市",
			"连云港市",
			"淮安市",
			"盐城市",
			"扬州市",
			"镇江市",
			"泰州市",
			"宿迁市"
		],
		"浙江省" => [
			"杭州市",
			"宁波市",
			"温州市",
			"嘉兴市",
			"湖州市",
			"绍兴市",
			"金华市",
			"衢州市",
			"舟山市",
			"台州市",
			"丽水市"
		],
		"安徽省" => [
			"合肥市",
			"芜湖市",
			"蚌埠市",
			"淮南市",
			"马鞍山市",
			"淮北市",
			"铜陵市",
			"安庆市",
			"黄山市",
			"滁州市",
			"阜阳市",
			"宿州市",
			"六安市",
			"亳州市",
			"池州市",
			"宣城市"
		],
		"福建省" => [
			"福州市",
			"厦门市",
			"莆田市",
			"三明市",
			"泉州市",
			"漳州市",
			"南平市",
			"龙岩市",
			"宁德市"
		],
		"江西省" => [
			"南昌市",
			"景德镇市",
			"萍乡市",
			"九江市",
			"新余市",
			"鹰潭市",
			"赣州市",
			"吉安市",
			"宜春市",
			"抚州市",
			"上饶市"
		],
		"山东省" => [
			"济南市",
			"青岛市",
			"淄博市",
			"枣庄市",
			"东营市",
			"烟台市",
			"潍坊市",
			"济宁市",
			"泰安市",
			"威海市",
			"日照市",
			"莱芜市",
			"临沂市",
			"德州市",
			"聊城市",
			"滨州市",
			"菏泽市"
		],
		"河南省" => [
			"郑州市",
			"开封市",
			"洛阳市",
			"平顶山市",
			"安阳市",
			"鹤壁市",
			"新乡市",
			"焦作市",
			"濮阳市",
			"许昌市",
			"漯河市",
			"三门峡市",
			"南阳市",
			"商丘市",
			"信阳市",
			"周口市",
			"驻马店市",
			"省直辖县级行政区划"
		],
		"湖北省" => [
			"武汉市",
			"黄石市",
			"十堰市",
			"宜昌市",
			"襄阳市",
			"鄂州市",
			"荆门市",
			"孝感市",
			"荆州市",
			"黄冈市",
			"咸宁市",
			"随州市",
			"恩施土家族苗族自治州",
			"省直辖县级行政区划"
		],
		"湖南省" => [
			"长沙市",
			"株洲市",
			"湘潭市",
			"衡阳市",
			"邵阳市",
			"岳阳市",
			"常德市",
			"张家界市",
			"益阳市",
			"郴州市",
			"永州市",
			"怀化市",
			"娄底市",
			"湘西土家族苗族自治州"
		],
		"广东省" => [
			"广州市",
			"韶关市",
			"深圳市",
			"珠海市",
			"汕头市",
			"佛山市",
			"江门市",
			"湛江市",
			"茂名市",
			"肇庆市",
			"惠州市",
			"梅州市",
			"汕尾市",
			"河源市",
			"阳江市",
			"清远市",
			"东莞市",
			"中山市",
			"潮州市",
			"揭阳市",
			"云浮市"
		],
		"广西壮族自治区" => [
			"南宁市",
			"柳州市",
			"桂林市",
			"梧州市",
			"北海市",
			"防城港市",
			"钦州市",
			"贵港市",
			"玉林市",
			"百色市",
			"贺州市",
			"河池市",
			"来宾市",
			"崇左市"
		],
		"海南省" => [
			"海口市",
			"三亚市",
			"省直辖县级行政区划"
		],
		"重庆市" => [],
		"四川省" => [
			"成都市",
			"自贡市",
			"攀枝花市",
			"泸州市",
			"德阳市",
			"绵阳市",
			"广元市",
			"遂宁市",
			"内江市",
			"乐山市",
			"南充市",
			"眉山市",
			"宜宾市",
			"广安市",
			"达州市",
			"雅安市",
			"巴中市",
			"资阳市",
			"阿坝藏族羌族自治州",
			"甘孜藏族自治州",
			"凉山彝族自治州"
		],
		"贵州省" => [
			"贵阳市",
			"六盘水市",
			"遵义市",
			"安顺市",
			"毕节市",
			"铜仁市",
			"黔西南布依族苗族自治州",
			"黔东南苗族侗族自治州",
			"黔南布依族苗族自治州"
		],
		"云南省" => [
			"昆明市",
			"曲靖市",
			"玉溪市",
			"保山市",
			"昭通市",
			"丽江市",
			"普洱市",
			"临沧市",
			"楚雄彝族自治州",
			"红河哈尼族彝族自治州",
			"文山壮族苗族自治州",
			"西双版纳傣族自治州",
			"大理白族自治州",
			"德宏傣族景颇族自治州",
			"怒江傈僳族自治州",
			"迪庆藏族自治州"
		],
		"西藏自治区" => [
			"拉萨市",
			"昌都地区",
			"山南地区",
			"日喀则地区",
			"那曲地区",
			"阿里地区",
			"林芝地区"
		],
		"陕西省" => [
			"西安市",
			"铜川市",
			"宝鸡市",
			"咸阳市",
			"渭南市",
			"延安市",
			"汉中市",
			"榆林市",
			"安康市",
			"商洛市"
		],
		"甘肃省" => [
			"兰州市",
			"嘉峪关市",
			"金昌市",
			"白银市",
			"天水市",
			"武威市",
			"张掖市",
			"平凉市",
			"酒泉市",
			"庆阳市",
			"定西市",
			"陇南市",
			"临夏回族自治州",
			"甘南藏族自治州"
		],
		"青海省" => [
			"西宁市",
			"海东地区",
			"海北藏族自治州",
			"黄南藏族自治州",
			"海南藏族自治州",
			"果洛藏族自治州",
			"玉树藏族自治州",
			"海西蒙古族藏族自治州"
		],
		"宁夏回族自治区" => [
			"银川市",
			"石嘴山市",
			"吴忠市",
			"固原市",
			"中卫市"
		],
		"新疆维吾尔自治区" => [
			"乌鲁木齐市",
			"克拉玛依市",
			"吐鲁番地区",
			"哈密地区",
			"昌吉回族自治州",
			"博尔塔拉蒙古自治州",
			"巴音郭楞蒙古自治州",
			"阿克苏地区",
			"克孜勒苏柯尔克孜自治州",
			"喀什地区",
			"和田地区",
			"伊犁哈萨克自治州",
			"塔城地区",
			"阿勒泰地区",
			"自治区直辖县级行政区划"
		],
		"台湾" => [
			"台北市",
			"新北市",
			"台中市",
			"台南市",
			"高雄市",
			"基隆市",
			"新竹市",
			"嘉义市",
			"桃园县",
			"新竹县",
			"苗栗县",
			"彰化县",
			"南投县",
			"云林县",
			"嘉义县",
			"屏东县",
			"宜兰县",
			"花莲县",
			"宜兰县",
			"澎湖县"
		],
		"香港" => [],
		"澳门" => []
	];
	public $name_list = [
		'赵',
		'钱',
		'孙',
		'李',
		'周',
		'吴',
		'郑',
		'王',
		'冯',
		'陈',
		'褚',
		'卫',
		'蒋',
		'沈',
		'韩',
		'杨',
		'何',
		'吕',
		'施',
		'张',
		'孔',
		'曹',
		'严',
		'华',
		'金',
		'魏',
		'陶',
		'姜',
		'戚',
		'谢',
		'邹',
		'喻',
		'云',
		'苏',
		'潘',
		'葛',
		'奚',
		'范',
		'彭',
		'郎',
		'鲁',
		'韦',
		'昌',
		'马',
		'苗',
		'凤',
		'花',
		'方',
		'酆',
		'鲍',
		'史',
		'唐',
		'费',
		'廉',
		'岑',
		'薛',
		'雷',
		'贺',
		'倪',
		'汤',
		'滕',
		'殷',
		'罗',
		'毕',
		'元',
		'卜',
		'顾',
		'孟',
		'平',
		'黄',
		'和',
		'穆',
		'萧',
		'尹',
		'姚',
		'邵',
		'湛',
		'汪',
		'祁',
		'毛',
		'明',
		'臧',
		'计',
		'伏',
		'成',
		'戴',
		'谈',
		'宋',
		'茅',
		'庞',
		'熊',
		'纪',
		'舒',
		'屈',
		'项',
		'祝',
		'蓝',
		'闵',
		'席',
		'季',
		'麻',
		'强',
		'贾',
		'路',
		'娄',
		'危',
		'江',
		'童',
		'颜',
		'郭',
		'梅',
		'盛',
		'邱',
		'骆',
		'高',
		'夏',
		'蔡',
		'田',
		'樊',
		'胡',
		'凌',
		'霍',
		'虞',
		'万',
		'支',
		'柯',
		'咎',
		'管',
		'裘',
		'缪',
		'干',
		'解',
		'应',
		'宗',
		'宣',
		'丁',
		'贲',
		'邓',
		'郁',
		'单',
		'杭',
		'洪',
		'包',
		'诸',
		'钮',
		'龚',
		'程',
		'嵇',
		'邢',
		'滑',
		'裴',
		'陆',
		'荣',
		'翁',
		'荀',
		'羊',
		'於',
		'惠',
		'甄',
		'麴',
		'储',
		'汲',
		'邴',
		'糜',
		'松',
		'井',
		'段',
		'富',
		'巫',
		'乌',
		'焦',
		'巴',
		'弓',
		'牧',
		'隗',
		'山',
		'蓬',
		'全',
		'郗',
		'班',
		'仰',
		'秋',
		'仲',
		'伊',
		'宫',
		'宁',
		'仇',
		'栾',
		'暴',
		'甘',
		'钭',
		'厉',
		'刘',
		'景',
		'詹',
		'束',
		'龙',
		'叶',
		'幸',
		'司',
		'韶',
		'郜',
		'黎',
		'蓟',
		'薄',
		'印',
		'宿',
		'白',
		'鄂',
		'索',
		'咸',
		'籍',
		'赖',
		'卓',
		'蔺',
		'屠',
		'胥',
		'能',
		'苍',
		'双',
		'闻',
		'莘',
		'党',
		'翟',
		'姬',
		'申',
		'扶',
		'堵',
		'冉',
		'宰',
		'郦',
		'雍',
		'郤',
		'璩',
		'桑',
		'桂',
		'濮',
		'牛',
		'寿',
		'通',
		'郏',
		'浦',
		'尚',
		'农',
		'温',
		'别',
		'庄',
		'晏',
		'柴',
		'瞿',
		'阎',
		'充',
		'慕',
		'连',
		'茹',
		'习',
	];
	public $name_list2 = [
		'寿',
		'弄',
		'麦',
		'形',
		'进',
		'戒',
		'吞',
		'远',
		'违',
		'运',
		'扶',
		'抚',
		'坛',
		'技',
		'坏',
		'扰',
		'拒',
		'找',
		'批',
		'扯',
		'址',
		'走',
		'抄',
		'坝',
		'贡',
		'攻',
		'赤',
		'折',
		'抓',
		'扮',
		'抢',
		'孝',
		'均',
		'抛',
		'投',
		'坟',
		'抗',
		'坑',
		'坊',
		'抖',
		'护',
		'壳',
		'志',
		'扭',
		'块',
		'声',
		'把',
		'报',
		'却',
		'劫',
		'芽',
		'花',
		'芹',
		'芬',
		'苍',
		'芳',
		'严',
		'芦',
		'劳',
		'克',
		'苏',
		'杆',
		'杠',
		'杜',
		'材',
		'村',
		'杏',
		'极',
		'李',
		'杨',
		'求',
		'更',
		'束',
		'豆',
		'两',
		'丽',
		'医',
		'辰',
		'励',
		'否',
		'还',
		'歼',
		'来',
		'连',
		'步',
		'坚',
		'旱',
		'盯',
		'呈',
		'时',
		'吴',
		'助',
		'县',
		'里',
		'呆',
		'园',
		'旷',
		'围',
		'呀',
		'吨',
		'足',
		'邮',
		'男',
		'困',
		'吵',
		'串',
		'员',
		'听',
		'吩',
		'吹',
		'呜',
		'吧',
		'吼',
		'别',
		'岗',
		'帐',
		'财',
		'针',
		'钉',
		'告',
		'我',
		'乱',
		'利',
		'秃',
		'秀',
		'私',
		'每',
		'兵',
		'估',
		'体',
		'何',
		'但',
		'伸',
		'作',
		'伯',
		'伶',
		'佣',
		'低',
		'你',
		'住',
		'位',
		'伴',
		'身',
		'皂',
		'佛',
		'近',
		'彻',
		'役',
		'返',
		'余',
		'希',
		'坐',
		'谷',
		'妥',
		'含',
		'邻',
		'岔',
		'肝',
		'肚',
		'肠',
		'龟',
		'免',
		'狂',
		'犹',
		'角',
		'删',
		'条',
		'卵',
		'岛',
		'迎',
		'饭',
		'饮',
		'系',
		'言',
		'冻',
		'状',
		'亩',
		'况',
		'床',
		'库',
		'疗',
		'应',
		'冷',
		'这',
		'序',
		'辛',
		'弃',
		'冶',
		'忘',
		'闲',
		'间',
		'闷',
		'判',
		'灶',
		'灿',
		'弟',
		'汪',
		'沙',
		'汽',
		'沃',
		'泛',
		'沟',
		'没',
		'沈',
		'沉',
		'怀',
		'忧',
		'快',
		'完',
		'宋',
		'宏',
		'牢',
		'究',
		'穷',
		'灾',
		'良',
		'证',
		'启',
		'评',
		'补',
		'初',
		'社',
		'识',
		'诉',
		'诊',
		'词',
		'译',
		'君',
		'灵',
		'即',
		'层',
		'尿',
		'尾',
		'迟',
		'局',
		'改',
		'张',
		'忌',
		'际',
		'陆',
		'阿',
		'陈',
		'阻',
		'附',
		'妙',
		'妖',
		'妨',
		'努',
		'忍',
		'劲',
		'鸡',
		'驱',
		'纯',
		'纱',
		'纳',
		'纲',
		'驳',
		'纵',
		'纷',
		'纸',
		'纹',
		'纺',
		'驴',
		'纽',
		'玉',
		'刊',
		'示',
		'末',
		'未',
		'击',
		'打',
		'巧',
		'正',
		'扑',
		'扒',
		'功',
		'扔',
		'去',
		'甘',
		'世',
		'古',
		'节',
		'本',
		'术',
		'可',
		'丙',
		'左',
		'厉',
		'卡',
		'北',
		'占',
		'业',
		'旧',
		'帅',
		'归',
		'且',
		'旦',
		'目',
		'叶',
		'甲',
		'申',
		'叮',
		'电',
		'号',
		'田',
		'由',
		'史',
		'只',
		'央',
		'兄',
		'叼',
		'叫',
		'另',
		'叨',
		'叹',
		'四',
		'生',
		'失',
		'禾',
		'丘',
		'付',
		'仗',
		'代',
		'仙',
		'们',
		'仪',
		'白',
		'仔',
		'他',
		'斥',
		'瓜',
		'乎',
		'丛',
		'令',
		'用',
		'甩',
		'印',
		'乐',
		'句',
		'匆',
		'册',
		'犯',
		'外',
		'处',
		'冬',
		'鸟',
		'务',
		'包',
		'饥',
		'主',
		'市',
		'立',
		'闪',
		'兰',
		'半',
		'汁',
		'汇',
		'头',
		'汉',
		'宁',
		'穴',
		'它',
		'讨',
		'写',
		'让',
		'礼',
		'训',
		'必',
		'议',
		'讯',
		'记',
		'永',
		'司',
		'尼',
		'民',
		'出',
		'辽',
		'奶',
		'奴',
		'加',
		'召',
		'皮',
		'边',
		'发',
		'孕',
		'圣',
		'对',
		'台',
		'矛',
		'纠',
		'母',
		'幼',
		'丝',
		'右',
		'石',
		'布',
		'龙',
		'平',
		'灭',
		'轧',
		'东',
		'丰',
		'王',
		'井',
		'开',
		'夫',
		'天',
		'无',
		'元',
		'专',
		'云',
		'扎',
		'艺',
		'木',
		'五',
		'支',
		'止',
		'少',
		'日',
		'中',
		'冈',
		'贝',
		'内',
		'水',
		'见',
		'午',
		'牛',
		'手',
		'毛',
		'气',
		'升',
		'长',
		'仁',
		'什',
		'片',
		'仆',
		'化',
		'仇',
		'币',
		'仍',
		'仅',
		'斤',
		'爪',
		'反',
		'介',
		'父',
		'从',
		'今',
		'凶',
		'分',
		'乏',
		'公',
		'仓',
		'月',
		'氏',
		'勿',
		'欠',
		'风',
		'丹',
		'匀',
		'乌',
		'凤',
		'勾',
		'文',
		'六',
		'方',
		'火',
		'为',
		'斗',
		'忆',
		'订',
		'计',
		'户',
		'认',
		'心',
		'尺',
		'引',
		'丑',
		'巴',
		'孔',
		'队',
		'办',
		'以',
		'允',
		'予',
		'劝',
		'双',
		'书',
		'幻',
		'厅',
		'不',
		'太',
		'犬',
		'区',
		'历',
		'尤',
		'友',
		'匹',
		'车',
		'巨',
		'牙',
		'屯',
		'比',
		'互',
		'切',
		'瓦',
		'织',
		'终',
		'驻',
		'驼',
		'绍',
		'经',
	];

	function __construct(){
		parent::__construct();
		header("Content-Type: text/plain; charset=utf-8");
	}

	public function add_student(){

	}

	public function add_college(){
		$names = [
			"石油学院",
			"计算机科学学院",
			"信息学院",
			"音乐学院",
			"物理学院",
			"生物学院",
			"体育学院",
			"科学学院",
			"太空学院",
			"历史学院",
			"法学院",
			"商务学院",
			"经济学院",
			"哲学学院",
		];
		$db = db_class()->getDriver();
		$list = list2keymap($db->select("info_campus", ['ic_name']), "ic_name", 'ic_name');
		$n = 1;
		foreach($list as $v){
			foreach(array_rand($names, rand(6, count($names))) as $v2){
				$db->insert("info_college", [
					'ico_id' => sprintf("%04d", $n++),
					'ico_name' => $names[$v2],
					'ic_name' => $v,
					'ico_tel' => "13" . rand(100, 999) . rand(100, 999) . rand(100, 999),
					'ico_teacher' => $this->name_list[array_rand($this->name_list)] . "老师",
				]);
			}
		}
	}

	public function add_discipline(){
		$list = array_unique(array_map("trim", explode("\n", file_get_contents(_RootPath_ . "/test/id_list.txt"))));
		$db = db_class()->getDriver();
		$i = 1;
		$campus_list = list2keymap($db->select("info_campus", ['ic_name']), "ic_name", 'ic_name');
		foreach($campus_list as $v){
			$xx_list = $list;//COPY
			$is_list = list2keymap($db->select("info_college", ['ico_id'], ['ic_name' => $v]), "ico_id", 'ico_id');
			foreach($is_list as $xxx){
				$id_list = array_rand($xx_list, rand(8, 15));
				foreach($id_list as $v){
					for($k = 2010; $k < 2016; $k++){
						$db->insert("info_discipline", [
							'id_id' => sprintf("%04d", $i++),
							'id_name' => $xx_list[$v],
							'id_time' => $k,
							'ico_id' => $xxx,
							'id_teacher' => $this->name_list[array_rand($this->name_list)] . "老师",
						]);
					}
					unset($xx_list[$v]);
				}
			}
		}
	}

	public function add_teacher(){
		$sex = [
			"男",
			"女"
		];
		$m = [
			"未婚",
			"已婚"
		];
		$edu = [
			'博士' => '博士',
			'研究生' => '研究生',
			'博士后' => '博士后',
			'博士后及以上' => '博士后及以上',
			'本科' => '本科',
			'大专' => '大专',
			'高职' => '高职',
			'高中' => '高中',
			'中学' => '中学',
			'小学' => '小学',
			'其他' => '其他'
		];
		$db = db_class()->getDriver();
		$city = array_keys($this->city_list);
		for($i = 0; $i < 800; $i++){
			$b = [];
			$b[0] = sprintf("%02d", rand(1975, 1989));
			$b[1] = sprintf("%02d", rand(1, 12));
			$b[2] = sprintf("%02d", rand(1, 28));
			if(!$db->insert("info_teacher", [
				'it_name' => $this->name_list[array_rand($this->name_list)] . $this->name_list2[array_rand($this->name_list2)] . $this->name_list2[array_rand($this->name_list2)],
				'it_start_date' => rand(1995, 2015) . "-09-01",
				'it_sex' => $sex[rand(0, 1)],
				'it_birthday' => $b[0] . "-" . $b[1] . "-" . $b[2],
				'it_marry' => $m[rand(0, 1)],
				'it_tel' => "1" . rand(3, 8) . rand(100, 999) . rand(100, 999) . rand(100, 999),
				'it_address' => $city[array_rand($city)],
				'it_email' => $this->rand(8) . "@" . $this->rand(4) . "." . $this->rand(3),
				'it_note' => '',
				'it_id_card' => rand(100, 999) . '0' . rand(1, 9) . '1' . implode("", $b) . rand(1000, 9999),
				'it_password' => '123456',
				'it_edu' => $edu[array_rand($edu)]
			])
			){
				print_r($db->error());
				break;
			} else{
				echo $i;
			}
		}
	}

	public function add_curriculum(){
		$db = db_class()->getDriver();
		$list = array_unique(array_map("trim", explode("\n", file_get_contents(_RootPath_ . "/test/id_list.txt"))));
		foreach($db->select("info_college", ['ico_id']) as $cv){
			$a_list = array_rand($list, rand(15, count($list)));
			foreach($a_list as $c){
				$point = rand(2, 9);
				$db->insert("info_curriculum", [
					'cu_name' => $list[$c],
					'cu_point' => 0.5 * $point,
					'cu_time' => 4 * $point,
					'cu_book' => $list[$c] . "书",
					'cu_note' => '无',
					'ico_id' => $cv['ico_id']
				]);
			}
		}
	}

	public function add_cm(){
		$db = db_class()->getDriver();
		$d_s = $db->select("info_discipline", "*");
		$teacher_list = list2keymap($db->select("info_teacher", ['it_id']), 'it_id', 'it_id');
		foreach($d_s as $ds_v){
			$cus = list2keymap($db->select("info_curriculum", "*", ['ico_id' => $ds_v['ico_id']]), 'cu_id', 'cu_id');
			$y_s = rand(2011, 2014);
			$y_n = rand(2, 4);
			for($j = 0; $j < $y_n; $j++){
				foreach(array_rand($cus, rand(5, 12)) as $c_id){
					$n_s = rand(1, 2);
					for($k = 1; $k <= $n_s; $k++){
						if($db->insert("mg_curriculum", [
								'mc_year' => $y_s,
								'mc_number' => $k,
								'mc_grade' => $ds_v['id_time'],
								'mc_note' => '无',
								'id_id' => $ds_v['id_id'],
								'ico_id' => $ds_v['ico_id'],
								'cu_id' => $c_id,
								'it_id' => array_rand($teacher_list)
							]) < 1
						){
							print_r($db->error());
							print_r($db);
							return;
						}
					}
				}
				$y_s++;
			}
		}
	}

	public function add_class(){
		set_time_limit(0);
		$db = db_class()->getDriver();
		$kk = [];
		$kk2 = [];
		$sex = [
			"男",
			"女"
		];
		$pl = [
			'群众',
			'党员',
			'中共团员',
			'预备党员'
		];
		$room = [
			"汉科",
			"新风",
			"校内"
		];
		$city = array_keys($this->city_list);
		foreach($db->select("info_discipline", "*") as $idv){
			if(!isset($kk[$idv['id_time']])){
				$kk[$idv['id_time']] = 1;
				$kk2[$idv['id_time']] = 1;
			}

			$class_number = rand(1, 6);
			$ic_name = $db->get("info_college", ['ic_name'], ['ico_id' => $idv['ico_id']]);
			$ic_name = $ic_name['ic_name'];
			for($i = 1; $i <= $class_number; $i++){
				$icl_id = sprintf($idv['id_time'] . "%06d", $kk[$idv['id_time']]++);
				$class_id = $db->insert("info_class", [
					'icl_id' => $icl_id,
					'ico_id' => $idv['ico_id'],
					'id_id' => $idv['id_id'],
					'icl_number' => $i,
					'icl_teacher' => $this->name_list[array_rand($this->name_list)] . $this->name_list2[array_rand($this->name_list2)] . $this->name_list2[array_rand($this->name_list2)],
					'icl_note' => '无',
					'icl_year' => $idv['id_time']
				]);
				if($class_id == -1){
					print_r($db->error());
					print_r($db);
					return;
				}
				$student_number = rand(22, 38);
				for($j = 0; $j < $student_number; $j++){
					$b = [];
					$city_key = $city[array_rand($city)];
					$city_xx_key = !empty($this->city_list[$city_key]) ? $this->city_list[$city_key][array_rand($this->city_list[$city_key])] : "";
					$b[0] = sprintf("%02d", rand(1990, 1996));
					$b[1] = sprintf("%02d", rand(1, 12));
					$b[2] = sprintf("%02d", rand(1, 28));
					if($db->insert("info_student", [
							'is_id' => sprintf($idv['id_time'] . "%06d", $kk2[$idv['id_time']]++),
							'is_name' => $this->name_list[array_rand($this->name_list)] . $this->name_list2[array_rand($this->name_list2)] . $this->name_list2[array_rand($this->name_list2)],
							'is_sex' => $sex[rand(0, 1)],
							'is_hometown' => implode("·", [
								str_replace("省", "", $city_key),
								str_replace("市", "", $city_xx_key)
							]),
							'is_birthday' => $b[0] . "-" . $b[1] . "-" . $b[2],
							'is_province' => $city_key,
							'is_city' => $city_xx_key,
							'is_county' => "",
							'is_zone' => "",
							'is_address' => "",
							'is_id_card' => rand(100, 999) . '0' . rand(1, 9) . '1' . implode("", $b) . rand(1000, 9999),
							'is_politics' => $pl[rand(0, 3)],
							'ic_name' => $ic_name,
							'ico_id' => $idv['ico_id'],
							'id_id' => $idv['id_id'],
							'icl_id' => $icl_id,
							'is_password' => "123456",
							'is_email' => $this->rand(8) . "@" . $this->rand(4) . "." . $this->rand(3),
							'is_tel' => "1" . rand(3, 8) . rand(100, 999) . rand(100, 999) . rand(100, 999),
							'is_room' => $room[rand(0, 2)],
							'is_room_number' => rand(1, 6) . "-" . sprintf("%02d", rand(1, 50)),
							'is_study_date' => $idv['id_time'] . "-09-01",
							'is_grade' => $idv['id_time']
						]) == -1
					){
						print_r($db->error());
						print_r($db);
						return;
					}
				}
			}
		}
	}

	public function rename_is_name(){
		set_time_limit(0);
		$db = db_class()->getDriver();
		$ids = $db->select("mg_curriculum", [
			'mc_id',
			'id_id'
		]);
		foreach($ids as $v){
			echo $v['mc_id'] . "\t" . $v['id_id'] . "\n";
		}
		//		$dt = [];
		//		$dt2 = [];
		//		$dt3 = [];
		//		foreach($ids as $v){
		//			$b = [];
		//			$b[0] = sprintf("%02d", rand(1990, 1996));
		//			$b[1] = sprintf("%02d", rand(1, 12));
		//			$b[2] = sprintf("%02d", rand(1, 28));
		//			$info = [
		//				'is_name' => $this->name_list[array_rand($this->name_list)] . $this->name_list2[array_rand($this->name_list2)] . $this->name_list2[array_rand($this->name_list2)],
		//				'is_birthday' => $b[0] . "-" . $b[1] . "-" . $b[2],
		//				'is_id_card' => rand(100, 999) . '0' . rand(1, 9) . '1' . implode("", $b) . rand(1000, 9999),
		//			];
		//			$dt[$info['is_name']] = "";
		//			$dt2[$info['is_birthday']] = "";
		//			$dt3[$info['is_id_card']] = "";
		//			//$db->update("info_student", $info, ['is_id' => $v['is_id']]);
		//			//print_r($info);
		//		}
		//		echo count($dt);
		//		echo count($dt2);
		//		echo count($dt3);
	}

	public function add_all_scores(){

	}

	private function rand($length = 40){
		$str = "abcdefghijklmnopqretuvwxyz0123456789";
		$rt = "a";
		$l = strlen($str);
		for($i = 0; $i < $length - 1; $i++){
			$rt .= $str[rand(0, $l - 1)];
		}
		return $rt;
	}

	public function table_info(){
		header("Content-Type: text/html; charset=utf-8");
		$db = db_class()->getDriver();
		$list = $db->query("show tables from sm;");
		$rt = $list->fetchAll(\PDO::FETCH_ASSOC);
		$list->closeCursor();
		$style = <<<CSS
<style>
table{
border-collapse: collapse;
}
table td{
padding:2px 4px;
}
</style>
CSS;
		echo $style;
		foreach($rt as $v){
			echo "<strong>表：", $v["Tables_in_sm"], "</strong><table border='1'><thead><tr>";
			echo "<th>字段名</th><th>类型</th><th>是否空</th><th>主键</th><th>详情</th></tr></thead><tbody>";
			$info = $db->query("SHOW FULL COLUMNS FROM {$v["Tables_in_sm"]}");
			$i2 = $info->fetchAll(\PDO::FETCH_ASSOC);
			$info->closeCursor();
			foreach($i2 as $v3){
				echo "<tr><td>{$v3['Field']}</td><td>{$v3['Type']}</td><td>{$v3['Null']}</td><td>{$v3['Key']}</td><td>{$v3['Comment']}</td></tr>";
			}
			echo "</tbody></table><p></p>";
		}
	}

	public function get_c_l(){
		header("Content-Type: text/html; charset=utf-8");
		foreach([
			'Access',
			'BaseInfo',
			'Home',
			'Profile',
			'Report',
			'Scores'
		] as $v){
			include_once(__DIR__ . "/" . $v . ".php");
			$c = "\\UView\\$v";
			$list = get_class_methods($c);
			$di = array_diff($list, $c::__un_register());
			echo "<p>控制器：{$v}，方法：" . implode("，", $di) . "</p>";
		}
	}
}