(function (scope, _) {

    var UtilsDivRow = {
        is_intercepted: function (a, b) {
            return !(a.x + a.width <= b.x || b.x + b.width <= a.x || a.y + a.height <= b.y || b.y + b.height <= a.y);
        },

        sort: function (nodes, dir, width) {
            width = width || _.chain(nodes).map(function (node) { return node.x + node.width; }).max().value();
            dir = dir != -1 ? 1 : -1;
            return _.sortBy(nodes, function (n) { return dir * (n.x + n.y * width); });
        },

        create_stylesheet: function () {
            var style = document.createElement("style");

            // style.setAttribute("media", "screen")
            // style.setAttribute("media", "only screen and (max-width : 1024px)")

            // WebKit hack :(
            style.appendChild(document.createTextNode(""));

            document.head.appendChild(style);

            return style.sheet;
        },

        toBool: function (v) {
            if (typeof v == 'boolean')
                return v;
            if (typeof v == 'string') {
                v = v.toLowerCase();
                return !(v == '' || v == 'no' || v == 'false' || v == '0');
            }
            return Boolean(v);
        }
    };

    var id_seq = 0;

    var GridStackEngineDivRow = function (width, onchange,a_float, height, items) {
        this.width = width;
        this.float = a_float || false;
        this.height = height || 0;

        this.nodes = items || [];
        this.onchange = onchange || function () {};
    };

    GridStackEngineDivRow.prototype._fix_collisions = function (node) {
        this._sort_nodes(-1);

        var nn = node, has_locked = Boolean(_.find(this.nodes, function (n) { return n.locked }));
        if (!this.float && !has_locked) {
            nn = {x: 0, y: node.y, width: this.width, height: node.height};
        }

        while (true) {
            var collision_node = _.find(this.nodes, function (n) {
                return n != node && UtilsDivRow.is_intercepted(n, nn);
            }, this);
            if (typeof collision_node == 'undefined') {
                return;
            }
            this.move_node(collision_node, collision_node.x, node.y + node.height,
                collision_node.width, collision_node.height, true);
        }
    };

    GridStackEngineDivRow.prototype._sort_nodes = function (dir) {
        this.nodes = UtilsDivRow.sort(this.nodes, dir, this.width);
    };

    GridStackEngineDivRow.prototype._pack_nodes = function () {
        this._sort_nodes();

        if (this.float) {
            _.each(this.nodes, function (n, i) {
                if (n._updating || typeof n._orig_y == 'undefined' || n.y == n._orig_y)
                    return;

                var new_y = n.y;
                while (new_y >= n._orig_y) {
                    var collision_node = _.chain(this.nodes)
                        .find(function (bn) {
                            return n != bn && UtilsDivRow.is_intercepted({x: n.x, y: new_y, width: n.width, height: n.height}, bn);
                        })
                        .value();

                    if (!collision_node) {
                        n._dirty = true;
                        n.y = new_y;
                    }
                    --new_y;
                }
            }, this);
        }
        else {
            _.each(this.nodes, function (n, i) {
                if (n.locked)
                    return;
                while (n.y > 0) {
                    var new_y = n.y - 1;
                    var can_be_moved = i == 0;

                    if (i > 0) {
                        var collision_node = _.chain(this.nodes)
                            .first(i)
                            .find(function (bn) {
                                return UtilsDivRow.is_intercepted({x: n.x, y: new_y, width: n.width, height: n.height}, bn);
                            })
                            .value();
                        can_be_moved = typeof collision_node == 'undefined';
                    }

                    if (!can_be_moved) {
                        break;
                    }
                    n._dirty = n.y != new_y;
                    n.y = new_y;
                }
            }, this);
        }
    };

    GridStackEngineDivRow.prototype._prepare_node = function (node, resizing) {
        node = _.defaults(node || {}, {width: 1, height: 1, x: 0, y: 0 });

        node.x = parseInt('' + node.x);
        node.y = parseInt('' + node.y);
        node.width = parseInt('' + node.width);
        node.height = parseInt('' + node.height);
        node.auto_position = node.auto_position || false;
        node.no_resize = node.no_resize || false;
        node.no_move = node.no_move || false;

        if (node.width > this.width) {
            node.width = this.width;
        }
        else if (node.width < 1) {
            node.width = 1;
        }

        if (node.height < 1) {
            node.height = 1;
        }

        if (node.x < 0) {
            node.x = 0;
        }

        if (node.x + node.width > this.width) {
            if (resizing) {
                node.width = this.width - node.x;
            }
            else {
                node.x = this.width - node.width;
            }
        }

        if (node.y < 0) {
            node.y = 0;
        }

        return node;
    };

    GridStackEngineDivRow.prototype._notify = function () {
        var deleted_nodes = Array.prototype.slice.call(arguments, 1).concat(this.get_dirty_nodes());
        deleted_nodes = deleted_nodes.concat(this.get_dirty_nodes());
        this.onchange(deleted_nodes);
    };

    GridStackEngineDivRow.prototype.clean_nodes = function () {
        _.each(this.nodes, function (n) {n._dirty = false });
    };

    GridStackEngineDivRow.prototype.get_dirty_nodes = function () {
        return _.filter(this.nodes, function (n) { return n._dirty; });
    };

    GridStackEngineDivRow.prototype.add_node = function(node) {
        node = this._prepare_node(node);

        if (typeof node.max_width != 'undefined') node.width = Math.min(node.width, node.max_width);
        if (typeof node.max_height != 'undefined') node.height = Math.min(node.height, node.max_height);
        if (typeof node.min_width != 'undefined') node.width = Math.max(node.width, node.min_width);
        if (typeof node.min_height != 'undefined') node.height = Math.max(node.height, node.min_height);

        node._id = ++id_seq;
        node._dirty = true;

        if (node.auto_position) {
            this._sort_nodes();

            for (var i = 0; ; ++i) {
                var x = i % this.width, y = Math.floor(i / this.width);
                if (x + node.width > this.width) {
                    continue;
                }
                if (!_.find(this.nodes, function (n) {
                        return UtilsDivRow.is_intercepted({x: x, y: y, width: node.width, height: node.height}, n);
                    })) {
                    node.x = x;
                    node.y = y;
                    break;
                }
            }
        }

        this.nodes.push(node);

        this._fix_collisions(node);
        this._pack_nodes();
        this._notify();
        return node;
    };

    GridStackEngineDivRow.prototype.remove_node = function (node) {
        node._id = null;
        this.nodes = _.without(this.nodes, node);
        this._pack_nodes();
        this._notify(node);
    };

    GridStackEngineDivRow.prototype.can_move_node = function (node, x, y, width, height) {
        var has_locked = Boolean(_.find(this.nodes, function (n) { return n.locked }));

        if (!this.height && !has_locked)
            return true;

        var cloned_node;
        var clone = new GridStackEngineDivRow(
            this.width,
            null,
            this.float,
            0,
            _.map(this.nodes, function (n) { if (n == node) { cloned_node = jQuery.extend({}, n); return cloned_node; } return jQuery.extend({}, n) }));

        clone.move_node(cloned_node, x, y, width, height);

        var res = true;

        if (has_locked)
            res &= !Boolean(_.find(clone.nodes, function (n) { return n != cloned_node && Boolean(n.locked) && Boolean(n._dirty); }));
        if (this.height)
            res &= clone.get_grid_height() <= this.height;

        return res;
    };

    GridStackEngineDivRow.prototype.can_be_placed_with_respect_to_height = function (node) {
        if (!this.height)
            return true;

        var clone = new GridStackEngineDivRow(
            this.width,
            null,
            this.float,
            0,
            _.map(this.nodes, function (n) { return jQuery.extend({}, n) }));
        clone.add_node(node);
        return clone.get_grid_height() <= this.height;
    };

    GridStackEngineDivRow.prototype.move_node = function (node, x, y, width, height, no_pack) {
        //jQuery(node.el).find('.offset-width').html('offset:'+x+'-width:'+width);

        if (typeof x != 'number') x = node.x;
        if (typeof y != 'number') y = node.y;
        if (typeof width != 'number') width = node.width;
        if (typeof height != 'number') height = node.height;

        if (typeof node.max_width != 'undefined') width = Math.min(width, node.max_width);
        if (typeof node.max_height != 'undefined') height = Math.min(height, node.max_height);
        if (typeof node.min_width != 'undefined') width = Math.max(width, node.min_width);
        if (typeof node.min_height != 'undefined') height = Math.max(height, node.min_height);

        if (node.x == x && node.y == y && node.width == width && node.height == height) {
            return node;
        }

        var resizing = node.width != width;
        node._dirty = true;

        node.x = x;
        node.y = y;
        node.width = width;
        node.height = height;

        node = this._prepare_node(node, resizing);

        this._fix_collisions(node);
        if (!no_pack) {
            this._pack_nodes();
            this._notify();
        }
        return node;
    };

    GridStackEngineDivRow.prototype.get_grid_height = function () {
        return _.reduce(this.nodes, function (memo, n) { return Math.max(memo, n.y + n.height); }, 0);
    };

    GridStackEngineDivRow.prototype.begin_update = function (node) {
        _.each(this.nodes, function (n) {
            n._orig_y = n.y;
        });
        node._updating = true;
    };

    GridStackEngineDivRow.prototype.end_update = function () {
        var n = _.find(this.nodes, function (n) { return n._updating; });
        if (n) {
            n._updating = false;
        }
    };

    var GridStackDivRow = function (el, opts) {
        var self = this, one_column_mode;

        this.container = jQuery(el);

        this.opts = _.defaults(opts || {}, {
            width: parseInt(this.container.attr('data-gs-width')) || 12,
            height: parseInt(this.container.attr('data-gs-height')) || 0,
            item_class: 'item_control',
            placeholder_class: 'grid-stack-placeholder',
            handle: '.item_control-content',
            cell_height: 60,
            vertical_margin: 20,
            auto: true,
            min_width: 100,
            float: false,
            _class: 'grid-stack-div-row' + (Math.random() * 10000).toFixed(0),
            animate: Boolean(this.container.attr('data-gs-animate')) || false
        });

        this.container.addClass(this.opts._class);
        this._styles = UtilsDivRow.create_stylesheet();
        this._styles._max = 0;

        this.grid = new GridStackEngineDivRow(this.opts.width, function (nodes) {
            var max_height = 0;
            _.each(nodes, function (n) {
                if (n._id == null) {
                    n.el.remove();
                }
                else {
                    n.el
                        .attr('data-gs-x', n.x)
                        .attr('data-gs-y', n.y)
                        .attr('data-gs-width', n.width)
                        .attr('data-gs-height', n.height);
                    max_height = Math.max(max_height, n.y + n.height);
                }
            });
            self._update_styles(max_height + 10);
        }, this.opts.float, this.opts.height);

        if (this.opts.auto) {
            var elements = [];
            this.container.find('.' + this.opts.item_class).each(function (index, el) {
                el = jQuery(el);
                elements.push({
                    el: el,
                    i: parseInt(el.attr('data-gs-x')) + parseInt(el.attr('data-gs-y')) * parseInt(el.attr('data-gs-width'))
                });
            });
            _.chain(elements).sortBy(function (x) { return x.i; }).each(function (i) {
                self._prepare_element(i.el);
            });
        }

        this.set_animation(this.opts.animate);

        this.placeholder = jQuery('<div class="' + this.opts.placeholder_class + ' ' + this.opts.item_class + '"><div class="placeholder-content" /></div>').hide();
        this.container.append(this.placeholder);
        this.container.height((this.grid.get_grid_height()) * (this.opts.cell_height + this.opts.vertical_margin) - this.opts.vertical_margin);

        var on_resize_handler = function () {

            if (self._is_one_column_mode()) {
                if (one_column_mode)
                    return;

                one_column_mode = true;

                _.each(self.grid.nodes, function (node) {
                    if (!node.no_move) {
                        node.el.draggable('disable');
                    }
                    if (!node.no_resize) {
                        node.el.resizable('disable');
                    }
                });
            }
            else {
                if (!one_column_mode)
                    return;

                one_column_mode = false;

                _.each(self.grid.nodes, function (node) {
                    if (!node.no_move) {
                        node.el.draggable('enable');
                    }
                    if (!node.no_resize) {
                        node.el.resizable('enable');
                    }
                });
            }
        };

        jQuery(window).resize(on_resize_handler);
        on_resize_handler();
    };

    GridStackDivRow.prototype._update_styles = function (max_height) {
        if (typeof max_height == 'undefined') {
            max_height = this._styles._max;
            this._styles._max = 0;
            while (this._styles.rules.length) {
                this._styles.removeRule(0);
            }
            this._update_container_height();
        }

        if (max_height > this._styles._max) {
            for (var i = this._styles._max; i < max_height; ++i) {
                var css;
                css = '.' + this.opts._class + ' .' + this.opts.item_class + '[data-gs-height="' + (i + 1) + '"] { height: ' + (this.opts.cell_height * (i + 1) + this.opts.vertical_margin * i) + 'px; }';
                this._styles.insertRule(css, i);
                css = '.' + this.opts._class + ' .' + this.opts.item_class + '[data-gs-y="' + (i) + '"] { top: ' + (this.opts.cell_height * i + this.opts.vertical_margin * i) + 'px; }';
                this._styles.insertRule(css, i);
            }
            this._styles._max = max_height;
        }
    };

    GridStackDivRow.prototype._update_container_height = function () {
        this.container.height(this.grid.get_grid_height() * (this.opts.cell_height + this.opts.vertical_margin) - this.opts.vertical_margin);
    };

    GridStackDivRow.prototype._is_one_column_mode = function () {
        return jQuery(window).width() <= this.opts.min_width;
    };

    GridStackDivRow.prototype._prepare_element = function (el) {
        var self = this;
        el = jQuery(el);

        el.addClass(this.opts.item_class);

        var node = self.grid.add_node({
            x: el.attr('data-gs-x'),
            y: el.attr('data-gs-y'),
            width: el.attr('data-gs-width'),
            height: el.attr('data-gs-height'),
            max_width: el.attr('data-gs-max-width'),
            min_width: el.attr('data-gs-min-width'),
            max_height: el.attr('data-gs-max-height') || 100,
            min_height: el.attr('data-gs-min-height'),
            auto_position: UtilsDivRow.toBool(el.attr('data-gs-auto-position')),
            no_resize: UtilsDivRow.toBool(el.attr('data-gs-no-resize')),
            no_move: UtilsDivRow.toBool(el.attr('data-gs-no-move')),
            locked: UtilsDivRow.toBool(el.attr('data-gs-locked')),
            el: el
        });
        el.data('_gridstack_node_div_row', node);

        var cell_width, cell_height;

        var on_start_moving = function (event, ui) {

            var o = jQuery(this);
            self.grid.clean_nodes();
            self.grid.begin_update(node);
            cell_width = Math.ceil(o.outerWidth() / o.attr('data-gs-width'));
            cell_height = self.opts.cell_height + self.opts.vertical_margin;
            self.placeholder
                .attr('data-gs-x', o.attr('data-gs-x'))
                .attr('data-gs-y', o.attr('data-gs-y'))
                .attr('data-gs-width', o.attr('data-gs-width'))
                .attr('data-gs-height', o.attr('data-gs-height'))
                .show();
            node.el = self.placeholder;
        };

        var on_end_moving = function (event, ui) {

            var o = jQuery(this);
            node.el = o;
            self.placeholder.hide();
            o
                .attr('data-gs-x', node.x)
                .attr('data-gs-y', node.y)
                .attr('data-gs-width', node.width)
                .attr('data-gs-height', node.height)
                .removeAttr('style');
            self._update_container_height();
            self.container.trigger('change', [self.grid.get_dirty_nodes()]);

            self.grid.end_update();

            self.grid._sort_nodes();
            setTimeout(function() { //if animating, delay detaching & reattaching all elements until animation finishes
                _.each(self.grid.nodes, function (node) {
                    node.el.detach();
                    self.container.append(node.el);
                });
            }, (self.opts.animate ? 300 : 0));
            self.opts.changeSizeGridParent(self.grid,el,1);
            self.opts.updateColumns();



        };

        el.draggable({
            handle: this.opts.handle,
            scroll: false,
            //helper: "clone",
            appendTo: '.screen-layout',
            containment: '.screen-layout',
            axis: "x,y",
            start: on_start_moving,
            stop: on_end_moving,
            drag: function (event, ui) {
                var x = Math.round(ui.position.left / cell_width),
                    y = Math.floor((ui.position.top + cell_height/2) / cell_height);
                if (!self.grid.can_move_node(node, x, y, node.width, node.height)) {
                    return;
                }
                self.grid.move_node(node, x, y);
                self._update_container_height();
                self.grid.write_header(el,node);
                console.log('drag');

            }
        }).resizable({
            autoHide: true,
            handles:'se',
            minHeight: this.opts.cell_height - 10,
            minWidth: 70,
            start: on_start_moving,
            stop: on_end_moving,
            resize: function (event, ui) {
                el.find(' > .grid-stack-item-content').addClass('hover-block-item');
                _.each(self.grid.nodes, function (node) {
                    node.el.find(' > .grid-stack-item-content').addClass('hover-block-item');
                });
                var width = Math.round(ui.size.width / cell_width),
                    height = Math.round(ui.size.height / cell_height);
                if (!self.grid.can_move_node(node, node.x, node.y, width, height)) {
                    return;
                }
                self.grid.move_node(node, node.x, node.y, width, height);
                self._update_container_height();
                self.grid.write_header(el,node);
                //self.opts.changeSizeGridParent(self.grid,el,1);



            }
        });

        if (node.no_move || this._is_one_column_mode()) {
            el.draggable('disable');
        }

        if (node.no_resize || this._is_one_column_mode()) {
            el.resizable('disable');
        }

        el.attr('data-gs-locked', node.locked ? 'yes' : null);
    };

    GridStackDivRow.prototype.set_animation = function (enable) {
        if (enable) {
            this.container.addClass('grid-stack-animate');
        }
        else {
            this.container.removeClass('grid-stack-animate');
        }
    };

    GridStackDivRow.prototype.add_widget = function (el, x, y, width, height, auto_position) {
        el = jQuery(el);
        if (typeof x != 'undefined') el.attr('data-gs-x', x);
        if (typeof y != 'undefined') el.attr('data-gs-y', y);
        if (typeof width != 'undefined') el.attr('data-gs-width', width);
        if (typeof height != 'undefined') el.attr('data-gs-height', height);
        if (typeof auto_position != 'undefined') el.attr('data-gs-auto-position', auto_position ? 'yes' : null);
        this.container.append(el);
        this._prepare_element(el);
        this._update_container_height();
    };

    GridStackDivRow.prototype.will_it_fit = function (x, y, width, height, auto_position) {
        var node = {x: x, y: y, width: width, height: height, auto_position: auto_position};
        return this.grid.can_be_placed_with_respect_to_height(node);
    };
    GridStackEngineDivRow.prototype.write_header = function (el,node) {
        prevEl=el.prev();
        nextEl=el.next();
        var prevNode = prevEl.data('_gridstack_node_div_row');
        var nextNode = nextEl.data('_gridstack_node_div_row');
        offset=node.x;
        if (typeof prevNode != 'undefined') {
            offset=node.x-(prevNode.x+prevNode.width);

            //prevEl.find('.offset-width').html('offset:'+offset+'-width:'+node.width);
        }else
        {

        }
        if (typeof nextNode != 'undefined') {
            nextOffset=nextNode.x-(node.x+node.width);
            nextEl.find('.offset-width').html('o:'+nextOffset+'-w:'+nextNode.width);
        }else
        {

        }

        el.find('.offset-width').html('o:'+offset+'-w:'+node.width);

    };
    GridStackDivRow.prototype.remove_widget = function (el) {
        el = jQuery(el);
        var node = el.data('_gridstack_node_div_row');
        this.grid.write_header(el,node);
        this.grid.remove_node(node);
        el.remove();
        this._update_container_height();
    };

    GridStackDivRow.prototype.remove_all = function () {
        _.each(this.grid.nodes, function (node) {
            node.el.remove();
        });
        this.grid.nodes = [];
        this._update_container_height();
    };

    GridStackDivRow.prototype.resizable = function (el, val) {
        el = jQuery(el);
        el.each(function (index, el) {
            el = jQuery(el);
            var node = el.data('_gridstack_node_div_row');
            if (typeof node == 'undefined') {
                return;
            }

            node.no_resize = !(val || false);
            if (node.no_resize) {
                el.resizable('disable');
            }
            else {
                el.resizable('enable');
            }
        });
        return this;
    };

    GridStackDivRow.prototype.destroy_resizable = function (el, val) {
        el = jQuery(el);
        el.each(function (index, el) {
            el = jQuery(el);
            var node = el.data('_gridstack_node_div_row');
            if (typeof node == 'undefined') {
                return;
            }

            node.destroy = (val || false);
            if (node.destroy) {
                console.log("hello destroy_resizable");
                el.resizable('destroy');
            }

        });
        return this;
    };
    GridStackDivRow.prototype.destroy_draggable = function (el, val) {
        el = jQuery(el);
        el.each(function (index, el) {
            el = jQuery(el);
            var node = el.data('_gridstack_node_div_row');
            if (typeof node == 'undefined') {
                return;
            }

            node.destroy = (val || false);
            if (node.destroy) {
                console.log("hello destroy_draggable");
                el.draggable('destroy');
            }

        });
        return this;
    };

    GridStackDivRow.prototype.movable = function (el, val) {
        el = jQuery(el);
        el.each(function (index, el) {
            el = jQuery(el);
            var node = el.data('_gridstack_node_div_row');
            if (typeof node == 'undefined') {
                return;
            }

            node.no_move = !(val || false);
            if (node.no_move) {
                el.draggable('disable');
            }
            else {
                el.draggable('enable');
            }
        });
        return this;
    };

    GridStackDivRow.prototype.locked = function (el, val) {
        el = jQuery(el);
        el.each(function (index, el) {
            el = jQuery(el);
            var node = el.data('_gridstack_node_div_row');
            if (typeof node == 'undefined') {
                return;
            }

            node.locked = (val || false);
            console.log('locked');
            el.attr('data-gs-locked', node.locked ? 'yes' : null);

        });
        return this;
    };

    GridStackDivRow.prototype._update_element = function (el, callback) {
        el = jQuery(el).first();
        var node = el.data('_gridstack_node_div_row');
        if (typeof node == 'undefined') {
            return;
        }

        var self = this;

        self.grid.clean_nodes();
        self.grid.begin_update(node);

        callback.call(this, el, node);

        self._update_container_height();
        self.container.trigger('change', [self.grid.get_dirty_nodes()]);

        self.grid.end_update();

        self.grid._sort_nodes();
        _.each(self.grid.nodes, function (node) {
            node.el.detach();
            self.container.append(node.el);
        });
    };

    GridStackDivRow.prototype.resize = function (el, width, height) {
        this._update_element(el, function (el, node) {
            width = (width != null && typeof width != 'undefined') ? width : node.width;
            height = (height != null && typeof height != 'undefined') ? height : node.height;

            this.grid.move_node(node, node.x, node.y, width, height);
        });
    };

    GridStackDivRow.prototype.move = function (el, x, y) {
        this._update_element(el, function (el, node) {
            x = (x != null && typeof x != 'undefined') ? x : node.x;
            y = (y != null && typeof y != 'undefined') ? y : node.y;

            this.grid.move_node(node, x, y, node.width, node.height);
        });
    };

    GridStackDivRow.prototype.cell_height = function (val) {
        if (typeof val == 'undefined') {
            return this.opts.cell_height;
        }
        val = parseInt(val);
        if (val == this.opts.cell_height)
            return;
        this.opts.cell_height = val || this.opts.cell_height;
        this._update_styles();
    };

    GridStackDivRow.prototype.cell_width = function () {
        var o = this.container.find('.' + this.opts.item_class).first();
        return Math.ceil(o.outerWidth() / o.attr('data-gs-width'));
    };

    scope.GridStackUIDivRow = GridStackDivRow;

    scope.GridStackUIDivRow.Utils = UtilsDivRow;

    jQuery.fn.gridstackDivRow = function (opts) {
        return this.each(function () {
            if (!jQuery(this).data('gridstackDivRow')) {
                jQuery(this).data('gridstackDivRow', new GridStackDivRow(this, opts));
            }
        });
    };

})(window, _);



jQuery(document).ready(function($){

    element_ui_div_row=$.extend({
        listBlockWhenResize:{},
        init_div_row:function(){

            for(var i=0;i<=$('.div-row[data-block-parent-id!="0"][data-block-id!="0"]').length;i++){
                divRow=$('.div-row[data-block-parent-id!="0"][data-block-id!="0"]:eq('+i+')');

                block_id=divRow.attr('data-block-id');
                if(divRow.find('>.div-row').length)
                {
                    row_heigt=0;
                    divRow.find('>.div-row').each(function(){
                        row_heigt+=$(this).outerHeight(true);
                    });
                    divRow.css({
                        'min-height':row_heigt+100
                    });

                     divRow.sortable({
                     items:'>.div-row',
                     containment: "parent",
                     handle: '.element-move-handle[data-block-parent-id="'+block_id+'"]',
                     axis: "y",
                     scroll: false,
                     //start: element_ui_div_row.on_start_moving,
                     stop:function(event, ui){
                         blockRowId=ui.item.attr("data-block-parent-id");
                         screenSize = $('select[name="smart_phone"] option:selected').val();
                         //screensize = screenSize.toLowerCase();
                         listElement={};
                         $('.div-row[data-block-id="'+blockRowId+'"]').find('>.div-row[data-block-parent-id="'+blockRowId+'"]').each(function(index){

                             listElement[$(this).attr('data-block-id')]={
                                 ordering:index,
                                 screenSize:screenSize
                             }

                         });

                         if(typeof ajaxUpdateElement !== 'undefined'){
                             ajaxUpdateElement.abort();
                         }

                         ajaxUpdateElement=$.ajax({
                             type: "GET",
                             url: this_host+'/index.php',
                             data: (function () {

                                 dataPost = {
                                     option: 'com_utility',
                                     task: 'utility.aJaxUpdateElements',
                                     listElement: listElement,
                                     menuItemActiveId: menuItemActiveId

                                 };
                                 return dataPost;
                             })(),
                             beforeSend: function () {

                                 // $('.loading').popup();
                             },
                             success: function (response) {



                             }
                         });


                     }

                     });

                   /* divRow.find('>.div-row').resizable({
                        autoHide: true,
                        handles:'se',
                        minHeight: 70
                    });*/
                }
                if(!divRow.find('>.item_control_'+block_id).length)
                {
                    continue;
                }

                if(divRow.hasClass('div_row_'+block_id))
                    continue;
                divRow.addClass('div_row_'+block_id);
                element_ui_div_row.create_grid_stack(divRow);

            }

            $('.div-row[resizable="true"]').addClass('enable-resizable').resizable({
                autoHide: true,
                handles:'se',
                minHeight: 100
            });


        },
        create_grid_stack:function(self)
        {
            self.gridstackDivRow({
                cell_height: 10,
                vertical_margin: 10,
                handle:'.element-move-handle_'+block_id,
                placeholder_class: 'placeholder-content-'+block_id,
                item_class:'item_control_'+block_id,
                updateColumns:element_ui_div_row.updateColumns,
                changeSizeGridParent:element_ui_div_row.changeSizeGridParent,
                auto:true
            });

        },
        set_resizable_row:function(self)
        {
            block_id=self.closest('.properties.block').attr('data-object-id');
            if(self.val()==1)
            {

                $('.div-row[data-block-id="'+block_id+'"]').addClass('enable-resizable').resizable({
                    autoHide: true,
                    handles:'se',
                    minHeight: 100
                });
            }else
            {
                $('.div-row[data-block-id="'+block_id+'"]').removeClass('enable-resizable').resizable("destroy");
            }

        },
        updateColumns:function(column)
        {
            if(typeof ajaxUpdateColumns !== 'undefined'){
                ajaxUpdateColumns.abort();
            }

            //console.log(listPositionSetting);
            ajaxUpdateColumns=$.ajax({
                type: "GET",
                url: this_host+'/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxUpdateColumnsInScreen',
                        listColumn: element_ui_div_row.listBlockWhenResize,
                        menuItemActiveId: menuItemActiveId

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {



                }
            });
        },
        changeSizeGridParent:function changeSizeGridParent(grid,el,setnull)
        {
            if(setnull==1)
            {
                element_ui_div_row.listBlockWhenResize={};
            }

            $.each(grid.nodes, function( index, node ) {

                if(typeof node.el.attr('data-block-id')!=='undefined') {
                    element_ui_div_row.listBlockWhenResize[node.el.attr('data-block-id')] = {
                        ordering: index,
                        x: node.x,
                        y: node.x,
                        height: node.height,
                        width: node.width,
                        type: 'column'
                    };
                }
                console.log(node);
            });
            parentRow=el.closest('.div-row[data-block-id="'+el.attr('data-block-parent-id')+'"]');
            rowHeigh=0;
            parenColumnOfParentRow=parentRow.closest('.item_control[data-block-id="'+parentRow.attr('data-block-parent-id')+'"]');
            parenColumnOfParentRow.find('.div-column[data-block-parent-id="'+parentRow.attr('data-block-parent-id')+'"]').each(function(){
                rowHeigh+=$(this).outerHeight(false);
            });
            if(parenColumnOfParentRow.length>0) {
                gridStackOfParenColumnOfParentRow = parenColumnOfParentRow.closest('.div-row[data-block-id="'+parenColumnOfParentRow.attr('data-block-parent-id')+'"]').data('gridstackDivRow');
                if(typeof gridStackOfParenColumnOfParentRow!=="undefined")
                    cell_height=gridStackOfParenColumnOfParentRow.opts.cell_height;
                else
                    cell_height=1;
                height=rowHeigh/cell_height+2;
                if(height!=2&&typeof gridStackOfParenColumnOfParentRow!=="undefined") {
                    gridStackOfParenColumnOfParentRow.resize(parenColumnOfParentRow, null, height);
                    element_ui_div_row.changeSizeGridParent(gridStackOfParenColumnOfParentRow.grid, parenColumnOfParentRow, 0);
                }
            }



        }


    }, element_ui_element);


});