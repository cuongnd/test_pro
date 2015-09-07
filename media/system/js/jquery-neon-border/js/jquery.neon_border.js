(function (scope, _) {
    var neonBorderStack = function (el, opts) {
        $=jQuery;
        var self = this;
        self.opts = _.defaults(opts || {}, {
            //option default here
        });
        $( this ).bind( "mousemove", function() {
            $( this ).toggleClass( "rotate" );
        });

        jQuery(document).on('hover',self,function($){
            jQuery(this).toggleClass("rotate");
        });

    };
    neonBorderStack.prototype.testfuntion = function () {
        console.log(self.opts);
    };


    jQuery.fn.neon_border = function (opts) {

        if (!jQuery(this).data('neon_border')) {
            return jQuery(this).data('neon_border', new neonBorderStack(this, opts));
        }

    };

})(window, _);
