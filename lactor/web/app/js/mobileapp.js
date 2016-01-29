
MobileApp = function(name, options) {
  if (MobileApp.instance) {
    return MobileApp.instance
  } else if (this != window) {
    MobileApp.instance = this;
  }
  this.name = name
  // set up application options.
  this.options = {
    'clickEvent': 'click',
    'panelTransitionTime': 300,
    'scale': 400,
    'menuFont': '2em'
  }
  if (options) {
    for (var i in options) {
      this.options[i] = options[i];
    }
  }
  if (this.options.scale) {
    this.scale(this.options.scale);
  }
  this.browser = this.detectBrowser();
  $('title').html(name);
  this.actionBar = new ActionBar(name);
  this.content = $('<div id="mainContent"></div>');
  $(document.body).append(this.content);
  $(window).on('hashchange', (function() {
    this.loadView(location.hash.substring(1));
  }).bind(this));
  // see if this is running in phonegap/cordova
  if (location.protocol == 'file:') {
    $.getScript('cordova.js');
  }
//  this.emulateSwipe(); // broken
  return this;
}

/**
 * This is only designed to detect mobile browsers for now. Results on Desktop
 * browsers may be unpredictable.
 *
 * TODO: Fix this for tablet and desktop browser detection.
 */
MobileApp.prototype.detectBrowser = function() {
  var browser = {};
  if (/(iPhone|iPad)/.exec(navigator.userAgent)) {
    browser.iOS = {};
    if (/Mac OS/.exec(navigator.userAgent)) {
      browser.iOS.safari = true;
    }
    if (/CriOS/.exec(navigator.userAgent)) {
      browser.iPhone.chrome = true;
    }
    if (/iPad/.exec(navigator.userAgent)) {
      browser.tablet = true;
    }
  }
  if (/Android/.exec(navigator.userAgent)) {
    browser.android = {}
    if (/Chrome\//.exec(navigator.userAgent)) {
      browser.android.chrome = true;
    } else {
      browser.android.webview = true;
    }
    if (!/Mobile Safari/.exec(navigator.userAgent)) {
      browser.tablet = true;
    }
  }
  return browser;
}

MobileApp.prototype.request = function(settings) {
  return new AjaxRequest(settings);
}

MobileApp.prototype.requireAuth = function(authURL, deauthURL) {
  this.authURL = authURL;
  this.deauthURL = deauthURL;
}

MobileApp.prototype.scale = function(width) {
  if (width) {
    this.zoom = $(window).width() / width;
    $(document.body).css('zoom', this.zoom);
  }
  return this.zoom;
}

MobileApp.prototype.width = function() {
    return $(window).width() / Number($(document.body).css('zoom'));
}

MobileApp.prototype.initPanels = function(selector) {
  if (!selector)
    selector = '.view_panel';
  this.panels = {};
  this.panels.elements = $(selector);
  this.panels.elements.css('position', 'absolute').
    css('left', this.width()).
    css('width', this.width()).hide().
    first().css('left', 0).show();
  this.panels.current = 0;
}

MobileApp.prototype.showMessage = function(message, duration) {
  if (!duration)
    duration = 2000;
  if (!this.messageBox) {
    this.messageBox = $('<div id="messageBox"></div>');
    this.messageBox.css({
      'position': 'fixed',
      'bottom': '25%',
      'width': '50%',
      'margin-left': '25%',
      'text-align': 'center',
      'background-color': '#333',
      'border': '1px solid #aaa',
      'color': 'white',
      'padding': '0.5em',
      'opacity': 0,
      'z-index': 10
    });
  }
  if (this.messageBox.parent().length) {
    // a message is already being displayed - wait for it.
    setTimeout(this.showMessage.bind(this, message, duration), 100);
    return;
  }
  this.messageBox.html(message);
  $(document.body).append(this.messageBox);
  this.messageBox.animate({'opacity':1}, 1000);
  var hideMessage = (function() {
    this.messageBox.animate({'opacity':0}, 1000, (function(){
      this.messageBox.detach();
    }).bind(this));
  }).bind(this)
  setTimeout(hideMessage, duration)
}

MobileApp.prototype.showPanel = function(index) {
  if (this.panels.current < index) {
    $(this.panels.elements[this.panels.current]).
        animate({'left': -this.width()},
        this.options.panelTransitionTime);
    $(this.panels.elements[index]).show().animate({'left': 0}, 
        this.options.panelTransitionTime);
    for (var i = this.panels.current+1; i < index; i++) {
      $(this.panels.elements[i]).css('left', -this.width()).hide();
    }
  } else if (this.panels.current > index) {
    $(this.panels.elements[this.panels.current]).animate({
      'left': this.width()
    }, this.options.panelTransitionTime, function() { $(this).hide(); });
    $(this.panels.elements[index]).show().animate({'left': 0},
        this.options.panelTransitionTime);
    for (var i = index+1; i < this.panels.current; i++) {
      $(this.panels.elements[i]).css('left', this.width()).hide();
    }
  }
  this.panels.current = index;
}

MobileApp.prototype.mustache = function(element, values) {
  if (values === undefined) {
    values = element;
    element = this.content;
  }
  var newContent = $(element).mustache(values);
  $(element).empty()
  $(element).append(newContent);
}

/*
 * Emulate swipes in javascript. This doesn't work yet.
 */
MobileApp.prototype.emulateSwipe = function() {
  // emulate 'swipe' events
  $(document.body).capture('touchstart', (function(evt) {
    e = evt;
    this.touchInfo = {
      startX: evt.pageX, 
      startY: evt.pageY,
      startTime: new Date().getTime(),
      lastX: evt.pageX, 
      lastY: evt.pageY,
      maxForce: 0
    };
  }).bind(this));

  $(document.body).on('touchmove', (function(evt) {
    var x = evt.originalEvent.targetTouches[0].pageX;
    var y = evt.originalEvent.targetTouches[0].pageY;
    window.scrollBy(this.touchInfo.lastX - x, this.touchInfo.lastY - y);
    if (this.touchInfo) {
      this.touchInfo.lastX = x
      this.touchInfo.lastY = y
      var force = evt.originalEvent.targetTouches[0].webkitForce || 0;
      if (force > this.touchInfo.maxForce)
        this.touchInfo.maxForce = force;
    }
    evt.preventDefault();
    evt.stopPropagation();
  }).bind(this));

  $(document.body).capture('touchend', (function(evt) {
    if (this.touchInfo) {
      var deltaX = evt.changedTouches[0].pageX - this.touchInfo.startX;
      var deltaY = this.touchInfo.startY - evt.changedTouches[0].pageY;
      app.showMessage(deltaX)
      var r = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
      if (r > $(window).width() / 4) {
        var theta = Math.atan2(deltaY, deltaX);
        var swipe = { 
          type: 'swipe',
          duration: new Date().getTime() - evt.startTime,
          startX: this.touchInfo.startX,
          startY: this.touchInfo.startY,
          endX: evt.changedTouches[0].pageX,
          endY: evt.changedTouches[0].pageY,
          r: r,
          theta: theta,
          maxForce: this.touchInfo.maxForce
        };
        $(document.body).trigger('swipe', swipe);
        evt.stopPropagation();
        evt.preventDefault();
        // temporary?
        (function() { 
          this.handleSwipe(swipeEvent);
        }).bind(MobileApp());
      }
    }
  }).bind(this));
}

MobileApp.prototype.handleSwipe= function(username, password, options) {


}

MobileApp.prototype.authenticate = function(username, password, options) {
  this.request({ 
      url: this.authURL, 
      data: { 'username': username, 'password':password }, 
      type: 'post',
      dataType: 'json'
  }).done(this.performAuth.bind(this, options)).error(
    (function(options) {
      // do somthing more elegant than 'alert'
      this.showMessage('There was an error during authentication');
      this.loadView(options.fail)
  }).bind(this, options));
}

MobileApp.prototype.isAuthenticated = function(options) {
  this.request({ 
      url: this.authURL, 
      type: 'get',
      dataType: 'json'
  }).done(this.performAuth.bind(this, options)).error(
        this.loadView.bind(this, options.fail));
}

MobileApp.prototype.performAuth = function(options, response) {
  if (response.userid) {
    this.userid = response.userid;
    this.loadView(options.success)
  } else {
    this.loadView(options.fail)
  }
}

MobileApp.prototype.logout = function() {
  if (this.deauthURL) {
    this.request({ 
        url: this.deauthURL, 
        dataType: 'json'
    }).done((function(response) {
        this.showMessage(response.message);
        setTimeout(this.loadView.bind(this, 'login'), 1000);
    }).bind(this));
  } else {
    this.showMessage("Logout is not enabled");
  }
}

MobileApp.prototype.initMenu = function(items) {
  this.menu = new MainMenu(items)
  this.actionBar.setAction('menu')
  return this;
}

MobileApp.prototype.initViews = function(views) {
  this.views = views;
  if (location.hash) {
    this.loadView(location.hash.substring(1));
  }
  return this;
}

MobileApp.prototype.createItemDetailView = function() {
  this.itemDetailView = new ItemDetailView(this.content, this.content);
  return this.itemDetailView;
}

MobileApp.prototype.setViewHTML = function(html) {
  this.content.html(html);
}

MobileApp.prototype.loadView = function(view) {
  if (!view) {
    return;
  }
  if (view.contains('/')) { // temporary
    return;
  }
  if (this.currentView) {
    this.unloadResources(this.currentView.resources.slice(0));
    if (this.currentView.unload) {
      this.currentView.unload();
    }
  }
  this.currentView = this.views[view];
  this.loadResources(
    // load any resources first
    ((this.currentView.resources) ? this.currentView.resources.slice(0).reverse() 
      : undefined), 
    // load the view html
    (function() {
      if (location.hash != '#'+view)
        location.hash = view;
      if (this.currentView.html) {
        this.content.load(this.currentView.html, (function(view, data) {
          if (view.title) {
            this.setTitle(view.title);
          }
          if (view.init) {
            view.init();
          }
        }.bind(this, this.currentView)));
      } else {
        this.content.empty();
        // run any initialization code after html is loaded
        if (this.currentView.init) {
          this.currentView.init();
        }
        this.setTitle(this.currentView.title);
      }
      if (this.currentView.actionBar) {
        this.actionBar.setAction(this.currentView.actionBar);
      }
    }).bind(this)
  );
  return this;
}

MobileApp.prototype.setTitle = function(text) {
  this.actionBar.setTitle(text);
}


MobileApp.prototype.loadResources = function(resources, complete) {
  if (!resources || !resources.length) {
    if (complete) {
      return complete();
    }
    return;
  }
  var resource = resources.pop();
  this.loadResource(resource, undefined,
      this.loadResources.bind(this, resources, complete));
}

MobileApp.prototype.unloadResources = function(resources, complete) {
  if (!resources || !resources.length) {
    if (complete) {
      return complete();
    }
    return;
  }
  var resource = resources.pop();
  this.unloadResource(resource, undefined,
      this.unloadResources.bind(this, resources, complete));
}

MobileApp.prototype.loadResource = function(resource, type, complete) {
  // autodetect the resource type
  if (type == 'css' || resource.substring(resource.length - 4) == '.css') {
    if (!$('link[href="'+resource+'"]').length) {
      $('head').append($('<link rel="stylesheet" type="text/css" href="'+
          resource+'">'));
    }
    if (complete)
      complete();
  }
  else if (type == 'js' || resource.substring(resource.length - 3) == '.js') {
      $.getScript(resource, complete);
  }
  else // unable to determine the type. abort.
    return;
}

MobileApp.prototype.unloadResource = function(resource, type, complete) {
  // autodetect the resource type
  if (type == 'css' || resource.substring(resource.length - 4) == '.css') {
    $('link[href="'+resource+'"]').detach();
    if (complete)
      complete();
  }
  else if (type == 'js' || resource.substring(resource.length - 3) == '.js') {
    // TODO: figure out how to disable or remove scripts.
  }
  else // unable to determine the type. abort.
    return;
}
  
  
// find a home for this
function roundCircles() {
  $('.circle').each(function() {
    $(this).css('height', $(this).css('width'));
  });
}

ActionBar = function(title) {
  if (ActionBar.instance) {
    return ActionBar.instance;
  }
  ActionBar.instance = this;
  this.container = $('#actionBar')
  if (!this.container.length) {
    this.container = $('<div id="actionBar"></div>');
    this.title = $('<span><span>')
    this.container.append(this.title);
    // wrapping svg images in a <div> and using it to size 
    // the image works around some svg issues in Android WebKit
//    this.icon = $('<img>')
    this.iconBox = $('<div id="actionBarIcon" />');
    this.iconBox.append(this.icon);
    this.container.prepend(this.iconBox);
    $(document.body).prepend(this.container);
  }
  if (title) {
    this.title.html(title);
  }
  return this;
}

ActionBar.prototype.setColor = function(color) {
  this.container.css('background-color', color);
}

ActionBar.prototype.setAction = function(type) {
  this.iconBox.unbind(MobileApp().options.clickEvent)
  if (type == 'menu') {
//    this.icon.attr('src', 'img/menuIcon.svg');
//    this.icon.show();
    this.iconBox.load('img/menuIcon.svg');
    this.iconBox.css('visibility', '');
    this.iconAction = (function( ) {
        this.show();
      }).bind(MobileApp().menu);
    // use the menu icon
  } else if (type == 'back') {
    // use the back icon
//    this.icon.attr('src', 'img/backIcon.svg');
//    this.icon.show();
    this.iconBox.load('img/backIcon.svg');
    this.iconBox.css('visibility', '');
    this.iconAction = function() {
      history.go(-1);
    };
  } else if (type == 'none') {
//    this.icon.hide();
    this.iconBox.css('visibility', 'hidden');
  }
  if (this.iconAction)
    this.iconBox.on(MobileApp().options.clickEvent, this.iconAction)
  return this;
}

ActionBar.prototype.hide = function() {
  this.container.hide();
}

ActionBar.prototype.show = function() {
  this.container.show();
}


ActionBar.prototype.setTitle = function(title) {
  this.title.html(title)
}

MainMenu = function(itemOptions) {
  this.container = $('<div id="menu"></div>').css({
    'position': 'fixed',
    'top': 0,
    'left': 0,
    'width': '300px',
    'height': '100%',
    'background-color': 'white',
    'z-index': 3,
    'font-size': '1em'
  });
  this.element = $('<ul></ul>');
  this.overlay = ($('<div id="mainMenuOverlay"></div>'));
  this.overlay.hide();
  this.overlay.on(MobileApp().options.clickEvent, (function() { 
    this.hide();
  }).bind(this));
  $(document.body).prepend(this.overlay);
  this.container.append(this.element);
  this.items = [];
  for (i in itemOptions) {
    this.add(itemOptions[i]);
  }
  $(document.body).prepend(this.container);
  // hide the menu
  this.container.css('left', -this.container.width());

  // open the menu when the user swipes.
  $(document.body).on('swipe', (function(evt) {
    if (evt.startX < $(window).width() / 10 
        && Math.abs(evt.theta) < Math.PI / 8) {
      this.show();
    }
  }).bind(this));

  return this;
}

MainMenu.prototype.add = function(options) {
  var newItem = new MenuItem(options); 
  this.items.push(newItem);
  this.element.append(newItem.element);
  this.enabled = true;
  return this;
}

MainMenu.prototype.remove = function(name) {
  return this;
}

MainMenu.prototype.trigger = function(menuItem) {
  menuItem.action();
  return this;
}

MainMenu.prototype.show = function() {
  if (this.enabled) {
    this.overlay.show();
    this.container.animate({'left': 0}, 100);
  }
}

MainMenu.prototype.hide = function() {
  this.overlay.hide();
  this.container.animate({'left': -this.container.width()}, 100);
}

MainMenu.prototype.setEnabled = function(enabled) {
  this.enabled = !!enabled;
  if (!enabled) {
    this.hide();
  }
}


MenuItem = function(options) {
  this.element = $("<li></li>");
  this.action = options.action;
  this.element.on(MobileApp().options.clickEvent, this.action);
  this.setName(options.name);
  this.setIcon(options.icon);
}

MenuItem.prototype.setName = function(name) {
  this.name = name
  this.update()
}

MenuItem.prototype.setIcon = function(icon) {
  this.icon = icon
  this.update()
}

MenuItem.prototype.update = function() {
  if (this.icon) {
    // workaround for broken svg support in Android webkit.
    if (this.icon.url.substring(this.icon.url.length-4) == '.svg') {
      this.element.html('<div class="icon"></div>' + this.name);
      if (this.icon.size) {
        this.element.find('div.icon').css({ 
          'width':  this.icon.size[0],
          'height': this.icon.size[1],
          'float': 'left',
          'margin-right': '0.5em'
        }).load(this.icon.url);
      }
    } else {
      this.element.html('<img src="' + this.icon.url + '">' + this.name);
      if (this.icon.size) {
        this.element.find('img').css({ 
          'width':  this.icon.size[0],
          'height': this.icon.size[1],
          'float': 'left',
          'margin-right': '0.5em'
        });
      }
    }
  } else {
    this.element.html(this.name);
  }
}


/*
Function: MobileApp.log
  Prints a message to console.log if it is available.

Arguments:
  String[s] to be printed to the logger

Returns:
  True if console.log is available, false otherwise

 */
MobileApp.prototype.log = function( ) {
  if ( window.console && console.log ) {
    for ( var i = 0; i < arguments.length; i++ ) {
      console.log( arguments[ i ] );
    }
    return true;
  }
  return false;
}

/**
 * Gets or sets a cookie.
 * 
 * @param key The key for the cookie to be set or retrieved.
 * @param value (optional) The new value for the cookie. If omitted, the
 *  current value of the cookie is returned. If null is passed, the cookie is
 *  deleted.
 * @return The current value of the cookie if value is omitted. Otherwise 
 *  returns the new complete cookie string.
 */
MobileApp.prototype.cookie=function(key, value){
  var f=encodeURIComponent(key);
  if(value === undefined){
    var result = new RegExp("(?:^|;)\\s*"+f+"=([^;]*)(?:;|$)").exec(document.cookie);
    if(result){
      return decodeURIComponent(result[1])
    }else{
      return undefined
    }
  }else{
    if(value === null){
      return document.cookie = key+"=; expires=Thu, 01 Jan 1970 00:00:00 GMT"
    } else {
      return document.cookie = key+"="+value
    }
  }
};

DynamicStyle = function( selector, rule ) {
  var style;
  if (DynamicStyle.instance) {
    style = DynamicStyle.instance
  } else if (this != window) {
    style = DynamicStyle.instance = this;
  } else {
    style = new DynamicStyle()
  }
  if (!style.element) {
    // add a new style element to the DOM.
    style.element = $("<style type='text/css' class='css_dynamic'></style>");
    $('head').append( this.element );
  }
  // if any new rules were passed in, add them.
  if (selector) {
    if (rule) {
      style.add(selector, rule);
    } else {
      style.addRaw(selector)
    }
  }
  return style;
}

DynamicStyle.prototype.add = function( selector, rule ) {
  this.element.text( this.element.text( ) + selector + "{" + rule + "}" );
}

DynamicStyle.prototype.addRaw = function( rules ) {
  this.element.text( this.element.text( )  + rules );
}

DynamicStyle.prototype.clear = function( ) {
  this.element.text('');
}

DynamicStyle.prototype.remove = function( selector, rule ) {
  this.element.text( this.element.text( ).replace( 
      selector + "{" + rule + "}", "" ));
}

ItemDetailView = function(listContainer, detailContainer) {
  this.container = $(listContainer);
  this.detailContainer = $(detailContainer);
  this.listElement = $('<ul class="itemDetailList"></ul>');
  this.detailElement = $('<div class="itemDetail"></div>');
  this.container.append(this.listElement);
  this.items = {}
  this.detailCallback = (function(html) {
    this.listElement.detach();
    this.detailElement.html(html); 
    this.container.append(this.detailElement);
  }).bind(this);
  return this;
}

ItemDetailView.prototype.createItem = function(itemId, itemName, detailFunc) {
  var item = new Item(
      itemId, itemName, detailFunc, this.detailCallback);
  this.items[itemId] = item;
  this.listElement.append(item.element);
  return item;
}

ItemDetailView.prototype.showDetail = function(itemId) {
  this.items[itemId].detail();
}

Item = function(id, name, detailFunc, detailCallback) {
  this.id = id;
  this.name = name;
  this.detail = detailFunc;
  this.detailCallback = detailCallback;
  this.element = $('<li>'+name+'</li>');
  this.element.on(MobileApp().options.clickEvent, (function() {
    return this.showDetail();
  }).bind(this));
}

Item.prototype.showDetail = function() {
  location.hash = location.hash + '/' + this.id
  ActionBar().setAction('back');
  this.detail(this.detailCallback);
}

// set up jQuery ajax to send cookies to other domains
$(function() {
  $.ajaxSetup({
      dataType: 'json',
      xhrFields: {
         withCredentials: true
      },
      crossDomain: true
  });
});

// A wrapper around jquery's ajax implementation which will manually
// handle session cookies so they don't time out.
AjaxRequest = function(settings) {
  if (this != window) {
    settings.Cookie = localStorage.getItem('session')
    settings.dataType = 'json';
    this.jqXHR = $.ajax(settings);
    return this;
  }
}

AjaxRequest.prototype.abort = function(statusText)  {
  this.jqXHR.abort(statusText);
  return this;
}

AjaxRequest.prototype.always = function(func) {
  this.jqXHR.always(function(response) {
    this.getSession();
    func.apply(arguments);
    func.apply(this, arguments);
  }.bind(this));
  return this;
}

AjaxRequest.prototype.complete = function(func) {
  this.jqXHR.complete(function(response) {
    this.getSession();
    func.apply(this, arguments);
  }.bind(this));
  return this;
}

AjaxRequest.prototype.done = function(func) {
  this.jqXHR.done(function(response) {
    this.getSession();
    func.apply(this, arguments);
  }.bind(this));
  return this;
}

AjaxRequest.prototype.error = function(func) {
  this.jqXHR.error(function(response) {
    this.getSession();
    func.apply(this, arguments);
  }.bind(this));
  return this;
}

AjaxRequest.prototype.fail = function(func) {
  this.jqXHR.fail(function(response) {
    this.getSession();
    func.apply(this, arguments);
  }.bind(this));
  return this;
}

AjaxRequest.prototype.getSession = function() {
  var match = this.jqXHR.getAllResponseHeaders().match(
      /(Set-Cookie|set-cookie): (.+?);/);
  if (match) {
    localStorage.setItem("session", match[2]);
    console.log("Session stored: " + match[2]);
    return match[2];
  }
}


/** ==== NATIVE CLASS EXTENSIONS ==== */

/** Gives a relative date and time */
Date.prototype.smartFormat = function() {
  var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday",
              "Saturday"];
  var months = ["January", "February", "March", "April", "May", "June", "July", 
                "August", "September", "October", "November", "December" ]
  var timestamp = this.getTime();
  var now = new Date();
  // 86400000 == one day
  var midnight = new Date(now.getYear()+1900, now.getMonth(), now.getDate(), 0, 0, 0, 0, 0)
  var dayString;
  if (timestamp > midnight) {
    dayString = "Today";
  } else if (timestamp > midnight - 86400000) {
    dayString = "Yesterday";
  } else if (timestamp > midnight - 86400000 * 6 ) {
    dayString = days[this.getDay()] + ",";
  } else {
    dayString = months[this.getMonth()] + " " + 
        this.getDate();
  }
  var minutes = this.getMinutes().pad(2);
  var ampm = this.getHours() < 12 ? "AM" : "PM";
//  ampm = jQuery.translate(ampm)
  var hour = this.getHours() % 12;
  if (hour === 0) {
    hour = 12;
  }
  var returnvalue = dayString + " ";
  if (this.getYear() != new Date().getYear())
    returnvalue = returnvalue += " " + this.getFullYear() + " ";
  returnvalue += hour +":"+ minutes +" "+ ampm;
  return returnvalue;
}

/**
 * Pads a number by prepending zeros to the beginning
 * 
 * @param digits The number of digits needed before the decimal point.
 * @return A string containing the padded number
 */
Number.prototype.pad = function(digits) {
  var zerosNeeded = digits - this.toFixed(0).length;
  var returnvalue = String(this);
  for (var i = zerosNeeded; i > 0; i--) {
    returnvalue = "0" + returnvalue;
  }
  return returnvalue;
}

String.prototype.contains = function(str) {
  return this.indexOf(str) >= 0
}

/*
 * Respond to an event in the 'capture' phase
 * This doesn't work in IE8 or less!
 */
jQuery.fn.capture = function(evt, handler) {
  // this works, but probably needs more error handling.
  this.each(function(i, ele) {
    if (ele.addEventListener) {
      ele.addEventListener(evt, handler, true);
    }
  });
}

