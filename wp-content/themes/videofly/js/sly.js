/*! sly 1.2.5 - 28th Aug 2014 | https://github.com/darsain/sly */
'use strict';
(function(l,A,Fa){function ma(g,k,cb){var ra,E,sa,r,ta,A,ua,na;function da(){var a=0,f=v.length;e.old=l.extend({},e);y=I?0:F[b.horizontal?"width":"height"]();S=K[b.horizontal?"width":"height"]();s=I?g:t[b.horizontal?"outerWidth":"outerHeight"]();v.length=0;e.start=0;e.end=Ga(s-y,0);if(B){a=p.length;G=t.children(b.itemSelector);p.length=0;var c=oa(t,b.horizontal?"paddingLeft":"paddingTop"),va=oa(t,b.horizontal?"paddingRight":"paddingBottom"),k="border-box"===l(G).css("boxSizing"),db="none"!==G.css("float"),
r=0,m=G.length-1,q;s=0;G.each(function(a,f){var d=l(f),e=d[b.horizontal?"outerWidth":"outerHeight"](),g=oa(d,b.horizontal?"marginLeft":"marginTop"),d=oa(d,b.horizontal?"marginRight":"marginBottom"),h=e+g+d,n=!g||!d,k={};k.el=f;k.size=n?e:h;k.half=k.size/2;k.start=s+(n?g:0);k.center=k.start-u(y/2-k.size/2);k.end=k.start-y+k.size;a||(s+=c);s+=h;b.horizontal||db||d&&(g&&0<a)&&(s-=Ha(g,d));a===m&&(k.end+=va,s+=va,r=n?d:0);p.push(k);q=k});t[0].style[b.horizontal?"width":"height"]=(k?s:s-c-va)+"px";s-=
r;p.length?(e.start=p[0][T?"center":"start"],e.end=T?q.center:y<s?q.end:e.start):e.start=e.end=0}e.center=u(e.end/2+e.start/2);l.extend(h,wa(void 0));C.length&&0<S&&(b.dynamicHandle?(N=e.start===e.end?S:u(S*y/s),N=L(N,b.minHandleSize,S),C[0].style[b.horizontal?"width":"height"]=N+"px"):N=C[b.horizontal?"outerWidth":"outerHeight"](),w.end=S-N,U||Ia());if(!I&&0<y){var n=e.start,k="";if(B)l.each(p,function(a,f){T?v.push(f.center):f.start+f.size>n&&n<=e.end&&(n=f.start,v.push(n),n+=y,n>e.end&&n<e.end+
y&&v.push(e.end))});else for(;n-y<e.end;)v.push(n),n+=y;if(V[0]&&f!==v.length){for(f=0;f<v.length;f++)k+=b.pageBuilder.call(d,f);ha=V.html(k).children();ha.eq(h.activePage).addClass(b.activeClass)}}h.slideeSize=s;h.frameSize=y;h.sbSize=S;h.handleSize=N;B?(d.initialized?(h.activeItem>=p.length||0===a&&0<p.length)&&pa(h.activeItem>=p.length?p.length-1:0,!a):(pa(b.startAt),d[M?"toCenter":"toStart"](b.startAt)),a=p[h.activeItem],J(M&&a?a.center:L(e.dest,e.start,e.end))):d.initialized?J(L(e.dest,e.start,
e.end)):J(b.startAt,1);z("load")}function J(a,f,ga){if(B&&c.released&&!ga){ga=wa(a);var g=a>e.start&&a<e.end;M?(g&&(a=p[ga.centerItem].center),T&&b.activateMiddle&&pa(ga.centerItem)):g&&(a=p[ga.firstItem].start)}c.init&&c.slidee&&b.elasticBounds?a>e.end?a=e.end+(a-e.end)/6:a<e.start&&(a=e.start+(a-e.start)/6):a=L(a,e.start,e.end);ra=+new Date;E=0;sa=e.cur;r=a;ta=a-e.cur;A=c.tweese||c.init&&!c.slidee;ua=!A&&(f||c.init&&c.slidee||!b.speed);c.tweese=0;a!==e.dest&&(e.dest=a,z("change"),U||xa());c.released&&
!d.isPaused&&d.resume();l.extend(h,wa(void 0));Ja();ha[0]&&q.page!==h.activePage&&(q.page=h.activePage,ha.removeClass(b.activeClass).eq(h.activePage).addClass(b.activeClass),z("activePage",q.page))}function xa(){U?(ua?e.cur=r:A?(na=r-e.cur,0.1>W(na)?e.cur=r:e.cur+=na*(c.released?b.swingSpeed:b.syncSpeed)):(E=Ha(+new Date-ra,b.speed),e.cur=sa+ta*jQuery.easing[b.easing](E/b.speed,E,0,1,b.speed)),r===e.cur?(e.cur=r,c.tweese=U=0):U=ia(xa),z("move"),I||(D?t[0].style[D]=ja+(b.horizontal?"translateX":"translateY")+
"("+-e.cur+"px)":t[0].style[b.horizontal?"left":"top"]=-u(e.cur)+"px"),!U&&c.released&&z("moveEnd"),Ia()):(U=ia(xa),c.released&&z("moveStart"))}function Ia(){C.length&&(w.cur=e.start===e.end?0:((c.init&&!c.slidee?e.dest:e.cur)-e.start)/(e.end-e.start)*w.end,w.cur=L(u(w.cur),w.start,w.end),q.hPos!==w.cur&&(q.hPos=w.cur,D?C[0].style[D]=ja+(b.horizontal?"translateX":"translateY")+"("+w.cur+"px)":C[0].style[b.horizontal?"left":"top"]=w.cur+"px"))}function Ka(){x.speed&&e.cur!==(0<x.speed?e.end:e.start)||
d.stop();La=c.init?ia(Ka):0;x.now=+new Date;x.pos=e.cur+(x.now-x.lastTime)/1E3*x.speed;J(c.init?x.pos:u(x.pos));c.init||e.cur!==e.dest||z("moveEnd");x.lastTime=x.now}function ya(a,f,b){"boolean"===ka(f)&&(b=f,f=Fa);f===Fa?J(e[a],b):M&&"center"!==a||(f=d.getPos(f))&&J(f[a],b,!M)}function qa(a){return null!=a?O(a)?0<=a&&a<p.length?a:-1:G.index(a):-1}function za(a){return qa(O(a)&&0>a?a+p.length:a)}function pa(a,f){var c=qa(a);if(!B||0>c)return!1;if(q.active!==c||f)G.eq(h.activeItem).removeClass(b.activeClass),
G.eq(c).addClass(b.activeClass),q.active=h.activeItem=c,Ja(),z("active",c);return c}function wa(a){a=L(O(a)?a:e.dest,e.start,e.end);var f={},c=T?0:y/2;if(!I)for(var b=0,d=v.length;b<d;b++){if(a>=e.end||b===v.length-1){f.activePage=v.length-1;break}if(a<=v[b]+c){f.activePage=b;break}}if(B){for(var d=b=c=!1,g=0,h=p.length;g<h;g++)if(!1===c&&a<=p[g].start+p[g].half&&(c=g),!1===d&&a<=p[g].center+p[g].half&&(d=g),g===h-1||a<=p[g].end+p[g].half){b=g;break}f.firstItem=O(c)?c:0;f.centerItem=O(d)?d:f.firstItem;
f.lastItem=O(b)?b:f.centerItem}return f}function Ja(){var a=e.dest<=e.start,f=e.dest>=e.end,d=a?1:f?2:3;q.slideePosState!==d&&(q.slideePosState=d,X.is("button,input")&&X.prop("disabled",a),Y.is("button,input")&&Y.prop("disabled",f),X.add(ea)[a?"addClass":"removeClass"](b.disabledClass),Y.add(Z)[f?"addClass":"removeClass"](b.disabledClass));q.fwdbwdState!==d&&c.released&&(q.fwdbwdState=d,ea.is("button,input")&&ea.prop("disabled",a),Z.is("button,input")&&Z.prop("disabled",f));B&&(a=0===h.activeItem,
f=h.activeItem>=p.length-1,d=a?1:f?2:3,q.itemsButtonState!==d&&(q.itemsButtonState=d,$.is("button,input")&&$.prop("disabled",a),aa.is("button,input")&&aa.prop("disabled",f),$[a?"addClass":"removeClass"](b.disabledClass),aa[f?"addClass":"removeClass"](b.disabledClass)))}function Ma(a,f,b){a=za(a);f=za(f);if(-1<a&&-1<f&&a!==f&&!(b&&f===a-1||!b&&f===a+1)){G.eq(a)[b?"insertAfter":"insertBefore"](p[f].el);var c=a<f?a:b?f:f-1,d=a>f?a:b?f+1:f,e=a>f;a===h.activeItem?q.active=h.activeItem=b?e?f+1:f:e?f:f-
1:h.activeItem>c&&h.activeItem<d&&(q.active=h.activeItem+=e?1:-1);da()}}function Na(a,f){for(var b=0,c=H[a].length;b<c;b++)if(H[a][b]===f)return b;return-1}function Oa(a){return u(L(a,w.start,w.end)/w.end*(e.end-e.start))+e.start}function eb(){c.history[0]=c.history[1];c.history[1]=c.history[2];c.history[2]=c.history[3];c.history[3]=c.delta}function Pa(a){c.released=0;c.source=a;c.slidee="slidee"===a}function Qa(a){var f="touchstart"===a.type,g=a.data.source,h="slidee"===g;!c.init&&(f||!~l.inArray(a.target.nodeName,
Ra)&&!l(a.target).is(b.interactive))&&("handle"!==g||b.dragHandle&&w.start!==w.end)&&(!h||(f?b.touchDragging:b.mouseDragging&&2>a.which))&&(f||P(a,1),Pa(g),c.init=0,c.$source=l(a.target),c.touch=f,c.pointer=f?a.originalEvent.touches[0]:a,c.initX=c.pointer.pageX,c.initY=c.pointer.pageY,c.initPos=h?e.cur:w.cur,c.start=+new Date,c.time=0,c.path=0,c.delta=0,c.locked=0,c.history=[0,0,0,0],c.pathToLock=h?f?30:10:0,ba.on(f?Sa:Ta,Ua),d.pause(1),(h?t:C).addClass(b.draggedClass),z("moveStart"),h&&(Va=setInterval(eb,
10)))}function Ua(a){c.released="mouseup"===a.type||"touchend"===a.type;c.pointer=c.touch?a.originalEvent[c.released?"changedTouches":"touches"][0]:a;c.pathX=c.pointer.pageX-c.initX;c.pathY=c.pointer.pageY-c.initY;c.path=fb(Wa(c.pathX,2)+Wa(c.pathY,2));c.delta=b.horizontal?c.pathX:c.pathY;if(!c.init)if(b.horizontal?W(c.pathX)>W(c.pathY):W(c.pathX)<W(c.pathY))c.init=1;else return Xa();P(a);!c.locked&&(c.path>c.pathToLock&&c.slidee)&&(c.locked=1,c.$source.on(ca,Aa));c.released&&(Xa(),b.releaseSwing&&
c.slidee&&(c.swing=300*((c.delta-c.history[0])/40),c.delta+=c.swing,c.tweese=10<W(c.swing)));J(c.slidee?u(c.initPos-c.delta):Oa(c.initPos+c.delta))}function Xa(){clearInterval(Va);ba.off(c.touch?Sa:Ta,Ua);(c.slidee?t:C).removeClass(b.draggedClass);setTimeout(function(){c.$source.off(ca,Aa)});d.resume(1);e.cur===e.dest&&c.init&&z("moveEnd");c.init=0}function Ya(){d.stop();ba.off("mouseup",Ya)}function fa(a){P(a);switch(this){case Z[0]:case ea[0]:d.moveBy(Z.is(this)?b.moveBy:-b.moveBy);ba.on("mouseup",
Ya);break;case $[0]:d.prev();break;case aa[0]:d.next();break;case X[0]:d.prevPage();break;case Y[0]:d.nextPage()}}function gb(a){n.curDelta=(b.horizontal?a.deltaY||a.deltaX:a.deltaY)||-a.wheelDelta;n.curDelta/=1===a.deltaMode?3:100;if(!B)return n.curDelta;Ba=+new Date;n.last<Ba-n.resetTime&&(n.delta=0);n.last=Ba;n.delta+=n.curDelta;1>W(n.delta)?n.finalDelta=0:(n.finalDelta=u(n.delta/1),n.delta%=1);return n.finalDelta}function hb(a){var f=+new Date;Ca+300>f?Ca=f:b.scrollBy&&e.start!==e.end&&(P(a,1),
d.slideBy(b.scrollBy*gb(a.originalEvent)))}function ib(a){b.clickBar&&a.target===K[0]&&(P(a),J(Oa((b.horizontal?a.pageX-K.offset().left:a.pageY-K.offset().top)-N/2)))}function jb(a){if(b.keyboardNavBy)switch(a.which){case b.horizontal?37:38:P(a);d["pages"===b.keyboardNavBy?"prevPage":"prev"]();break;case b.horizontal?39:40:P(a),d["pages"===b.keyboardNavBy?"nextPage":"next"]()}}function kb(a){~l.inArray(this.nodeName,Ra)||l(this).is(b.interactive)?a.stopPropagation():this.parentNode===t[0]&&d.activate(this)}
function lb(){this.parentNode===V[0]&&d.activatePage(ha.index(this))}function mb(a){if(b.pauseOnHover)d["mouseenter"===a.type?"pause":"resume"](2)}function z(a,b){if(H[a]){Da=H[a].length;for(Q=Ea.length=0;Q<Da;Q++)Ea.push(H[a][Q]);for(Q=0;Q<Da;Q++)Ea[Q].call(d,a,b)}}var b=l.extend({},ma.defaults,k),d=this,I=O(g),F=l(g),t=F.children().eq(0),y=0,s=0,e={start:0,center:0,end:0,cur:0,dest:0},K=l(b.scrollBar).eq(0),C=K.children().eq(0),S=0,N=0,w={start:0,end:0,cur:0},V=l(b.pagesBar),ha=0,v=[],G=0,p=[],
h={firstItem:0,lastItem:0,centerItem:0,activeItem:-1,activePage:0};k="basic"===b.itemNav;var T="forceCentered"===b.itemNav,M="centered"===b.itemNav||T,B=!I&&(k||M||T),Za=b.scrollSource?l(b.scrollSource):F,nb=b.dragSource?l(b.dragSource):F,Z=l(b.forward),ea=l(b.backward),$=l(b.prev),aa=l(b.next),X=l(b.prevPage),Y=l(b.nextPage),H={},q={};na=ua=A=ta=r=sa=E=ra=void 0;var x={},c={released:1},n={last:0,delta:0,resetTime:200},U=0,Va=0,R=0,La=0,Q,Da;I||(g=F[0]);d.initialized=0;d.frame=g;d.slidee=t[0];d.pos=
e;d.rel=h;d.items=p;d.pages=v;d.isPaused=0;d.options=b;d.dragging=c;d.reload=da;d.getPos=function(a){if(B)return a=qa(a),-1!==a?p[a]:!1;var f=t.find(a).eq(0);return f[0]?(a=b.horizontal?f.offset().left-t.offset().left:f.offset().top-t.offset().top,f=f[b.horizontal?"outerWidth":"outerHeight"](),{start:a,center:a-y/2+f/2,end:a-y+f,size:f}):!1};d.moveBy=function(a){x.speed=a;!c.init&&(x.speed&&e.cur!==(0<x.speed?e.end:e.start))&&(x.lastTime=+new Date,x.startPos=e.cur,Pa("button"),c.init=1,z("moveStart"),
la(La),Ka())};d.stop=function(){"button"===c.source&&(c.init=0,c.released=1)};d.prev=function(){d.activate(h.activeItem-1)};d.next=function(){d.activate(h.activeItem+1)};d.prevPage=function(){d.activatePage(h.activePage-1)};d.nextPage=function(){d.activatePage(h.activePage+1)};d.slideBy=function(a,f){if(a)if(B)d[M?"toCenter":"toStart"](L((M?h.centerItem:h.firstItem)+b.scrollBy*a,0,p.length));else J(e.dest+a,f)};d.slideTo=function(a,b){J(a,b)};d.toStart=function(a,b){ya("start",a,b)};d.toEnd=function(a,
b){ya("end",a,b)};d.toCenter=function(a,b){ya("center",a,b)};d.getIndex=qa;d.activate=function(a,f){var e=pa(a);b.smart&&!1!==e&&(M?d.toCenter(e,f):e>=h.lastItem?d.toStart(e,f):e<=h.firstItem?d.toEnd(e,f):c.released&&!d.isPaused&&d.resume())};d.activatePage=function(a,b){O(a)&&J(v[L(a,0,v.length-1)],b)};d.resume=function(a){!b.cycleBy||(!b.cycleInterval||"items"===b.cycleBy&&!p[0]||a<d.isPaused)||(d.isPaused=0,R?R=clearTimeout(R):z("resume"),R=setTimeout(function(){z("cycle");switch(b.cycleBy){case "items":d.activate(h.activeItem>=
p.length-1?0:h.activeItem+1);break;case "pages":d.activatePage(h.activePage>=v.length-1?0:h.activePage+1)}},b.cycleInterval))};d.pause=function(a){a<d.isPaused||(d.isPaused=a||100,R&&(R=clearTimeout(R),z("pause")))};d.toggle=function(){d[R?"pause":"resume"]()};d.set=function(a,c){l.isPlainObject(a)?l.extend(b,a):b.hasOwnProperty(a)&&(b[a]=c)};d.add=function(a,b){var c=l(a);B?(null==b||!p[0]||b>=p.length?c.appendTo(t):p.length&&c.insertBefore(p[b].el),b<=h.activeItem&&(q.active=h.activeItem+=c.length)):
t.append(c);da()};d.remove=function(a){if(B){if(a=za(a),-1<a){G.eq(a).remove();var b=a===h.activeItem;a<h.activeItem&&(q.active=--h.activeItem);da();b&&(q.active=null,d.activate(h.activeItem))}}else l(a).remove(),da()};d.moveAfter=function(a,b){Ma(a,b,1)};d.moveBefore=function(a,b){Ma(a,b)};d.on=function(a,b){if("object"===ka(a))for(var c in a){if(a.hasOwnProperty(c))d.on(c,a[c])}else if("function"===ka(b)){c=a.split(" ");for(var e=0,g=c.length;e<g;e++)H[c[e]]=H[c[e]]||[],-1===Na(c[e],b)&&H[c[e]].push(b)}else if("array"===
ka(b))for(c=0,e=b.length;c<e;c++)d.on(a,b[c])};d.one=function(a,b){function c(){b.apply(d,arguments);d.off(a,c)}d.on(a,c)};d.off=function(a,b){if(b instanceof Array)for(var c=0,e=b.length;c<e;c++)d.off(a,b[c]);else for(var c=a.split(" "),e=0,g=c.length;e<g;e++)if(H[c[e]]=H[c[e]]||[],null==b)H[c[e]].length=0;else{var h=Na(c[e],b);-1!==h&&H[c[e]].splice(h,1)}};d.destroy=function(){ba.add(Za).add(C).add(K).add(V).add(Z).add(ea).add($).add(aa).add(X).add(Y).unbind("."+m);$.add(aa).add(X).add(Y).removeClass(b.disabledClass);
G&&G.eq(h.activeItem).removeClass(b.activeClass);V.empty();I||(F.unbind("."+m),t.add(C).css(D||(b.horizontal?"left":"top"),D?"none":0),l.removeData(g,m));p.length=v.length=0;q={};d.initialized=0;return d};d.init=function(){if(!d.initialized){d.on(cb);var a=C;I||(a=a.add(t),F.css("overflow","hidden"),D||"static"!==F.css("position")||F.css("position","relative"));D?ja&&a.css(D,ja):("static"===K.css("position")&&K.css("position","relative"),a.css({position:"absolute"}));if(b.forward)Z.on($a,fa);if(b.backward)ea.on($a,
fa);if(b.prev)$.on(ca,fa);if(b.next)aa.on(ca,fa);if(b.prevPage)X.on(ca,fa);if(b.nextPage)Y.on(ca,fa);Za.on(ab,hb);if(K[0])K.on(ca,ib);if(B&&b.activateOn)F.on(b.activateOn+"."+m,"*",kb);if(V[0]&&b.activatePageOn)V.on(b.activatePageOn+"."+m,"*",lb);nb.on(bb,{source:"slidee"},Qa);if(C)C.on(bb,{source:"handle"},Qa);ba.bind("keydown."+m,jb);I||(F.on("mouseenter."+m+" mouseleave."+m,mb),F.on("scroll."+m,ob));da();if(b.cycleBy&&!I)d[b.startPaused?"pause":"resume"]();d.initialized=1;return d}}}function ka(g){return null==
g?String(g):"object"===typeof g||"function"===typeof g?Object.prototype.toString.call(g).match(/\s([a-z]+)/i)[1].toLowerCase()||"object":typeof g}function P(g,k){g.preventDefault();k&&g.stopPropagation()}function Aa(g){P(g,1);l(this).off(g.type,Aa)}function ob(){this.scrollTop=this.scrollLeft=0}function O(g){return!isNaN(parseFloat(g))&&isFinite(g)}function oa(g,k){return 0|u(String(g.css(k)).replace(/[^\-0-9.]/g,""))}function L(g,k,l){return g<k?k:g>l?l:g}var m="sly",la=A.cancelAnimationFrame||A.cancelRequestAnimationFrame,
ia=A.requestAnimationFrame,D,ja,ba=l(document),bb="touchstart."+m+" mousedown."+m,Ta="mousemove."+m+" mouseup."+m,Sa="touchmove."+m+" touchend."+m,ab=(document.implementation.hasFeature("Event.wheel","3.0")?"wheel.":"mousewheel.")+m,ca="click."+m,$a="mousedown."+m,Ra=["INPUT","SELECT","BUTTON","TEXTAREA"],Ea=[],Ba,W=Math.abs,fb=Math.sqrt,Wa=Math.pow,u=Math.round,Ga=Math.max,Ha=Math.min,Ca=0;ba.on(ab,function(){Ca=+new Date});(function(g){for(var k=["moz","webkit","o"],l=0,m=0,E=k.length;m<E&&!la;++m)ia=
(la=g[k[m]+"CancelAnimationFrame"]||g[k[m]+"CancelRequestAnimationFrame"])&&g[k[m]+"RequestAnimationFrame"];la||(ia=function(k){var m=+new Date,E=Ga(0,16-(m-l));l=m+E;return g.setTimeout(function(){k(m+E)},E)},la=function(g){clearTimeout(g)})})(window);(function(){function g(g){for(var m=0,u=k.length;m<u;m++){var r=k[m]?k[m]+g.charAt(0).toUpperCase()+g.slice(1):g;if(null!=l.style[r])return r}}var k=["","webkit","moz","ms","o"],l=document.createElement("div");D=g("transform");ja=g("perspective")?"translateZ(0) ":
""})();A.Sly=ma;l.fn.sly=function(g,k){var u,D;if(!l.isPlainObject(g)){if("string"===ka(g)||!1===g)u=!1===g?"destroy":g,D=Array.prototype.slice.call(arguments,1);g={}}return this.each(function(E,A){var r=l.data(A,m);r||u?r&&u&&r[u]&&r[u].apply(r,D):l.data(A,m,(new ma(A,g,k)).init())})};ma.defaults={horizontal:0,itemNav:null,itemSelector:null,smart:0,activateOn:null,activateMiddle:0,scrollSource:null,scrollBy:0,scrollHijack:300,dragSource:null,mouseDragging:0,touchDragging:0,releaseSwing:0,swingSpeed:0.2,
elasticBounds:0,interactive:null,scrollBar:null,dragHandle:0,dynamicHandle:0,minHandleSize:50,clickBar:0,syncSpeed:0.5,pagesBar:null,activatePageOn:null,pageBuilder:function(g){return"<li>"+(g+1)+"</li>"},forward:null,backward:null,prev:null,next:null,prevPage:null,nextPage:null,cycleBy:null,cycleInterval:5E3,pauseOnHover:0,startPaused:0,moveBy:300,speed:0,easing:"swing",startAt:0,keyboardNavBy:null,draggedClass:"dragged",activeClass:"active",disabledClass:"disabled"}})(jQuery,window);

jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

function startSly(){
    jQuery(function($){
        (function () {
            var $frame  = $('.slyframe');
                var $slidee = $frame.children('ul').eq(0);
                var $wrap   = $frame.parent();

                // Call Sly on frame
                $frame.sly({
                    horizontal: 1,
                    itemNav: 'centered',
                    smart: 1,
                    activateOn: 'click',
                    mouseDragging: 1,
                    touchDragging: 1,
                    releaseSwing: 1,
                    startAt: 0,
                    scrollBar: $wrap.find('.slyscrollbar'),
                    scrollBy: 1,
                    pagesBar: $wrap.find('.pages'),
                    activatePageOn: 'click',
                    speed: 300,
                    elasticBounds: 1,
                    easing: 'easeOutExpo',
                    dragHandle: 1,
                    dynamicHandle: 1,
                    clickBar: 1
                });
        }());
    });
}

jQuery(window).load(function(){
	startSly();
});