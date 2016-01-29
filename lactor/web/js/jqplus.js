/*

License:
        MIT license.

        Copyright (c) 2009-2012 Thomas McGrew

        Permission is hereby granted, free of charge, to any person
        obtaining a copy of this software and associated documentation
        files (the "Software"), to deal in the Software without
        restriction, including without limitation the rights to use,
        copy, modify, merge, publish, distribute, sublicense, and/or sell
        copies of the Software, and to permit persons to whom the
        Software is furnished to do so, subject to the following
        conditions:

        The above copyright notice and this permission notice shall be
        included in all copies or substantial portions of the Software.

        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
        EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
        OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
        NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
        HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
        WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
        FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
        OTHER DEALINGS IN THE SOFTWARE.

*/

if (window.jQuery) { (function( $ ) {

  /*
  Function: $.GET
      Returns the query string parameter requested. Also known as a GET 
      variable, these parameters are passed from one page to another after the 
      '?' in the url.

  Arguments:
      varName - the name of the variable to request
      defaultValue - The value to be returned if the passed in variable is not 
          present in the query string.  This paramater is optional, and the 
          function will return null in it's stead.
      bubble - If true, this function will bubble up through the parent frames 
          looking for get values if they are not set on the current frame. 
          Defaults to true.

  Returns:
                  A string containing the value of the GET variable.
  */
  $.GET = function( varName, defaultValue, bubble ) {
          if ( arguments.length < 1 )
                  return;
          if (bubble == undefined)
                  bubble = true;

          var getValue = function( win ) {
                  var queryString = win.location.toString( ).split( '?' )[ 1 ];
                  var value = new RegExp( "(^|&)" + varName + "=([^&]*)(&|$)" ).exec( queryString );
                  if ( !value && bubble && win != win.parent )
                          return getValue( win.parent );
                  return value;
          }

          var value = getValue( window );
          if ( value )
                  return value[ 2 ]
          return defaultValue;
  }

  /*
  Function: $.benchmark
      Logs the time taken to execute a function and returns its return value.

  Arguments:
      func - The function to be executed.
      args - An array containing the arguments to be passed to the function. 
          Alternatively a single value may be passed.
      label - A label to be prepended to the log entry. Defaults to 'runtime'.

  Returns:
          The return value of the passed in function.
   */
  $.benchmark = function( func, args, label ) {
          if ( !label ) label = 'runtime';
          if ( !args ) args = [ ];
          var t = - ( new Date( ).getTime( ) )
          var returnvalue = func.apply( func, args );
          $.log( label + ': ' + (t + new Date( ).getTime( )) + 'ms' )
          return returnvalue;

  }

  /*
  Function: $.log
          Prints a message to console.log if it is available.

  Arguments:
          String[s] to be printed to the logger

  Returns:
          True if console.log is available, false otherwise

   */
  $.log = function( ) {
          if ( window.console && console.log ) {
                  for ( var i = 0; i < arguments.length; i++ ) {
                          console.log( arguments[ i ] );
                  }
                  return true;
          }
          return false;
  }

  /*
  Function: .option( option[, value ])
      Sets or gets the value of the option for a special element. This value
      should be set in the element's class name in the form class="option:value"

   Arguments:
      option - The option to retrieve the setting for.
      value - If specified, this sets the value of option instead of retrieving 
          it's value

   Returns:
      The option value as a string if present, defaultValue if not.
   */
  $.fn.option = function( option, value ) { 
    var optParser = new RegExp( option + ":([a-zA-Z0-9_.]*)" );
    if ( arguments.length >= 2 ) {
      if ( value ) {
        return this.addClass( option + ':' + value );
      } else {
        return this.removeClass( optParser.exec( this.attr('class'))[0]);
      }
    }
    value = optParser.exec(this.attr('class'))
    if ( value )
      return value[1];
    return null;
  }

  if ($.browser.msie && parseFloat($.browser.version) < 9) { //IE8
    $.styles = {
      init: function( ) { /* Nothing to do */ },
      add: function( selector, rule ) {
        $('head').append( 
          $("<style type='text/css' class='css_dynamic'>"+
           selector + "{" + rule + "}"+
           "</style>"));
      },
      remove: function( selector, rule ) {
        var rule = $.trim((selector + "{" + rule + "}"));
        $('style').each( function( ){
          var $this = $(this);
          if ( $.trim($this.html( )) == rule )
            $this.detach( )
        });
      }
    }
  } else {
    $.styles = {
      init: function( ) {
        // add this style element to the DOM.
        $('head').append( $.styles.element );
      },
      element: $("<style type='text/css' class='css_dynamic'></style>"),
      add: function( selector, rule ) {
        $.styles.element.text( 
          $.styles.element.text( ) + selector + "{" + rule + "}" );
      },
      remove: function( selector, rule ) {
        $.styles.element.text( 
          $.styles.element.text( ).replace( 
            selector + "{" + rule + "}", "" ));
      },
    }
  }


  /* 
  Automatically set all elements with the 'fullHeight' class to the same height 
  as their parent element.
  */
  $(function( event ) {
    var elements = $('.fullHeight');
    elements.each( function( ){ $(this).css('height', ''); });
    elements.each( function( ){ 
      var t = $(this);
      t.height( t.parent( ).height( ) - ( t.outerHeight( true ) - t.height( )));
    });
  });

  /*
  Automatically create tabs with elements containing the 'tabs' class.
  */
  if ( $.fn.tabs ) {
    $(function( ) {
      $( '.tabs' ).tabs( );
    });
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
  $.cookie=function(key, value){
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


  /**
   * Translates a text string or an entire page.
   * 
   * @param value (optional) The text string to be translated. If omitted, this
   *  function will translate all elements which contain the
   *  data-translate="true" attribute.
   * @return The translated string if value is passed in, otherwise nothing.
   */
  $.translate = function(value){
    if(value === undefined){
      $('[data-translate="true"]').each(function(){
        var $this = $(this);
        $this.html($.translate($this.html()));
        $this.attr("data-translate","false");
      })
    } else {
      if($.translate.strings){
        var translation = $.translate.strings[value];
        if(translation){
          return translation;
        }
      }
      return value;
    }
  };

  /**
   * Loads a set of translation strings from the given url.
   * 
   * @param url (optional) The url to load the translation strings from. If
   *  omitted, strings are loaded from '/translations/strings.json'
   */
  $.translate.load = function(url){
    if (url === undefined) 
      url = '/translations/strings.json';
    $.get(url,function(data){
      $.translate.strings = data;
      $.translate();
    },"json")
  };

})(jQuery);}

/** Gives a relative date and time */
Date.prototype.smartFormat = function() {
  var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday",
              "Saturday"];
  var months = ["January", "February", "March", "April", "May", "June", "July", 
                "August", "September", "October", "November", "December" ]
  var timestamp = this.getTime();
  var midnight = new Date().getTime();
  // 86400000 == one day
  var midnight = midnight - (midnight % 86400000);
  var dayString;
  if (timestamp > midnight) {
    dayString = "Today";
  } else if (timestamp > midnight - 86400000) {
    dayString = "Yesterday";
  } else if (timestamp > midnight - 86400000 * 6 ) {
    dayString = jQuery.translate(days[this.getDay()]) + ",";
  } else {
    dayString = jQuery.translate(months[this.getMonth()]) + " " + 
        this.getDate();
  }
  var minutes = this.getMinutes();
  if (minutes < 10)
    minutes = "0" + minutes;
  var ampm = this.getHours() < 12 ? "AM" : "PM";
  ampm = jQuery.translate(ampm)
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

