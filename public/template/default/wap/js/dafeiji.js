    //获得主界面
var mainDiv=document.getElementById("maindiv");
    //获得开始界面
var startdiv=document.getElementById("startdiv");
    //获得游戏中分数显示界面
var scorediv=document.getElementById("scorediv");
    //获得分数界面
var scorelabel=document.getElementById("label");
    //获得暂停界面
var suspenddiv=document.getElementById("suspenddiv");
    //获得游戏结束界面
var enddiv=document.getElementById("enddiv");
    //获得游戏结束后分数统计界面
var planscore=document.getElementById("planscore");
    //获得游戏结束后分数统计界面
var overDiv=document.getElementById("overDiv");
    //初始化分数
var scores=0;
    //本方飞机生命条数
var gameLife = 3;

/*
 创建飞机类
 */
function plan(hp,X,Y,sizeX,sizeY,score,dietime,sudu,boomimage,imagesrc){
    this.planX=X;
    this.planY=Y;
    this.imagenode=null;
    this.planhp=hp;
    this.planscore=score;
    this.plansizeX=sizeX;
    this.plansizeY=sizeY;
    this.planboomimage=boomimage;
    this.planisdie=false;
    this.plandietimes=0;
    this.plandietime=dietime;
    this.plansudu=sudu;
//行为
/*
移动行为
     */
    this.planmove=function(){
        if(scores<=1000){
            this.imagenode.style.top=this.imagenode.offsetTop+this.plansudu+"px";
        }
        else if(scores>1000 && scores<=3000){
            this.imagenode.style.top=this.imagenode.offsetTop+this.plansudu+1+"px";
        }
        else if(scores>3000 && scores<=5000){
            this.imagenode.style.top=this.imagenode.offsetTop+this.plansudu+2+"px";
        }
        else if(scores>5000 &&scores<=7000){
            this.imagenode.style.top=this.imagenode.offsetTop+this.plansudu+3+"px";
        }
        else if(scores>7000 &&scores <= 10000){
            this.imagenode.style.top=this.imagenode.offsetTop+this.plansudu+4+"px";
        }
        else{
            this.imagenode.style.top=this.imagenode.offsetTop+this.plansudu+5+"px";
        }
    }
    this.init=function(){
        this.imagenode=document.createElement("img");
        this.imagenode.style.left=this.planX+"px";
        this.imagenode.style.top=this.planY+"px";
        this.imagenode.src=imagesrc;
        mainDiv.appendChild(this.imagenode);
    }
    this.init();
}

/*
创建子弹类
 */
function bullet(X,Y,sizeX,sizeY,imagesrc){
    this.bulletX=X;
    this.bulletY=Y;
    this.bulletimage=null;
    this.bulletattach=1;
    this.bulletsizeX=sizeX;
    this.bulletsizeY=sizeY;
//行为
/*
 移动行为
 */
    this.bulletmove=function(){
        this.bulletimage.style.top=this.bulletimage.offsetTop-20+"px";
    }
    this.init=function(){
        this.bulletimage=document.createElement("img");
        this.bulletimage.style.left= this.bulletX+"px";
        this.bulletimage.style.top= this.bulletY+"px";
        this.bulletimage.src=imagesrc;
        mainDiv.appendChild(this.bulletimage);
    }
    this.init();
}

/*
 创建单行子弹类
 */
function oddbullet(X,Y){
    bullet.call(this,X,Y,6,14,"/template/default/wap/images/weixin/dafeiji/bullet1.png");
}

/*
创建敌机类
 */
function enemy(hp,a,b,sizeX,sizeY,score,dietime,sudu,boomimage,imagesrc){
    plan.call(this,hp,random(a,b),-100,sizeX,sizeY,score,dietime,sudu,boomimage,imagesrc);
}
//产生min到max之间的随机数
function random(min,max){
    return Math.floor(min+Math.random()*(max-min));
}

/*
创建本方飞机类
 */
function ourplan(X,Y){
    var imagesrc="/template/default/wap/images/weixin/dafeiji/我的飞机.gif";
    plan.call(this,1,X,Y,66,80,0,660,0,"/template/default/wap/images/weixin/dafeiji/本方飞机爆炸.gif",imagesrc);
    this.imagenode.setAttribute('id','ourplan');
}

/*
 创建本方飞机
 */
var dW=$('body').width();;
var dH = $('body').height();
var selfplan=new ourplan(dW/2-33,dH*0.75);
//移动事件
var ourPlan=document.getElementById('ourplan');
var yidong=function(){
    // var oevent=window.event||arguments[0];
    // var chufa=oevent.srcElement||oevent.target;
    // var selfplanX=oevent.clientX;
    // var selfplanY=oevent.clientY;
    // ourPlan.style.left=selfplanX-selfplan.plansizeX/2+"px";
    // ourPlan.style.top=selfplanY-selfplan.plansizeY/2+"px";
//    document.getElementsByTagName('img')[0].style.left=selfplanX-selfplan.plansizeX/2+"px";
//    document.getElementsByTagName('img')[0]..style.top=selfplanY-selfplan.plansizeY/2+"px";
    var endTouchY = 0;
    var endTouchX = 0;
    ourPlan.addEventListener('touchstart',function(ev){
        ev.preventDefault();
        this.addEventListener('touchmove', function(ev){
            ev.preventDefault();
            var ev = ev.touches[0];
            ourPlan.style.top = (ev.pageY-41)+'px';
            ourPlan.style.left = (ev.pageX-33)+'px';
            endTouchX = ev.pageX;
            endTouchY = ev.pageY;
        },false);
        this.addEventListener('touchend', function(ev){
            ev.preventDefault();
            this.ontouchmove = null;
            this.ontouchend = null;
            var x;
            var y;
            x = (x>(dW-66))?(dW-66):x;
            y = (y>(dH-82))?(dH-82):y;
            ourPlan.style.left = x + 'px';
            ourPlan.style.top = y + 'px';
        },false);
    },false);
}
/*
暂停事件
 */
var number=0;
var findguanzhu;
var zanting=function(type){
    if(type == 'over'){
        if(number == 0 ) {
            over_zangting();
            findguanzhu = setInterval(isguanzhu,1000);
        }else{
            over_zangting_end();
        }
    }else {
        if (number == 0) {
            suspenddiv.style.display = "block";
            if (document.removeEventListener) {
                mainDiv.removeEventListener("touchstart", yidong, true);
                bodyobj.removeEventListener("touchstart", bianjie, true);
            }
            else if (document.detachEvent) {
                mainDiv.detachEvent("ontouchstart", yidong);
                bodyobj.detachEvent("ontouchstart", bianjie);
            }
            clearInterval(set);
            number = 1;
        }
        else {
            suspenddiv.style.display = "none";
            if (document.addEventListener) {
                mainDiv.addEventListener("touchstart", yidong, true);
                bodyobj.addEventListener("touchstart", bianjie, true);
            }
            else if (document.attachEvent) {
                mainDiv.attachEvent("ontouchstart", yidong);
                bodyobj.attachEvent("ontouchstart", bianjie);
            }
            set = setInterval(start, 20);
            number = 0;
        }
    }
}
//判断本方飞机是否移出边界,如果移出边界,则取消mousemove事件,反之加上mousemove事件
var bianjie=function(){
    var oevent=window.event||arguments[0];
    var bodyobjX=oevent.clientX;
    var bodyobjY=oevent.clientY;
    if(bodyobjX<0||bodyobjX>dW||bodyobjY<0||bodyobjY>dH){
        if(document.removeEventListener){
            mainDiv.removeEventListener("touchstart",yidong,true);
        }
        else if(document.detachEvent){
            mainDiv.detachEvent("ontouchstart",yidong);
        }
    }
    else{
        if(document.addEventListener){
            mainDiv.addEventListener("touchstart",yidong,true);
        }
        else if(document.attachEvent){
            mainDiv.attachEvent("notouchstart",yidong);
        }
    }
}
//暂停界面重新开始事件
//function chongxinkaishi(){
//    location.reload(true);
//    startdiv.style.display="none";
//    maindiv.style.display="block";
//}
var bodyobj=document.getElementsByTagName("body")[0];
if(document.addEventListener){
    //为本方飞机添加移动和暂停
    mainDiv.addEventListener("click",yidong,true);
    //为本方飞机添加暂停事件
    selfplan.imagenode.addEventListener("click",zanting,true);
    //为body添加判断本方飞机移出边界事件
    bodyobj.addEventListener("touchstart",bianjie,true);
    //为暂停界面的继续按钮添加暂停事件
    suspenddiv.getElementsByTagName("button")[0].addEventListener("click",zanting,true);
//    suspenddiv.getElementsByTagName("button")[1].addEventListener("click",chongxinkaishi,true);
    //为暂停界面的返回主页按钮添加事件
    suspenddiv.getElementsByTagName("button")[2].addEventListener("click",jixu,true);
}
else if(document.attachEvent){
    //为本方飞机添加移动
    mainDiv.attachEvent("ontouchstart",yidong);
    //为本方飞机添加暂停事件
    selfplan.imagenode.attachEvent("onclick",zanting);
    //为body添加判断本方飞机移出边界事件
    bodyobj.attachEvent("onclick",bianjie);
    //为暂停界面的继续按钮添加暂停事件
    suspenddiv.getElementsByTagName("button")[0].attachEvent("onclick",zanting);
//    suspenddiv.getElementsByTagName("button")[1].attachEvent("click",chongxinkaishi,true);
    //为暂停界面的返回主页按钮添加事件
    suspenddiv.getElementsByTagName("button")[2].attachEvent("click",jixu,true);
}
//初始化隐藏本方飞机
selfplan.imagenode.style.display="none";

/*
敌机对象数组
 */
var enemys=[];

/*
子弹对象数组
 */
var bullets=[];
var mark=0;
var mark1=0;
var backgroundPositionY=0;
/*
开始函数
 */
function start(){
    // mainDiv.style.backgroundPositionY=backgroundPositionY+"px";
    // backgroundPositionY+=0.5;
    if(backgroundPositionY==568){
        backgroundPositionY=0;
    }
    mark++;
    /*
    创建敌方飞机
     */

    if(mark==20){
        mark1++;
        //中飞机
        if(mark1%5==0){
            enemys.push(new enemy(6,25,274,46,60,50,360,random(1,3),"/template/default/wap/images/weixin/dafeiji/中飞机爆炸.gif","/template/default/wap/images/weixin/dafeiji/enemy3_fly_1.png"));
        }
        //大飞机
        if(mark1==20){
            enemys.push(new enemy(12,57,210,110,164,100,540,1,"/template/default/wap/images/weixin/dafeiji/大飞机爆炸.gif","/template/default/wap/images/weixin/dafeiji/enemy2_fly_1.png"));
            mark1=0;
        }
        //小飞机
        else{
            enemys.push(new enemy(1,19,286,34,24,10,360,random(1,4),"/template/default/wap/images/weixin/dafeiji/小飞机爆炸.gif","/template/default/wap/images/weixin/dafeiji/enemy1_fly_1.png"));
        }
        mark=0;
    }

/*
移动敌方飞机
 */
    var enemyslen=enemys.length;
    for(var i=0;i<enemyslen;i++){
        if(enemys[i].planisdie!=true){
            enemys[i].planmove();
        }
/*
 如果敌机超出边界,删除敌机
 */
        if(enemys[i].imagenode.offsetTop>dH){
            mainDiv.removeChild(enemys[i].imagenode);
            enemys.splice(i,1);
            enemyslen--;
        }
        //当敌机死亡标记为true时，经过一段时间后清除敌机
        if(enemys[i].planisdie==true){
            enemys[i].plandietimes+=20;
            if(enemys[i].plandietimes==enemys[i].plandietime){
                mainDiv.removeChild(enemys[i].imagenode);
                enemys.splice(i,1);
                enemyslen--;
            }
        }
    }

/*
创建子弹
*/
    if(mark%5==0){
            bullets.push(new oddbullet(parseInt(selfplan.imagenode.style.left)+31,parseInt(selfplan.imagenode.style.top)-10));
    }

/*
移动子弹
*/
    var bulletslen=bullets.length;
    for(var i=0;i<bulletslen;i++){
        bullets[i].bulletmove();
/*
如果子弹超出边界,删除子弹
*/
        if(bullets[i].bulletimage.offsetTop<0){
            mainDiv.removeChild(bullets[i].bulletimage);
            bullets.splice(i,1);
            bulletslen--;
        }
    }

/*
碰撞判断
*/
    for(var k=0;k<bulletslen;k++){
        for(var j=0;j<enemyslen;j++){
            //判断碰撞本方飞机
            if(enemys[j].planisdie==false){
                if(enemys[j].imagenode.offsetLeft+enemys[j].plansizeX>=selfplan.imagenode.offsetLeft&&enemys[j].imagenode.offsetLeft<=selfplan.imagenode.offsetLeft+selfplan.plansizeX){
                  if(enemys[j].imagenode.offsetTop+enemys[j].plansizeY>=selfplan.imagenode.offsetTop+40&&enemys[j].imagenode.offsetTop<=selfplan.imagenode.offsetTop-20+selfplan.plansizeY){
                      //碰撞本方飞机，游戏结束，统计分数
                      selfplan.imagenode.src="/template/default/wap/images/weixin/dafeiji/本方飞机爆炸.gif";
                      updateScore();
                      zanting('over');
                      return;
                  }
                }
                //判断子弹与敌机碰撞
                if((bullets[k].bulletimage.offsetLeft+bullets[k].bulletsizeX>enemys[j].imagenode.offsetLeft)&&(bullets[k].bulletimage.offsetLeft<enemys[j].imagenode.offsetLeft+enemys[j].plansizeX)){
                    if(bullets[k].bulletimage.offsetTop<=enemys[j].imagenode.offsetTop+enemys[j].plansizeY&&bullets[k].bulletimage.offsetTop+bullets[k].bulletsizeY>=enemys[j].imagenode.offsetTop){
                        //敌机血量减子弹攻击力
                        enemys[j].planhp=enemys[j].planhp-bullets[k].bulletattach;
                        //敌机血量为0，敌机图片换为爆炸图片，死亡标记为true，计分
                        if(enemys[j].planhp==0){
                            scores=scores+enemys[j].planscore;
                            scorelabel.innerHTML=scores;
                            enemys[j].imagenode.src=enemys[j].planboomimage;
                            enemys[j].planisdie=true;
                        }
                        //删除子弹
                        mainDiv.removeChild(bullets[k].bulletimage);
                            bullets.splice(k,1);
                            bulletslen--;
                            break;
                    }
                }
            }
        }
    }
}
/*
开始游戏按钮点击事件
 */
var set;
function begin(){

    startdiv.style.display="none";
    mainDiv.style.display="block";
    selfplan.imagenode.style.display="block";
    scorediv.style.display="block";
    /*
     调用开始函数
     */
    set=setInterval(start,20);
}
//游戏结束后点击继续按钮事件
function jixu(){
    location.reload(true);
}
function over_zangting(){
    overDiv.style.display = 'block';
    if (document.removeEventListener) {
        mainDiv.removeEventListener("touchstart", yidong, true);
        bodyobj.removeEventListener("touchstart", bianjie, true);
    }
    else if (document.detachEvent) {
        mainDiv.detachEvent("ontouchstart", yidong);
        bodyobj.detachEvent("ontouchstart", bianjie);
    }
    clearInterval(set);
    number = 1;
}
function over_zangting_end(){
    overDiv.style.display = "none";
    if (document.addEventListener) {
        mainDiv.addEventListener("touchstart", yidong, true);
        bodyobj.addEventListener("touchstart", bianjie, true);
    }
    else if (document.attachEvent) {
        mainDiv.attachEvent("ontouchstart", yidong);
        bodyobj.attachEvent("ontouchstart", bianjie);
    }
    //移除已经爆炸的本方飞机 复活重新创建本方飞机
    document.getElementById('ourplan').parentNode.removeChild(document.getElementById('ourplan'));
    selfplan = new ourplan(dW/2-33,dH*0.75);
    ourPlan = document.getElementById('ourplan');
    ourPlan.style.left = 100 + 'px';
    ourPlan.style.top = dH-66 + 'px';
    //-------------------------------------
    set = setInterval(start, 20);
    number = 0;
}

function overCount(){
     fuhuoDiv.style.display = 'none';
     enddiv.style.display="block";
     planscore.innerHTML=scores;
     if(document.removeEventListener){
         mainDiv.removeEventListener("touchstart",yidong,true);
         bodyobj.removeEventListener("touchstart",bianjie,true);
     }
     else if(document.detachEvent){
         mainDiv.detachEvent("ontouchstart",yidong);
         bodyobj.removeEventListener("touchstart",bianjie,true);
     }
}
function updateScore(){
    $.post(scoresUrl,{scores:scores},function (res) {
        var res = $.parseJSON(res);
        if(res.status == 'error'){
            layer.open({
                content: res.msg,
                btn: '继续加油',
                shadeClose: false,
                yes: function(){
                    location.reload();
                }
            });
        }
    });
}
function isguanzhu(){
    $.get(isguanzhuUrl,function (res) {
        var res = $.parseJSON(res);
        if(res.subscribe == 1){
            over_zangting_end();
            clearInterval(findguanzhu);
        }else{

        }
    });
}

/*
    完成界面的初始化
    敌方小飞机一个
    我方飞机一个
 */
