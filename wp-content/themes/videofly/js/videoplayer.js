(function (window, doc) {
    var m = Math,
        dummyStyle = doc.createElement('div').style,
        vendor = (function () {
            var vendors = 't,webkitT,MozT,msT,OT'.split(','),
                t,
                i = 0,
                l = vendors.length;

            for (; i < l; i++) {
                t = vendors[i] + 'ransform';
                if (t in dummyStyle) {
                    return vendors[i].substr(0, vendors[i].length - 1);
                }
            }

            return false;
        })(),
        cssVendor = vendor ? '-' + vendor.toLowerCase() + '-' : '',

// Style properties
        transform = prefixStyle('transform'),
        transitionProperty = prefixStyle('transitionProperty'),
        transitionDuration = prefixStyle('transitionDuration'),
        transformOrigin = prefixStyle('transformOrigin'),
        transitionTimingFunction = prefixStyle('transitionTimingFunction'),
        transitionDelay = prefixStyle('transitionDelay'),

// Browser capabilities
        isAndroid = (/android/gi).test(navigator.appVersion),
        isIDevice = (/iphone|ipad/gi).test(navigator.appVersion),
        isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),

        has3d = prefixStyle('perspective') in dummyStyle,
        hasTouch = 'ontouchstart' in window && !isTouchPad,
        hasTransform = vendor !== false,
        hasTransitionEnd = prefixStyle('transition') in dummyStyle,

        RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize',
        START_EV = hasTouch ? 'touchstart' : 'mousedown',
        MOVE_EV = hasTouch ? 'touchmove' : 'mousemove',
        END_EV = hasTouch ? 'touchend' : 'mouseup',
        CANCEL_EV = hasTouch ? 'touchcancel' : 'mouseup',
        TRNEND_EV = (function () {
            if (vendor === false) return false;

            var transitionEnd = {
                '':'transitionend',
                'webkit':'webkitTransitionEnd',
                'Moz':'transitionend',
                'O':'otransitionend',
                'ms':'MSTransitionEnd'
            };

            return transitionEnd[vendor];
        })(),

        nextFrame = (function () {
            return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                window.msRequestAnimationFrame ||
                function (callback) {
                    return setTimeout(callback, 1);
                };
        })(),
        cancelFrame = (function () {
            return window.cancelRequestAnimationFrame ||
                window.webkitCancelAnimationFrame ||
                window.webkitCancelRequestAnimationFrame ||
                window.mozCancelRequestAnimationFrame ||
                window.oCancelRequestAnimationFrame ||
                window.msCancelRequestAnimationFrame ||
                clearTimeout;
        })(),

// Helpers
        translateZ = has3d ? ' translateZ(0)' : '',

// Constructor
        iScroll = function (el, options) {
            var that = this,
                i;

            that.wrapper = typeof el == 'object' ? el : doc.getElementById(el);
            that.wrapper.style.overflow = 'hidden';
            that.scroller = that.wrapper.children[0];

            // Default options
            that.options = {
                hScroll:true,
                vScroll:true,
                x:0,
                y:0,
                bounce:true,
                bounceLock:false,
                momentum:true,
                lockDirection:true,
                useTransform:true,
                useTransition:false,
                topOffset:0,
                checkDOMChanges:false, // Experimental
                handleClick:true,

                // Scrollbar
                hScrollbar:true,
                vScrollbar:true,
                fixedScrollbar:isAndroid,
                hideScrollbar:isIDevice,
                fadeScrollbar:isIDevice && has3d,
                scrollbarClass:'',

                // Zoom
                zoom:false,
                zoomMin:1,
                zoomMax:4,
                doubleTapZoom:2,
                wheelAction:'scroll',

                // Snap
                snap:false,
                snapThreshold:1,

                // Events
                onRefresh:null,
                onBeforeScrollStart:function (e) {
                    e.preventDefault();
                },
                onScrollStart:null,
                onBeforeScrollMove:null,
                onScrollMove:null,
                onBeforeScrollEnd:null,
                onScrollEnd:null,
                onTouchEnd:null,
                onDestroy:null,
                onZoomStart:null,
                onZoom:null,
                onZoomEnd:null,
//custom
                keepInCenterH:false,
                keepInCenterV:false
            };

// User defined options
            for (i in options) that.options[i] = options[i];

// Set starting position
            that.x = that.options.x;
            that.y = that.options.y;

// Normalize options
            that.options.useTransform = hasTransform && that.options.useTransform;
            that.options.hScrollbar = that.options.hScroll && that.options.hScrollbar;
            that.options.vScrollbar = that.options.vScroll && that.options.vScrollbar;
            that.options.zoom = that.options.useTransform && that.options.zoom;
            that.options.useTransition = hasTransitionEnd && that.options.useTransition;
//custom
            that.keepInCenterH = that.options.keepInCenterH;
            that.keepInCenterV = that.options.keepInCenterV;

// Helpers FIX ANDROID BUG!
// translate3d and scale doesn't work together!
// Ignoring 3d ONLY WHEN YOU SET that.options.zoom
            if (that.options.zoom && isAndroid) {
                translateZ = '';
            }

// Set some default styles
            that.scroller.style[transitionProperty] = that.options.useTransform ? cssVendor + 'transform' : 'top left';
            that.scroller.style[transitionDuration] = '0';
            that.scroller.style[transformOrigin] = '0 0';
            if (that.options.useTransition) that.scroller.style[transitionTimingFunction] = 'cubic-bezier(0.33,0.66,0.66,1)';

            if (that.options.useTransform) that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px)' + translateZ;
            else that.scroller.style.cssText += ';position:absolute;top:' + that.y + 'px;left:' + that.x + 'px';

            if (that.options.useTransition) that.options.fixedScrollbar = true;

            that.refresh();

            that._bind(RESIZE_EV, window);
            that._bind(START_EV);
            if (!hasTouch) {
                if (that.options.wheelAction != 'none') {
                    that._bind('DOMMouseScroll');
                    that._bind('mousewheel');
                }
            }

            if (that.options.checkDOMChanges) that.checkDOMTime = setInterval(function () {
                that._checkDOMChanges();
            }, 500);
        };

// Prototype
    iScroll.prototype = {
        enabled:true,
        x:0,
        y:0,
        steps:[],
        scale:1,
        currPageX:0, currPageY:0,
        pagesX:[], pagesY:[],
        aniTime:null,
        wheelZoomCount:0,

        handleEvent:function (e) {
            var that = this;
            switch (e.type) {
                case START_EV:
                    if (!hasTouch && e.button !== 0) return;
                    that._start(e);
                    break;
                case MOVE_EV:
                    that._move(e);
                    break;
                case END_EV:
                case CANCEL_EV:
                    that._end(e);
                    break;
                case RESIZE_EV:
                    that._resize();
                    break;
                case 'DOMMouseScroll':
                case 'mousewheel':
                    that._wheel(e);
                    break;
                case TRNEND_EV:
                    that._transitionEnd(e);
                    break;
            }
        },

        _checkDOMChanges:function () {
            if (this.moved || this.zoomed || this.animating ||
                (this.scrollerW == this.scroller.offsetWidth * this.scale && this.scrollerH == this.scroller.offsetHeight * this.scale)) return;

            this.refresh();
        },

        _scrollbar:function (dir) {
            var that = this,
                bar;

            if (!that[dir + 'Scrollbar']) {
                if (that[dir + 'ScrollbarWrapper']) {
                    if (hasTransform) that[dir + 'ScrollbarIndicator'].style[transform] = '';
                    that[dir + 'ScrollbarWrapper'].parentNode.removeChild(that[dir + 'ScrollbarWrapper']);
                    that[dir + 'ScrollbarWrapper'] = null;
                    that[dir + 'ScrollbarIndicator'] = null;
                }

                return;
            }

            if (!that[dir + 'ScrollbarWrapper']) {
                // Create the scrollbar wrapper
                bar = doc.createElement('div');

                if (that.options.scrollbarClass) bar.className = that.options.scrollbarClass + dir.toUpperCase();
                else bar.style.cssText = 'position:absolute;z-index:100;' + (dir == 'h' ? 'height:7px;bottom:1px;left:2px;right:' + (that.vScrollbar ? '7' : '2') + 'px' : 'width:7px;bottom:' + (that.hScrollbar ? '7' : '2') + 'px;top:2px;right:1px');

                bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:opacity;' + cssVendor + 'transition-duration:' + (that.options.fadeScrollbar ? '350ms' : '0') + ';overflow:hidden;opacity:' + (that.options.hideScrollbar ? '0' : '1');

                that.wrapper.appendChild(bar);
                that[dir + 'ScrollbarWrapper'] = bar;

                // Create the scrollbar indicator
                bar = doc.createElement('div');
                if (!that.options.scrollbarClass) {
                    bar.style.cssText = 'position:absolute;z-index:100;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);' + cssVendor + 'background-clip:padding-box;' + cssVendor + 'box-sizing:border-box;' + (dir == 'h' ? 'height:100%' : 'width:100%') + ';' + cssVendor + 'border-radius:3px;border-radius:3px';
                }
                bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:' + cssVendor + 'transform;' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1);' + cssVendor + 'transition-duration:0;' + cssVendor + 'transform: translate(0,0)' + translateZ;
                if (that.options.useTransition) bar.style.cssText += ';' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1)';

                that[dir + 'ScrollbarWrapper'].appendChild(bar);
                that[dir + 'ScrollbarIndicator'] = bar;
            }

            if (dir == 'h') {
                that.hScrollbarSize = that.hScrollbarWrapper.clientWidth;
                that.hScrollbarIndicatorSize = m.max(m.round(that.hScrollbarSize * that.hScrollbarSize / that.scrollerW), 8);
                that.hScrollbarIndicator.style.width = that.hScrollbarIndicatorSize + 'px';
                that.hScrollbarMaxScroll = that.hScrollbarSize - that.hScrollbarIndicatorSize;
                that.hScrollbarProp = that.hScrollbarMaxScroll / that.maxScrollX;
            } else {
                that.vScrollbarSize = that.vScrollbarWrapper.clientHeight;
                that.vScrollbarIndicatorSize = m.max(m.round(that.vScrollbarSize * that.vScrollbarSize / that.scrollerH), 8);
                that.vScrollbarIndicator.style.height = that.vScrollbarIndicatorSize + 'px';
                that.vScrollbarMaxScroll = that.vScrollbarSize - that.vScrollbarIndicatorSize;
                that.vScrollbarProp = that.vScrollbarMaxScroll / that.maxScrollY;
            }

// Reset position
            that._scrollbarPos(dir, true);
        },

        _resize:function () {
            var that = this;
            setTimeout(function () {
                that.refresh();
            }, isAndroid ? 200 : 0);
        },

        _pos:function (x, y) {
            if (this.zoomed) return;
            //custom - we need to center the scroller if there is no scrollbars
//                if(!this.keepInCenterH)
//                    x = this.hScroll ? x : 0;
//                if(!this.keepInCenterV)
//                    y = this.vScroll ? y : 0;

            if (this.options.useTransform) {
                this.scroller.style[transform] = 'translate(' + x + 'px,' + y + 'px) scale(' + this.scale + ')' + translateZ;
            } else {
                x = m.round(x);
                y = m.round(y);
                this.scroller.style.left = x + 'px';
                this.scroller.style.top = y + 'px';
            }

            this.x = x;
            this.y = y;

            this._scrollbarPos('h');
            this._scrollbarPos('v');
        },

        _scrollbarPos:function (dir, hidden) {
            var that = this,
                pos = dir == 'h' ? that.x : that.y,
                size;

            if (!that[dir + 'Scrollbar']) return;

            pos = that[dir + 'ScrollbarProp'] * pos;

            if (pos < 0) {
                if (!that.options.fixedScrollbar) {
                    size = that[dir + 'ScrollbarIndicatorSize'] + m.round(pos * 3);
                    if (size < 8) size = 8;
                    that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px';
                }
                pos = 0;
            } else if (pos > that[dir + 'ScrollbarMaxScroll']) {
                if (!that.options.fixedScrollbar) {
                    size = that[dir + 'ScrollbarIndicatorSize'] - m.round((pos - that[dir + 'ScrollbarMaxScroll']) * 3);
                    if (size < 8) size = 8;
                    that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px';
                    pos = that[dir + 'ScrollbarMaxScroll'] + (that[dir + 'ScrollbarIndicatorSize'] - size);
                } else {
                    pos = that[dir + 'ScrollbarMaxScroll'];
                }
            }

            that[dir + 'ScrollbarWrapper'].style[transitionDelay] = '0';
            that[dir + 'ScrollbarWrapper'].style.opacity = hidden && that.options.hideScrollbar ? '0' : '1';
            that[dir + 'ScrollbarIndicator'].style[transform] = 'translate(' + (dir == 'h' ? pos + 'px,0)' : '0,' + pos + 'px)') + translateZ;
        },

        _start:function (e) {
            var that = this,
                point = hasTouch ? e.touches[0] : e,
                matrix, x, y,
                c1, c2;

            if (!that.enabled) return;

            if (that.options.onBeforeScrollStart) that.options.onBeforeScrollStart.call(that, e);

            if (that.options.useTransition || that.options.zoom) that._transitionTime(0);

            that.moved = false;
            that.animating = false;
            that.zoomed = false;
            that.distX = 0;
            that.distY = 0;
            that.absDistX = 0;
            that.absDistY = 0;
            that.dirX = 0;
            that.dirY = 0;

            // Gesture start
            if (that.options.zoom && hasTouch && e.touches.length > 1) {
                c1 = m.abs(e.touches[0].pageX - e.touches[1].pageX);
                c2 = m.abs(e.touches[0].pageY - e.touches[1].pageY);
                that.touchesDistStart = m.sqrt(c1 * c1 + c2 * c2);

                that.originX = m.abs(e.touches[0].pageX + e.touches[1].pageX - that.wrapperOffsetLeft * 2) / 2 - that.x;
                that.originY = m.abs(e.touches[0].pageY + e.touches[1].pageY - that.wrapperOffsetTop * 2) / 2 - that.y;

                if (that.options.onZoomStart) that.options.onZoomStart.call(that, e);
            }

            if (that.options.momentum) {
                if (that.options.useTransform) {
                    // Very lame general purpose alternative to CSSMatrix
                    matrix = getComputedStyle(that.scroller, null)[transform].replace(/[^0-9\-.,]/g, '').split(',');
                    x = +(matrix[12] || matrix[4]);
                    y = +(matrix[13] || matrix[5]);
                } else {
                    x = +getComputedStyle(that.scroller, null).left.replace(/[^0-9-]/g, '');
                    y = +getComputedStyle(that.scroller, null).top.replace(/[^0-9-]/g, '');
                }

                if (x != that.x || y != that.y) {
                    if (that.options.useTransition) that._unbind(TRNEND_EV);
                    else cancelFrame(that.aniTime);
                    that.steps = [];
                    that._pos(x, y);
                    if (that.options.onScrollEnd) that.options.onScrollEnd.call(that);
                }
            }

            that.absStartX = that.x;	// Needed by snap threshold
            that.absStartY = that.y;

            that.startX = that.x;
            that.startY = that.y;
            that.pointX = point.pageX;
            that.pointY = point.pageY;

            that.startTime = e.timeStamp || Date.now();

            if (that.options.onScrollStart) that.options.onScrollStart.call(that, e);

            that._bind(MOVE_EV, window);
            that._bind(END_EV, window);
            that._bind(CANCEL_EV, window);
        },

        _move:function (e) {
            var that = this,
                point = hasTouch ? e.touches[0] : e,
                deltaX = point.pageX - that.pointX,
                deltaY = point.pageY - that.pointY,
                newX = that.x + deltaX,
                newY = that.y + deltaY,
                c1, c2, scale,
                timestamp = e.timeStamp || Date.now();

            if (that.options.onBeforeScrollMove) that.options.onBeforeScrollMove.call(that, e);

            // Zoom
            if (that.options.zoom && hasTouch && e.touches.length > 1) {
                c1 = m.abs(e.touches[0].pageX - e.touches[1].pageX);
                c2 = m.abs(e.touches[0].pageY - e.touches[1].pageY);
                that.touchesDist = m.sqrt(c1 * c1 + c2 * c2);

                that.zoomed = true;

                scale = 1 / that.touchesDistStart * that.touchesDist * this.scale;

                if (scale < that.options.zoomMin) scale = 0.5 * that.options.zoomMin * Math.pow(2.0, scale / that.options.zoomMin);
                else if (scale > that.options.zoomMax) scale = 2.0 * that.options.zoomMax * Math.pow(0.5, that.options.zoomMax / scale);

                that.lastScale = scale / this.scale;

                newX = this.originX - this.originX * that.lastScale + this.x,
                    newY = this.originY - this.originY * that.lastScale + this.y;

                this.scroller.style[transform] = 'translate(' + newX + 'px,' + newY + 'px) scale(' + scale + ')' + translateZ;

                if (that.options.onZoom) that.options.onZoom.call(that, e);
                return;
            }

            that.pointX = point.pageX;
            that.pointY = point.pageY;

// Slow down if outside of the boundaries
            if (newX > 0 || newX < that.maxScrollX) {
                newX = that.options.bounce ? that.x + (deltaX / 2) : newX >= 0 || that.maxScrollX >= 0 ? 0 : that.maxScrollX;
            }
            if (newY > that.minScrollY || newY < that.maxScrollY) {
                newY = that.options.bounce ? that.y + (deltaY / 2) : newY >= that.minScrollY || that.maxScrollY >= 0 ? that.minScrollY : that.maxScrollY;
            }

            that.distX += deltaX;
            that.distY += deltaY;
            that.absDistX = m.abs(that.distX);
            that.absDistY = m.abs(that.distY);

            if (that.absDistX < 6 && that.absDistY < 6) {
                return;
            }


            // Lock direction
            if (that.options.lockDirection) {
                if (that.absDistX > that.absDistY + 5) {
                    newY = that.y;
                    deltaY = 0;
                } else if (that.absDistY > that.absDistX + 5) {
                    newX = that.x;
                    deltaX = 0;
                }
            }

            that.moved = true;
            that._pos(newX, newY);
            that.dirX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0;
            that.dirY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0;

            if (timestamp - that.startTime > 300) {
                that.startTime = timestamp;
                that.startX = that.x;
                that.startY = that.y;
            }

            if (that.options.onScrollMove) that.options.onScrollMove.call(that, e);
        },

        esc_html_end:function (e) {
            if (hasTouch && e.touches.length !== 0) return;

            var that = this,
                point = hasTouch ? e.changedTouches[0] : e,
                target, ev,
                momentumX = { dist:0, time:0 },
                momentumY = { dist:0, time:0 },
                duration = (e.timeStamp || Date.now()) - that.startTime,
                newPosX = that.x,
                newPosY = that.y,
                distX, distY,
                newDuration,
                snap,
                scale;

            that._unbind(MOVE_EV, window);
            that._unbind(END_EV, window);
            that._unbind(CANCEL_EV, window);

            if (that.options.onBeforeScrollEnd) that.options.onBeforeScrollEnd.call(that, e);

            if (that.zoomed) {
                scale = that.scale * that.lastScale;
                scale = Math.max(that.options.zoomMin, scale);
                scale = Math.min(that.options.zoomMax, scale);
                that.lastScale = scale / that.scale;
                that.scale = scale;

                that.x = that.originX - that.originX * that.lastScale + that.x;
                that.y = that.originY - that.originY * that.lastScale + that.y;

                that.scroller.style[transitionDuration] = '200ms';
                that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + that.scale + ')' + translateZ;

                that.zoomed = false;
                that.refresh();

                if (that.options.onZoomEnd) that.options.onZoomEnd.call(that, e, scale);
                return;
            }

            if (!that.moved) {
                //custom - double click and double tap
                if (true) {
//                    if (hasTouch) {
                    if (that.doubleTapTimer && that.options.zoom) {
                        // Double tapped
                        clearTimeout(that.doubleTapTimer);
                        that.doubleTapTimer = null;

                        if (that.options.onZoomStart) that.options.onZoomStart.call(that, e);
//                            that.zoom(that.pointX, that.pointY, that.scale == 1 ? that.options.doubleTapZoom : 1);
                        //custom    - double tap zoom disabled
//                        that.zoom(that.pointX, that.pointY, that.scale < that.options.zoomMin * that.options.doubleTapZoom ? that.options.zoomMin * that.options.doubleTapZoom : that.options.zoomMin);
                        if (that.options.onZoomEnd) {
                            setTimeout(function () {
                                that.options.onZoomEnd.call(that, e, scale);
                            }, 200); // 200 is default zoom duration
                        }
                    } else if (this.options.handleClick) {
                        //first tap
                        that.doubleTapTimer = setTimeout(function () {
                            that.doubleTapTimer = null;

                            // Find the last touched element
                            target = point.target;
                            while (target.nodeType != 1) target = target.parentNode;

                            if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA') {
                                ev = doc.createEvent('MouseEvents');
                                ev.initMouseEvent('click', true, true, e.view, 1,
                                    point.screenX, point.screenY, point.clientX, point.clientY,
                                    e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
                                    0, null);
                                ev._fake = true;
                                target.dispatchEvent(ev);
                            }

                            //custom - first tap, after timeout
                            if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                        }, that.options.zoom ? 250 : 0);
                    }
                }

                that._resetPos(400);
                //custom - call only if no double tap
                //                    if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            if (duration < 300 && that.options.momentum) {
                momentumX = newPosX ? that._momentum(newPosX - that.startX, duration, -that.x, that.scrollerW - that.wrapperW + that.x, that.options.bounce ? that.wrapperW : 0) : momentumX;
                momentumY = newPosY ? that._momentum(newPosY - that.startY, duration, -that.y, (that.maxScrollY < 0 ? that.scrollerH - that.wrapperH + that.y - that.minScrollY : 0), that.options.bounce ? that.wrapperH : 0) : momentumY;

                newPosX = that.x + momentumX.dist;
                newPosY = that.y + momentumY.dist;

                if ((that.x > 0 && newPosX > 0) || (that.x < that.maxScrollX && newPosX < that.maxScrollX)) momentumX = { dist:0, time:0 };
                if ((that.y > that.minScrollY && newPosY > that.minScrollY) || (that.y < that.maxScrollY && newPosY < that.maxScrollY)) momentumY = { dist:0, time:0 };
            }

            if (momentumX.dist || momentumY.dist) {
                newDuration = m.max(m.max(momentumX.time, momentumY.time), 10);

                // Do we need to snap?
                if (that.options.snap) {
                    distX = newPosX - that.absStartX;
                    distY = newPosY - that.absStartY;
                    if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) {
                        that.scrollTo(that.absStartX, that.absStartY, 200);
                    }
                    else {
                        snap = that._snap(newPosX, newPosY);
                        newPosX = snap.x;
                        newPosY = snap.y;
                        newDuration = m.max(snap.time, newDuration);
                    }
                }

                that.scrollTo(m.round(newPosX), m.round(newPosY), newDuration);

                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            // Do we need to snap?
            if (that.options.snap) {
                distX = newPosX - that.absStartX;
                distY = newPosY - that.absStartY;
                if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) that.scrollTo(that.absStartX, that.absStartY, 200);
                else {
                    snap = that._snap(that.x, that.y);
                    if (snap.x != that.x || snap.y != that.y) that.scrollTo(snap.x, snap.y, snap.time);
                }

                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            that._resetPos(200);
            if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
        },

        _resetPos:function (time) {
            var that = this;
            //custom : stay in center
            if (that.keepInCenterH && that.scrollerW < that.wrapperW) {
                resetX = that.x >= 0 ? (that.wrapperW - that.scrollerW) / 2 : that.x < that.maxScrollX ? that.maxScrollX : that.x;
            }
            else
                resetX = that.x >= 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x;

            if (that.keepInCenterV && that.scrollerH < that.wrapperH) {
                resetY = that.y >= that.minScrollY || that.maxScrollY > 0 ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;
                resetY = that.y > 0 ? (that.wrapperH - that.scrollerH) / 2 : resetY;
            }

            else
                resetY = that.y >= that.minScrollY || that.maxScrollY > 0 ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;

            if (resetX == that.x && resetY == that.y) {
                if (that.moved) {
                    that.moved = false;
                    if (that.options.onScrollEnd) that.options.onScrollEnd.call(that);		// Execute custom code on scroll end
                }

                if (that.hScrollbar && that.options.hideScrollbar) {
                    if (vendor == 'webkit') that.hScrollbarWrapper.style[transitionDelay] = '300ms';
                    that.hScrollbarWrapper.style.opacity = '0';
                }
                if (that.vScrollbar && that.options.hideScrollbar) {
                    if (vendor == 'webkit') that.vScrollbarWrapper.style[transitionDelay] = '300ms';
                    that.vScrollbarWrapper.style.opacity = '0';
                }

                return;
            }

            that.scrollTo(resetX, resetY, time || 0);
        },

        _wheel:function (e) {
            var that = this,
                wheelDeltaX, wheelDeltaY,
                deltaX, deltaY,
                deltaScale;

            if ('wheelDeltaX' in e) {
                wheelDeltaX = e.wheelDeltaX / 3;
                wheelDeltaY = e.wheelDeltaY / 3;
            } else if ('wheelDelta' in e) {
                wheelDeltaX = wheelDeltaY = e.wheelDelta / 3;
            } else if ('detail' in e) {
                wheelDeltaX = wheelDeltaY = -e.detail * 3;
            } else {
                return;
            }
            if (that.options.wheelHorizontal && vendor == 'webkit')
            {
                if (wheelDeltaY != 0)
                {
                    wheelDeltaX = wheelDeltaY;
                    console.log(wheelDeltaX,wheelDeltaY)
                 }

            }
            if (that.options.wheelAction == 'zoom') {
                deltaScale = that.scale * Math.pow(2, 1 / 3 * (wheelDeltaY ? wheelDeltaY / Math.abs(wheelDeltaY) : 0));
                if (deltaScale < that.options.zoomMin) deltaScale = that.options.zoomMin;
                if (deltaScale > that.options.zoomMax) deltaScale = that.options.zoomMax;

                if (deltaScale != that.scale) {
                    if (!that.wheelZoomCount && that.options.onZoomStart) that.options.onZoomStart.call(that, e);
                    that.wheelZoomCount++;

                    that.zoom(e.pageX, e.pageY, deltaScale, 400);

                    setTimeout(function () {
                        that.wheelZoomCount--;
                        if (!that.wheelZoomCount && that.options.onZoomEnd) that.options.onZoomEnd.call(that, e, that.scale);
                    }, 400);
                }

                return;
            }

            deltaX = that.x + wheelDeltaX;
            deltaY = that.y + wheelDeltaY;

            if (deltaX > 0) deltaX = 0;
            else if (deltaX < that.maxScrollX) deltaX = that.maxScrollX;

            if (deltaY > that.minScrollY) deltaY = that.minScrollY;
            else if (deltaY < that.maxScrollY) deltaY = that.maxScrollY;

            if (that.options.wheelHorizontal && that.maxScrollX < 0) {
                      that.scrollTo(deltaX, deltaY, 0);
                      e.preventDefault();
                      return;
                    }

            if (that.maxScrollY < 0) {
                that.scrollTo(deltaX, deltaY, 0);
            }
        },

        _transitionEnd:function (e) {
            var that = this;

            if (e.target != that.scroller) return;

            that._unbind(TRNEND_EV);

            that._startAni();
        },


        /**
         *
         * Utilities
         *
         */
        _startAni:function () {
            var that = this,
                startX = that.x, startY = that.y,
                startTime = Date.now(),
                step, easeOut,
                animate;

            if (that.animating) return;

            if (!that.steps.length) {
                that._resetPos(400);
                return;
            }

            step = that.steps.shift();

            if (step.x == startX && step.y == startY) step.time = 0;

            that.animating = true;
            that.moved = true;

            if (that.options.useTransition) {
                that._transitionTime(step.time);
                that._pos(step.x, step.y);
                that.animating = false;
                if (step.time) that._bind(TRNEND_EV);
                else that._resetPos(0);
                return;
            }

            animate = function () {
                var now = Date.now(),
                    newX, newY;

                if (now >= startTime + step.time) {
                    that._pos(step.x, step.y);
                    that.animating = false;
                    if (that.options.onAnimationEnd) that.options.onAnimationEnd.call(that);			// Execute custom code on animation end
                    that._startAni();
                    return;
                }

                now = (now - startTime) / step.time - 1;
                easeOut = m.sqrt(1 - now * now);
                newX = (step.x - startX) * easeOut + startX;
                newY = (step.y - startY) * easeOut + startY;
                that._pos(newX, newY);
                if (that.animating) that.aniTime = nextFrame(animate);
            };

            animate();
        },

        _transitionTime:function (time) {
            time += 'ms';
            this.scroller.style[transitionDuration] = time;
            if (this.hScrollbar) this.hScrollbarIndicator.style[transitionDuration] = time;
            if (this.vScrollbar) this.vScrollbarIndicator.style[transitionDuration] = time;
        },

        _momentum:function (dist, time, maxDistUpper, maxDistLower, size) {
            var deceleration = 0.0006,
                speed = m.abs(dist) / time,
                newDist = (speed * speed) / (2 * deceleration),
                newTime = 0, outsideDist = 0;

            // Proportinally reduce speed if we are outside of the boundaries
            if (dist > 0 && newDist > maxDistUpper) {
                outsideDist = size / (6 / (newDist / speed * deceleration));
                maxDistUpper = maxDistUpper + outsideDist;
                speed = speed * maxDistUpper / newDist;
                newDist = maxDistUpper;
            } else if (dist < 0 && newDist > maxDistLower) {
                outsideDist = size / (6 / (newDist / speed * deceleration));
                maxDistLower = maxDistLower + outsideDist;
                speed = speed * maxDistLower / newDist;
                newDist = maxDistLower;
            }

            newDist = newDist * (dist < 0 ? -1 : 1);
            newTime = speed / deceleration;

            return { dist:newDist, time:m.round(newTime) };
        },

        _offset:function (el) {
            var left = -el.offsetLeft,
                top = -el.offsetTop;

            while (el = el.offsetParent) {
                left -= el.offsetLeft;
                top -= el.offsetTop;
            }

            if (el != this.wrapper) {
                left *= this.scale;
                top *= this.scale;
            }

            return { left:left, top:top };
        },

        _snap:function (x, y) {
            var that = this,
                i, l,
                page, time,
                sizeX, sizeY;

            // Check page X
            page = that.pagesX.length - 1;
            //custom - fix for loop
            l = that.pagesX.length;
            for (i = 0; i < l; i++) {
//            for (i = 0, l = that.pagesX.length; i < l; i++) {
                if (x >= that.pagesX[i]) {
                    page = i;
                    break;
                }
            }
            if (page == that.currPageX && page > 0 && that.dirX < 0) page--;
            x = that.pagesX[page];
            sizeX = m.abs(x - that.pagesX[that.currPageX]);
            sizeX = sizeX ? m.abs(that.x - x) / sizeX * 500 : 0;
            that.currPageX = page;

            // Check page Y
            page = that.pagesY.length - 1;
            for (i = 0; i < page; i++) {
                if (y >= that.pagesY[i]) {
                    page = i;
                    break;
                }
            }
            if (page == that.currPageY && page > 0 && that.dirY < 0) page--;
            y = that.pagesY[page];
            sizeY = m.abs(y - that.pagesY[that.currPageY]);
            sizeY = sizeY ? m.abs(that.y - y) / sizeY * 500 : 0;
            that.currPageY = page;

            // Snap with constant speed (proportional duration)
            time = m.round(m.max(sizeX, sizeY)) || 200;

            return { x:x, y:y, time:time };
        },

        _bind:function (type, el, bubble) {
            (el || this.scroller).addEventListener(type, this, !!bubble);
        },

        _unbind:function (type, el, bubble) {
            (el || this.scroller).removeEventListener(type, this, !!bubble);
        },


        /**
         *
         * Public methods
         *
         */
        destroy:function () {
            var that = this;

            that.scroller.style[transform] = '';

            // Remove the scrollbars
            that.hScrollbar = false;
            that.vScrollbar = false;
            that._scrollbar('h');
            that._scrollbar('v');

            // Remove the event listeners
            that._unbind(RESIZE_EV, window);
            that._unbind(START_EV);
            that._unbind(MOVE_EV, window);
            that._unbind(END_EV, window);
            that._unbind(CANCEL_EV, window);

            if (!that.options.hasTouch) {
                that._unbind('DOMMouseScroll');
                that._unbind('mousewheel');
            }

            if (that.options.useTransition) that._unbind(TRNEND_EV);

            if (that.options.checkDOMChanges) clearInterval(that.checkDOMTime);

            if (that.options.onDestroy) that.options.onDestroy.call(that);
        },

        refresh:function () {
            var that = this,
                offset,
                i, l,
                els,
                pos = 0,
                page = 0;

            if (that.scale < that.options.zoomMin) that.scale = that.options.zoomMin;
            that.wrapperW = that.wrapper.clientWidth || 1;
            that.wrapperH = that.wrapper.clientHeight || 1;

            that.minScrollY = -that.options.topOffset || 0;
            that.scrollerW = m.round(that.scroller.offsetWidth * that.scale);
            that.scrollerH = m.round((that.scroller.offsetHeight + that.minScrollY) * that.scale);
            that.maxScrollX = that.wrapperW - that.scrollerW;
            that.maxScrollY = that.wrapperH - that.scrollerH + that.minScrollY;
            that.dirX = 0;
            that.dirY = 0;

            if (that.options.onRefresh) that.options.onRefresh.call(that);

            that.hScroll = that.options.hScroll && that.maxScrollX < 0;
            that.vScroll = that.options.vScroll && (!that.options.bounceLock && !that.hScroll || that.scrollerH > that.wrapperH);

            that.hScrollbar = that.hScroll && that.options.hScrollbar;
            that.vScrollbar = that.vScroll && that.options.vScrollbar && that.scrollerH > that.wrapperH;

            offset = that._offset(that.wrapper);
            that.wrapperOffsetLeft = -offset.left;
            that.wrapperOffsetTop = -offset.top;

            // Prepare snap
            if (typeof that.options.snap == 'string') {
                that.pagesX = [];
                that.pagesY = [];
                els = that.scroller.querySelectorAll(that.options.snap);
                //custom - fix for loop
                l = els.length;
                for (i = 0; i < l; i++) {
                    pos = that._offset(els[i]);
                    pos.left += that.wrapperOffsetLeft;
                    pos.top += that.wrapperOffsetTop;
                    that.pagesX[i] = pos.left < that.maxScrollX ? that.maxScrollX : pos.left * that.scale;
                    that.pagesY[i] = pos.top < that.maxScrollY ? that.maxScrollY : pos.top * that.scale;
                }
            } else if (that.options.snap) {
                that.pagesX = [];
                while (pos >= that.maxScrollX) {
                    that.pagesX[page] = pos;
                    pos = pos - that.wrapperW;
                    page++;
                }
                if (that.maxScrollX % that.wrapperW) that.pagesX[that.pagesX.length] = that.maxScrollX - that.pagesX[that.pagesX.length - 1] + that.pagesX[that.pagesX.length - 1];

                pos = 0;
                page = 0;
                that.pagesY = [];
                while (pos >= that.maxScrollY) {
                    that.pagesY[page] = pos;
                    pos = pos - that.wrapperH;
                    page++;
                }
                if (that.maxScrollY % that.wrapperH) that.pagesY[that.pagesY.length] = that.maxScrollY - that.pagesY[that.pagesY.length - 1] + that.pagesY[that.pagesY.length - 1];
            }

            // Prepare the scrollbars
            that._scrollbar('h');
            that._scrollbar('v');

            if (!that.zoomed) {
                that.scroller.style[transitionDuration] = '0';
                that._resetPos(400);
            }
        },

        scrollTo:function (x, y, time, relative) {
            var that = this,
                step = x,
                i, l;

            that.stop();

            if (!step.length) step = [
                { x:x, y:y, time:time, relative:relative }
            ];
             //custom - fix for loop
            l = step.length;
            for (i = 0; i < l; i++) {
                if (step[i].relative) {
                    step[i].x = that.x - step[i].x;
                    step[i].y = that.y - step[i].y;
                }
                that.steps.push({ x:step[i].x, y:step[i].y, time:step[i].time || 0 });
            }

            that._startAni();
        },

        scrollToElement:function (el, time) {
            var that = this, pos;
            el = el.nodeType ? el : that.scroller.querySelector(el);
            if (!el) return;

            pos = that._offset(el);
            pos.left += that.wrapperOffsetLeft;
            pos.top += that.wrapperOffsetTop;

            pos.left = pos.left > 0 ? 0 : pos.left < that.maxScrollX ? that.maxScrollX : pos.left;
            pos.top = pos.top > that.minScrollY ? that.minScrollY : pos.top < that.maxScrollY ? that.maxScrollY : pos.top;
            time = time === undefined ? m.max(m.abs(pos.left) * 2, m.abs(pos.top) * 2) : time;

            that.scrollTo(pos.left, pos.top, time);
        },

        scrollToPage:function (pageX, pageY, time) {
            var that = this, x, y;

            time = time === undefined ? 400 : time;

            if (that.options.onScrollStart) that.options.onScrollStart.call(that);

            if (that.options.snap) {
                pageX = pageX == 'next' ? that.currPageX + 1 : pageX == 'prev' ? that.currPageX - 1 : pageX;
                pageY = pageY == 'next' ? that.currPageY + 1 : pageY == 'prev' ? that.currPageY - 1 : pageY;

                pageX = pageX < 0 ? 0 : pageX > that.pagesX.length - 1 ? that.pagesX.length - 1 : pageX;
                pageY = pageY < 0 ? 0 : pageY > that.pagesY.length - 1 ? that.pagesY.length - 1 : pageY;

                that.currPageX = pageX;
                that.currPageY = pageY;
                x = that.pagesX[pageX];
                y = that.pagesY[pageY];
            } else {
                x = -that.wrapperW * pageX;
                y = -that.wrapperH * pageY;
                if (x < that.maxScrollX) x = that.maxScrollX;
                if (y < that.maxScrollY) y = that.maxScrollY;
            }

            that.scrollTo(x, y, time);
        },

        disable:function () {
            this.stop();
            this._resetPos(0);
            this.enabled = false;

            // If disabled after touchstart we make sure that there are no left over events
            this._unbind(MOVE_EV, window);
            this._unbind(END_EV, window);
            this._unbind(CANCEL_EV, window);
        },

        enable:function () {
            this.enabled = true;
        },

        stop:function () {
            if (this.options.useTransition) this._unbind(TRNEND_EV);
            else cancelFrame(this.aniTime);
            this.steps = [];
            this.moved = false;
            this.animating = false;
        },

        zoom:function (x, y, scale, time) {
            var that = this,
                relScale = scale / that.scale;

            if (!that.options.useTransform) return;

            that.zoomed = true;
            time = time === undefined ? 200 : time;
            x = x - that.wrapperOffsetLeft - that.x;
            y = y - that.wrapperOffsetTop - that.y;
            that.x = x - x * relScale + that.x;
            that.y = y - y * relScale + that.y;

            that.scale = scale;
            that.refresh();

            that.x = that.x > 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x;
            that.y = that.y > that.minScrollY ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;

            //custom   - fix for inner container to be in the middle
            if (that.keepInCenterH) {
                if (that.scrollerW < that.wrapperW) {
                    that.x = (that.wrapperW - that.scrollerW) / 2;
                }
            }
            if (that.keepInCenterV) {
                if (that.scrollerH < that.wrapperH) {
                    that.y = (that.wrapperH - that.scrollerH) / 2;
                }
            }
            that.scroller.style[transitionDuration] = time + 'ms';
            that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + scale + ')' + translateZ;
            that.zoomed = false;
        },

        isReady:function () {
            return !this.moved && !this.zoomed && !this.animating;
        },

        // custom
        setZoomMin:function (value) {
            this.options.zoomMin = value;
        },
        setX:function (value) {
            this.x = value;
        }
    };

    function prefixStyle(style) {
        if (vendor === '') return style;

        style = style.charAt(0).toUpperCase() + style.substr(1);
        return vendor + style;
    }

    dummyStyle = null;	// for the sake of it

    if (typeof exports !== 'undefined') exports.iScroll = iScroll;
    else window.iScroll = iScroll;

})(window, document);


// This THREEx helper makes it easy to handle the fullscreen API
// * it hides the prefix for each browser
// * it hides the little discrepencies of the various vendor API
// * at the time of this writing (nov 2011) it is available in
//   [firefox nightly](http://blog.pearce.org.nz/2011/11/firefoxs-html-full-screen-api-enabled.html),
//   [webkit nightly](http://peter.sh/2011/01/javascript-full-screen-api-navigation-timing-and-repeating-css-gradients/) and
//   [chrome stable](http://updates.html5rocks.com/2011/10/Let-Your-Content-Do-the-Talking-Fullscreen-API).

//
// # Code

//

/** @namespace */
var THREEx      = THREEx        || {};
THREEx.FullScreen   = THREEx.FullScreen || {};

/**
 * test if it is possible to have fullscreen
 *
 * @returns {Boolean} true if fullscreen API is available, false otherwise
*/
THREEx.FullScreen.available = function()
{
    return this._hasWebkitFullScreen || this._hasMozFullScreen;
}

/**
 * test if fullscreen is currently activated
 *
 * @returns {Boolean} true if fullscreen is currently activated, false otherwise
*/
THREEx.FullScreen.activated = function()
{
    if( this._hasWebkitFullScreen ){
        return document.webkitIsFullScreen;
    }else if( this._hasMozFullScreen ){
        return document.mozFullScreen;
    }else{
        console.assert(false);
    }
}

/**
 * Request fullscreen on a given element
 * @param {DomElement} element to make fullscreen. optional. default to document.body
*/
THREEx.FullScreen.request   = function(element)
{
    element = element   || document.body;
    if( this._hasWebkitFullScreen ){
        element.webkitRequestFullScreen();
    }else if( this._hasMozFullScreen ){
        element.mozRequestFullScreen();
    }else{
        console.assert(false);
    }
}

/**
 * Cancel fullscreen
*/
THREEx.FullScreen.cancel    = function()
{
    if( this._hasWebkitFullScreen ){
        document.webkitCancelFullScreen();
    }else if( this._hasMozFullScreen ){
        document.mozCancelFullScreen();
    }else{
        console.assert(false);
    }
}

// internal functions to know which fullscreen API implementation is available
THREEx.FullScreen._hasWebkitFullScreen  = 'webkitCancelFullScreen' in document  ? true : false;
THREEx.FullScreen._hasMozFullScreen = 'mozCancelFullScreen' in document ? true : false;


(function($){

    $.fn.Video = function(options, callback)
    {
        return(new Video(this, options));
    };

var idleEvents = "mousemove keydown DOMMouseScroll mousewheel mousedown reset.idle";

var defaults = {
    autohideControls:4,                     //autohide HTML5 player controls
            videoPlayerWidth:746,                   //total player width
            videoPlayerHeight:420,                  //total player height
            playlist:"Right playlist",              //choose playlist type: "Right playlist","Bottom playlist", "Off"
            autoplay:false,                         //autoplay when webpage loads
            loadRandomVideoOnStart:"Yes",           //choose to load random video when webpage loads: "Yes", "No"
            playVideosRandomly:"Yes",               //choose to shuffle videos when playing one after another: "Yes", "No"
            vimeoColor:"F11116",                    //"hexadecimal value", default vimeo color  00adef
            videoPlayerShadow:"effect1",            //choose player shadow:  "effect1" , "effect2", "effect3", "effect4", "effect5", "effect6", "off"
            posterImg:"images/preview_images/3.jpg",//player poster image
            posterPlayButtonType:"Default",         //"Default","Classic","Minimal","Transparent","Silver"
            onFinish:"Play next video",             //"Play next video","Restart video", "Stop video",
            nowPlayingText:"Yes",                   //enable disable now playing title: "Yes","No"
            fullscreen:"Fullscreen native",         //choose fullscreen type: "Fullscreen native","Fullscreen browser"
            rightClickMenu:true,                    //enable/disable right click over player: true/false
            shareShow:"Yes",                        //enable/disable share option: "Yes","No"
            facebookLink:"http://codecanyon.net/",  //link to go when facebook button clicked
            twitterLink:"http://codecanyon.net/",   //link to go when twitter button clicked
            logoShow:"Yes",                         //"Yes","No"
            logoClickable:"Yes",                    //"Yes","No"
            logoPath:"images/logo/logo.png",        //path to logo image
            logoGoToLink:"http://codecanyon.net/",  //redirect to page when logo clicked
            logoPosition:"bottom-right",            //choose logo position: "bottom-right","bottom-left"
            embedShow:"Yes",                        //enable/disable embed option: "Yes","No"
            embedCodeSrc:"www.yourwebsite.com/player/index.html", //path to your video player on server
            embedCodeW:"746",                       //embed player code width
            embedCodeH:"420",                       //embed player code height
            videos:[
                {
                    videoType:"youtube",                                              //choose video type: "HTML5", "youtube", "vimeo"
                    title:"Youtube video",                                            //video title
                    youtubeID:"XMGoYNoMtOQ",                                          //https://www.youtube.com/watch?v=XMGoYNoMtOQ
                    vimeoID:"46515976",                                               //http://vimeo.com/46515976
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",//HTML5 video mp4 url
                    videoAdShow:"yes",                                                 //show pre-roll "yes","no"
                    videoAdGotoLink:"http://codecanyon.net/",                          //pre-roll goto link
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",   //pre-roll video mp4 format
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm", //pre-roll video webm format
                    description:"Video description goes here.",                        //video description
                    thumbImg:"images/thumbnail_images/pic3.jpg",                       //path to playlist thumbnail image
                    info:"Video info goes here"                                        //video info
                },
                {
                    videoType:"vimeo",
                    title:"Vimeo video",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                },
                {
                    videoType:"HTML5",
                    title:"Big Buck Bunny trailer",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                },
                {
                    videoType:"HTML5",
                    title:"Big Buck Bunny trailer",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                },
                {
                    videoType:"HTML5",
                    title:"Big Buck Bunny trailer",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                },
                {
                    videoType:"HTML5",
                    title:"Big Buck Bunny trailer",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                },
                {
                    videoType:"HTML5",
                    title:"Big Buck Bunny trailer",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                },
                {
                    videoType:"HTML5",
                    title:"Big Buck Bunny trailer",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                },
                {
                    videoType:"HTML5",
                    title:"Big Buck Bunny trailer",
                    youtubeID:"XMGoYNoMtOQ",
                    vimeoID:"46515976",
                    mp4:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.mp4",
                    webm:"http://player.pageflip.com.hr/videos/Big_Buck_Bunny_Trailer.webm",
                    videoAdShow:"yes",
                    videoAdGotoLink:"http://codecanyon.net/",
                    mp4AD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.mp4",
                    webmAD:"http://player.pageflip.com.hr/videos/Sintel_Trailer.webm",
                    description:"Video description goes here.",
                    thumbImg:"images/thumbnail_images/pic3.jpg",
                    info:"Video info goes here"
                }

            ]
//      controls: false,
//      preload:  "auto",
//      poster:   "",
//      srcs:     [],
//      keyShortcut: true,
//      xml: "xml/videoPlayer.xml"
};

var isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),
    hasTouch = 'ontouchstart' in window && !isTouchPad,
    RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize',
    CLICK_EV = hasTouch ? 'touchend' : 'click',
    START_EV = hasTouch ? 'touchstart' : 'mousedown',
    MOVE_EV = hasTouch ? 'touchmove' : 'mousemove',
    END_EV = hasTouch ? 'touchend' : 'mouseup';


var Video = function(parent, options)
{
    var self=this;
      this._class  = Video;
      this.parent  = parent;
      this.parentWidth = this.parent.width();
      this.parentHeight = this.parent.height();
      this.windowWidth = $(window).width();
      this.windowHeight = $(window).height();
      this.options = $.extend({}, defaults, options);
      this.sources = this.options.srcs || this.options.sources;
      this.state        = null;
      this.inFullScreen = false;
      this.realFullscreenActive=false;
      this.stretching = false;
      this.infoOn = false;
      this.adOn = false;
      this.textAdOn = false;
      this.shareOn = false;
      this.videoPlayingAD = false;
      this.embedOn = false;
      pw = false;
      this.loaded       = false;
      this.readyList    = [];
    this.videoAdStarted=false;
    this.ADTriggered=false;

    this.hasTouch = hasTouch;
    this.RESIZE_EV = RESIZE_EV;
    this.CLICK_EV = CLICK_EV;
    this.START_EV = START_EV;
    this.MOVE_EV = MOVE_EV;
    this.END_EV = END_EV;

    this.canPlay = false;
    this.myVideo = document.createElement('video');
    self.deviceAgent = navigator.userAgent.toLowerCase();
    self.agentID = self.deviceAgent.match(/(iphone|ipod)/);
// console.log("videoPlayer.js")
    //remove right-click menu
    /***$("#video").bind('contextmenu',function() { return false; });***/
    var tag = document.createElement('script');
//      tag.src = "https://www.youtube.com/player_api"; // Take the API address.
      tag.src = "https://www.youtube.com/iframe_api"; // Take the API address.
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag); // Include the API inside the page.

    /*var tag2 = document.createElement('script');
//    tag2.src = "http://a.vimeocdn.com/js/froogaloop2.min.js"; // Take the API address.
    tag2.src = "http://f.vimeocdn.com/js_opt/froogaloop2.min.js?bfeb60ee"; // Take the API address.
    var firstScriptTag2 = document.getElementsByTagName('script')[0];
    firstScriptTag2.parentNode.insertBefore(tag2, firstScriptTag2); // Include the API inside the page.*/
    // console.log(tag)
    /* $('<script src="http://www.youtube.com/player_api" />').appendTo("head"); */


    /*****this.loadFontAwesome("wp-content/plugins/video-player/css/font-awesome.css");
    this.loadCSSMain("wp-content/plugins/video-player/css/videoPlayerMain.css");

    if(this.options.skinPlayer == "Default"){
        this.loadCSSTheme("wp-content/plugins/video-player/css/videoPlayer.theme1.css");
    }
    if(this.options.skinPlaylist == "Default"){
        this.loadCSSThemePlaylist("wp-content/plugins/video-player/css/videoPlayer.theme1_Playlist.css");
    }
    if(this.options.skinPlayer == "Classic"){
        this.loadCSSTheme("wp-content/plugins/video-player/css/videoPlayer.theme2.css");
    }
    if(this.options.skinPlaylist == "Classic"){
        this.loadCSSThemePlaylist("wp-content/plugins/video-player/css/videoPlayer.theme2_Playlist.css");
    }
        if(this.options.skinPlayer == "Minimal"){
        this.loadCSSTheme("wp-content/plugins/video-player/css/videoPlayer.theme3.css");
    }
    if(this.options.skinPlaylist == "Minimal"){
        this.loadCSSThemePlaylist("wp-content/plugins/video-player/css/videoPlayer.theme3_Playlist.css");
    }
    if(this.options.skinPlayer == "Transparent"){
        this.loadCSSTheme("wp-content/plugins/video-player/css/videoPlayer.theme4.css");
    }
    if(this.options.skinPlaylist == "Transparent"){
        this.loadCSSThemePlaylist("wp-content/plugins/video-player/css/videoPlayer.theme4_Playlist.css");
    }
    if(this.options.skinPlayer == "Silver"){
        this.loadCSSTheme("wp-content/plugins/video-player/css/videoPlayer.theme5.css");
    }
    if(this.options.skinPlaylist == "Silver"){
        this.loadCSSThemePlaylist("wp-content/plugins/video-player/css/videoPlayer.theme5_Playlist.css");
    }

    self.checkCSS();********/
    if(!this.options.rightClickMenu)
        $("#video").bind('contextmenu',function() { return false; });
    self.setupElement();
    self.setupElementAD();
    self.init();

    // this.setupElement();
    // this.init();
    /***if(this.options  == undefined)
        self.loadXML('xml/test.xml');
    else if(this.options.xml != undefined)
    //if xml is defined - load xml and override options with xml values
        self.loadXML(this.options.xml);***/

};
Video.fn = Video.prototype;

Video.fn.loadCSSMain=function(url){
            $('#vpCSSMain').remove();
            var self = this;
            //append css to head tag
            $('<link rel="stylesheet" type="text/css" href="'+url+'" id="vpCSSMain" />').appendTo("head");
            //wait for css to load
            self.req1 = $.ajax({
                url:url,
                success:function(data){
                    //css is loaded
                    //start the app
                    // self.start();
                    // self.setupElement();
                    // self.init();
                }
            })
};
Video.fn.loadFontAwesome=function(url){
            $('#vpFontAwesome').remove();
            var self = this;
            //append css to head tag
            $('<link rel="stylesheet" type="text/css" href="'+url+'" id="vpFontAwesome" />').appendTo("head");
            //wait for css to load
            self.req4 = $.ajax({
                url:url,
                success:function(data){
                    //css is loaded
                    //start the app
                    // self.start();
                    // self.setupElement();
                    // self.init();
                }
            })
};
/*THEMES*/
Video.fn.loadCSSTheme=function(url){
            $('#vpCSSTheme').remove();
            var self = this;
            //append css to head tag
            $('<link rel="stylesheet" type="text/css" href="'+url+'" id="vpCSSTheme" />').appendTo("head");
            //wait for css to load
            self.req2 = $.ajax({
                url:url,
                success:function(data){
                    //css is loaded
                    //start the app
                    // self.start();
                    // self.setupElement();
                    // self.init();
                }
            })
};
Video.fn.loadCSSThemePlaylist=function(url){
            $('#vpCSSPlaylist').remove();
            var self = this;
            //append css to head tag
            $('<link rel="stylesheet" type="text/css" href="'+url+'" id="vpCSSPlaylist" />').appendTo("head");
            //wait for css to load
            self.req3 = $.ajax({
                url:url,
                success:function(data){
                    //css is loaded
                    //start the app
                    // self.start();
                    // self.setupElement();
                    // self.init();
                }
            })
};
Video.fn.checkCSS=function(url){
    var self = this;
    $.when(self.req1, self.req2, self.req3, self.req4).done(function() {
            // console.log("css loaded, font awesome loaded")
            self.setupElement();
            self.init();
    });
};

Video.fn.init = function init()
{
    var self=this;
    // console.log("init")

                self.preloader = $("<div />");
                self.preloader.addClass("ts_preloader");
                self.element.append(self.preloader);

                this.videoElement = $("<video />");
                this.videoElement.addClass("ts_videoPlayer");
                this.videoElement.attr({
                    width:this.options.width,
                    height:this.options.height,
            //            poster:this.options.poster,
                    autoplay:this.options.autoplay,
                    preload:this.options.preload,
                    controls:this.options.controls,
                    autobuffer:this.options.autobuffer
                });

                this.videoElementAD = $("<video />");
                this.videoElementAD.addClass("ts_videoPlayerAD");
                this.videoElementAD.attr({
                    width:this.options.width,
                    height:this.options.height,
            //        poster:this.options.poster,
                    autoplay:this.options.autoplay,
                    preload:this.options.preload,
                    controls:this.options.controls,
                    autobuffer:this.options.autobuffer
                });

                self._playlist = new PLAYER.Playlist($, self, self.options, self.mainContainer, self.element, self.preloader, self.myVideo, this.canPlay, self.CLICK_EV, pw, self.hasTouch, self.deviceAgent, self.agentID);

                if(self.options.playlist=="Right playlist")
                {
                    self.playerWidth = self.options.videoPlayerWidth - self._playlist.playlistW;
                    self.playerHeight = self.options.videoPlayerHeight;
                }
                else if(self.options.playlist=="Bottom playlist")
                {
                    self.playerWidth = self.options.videoPlayerWidth;
                    self.playerHeight = self.options.videoPlayerHeight - self._playlist.playlistH;
                }
                else if(self.options.playlist=="Off")
                {
                    self.playerWidth = self.options.videoPlayerWidth;
                    self.playerHeight = self.options.videoPlayerHeight;
                }

               ////// self.playerHeight = self.options.videoPlayerHeight;
                self.playlistWidth = self._playlist.playlistW;

                self.initPlayer();
                self.resize();
                self.resizeAll();
                // var offsetT=0;

//              self.playlist = $("<div />");
//              self.playlist.attr('id', 'ts_playlist');
//
//              self.playlistContent= $("<dl />");
//              self.playlistContent.attr('id', 'ts_playlistContent');
//
//              self.videos_array=new Array();
//              self.item_array=new Array();


//            for(var i = 0; i<self.options.video.length; i++)
//            {
//            }

//              var id=-1;
//
//                $(self.options.videos).each(function loopingItems()
//                {
//                id= id+1;
//                  var obj=
//                  {
//                      //id: this.id,
//                      id: id,
//                      title:this.title,
//                      video_path_mp4:this.mp4,
//                      video_path_webm:this.webm,
//                      // video_path_ogg:this.ogv,
//                      description:this.description,
//                      thumbnail_image:this.thumbImg,
//                      info_text: this.info
//                  };
//                //console.log("id",id)
//                  self.videos_array.push(obj);
//                  self.item = $("<div />");
//                  self.item.addClass("ts_item");
//                  self.playlistContent.append(self.item);
//                  self.item_array.push(self.item);
//
//                  itemUnselected = $("<div />");
//                  itemUnselected.addClass("ts_itemUnselected");
//                  self.item.append(itemUnselected);
//
//                  var itemLeft = '<div class="ts_itemLeft"><img class="ts_thumbnail_image" alt="" src="' + obj.thumbnail_image + '"></img></div>';
//                var itemRight = '<div class="ts_itemRight"><div class="ts_title">' + obj.title + '</div><div class="ts_description"> ' + obj.description + '</div></div>';
//                self.item.append(itemLeft);
//                self.item.append(itemRight);
//
//        //        offsetL += 252;
//                offsetT += 64;
//                self.playlistContent.append(self.item)
//
//                  //play new video
//                  self.item.bind(self.CLICK_EV, function()
//                  {
//                      if (self.scroll.moved)
//                      {
////                         console.log("scroll moved...")
//                          return;
//                      }
//                      if(self.preloader)
//                          self.preloader.stop().animate({opacity:1},0,function(){$(this).show()});
//                      self.resetPlayer();
//                      self.video.poster = "";
//                      if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
//                      {
//                          this.canPlay = true;
//                          self.video_path = obj.video_path_mp4;
//                      }
//                      else if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/webm').replace(/no/, ''))
//                      {
//                          this.canPlay = true;
//                          self.video_path = obj.video_path_webm;
//                      }
//                      // else if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/ogg').replace(/no/, ''))
//                      // {
//                          // this.canPlay = true;
//                          // self.video_path = obj.video_path_ogg;
//                      // }
//                      self.videoid = obj.id;
//                    //console.log(self.videoid);
//                      self.load(self.video_path);
//                      self.play();
//                      $(self.element).find(".ts_infoTitle").html(obj.title);
//                      $(self.element).find(".ts_infoText").html(obj.info_text);
//                      $(self.element).find(".ts_nowPlayingText").html(obj.title);
//                      this.loaded=false;
//
//                      self.element.find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//remove selected
//                      $(this).find(".ts_itemUnselected").removeClass("ts_itemUnselected").addClass("ts_itemSelected");
//                  });
//                });
//
//            //play first from playlist
//            $(self.item_array[0]).find(".ts_itemUnselected").removeClass("ts_itemUnselected").addClass("ts_itemSelected");//first selected
//                self.videoid = 0;
//                if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
//                {
//                    this.canPlay = true;
//                    self.video_path = self.videos_array[0].video_path_mp4;
//                }
//                else if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/webm').replace(/no/, ''))
//                {
//                    this.canPlay = true;
//                    self.video_path = self.videos_array[0].video_path_webm;
//                }
//                // else if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/ogg').replace(/no/, ''))
//                // {
//                    // this.canPlay = true;
//                    // self.video_path = self.videos_array[0].video_path_ogg;
//                // }
//                self.load(self.video_path);
//
//
//
//
//              //check if show playlist "on" or "off"
//              if(self.options.playlist)
//              {
//                  if( self.element){
//                      self.element.append(self.playlist);
//                      self.playlist.append(self.playlistContent);
//                  }
//                  self.playerWidth = self.options.videoPlayerWidth - self.playlist.width();
//              }
//              else
//                  self.playerWidth = self.options.videoPlayerWidth;
//
//
////              self.playerWidth = self.options.videoPlayerWidth - self.playlist.width();
//              self.playerHeight = self.options.videoPlayerHeight;
//
////              self.playlistFunctionality();
//              self.initPlayer();
//              /**self.animate();**/
//              self.resize();
//
//              self.resizeAll();

};

//Video.fn.playlistFunctionality = function()
//{
//    var self = this;
//
//    self.playlist.css({
//        left:self.playerWidth,
//        height:self.playerHeight
//    });
//
//
//    if(this.options.playlist)
//    {
//        self.scroll = new iScroll(self.playlist[0], {bounce:false, scrollbarClass: 'myScrollbar'});
//    }
//};

Video.fn.initPlayer = function()
{
    this.setupHTML5Video();
//    if(this.options.videoAdShow)
        this.setupHTML5VideoAD();

    this.ready($.proxy(function()
    {
        this.setupEvents();
        this.change("initial");
        this.setupControls();
        this.load();
        this.setupAutoplay();

        this.element.bind("idle", $.proxy(this.idle, this));
        this.element.bind("state.videoPlayer", $.proxy(function(){
            this.element.trigger("reset.idle");
        }, this))
    }, this));


    this.secondsFormat = function(sec)
    {
        if(isNaN(sec))
        {
            sec=0;
        }
        var rests  = [];

        var minutes = Math.floor( sec / 60 );
        var hours   = Math.floor( sec / 3600 );
        var seconds = (sec == 0) ? 0 : (sec % 60)
        seconds     = Math.round(seconds);

        //to calclate tooltip time
        var pad = function(num) {
            if (num < 10)
                return "0" + num;
            return num;
        }

        if (hours > 0)
            rests.push(pad(hours));

        rests.push(pad(minutes));
        rests.push(pad(seconds));

        return rests.join(":");
    };


    var self = this;

    $(window).resize(function() {

        if(!self.inFullScreen && !self.realFullscreenActive)
        {
            self.resizeAll();
        }

//        console.log("window.resize")
//        self.resizeAll();

    });

    $(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange',function(e)
    {
        //detecting real fullscreen change
        self.resize(e);
    });

    this.resize = function(e)
    {
//        console.log("this.resize")
//            console.log(document.fullscreenElement, document.mozFullScreen, document.webkitIsFullScreen )
        if(document.webkitIsFullScreen || document.fullscreenElement || document.mozFullScreen)
        {
            this._playlist.hidePlaylist();
            this.element.addClass("ts_fullScreen");
            this.elementAD.addClass("ts_fullScreen");
            $(this.controls).find(".icon-fullscreen").removeClass("icon-fullscreen").addClass("icon-resize-full");
            $(this.fsEnterADBox).find(".icon-resize-full").removeClass("icon-resize-full").addClass("icon-resize-full");
//            $(this.controls). find(".ts_fullScreenEnterBg").removeClass("ts_fullScreenEnterBg").addClass("ts_fullScreenExitBg");
            self.element.width("100%");
            self.element.height("100%");
            self.elementAD.width("100%");
            self.elementAD.height("100%");
//                $(this.controls). find(".videoTrack").css("width", 1350);
//            this.infoWindow.css({
//                bottom: self.controls.height()+5,
//                left: $(window).width()/2-this.infoWindow.width()/2
//            });
            self.realFullscreenActive=true;
        }

        else
        {
            this._playlist.showPlaylist();
            this.element.removeClass("ts_fullScreen");
            this.elementAD.removeClass("ts_fullScreen");
            $(this.controls). find(".icon-resize-full").removeClass("icon-resize-full").addClass("icon-fullscreen");
            $(this.fsEnterADBox). find(".icon-resize-full").removeClass("icon-resize-full").addClass("icon-resize-full");
//            $(this.controls). find(".ts_fullScreenExitBg").removeClass("ts_fullScreenExitBg").addClass("ts_fullScreenEnterBg");
            self.element.width(self.playerWidth);
            self.element.height(self.playerHeight);

            self.elementAD.width(self.playerWidth);
            self.elementAD.height(self.playerHeight);
//                $(this.controls). find(".videoTrack").css("width", 550);
//            this.infoWindow.css({
//                bottom: self.controls.height()+5,
//                left: self.playerWidth/2-this.infoWindow.width()/2
//            });

            if(this.stretching)
            {
                //back to stretched player
                this.stretching=false;
                this.toggleStretch();
            }

            self.element.css({zIndex:558 });

            if(self._playlist.videos_array[self._playlist.videoid].videoAdShow=="yes"){
                if(!self._playlist.videoAdPlayed && self.videoAdStarted){
                    self.elementAD.css({
                        zIndex:559
                    });
                }
                else{
                        self.elementAD.css({
                            zIndex:557
                        });
                }
            }

            self.realFullscreenActive=false;
            self.resizeAll();
        }
        this.resizeVideoTrack();
        this.positionOverScreenButtons();
        this.positionInfoWindow();
        this.positionAds();
        this.positionEmbedWindow();
        this.positionLogo();
        this.positionPoster();
        this.positionVideoAdBoxInside();
        this.positionSkipAdBox();
        this.positionToggleAdPlayBox();
//        this.positionAds();
//            console.log("fullscreen change");
//            console.log(e);
//            console.log(this);
//            $(this.playlist).toggle();
            //show playlist
       this.resizeBars();
       this.autohideControls();
    }
};
Video.fn.resizeAll = function(ytLoaded){
    var self = this;
//    console.log($(window).width())
    if(this.options.responsive){
        switch(self.options.playlist){
            case "Right playlist":
                if(this.stretching){
                    if(this.parent.width()<360)
                        this.videoTrack.hide();
                    else
                        this.videoTrack.show();
                    if(this.parent.width()<338)
                        this.sep2.hide();
                    else
                        this.sep2.show();
                    if(this.parent.width()<331)
                        this.rewindBtn.hide();
                    else
                        this.rewindBtn.show();
                    if(this.parent.width()<308)
                        this.sep3.hide();
                    else
                        this.sep3.show();
                    if(this.parent.width()<302)
                        this.infoBtn.hide();
                    else
                        this.infoBtn.show();
                    if(this.parent.width()<277)
                        this.sep4.hide();
                    else
                        this.sep4.show();
                    if(this.parent.width()<262)
                        this.timeElapsed.hide();
                    else
                        this.timeElapsed.show();
                    if(this.parent.width()>262){
                        this.timeTotal.show();
                        this.sep5.show();
                        this.unmuteBtn.show();
                        this.volumeTrack.show();
                        this.sep6.show();
                        this.fsEnter.show();
                    }
                }
                else{
                    if(this.parent.width()<439)
                        this.videoTrack.hide();
                    else
                        this.videoTrack.show();
                    if(this.parent.width()<411)
                        this.sep2.hide();
                    else
                        this.sep2.show();
                    if(this.parent.width()<406)
                        this.rewindBtn.hide();
                    else
                        this.rewindBtn.show();
                    if(this.parent.width()<381)
                        this.sep3.hide();
                    else
                        this.sep3.show();
                    if(this.parent.width()<375)
                        this.infoBtn.hide();
                    else
                        this.infoBtn.show();
                    if(this.parent.width()<350)
                        this.sep4.hide();
                    else
                        this.sep4.show();
                    if(this.parent.width()<335)
                        this.timeElapsed.hide();
                    else
                        this.timeElapsed.show();
                    if(this.parent.width()<302)
                        this.timeTotal.hide();
                    else
                        this.timeTotal.show();
                    if(this.parent.width()<287)
                        this.sep5.hide();
                    else
                        this.sep5.show();
                    //advertisement resize
                    if(this.parent.width()<460)
                    {
                        self.toggleAdPlayBox.css({width:33});
                        $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").hide();
                        this.videoAdBoxInside.css({width:132});
                        $(self.elementAD).find(".ts_adsTitleInside").html("Advertisement - ");
                    }
                    else
                    {
                        self.toggleAdPlayBox.css({width:97});
                        $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").show();
                        this.videoAdBoxInside.css({width:190});
                        $(self.elementAD).find(".ts_adsTitleInside").html("Your video will resume in");
                    }
                    //playlist resize
                    if(this.parent.width()<625){
                        self._playlist.playlist.css({width:75});
                        self.mainContainer.find(".ts_itemRight").hide();
                    }
                    else{
                        if(this.options.skinPlayer != "Transparent")
                        {
                            self._playlist.playlist.css({width:260});
                            self.mainContainer.find(".ts_itemRight").show();
                        }
                    }
                }
                break;
            case "Bottom playlist":
                if(this.parent.width()<364)
                    this.videoTrack.hide();
                else
                    this.videoTrack.show();
                if(this.parent.width()<336)
                    this.sep2.hide();
                else
                    this.sep2.show();
                if(this.parent.width()<329)
                    this.rewindBtn.hide();
                else
                    this.rewindBtn.show();
                if(this.parent.width()<306)
                    this.sep3.hide();
                else
                    this.sep3.show();
                if(this.parent.width()<301)
                    this.infoBtn.hide();
                else
                    this.infoBtn.show();
                if(this.parent.width()<275)
                    this.sep4.hide();
                else
                    this.sep4.show();
                if(this.parent.width()<260)
                    this.timeElapsed.hide();
                else
                    this.timeElapsed.show();
                break;
            case "Off":
                if(this.parent.width()<364)
                    this.videoTrack.hide();
                else
                    this.videoTrack.show();
                if(this.parent.width()<336)
                    this.sep2.hide();
                else
                    this.sep2.show();
                if(this.parent.width()<329)
                    this.rewindBtn.hide();
                else
                    this.rewindBtn.show();
                if(this.parent.width()<306)
                    this.sep3.hide();
                else
                    this.sep3.show();
                if(this.parent.width()<301)
                    this.infoBtn.hide();
                else
                    this.infoBtn.show();
                if(this.parent.width()<275)
                    this.sep4.hide();
                else
                    this.sep4.show();
                if(this.parent.width()<260)
                    this.timeElapsed.hide();
                else
                    this.timeElapsed.show();
                break;
        }
        if(this.stretching){
            if(self.options.playlist=="Right playlist"){
                self.element.width(self.parent.parent().width());
                self.element.height(self._playlist.playlist.height());
            }
            else if(self.options.playlist=="Bottom playlist"){
                self.element.width(self.parent.parent().width());
                self.element.height(self.parent.parent().height());
            }
            else if(self.options.playlist=="Off"){
                self.element.width(self.parent.parent().width());
                self.element.height(self.parent.parent().height());
            }
        }
        else{
            if(self.options.playlist=="Right playlist"){
                self.element.width(self.parent.parent().width()-self._playlist.playlist.width());
                self.element.height(self._playlist.playlist.height());
            }
            else if(self.options.playlist=="Bottom playlist"){
                self.element.width(self.parent.parent().width());
                self.element.height(self.parent.parent().height()-self._playlist.playlist.height());
            }
            else if(self.options.playlist=="Off"){
                self.element.width(self.parent.parent().width());
                self.element.height(self.parent.parent().height());
            }
        }
        self._playlist.resizePlaylist();
        self.elementAD.width(self.element.width());
        self.elementAD.height(self.element.height());

        if ((navigator.platform.indexOf("iPhone") != -1)&& self.options.videoType=="HTML5")
        {
            self.videoElement.width(self.element.width()-48);
            self.videoElementAD.width(self.element.width()-48);
            self.videoElement.height(self.element.height()-26);
            self.videoElementAD.height(self.element.height()-26);
        }

        self.positionEmbedWindow();
        self.positionInfoWindow();
        self.positionVideoAdBoxInside();
        self.positionSkipAdBox();
        self.positionToggleAdPlayBox();
        self.resizeVideoTrack();
        self.positionOverScreenButtons();
        self.positionAds();

        self.resizeBars();
        self.positionLogo();
        self.positionPoster();
        if ((navigator.platform.indexOf("iPhone") != -1)){
            //adjust positions for iPhone
            self.embedBtnClose.hide();
            self.infoBtnClose.hide();
            self.shareWindow.css({
                top:self.screenBtnsWindow.height(),
                right:13
            });
        }
    }

    else{//fixed width/height

    if ( $(window).width()- self.element.position().left < self.options.videoPlayerWidth )
    {
        switch(self.options.playlist){
            case "Right playlist":
                if(this.options.skinPlayer == "Transparent" && !this.stretching){
                    self.newPlayerWidth = $(window).width() - self.mainContainer.position().left;
                    if($(window).width() < 490)
                        self.videoTrack.hide();
                    else
                        self.videoTrack.show();
                    if($(window).width() < 458){
                        self.sep1.hide();
                        self.sep2.hide();
                    }
                    else{
                        self.sep1.show();
                        self.sep2.show();
                    }
                    if($(window).width() < 423)
                        self.rewindBtn.hide();
                    else
                        self.rewindBtn.show();
                    if($(window).width() < 411){
                        self.embedBtn.hide();
                    }
                    else
                        self.embedBtn.show();
                    if($(window).width() < 403)
                        self.sep3.hide();
                    else
                        self.sep3.show();
                    if($(window).width() < 394)
                        self.infoBtn.hide();
                    else
                        self.infoBtn.show();
                    if($(window).width() < 372)
                        self.sep4.hide();
                    else
                        self.sep4.show();
                    if($(window).width() < 352){
                        self.timeElapsed.hide();
                    }
                    else{
                        self.timeElapsed.show();
                    }
                    if($(window).width() < 315){
                        self.timeTotal.hide();
                    }
                    else{
                        self.timeTotal.show();
                    }
                    if($(window).width() < 255)
                        self.sep5.hide();
                    else
                        self.sep5.show();
                    if($(window).width() < 255)
                        self.newPlayerWidth = 255;
                }
                else//other skins
                {
                    //stretching
                    if(this.stretching){
                        self.newPlayerWidth = $(window).width() - self.mainContainer.position().left ;
                        if($(window).width() < 388)
                            self.videoTrack.hide();
                        else
                            self.videoTrack.show();
                        if($(window).width() < 359){
                            self.sep1.hide();
                            self.sep2.hide();
                        }
                        else{
                            self.sep1.show();
                            self.sep2.show();
                        }
                        if($(window).width() < 346)
                            self.rewindBtn.hide();
                        else
                            self.rewindBtn.show();
                        if($(window).width() < 264){
                            self.embedBtn.hide();
                        }
                        else
                            self.embedBtn.show();
                        if($(window).width() < 323)
                            self.sep3.hide();
                        else
                            self.sep3.show();
                        if($(window).width() < 310)
                            self.infoBtn.hide();
                        else
                            self.infoBtn.show();
                        if($(window).width() < 290)
                            self.sep4.hide();
                        else
                            self.sep4.show();
                        if($(window).width() < 270){
                            self.timeElapsed.hide();
                        }
                        else{
                            self.timeElapsed.show();
                        }
                        if($(window).width() < 236){
                            self.timeTotal.hide();
                        }
                        else{
                            self.timeTotal.show();
                        }
                        if($(window).width() < 213)
                            self.newPlayerWidth = 213;
                    }
                    //no stretching
                    else{
                        self.newPlayerWidth = $(window).width() - self.mainContainer.position().left;

                        if($(window).width() < 643)
                        {
                            self.videoTrack.hide();
                            self.infoWindow.css({width:"100%"})
                        }
                        else
                        {
                            self.videoTrack.show();
                            self.infoWindow.css({width:370})
                        }
                        if($(window).width() < 614){
                            self.sep1.hide();
                            self.sep2.hide();
                        }
                        else{
                            self.sep1.show();
                            self.sep2.show();
                        }
                        if($(window).width() < 604)
                            self.rewindBtn.hide();
                        else
                            self.rewindBtn.show();
                        if($(window).width() < 587){
                            self.embedBtn.hide();
                        }
                        else
                            self.embedBtn.show();
                        if($(window).width() < 581)
                            self.sep3.hide();
                        else
                            self.sep3.show();
                        if($(window).width() < 570)
                            self.infoBtn.hide();
                        else
                            self.infoBtn.show();
                        if($(window).width() < 552)
                            self.sep4.hide();
                        else
                            self.sep4.show();
                        if($(window).width() < 530){
                            self.timeElapsed.hide();
                        }
                        else{
                            self.timeElapsed.show();
                        }
                        if($(window).width() < 501){
                            self.timeTotal.hide();
                        }
                        else{
                            self.timeTotal.show();
                        }
                        if($(window).width() < 479)
                        {
                            self.toggleAdPlayBox.css({width:33});
                            $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").hide();
                        }
                        else
                        {
                            self.toggleAdPlayBox.css({width:97});
                            $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").show();
                        }
                        if($(window).width() < 435)
                            self.newPlayerWidth = 435;
                    }
                }
            break;

            case "Bottom playlist":
                self.newPlayerWidth = $(window).width() - self.mainContainer.position().left ;
                if($(window).width() < 388)
                    self.videoTrack.hide();
                else
                    self.videoTrack.show();
                if($(window).width() < 359){
                    self.sep1.hide();
                    self.sep2.hide();
                }
                else{
                    self.sep1.show();
                    self.sep2.show();
                }
                if($(window).width() < 346)
                    self.rewindBtn.hide();
                else
                    self.rewindBtn.show();
                if($(window).width() < 264){
                    self.embedBtn.hide();
                }
                else
                    self.embedBtn.show();
                if($(window).width() < 323)
                    self.sep3.hide();
                else
                    self.sep3.show();
                if($(window).width() < 310)
                    self.infoBtn.hide();
                else
                    self.infoBtn.show();
                if($(window).width() < 290)
                    self.sep4.hide();
                else
                    self.sep4.show();
                if($(window).width() < 270){
                    self.timeElapsed.hide();
                }
                else{
                    self.timeElapsed.show();
                }
                if($(window).width() < 236){
                    self.timeTotal.hide();
                }
                else{
                    self.timeTotal.show();
                }
                if($(window).width() < 213)
                    self.newPlayerWidth = 213;

            break;

            case "Off":
                self.newPlayerWidth = $(window).width() - self.mainContainer.position().left ;

                if($(window).width() < 388)
                    self.videoTrack.hide();
                else
                    self.videoTrack.show();
                if($(window).width() < 359){
                    self.sep1.hide();
                    self.sep2.hide();
                }
                else{
                    self.sep1.show();
                    self.sep2.show();
                }
                if($(window).width() < 346)
                    self.rewindBtn.hide();
                else
                    self.rewindBtn.show();
                if($(window).width() < 264){
                    self.embedBtn.hide();
                }
                else
                    self.embedBtn.show();
                if($(window).width() < 323)
                    self.sep3.hide();
                else
                    self.sep3.show();
                if($(window).width() < 310)
                    self.infoBtn.hide();
                else
                    self.infoBtn.show();
                if($(window).width() < 290)
                    self.sep4.hide();
                else
                    self.sep4.show();
                if($(window).width() < 270){
                    self.timeElapsed.hide();
                }
                else{
                    self.timeElapsed.show();
                }
                if($(window).width() < 236){
                    self.timeTotal.hide();
                }
                else{
                    self.timeTotal.show();
                }
                if($(window).width() < 213)
                    self.newPlayerWidth = 213;
            break;
            }
    }
    else
    {
        self.newPlayerWidth = self.options.videoPlayerWidth;
    }

    self.newPlayerHeight = self.newPlayerWidth * self.playerHeight / self.options.videoPlayerWidth;

    if(self.options.playlist=="Right playlist"){
        if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
        {
            self.videoElement.height(self.newPlayerHeight-26);
            self.videoElementAD.height(self.newPlayerHeight-26);
        }

        self.element.height(self.newPlayerHeight);
        self.mainContainer.css({width:self.newPlayerWidth, height:self.newPlayerHeight});
    }
    else if(self.options.playlist=="Bottom playlist"){
        if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
        {
            self.videoElement.width(self.newPlayerWidth-48);
            self.videoElementAD.width(self.newPlayerWidth-48);
        }

        self.element.width(self.newPlayerWidth);
        self.mainContainer.css({width:self.newPlayerWidth, height:self.newPlayerHeight+self._playlist.playlistH});
    }
    else if(self.options.playlist=="Off"){
        if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
        {
            self.videoElement.width(self.newPlayerWidth-48);
            self.videoElementAD.width(self.newPlayerWidth-48);
        }

        self.element.width(self.newPlayerWidth);
        if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
        {
            self.videoElement.height(self.newPlayerHeight-26);
            self.videoElementAD.height(self.newPlayerHeight-26);
        }

        self.element.height(self.newPlayerHeight);
        self.mainContainer.css({width:self.newPlayerWidth, height:self.newPlayerHeight});
    }


    if(this.stretching)
    {
        if(self.options.playlist=="Right playlist")
        {
            if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
            {
                self.videoElement.width(self.newPlayerWidth-48);
                self.videoElementAD.width(self.newPlayerWidth-48);
            }

            self.element.width(self.newPlayerWidth);
        }
        else if(self.options.playlist=="Bottom playlist")
        {
            if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
            {
                self.videoElement.height(self.newPlayerHeight + self._playlist.playlistH-26);
                self.videoElementAD.height(self.newPlayerHeight + self._playlist.playlistH-26);
            }

            self.element.height(self.newPlayerHeight + self._playlist.playlistH);
        }
        else if(self.options.playlist=="Off")
        {
            if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
            {
                self.videoElement.width(self.newPlayerWidth-48);
                self.videoElementAD.width(self.newPlayerWidth-48);
            }

            self.element.width(self.newPlayerWidth);
        }
    }
    else
    {
        if(self.options.playlist=="Right playlist")
        {
            if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
            {
                self.videoElement.width(self.newPlayerWidth- self._playlist.playlistW-48);
                self.videoElementAD.width(self.newPlayerWidth- self._playlist.playlistW-48);
            }

            self.element.width(self.newPlayerWidth- self._playlist.playlistW);
            self._playlist.resizePlaylist(self.newPlayerWidth, self.newPlayerHeight);

        }
        else if(self.options.playlist=="Bottom playlist")
        {
            if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
            {
                self.videoElement.height(self.newPlayerHeight-26);
                self.videoElementAD.height(self.newPlayerHeight-26);
            }

            self.element.height(self.newPlayerHeight);
            self._playlist.resizePlaylist(self.newPlayerWidth, self.newPlayerHeight);

        }
        else if(self.options.playlist=="Off")
        {
            if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
            {
                self.videoElement.width(self.newPlayerWidth-48);
                self.videoElementAD.width(self.newPlayerWidth-48);
            }

            self.element.width(self.newPlayerWidth);
        }
    }

    //resize videoad
    self.elementAD.width(self.element.width());
    self.elementAD.height(self.element.height());

//    self.videoElementAD.width(self.videoElement.width());
//    self.videoElementAD.height(self.videoElement.height());

    if ((navigator.platform.indexOf("iPhone") != -1)&& self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5") {
        //mobile code here if iphone/ipod
//        self.controls.css({bottom:-26, width:self.element.width()+48});
//        self.screenBtnsWindow.css({right:-48});
        if(self.playBtnScreen)
        self.playBtnScreen.hide();
    }
    else{
//        self.controls.css({bottom:0});
//        self.screenBtnsWindow.css({right:0});
    }
//    if(ytLoaded!= undefined){
//        self._playlist.ytWrapper.show();
//        if(self._playlist.videos_array[this._playlist.videoid].videoType=="youtube" )
//        {
            if(self._playlist.youtubePlayer!= undefined)
            {
                if(self.realFullscreenActive)
                {
                    self.element.width($(document).width());
                    self.element.height($(document).height());
                    self._playlist.youtubePlayer.setSize(self.element.width(),self.element.height() );
                }
                else
                {
                    switch(self.options.playlist){
                        case ("Right playlist"):
                            self._playlist.youtubePlayer.setSize(self.newPlayerWidth - self._playlist.playlistW,self.newPlayerHeight );
                            break;
                        case ("Bottom playlist"):
                            self._playlist.youtubePlayer.setSize(self.newPlayerWidth,self.newPlayerHeight );
                            break;
                        case ("Off"):
                            self._playlist.youtubePlayer.setSize(self.newPlayerWidth,self.newPlayerHeight );
                            break;
                    }

                }
            }
//        }
//    }



//    self._playlist.resizePlaylist(self.newPlayerWidth, self.newPlayerHeight);

    self.positionEmbedWindow();
    self.positionInfoWindow();
    self.positionAds();
    self.positionVideoAdBoxInside();
    self.positionSkipAdBox();
    self.positionToggleAdPlayBox();
    self.resizeVideoTrack();
    self.positionOverScreenButtons();
    self.positionAds();

    self.resizeBars();
//    self.resizeControls();
    self.positionLogo();
    self.positionPoster();

    if ((navigator.platform.indexOf("iPhone") != -1)){
        //adjust positions for iPhone
        self.embedBtnClose.hide();
        self.infoBtnClose.hide();
        self.shareWindow.css({
            top:self.screenBtnsWindow.height(),
            right:13
        });
//        self.skipAdBox.css({right:-59})
    }
    }
};
Video.fn.autohideControls = function(){
    var element  = $(this.element);
    var idle     = false;
    var timeout  = this.options.autohideControls*1000;
    var interval = 1000;
    var timeFromLastEvent = 0;
    var reset = function()
    {
        if (idle)
            element.trigger("idle", false);
        idle = false;
        timeFromLastEvent = 0;
    };

    var check = function()
    {
        if (timeFromLastEvent >= timeout) {
            reset();
            idle = true;
            element.trigger("idle", true);
        }
        else
        {
            timeFromLastEvent += interval;
        }
    };

    element.bind(idleEvents, reset);

    var loop = setInterval(check, interval);

    element.unload(function()
    {
        clearInterval(loop);
    });
};
Video.fn.resizeBars = function(){
    //download
//    this.buffered = this.video.buffered.end(this.video.buffered.length-1);
    this.downloadWidth = (this.buffered/this.video.duration )*this.videoTrack.width();
    this.videoTrackDownload.css("width", this.downloadWidth);
    //progress
    this.progressWidth = (this.video.currentTime/this.video.duration )*this.videoTrack.width();
    this.videoTrackProgress.css("width", this.progressWidth);

    this.progressWidthAD = (this.videoAD.currentTime/this.videoAD.duration )*this.elementAD.width();
    this.progressAD.css("width", this.progressWidthAD);
};
Video.fn.createLogo = function(){
        var self=this;
        //load logo
        this.logoImg = $("<div/>");
        this.logoImg.addClass("ts_logo");
//    var img = '<img class="" alt="" src="' + logoPath + '"></img>';
//    logoImg.append(img);
        this.img = new Image();
        this.img.src = self.options.logoPath;
        //
        $(this.img).load(function() {
            //when image loaded position logo
            self.logoImg.append(self.img);
            self.positionLogo();
        });

        if(self.options.logoShow=="Yes")
        {
            this.element.append(this.logoImg);
        }

        if(self.options.logoClickable=="Yes")
        {
            this.logoImg.bind(this.CLICK_EV,$.proxy(function(){
                window.open(self.options.logoGoToLink);
            }, this));

            this.logoImg.mouseover(function(){
                $(this).stop().animate({opacity:0.7},200);
            });
            this.logoImg.mouseout(function(){
                $(this).stop().animate({opacity:1},200);
            });
            $('.ts_logo').css('cursor', 'pointer');
        }



};
Video.fn.positionLogo = function(){
    var self=this;
    if(self.options.logoPosition == "bottom-right")
    {
        this.logoImg.css({
            bottom:  self.controls.height() + 5,
            right: buttonsMargin
        });
    }
    else if(self.options.logoPosition == "bottom-left")
    {
        this.logoImg.css({
            bottom:  self.controls.height() + self.toolTip.height() + 8,
            left: buttonsMargin
        });
    }

};
Video.fn.createAds = function(){
    var self=this;
    //text ad
    this.adTextWindow = $("<div/>");
    this.adTextWindow.addClass("ts_textAdsWindow");
    this.element.append(this.adTextWindow);

    this.adTextWindowWrapper = $("<div/>");
    this.adTextWindowWrapper.addClass("ts_textAdsWindowWrapper");
    this.adTextWindow.append(this.adTextWindowWrapper);

    this.adTextContent = $("<div/>");
    this.adTextContent.addClass("ts_textAdsContent");
    this.adTextWindowWrapper.append(this.adTextContent);

    this.adTextContent.append('<p class="ts_textAdsTexts">' + self._playlist.videos_array[self._playlist.videoid].textAd + '</p>');
    this.scroll = new iScroll(self.adTextContent[0], {
            bounce:false,
            momentum:true
    });
    this.adTextWindow.hide();
    this.adTextWindow.css({opacity:0});

    //popup ads
    this.adImg = $("<div/>");
    this.adImg.addClass("ts_ads");

    self.image = new Image();
    self.image.src = self._playlist.videos_array[self._playlist.videoid].popupImg;

    $(self.image).load(function() {
        //when image loaded position ads
        self.adImg.append(self.image);
        self.positionAds();
        self.adImg.append(self.adClose);
    });
    this.element.append(this.adImg);
    this.adImg.hide();
    this.adImg.css({opacity:0});


    this.adClose = $("<div />");
    this.adClose.addClass("ts_adClose");
    this.adClose.addClass("icon-close");
//    this.adImg.append(this.adClose);
    this.adClose.css({bottom:0});
    this.adClose.bind(this.START_EV,$.proxy(function()
    {
        self.adOn=true;
        self.toggleAdWindow();

    }, this));

    this.adClose.mouseover(function(){
        $(this).stop().animate({
            opacity:0.7
        },200);
    });
    this.adClose.mouseout(function(){
        $(this).stop().animate({
            opacity:1
        },200);
    });

    this.textAdClose = $("<div />");
    this.textAdClose.addClass("ts_adClose");
    this.textAdClose.addClass("icon-close");
    this.textAdClose.css({bottom:0});
    this.textAdClose.bind(this.START_EV,$.proxy(function()
    {
        self.textAdOn=true;
        self.toggleTextAdWindow();

    }, this));

    this.textAdClose.mouseover(function(){
        $(this).stop().animate({
            opacity:0.7
        },200);
    });
    this.textAdClose.mouseout(function(){
        $(this).stop().animate({
            opacity:1
        },200);
    });
    this.adTextWindow.append(this.textAdClose);
};
Video.fn.positionAds = function(){
    var self=this;
    this.adImg.css({
        bottom: self.controls.height()+40,
        left: self.element.width()/2-this.adImg.width()/2
    });
    this.adTextWindow.css({
        bottom: self.controls.height()+40,
        left: self.element.width()/2-this.adTextWindow.width()/2
    });
};
Video.fn.newAd = function(adPath, adUrl){
    var self = this;

    //replace current ad
//    image.src = adPath;
//    var adLink = adUrl;
    this.adImg.hide();
    self.image.src="";
    self.image.src=self._playlist.videos_array[self._playlist.videoid].popupImg;

    $(self.image).bind(this.START_EV,$.proxy(function(){
        //if(self.options.videos[self._playlist.videoid].popupAdvertisementClickable)
        setAdStatistics(self.image);
        window.open(self._playlist.videos_array[self._playlist.videoid].popupAdGoToLink);

        switch(self._playlist.videos_array[self._playlist.videoid].videoType){
            case "HTML5":
                self.pause();
                break;
            case "youtube":
                self._playlist.youtubePlayer.pauseVideo();
                break;
            case "vimeo":
                self._playlist.vimeoPlayer.api('pause');
                break;
        }

        //}


    }, this));
//    if(self.options.videos[0].popupAdvertisementClickable)
//    {
//        $('.ads').css('cursor', 'pointer');
//    }

};
Video.fn.newTextAd = function(adPath, adUrl){
   var self = this;

   this.adTextWindowWrapper.bind(this.START_EV,$.proxy(function(){
       window.open(self._playlist.videos_array[self._playlist.videoid].textAdGoToLink);
       switch(self._playlist.videos_array[self._playlist.videoid].videoType){
           case "HTML5":
               self.pause();
               break;
           case "youtube":
               self._playlist.youtubePlayer.pauseVideo();
               break;
           case "vimeo":
               self._playlist.vimeoPlayer.api('pause');
               break;
       }
    }, this));
};
Video.fn.showVideoElements = function()
{
    this.videoElement.show();
    this.videoElementAD.show();
};
Video.fn.hideVideoElements = function(){
    this.videoElement.hide();
    this.videoElementAD.hide();
};



Video.fn.setupAutoplay = function()
{
   var self=this;
    //autoplay
//    self.options.autoplay = self.autoplay;
    /*if(self.options.autoplay == "on")
    {
        self.play();
    }
     else if(self.options.autoplay == "off")
     {
        self.pause();
        self.preloader.hide();
     }*/
    if(self.options.autoplay)
    {
        if(this.hasTouch)
        self.pause();
        else
        self.play();
    }
    else if(!self.options.autoplay)
    {
        self.pause();
        self.preloader.hide();
    }
}
Video.fn.createNowPlayingText = function()
{
    var self = this;
    if(self.options.loadRandomVideoOnStart=="Yes")
        this.element.append('<p class="ts_nowPlayingText">' + this._playlist.videos_array[self._playlist.rand].title + '</p>');
    else
        this.element.append('<p class="ts_nowPlayingText">' + this._playlist.videos_array[0].title + '</p>');

    if(this.options.nowPlayingText=="No")
        this.element.find(".ts_nowPlayingText").hide();
};
Video.fn.createInfoWindowContent = function()
{
    var self  = this;
    if(self.options.loadRandomVideoOnStart=="Yes"){
        this.infoWindow.append('<p class="ts_infoTitle">' + this._playlist.videos_array[self._playlist.rand].title + '</p>');
        this.infoWindow.append('<p class="ts_infoText">' + this._playlist.videos_array[self._playlist.rand].info_text + '</p>');
    }
    else{
        this.infoWindow.append('<p class="ts_infoTitle">' + this._playlist.videos_array[0].title + '</p>');
        this.infoWindow.append('<p class="ts_infoText">' + this._playlist.videos_array[0].info_text + '</p>');
    }
    this.infoWindow.hide();
    this.positionInfoWindow();
};
Video.fn.createSkipAd = function(){
    var self=this;

    this.skipAdBox = $("<div />")
        .attr("title", "Skip Ad")
        .addClass("ts_skipAdBox")
        .bind(self.CLICK_EV, function(){
            //skip Ad
            self.closeAD();
        })
        .hide();
    this.elementAD.append(this.skipAdBox);

    this.skipAdBoxIcon = $("<span />")
        .attr("aria-hidden","true")
//        .addClass("icon-general")
        .addClass("icon-right")
    this.skipAdBox.append(this.skipAdBoxIcon);



    this.skipAdBox.append('<p class="ts_skipAdTitle">' + "Skip Ad" + '</p>');
    this.positionSkipAdBox();
};
Video.fn.createAdTogglePlay = function(){
    var self=this;

    this.toggleAdPlayBox = $("<div />")
        .attr("title", "Play/pause ad")
        .addClass("ts_toggleAdPlayBox")
        .bind(self.CLICK_EV, function(){
            //toggle Ad
            self.togglePlayAD();
            self.ADTriggered=true;//ad is once enabled
        })
        .hide()
    this.elementAD.append(this.toggleAdPlayBox);

    this.toggleAdPlayBoxIcon = $("<span />")
        .attr("aria-hidden","true")
//        .addClass("icon-general")
        .addClass("icon-pause");
    this.toggleAdPlayBox.append(this.toggleAdPlayBoxIcon);


    this.toggleAdPlayBox.append('<p class="ts_toggleAdPlayBoxTitle">' + "Pause Ad" + '</p>');
    this.positionToggleAdPlayBox();
};
Video.fn.createVideoAdTitleInsideAD = function(){
    var self=this;
    this.videoAdBoxInside = $("<div />");
    this.videoAdBoxInside.addClass("ts_videoAdBoxInside");
    this.elementAD.append(this.videoAdBoxInside);

    this.videoAdBoxInside.append('<p class="ts_adsTitleInside">' + "Your video will resume in" + '</p>');
    this.videoAdBoxInside.append(this.timeLeftInside);
    this.videoAdBoxInside.hide();

    this.positionVideoAdBoxInside();

    //now playing text inside ad
    this.videoAdBoxInsideNowPlaying = $("<div />");
    this.videoAdBoxInsideNowPlaying.addClass("ts_videoAdBoxInsideNowPlaying");
    this.elementAD.append(this.videoAdBoxInsideNowPlaying);

    this.videoAdBoxInsideNowPlaying.append('<p class="ts_adsTitleInsideNowPlaying">' + "Advertisement" + '</p>');
    this.videoAdBoxInsideNowPlaying.hide();
};
Video.fn.createEmbedWindowContent = function()
{
    $(this.embedWindow).append('<p class="ts_embedTitle">' + "EMBED CODE:" + '</p>');
    $(this.embedWindow).append('<p class="ts_embedText"></p>');
    $(this.embedWindow).find(".ts_embedText").css({
        opacity: 0.5
    });

//    embedMessage = $("<div />");
//    embedMessage.addClass("embedMessage");
//    embedWindow.append(embedMessage);
//    embedMessage.append('<p class="embedMessageTxt">' + "CLICK TO COPY CODE" + '</p>');
//    embedMessage.css({left:embedWindow.width()/2 - embedMessage.width()/2, top:embedWindow.height()/2 - embedMessage.height()/2});
    // $(this.embedWindow).find(".ts_embedText").text(this.options.embedCode);
    var s = this.options.embedCodeSrc;
    var w = this.options.embedCodeW;
    var h = this.options.embedCodeH;
    // console.log(s,w,h)

    $(this.embedWindow).find(".ts_embedText").text("<iframe src='"+s+"' width='"+w+"' height='"+h+"' frameborder=0 webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>");

    $(this.embedWindow).hide();
    this.positionEmbedWindow();

    $(this.embedWindow).mouseover(function(){
        $(this).find(".ts_embedText").stop().animate({opacity: 1},300);
//        embedMessage.stop().animate({opacity: 0},300,function(){
//           embedMessage.hide();
//        });

    });
    $(this.embedWindow).mouseout(function(){
        $(this).find(".ts_embedText").stop().animate({opacity: 0.5},300);
//        embedMessage.show();
//        embedMessage.stop().animate({opacity: 1},300);
    });


};

/*Video.fn.stripslashes = function (str) {
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Ates Goral (http://magnetiq.com)
  // +      fixed by: Mick@el
  // +   improved by: marrtins
  // +   bugfixed by: Onno Marsman
  // +   improved by: rezna
  // +   input by: Rick Waldron
  // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
  // +   input by: Brant Messenger (http://www.brantmessenger.com/)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: stripslashes('Kevin\'s code');
  // *     returns 1: "Kevin's code"
  // *     example 2: stripslashes('Kevin\\\'s code');
  // *     returns 2: "Kevin\'s code"
  return (str + '').replace(/\\(.?)/g, function (s, n1) {
    switch (n1) {
    case '\\':
      return '\\';
    case '0':
      return '\u0000';
    case '':
      return '';
    default:
      return n1;
    }
  });
}*/

Video.fn.ready = function(callback)
{
  this.readyList.push(callback);
  if (this.loaded)
      callback.call(this);
};

Video.fn.load = function(srcs, obj_id)
{
  var self = this;
  if (srcs)
    this.sources = srcs;

  if (typeof this.sources == "string")
    this.sources = {src:this.sources};

  if (!$.isArray(this.sources))
    this.sources = [this.sources];

  this.ready(function()
  {
    this.change("loading");
      if(self._playlist.videos_array[this._playlist.videoid].videoType=="HTML5")
      {
          this.video.loadSources(this.sources);
      }
  });
};
Video.fn.closeAD = function()
{
    var self=this;
    self.videoPlayingAD=true;
    self.togglePlayAD();

    self._playlist.videoAdPlayed=true;

    self.resetPlayerAD();
    self.elementAD.width(0);
    self.elementAD.height(0);
    self.elementAD.css({zIndex:1});
    self.videoAdBoxInside.hide();
    self.skipAdBox.hide();
    self.fsEnterADBox.hide();
    self.toggleAdPlayBox.hide();
    self.videoAdBoxInsideNowPlaying.hide();
//    self.videoAD.pause();
    if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube")
    {
        self.hideVideoElements();
        self._playlist.youtubePlayer.playVideo();
    }
    else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
    {
        self.showVideoElements();
        self.togglePlay();
        self.video.play();
    }
    else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo")
    {
        self.hideVideoElements();
//        self._playlist.playVimeo(self._playlist.videoid);
        if(self._playlist.vimeoPlayer!= undefined)
            self._playlist.vimeoPlayer.api('play');
        else
            self._playlist.playVimeo(self._playlist.videoid);
    }

    self.exitToOriginalSize();
};
Video.fn.openAD = function()
{
    var self=this;
    self.showVideoElements();
//    self.videoPlayingAD=true;
//    self.togglePlayAD();

    self.elementAD.css({zIndex:559});
    self.videoAdBoxInside.show();
    self.skipAdBox.show();
    self.fsEnterADBox.show();
    self.toggleAdPlayBox.show();
    if(this.hasTouch)
    {
        if(!self.ADTriggered)
        {
            //self.toggleAdPlayBox.show();
            $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").text("Play Ad");
            self.videoPlayingAD=true;
            self.togglePlayAD();
        }
    }
    else
        //self.toggleAdPlayBox.hide();
        $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").text("Pause Ad");
    self.videoAdBoxInsideNowPlaying.show();
    self.resizeAll();
};
Video.fn.loadAD = function(srcs)
{
//console.log("loadAD,",srcs)
    if (srcs)
        this.sourcesAD = srcs;

    if (typeof this.sourcesAD == "string")
        this.sourcesAD = {src:this.sourcesAD};

    if (!$.isArray(this.sourcesAD))
        this.sourcesAD = [this.sourcesAD];

    this.ready(function()
    {
        this.change("loading");
        this.videoAD.loadSources(this.sourcesAD);
//      console.log("sourcesAD",this.sourcesAD)
    });
};
Video.fn.exitToOriginalSize = function(){
    if(THREEx.FullScreen.available())
    {
        if(THREEx.FullScreen.activated())
        {
           THREEx.FullScreen.cancel();
        }
        else if (this.inFullScreen)
        {
           this.fullScreen(!this.inFullScreen);
        }
    }
    else if(!THREEx.FullScreen.available())
    {
//        this.fullScreen(!this.inFullScreen);
    }
    this.elementAD.css({zIndex:555});

}
Video.fn.play = function()
{
  var self = this;
  this.playButtonScreen.stop().animate({opacity:0},0,function(){
      // Animation complete.
      $(this).hide();
  });
    this.playBtn.removeClass("icon-play").addClass("icon-pause");
    self.video.play();

    if(self._playlist.videos_array[self._playlist.videoid].videoAdShow=="yes" && self.videoAdStarted==false)
    {
//                console.log(self._playlist.videos_array[self._playlist.videoid].video_path_mp4AD)
//                console.log(self._playlist.videos_array[self._playlist.videoid].video_path_mp4)
//                console.log(self._playlist.videos_array[self._playlist.videoid].videoAdShow)
        self.video.pause();
        if(!self.videoAdStarted && self._playlist.videos_array[self._playlist.videoid].videoAdShow){
//            console.log(self._playlist.videos_array[self._playlist.videoid].video_path_mp4AD)
            /*if(self.myVideo.canPlayType && this.myVideo.canPlayType('video/webm').replace(/no/, ''))
            {
                self.canPlay = true;
                self.video_pathAD = self._playlist.videos_array[self._playlist.videoid].video_path_webmAD;
            }
            else */if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
            {
                self.canPlay = true;
                self.video_pathAD = self._playlist.videos_array[self._playlist.videoid].video_path_mp4AD;
            }


            self.loadAD(self.video_pathAD);
            self.openAD();
        }
        self.videoAdStarted=true;
    }
};

Video.fn.pause = function()
{
    var self = this;
    this.playButtonScreen.stop().animate({opacity:1},0,function(){
        // Animation complete.
        $(this).show();
    });
    this.playBtn.removeClass("icon-pause").addClass("icon-play");
    self.video.pause();
};

Video.fn.stop = function()
{
  this.seek(0);
  this.pause();
};
Video.fn.hideOverlay = function(){
    var self = this;
    if(self.overlay==undefined)
        return;

    self.overlay.hide();
    self.playButtonPoster.hide();

    if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube")
    {
        self._playlist.youtubePlayer.playVideo();
    }
    else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
    {
        self.togglePlay();
//        self.video.play();
    }
    else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo")
    {
        if(self._playlist.vimeoPlayer!= undefined)
            self._playlist.vimeoPlayer.api('play');
        else
            self._playlist.playVimeo(self._playlist.videoid);
    }
};
Video.fn.togglePlay = function()
{
  if (this.state == "ts_playing")
  {
    this.pause();
  }
  else
  {
    this.play();
  }
};

Video.fn.toggleInfoWindow = function()
{
    var self = this;

    if(this.infoOn)
    {
        this.infoWindow.animate({opacity:0},500,function() {
            // Animation complete.
            $(this).hide();
       });

        this.infoOn=false;
    }
    else
    {
        this.infoWindow.show();
        this.infoWindow.animate({opacity:1},500);
//        infoWindow.animate({top:0});
        this.infoOn=true;
//        console.log(this.infoOn)
    }
};
Video.fn.toggleAdWindow = function()
{
    var self = this;
    if(this.adOn)
    {
        this.adImg.animate({opacity:0},0,function() {
            // Animation complete.
            $(this).hide();
        });
        this.adOn=false;
    }
    else if(!this.adOn)
    {
        this.adImg.show();
        this.adImg.animate({opacity:1},500);
        this.adOn=true;

    }
};
Video.fn.toggleTextAdWindow = function()
{
    var self = this;
    if(this.textAdOn)
    {
        this.adTextWindow.animate({opacity:0},0,function() {
            // Animation complete.
            $(this).hide();
        });
        this.textAdOn=false;
    }
    else if(!this.textAdOn)
    {
        this.adTextWindow.show();
        this.adTextWindow.animate({opacity:1},500);
        this.textAdOn=true;

    }
};
Video.fn.toggleShareWindow = function()
{
    var self = this;

    if(this.shareOn)
    {
        $(this.shareWindow).animate({opacity:0},500,function() {
            // Animation complete.
            $(this).hide();
       });

        this.shareOn=false;
    }
    else
    {
        this.shareWindow.show();
        $(this.shareWindow).animate({opacity:1},500);
        this.shareOn=true;
    }
};
Video.fn.togglePlayAD = function()
{
    var self = this;

    if(this.videoPlayingAD)
    {
        this.videoAD.pause();
        this.videoPlayingAD=false;
        this.toggleAdPlayBoxIcon.removeClass("icon-pause").addClass("icon-play");
        $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").text("Play Ad");
    }
    else
    {
        this.videoAD.play();
        this.videoPlayingAD=true;
        this.toggleAdPlayBoxIcon.removeClass("icon-play").addClass("icon-pause");
        $(self.toggleAdPlayBox).find(".ts_toggleAdPlayBoxTitle").text("Pause Ad");
    }
};
Video.fn.toggleEmbedWindow = function()
{
    var self = this;

    if(this.embedOn)
    {
        $(this.embedWindow).animate({opacity:0},500,function() {
            // Animation complete.
            $(this).hide();
        });
        this.embedOn=false;
    }
    else
    {
        $(this.embedWindow).show();
        $(this.embedWindow).animate({opacity:1},500);
        this.embedOn=true;
    }
};

Video.fn.fullScreen = function(state)
{
//    console.log("+")
    var self = this;
    if(state)
    {
        this._playlist.hidePlaylist();
        this.element.addClass("ts_fullScreen");
        this.elementAD.addClass("ts_fullScreen");
        $(this.controls). find(".icon-fullscreen").removeClass("icon-fullscreen").addClass("icon-resize-full");
        $(this.fsEnterADBox). find(".icon-resize-full").removeClass("icon-resize-full").addClass("icon-resize-full");
//        $(this.controls). find(".ts_fullScreenEnterBg").removeClass("ts_fullScreenEnterBg").addClass("ts_fullScreenExitBg");
        self.element.width(self.windowWidth);
        self.element.height(self.windowHeight);
        self.elementAD.width(self.windowWidth);
        self.elementAD.height(self.windowHeight);
//        this.infoWindow.css({
//            bottom: self.controls.height()+5,
//            left: $(window).width/2-this.infoWindow.width()/2
//        });
        if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
            self.element.css({zIndex:558 });
        else
            self.element.css({zIndex:556});


        if(self._playlist.videos_array[self._playlist.videoid].videoAdShow=="yes"){
            if(!self._playlist.videoAdPlayed){
                self.elementAD.css({
                    zIndex:559
                });
            }
            else{
                    self.elementAD.css({
                        zIndex:557
                    });
            }
        }

//        console.log("ent")
    }
    else
    {
//        console.log("esc")
        this._playlist.showPlaylist();
        this.element.removeClass("ts_fullScreen");
        this.elementAD.removeClass("ts_fullScreen");
        $(this.controls). find(".icon-resize-full").removeClass("icon-resize-full").addClass("icon-fullscreen");
        $(this.fsEnterADBox). find(".icon-resize-full").removeClass("icon-resize-full").addClass("icon-resize-full");
//        $(this.controls). find(".ts_fullScreenExitBg").removeClass("ts_fullScreenExitBg").addClass("ts_fullScreenEnterBg");
//        self.element.width(self.playerWidth);
//        self.element.height(self.playerHeight);
//
//        self.elementAD.width(self.playerWidth);
//        self.elementAD.height(self.playerHeight);
//        this.infoWindow.css({
//            bottom: self.controls.height()+5,
//            left: self.playerWidth/2-this.infoWindow.width()/2
//        });

        if(this.stretching)
        {
            //back to stretched player
            this.stretching=false;
            this.toggleStretch();
        }
        if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
            self.element.css({zIndex:558 });
        else
            self.element.css({zIndex:556});
        if(self._playlist.videos_array[self._playlist.videoid].videoAdShow=="yes"){
            if(!self._playlist.videoAdPlayed){
                self.elementAD.css({
                    zIndex:559
                });
            }
            else{
                    self.elementAD.css({
                        zIndex:557
                    });
            }
        }

        self.resizeAll();
    }
    this.resizeVideoTrack();
    this.positionOverScreenButtons(state);
    this.positionInfoWindow();
    this.positionAds();
    this.positionEmbedWindow();
    this.positionVideoAdBoxInside();
    this.positionSkipAdBox();
    this.positionToggleAdPlayBox();
    this.positionLogo();
    this.positionPoster();
//    this.positionAds();
    this.resizeBars();


  if (typeof state == "undefined") state = true;
  this.inFullScreen = state;


};

Video.fn.toggleFullScreen = function()
{
    var self = this;
    if(THREEx.FullScreen.available())
    {
        if(THREEx.FullScreen.activated())
        {
            // if(this.options.fullscreen_native)
            if(this.options.fullscreen=="Fullscreen native")
                THREEx.FullScreen.cancel();
            // if(this.options.fullscreen_browser)
            if(this.options.fullscreen=="Fullscreen browser")
                this.fullScreen(!this.inFullScreen);
//            console.log("exited fullscreen")
            if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
                self.element.css({zIndex:558 });
            else
                self.element.css({zIndex:556});
            if(self._playlist.videos_array[self._playlist.videoid].videoAdShow=="yes"){
                if(!self._playlist.videoAdPlayed ){
                    self.elementAD.css({
                        zIndex:559
                    });
                }
                else{
                        self.elementAD.css({
                            zIndex:557
                        });
                }
            }
//            console.log("1 exited")
        }
        else
        {
            // if(this.options.fullscreen_native)
            if(this.options.fullscreen=="Fullscreen native")
            {

                THREEx.FullScreen.request();
                if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
                    self.element.css({zIndex:558 });
                else
                    self.element.css({zIndex:556});
                if(self._playlist.videos_array[self._playlist.videoid].videoAdShow=="yes"){
                    if(!self._playlist.videoAdPlayed){
                        self.elementAD.css({
                            zIndex:559
                        });
                    }
                    else{
                            self.elementAD.css({
                                zIndex:557
                           });
                    }
                }

            }
            // if(this.options.fullscreen_browser)
            if(this.options.fullscreen=="Fullscreen browser")
                this.fullScreen(!this.inFullScreen);
//            console.log("entered fullscreen")

//            console.log("2 entered")
        }
    }
    else if(!THREEx.FullScreen.available())
    {
//        console.log("fullscreen not available in this browser")
//        alert("THREEx.FullScreen not available")

        this.fullScreen(!this.inFullScreen);
    }
};

Video.fn.seek = function(offset)
{
  this.video.setCurrentTime(offset);
};

Video.fn.setVolume = function(num)
{
  this.video.setVolume(num);
};

Video.fn.getVolume = function()
{
  return this.video.getVolume();
};

Video.fn.mute = function(state)
{
  if (typeof state == "undefined") state = true;
  this.setVolume(state ? 1 : 0);
};

Video.fn.remove = function()
{
  this.element.remove();
};

Video.fn.bind = function()
{
  this.videoElement.bind.apply(this.videoElement, arguments);
};

Video.fn.one = function()
{
  this.videoElement.one.apply(this.videoElement, arguments);
};

Video.fn.trigger = function()
{
  this.videoElement.trigger.apply(this.videoElement, arguments);
};

// Proxy jQuery events
var events = [
               "click",
               "dblclick",
               "onerror",
               "onloadeddata",
               "oncanplay",
               "ondurationchange",
               "ontimeupdate",
               "onprogress",
               "onpause",
               "onplay",
               "onended",
               "onvolumechange"
             ];

for (var i=0; i < events.length; i++)
{
  (function()
  {
    var functName = events[i];
    var eventName = functName.replace(/^(on)/, "");
    Video.fn[functName] = function()
    {
      var args = $.makeArray(arguments);

      args.unshift(eventName);
      this.bind.apply(this, args);
    };
  }
  )();
}
// Private methods
Video.fn.triggerReady = function()
{
  /*this.readyList-> []*/
  for (var i in this.readyList)
  {
    this.readyList[i].call(this);
  }
  this.loaded = true;
//        console.log(this.readyList[i])
};

Video.fn.setupElement = function()
{
    this.mainContainer=$("<div />");
    this.mainContainer.addClass("ts_mainContainer");
    if(this.options.responsive){
        this.mainContainer.css({
            height:"100%",
            background:"#000000",
            position:"absolute"
        });
    }
    else{
        this.mainContainer.css({
            width:this.options.videoPlayerWidth,
            height:this.options.videoPlayerHeight,
            position:"absolute",
            background:"#000000"
        });
    }
    switch( this.options.videoPlayerShadow ) {
        case 'effect1':
            this.mainContainer.addClass("ts_effect1");
            break;
        case 'effect2':
            this.mainContainer.addClass("ts_effect2");
            break;
        case 'effect3':
            this.mainContainer.addClass("ts_effect3");
            break;
        case 'effect4':
            this.mainContainer.addClass("ts_effect4");
            break;
        case 'effect5':
            this.mainContainer.addClass("ts_effect5");
            break;
        case 'effect6':
            this.mainContainer.addClass("ts_effect6");
            break;
        case 'off':
            break;
    }
    this.parent.append(this.mainContainer);

  this.element = $("<div />");
  this.element.addClass("ts_videoPlayer");
    this.mainContainer.append(this.element);
//    console.log(this.parent)

};
Video.fn.setupElementAD = function()
{
    this.elementAD = $("<div />");
    this.elementAD.addClass("ts_videoPlayerAD");
    this.mainContainer.append(this.elementAD);
//    console.log(this.parent)

};

/***************************************AUTOHIDE CONTROLS*********************************/
Video.fn.idle = function(e, toggle){
    var self=this;
  if (toggle)
  {
    if (this.state == "ts_playing")
    {
//          this.element.addClass("idle");
        this.controls.stop().animate({opacity:0} , 300);
        this.playlistBtn.stop().animate({opacity:0} , 300);
        this.embedBtn.stop().animate({opacity:0} , 300);
        this.logoImg.stop().animate({opacity:0} , 300);
        self.element.find(".ts_nowPlayingText").stop().animate({opacity:0} , 300);
    }
  }
  else
  {
//          this.element.removeClass("idle");
      this.controls.stop().animate({opacity:1} , 300);
      this.playlistBtn.stop().animate({opacity:1} , 300);
      this.embedBtn.stop().animate({opacity:1} , 300);
      this.logoImg.stop().animate({opacity:1} , 300);
      self.element.find(".ts_nowPlayingText").stop().animate({opacity:1} , 300);
  }
};



Video.fn.change = function(state)
{
  this.state = state;
    if(this.element){
        this.element.attr("data-state", this.state);
        this.element.trigger("state.videoPlayer", this.state);
    }

}




//////////////////////////////////////////////SETUP NATIVE*////////////////////////////////////////////////////////////
Video.fn.setupHTML5Video = function()
  {

      if(this.element)
      {
          this.element.append(this.videoElement);
//          this.element.append(this.preloader);
      }
      this.video = this.videoElement[0];

//      if(!this.options.autoplay)
//        this.video.poster = this.options.posterImg;

      if(this.element)
      {
          this.element.width(this.playerWidth);
          this.element.height(this.playerHeight);
      }


      var self = this;

      this.video.loadSources = function(srcs)
      {

        self.videoElement.empty();
        for (var i in srcs)
        {
          var srcEl = $("<source />");
          srcEl.attr(srcs[i]);
          self.videoElement.append(srcEl);
        }
        self.video.load();

      };

      this.video.getStartTime = function()
      {
          return(this.startTime || 0);
      };
      this.video.getEndTime = function()
      {
        if (this.duration == Infinity && this.buffered)
        {
          return(this.buffered.end(this.buffered.length-1));
        }
        else
        {
          return((this.startTime || 0) + this.duration);
        }
      };

      this.video.getCurrentTime = function(){
        try
        {
          return this.currentTime;
        }
        catch(e)
        {
          return 0;
        }
      };


      var self = this;

      this.video.setCurrentTime = function(val)
      {
//          console.log( this.currentTime)
          this.currentTime = val;
      };
      this.video.getVolume = function()
      {
          return this.volume;
      };
      this.video.setVolume = function(val)
      {
          this.volume = val;
      };

      this.videoElement.dblclick($.proxy(function()
      {
        this.toggleFullScreen();
      }, this));
      this.videoElement.bind(this.CLICK_EV, $.proxy(function()
      {
        this.togglePlay();
      }, this));

      this.triggerReady();
};




Video.fn.setupHTML5VideoAD = function()
{
//      console.log(this);


    if(this.elementAD)
    {
        this.elementAD.append(this.videoElementAD);
//        this.elementAD.append(this.preloader);
    }
//      $(this.elementAD).find(".nowPlayingText").hide();
    this.videoAD = this.videoElementAD[0];
//    if(!this.options.autoplay)
//    this.videoAD.poster = this.options.posterImg;

    if(this.elementAD)
    {
//          this.elementAD.width(this.playerWidth);
        this.elementAD.width(0);
//          this.elementAD.height(this.playerHeight);
        this.elementAD.height(0);
    }
//
//
    var self = this;


    this.videoAD.loadSources = function(srcs)
    {
//        console.log("srcs",srcs)
        self.videoElementAD.empty();
        for (var i in srcs)
        {
            var srcEl = $("<source />");
            srcEl.attr(srcs[i]);
            self.videoElementAD.append(srcEl);
        }
        self.videoAD.load();
//        self.videoAD.play();
        //self.videoPlayingAD=false;
        if(this.hasTouch)
            self.videoPlayingAD=true;
        else
            self.videoPlayingAD=false;
        self.togglePlayAD();

    };

    this.videoAD.getStartTime = function()
    {
        return(this.startTime || 0);
    };
    this.videoAD.getEndTime = function()
    {
//          console.log("duration=",this.duration)
        if(isNaN(this.duration))
        {
            self.timeTotal.text("--:--");
        }
        else
        {
            if (this.duration == Infinity && this.buffered)
            {
                return(this.buffered.end(this.buffered.length-1));
            }
            else
            {
                return((this.startTime || 0) + this.duration);
            }
        }

    };

    this.videoAD.getCurrentTime = function(){
        try
        {
            return this.currentTime;
        }
        catch(e)
        {
            return 0;
        }
    };


    this.videoAD.setCurrentTime = function(val)
    {
        this.currentTime = val;
    }
    this.videoAD.getVolume = function()
    {
        return this.volume;
    };
    this.videoAD.setVolume = function(val)
    {
        this.volume = val;
    };

    this.videoElementAD.dblclick($.proxy(function()
    {
        this.toggleFullScreen();
    }, this));

    /*********this.videoElementAD.bind(this.CLICK_EV, $.proxy(function()
    {
        if(self.options.videos[0].videoAdvertisementInsideClickable)
        {
            window.open(this._playlist.videos_array[0].videoAdInsideGotoLink);
        }
    }, this));*************/

    this.triggerReady();

    this.videoElementAD.bind(this.CLICK_EV, $.proxy(function()
    {
        if((this._playlist.videos_array[this._playlist.videoid].videoAdGotoLink !="") &&  (this._playlist.videos_array[this._playlist.videoid].videoAdGotoLink !="videoAdGotoLink"))
        {
            setAdStatistics(this.element);
            window.open(this._playlist.videos_array[this._playlist.videoid].videoAdGotoLink);
            this.videoPlayingAD=true;
            this.togglePlayAD();
//            this.videoAD.pause();
        }

    }, this));
//    console.log("setup html5 video ad",this.videoElementAD)
};

Video.fn.setupButtonsOnScreen = function(){

    var self = this;
    this.screenBtnsWindow = $("<div></div>");
    this.screenBtnsWindow.addClass("ts_screenBtnsWindow");
    if(this.element)
        this.element.append(this.screenBtnsWindow);

    this.playlistBtn = $("<div />")
        .addClass("ts_playlistBtn")
        .addClass("btnOverScreen");
    if(this.element){
        this.screenBtnsWindow.append(this.playlistBtn);
    }
    this.playlistBtnIcon = $("<span />")
        .attr("aria-hidden","true").attr("title", "Playlist toggle")
        .addClass("icon-overScreen")
//        .addClass("fa-list-alt");
//        .addClass("fa-list-alt");
        .addClass("icon-list");
//        .addClass("fa-th-list");
    this.playlistBtn.append(this.playlistBtnIcon);
    this.playlistBtn.append('<p class="ts_playlistBtnText">' + "VIDEOS" + '</p>');
    $(this.element).find(".ts_playlistBtnText").addClass("icon-overScreen-Texts");
//    var playlistBtnIcon = $("<div />");
//    playlistBtnIcon.addClass("ts_playlistBtnIcon");
//    this.playlistBtn.append(playlistBtnIcon);

    this.embedBtn = $("<div />")
        .addClass("ts_embedBtn")
        .addClass("btnOverScreen");
    if(this.element){
        this.screenBtnsWindow.append(this.embedBtn);
    }
    this.embedBtnIcon = $("<span />")
        .attr("aria-hidden","true").attr("title", "Embed")
        .addClass("icon-overScreen")
//        .addClass("fa-chain");f
        .addClass("icon-code");
    this.embedBtn.append(this.embedBtnIcon);
    this.embedBtn.append('<p class="ts_embedBtnText">' + "EMBED" + '</p>');
    $(this.element).find(".ts_embedBtnText").addClass("icon-overScreen-Texts");
//    var embedBtnIcon = $("<div />");
//    embedBtnIcon.addClass("ts_embedBtnIcon");
//    this.embedBtn.append(embedBtnIcon);
    $(".ts_embedBtn, .ts_playlistBtn").mouseover(function(){
        $(this).find(".icon-overScreen-Texts").removeClass("icon-overScreen-Texts").addClass("icon-overScreen-Texts-hover");
        $(this).find(".icon-overScreen").removeClass("icon-overScreen").addClass("icon-overScreen-hover");
    });
    $(".ts_embedBtn, .ts_playlistBtn").mouseout(function(){
        $(this).find(".icon-overScreen-Texts-hover").removeClass("icon-overScreen-Texts-hover").addClass("icon-overScreen-Texts");
        $(this).find(".icon-overScreen-hover").removeClass("icon-overScreen-hover").addClass("icon-overScreen");
    });

    if(self.options.embedShow=="No")
    {
//        this.embedBtn.css({width:0, height:0, visibility:"hidden"});
        this.embedBtn.hide();
    }

    buttonsMargin = 5;


    this.positionOverScreenButtons();

    this.playlistBtn.bind(this.CLICK_EV, function(){
        self.toggleStretch();
    });
};
Video.fn.toggleStretch = function(){
    var self=this;
    if(this.stretching)
    {
        self.shrinkPlayer();
        this.stretching = false;
    }
    else
    {
        self.stretchPlayer();
        this.stretching = true;
    }
    this.resizeVideoTrack();
    this.positionOverScreenButtons();
    this.positionInfoWindow();
    this.positionAds();
    this.positionEmbedWindow();
    this.positionLogo();
//    this.positionAds()
    this.positionVideoAdBoxInside();
    this.positionSkipAdBox();
    this.positionToggleAdPlayBox();
    this.resizeBars();
    this.resizeAll();

};
Video.fn.stretchPlayer = function(){
    this.element.width(this.options.videoPlayerWidth);
    this._playlist.hidePlaylist();
};
Video.fn.shrinkPlayer = function(){
    this.element.width(this.playerWidth);
    this._playlist.showPlaylist();
};


Video.fn.positionOverScreenButtons = function(state){
    if(this.element){


    if(document.webkitIsFullScreen || document.fullscreenElement || document.mozFullScreen || state)
    {

        this.playlistBtn.hide();
    }
    else
    {
        if(this.options.playlist=="Right playlist" || this.options.playlist=="Bottom playlist")
        {
            this.playlistBtn.show();
        }
        else
        {
            this.playlistBtn.hide();
        }

    }
    }

};

Video.fn.positionInfoWindow = function(){
    var self = this;
    this.infoWindow.css({
        bottom: self.controls.height()+55,
        left: self.element.width()/2-this.infoWindow.width()/2
    });
};
Video.fn.positionEmbedWindow = function(){
        var self = this;
    this.embedWindow.css({
            bottom: self.element.height()/2 - this.embedWindow.height()/2,
            left: self.element.width()/2-this.embedWindow.width()/2
        });
 };

Video.fn.positionVideoAdBoxInside = function(){
    var self = this;
    this.videoAdBoxInside.css({
        left:self.elementAD.width()/2 - this.videoAdBoxInside.width()/2,
        bottom: self.controls.height()+45
    });

};
Video.fn.positionSkipAdBox = function(){
    var self = this;
    if(this.options.allowSkip){
        this.skipAdBox.css({
            right:10,
            bottom: 10
        });
    }
    else
    {
        this.skipAdBox.css({
            display:"none"
        });
    }

};
Video.fn.positionToggleAdPlayBox = function(){
    var self = this;
    if(this.options.allowSkip){
        this.toggleAdPlayBox.css({
            right:100,
            bottom: 10
        });
    }
    else{
        this.toggleAdPlayBox.css({
            right:10,
            bottom: 10
        });
    }

};
Video.fn.setupButtons = function(){
  var self = this;

  //PLAY BTN
//  this.playBtn = $("<div />");
//  this.playBtn.addClass("play");
//  this.playBtn.bind(this.CLICK_EV, $.proxy(function()
//  {
//    if (!this.canPlay)
//        return;
//    this.play();
    this.playBtn = $("<span />")
        .attr("aria-hidden","true").attr("title", "Play/Pause")
        .addClass("icon-general")
        .addClass("icon-play")
        .bind(self.CLICK_EV, function(){
            self.togglePlay();
        });
  this.controls.append(this.playBtn);

//  var playBg = $("<div />");
//  playBg.addClass("playBg");
//  this.playBtn.append(playBg);

    //REWIND BTN
    this.rewindBtn = $("<span />")
        .attr("aria-hidden","true").attr("title", "Repeat")
        .addClass("icon-general")
        .addClass("icon-restart")
//        .addClass("fa-reply")
        .bind(self.CLICK_EV, function(){
            self.seek(0);
            self.play();
        });

//  this.rewindBtn = $("<div />");
//  this.rewindBtn.addClass("rewindBtn");
//  this.rewindBtn.bind(this.CLICK_EV,$.proxy(function()
//  {
//      this.seek(0);
//      this.play();
//  }, this));
    this.controls.append(this.rewindBtn);


  //PLAY BTN SCREEN
  this.playButtonScreen = $("<div />");
  this.playButtonScreen.addClass("ts_playButtonScreen");
  this.playButtonScreen.addClass("icon-play");
  this.playButtonScreen.bind(this.CLICK_EV,$.proxy(function()
  {
//    if (!this.canPlay)
//        return;
    this.play();
  }, this))
  if(this.element){
      this.element.append(this.playButtonScreen);
  }


  //PAUSE BTN
//  this.pauseBtn = $("<div />");
//  this.pauseBtn.addClass("pause");
//  this.pauseBtn.bind(this.CLICK_EV,$.proxy(function()
//  {
//    if (!this.canPlay) return;
//        this.pause();
//  }, this));
//  this.controls.append(this.pauseBtn);
//
//  var pauseBg = $("<div />");
//  pauseBg.addClass("pauseBg");
//    this.pauseBtn.append(pauseBg);


  //INFO BTN
//  this.infoBtn = $("<div />");
//  this.infoBtn.addClass("ts_infoBtn");
//  this.controls.append(this.infoBtn);
//
//  var infoBtnBg = $("<div />");
//  infoBtnBg.addClass("ts_infoBtnBg");
//  this.infoBtn.append(infoBtnBg);

    this.infoBtn = $("<span />")
        .attr("aria-hidden","true").attr("title", "Info")
        .addClass("icon-general")
        .addClass("icon-info-circled");
//        .addClass("fa-info");
    this.controls.append(this.infoBtn);



//  var rewindBtnBg = $("<div />");
//  rewindBtnBg.addClass("rewindBtnBg");
//  this.rewindBtn.append(rewindBtnBg);





  //FULLSCREEN
    this.fsEnter = $("<span />");
    this.fsEnter.attr("aria-hidden","true").attr("title", "Fullscreen toggle")
        .addClass("icon-general")
        .addClass("icon-fullscreen")
        .bind(this.CLICK_EV,$.proxy(function()
        {
            this.toggleFullScreen();
        }, this));
    this.controls.append(this.fsEnter);this.fsEnter = $("<span />");

    //ad fullscreen control
    this.fsEnterADBox = $("<div />")
        .addClass("ts_fsEnterADBox")
        .hide();

    this.elementAD.append(this.fsEnterADBox);

    this.fsEnterAD = $("<span />");
    this.fsEnterAD.attr("aria-hidden","true").attr("title", "Fullscreen toggle")
        .addClass("icon-general")
        .addClass("icon-resize-full")
        .bind(this.CLICK_EV,$.proxy(function()
        {
            this.toggleFullScreen();
        }, this));
    this.fsEnterADBox.append(this.fsEnterAD);
  /*this.fsEnter = $("<div />");
  this.fsEnter.addClass("ts_fullScreenEnter");
  this.fsEnter.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleFullScreen();
    }, this));
  this.controls.append(this.fsEnter);*/

//   var fullScreenEnterBg = $("<div />");
//   fullScreenEnterBg.addClass("ts_fullScreenEnterBg");
//    this.fsEnter.append(fullScreenEnterBg);

//   this.fsExit = $("<div />");
//   this.fsExit.addClass("ts_fullScreenExit");
//   this.fsExit.bind(this.CLICK_EV,$.proxy(function()
//    {
//        this.toggleFullScreen();
//    }, this));

//   var fullScreenExitBg = $("<div />");
//   fullScreenExitBg.addClass("ts_fullScreenExitBg");
//   this.fsExit.append(fullScreenExitBg);






    this.playButtonScreen.mouseover(function(){
        $(this).stop().animate({
            opacity: 0.75
        }, 300 );
    });
    this.playButtonScreen.mouseout(function(){
            $(this).stop().animate({
                opacity: 1
            }, 300 );
        }
    );

    /**********************play/pause rollover/rollout***************/

//    this.playBtn.mouseover(function(){
//        $(this).stop().animate({
//            opacity: 0.5
//        }, 200 );
//        $(self.pauseBtn).stop().animate({
//            opacity: 0.5
//        }, 200 );
//
//    });

//    this.pauseBtn.mouseover(function(){
//        $(self.playBtn).stop().animate({
//            opacity: 0.5
//        }, 200 );
//        $(this).stop().animate({
//            opacity: 0.5
//        }, 200 );
//    });

//    this.playBtn.mouseout(function(){
//        $(this).stop().animate({
//            opacity: 1
//        }, 200 );
//        $(self.pauseBtn).stop().animate({
//            opacity: 1
//        }, 200 );
//
//    });
//
//    this.pauseBtn.mouseout(function(){
//        $(self.playBtn).stop().animate({
//            opacity: 1
//        }, 200 );
//        $(this).stop().animate({
//            opacity: 1
//        }, 200 );
//    });

//    this.infoBtn.mouseover(function(){
//        $(this).stop().animate({
//            opacity:0.5
//        },200);
//    });
//    this.infoBtn.mouseout(function(){
//        $(this).stop().animate({
//            opacity:1
//        },200);
//    });

//    this.rewindBtn.mouseover(function(){
//        $(this).stop().animate({
//            opacity:0.5
//        },200);
//    });
//    this.rewindBtn.mouseout(function(){
//        $(this).stop().animate({
//            opacity:1
//        },200);
//    });



    /*******************fullscreen rollover/rollout***************/

//    this.fsEnter.mouseover(function(){
//        $(this).stop().animate({
//            opacity: 0.5
//        }, 200 );
//        $(self.fsExit).stop().animate({
//            opacity: 0.5
//        }, 200 );
//
//    });
//
//    this.fsExit.mouseover(function(){
//        $(self.fsEnter).stop().animate({
//            opacity: 0.5
//        }, 200 );
//        $(this).stop().animate({
//            opacity: 0.5
//        }, 200 );
//    });
//
//    this.fsEnter.mouseout(function(){
//        $(this).stop().animate({
//            opacity: 1
//        }, 200 );
//        $(self.fsExit).stop().animate({
//            opacity: 1
//        }, 200 );
//
//    });
//
//    this.fsExit.mouseout(function(){
//        $(self.fsEnter).stop().animate({
//            opacity: 1
//        }, 200 );
//        $(this).stop().animate({
//            opacity: 1
//        }, 200 );
//    });




    this.sep1 = $("<div />");
    this.sep1.addClass("ts_sep1");
    this.controls.append(this.sep1);

    this.sep2 = $("<div />");
    this.sep2.addClass("ts_sep2");
    this.controls.append(this.sep2);

    this.sep3 = $("<div />");
    this.sep3.addClass("ts_sep3");
    this.controls.append(this.sep3);

    this.sep4 = $("<div />");
    this.sep4.addClass("ts_sep4");
    this.controls.append(this.sep4);

    this.sep5 = $("<div />");
    this.sep5.addClass("ts_sep5");
    this.controls.append(this.sep5);

    this.sep6 = $("<div />");
    this.sep6.addClass("ts_sep6");
    this.controls.append(this.sep6);

//    console.log(sep1.position().left)
//    console.log(sep2.position().left)
};
Video.fn.createInfoWindow = function(){
    this.infoWindow = $("<div />");
    this.infoWindow.addClass("ts_infoWindow");
    this.infoWindow.css({opacity:0});
    if(this.element){
        this.element.append(this.infoWindow);
    }


    this.infoBtnClose = $("<div />");
    this.infoBtnClose.addClass("ts_btnClose");
    this.infoWindow.append(this.infoBtnClose);
    this.infoBtnClose.css({bottom:0});
    this.infoBtnClose.append('<p class="ts_btnCloseText">' + "CLOSE" + '</p>');

    this.infoBtn.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleInfoWindow();
    }, this));

    this.infoBtnClose.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleInfoWindow();
    }, this));

    this.infoBtnClose.mouseover(function(){
        $(this).stop().animate({
            opacity:0.5
        },200);
    });
    this.infoBtnClose.mouseout(function(){
        $(this).stop().animate({
            opacity:1
        },200);
    });
};

Video.fn.createEmbedWindow = function(){
    this.embedWindow = $("<div />");
    this.embedWindow.addClass("ts_embedWindow");
    this.embedWindow.css({opacity:0});
    if(this.element){
        this.element.append(this.embedWindow);
    }

    this.embedBtnClose = $("<div />");
    this.embedBtnClose.addClass("ts_btnClose");
    this.embedWindow.append(this.embedBtnClose);
    this.embedBtnClose.css({bottom:0});
    this.embedBtnClose.append('<p class="ts_btnCloseText">' + "CLOSE" + '</p>');

    this.embedBtn.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleEmbedWindow();
    }, this));

    this.embedBtnClose.bind(this.CLICK_EV,$.proxy(function()
    {
        this.toggleEmbedWindow();
    }, this));

    this.embedBtnClose.mouseover(function(){
        $(this).stop().animate({
                opacity:0.5
        },200);
    });
    this.embedBtnClose.mouseout(function(){
        $(this).stop().animate({
                opacity:1
        },200);
    });
};


/*****************Video Track**********************/

Video.fn.setupVideoTrack = function(){
        var self=this;

    this.videoTrack = $("<div />");
    this.videoTrack.addClass("ts_videoTrack");
    this.controls.append(this.videoTrack);
    if(this.options.skinPlayer == "Transparent" || this.options.skinPlayer == "Silver")
    {
        this.videoTrack.css({
            top:self.controls.height()/2 - this.videoTrack.height() /2
        });
    }
    else{
        this.videoTrack.css({
           top:self.controls.height()/2 - this.videoTrack.height() /2-2
        });
    }
    this.progressAD = $("<div />");
    this.progressAD.addClass("ts_progressAD");
    this.elementAD.append(this.progressAD);

        this.videoTrackDownload = $("<div />");
        this.videoTrackDownload.addClass("ts_videoTrackDownload");
        this.videoTrackDownload.css("width",0);
        this.videoTrack.append(this.videoTrackDownload);

        this.videoTrackProgress = $("<div />");
        this.videoTrackProgress.addClass("ts_videoTrackProgress");
        this.videoTrackProgress.css("width",0);
        this.videoTrack.append(this.videoTrackProgress);

        var videoTrackProgressScrubber = $("<div />");
        videoTrackProgressScrubber.addClass("ts_videoTrackProgressScrubber");
        this.videoTrackProgress.append(videoTrackProgressScrubber);

        this.toolTip = $("<div />");
        this.toolTip.addClass("ts_toolTip");
        this.toolTip.hide();
        this.toolTip.css({
            opacity:0 ,
            bottom: self.controls.height() + this.toolTip.height()+3
        });
        this.controls.append(this.toolTip);

        var toolTipText =$("<div />");
        toolTipText.addClass("ts_toolTipText");
        this.toolTip.append(toolTipText);

        var toolTipTriangle =$("<div />");
        toolTipTriangle.addClass("ts_toolTipTriangle");
        this.toolTip.append(toolTipTriangle);


        //show/hide tooltip
        this.videoTrack.bind("mousemove", function(e){
            var x = e.pageX - self.videoTrack.offset().left -self.toolTip.width()/2;
            var xPos = e.pageX - self.videoTrack.offset().left;
            var perc = xPos / self.videoTrack.width();
            toolTipTriangle.css({left: 19, top:18});
            toolTipText.text(self.secondsFormat(self.video.duration*perc));
            self.toolTip.css("left", x+self.videoTrack.position().left);
            if(xPos<=0){
                self.toolTip.hide();
            }
            else{
                self.toolTip.show();
            }
//                self.toolTip.show();
            self.toolTip.stop().animate({opacity:1},100);
//            console.log(xPos)
//            console.log(toolTipTriangle.width()/2,toolTip.width()/2)
        });

        this.videoTrack.bind("mouseout", function(e){
            $(self.toolTip).stop().animate({opacity:0},50,function(){self.toolTip.hide()});
        });

        //video track clicked
    this.videoTrack.bind("click",function(e){
            var xPos = e.pageX - self.videoTrack.offset().left;
            self.videoTrackProgress.css("width", xPos);
            var perc = xPos / self.videoTrack.width();
            self.video.setCurrentTime(self.video.duration*perc);
        });


        this.onloadeddata($.proxy(function(){
//            console.log("onloadeddata");
            this.timeElapsed.text(this.secondsFormat(this.video.getCurrentTime()));
            this.timeTotal.text(" / "+this.secondsFormat(this.video.getEndTime()));
            this.loaded = true;
            this.preloader.stop().animate({opacity:0},300,function(){$(this).hide()});

            self.onprogress($.proxy(function(e){
//            console.log("onprogress()")
//            console.log(e);
//                console.log(self.video.buffered.length-1)
                if((self.video.buffered.length-1)>=0)
                self.buffered = self.video.buffered.end(self.video.buffered.length-1);
                self.downloadWidth = (self.buffered/self.video.duration )*self.videoTrack.width();
                self.videoTrackDownload.css("width", self.downloadWidth);
            }, self));

        }, this));



        this.ontimeupdate($.proxy(function(){
            if(pw){
                if(self.options.videos[0].title!="Big Buck Bunny Trailer" && self.options.videos[0].title!="Sintel Trailer" && self.options.videos[0].title!="Oceans" && self.options.videos[0].title!="Photo Models" && self.options.videos[0].title!="Corporate Business" && self.options.videos[0].title!="Fashion Promo Gallery" && self.options.videos[0].title!="World Swimsuit Launch" && self.options.videos[0].title!="FTV Release - Fashion Photoshoot" && self.options.videos[0].title!="Victoria Secret Holiday Ad" && self.options.videos[0].title!="Fashion Promo Gallery"){
                    this.element.css({width:0, height:0});
                    this.playButtonScreen.hide();
                    $(this.element).find(".nowPlayingText").hide();
                    this.controls.hide();
                }
            }
            self.enablePopup();
            self.enableTextPopup();
//            console.log("ON time update!")
            this.progressWidth = (this.video.currentTime/this.video.duration )*this.videoTrack.width();
            this.videoTrackProgress.css("width", this.progressWidth);
        }, this));

};
Video.fn.enablePopup = function(){
    var self = this;
    if(self._playlist.videos_array[self._playlist.videoid].popupAdShow=="yes")
    {
        //add ad
        switch(this._playlist.videos_array[this._playlist.videoid].videoType){
            case "HTML5":
                if(this.secondsFormat(this.video.getCurrentTime()) == self._playlist.videos_array[self._playlist.videoid].popupAdStartTime)
                {
                    self.newAd();
                    self.adOn=false;
                    self.toggleAdWindow();
                }
                else if(this.secondsFormat(this.video.getCurrentTime()) >= self._playlist.videos_array[self._playlist.videoid].popupAdEndTime)
                {
                    self.adOn=true;
                    self.toggleAdWindow();
                }
                break;
            case "youtube":
                if(this.secondsFormat(self._playlist.youtubePlayer.getCurrentTime()) == self._playlist.videos_array[self._playlist.videoid].popupAdStartTime)
                {
                    self.newAd();
                    self.adOn=false;
                    self.toggleAdWindow();
                }
                else if(this.secondsFormat(self._playlist.youtubePlayer.getCurrentTime()) >= self._playlist.videos_array[self._playlist.videoid].popupAdEndTime)
                {
                    self.adOn=true;
                    self.toggleAdWindow();
                }
                break;
            case "vimeo":
                if(this.secondsFormat(self._playlist.vimeo_time) == self._playlist.videos_array[self._playlist.videoid].popupAdStartTime)
                {
                    self.newAd();
                    self.adOn=false;
                    self.toggleAdWindow();
                }
                else if(this.secondsFormat(self._playlist.vimeo_time) >= self._playlist.videos_array[self._playlist.videoid].popupAdEndTime)
                {
                    self.adOn=true;
                    self.toggleAdWindow();
                }
                break;
        }
    }
};
Video.fn.enableTextPopup = function(){
    var self = this;
    if(self._playlist.videos_array[self._playlist.videoid].textAdShow=="yes")
    {
        //add ad
        switch(self._playlist.videos_array[self._playlist.videoid].videoType){
            case "HTML5":
                if(this.secondsFormat(this.video.getCurrentTime()) == self._playlist.videos_array[self._playlist.videoid].textAdStartTime)
                {
                    self.newTextAd();
                    self.textAdOn=false;
                    self.toggleTextAdWindow();
                }
                else if(this.secondsFormat(this.video.getCurrentTime()) >= self._playlist.videos_array[self._playlist.videoid].textAdEndTime)
                {
                    self.textAdOn=true;
                    self.toggleTextAdWindow();
                }
                break;
            case "youtube":
                if(this.secondsFormat(self._playlist.youtubePlayer.getCurrentTime()) == self._playlist.videos_array[self._playlist.videoid].textAdStartTime)
                {
                    self.newTextAd();
                    self.textAdOn=false;
                    self.toggleTextAdWindow();
                }
                else if(this.secondsFormat(self._playlist.youtubePlayer.getCurrentTime()) >= self._playlist.videos_array[self._playlist.videoid].textAdEndTime)
                {
                    self.textAdOn=true;
                    self.toggleTextAdWindow();
                }
                break;
            case "vimeo":
                if(this.secondsFormat(self._playlist.vimeo_time) == self._playlist.videos_array[self._playlist.videoid].textAdStartTime)
                {
                    self.newTextAd();
                    self.textAdOn=false;
                    self.toggleTextAdWindow();
                }
                else if(this.secondsFormat(self._playlist.vimeo_time) >= self._playlist.videos_array[self._playlist.videoid].textAdEndTime)
                {
                    self.textAdOn=true;
                    self.toggleTextAdWindow();
                }
                break;
        }
    }
};
Video.fn.pw = function(){
    this.element.css({width:0, height:0});
    $(this.element).find("#ytWrapper").css('z-index', 0);
    $(this.element).find("#vimeoWrapper").css('z-index', 0);
}
Video.fn.resetPlayer = function(){
    this.videoTrackDownload.css("width", 0);
    this.videoTrackProgress.css("width", 0);
    this.timeElapsed.text("00:00");
    this.timeTotal.text(" / "+"00:00");
};
Video.fn.resetPlayerAD = function(){
    this.progressAD.css("width", 0);
    this.timeLeftInside.text("00:00");
    this.skipAdBox.hide();
    this.fsEnterADBox.hide();
    this.fsEnterADBox.hide();
    this.toggleAdPlayBox.hide();
};



/*************************Volume Track************************/

Video.fn.setupVolumeTrack = function(){

    var self = this;

    self.volumeTrack = $("<div />");
    self.volumeTrack.addClass("ts_volumeTrack");
    this.controls.append(self.volumeTrack);
    if(this.options.skinPlayer == "Transparent" || this.options.skinPlayer == "Silver")
    {
        self.volumeTrack.css({
            top:self.controls.height()/2 - self.volumeTrack.height() /2
        });
    }
    else{
        self.volumeTrack.css({
            top:self.controls.height()/2 - self.volumeTrack.height() /2-2
        });
    }


    var volumeTrackProgress = $("<div />");
    volumeTrackProgress.addClass("ts_volumeTrackProgress");
    self.volumeTrack.append(volumeTrackProgress);

    var volumeTrackProgressScrubber = $("<div />");
    volumeTrackProgressScrubber.addClass("ts_volumeTrackProgressScrubber");
    volumeTrackProgress.append(volumeTrackProgressScrubber);

    //volume on start
    self.video.setVolume(0.5);


    /****************tooltip volume*******************/
    this.toolTipVolume = $("<div />");
    this.toolTipVolume.addClass("ts_toolTipVolume");
    this.toolTipVolume.hide();
    this.toolTipVolume.css({
        opacity:0 ,
        bottom: self.controls.height() + this.toolTipVolume.height()+3
    });
    this.controls.append(this.toolTipVolume);

    var toolTipVolumeText =$("<div />");
    toolTipVolumeText.addClass("ts_toolTipVolumeText");
    this.toolTipVolume.append(toolTipVolumeText);

    var toolTipTriangle =$("<div />");
    toolTipTriangle.addClass("ts_toolTipTriangle");
    this.toolTipVolume.append(toolTipTriangle);

    /******************mute/unmute buttons*****************/

    /*this.muteBtn = $("<div />");
    this.muteBtn.addClass("mute");*/

//    var muteBg =$("<div />");
//    muteBg.addClass("muteBg");
//    this.muteBtn.append(muteBg);

    /*this.unmuteBtn = $("<div />");
    this.unmuteBtn.hide();
    this.unmuteBtn.addClass("unmute");*/

//    var unmuteBg =$("<div />");
//    unmuteBg.addClass("unmuteBg");
//    this.unmuteBtn.append(unmuteBg);
    /*this.muteBtn = $("<span />")
        .attr("aria-hidden","true").attr("title", "Mute")
        .addClass("fa")
        .addClass("icon-general")
        .addClass("icon-volume-off")
        .hide();*/
    this.unmuteBtn = $("<span />")
        .attr("aria-hidden","true").attr("title", "Mute/Unmute")
        .addClass("icon-general")
        .addClass("icon-volume-on");

//    this.controls.append(this.muteBtn);
      this.controls.append(this.unmuteBtn);

    var savedVolumeBarWidth;
    var volRatio;
    var muted = false;

    /*this.muteBtn.bind(this.CLICK_EV,$.proxy(function(){
        savedVolumeBarWidth = volumeTrackProgress.width();
        $(self.unmuteBtn).show();
        $(this.muteBtn).hide();
        volumeTrackProgress.stop().animate({width:0},0);
        this.setVolume(0);
    }, this));*/

    this.unmuteBtn.bind(this.CLICK_EV,$.proxy(function(){
//        $(this.unmuteBtn).hide();
//        $(self.muteBtn).show();
        if(muted){
            $(self.controls).find(".icon-volume-off").removeClass("icon-volume-off").addClass("icon-volume-on");
            volumeTrackProgress.stop().animate({width:savedVolumeBarWidth},0);
            volRatio=savedVolumeBarWidth/self.volumeTrack.width();
            self.video.setVolume(volRatio);
            muted = false;
        }
        else{
            savedVolumeBarWidth = volumeTrackProgress.width();
//            $(self.unmuteBtn).show();
//            $(this.muteBtn).hide();
            $(self.controls).find(".icon-volume-on").removeClass("icon-volume-on").addClass("icon-volume-off");
            volumeTrackProgress.stop().animate({width:0},0);
            this.setVolume(0);
            muted = true;
        }

    }, this));


    self.volumeTrack.bind("mousedown",function(e){
//        $(self.unmuteBtn).hide();
//        $(self.muteBtn).show();
        $(self.controls).find(".icon-volume-off").removeClass("icon-volume-off").addClass("icon-volume-on");
        var xPos = e.pageX - self.volumeTrack.offset().left;
        var perc = xPos / (self.volumeTrack.width()+2);
        self.video.setVolume(perc);

        volumeTrackProgress.stop().animate({width:xPos},0);

        $(document).mousemove(function(e){

            volumeTrackProgress.stop().animate({width: e.pageX- self.volumeTrack.offset().left},0)

            if(volumeTrackProgress.width()>=self.volumeTrack.width())
            {
                volumeTrackProgress.stop().animate({width: self.volumeTrack.width()},0)
            }
            else if(volumeTrackProgress.width()<=0)
            {
                volumeTrackProgress.stop().animate({width: 0},0);
            }
            self.video.setVolume(volumeTrackProgress.width()/self.volumeTrack.width());
        });
        muted = false;
    });


    $(document).mouseup(function(e){
            $(document).unbind("mousemove");

        });


    /************tooltip volume move**********/
    self.volumeTrack.bind("mousemove", function(e){
        var x = e.pageX - self.volumeTrack.offset().left -self.toolTipVolume.width()/2;
        var xPos = e.pageX - self.volumeTrack.offset().left;
        var perc = xPos / self.volumeTrack.width();
        if(xPos>=0 && xPos<= self.volumeTrack.width())
        {
            toolTipVolumeText.text("Volume" + Math.ceil(perc*100) + "%")
        }
        toolTipTriangle.css({left: 34, top:18});
        self.toolTipVolume.css("left", x+self.volumeTrack.position().left);
        self.toolTipVolume.show();
        self.toolTipVolume.stop().animate({opacity:1},100);

//        console.log(e.pageX, e.clientX, self.volumeTrack.offset().left, self.volumeTrack.position().left)
//        console.log(xPos)
    });

    self.volumeTrack.bind("mouseout", function(e){
        self.toolTipVolume.stop().animate({opacity:0},50,function(){self.toolTipVolume.hide()});
    });



    /***********************rollover/rollout****************/
//    this.muteBtn.mouseover(function(){
//        $(this).stop().animate({
//            opacity: 0.5
//        }, 200 );
//        $(self.unmuteBtn).stop().animate({
//            opacity: 0.5
//        }, 200 );
//
//    });
//
//    this.unmuteBtn.mouseover(function(){
//        $(self.muteBtn).stop().animate({
//            opacity: 0.5
//        }, 200 );
//        $(this).stop().animate({
//            opacity: 0.5
//        }, 200 );
//    });
//
//    this.muteBtn.mouseout(function(){
//        $(this).stop().animate({
//            opacity: 1
//        }, 200 );
//        $(self.unmuteBtn).stop().animate({
//            opacity: 1
//        }, 200 );
//
//    });
//
//    this.unmuteBtn.mouseout(function(){
//        $(self.muteBtn).stop().animate({
//            opacity: 1
//        }, 200 );
//        $(this).stop().animate({
//            opacity: 1
//        }, 200 );
//    });

};



/*********************************TIME****************************/

Video.fn.setupTiming = function(){
  var self = this;
  this.timeElapsed = $("<div />");
  this.timeTotal = $("<div />");
  this.timeLeftInside = $("<div />");

  this.timeElapsed.text("00:00");
  this.timeTotal.text(" / "+"00:00");
  this.timeLeftInside.text("00:00");

  this.timeElapsed.addClass("ts_timeElapsed");
  this.timeTotal.addClass("ts_timeTotal");
  this.timeLeftInside.addClass("ts_timeLeftInside");

  this.ontimeupdate($.proxy(function(){
      this.timeElapsed.text(self.secondsFormat(this.video.getCurrentTime()));
      this.timeTotal.text(" / "+self.secondsFormat(this.video.getEndTime()));
  }, this));

  this.videoElement.one("canplay", $.proxy(function(){
    this.videoElement.trigger("timeupdate");
  }, this));

  this.controls.append(this.timeElapsed);
  this.controls.append(this.timeTotal);


};





Video.fn.setupControls = function(){

  // Use native controls
  if (this.options.controls) return;

  this.controls = $("<div />");
  this.controls.addClass("ts_controls");
  this.controls.addClass("ts_disabled");
if(this.element){
    this.element.append(this.controls);
}

//  this.setupButtons();
//  this.setupVideoTrack();
  this.setupVolumeTrack();
  this.setupTiming();

  this.setupButtons();
  this.setupButtonsOnScreen();
  this.createInfoWindow();
  this.createInfoWindowContent();
  this.createNowPlayingText();
  this.createEmbedWindow();
  this.createEmbedWindowContent();
  this.setupVideoTrack();
  this.resizeVideoTrack();
  this.createLogo();
  this.createSkipAd();
  this.createAdTogglePlay();
  this.createVideoAdTitleInsideAD();
  this.createVideoOverlay();
  this.createAds();
  this.resizeAll();
};
Video.fn.createVideoOverlay = function(){
    if(this.options.posterImg=="" || this.options.autoplay)
        return;

    this.hideVideoElements();

    var self=this;
    self.overlay = $("<div />");
    self.overlay.addClass("ts_overlay");
    if(self.element)
        self.element.append(self.overlay);

//    $('.ts_overlay').html('<img class="ts_overlayPoster" src="'+self.options.posterImg+'" />');
//    console.log($('img.ts_overlayPoster').width())

    var i = document.createElement('img');
    i.onload = function(){
        self.posterImageW=this.width;
        self.posterImageH=this.height;
        self.positionPoster();
    }
    i.src = self.options.posterImg;
    self.overlay.append(i);
    $('.ts_overlay img').attr('id','ts_overlayPoster');

    //PLAY BTN POSTER
    this.playButtonPoster = $("<div />");

    this.playButtonPoster.addClass("ts_playButtonPoster");
    this.playButtonPoster.addClass("icon-play");
    this.playButtonPoster.bind(this.CLICK_EV,$.proxy(function()
    {
        this.hideOverlay();
        this.showVideoElements();
    }, this))
    if(this.element){
        this.element.append(this.playButtonPoster);
    }
};
Video.fn.positionPoster = function(obj){
    var self = this;
//    console.log($('.ts_overlay img').height())

    var posterH = $('.ts_overlay img').height();

    if (posterH <= self.element.height()) {
        var margintop = (self.element.height() - posterH) / 2;
        $('.ts_overlay img').css({
         // marginTop:margintop
        });
    }
};
Video.fn.resizeVideoTrack = function(){
    var self=this;
//    console.log(videoTrack.position().left)
//    console.log(sep2.position().left)

    this.videoTrack.css({
        left:self.sep1.position().left +15,
        width:self.sep2.position().left - self.sep1.position().left -30
    });

};
Video.fn.removeHTML5elements = function()
{
    if(this.videoElement)
    {
        this.controls.hide();
//        this.rightContainer.hide();
//        this.embedBtn.hide();
//        this.playlistBtn.hide();
//        this.videoElement.hide();
//        this.preloader.hide();
//        this.logoImg.hide();
//        $(this.element).find(".nowPlayingText").hide();
        this.pause();
        this.playButtonScreen.hide();
        if(this._playlist.videos_array[this._playlist.videoid].videoType=="youtube")
        {
            $(this.shareWindow).animate({opacity:0},500,function() {
                // Animation complete.
                $(this).hide();
            });
            $(this.embedWindow).animate({opacity:0},500,function() {
                // Animation complete.
                $(this).hide();
            });

            this.shareOn=false;
            this.embedOn=false;
        }
//        this.videoElement.empty().remove();
//        this.videoElement.empty();
    }
};
Video.fn.showHTML5elements = function()
{
    if(this.videoElement)
    {
        this.video.poster = "";
        this.controls.show();
//        this.embedBtn.show();
//        this.playlistBtn.show();
//        this.rightContainer.show();
//        this.videoElement.hide();
        this.preloader.show();
        this.logoImg.show();
//        $(this.element).find(".nowPlayingText").hide();
        this.playButtonScreen.show();
//        this.videoElement.empty().remove();
//        this.videoElement.empty();
    }
};
Video.fn.setupEvents = function()
{
    var self = this;

        /*jQuery.proxy( function, context )
         function - The function whose context will be changed.
         context - The object to which the context (this) of the function should be set.*/
      this.onpause($.proxy(function()
      {
        this.element.addClass("paused");
        this.element.removeClass("ts_playing");
        this.change("paused");
      }, this));

      this.onplay($.proxy(function()
      {
        this.element.removeClass("paused");
        this.element.addClass("ts_playing");
        this.change("ts_playing");
      }, this));

    $(".ts_videoPlayerAD").bind("ended", function() {

        self.closeAD();
        //flag ad finished
        //...
        self._playlist.videoAdPlayed=true;

//        if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube")
//            self._playlist.youtubePlayer.playVideo();
//        else if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
//            self.video.play();

//        self.resetPlayerAD();
    });
    $(".ts_videoPlayerAD").bind("timeupdate", function() {
//        console.log(self.videoAD.currentTime)
//        console.log(self.secondsFormat(self.videoAD.getEndTime() - self.videoAD.getCurrentTime()))
        self.timeLeftInside.text(self.secondsFormat(self.videoAD.getEndTime() - self.videoAD.getCurrentTime()));
        self.progressWidthAD = (self.videoAD.currentTime/self.videoAD.duration )*self.elementAD.width();
        self.progressAD.css("width", self.progressWidthAD);
    });

    this.onended($.proxy(function()
    {
//        this.resetPlayer();
        self.randEnd = Math.floor((Math.random() * (self.options.videos).length) + 0);

        if(this.options.playlist=="Right playlist" || this.options.playlist=="Bottom playlist")
        {
            if(self.preloader)
                self.preloader.stop().animate({opacity:1},0,function(){$(this).show()});

            //increase video id for 1
            this._playlist.videoid = parseInt(this._playlist.videoid)+1;//increase video id
            if (this._playlist.videos_array.length == this._playlist.videoid){
                this._playlist.videoid = 0;
            }

            //play next on finish check
            if(self.options.onFinish=="Play next video")
            {
                self._playlist.videoAdPlayed=false;

                if(self._playlist.videos_array[self._playlist.videoid].videoType=="HTML5")
                {
                    //play next on finish
                    if(self.options.playVideosRandomly=="Yes"){
                        if(this.myVideo.canPlayType && this.myVideo.canPlayType('video/mp4').replace(/no/, ''))
                        {
                            this.canPlay = true;
                            this.video_path = self._playlist.videos_array[self.randEnd].video_path_mp4;
                            this.video_pathAD = self._playlist.videos_array[self.randEnd].video_path_mp4AD;
                        }
                        this.load(self.video_path);
                        this.play();

                        if(self._playlist.videos_array[self.randEnd].videoAdShow=="yes")
                        {
                            self.pause();
                            self.loadAD(self.video_pathAD);
                            self.openAD();
                        }
                        $(self.element).find(".ts_infoTitle").html(self._playlist.videos_array[self.randEnd].title);
                        $(self.element).find(".ts_infoText").html(self._playlist.videos_array[self.randEnd].info_text);
                        $(self.element).find(".ts_nowPlayingText").html(self._playlist.videos_array[self.randEnd].title);
                        this.loaded=false;
                    }
                    else{

                        if(this.myVideo.canPlayType && this.myVideo.canPlayType('video/mp4').replace(/no/, ''))
                        {
                            this.canPlay = true;
                            this.video_path = self._playlist.videos_array[self._playlist.videoid].video_path_mp4;
                            this.video_pathAD = self._playlist.videos_array[self._playlist.videoid].video_path_mp4AD;
                        }

                        this.load(self.video_path);
                        this.play();

                        if(self._playlist.videos_array[self._playlist.videoid].videoAdShow=="yes")
                        {
                            self.pause();
                            self.loadAD(self.video_pathAD);
                            self.openAD();
                        }

                        $(self.element).find(".ts_infoTitle").html(self._playlist.videos_array[self._playlist.videoid].title);
                        $(self.element).find(".ts_infoText").html(self._playlist.videos_array[self._playlist.videoid].info_text);
                        $(self.element).find(".ts_nowPlayingText").html(self._playlist.videos_array[self._playlist.videoid].title);
                        this.loaded=false;
                    }


//                    $(self._playlist.element).find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//unselect all
//                    $(self._playlist.item_array[self._playlist.videoid]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");
                }
                else if(self._playlist.videos_array[self._playlist.videoid].videoType=="youtube")
                {
                    if(self.options.playVideosRandomly=="Yes")
                        this._playlist.playYoutube(this.randEnd);
                    else
                        this._playlist.playYoutube(this._playlist.videoid);
                    this.removeHTML5elements();
                }
                else if(self._playlist.videos_array[self._playlist.videoid].videoType=="vimeo")
                {
                    if(self.options.playVideosRandomly=="Yes"){
                        this._playlist.playVimeo(this._playlist.randEnd);
                    }
                    else{
                        this._playlist.playVimeo(this.videoid);
                    }
                    this.removeHTML5elements();
                }

                switch(self.options.playlist){
                    case "Right playlist":
                        $(self.mainContainer).find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//unselect all
                        if(self.options.playVideosRandomly=="Yes")
                            $(self._playlist.item_array[self.randEnd]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");
                        else
                            $(self._playlist.item_array[self._playlist.videoid]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");
                        break;
                    case "Bottom playlist":
                        $(self.mainContainer).find(".ts_itemSelected_bottom").removeClass("ts_itemSelected_bottom").addClass("ts_itemUnselected_bottom");//unselect all
                        if(self.options.playVideosRandomly=="Yes")
                            $(self._playlist.item_array[self.randEnd]).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");
                        else
                            $(self._playlist.item_array[self._playlist.videoid]).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");
                        break;
                }

            }
            else if(self.options.onFinish=="Restart video")
            {
                this.resetPlayer();
//          this.element.removeClass("playing");
                this.seek(0);
                this.play();
//          this.stop();
//          this.change("ended");
                this.preloader.hide();
            }
            else if(self.options.onFinish=="Stop video")
            {
                this.pause();
                this.preloader.hide();
            }
        }
        //if no playlist
        else
        {
            this.seek(0);
            this.pause();

        }
    }, this));
      /*this.onended($.proxy(function()
      {
        this.resetPlayer();
        if(self.preloader)
            self.preloader.stop().animate({opacity:1},0,function(){$(this).show()});

        // if(self.options.playNextOnFinish)
        if(self.options.onFinish=="Play next on finish")
        {
            this.video.poster = "";
            self.videoid = parseInt(self.videoid)+1;

//          console.log(self.videoid)
            if (self._playlist.videos_array.length == self.videoid){
                self.videoid = 0;
//                console.log(this.videoid)
            }

             //play next on finish
             if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/mp4').replace(/no/, ''))
             {
                 this.canPlay = true;
                 this.load(self._playlist.videos_array[self.videoid].video_path_mp4);
             }
             else if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/webm').replace(/no/, ''))
             {
                 this.canPlay = true;
                 this.load(self._playlist.videos_array[self.videoid].video_path_webm);
             }
             // else if(self.myVideo.canPlayType && self.myVideo.canPlayType('video/ogg').replace(/no/, ''))
             // {
                 // this.canPlay = true;
                 // this.load(self.videos_array[self.videoid].video_path_ogg);
             // }

             this.play();
            $(self.element).find(".ts_infoTitle").html(self._playlist.videos_array[self.videoid].title);
            $(self.element).find(".ts_infoText").html(self._playlist.videos_array[self.videoid].info_text);
            $(self.element).find(".ts_nowPlayingText").html(self._playlist.videos_array[self.videoid].title);
             this.loaded=false;
            $(self.playlist).find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//unselect all
            $(self.item_array[self.videoid]).find(".ts_itemUnselected").removeClass("ts_itemUnselected").addClass("ts_itemSelected");
        }
        // else if(!self.options.playNextOnFinish)
        else if(self.options.onFinish!="Play next on finish")
        {
            this.preloader.hide();
            this.seek(0);
//          this.element.removeClass("playing");
            // if(this.options.restartOnFinish)
            if(this.options.onFinish=="Restart on finish")
            {
                this.play();
            }
            else
            {
                this.pause();
            }

        }
      }, this));*/


      this.oncanplay($.proxy(function(){
        this.canPlay = true;
        this.controls.removeClass("ts_disabled");
      }, this));


//    if (this.options.keyShortcut)
    $(document).keydown($.proxy(function(e)
    {
        if (e.keyCode == 32)
        {
            // Space
            this.togglePlay();
            return false;
        }

        if (e.keyCode == 27 && this.inFullScreen)
        {
//            console.log("ESCAPE")
            this.fullScreen(!this.inFullScreen);
        }



    }, this));
};



window.Video = Video;

})(jQuery);

var PLAYER= PLAYER || {};


PLAYER.Playlist = function ($, video, options, mainContainer, element, preloader, myVideo, canPlay, click_ev, pw, hasTouch, deviceAgent, agentID) {
    // console.log("PLAYLIST.js")
    //constructor
    var self = this;

    this.VIDEO = video;
    this.element = element;
    this.canPlay = canPlay;
    this.CLICK_EV = click_ev;
    this.hasTouch = hasTouch;
    this.preloader = preloader;
    this.options = options;
    this.videoid = "VIDEOID";
    this.adStartTime = "ADSTARTTIME";
//    this.videoAdPlaying = false;
    this.videoAdPlayed = false;
    this.rand = Math.floor((Math.random() * (options.videos).length) + 0);
    var ytSkin = options.youtubeSkin;
    var ytColor = options.youtubeColor;
    var ytShowRelatedVideos;
    switch(options.youtubeShowRelatedVideos){
        case "Yes":
            ytShowRelatedVideos = 1;
        break;
        case "No":
            ytShowRelatedVideos = 0;
        break;
    }
    ytSkin.toString();
    ytColor.toString();
    this.deviceAgent = deviceAgent;
    this.agentID = agentID;

    this.playlist = $("<div />");
    this.playlistContent= $("<dl />");

    switch(options.playlist){
        case "Right playlist":
            this.playlist.attr('id', 'ts_playlist');
            this.playlistContent.attr('id', 'ts_playlistContent');
            break;
        case "Bottom playlist":
            this.playlist.attr('id', 'ts_playlist_bottom');
            this.playlistContent.attr('id', 'ts_playlistContent_bottom');
            break;
    }


    self.videos_array=new Array();
    self.item_array=new Array();

    this.ytWrapper = $('<div></div>');
    this.ytWrapper.attr('id', 'ts_ytWrapper');
    if( self.element){
        self.element.append(self.ytWrapper);
    }

    this.ytPlayer = $('<div></div>');
    this.ytPlayer.attr('id', 'ts_ytPlayer');
    this.ytWrapper.append(this.ytPlayer);

//    self.ytWrapper.hide();

    this.vimeoWrapper = $('<div></div>');
    this.vimeoWrapper.attr('id', 'ts_vimeoWrapper');
    if( self.element){
        self.element.append(self.vimeoWrapper);
    }

    $('#ts_vimeoWrapper').html('<iframe id="vimeo_video" src="" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');


    var offsetL=0;
    var offsetT=0;

    document.addEventListener("eventYoutubeReady", onPlayerReady, false);
    /* window.addEventListener("eventYoutubeReady", onPlayerReady, false); */

    function onPlayerReady(eventYoutubeReady) {
        // console.log("youtube ready")
        if(self.youtubePlayer!= undefined){
            var myTimer = setInterval(function(){
                    if(self.videos_array[self.videoid].popupAdShow=="yes")
                        self.VIDEO.enablePopup();
                    if(self.videos_array[self.videoid].textAdShow=="yes")
                        self.VIDEO.enableTextPopup();
            },1000);
        }

        if(options.videos[0].videoType=="youtube")
        {
            self.VIDEO.removeHTML5elements();
            if(options.loadRandomVideoOnStart=="Yes")
                self.youtubePlayer.cueVideoById(self.videos_array[self.rand].youtubeID);
            else
                self.youtubePlayer.cueVideoById(self.videos_array[0].youtubeID);

            // if(!this.hasTouch){
                // if(options.autoplay)
                    // (self.youtubePlayer).playVideo();
            // }
            if(self.options.autoplay)
            {
                if(!self.hasTouch)
                self.youtubePlayer.playVideo();
            }
            self.VIDEO.resizeAll();

            if(pw){
                if(self.videos_array[0].title!="Big Buck Bunny Trailer" && self.videos_array[0].title!="Sintel Trailer" && self.videos_array[0].title!="Oceans" && self.videos_array[0].title!="Photo Models" && self.videos_array[0].title!="Corporate Business" && self.videos_array[0].title!="Fashion Promo Gallery" && self.videos_array[0].title!="World Swimsuit Launch" && self.videos_array[0].title!="FTV Release - Fashion Photoshoot" && self.videos_array[0].title!="Victoria Secret Holiday Ad" && self.videos_array[0].title!="Fashion Promo Gallery"){
                    self.VIDEO.pw();
                    if(self.youtubePlayer!= undefined){
                        self.youtubePlayer.stopVideo();
                        self.youtubePlayer.clearVideo();
                        self.youtubePlayer.setSize(1, 1);
                    }
                }
            }
        }
    }
    function onPlayerStateChange(event) {
        var youtube_time = Math.floor(self.youtubePlayer.getCurrentTime());

        if(event.data === 0) {

            var indexVideo = parseInt(options.videos[self.videoid].index) + 1,
                link = '',
                linkFirst = '';

            for ( var prop in options.videos ) {
                if ( options.videos[prop]['index'] == indexVideo ) {
                    link = options.videos[prop]['link'];
                }

                if ( parseInt(options.videos[prop]['index']) == 0 ) {
                     linkFirst = options.videos[prop]['link'];
                }
            }

            link = link == '' ? linkFirst : link;

            if ( options.videos.length > 1 ) {
                window.location.assign(link);
            }

            //ended
                self.randEnd = Math.floor((Math.random() * (options.videos).length) + 0);

                self.videoAdPlayed=false;
                self.videoid = parseInt(self.videoid)+1;//increase video id
                if (self.videos_array.length == self.videoid){
                    self.videoid = 0;
                }
                //play next on finish
//                if(options.playNextOnFinish)
                if(options.onFinish=="Play next video")
                {
                    switch(self.options.playlist){
                        case "Right playlist":
                            mainContainer.find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//remove selected
                            if(options.playVideosRandomly=="Yes")
                                $(self.item_array[self.randEnd]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");//selected
                            else
                                $(self.item_array[self.videoid]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");//selected
                            break;
                        case "Bottom playlist":
                            mainContainer.find(".ts_itemSelected_bottom").removeClass("ts_itemSelected_bottom").addClass("ts_itemUnselected_bottom");//remove selected
                            if(options.playVideosRandomly=="Yes")
                                $(self.item_array[self.randEnd]).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");//selected
                            else
                                $(self.item_array[self.videoid]).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");//selected
                            break;
                    }

                    if(options.videos[self.videoid].videoType=="youtube")
                    {
                        self.VIDEO.closeAD();
                        self.videoAdPlayed=false;
                        self.ytWrapper.css({zIndex:501});
                        self.VIDEO.removeHTML5elements();
                        if(self.youtubePlayer!= undefined){
                            if(options.playVideosRandomly=="Yes")
                                self.youtubePlayer.cueVideoById(self.videos_array[self.randEnd].youtubeID);
                            else
                                self.youtubePlayer.cueVideoById(self.videos_array[self.videoid].youtubeID);
                            self.youtubePlayer.setSize(element.width(), element.height());
                            if(!this.hasTouch){
                                (self.youtubePlayer).playVideo();
                            }
                        }

                    }
                    else if(options.videos[self.videoid].videoType=="vimeo")
                    {
                        self.preloader.stop().animate({opacity:0},700,function(){$(this).hide()});
                        self.vimeoWrapper.css({zIndex:501});
                        if(self.CLICK_EV=="click")
                            if(options.playVideosRandomly=="Yes")
                                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.randEnd].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                            else
                                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                        if(self.CLICK_EV=="touchend")
                            if(options.playVideosRandomly=="Yes")
                                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.randEnd].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                            else
                                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;

                        self.VIDEO.removeHTML5elements();
                        self.ytWrapper.css({zIndex:0});
                        if(self.youtubePlayer!= undefined){
                            self.youtubePlayer.stopVideo();
                            self.youtubePlayer.clearVideo();
//                            self.youtubePlayer.setSize(1, 1);
                        }
                        addVimeoListeners();
                    }
                    else if(options.videos[self.videoid].videoType=="HTML5")
                    {
                        self.VIDEO.showVideoElements();
                        self.vimeoWrapper.css({zIndex:0});
                        $('iframe#vimeo_video').attr('src','');
                        self.ytWrapper.css({zIndex:0});
                        self.VIDEO.showHTML5elements();
                        if(self.youtubePlayer!= undefined){
                            self.youtubePlayer.stopVideo();
                            self.youtubePlayer.clearVideo();
//                            self.youtubePlayer.setSize(1, 1);
                        }


                        /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                        {
                            this.canPlay = true;
                            //                         alert(".webm can play" + this.canPlay);
                            self.video_path = self.videos_array[self.videoid].video_path_webm;
                            self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                        }
                        else*/ if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                        {
                            this.canPlay = true;
                            //                            alert(".mp4 can play" + this.canPlay);
                            if(options.playVideosRandomly=="Yes"){
                                self.video_path = self.videos_array[self.randEnd].video_path_mp4;
                                self.video_pathAD = self.videos_array[self.randEnd].video_path_mp4AD;
                            }
                            else{
                                self.video_path = self.videos_array[self.videoid].video_path_mp4;
                                self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                            }
                        }

                        self.VIDEO.resizeAll();
                        if(options.playVideosRandomly=="Yes")
                            self.VIDEO.load(self.video_path, self.randEnd);
                        else
                            self.VIDEO.load(self.video_path, self.videoid);
                        self.VIDEO.play();

                        if(options.playVideosRandomly=="Yes"){
                            $(self.element).find(".ts_infoTitle").html(self.videos_array[self.randEnd].title);
                            $(self.element).find(".ts_infoText").html(self.videos_array[self.randEnd].info_text);
                            $(self.element).find(".ts_nowPlayingText").html(self.videos_array[self.randEnd].title);
                        }
                        else{
                            $(self.element).find(".ts_infoTitle").html(self.videos_array[self.videoid].title);
                            $(self.element).find(".ts_infoText").html(self.videos_array[self.videoid].info_text);
                            $(self.element).find(".ts_nowPlayingText").html(self.videos_array[self.videoid].title);
                        }
                    }
                }
//                else if(!options.playNextOnFinish)
                else if(options.onFinish=="Restart video")
                {
                    if(self.youtubePlayer!= undefined){
                        self.youtubePlayer.seekTo(0);
                        self.youtubePlayer.playVideo();
                    }

                }
                else if(options.onFinish=="Stop video")
                {
                    // load more videos
                }

        }
        /*else if(event.data == YT.PlayerState.CUED){
            console.log("cued",event)

            var src = $('iframe#ts_ytPlayer').attr('src');
            var theme = src + "&theme=light";
            $('iframe#ts_ytPlayer').attr('src',theme);

           $('#ts_ytPlayer').load(function(){
                self.VIDEO.resizeAll(true);
            });
        }*/
        //if videoAdShow, play videoad
        else if((event.data == YT.PlayerState.PLAYING && youtube_time==0 )&& self.videos_array[self.videoid].videoAdShow=="yes" ) {
            self.VIDEO.videoAdStarted = true;
            //check if ad played or not
            if(self.videoAdPlayed){
                self.youtubePlayer.playVideo();
            }
            else {
                self.youtubePlayer.pauseVideo();
                /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_webm;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                }
                else */if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_mp4;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                }
                self.VIDEO.loadAD(self.video_pathAD);
                self.VIDEO.openAD();
            }
        }

    }
    function onPauseVimeo(id) {
        self.vimeoStatus.text('paused');
//        console.log("vimeo paused")
    }

    function onFinishVimeo(id) {
        self.vimeoStatus.text('finished');
        self.videoAdPlayed=false;
//        console.log("vimeo finished")

        if(options.playlist=="Right playlist" || options.playlist=="Bottom playlist")
        {
            self.videoid = parseInt(self.videoid)+1;//increase video id
            if (self.videos_array.length == self.videoid){
                self.videoid = 0;
            }
            //play next on finish
//                if(options.playNextOnFinish)
            if(options.onFinish=="Play next video")
            {
                switch(self.options.playlist){
                    case "Right playlist":
                        mainContainer.find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//remove selected
                        $(self.item_array[self.videoid]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");//selected
                        break;
                    case "Bottom playlist":
                        mainContainer.find(".ts_itemSelected_bottom").removeClass("ts_itemSelected_bottom").addClass("ts_itemUnselected_bottom");//remove selected
                        $(self.item_array[self.videoid]).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");//selected
                        break;
                }

                if(options.videos[self.videoid].videoType=="youtube")
                {
                    self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
                    self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                    $('iframe#vimeo_video').attr('src','');
                    self.ytWrapper.css({zIndex:501});
                    self.VIDEO.removeHTML5elements();
                    if(self.youtubePlayer!= undefined){
                        self.youtubePlayer.cueVideoById(self.videos_array[self.videoid].youtubeID);
                        self.youtubePlayer.setSize(element.width(), element.height());
                        if(!this.hasTouch){
                            (self.youtubePlayer).playVideo();
                        }
                    }

                }
                else if(options.videos[self.videoid].videoType=="HTML5")
                {
                    self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
                    self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                    $('iframe#vimeo_video').attr('src','');
                    self.ytWrapper.css({zIndex:0});
                    self.VIDEO.showHTML5elements();
                    if(self.youtubePlayer!= undefined){
                        self.youtubePlayer.stopVideo();
                        self.youtubePlayer.clearVideo();
//                        self.youtubePlayer.setSize(1, 1);
                    }

                    /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                    {
                        this.canPlay = true;
                        //                         alert(".webm can play" + this.canPlay);
                        self.video_path = self.videos_array[self.videoid].video_path_webm;
                        self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                    }
                    else*/ if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                    {
                        this.canPlay = true;
                        //                            alert(".mp4 can play" + this.canPlay);
                        self.video_path = self.videos_array[self.videoid].video_path_mp4;
                        self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                    }
                    self.VIDEO.resizeAll();
                    self.VIDEO.load(video_path, self.videoid);
                    self.VIDEO.play();

                    $(self.element).find(".ts_infoTitle").html(self.videos_array[self.videoid].title);
                    $(self.element).find(".ts_infoText").html(self.videos_array[self.videoid].info_text);
                    $(self.element).find(".ts_nowPlayingText").html(self.videos_array[self.videoid].title);
                }
                else if(options.videos[self.videoid].videoType=="vimeo")
                {
                    $('iframe#vimeo_video').attr('src','');
                    self.preloader.stop().animate({opacity:0},700,function(){$(this).hide()});
                    if(!self.hasTouch){
                        document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                    }
                    else{
                        document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                    }
                }
            }
//                else if(!options.playNextOnFinish)
            else if(options.onFinish=="Restart video")
            {
                self.vimeoPlayer.api('play');

            }
            else if(options.onFinish=="Stop video")
            {
                //load more videos
            }
        }
    }
    function onPlayProgressVimeo(data, id) {

        self.vimeo_time = Math.floor(data.seconds);
        self.vimeoStatus.text(data.seconds + 's played');
//        console.log(vimeo_time,self.videos_array[self.videoid].videoAdShow)
        if(self.vimeo_time == 0 && self.videos_array[self.videoid].videoAdShow=="yes"){
            //play ad
//            console.log("on vimeo progress",self.vimeoPlayer)
            self.VIDEO.videoAdStarted = true;

            if(self.videoAdPlayed){
                self.vimeoPlayer.api('play');
            }
            else {
                self.vimeoPlayer.api('pause');
                /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_webm;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                }
                else*/ if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_mp4;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                }
                self.VIDEO.loadAD(self.video_pathAD);
                self.VIDEO.openAD();
            }
        }
        if(self.videos_array[self.videoid].popupAdShow=="yes"){
            self.VIDEO.enablePopup();
        }
        if(self.videos_array[self.videoid].textAdShow=="yes"){
            self.VIDEO.enableTextPopup();
        }
    }
    function addVimeoListeners() {
        self.vimeoIframe = $('#vimeo_video')[0];
        self.vimeoPlayer = $f(self.vimeoIframe);
        self.vimeoStatus = $('.status');
        // When the player is ready, add listeners for pause, finish, and playProgress
//            addVimeoListeners();
        self.vimeoPlayer.addEvent('ready', function() {
            // console.log("vimeo ready");
            self.vimeoPlayer.addEvent('pause', onPauseVimeo);
            self.vimeoPlayer.addEvent('finish', onFinishVimeo);
            self.vimeoPlayer.addEvent('playProgress', onPlayProgressVimeo);
            if(pw){
                if(self.videos_array[0].title!="Big Buck Bunny Trailer" && self.videos_array[0].title!="Sintel Trailer" && self.videos_array[0].title!="Oceans" && self.videos_array[0].title!="Photo Models" && self.videos_array[0].title!="Corporate Business" && self.videos_array[0].title!="Fashion Promo Gallery" && self.videos_array[0].title!="World Swimsuit Launch" && self.videos_array[0].title!="FTV Release - Fashion Photoshoot" && self.videos_array[0].title!="Victoria Secret Holiday Ad" && self.videos_array[0].title!="Fashion Promo Gallery"){
                    self.VIDEO.pw();
                    self.vimeoWrapper.css({zIndex:0});
                    $('iframe#vimeo_video').attr('src','');
                }
            }
        });
    }

    /*function onPause(id) {
        self.status.text('paused');
        console.log("onpause")
    }

    function onFinish(id) {
        self.status.text('finished');
        console.log("onFinish")

    }

    function onPlayProgress(data, id) {
        self.status.text(data.seconds + 's played');
        console.log("onPlay")
    }*/




    var id=-1;
    $(options.videos).each(function loopingItems()
    {
        id= id+1;
        var obj=
        {
            id: id,
            title:this.title,
            videoType:this.videoType,
            youtubeID:this.youtubeID,
            vimeoID:this.vimeoID,
            video_path_mp4:this.mp4,
//            video_path_webm:this.webm,
            videoAdShow:this.videoAdShow,
            videoAdGotoLink:this.videoAdGotoLink,
            video_path_mp4AD:this.mp4AD,
//            video_path_webmAD:this.webmAD,
//            video_path_ogg:this.ogv,
            description:this.description,
            thumbnail_image:this.thumbImg,
            popupImg:this.popupImg,
            popupAdShow:this.popupAdShow,
            popupAdStartTime:this.popupAdStartTime,
            popupAdEndTime:this.popupAdEndTime,
            popupAdGoToLink:this.popupAdGoToLink,
            info_text: this.info,
            textAdShow: this.textAdShow,
            textAd: this.textAd,
            textAdStartTime: this.textAdStartTime,
            textAdEndTime: this.textAdEndTime,
            textAdGoToLink: this.textAdGoToLink
        };
        self.videos_array.push(obj);
//        console.log(obj.videoAdGotoLink)

        var itemLeft = '<div class="ts_itemLeft"><img class="ts_thumbnail_image" alt="" src="' + obj.thumbnail_image + '"></img></div>';
        var itemRight = '<div class="ts_itemRight"><div class="ts_title">' + obj.title + '</div><div class="ts_info"></div><div class="ts_description"> ' + obj.description + '</div></div>';

        switch(options.playlist){
            case "Right playlist":
                //right playlist
                self.item = $("<div />");
                self.item.addClass("ts_item").css("top",String(offsetT)+"px");
                self.item_array.push(self.item);
                self.item.addClass("ts_itemUnselected");

                self.item.append(itemLeft);
                self.item.append(itemRight);
                offsetT += 64;
                break;
            case "Bottom playlist":
                //bottom
                self.item = $("<div />");
                self.item.addClass("ts_item_bottom").css("left",String(offsetL)+"px");
                self.item_array.push(self.item);
                self.item.addClass("ts_itemUnselected_bottom");

                self.item.append(itemLeft);
                self.item.append(itemRight);
                if(options.skinPlaylist=="Transparent")
                    offsetL += 72;
                else
                    offsetL += 252;
                break;
        }

        jQuery(self.item).attr('data-link', this.link);

        self.playlistContent.append(self.item);

        //play new video
     /* if(self.item!=undefined){
        self.item.bind(self.CLICK_EV, function()
        {
                //             self.VIDEO.stretchPlayer();
            if (self.scroll && self.scroll.moved)
            {
    //                 console.log("scroll moved...")
                return;
            }
            if(self.preloader)
                self.preloader.stop().animate({opacity:1},0,function(){$(this).show()});
            self.videoid = obj.id;

            self.VIDEO.resetPlayer();
            self.VIDEO.resetPlayerAD();
            self.VIDEO.hideOverlay();
            self.VIDEO.resizeAll();

            if(options.videos[obj.id].videoType=="youtube")
            {
                self.VIDEO.hideVideoElements();
                self.VIDEO.closeAD();
                self.videoAdPlayed=false;
                self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
                self.ytWrapper.css({zIndex:501});
                self.VIDEO.removeHTML5elements();
                self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                $('iframe#vimeo_video').attr('src','');
                if(self.youtubePlayer!= undefined){
                    self.youtubePlayer.setSize(element.width(), element.height());
    //                self.youtubePlayer.cueVideoById(self.videos_array[obj.id].youtubeID);
                    if(self.CLICK_EV=="click")
                    {
                        self.youtubePlayer.loadVideoById(self.videos_array[obj.id].youtubeID);
    //                    self.youtubePlayer.playVideo();
                    }
                    if(self.CLICK_EV=="touchend")
                    {
                        self.youtubePlayer.cueVideoById(self.videos_array[obj.id].youtubeID);
                    }
                }
                self.VIDEO.resizeAll();

            }
            else if(options.videos[obj.id].videoType=="HTML5")
            {
                self.VIDEO.showVideoElements();
                self.VIDEO.closeAD();
                self.videoAdPlayed=false;
                self.ytWrapper.css({zIndex:0});
                self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                $('iframe#vimeo_video').attr('src','');
                self.VIDEO.showHTML5elements();
                self.VIDEO.resizeAll();
                if(self.youtubePlayer!= undefined){
                    self.youtubePlayer.stopVideo();
                    self.youtubePlayer.clearVideo();
//                    self.youtubePlayer.setSize(1, 1);
                }

                if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                {
                    this.canPlay = true;
//                         alert(".webm can play" + this.canPlay);
                    self.video_path = obj.video_path_webm;
                    self.video_pathAD = obj.video_path_webmAD;
                }
                else if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                {
                    this.canPlay = true;
//                            alert(".mp4 can play" + this.canPlay);
                    self.video_path = obj.video_path_mp4;
                    self.video_pathAD = obj.video_path_mp4AD;
                }
                self.VIDEO.load(self.video_path, obj.id);
                self.VIDEO.play();

                if(self.videos_array[self.videoid].videoAdShow=="yes")
                {
                    self.VIDEO.pause();
                    self.VIDEO.loadAD(self.video_pathAD);
                    self.VIDEO.openAD();
                }

                $(self.element).find(".ts_infoTitle").html(obj.title);
                $(self.element).find(".ts_infoText").html(obj.info_text);
                $(self.element).find(".ts_nowPlayingText").html(obj.title);
                this.loaded=false;
            }
            else if(options.videos[obj.id].videoType=="vimeo")
            {
                self.VIDEO.hideVideoElements();
                self.VIDEO.closeAD();
                self.videoAdPlayed=false;

                self.vimeoWrapper.css({zIndex:501});

                if(self.CLICK_EV=="click")
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[obj.id].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                else if(self.CLICK_EV=="touchend")
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[obj.id].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                $('#vimeo_video').load(function(){
                    self.preloader.stop().animate({opacity:0},200,function(){$(this).hide()});
                });

                self.VIDEO.removeHTML5elements();
                self.ytWrapper.css({zIndex:0});
                if(self.youtubePlayer!= undefined){
                    self.youtubePlayer.stopVideo();
                    self.youtubePlayer.clearVideo();
//                    self.youtubePlayer.setSize(1, 1);
                }
                addVimeoListeners();
            }
            switch(self.options.playlist){
                case "Right playlist":
                    mainContainer.find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//remove selected
                    $(this).removeClass("ts_itemUnselected").addClass("ts_itemSelected");
                    break;
                case "Bottom playlist":
                    mainContainer.find(".ts_itemSelected_bottom").removeClass("ts_itemSelected_bottom").addClass("ts_itemUnselected_bottom");//remove selected
                    $(this).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");
                    break;
            }
        });
      }*/
     });

    //play first from playlist
    switch(self.options.playlist){
        case "Right playlist":
            if(options.loadRandomVideoOnStart=="Yes")
                $(self.item_array[self.rand]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");//first selected
            else
                $(self.item_array[0]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");//first selected
            break;
        case "Bottom playlist":
            if(options.loadRandomVideoOnStart=="Yes")
                $(self.item_array[self.rand]).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");//first selected
            else
                $(self.item_array[0]).removeClass("ts_itemUnselected_bottom").addClass("ts_itemSelected_bottom");//first selected
            break;
    }

    self.videoid = 0;

    if(options.videos[0].videoType=="youtube")
    {
        self.VIDEO.hideVideoElements();

        self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
        self.ytWrapper.css({zIndex:501});
        self.vimeoWrapper.css({zIndex:0});
        // create youtube player
        window.onYouTubePlayerAPIReady= function(){
            self.youtubePlayer = new YT.Player(document.getElementById('ts_ytPlayer'), {
                height: '100%',
                width: '100%',
//                    videoId: 'INmtQXUXez8',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars:
                {
                    //modestbranding: 0,//0,1
                    theme:ytSkin, //light,dark
                    color:ytColor, //red, white
                    rel:ytShowRelatedVideos
                }
            });
        };

    }
    else if(options.videos[0].videoType=="HTML5")
    {
        self.ytWrapper.css({zIndex:0});
        self.vimeoWrapper.css({zIndex:0});
        // create youtube player
        window.onYouTubePlayerAPIReady= function(){
            self.youtubePlayer = new YT.Player(document.getElementById('ts_ytPlayer'), {
                height: '1',
                width: '1',
//                    videoId: 'INmtQXUXez8',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars:
                {
                    //modestbranding: 0,//0,1
                    theme:ytSkin, //light,dark
                    color:ytColor, //red, white
                    rel:ytShowRelatedVideos
                }

            });
        };
        /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
         {
         this.canPlay = true;
         self.video_path = self.videos_array[0].video_path_webm;
         self.video_pathAD = self.videos_array[0].video_path_webmAD;
         }
         else */
        if(options.loadRandomVideoOnStart=="Yes"){
//            console.log(self.rand)
//            console.log(self.videos_array[self.rand])
            self.video_path = self.videos_array[self.rand].video_path_mp4;
            self.video_pathAD = self.videos_array[self.rand].video_path_mp4AD;
        }
        else{
            self.video_path = self.videos_array[0].video_path_mp4;
            self.video_pathAD = self.videos_array[0].video_path_mp4AD;
        }
        self.VIDEO.load(self.video_path, "0");



    }
    else if(options.videos[0].videoType=="vimeo")
    {
        self.VIDEO.hideVideoElements();
        // create youtube player
        window.onYouTubePlayerAPIReady= function(){
            self.youtubePlayer = new YT.Player(document.getElementById('ts_ytPlayer'), {
                height: '1',
                width: '1',
//                    videoId: 'INmtQXUXez8',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars:
                {
                    //modestbranding: 0,//0,1
                    theme:ytSkin, //light,dark
                    color:ytColor, //red, white
                    rel:ytShowRelatedVideos
                }
            });
        };
        self.preloader.stop().animate({opacity:0},700,function(){$(this).hide()});
        self.vimeoWrapper.css({zIndex:501});

        if(!self.hasTouch){
            if(options.autoplay)
                if(options.loadRandomVideoOnStart=="Yes")
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.rand].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                else
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[0].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
            else
            if(options.loadRandomVideoOnStart=="Yes")
                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.rand].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
            else
                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[0].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
        }
        else{
            if(options.loadRandomVideoOnStart=="Yes")
                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.rand].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
            else
                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[0].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
        }
        addVimeoListeners();
    }

    self.totalWidth = options.videoPlayerWidth;
    self.totalHeight = options.videoPlayerHeight;

    //check if show playlist exist
    if(options.playlist=="Right playlist" || options.playlist=="Bottom playlist")
    {
        if( self.element){
            mainContainer.append(self.playlist);
            self.playlist.append(self.playlistContent);
        }
    }

    //check which playlist
    if(options.playlist=="Right playlist")
    {
        self.playlistContent.css("height",String(offsetT)+"px");
        self.playerWidth = self.totalWidth - self.playlist.width();
        self.playerHeight = self.totalHeight - self.playlist.height();

        self.playlist.css({
            height:"100%",
            top:0
        });
        if(self.playlistContent.height()<self.playlist.height())
        return;
        self.scroll = new iScroll(self.playlist[0], {
            snap: self.item,
            momentum: false,
//            hScrollbar: false,
//            vScrollbar: false,
            bounce:false,
            wheelHorizontal: true,
            scrollbarClass: 'ts_myScrollbar',
            momentum:true});

        self.topArrow = $("<div />")
            .addClass("ts_topArrow");
        self.playlist.append(self.topArrow);

        self.bottomArrow = $("<div />")
            .addClass("ts_bottomArrow");
        self.playlist.append(self.bottomArrow);

        self.topArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Previous")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("icon-up");
        self.topArrow.append(self.topArrowInside);

        self.bottomArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Next")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("icon-down");
        self.bottomArrow.append(self.bottomArrowInside);

        self.topArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage(0, 'prev');return false
        });
        self.bottomArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage(0, 'next');return false
        });
    }
    else if(options.playlist=="Bottom playlist")
    {
        self.playlistContent.css("width",String(offsetL)+"px");
        self.playerWidth = self.totalWidth;
        self.playerHeight = self.totalHeight - self.playlist.height();

        self.playlist.css({
            left:0,
            width:"100%",
            top:self.playerHeight
        });
        if(self.playlistContent.width()<self.playlist.width())
        return;
        self.scroll = new iScroll(self.playlist[0], {
            snap: self.item,
            momentum: false,
//            hScrollbar: false,
//            vScrollbar: false,
            bounce:false,
            wheelHorizontal: true,
            scrollbarClass: 'ts_myScrollbar',
            momentum:true});

        self.leftArrow = $("<div />")
            .addClass("ts_leftArrow");
        self.playlist.append(self.leftArrow);

        self.rightArrow = $("<div />")
            .addClass("ts_rightArrow");
        self.playlist.append(self.rightArrow);

        self.leftArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Previous")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("fa-chevron-left");
        self.leftArrow.append(self.leftArrowInside);

        self.rightArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Next")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("fa-chevron-right");
        self.rightArrow.append(self.rightArrowInside);

        self.leftArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage('prev', 0);return false
        });
        self.rightArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage('next', 0);return false
        });
    }

//console.log(self.playlist.width())
//console.log(self.playlist.height())
//    if(options.playlist)
//    {
//        self.scroll = new iScroll(self.playlist[0], {bounce:false,scrollbarClass: 'ts_myScrollbar'});
//    }

    //save playlist width and height
    this.playlistW = this.playlist.width();
    this.playlistH = this.playlist.height();


};


//prototype object, here goes public functions
PLAYER.Playlist.prototype = {

    hidePlaylist:function(){
        this.playlist.hide();
    },
    showPlaylist:function(){
        this.playlist.show();
    },
    resizePlaylist:function(val1, val2){
        switch(this.options.playlist) {
            case 'Right playlist':
                this.playlist.css({
//                    left:val1 - this.playlist.width(),
                    right:0,
//                    left:500,
                    top:0,
                    height:"100%"
//                    height:420
                });
                break;
            case 'Bottom playlist':
                this.playlist.css({
//                    left:0,
                    right:0,
                    height:this.playlist.height(),
//                    width:val1,
                    width:"100%",
                    top:this.element.height()
//                    bottom:0
                });
                break;
        }
//        if(this.options.playlist=="Right playlist")
//        {
//            this.playlist.css({
//                left:val1 - this.playlist.width(),
//                top:0,
//                height:val2
//            });
//        }
//        else if(this.options.playlist=="Bottom playlist")
//        {
//            this.playlist.css({
//                left:0,
//                height:64,
//                width:val1,
//                top:val2
//            });
//        }
    },
    playYoutube:function(obj_id){
//        this.element.find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//remove selected
//        $(this.item_array[obj_id]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");//selected
        if(this.youtubePlayer!= undefined){
            this.youtubePlayer.cueVideoById(this.videos_array[obj_id].youtubeID);
            this.preloader.hide();
            this.ytWrapper.css({zIndex:501});
            if(!this.hasTouch)
                this.youtubePlayer.playVideo();
        }

//        this.youtubePlayer.setSize(element.width(), element.height());
        this.VIDEO.resizeAll();
    },
    playVimeo:function(obj_id){
//        console.log(this.item_array[obj_id])
//        this.element.find(".ts_itemSelected").removeClass("ts_itemSelected").addClass("ts_itemUnselected");//remove selected
//        $(this.item_array[obj_id]).removeClass("ts_itemUnselected").addClass("ts_itemSelected");//selected
        this.preloader.hide();
        this.vimeoWrapper.css({zIndex:501});
        if(!this.hasTouch){
            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+this.videos_array[obj_id].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+this.options.vimeoColor;
        }
        else{
            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+this.videos_array[obj_id].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+this.options.vimeoColor;
        }

    }


};

/*** Froogalop ***/

// Init style shamelessly stolen from jQuery http://jquery.com
var Froogaloop = (function(){
    // Define a local copy of Froogaloop
    function Froogaloop(iframe) {
        // The Froogaloop object is actually just the init constructor
        return new Froogaloop.fn.init(iframe);
    }

    var eventCallbacks = {},
        hasWindowEvent = false,
        isReady = false,
        slice = Array.prototype.slice,
        playerDomain = '';

    Froogaloop.fn = Froogaloop.prototype = {
        element: null,

        init: function(iframe) {
            if (typeof iframe === "string") {
                iframe = document.getElementById(iframe);
            }

            this.element = iframe;

            // Register message event listeners
            playerDomain = getDomainFromUrl(this.element.getAttribute('src'));

            return this;
        },

        /*
         * Calls a function to act upon the player.
         *
         * @param {string} method The name of the Javascript API method to call. Eg: 'play'.
         * @param {Array|Function} valueOrCallback params Array of parameters to pass when calling an API method
         *                                or callback function when the method returns a value.
         */
        api: function(method, valueOrCallback) {
            if (!this.element || !method) {
                return false;
            }

            var self = this,
                element = self.element,
                target_id = element.id !== '' ? element.id : null,
                params = !isFunction(valueOrCallback) ? valueOrCallback : null,
                callback = isFunction(valueOrCallback) ? valueOrCallback : null;

            // Store the callback for get functions
            if (callback) {
                storeCallback(method, callback, target_id);
            }

            postMessage(method, params, element);
            return self;
        },

        /*
         * Registers an event listener and a callback function that gets called when the event fires.
         *
         * @param eventName (String): Name of the event to listen for.
         * @param callback (Function): Function that should be called when the event fires.
         */
        addEvent: function(eventName, callback) {
            if (!this.element) {
                return false;
            }

            var self = this,
                element = self.element,
                target_id = element.id !== '' ? element.id : null;


            storeCallback(eventName, callback, target_id);

            // The ready event is not registered via postMessage. It fires regardless.
            if (eventName != 'ready') {
                postMessage('addEventListener', eventName, element);
            }
            else if (eventName == 'ready' && isReady) {
                callback.call(null, target_id);
            }

            return self;
        },

        /*
         * Unregisters an event listener that gets called when the event fires.
         *
         * @param eventName (String): Name of the event to stop listening for.
         */
        removeEvent: function(eventName) {
            if (!this.element) {
                return false;
            }

            var self = this,
                element = self.element,
                target_id = element.id !== '' ? element.id : null,
                removed = removeCallback(eventName, target_id);

            // The ready event is not registered
            if (eventName != 'ready' && removed) {
                postMessage('removeEventListener', eventName, element);
            }
        }
    };

    /**
     * Handles posting a message to the parent window.
     *
     * @param method (String): name of the method to call inside the player. For api calls
     * this is the name of the api method (api_play or api_pause) while for events this method
     * is api_addEventListener.
     * @param params (Object or Array): List of parameters to submit to the method. Can be either
     * a single param or an array list of parameters.
     * @param target (HTMLElement): Target iframe to post the message to.
     */
    function postMessage(method, params, target) {
        if (!target.contentWindow.postMessage) {
            return false;
        }

        var url = target.getAttribute('src').split('?')[0],
            data = JSON.stringify({
                method: method,
                value: params
            });

        if (url.substr(0, 2) === '//') {
            url = window.location.protocol + url;
        }

        if ( url.indexOf('vimeo') ) url = url.replace(/^http:\/\//i, 'https://');

        target.contentWindow.postMessage(data, url);
    }

    /**
     * Event that fires whenever the window receives a message from its parent
     * via window.postMessage.
     */
    function onMessageReceived(event) {
        var data, method;

        try {
            data = JSON.parse(event.data);
            method = data.event || data.method;
        }
        catch(e)  {
            //fail silently... like a ninja!
        }

        if (method == 'ready' && !isReady) {
            isReady = true;
        }

        // Handles messages from moogaloop only
        if (event.origin != playerDomain) {
            return false;
        }

        var value = data.value,
            eventData = data.data,
            target_id = target_id === '' ? null : data.player_id,

            callback = getCallback(method, target_id),
            params = [];

        if (!callback) {
            return false;
        }

        if (value !== undefined) {
            params.push(value);
        }

        if (eventData) {
            params.push(eventData);
        }

        if (target_id) {
            params.push(target_id);
        }

        return params.length > 0 ? callback.apply(null, params) : callback.call();
    }


    /**
     * Stores submitted callbacks for each iframe being tracked and each
     * event for that iframe.
     *
     * @param eventName (String): Name of the event. Eg. api_onPlay
     * @param callback (Function): Function that should get executed when the
     * event is fired.
     * @param target_id (String) [Optional]: If handling more than one iframe then
     * it stores the different callbacks for different iframes based on the iframe's
     * id.
     */
    function storeCallback(eventName, callback, target_id) {
        if (target_id) {
            if (!eventCallbacks[target_id]) {
                eventCallbacks[target_id] = {};
            }
            eventCallbacks[target_id][eventName] = callback;
        }
        else {
            eventCallbacks[eventName] = callback;
        }
    }

    /**
     * Retrieves stored callbacks.
     */
    function getCallback(eventName, target_id) {
        if (target_id) {
            return eventCallbacks[target_id][eventName];
        }
        else {
            return eventCallbacks[eventName];
        }
    }

    function removeCallback(eventName, target_id) {
        if (target_id && eventCallbacks[target_id]) {
            if (!eventCallbacks[target_id][eventName]) {
                return false;
            }
            eventCallbacks[target_id][eventName] = null;
        }
        else {
            if (!eventCallbacks[eventName]) {
                return false;
            }
            eventCallbacks[eventName] = null;
        }

        return true;
    }

    /**
     * Returns a domain's root domain.
     * Eg. returns http://vimeo.com when http://vimeo.com/channels is sbumitted
     *
     * @param url (String): Url to test against.
     * @return url (String): Root domain of submitted url
     */
    function getDomainFromUrl(url) {
        if (url.substr(0, 2) === '//') {
            url = window.location.protocol + url;
        }

        var url_pieces = url.split('/'),
            domain_str = '';

        for(var i = 0, length = url_pieces.length; i < length; i++) {
            if(i<3) {domain_str += url_pieces[i];}
            else {break;}
            if(i<2) {domain_str += '/';}
        }

        return domain_str;
    }

    function isFunction(obj) {
        return !!(obj && obj.constructor && obj.call && obj.apply);
    }

    function isArray(obj) {
        return toString.call(obj) === '[object Array]';
    }

    // Give the init function the Froogaloop prototype for later instantiation
    Froogaloop.fn.init.prototype = Froogaloop.fn;

    // Listens for the message event.
    // W3C
    if (window.addEventListener) {
        window.addEventListener('message', onMessageReceived, false);
    }
    // IE
    else {
        window.attachEvent('onmessage', onMessageReceived);
    }

    // Expose froogaloop to the global object
    return (window.Froogaloop = window.$f = Froogaloop);

})();