jQuery(function() {
    var ele   = jQuery('#scroll');
    var speed = 25, scroll = 5, scrolling;
    
    jQuery('#scroll-up').mouseenter(function() {
        // Scroll the element up
        scrolling = window.setInterval(function() {
            ele.scrollTop( ele.scrollTop() - scroll );
        }, speed);
    });
    
    jQuery('#scroll-down').mouseenter(function() {
        // Scroll the element down
        scrolling = window.setInterval(function() {
            ele.scrollTop( ele.scrollTop() + scroll );
        }, speed);
    });
    
    jQuery('#scroll-up, #scroll-down').bind({
        click: function(e) {
            // Prevent the default click action
            e.preventDefault();
        },
        mouseleave: function() {
            if (scrolling) {
                window.clearInterval(scrolling);
                scrolling = false;
            }
        }
    });
    var ele1   = jQuery('#scroll1');
    var speed1 = 25, scroll = 5, scrolling;
    
    jQuery('#scroll-up1').mouseenter(function() {
        // Scroll the element up
        scrolling = window.setInterval(function() {
            ele1.scrollTop( ele1.scrollTop() - scroll );
        }, speed1);
    });
    
    jQuery('#scroll-down1').mouseenter(function() {
        // Scroll the element down
        scrolling = window.setInterval(function() {
            ele1.scrollTop( ele1.scrollTop() + scroll );
        }, speed1);
    });
    
    jQuery('#scroll-up1, #scroll-down1').bind({
        click: function(e) {
            // Prevent the default click action
            e.preventDefault();
        },
        mouseleave: function() {
            if (scrolling) {
                window.clearInterval(scrolling);
                scrolling = false;
            }
        }
    });

    var ele2   = jQuery('#scroll2');
    var speed2 = 25, scroll = 5, scrolling;
    
    jQuery('#scroll-up2').mouseenter(function() {
        // Scroll the element up
        scrolling = window.setInterval(function() {
            ele2.scrollTop( ele2.scrollTop() - scroll );
        }, speed2);
    });
    
    jQuery('#scroll-down2').mouseenter(function() {
        // Scroll the element down
        scrolling = window.setInterval(function() {
            ele2.scrollTop( ele2.scrollTop() + scroll );
        }, speed2);
    });
    
    jQuery('#scroll-up2, #scroll-down2').bind({
        click: function(e) {
            // Prevent the default click action
            e.preventDefault();
        },
        mouseleave: function() {
            if (scrolling) {
                window.clearInterval(scrolling);
                scrolling = false;
            }
        }
    });
});