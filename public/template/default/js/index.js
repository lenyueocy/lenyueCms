

$(function(){
	//nav
	jQuery("#nav").slide({ 
		type:"menu",// 效果类型，针对菜单/导航而引入的参数（默认slide）
		titCell:".nLi", //鼠标触发对象
		targetCell:".sub", //titCell里面包含的要显示/消失的对象
		effect:"slideDown", //targetCell下拉效果
		delayTime:300 , //效果时间
		triggerTime:0 //鼠标延迟触发时间（默认150）
	});
	
	
	//banner
	jQuery(".banner").slide({
		mainCell:".bd ul",
		effect:"leftLoop",
		autoPlay:true
	});
	
	jQuery(".introDiv").slide({
		mainCell:".bd ul",
		effect:"leftLoop",
		trigger:"click"
//		autoPlay:true
	});
	
	jQuery(".rdzxTab").slide({
		mainCell:".bd ul",
		effect:"topLoop",
		trigger:"click"
//		autoPlay:true
	});
	
	//动态加class
	$(".page a").click(function(){
		$(this).addClass("cur").siblings().removeClass("cur");
	});
	$(".geneR ul li label").click(function(){
		$(this).addClass("on").siblings().removeClass("on");
	});
	
	//伪类选择器
	$(".tytdDiv ul li:nth-child(3n),.groupList li:nth-child(4n),.videoDiv ul li:nth-child(3n),.linkDiv ul li:nth-child(6n)").css({'margin-right':'0','float':'right'});
	
	//动画
	window.scrollReveal = new scrollReveal({
		reset: true,
		move: '100px'
	});
	
	
})