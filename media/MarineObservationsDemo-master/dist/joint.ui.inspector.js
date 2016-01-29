/*! Rappid - the diagramming toolkit

Copyright (c) 2013 client IO

 2015-02-04 


This Source Code Form is subject to the terms of the Rappid License
, v. 2.0. If a copy of the Rappid License was not distributed with this
file, You can obtain one at http://jointjs.com/license/rappid_v2.txt
 or from the Rappid archive as was distributed by client IO. See the LICENSE file.*/


/*

Copyright (C) 2011 by Yehuda Katz

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

// lib/handlebars/browser-prefix.js
var Handlebars = {};

(function(Handlebars, undefined) {
;
// lib/handlebars/base.js

Handlebars.VERSION = "1.0.0";
Handlebars.COMPILER_REVISION = 4;

Handlebars.REVISION_CHANGES = {
  1: '<= 1.0.rc.2', // 1.0.rc.2 is actually rev2 but doesn't report it
  2: '== 1.0.0-rc.3',
  3: '== 1.0.0-rc.4',
  4: '>= 1.0.0'
};

Handlebars.helpers  = {};
Handlebars.partials = {};

var toString = Object.prototype.toString,
    functionType = '[object Function]',
    objectType = '[object Object]';

Handlebars.registerHelper = function(name, fn, inverse) {
  if (toString.call(name) === objectType) {
    if (inverse || fn) { throw new Handlebars.Exception('Arg not supported with multiple helpers'); }
    Handlebars.Utils.extend(this.helpers, name);
  } else {
    if (inverse) { fn.not = inverse; }
    this.helpers[name] = fn;
  }
};

Handlebars.registerPartial = function(name, str) {
  if (toString.call(name) === objectType) {
    Handlebars.Utils.extend(this.partials,  name);
  } else {
    this.partials[name] = str;
  }
};

Handlebars.registerHelper('helperMissing', function(arg) {
  if(arguments.length === 2) {
    return undefined;
  } else {
    throw new Error("Missing helper: '" + arg + "'");
  }
});

Handlebars.registerHelper('blockHelperMissing', function(context, options) {
  var inverse = options.inverse || function() {}, fn = options.fn;

  var type = toString.call(context);

  if(type === functionType) { context = context.call(this); }

  if(context === true) {
    return fn(this);
  } else if(context === false || context == null) {
    return inverse(this);
  } else if(type === "[object Array]") {
    if(context.length > 0) {
      return Handlebars.helpers.each(context, options);
    } else {
      return inverse(this);
    }
  } else {
    return fn(context);
  }
});

Handlebars.K = function() {};

Handlebars.createFrame = Object.create || function(object) {
  Handlebars.K.prototype = object;
  var obj = new Handlebars.K();
  Handlebars.K.prototype = null;
  return obj;
};

Handlebars.logger = {
  DEBUG: 0, INFO: 1, WARN: 2, ERROR: 3, level: 3,

  methodMap: {0: 'debug', 1: 'info', 2: 'warn', 3: 'error'},

  // can be overridden in the host environment
  log: function(level, obj) {
    if (Handlebars.logger.level <= level) {
      var method = Handlebars.logger.methodMap[level];
      if (typeof console !== 'undefined' && console[method]) {
        console[method].call(console, obj);
      }
    }
  }
};

Handlebars.log = function(level, obj) { Handlebars.logger.log(level, obj); };

Handlebars.registerHelper('each', function(context, options) {
  var fn = options.fn, inverse = options.inverse;
  var i = 0, ret = "", data;

  var type = toString.call(context);
  if(type === functionType) { context = context.call(this); }

  if (options.data) {
    data = Handlebars.createFrame(options.data);
  }

  if(context && typeof context === 'object') {
    if(context instanceof Array){
      for(var j = context.length; i<j; i++) {
        if (data) { data.index = i; }
        ret = ret + fn(context[i], { data: data });
      }
    } else {
      for(var key in context) {
        if(context.hasOwnProperty(key)) {
          if(data) { data.key = key; }
          ret = ret + fn(context[key], {data: data});
          i++;
        }
      }
    }
  }

  if(i === 0){
    ret = inverse(this);
  }

  return ret;
});

Handlebars.registerHelper('if', function(conditional, options) {
  var type = toString.call(conditional);
  if(type === functionType) { conditional = conditional.call(this); }

  if(!conditional || Handlebars.Utils.isEmpty(conditional)) {
    return options.inverse(this);
  } else {
    return options.fn(this);
  }
});

Handlebars.registerHelper('unless', function(conditional, options) {
  return Handlebars.helpers['if'].call(this, conditional, {fn: options.inverse, inverse: options.fn});
});

Handlebars.registerHelper('with', function(context, options) {
  var type = toString.call(context);
  if(type === functionType) { context = context.call(this); }

  if (!Handlebars.Utils.isEmpty(context)) return options.fn(context);
});

Handlebars.registerHelper('log', function(context, options) {
  var level = options.data && options.data.level != null ? parseInt(options.data.level, 10) : 1;
  Handlebars.log(level, context);
});
;
// lib/handlebars/utils.js

var errorProps = ['description', 'fileName', 'lineNumber', 'message', 'name', 'number', 'stack'];

Handlebars.Exception = function(message) {
  var tmp = Error.prototype.constructor.apply(this, arguments);

  // Unfortunately errors are not enumerable in Chrome (at least), so `for prop in tmp` doesn't work.
  for (var idx = 0; idx < errorProps.length; idx++) {
    this[errorProps[idx]] = tmp[errorProps[idx]];
  }
};
Handlebars.Exception.prototype = new Error();

// Build out our basic SafeString type
Handlebars.SafeString = function(string) {
  this.string = string;
};
Handlebars.SafeString.prototype.toString = function() {
  return this.string.toString();
};

var escape = {
  "&": "&amp;",
  "<": "&lt;",
  ">": "&gt;",
  '"': "&quot;",
  "'": "&#x27;",
  "`": "&#x60;"
};

var badChars = /[&<>"'`]/g;
var possible = /[&<>"'`]/;

var escapeChar = function(chr) {
  return escape[chr] || "&amp;";
};

Handlebars.Utils = {
  extend: function(obj, value) {
    for(var key in value) {
      if(value.hasOwnProperty(key)) {
        obj[key] = value[key];
      }
    }
  },

  escapeExpression: function(string) {
    // don't escape SafeStrings, since they're already safe
    if (string instanceof Handlebars.SafeString) {
      return string.toString();
    } else if (string == null || string === false) {
      return "";
    }

    // Force a string conversion as this will be done by the append regardless and
    // the regex test will do this transparently behind the scenes, causing issues if
    // an object's to string has escaped characters in it.
    string = string.toString();

    if(!possible.test(string)) { return string; }
    return string.replace(badChars, escapeChar);
  },

  isEmpty: function(value) {
    if (!value && value !== 0) {
      return true;
    } else if(toString.call(value) === "[object Array]" && value.length === 0) {
      return true;
    } else {
      return false;
    }
  }
};
;
// lib/handlebars/runtime.js

Handlebars.VM = {
  template: function(templateSpec) {
    // Just add water
    var container = {
      escapeExpression: Handlebars.Utils.escapeExpression,
      invokePartial: Handlebars.VM.invokePartial,
      programs: [],
      program: function(i, fn, data) {
        var programWrapper = this.programs[i];
        if(data) {
          programWrapper = Handlebars.VM.program(i, fn, data);
        } else if (!programWrapper) {
          programWrapper = this.programs[i] = Handlebars.VM.program(i, fn);
        }
        return programWrapper;
      },
      merge: function(param, common) {
        var ret = param || common;

        if (param && common) {
          ret = {};
          Handlebars.Utils.extend(ret, common);
          Handlebars.Utils.extend(ret, param);
        }
        return ret;
      },
      programWithDepth: Handlebars.VM.programWithDepth,
      noop: Handlebars.VM.noop,
      compilerInfo: null
    };

    return function(context, options) {
      options = options || {};
      var result = templateSpec.call(container, Handlebars, context, options.helpers, options.partials, options.data);

      var compilerInfo = container.compilerInfo || [],
          compilerRevision = compilerInfo[0] || 1,
          currentRevision = Handlebars.COMPILER_REVISION;

      if (compilerRevision !== currentRevision) {
        if (compilerRevision < currentRevision) {
          var runtimeVersions = Handlebars.REVISION_CHANGES[currentRevision],
              compilerVersions = Handlebars.REVISION_CHANGES[compilerRevision];
          throw "Template was precompiled with an older version of Handlebars than the current runtime. "+
                "Please update your precompiler to a newer version ("+runtimeVersions+") or downgrade your runtime to an older version ("+compilerVersions+").";
        } else {
          // Use the embedded version info since the runtime doesn't know about this revision yet
          throw "Template was precompiled with a newer version of Handlebars than the current runtime. "+
                "Please update your runtime to a newer version ("+compilerInfo[1]+").";
        }
      }

      return result;
    };
  },

  programWithDepth: function(i, fn, data /*, $depth */) {
    var args = Array.prototype.slice.call(arguments, 3);

    var program = function(context, options) {
      options = options || {};

      return fn.apply(this, [context, options.data || data].concat(args));
    };
    program.program = i;
    program.depth = args.length;
    return program;
  },
  program: function(i, fn, data) {
    var program = function(context, options) {
      options = options || {};

      return fn(context, options.data || data);
    };
    program.program = i;
    program.depth = 0;
    return program;
  },
  noop: function() { return ""; },
  invokePartial: function(partial, name, context, helpers, partials, data) {
    var options = { helpers: helpers, partials: partials, data: data };

    if(partial === undefined) {
      throw new Handlebars.Exception("The partial " + name + " could not be found");
    } else if(partial instanceof Function) {
      return partial(context, options);
    } else if (!Handlebars.compile) {
      throw new Handlebars.Exception("The partial " + name + " could not be compiled when running in runtime-only mode");
    } else {
      partials[name] = Handlebars.compile(partial, {data: data !== undefined});
      return partials[name](context, options);
    }
  }
};

Handlebars.template = Handlebars.VM.template;
;
// lib/handlebars/browser-suffix.js
})(Handlebars);
;

this["joint"] = this["joint"] || {};
this["joint"]["templates"] = this["joint"]["templates"] || {};
this["joint"]["templates"]["inspector"] = this["joint"]["templates"]["inspector"] || {};

this["joint"]["templates"]["inspector"]["color.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<input type=\"color\" class=\"color\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" value=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" />\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["group.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"group\">\n    <h3 class=\"group-label\">";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</h3>\n</div>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["list-item.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"list-item\" data-index=\"";
  if (stack1 = helpers.index) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.index; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n    <button class=\"btn-list-del\">-</button>\n</div>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["list.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<div class=\"list\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n    <button class=\"btn-list-add\">+</button>\n    <div class=\"list-items\">\n    </div>\n</div>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["number.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<input type=\"number\" class=\"number\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" min=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.min)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" max=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.max)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" step=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.step)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" value=\"";
  if (stack2 = helpers.value) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.value; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "\"/>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["object-property.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"object-property\" data-property=\"";
  if (stack1 = helpers.property) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.property; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n</div>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["object.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<div class=\"object\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n    <div class=\"object-properties\"></div>\n</div>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["range.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<form onchange=\"$(this).find('output').text(range.value)\">\n    <label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ": (<output>";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</output>"
    + escapeExpression(((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.unit)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + ")</label>\n    <input type=\"range\" class=\"range\" name=\"range\" data-type=\"";
  if (stack2 = helpers.type) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.type; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "\" data-attribute=\"";
  if (stack2 = helpers.attribute) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.attribute; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "\" min=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.min)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" max=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.max)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" step=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.step)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" value=\"";
  if (stack2 = helpers.value) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.value; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "\"/>\n</form>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["select.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += " multiple size=\""
    + escapeExpression(((stack1 = ((stack1 = ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.options)),stack1 == null || stack1 === false ? stack1 : stack1.length)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" ";
  return buffer;
  }

function program3(depth0,data,depth1) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n    <option value=\""
    + escapeExpression(((stack1 = depth0.value),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" ";
  options = {hash:{},inverse:self.noop,fn:self.program(4, program4, data),data:data};
  stack2 = ((stack1 = helpers['is-or-contains'] || depth0['is-or-contains']),stack1 ? stack1.call(depth0, depth0.value, depth1.value, options) : helperMissing.call(depth0, "is-or-contains", depth0.value, depth1.value, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += ">"
    + escapeExpression(((stack1 = depth0.content),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</option>\n    ";
  return buffer;
  }
function program4(depth0,data) {
  
  
  return " selected ";
  }

  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<select class=\"select\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" value=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" ";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.multiple), {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += ">\n    ";
  stack2 = helpers.each.call(depth0, ((stack1 = depth0.options),stack1 == null || stack1 === false ? stack1 : stack1.items), {hash:{},inverse:self.noop,fn:self.programWithDepth(3, program3, data, depth0),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n</select>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["text.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<input type=\"text\" class=\"text\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" value=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" />\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["textarea.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<textarea class=\"textarea\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</textarea>\n";
  return buffer;
  });

this["joint"]["templates"]["inspector"]["toggle.html"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  
  return " checked ";
  }

  buffer += "<label>";
  if (stack1 = helpers.label) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.label; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ":</label>\n<div class=\"toggle\">\n    <input type=\"checkbox\" data-type=\"";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-attribute=\"";
  if (stack1 = helpers.attribute) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.attribute; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" ";
  stack1 = helpers['if'].call(depth0, depth0.value, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += " />\n    <span><i></i></span>\n</div>\n";
  return buffer;
  });
Handlebars.registerHelper('is', function(value, test, options) {
    if (value == test) {
        return options.fn(this);
    }
    return options.inverse(this);
});

Handlebars.registerHelper('is-or-contains', function(value, test, options) {
    if (_.isArray(test) ? _.contains(test, value): value == test) {
        return options.fn(this);
    }
    return options.inverse(this);
});

Handlebars.registerPartial('list-item', joint.templates.inspector['list-item.html']);

// Inspector plugin.
// -----------------

// This plugin creates a two-way data-binding between the cell model and a generated
// HTML form with input fields of a type declaratively specified in an options object passed
// into the element inspector.

/*
USAGE:

var inspector = new joint.ui.Inspector({
    cellView: cellView,
    inputs: {
            attrs: {
                text: {
                    'font-size': { type: 'number', min: 5, max: 80, group: 'text', index: 2 },
                    'text': { type: 'textarea', group: 'text', index: 1 }
                }
            },
            position: {
                x: { type: 'number', group: 'geometry', index: 1 },
                y: { type: 'number', group: 'geometry', index: 2 }
            },
            size: {
                width: { type: 'number', min: 1, max: 500, group: 'geometry', index: 3 },
                height: { type: 'number', min: 1, max: 500, group: 'geometry', index: 4 }
            },
            mydata: {
                foo: { type: 'textarea', group: 'data' }
            }
   },
   groups: {
           text: { label: 'Text', index: 1 },
           geometry: { label: 'Geometry', index: 2, closed: true },
           data: { label: 'data', index: 3 }
   }
});

$('.inspector-container').append(inspector.render().el);
*/

joint.ui.Inspector = Backbone.View.extend({

    className: 'inspector',

    options: {
        cellView: undefined,    // One can pass either a cell view ...
        cell: undefined,        // ... or the cell itself.
        live: true,      // By default, we enabled live changes from the inspector inputs.
        validateInput: function(input, path) { return input.validity.valid; }
    },

    events: {
        'mousedown': 'startBatchCommand',
        'change [data-attribute]': 'onChangeInput',
        'click .group-label': 'onGroupLabelClick',
        'click .btn-list-add': 'addListItem',
        'click .btn-list-del': 'deleteListItem'
    },

    initialize: function(options) {

	this.options = _.extend({}, _.result(this, 'options'), options || {});
        this.options.groups = this.options.groups || {};

        _.bindAll(this, 'stopBatchCommand');

        // Start a batch command on `mousedown` over the inspector and stop it when the mouse is
        // released anywhere in the document. This prevents setting attributes in tiny steps
        // when e.g. a value is being adjusted through a slider. This gives other parts
        // of the application a chance to treat the serveral little changes as one change.
        // Consider e.g. the CommandManager plugin.
        $(document).on('mouseup', this.stopBatchCommand);
        
        
        // Flatten the `inputs` object until the level where the options object is.
        // This produces an object with this structure: { <path>: <options> }, e.g. { 'attrs/rect/fill': { type: 'color' } }
        this.flatAttributes = joint.util.flattenObject(this.options.inputs, '/', function(obj) {
            // Stop flattening when we reach an object that contains the `type` property. We assume
            // that this is our options object. @TODO This is not very robust as there could
            // possibly be another object with a property `type`. Instead, we should stop
            // just before the nested leaf object.
            return obj.type;
        });

        // `_when` object maps path to a set of conditions (either `eq` or `regex`).
        // When an input under the path changes to
        // the value that equals all the `eq` values or matches all the `regex` regular expressions,
        // the inspector rerenders itself and this time includes all the
        // inputs that met the conditions.
        this._when = {};

        // `_bound` object maps a slave path to a master path (A slave is using master's data).
        // When an input under the master path changes, the inspector rerenders the input under the
        // slave path
        this._bound = {};

        // Add the attributes path the options object as we're converting the flat object to an array
        // and so we would loose the keys otherwise.
        var attributesArray = _.map(this.flatAttributes, function(options, path) {

            if (options.when) {

                var dependant = { expression: options.when, path: path };

                _.each(this.extractExpressionPaths(dependant.expression), function(condPath) {
                    (this._when[condPath] || (this._when[condPath] = [])).push(dependant);
                }, this);
            }

            // If the option type is 'select' and its options needs resolving (is defined by path)
            // we bind the select (slave) and the input under the path (master) together.
            if (options.type == 'select' && _.isString(options.options)) {
                // slave : master
                this._bound[path] = options.options;
            }

            options.path = path;
            return options;

        }, this);

        // Sort the flat attributes object by two criteria: group first, then index inside that group.
        // As underscore 'sortBy' is a stable sort algorithm we can sort by index first and then
        // by group again.
        this.groupedFlatAttributes = _.sortBy(_.sortBy(attributesArray, 'index'), function(options) {
            var groupOptions = this.options.groups[options.group];
            return (groupOptions && groupOptions.index) || Number.MAX_VALUE;
        }, this);

        // Cache all the attributes (inputs, lists and objects) with every change to the DOM tree.
        // Chache it by its path.
        this.on('render', function() {

            this._byPath = {};
            
            _.each(this.$('[data-attribute]'), function(attribute) {
                var $attribute = $(attribute);
                this._byPath[$attribute.attr('data-attribute')] = $attribute;
            }, this);
            
        }, this);

        // Listen on events on the cell.
        this.listenTo(this.getModel(), 'all', this.onCellChange, this);
    },

    getModel: function() {
        return this.options.cell || this.options.cellView.model;
    },

    onCellChange: function(eventName, cell, change, opt) {

        opt = opt || {};

        // Do not react on changes that happened inside this inspector. This would
        // cause a re-render of the same inspector triggered by an input change in this inspector.
        if (opt.inspector == this.cid) return;

        // Note that special care is taken for all the transformation attribute changes
        // (`position`, `size` and `angle`). See below for details.
        
        switch (eventName) {
            
          case 'remove':
            // Make sure the element inspector gets removed when the cell is removed from the graph.
            // Otherwise, a zombie cell could possibly be updated.
            this.remove();
            break;
          case 'change:position':
            // Make a special case for `position` as this one is performance critical.
            // There is no need to rerender the whole inspector but only update the position input.
            this.updateInputPosition();
            break;
          case 'change:size':
            // Make a special case also for the `size` attribute for the same reasons as for `position`.
            this.updateInputSize();
            break;
          case 'change:angle':
            // Make a special case also for the `angle` attribute for the same reasons as for `position`.
            this.updateInputAngle();
            break;
          case 'change:source':
          case 'change:target':
          case 'change:vertices':
            // Make a special case also for the 'source' and 'target' of a link for the same reasons
            // as for 'position'. We don't expect source or target to be configurable.
            // That's why we do nothing here.
            break;
        default:
            // Re-render only on specific attributes changes. These are all events that starts with `'change:'`.
            // Otherwise, the re-render would be called unnecessarily (consider generic `'change'` event, `'bach:start'`, ...).
            var changeAttributeEvent = 'change:';
            if (eventName.slice(0, changeAttributeEvent.length) === changeAttributeEvent) {
                
                this.render();
            }
            break;
        }
    },

    render: function() {

        this.$el.empty();

        var lastGroup;
        var $groups = [];
        var $group;
        
        _.each(this.groupedFlatAttributes, function(options) {

            if (lastGroup !== options.group) {
                // A new group should be created.

                var groupOptions = this.options.groups[options.group];
                var groupLabel = groupOptions ? groupOptions.label || options.group : options.group;
                
                $group = $(joint.templates.inspector['group.html']({ label: groupLabel }));
                $group.attr('data-name', options.group);
                if (groupOptions && groupOptions.closed) $group.addClass('closed');
                $groups.push($group);
            }
            
            this.renderTemplate($group, options, options.path);

            lastGroup = options.group;
            
        }, this);

        this.$el.append($groups);

        this.trigger('render');
        
        return this;
    },

    // Get the value of the attribute at `path` based on the `options.defaultValue`,
    // and `options.valueRegExp` if present.
    getCellAttributeValue: function(path, options) {

        var cell = this.getModel();

        var value = joint.util.getByPath(cell.attributes, path, '/');
        if (!options) return value;

        if (_.isUndefined(value) && !_.isUndefined(options.defaultValue)) {
            value = options.defaultValue;
        }

        if (options.valueRegExp) {

            if (_.isUndefined(value)) {
                
                throw new Error('Inspector: defaultValue must be present when valueRegExp is used.');
            }
            
            var valueMatch = value.match(new RegExp(options.valueRegExp));
            value = valueMatch && valueMatch[2];
        }

        return value;
    },

    resolveBindings: function(options) {

        switch(options.type) {

          case 'select': // options['options'] are transformed here to options['items']

            var items = options.options || [];

            // resolve items if the options are defined indirectly as a reference to a model property
            if (_.isString(items)) {

                items = joint.util.getByPath(this.getModel().attributes, items, '/') || [];
            }

            // Check if items array has incorrect format (i.e an array of strings).
            if (!_.isObject(items[0])) {
                // Transform each array item into the { value: [value], content: [content] } object.
                items = _.map(items, function(item) { return { value: item, content: item }; });
            }

            // export result as 'items'
            options.items = items;

            break;
        }
    },

    updateBindings: function(path) {

        // Find all inputs which are bound to the current input (i.e find all slaves).
        var slaves = _.reduce(this._bound, function(result, master, slave) {

            // Does the current input path starts with a master path?
            if (!path.indexOf(master)) result.push(slave);

            return result;

        }, []);

        if (!_.isEmpty(slaves)) {

            // Re-render all slave inputs
            _.each(slaves, function(slave) {
                this.renderTemplate(null, this.flatAttributes[slave], slave, { replace: true });
            }, this);

            this.trigger('render');
        }
    },

    renderTemplate: function($el, options, path, opt) {

        $el = $el || this.$el;
        opt = opt || {};

        this.resolveBindings(options);

        // Wrap the input into a `.field` classed element so that we can easilly hide and show
        // the entire block.
        var $field = $('<div class="field"></div>').attr('data-field', path);

        if (options.when && !this.isExpressionValid(options.when)) {
            $field.addClass('hidden');
            if (options.when.otherwise) {
                if (options.when.otherwise.unset) this.unsetProperty(path);
            }
        }

        var value = this.getCellAttributeValue(path, options);

        var inputHtml = joint.templates.inspector[options.type + '.html']({
            options: options,
            type: options.type,
            label: options.label || path,
            attribute: path,
            value: value
        });

        var $input = $(inputHtml);
        $field.append($input);

        // `options.attrs` allows for setting arbitrary attributes on the generated HTML.
        // This object is of the form: `<selector> : { <attributeName> : <attributeValue>, ... }`
        _.each(options.attrs, function(attrs, selector) {
            $field.find(selector).addBack().filter(selector).attr(attrs);
        });

        if (options.type === 'list') {
            
            _.each(value, function(itemValue, idx) {

                var $listItem = $(joint.templates.inspector['list-item.html']({
                    index: idx
                }));

                this.renderTemplate($listItem, options.item, path + '/' + idx);

                $input.children('.list-items').append($listItem);
                
            }, this);
            
        } else if (options.type === 'object') {

            options.flatAttributes = joint.util.flattenObject(options.properties, '/', function(obj) {
                // Stop flattening when we reach an object that contains the `type` property. We assume
                // that this is our options object. @TODO This is not very robust as there could
                // possibly be another object with a property `type`. Instead, we should stop
                // just before the nested leaf object.
                return obj.type;
            });

            var attributesArray = _.map(options.flatAttributes, function(options, path) {
                options.path = path;
                return options;
            });
            // Sort the attributes by `index` and assign the `path` to the `options` object
            // so that we can acess it later.
            attributesArray = _.sortBy(attributesArray, function(options) {

                return options.index;
            });

            _.each(attributesArray, function(propertyOptions) {

                var $objectProperty = $(joint.templates.inspector['object-property.html']({
                    property: propertyOptions.path
                }));

                this.renderTemplate($objectProperty, propertyOptions, path + '/' + propertyOptions.path);

                $input.children('.object-properties').append($objectProperty);
                
            }, this);
        }

        if (opt.replace) {

            $el.find('[data-field="' + path + '"]').replaceWith($field);

        } else {

            $el.append($field);
        }
    },

    updateInputPosition: function() {

        var $inputX = this._byPath['position/x'];
        var $inputY = this._byPath['position/y'];

        var position = this.getModel().get('position');
        
        if ($inputX) { $inputX.val(position.x); }
        if ($inputY) { $inputY.val(position.y); }
    },
    updateInputSize: function() {

        var $inputWidth = this._byPath['size/width'];
        var $inputHeight = this._byPath['size/height'];

        var size = this.getModel().get('size');
        
        if ($inputWidth) { $inputWidth.val(size.width); }
        if ($inputHeight) { $inputHeight.val(size.height); }
    },
    updateInputAngle: function() {

        var $inputAngle = this._byPath['angle'];

        var angle = this.getModel().get('angle');
        
        if ($inputAngle) { $inputAngle.val(angle); }
    },

    onChangeInput: function(evt) {

        var $input = $(evt.target);
        var path = $input.attr('data-attribute');

        if (!this.options.validateInput($input[0], path)) return;

        if (this.options.live) {
            
            this.updateCell($input, path);
        }

        var type = $input.attr('data-type');
        var value = this.parse(type, $input.val(), $input[0]);
        var dependants = this._when[path];

        // Notify the outside world that an input has changed.
        this.trigger('change:' + path, value, $input[0]);

        if (dependants) {

            // Go through all the inputs that are dependent on the value of the changed input.
            // Show them if the 'when' expression is evaluated to 'true'. Hide them otherwise.
            _.each(dependants, function(dependant) {

                var $attribute = this._byPath[dependant.path];
                var $field = $attribute.closest('.field');
                var previouslyHidden = $field.hasClass('hidden');

                var valid = this.isExpressionValid(dependant.expression);

                $field.toggleClass('hidden', !valid);

                if (dependant.expression.otherwise) {
                    // unset option - works only with 'live' inspector.
                    if (dependant.expression.otherwise.unset && this.options.live) {

                        if (!valid) {

                            // When an attribute is hidden in the inspector unset its value in the model.
                            this.unsetProperty(dependant.path);
                            this.renderTemplate(null, this.flatAttributes[dependant.path], dependant.path, { replace: true });
                            this.trigger('render');

                        } else if (previouslyHidden) {

                            // The attribute just switched from hidden to visible. We set its value
                            // to the cell again in case it was unset earlier.
                            this.updateCell($attribute, dependant.path);
                        }
                    }
                }

            }, this);
        }
    },

    // unset a model property
    unsetProperty: function(path, opt) {

        var cell = this.getModel();
        var pathArray = path.split('/');
        var attribute = _.first(pathArray);
        var nestedAttrPath = _.rest(pathArray).join('/');

        opt = opt || {};
        opt.inspector = this.cid;
        opt['inspector_' + this.cid] = true; // kept for backwards compatibility

        if (path == 'attrs') {
            // Unsetting an attrs property requires to re-render the view. The cell.removeAttr() does
            // it for us.
            cell.removeAttr(nestedAttrPath, opt);
        } else if (path == attribute) {
            // Unsetting a primitive object. Fast path.
            cell.unset(attribute, opt);
        } else {
            // Unsetting a nested property.
            var oldAttrValue = _.merge({}, cell.get(attribute));
            var newAttrValue = joint.util.unsetByPath(oldAttrValue, nestedAttrPath, '/');
            cell.set(attribute, newAttrValue, opt);
        }
    },

    getOptions: function($attribute) {

        if ($attribute.length === 0) return undefined;
        
        var path = $attribute.attr('data-attribute');
        var type = $attribute.attr('data-type');
        var options = this.flatAttributes[path];
        if (!options) {
            var $parentAttribute = $attribute.parent().closest('[data-attribute]');
            var parentPath = $parentAttribute.attr('data-attribute');
            options = this.getOptions($parentAttribute);
            var childPath = path.replace(parentPath + '/', '');
            var parent = options;
            options = parent.item || parent.flatAttributes[childPath];
            options.parent = parent;
        }
        return options;
    },

    updateCell: function($attr, attrPath) {

        var cell = this.getModel();

        var byPath = {};

        if ($attr) {
            // We are updating only one specific attribute
            byPath[attrPath] = $attr;
        } else {
            // No parameters given. We are updating all attributes
            byPath = this._byPath;
        }

        this.startBatchCommand();
        this._tempListsByPath = {};

        _.each(byPath, function($attribute, path) {

            if ($attribute.closest('.field').hasClass('hidden')) return;

            var type = $attribute.attr('data-type');
            var value;
            var options;
            var kind;

            switch (type) {
                
              case 'list':

                // TODO: this is wrong! There could have been other properties not
                // defined in the inspector which we delete by this! We should only remove
                // those items that disappeared from DOM.

		// Do not empty the list (and trigger change event) if we have at
		// least one item in the list. It is not only desirable but necessary.
		// An example is when an element has ports. If we emptied the list
		// and then reconstructed it again, all the links connected to the ports
		// will get lost as the element with ports will think the ports disappeared
		// first.
                if (!byPath[path + '/0']) {
                    this.setProperty(path, [], { rewrite: true });
                } else {
                    this._tempListsByPath[path] = [];
                }

                break;
                
              case 'object':
                // For objects, all is handled in the actual inputs.
                break;
                
            default:

                if (!this.options.validateInput($attribute[0], path)) return;

                value = this.parse(type, $attribute.val(), $attribute[0]);
                options = this.getOptions($attribute);

                if (options.valueRegExp) {
                    var oldValue = joint.util.getByPath(cell.attributes, path, '/') || options.defaultValue;
                    value = oldValue.replace(new RegExp(options.valueRegExp), '$1' + value + '$3');
                }

		if (options.parent && options.parent.type === 'list') {

		    var pathArray = path.split('/');
                    var parentPath =_.initial(pathArray).join('/');

                    // if the temporary list doesn't exist we are changing the input value only
                    if (this._tempListsByPath[parentPath]) {

		        var index = parseInt(_.last(pathArray), 10);
		        this._tempListsByPath[parentPath][index] = value;

		        // Check if there is another item coming, if not, trigger change event,
		        // otherwise do not do that as that is not necessary and not desirable either.
		        if (!byPath[parentPath + '/' + (index + 1)]) {
                            this.setProperty(parentPath, this._tempListsByPath[parentPath], { rewrite: true });
                        }

                        return;
                    }
		}

                this.setProperty(path, value);
                break;
            }

            this.updateBindings(path);

        }, this);

        this.stopBatchCommand();
    },

    setProperty: function(path, value, opt) {

        opt = opt || {};
        opt.inspector = this.cid;
        opt['inspector_' + this.cid] = true; // kept for backwards compatibility

        // the model doesn't have to be a JointJS cell necessary. It could be
        // an ordinary Backbone.Model and such would have no method 'prop'.
        joint.dia.Cell.prototype.prop.call(this.getModel(), path, value, opt);
    },

    // Parse the input `value` based on the input `type`.
    // Override this method if you need your own specific parsing.
    parse: function(type, value, targetElement) {
        
        switch (type) {
          case 'number':
            value = parseFloat(value);
            break;
          case 'toggle':
            value = targetElement.checked;
            break;
          default:
            value = value;
            break;
        }
        return value;
    },

    startBatchCommand: function() {

        this.getModel().trigger('batch:start');
    },
    
    stopBatchCommand: function() {

        this.getModel().trigger('batch:stop');
    },

    addListItem: function(evt) {

        var $target = $(evt.target);
        var $attribute = $target.closest('[data-attribute]');
        var path = $attribute.attr('data-attribute');
        var options = this.getOptions($attribute);

        // Take the index of the last list item and increase it by one.
        var $lastListItem = $attribute.children('.list-items').children('.list-item').last();
        var lastIndex = $lastListItem.length === 0 ? -1 : parseInt($lastListItem.attr('data-index'), 10);
        var index = lastIndex + 1;

        var $listItem = $(joint.templates.inspector['list-item.html']({ index: index }));
        
        this.renderTemplate($listItem, options.item, path + '/' + index);

        $target.parent().children('.list-items').append($listItem);
        $listItem.find('input:first').focus();

        this.trigger('render');
        
        if (this.options.live) {
            this.updateCell();
        }
    },
    
    deleteListItem: function(evt) {

        var $listItem = $(evt.target).closest('.list-item');

        // Update indexes of all the following list items and their inputs.
        $listItem.nextAll('.list-item').each(function() {
            
            var index = parseInt($(this).attr('data-index'), 10);
            var newIndex = index - 1;

            // TODO: if field labels are not defined and the paths string are used
            // for labels instead, these are not rewritten.

            // Find all the nested inputs and update their path so that it contains the new index.
            $(this).find('[data-field]').each(function() {
                $(this).attr('data-field', $(this).attr('data-field').replace('/' + index, '/' + newIndex));
            });

            // Find all the nested inputs and update their path so that it contains the new index.
            $(this).find('[data-attribute]').each(function() {
                $(this).attr('data-attribute', $(this).attr('data-attribute').replace('/' + index, '/' + newIndex));
            });

            // Update the index of the list item itself.
            $(this).attr('data-index', newIndex);
        });

        $listItem.remove();
        this.trigger('render');
        
        if (this.options.live) {
            this.updateCell();
        }
    },

    remove: function() {

        $(document).off('mouseup', this.stopBatchCommand);
        return Backbone.View.prototype.remove.apply(this, arguments);
    },

    onGroupLabelClick: function(evt) {

        // Prevent default action for iPad not to handle this event twice.
        evt.preventDefault();
        
        var $group = $(evt.target).closest('.group');
        this.toggleGroup($group.data('name'));
    },
    
    toggleGroup: function(name) {

        this.$('.group[data-name="' + name + '"]').toggleClass('closed');
    },

    closeGroup: function(name) {
        
        this.$('.group[data-name="' + name + '"]').addClass('closed');
    },

    openGroup: function(name) {
        
        this.$('.group[data-name="' + name + '"]').removeClass('closed');
    },

    closeGroups: function() {

        this.$('.group').addClass('closed');
    },

    openGroups: function() {

        this.$('.group').removeClass('closed');
    },

    // Expressions

    _isComposite: function(expr) {
        var composite = _.pick(expr, 'not','and','or','nor');
        return _.some(composite);
    },

    _isPrimitive: function(expr) {
        var primitive = _.pick(expr, 'eq', 'ne', 'regex', 'text', 'lt', 'lte', 'gt', 'gte', 'in', 'nin');
        return _.some(primitive);
    },

    _evalPrimitive: function(expr) {

        return _.reduce(expr, function(res, condition, operator) {
            return _.reduce(condition, function(res, condValue, condPath) {

                var val = this.getCellAttributeValue(condPath, this.flatAttributes[condPath]);

                switch (operator) {
                  case 'eq':
                    return condValue == val;
                  case 'ne':
                    return condValue != val;
                  case 'regex':
                    return (new RegExp(condValue)).test(val);
                  case 'text':
                    return !condValue || (_.isString(val) && val.toLowerCase().indexOf(condValue) > -1);
                  case 'lt':
                    return val < condValue;
                  case 'lte':
                    return val <= condValue;
                  case 'gt':
                    return val > condValue;
                  case 'gte':
                    return val >= condValue;
                  case 'in':
                    return _.contains(condValue, val);
                  case 'nin':
                    return !_.contains(condValue, val);
                default:
                    return res;
                }

            }, false, this);
        }, false, this);
    },

    _evalExpression: function(expr) {

        if (this._isPrimitive(expr)) {
            return this._evalPrimitive(expr);
        }

        return _.reduce(expr, function(res, childExpr, operator) {

            if (operator == 'not') return !this._evalExpression(childExpr);

            var childExprRes = _.map(childExpr, this._evalExpression, this);

            switch (operator) {
              case 'and':
                return _.every(childExprRes);
              case 'or':
                return  _.some(childExprRes);
              case 'nor':
                return !_.some(childExprRes);
            default:
                return res;
            }

        }, false, this);
    },

    _extractVariables: function(expr) {

        if (_.isArray(expr) || this._isComposite(expr)) {
            return _.reduce(expr, function(res, childExpr) {
                return res.concat(this._extractVariables(childExpr));
            }, [], this);
        }

        return _.reduce(expr, function(res, primitive) {
            return _.keys(primitive);
        }, []);
    },

    isExpressionValid: function(expr) {
        expr = _.omit(expr, 'otherwise');
        return this._evalExpression(expr);
    },

    extractExpressionPaths: function(expr) {
        expr = _.omit(expr, 'otherwise');
        return _.uniq(this._extractVariables(expr));
    }
});
