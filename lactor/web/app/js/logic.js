
$(function() {
  app = new MobileApp("Lactor", { 'scale':400 });
  base_url = '../';
  //app.loadResource('js/lactor.js')
  app.initMenu([
    { 
      name: 'Profile',
      icon: { url: 'img/profile.png', size: ['1.3em','1.3em'] },
      action: function(){
        app.loadView('profile');
      }
    },
    {
      name: 'Inbox', 
      icon: { url: 'img/inbox.svg', size: ['1.3em','1.3em'] },
      action: function(){
        app.loadView('inbox');
      }
    },
    { 
      name: 'Add an Entry',
      icon: { url: 'img/clock.svg', size: ['1.3em','1.3em'] },
      action: function(){ app.loadView('add_entry'); }
    },
    { 
      name: 'Diary', 
      icon: { url: 'img/diary.svg', size: ['1.3em','1.3em'] },
      action: function(){ app.loadView('diary'); }
    },
//    { 
//      name: 'Resources', 
//      icon: { url: 'img/resources.svg', size: ['1.3em','1.3em'] },
//      action: function(){
//        app.loadView('resources');
//      }
//    },
    { 
      name: 'Notifications', 
      icon: { url: 'img/notifications.svg', size: ['1.3em','1.3em'] },
      action: function(){ app.loadView('notifications'); }
    },
    { 
      name: 'Logout', 
      icon: { url: 'img/logout.svg', size: ['1.3em','1.3em'] },
      action: function(){ 
        app.menu.hide();
        app.logout(); 
      }
    },
  
  ])
  app.initViews({
    login: {
      html: 'html/login.html',
      init: function() {
        app.actionBar.hide();
        app.isAuthenticated({'success':'add_entry'});
        $('button').on('click', function() {
          app.authenticate($('#login_user').val(), $('#login_password').val(),
          {'success':'add_entry', 'fail':'login'});
        });
      },
      unload: function() {
        app.actionBar.show();
      },
      resources: ['css/login.css'],
      actionBar: 'none',
      title: "Login"
    },

    inbox: {
      html: 'html/inbox.html',
      init: function() {
        app.menu.hide();
        app.actionBar.setColor('#593474');

        insertMessage = function(message) {
          var time = new Date(message.timestamp * 1000).smartFormat();
          var html = "<div class='inbox_message inbox_" + 
            (message.sent ? 'sent' : 'received') +
            "' data-messageId='"+ message.id +"' data-timestamp='" + 
            message.timestamp + "'>" + message.content + 
            '<div class="time">' + time + '</div>' +
            '<div class="tail"></div></div>';
          var current_messages = $('.inbox_message');
          if (!current_messages.length) {
              $('#inbox_bottom').before(html);
          } else {
            for (var i = current_messages.length-1; i >= 0; i--) {
              var current =  $(current_messages[i]);
              if (current.attr('data-timestamp') < message.timestamp) {
                current.after(html);
                break;
              }
              if (i == 0) {
                $(current).before(html);
              }
            }
          }
          var bottom = document.getElementById('inbox_bottom');
          if (bottom)
            bottom.scrollIntoView();
        }
        $('#inbox_content').html(
            '<div style="height:0; clear:both;" id="inbox_bottom"></div>');

        var getNewMessages = function() {
          var last_message = $('.inbox_message').last();
          if (last_message.length == 0)
            var since = 0;
          else
            var since = last_message.attr('data-timesatmp');
          app.request( {
            url: base_url + 'services/messages.php?since='+since, 
            dataType: 'json'
          }).done(function(data) {
            for (var i in data.messages) {
              if (!$('.inbox_message[data-messageid="'+data.messages[i].id+'"]').length)
                insertMessage(data.messages[i]);
            }
          });
        };
        getNewMessages();
        setInterval(getNewMessages, 5000);

        $('#inbox_compose_send').click(function() {
          app.request({
            url: base_url + 'services/send_message.php', 
            data: {
              message: $('#inbox_compose_text').val()
            },
            type: 'post',
            dataType: 'json',


          }).done( function(data) {
              insertMessage(data);
          });
        });
      },
      unload: function() {
        insertMessage = undefined;
      },
      resources: [ 'css/inbox.css' ],
      actionBar: 'menu',
      title: "Messages"
    },

    profile: {
      init: function() {
        app.menu.hide();
        app.actionBar.setColor('#3a74ad');
        app.request({
          url: base_url + 'services/mother_info.php',
          type: 'post',
          dataType: 'json'
        }).done(function(response) {
          response.profile_img = "img/profile.png";
          response.Age = lactor.profile["Age"][response.Age] || "Not specified";
          response.Race = lactor.profile["Race"][response.Race] || "Not specified";
          response.education = lactor.profile["Education"][response.Education] || "Not specified";
          response.HouseIncome = lactor.profile["HouseIncome"][response.HouseIncome] || "Not specified";
          response.Occupation = lactor.profile["Occupation"][response.Occupation] || "Not specified";
          response.residence = lactor.profile["Residence"][response.Residence] || "Not specified";
          response.parity = lactor.profile["Parity"][response.Parity] || "Not specified";
          response.MethodOfDelivery = lactor.profile["MethodOfDelivery"][response.MethodOfDelivery] || "Not specified";
          var mhdp = [];
          for (var i=0; i < response.MHDP.length; i++) {
            if (response.MHDP.charAt(i) == "2") {
                mhdp.push({ 'value':lactor.profile.MHDP[i] });
            }
          }
          response.MHDP = (mhdp.length) ? mhdp : [{'value':"None"}];
          response.PBE = lactor.profile["PBE"][response.PBE];
          app.mustache(response);

          
        }).error(function(response) {
          app.showMessage("There was an error when retreiving your profile "+
              "information. Please check your internet connection");
        });
      },
      resources: ['js/profile.js', 'css/profile.css'],
      html: 'html/profile.html',
      actionBar: 'menu',
      title: "Profile"
    },

    add_entry: {
      html: 'html/add_entry.html',
      init: function() {
         // fix for iOS browser
        if (app.browser.iOS) {
          if (app.browser.tablet) { //iPad
            $('#addentry_day_wrapper').css('top', -0.7);
          } else {
            $('#addentry_day_wrapper').css('top', -2);
          }
        }
        roundCircles();
        app.menu.hide();
        app.actionBar.setColor('#3a74ad');
        app.initPanels();
        var updateTimer = function(timer, startTime) {
          var side = 'left';
          if ($('#addentry_timerSelectRight').hasClass('selected'))
            side = 'right'
          var timerVal = new Date().getTime() - startTime + app.bftimers[side];
          var minutes = Number(Math.floor(timerVal / 60000).toFixed(0));
          var seconds = Number((Math.floor(timerVal / 1000) % 60).toFixed(0));
          var centiseconds = Number(((timerVal / 10) % 100).toFixed(0));
          //console.log(minutes + ' ' + seconds + ' ' + (timerVal/1000));
          $(timer).val(
            minutes.toFixed(0) + ':' + 
            seconds.pad(2) + '.' +
            centiseconds.pad(2)
          );
          return timerVal;
        }
        // a function to start the currently selected timer
        var startTimer = function() {
          if (app.bftimers.intervalId < 0) {
            app.bftimers[app.bftimers.selected+'Start'] = new Date().getTime(); 
            app.bftimers.intervalId = setInterval(function() {
              updateTimer('#'+app.bftimers.selected+' input',
                  app.bftimers[app.bftimers.selected+'Start']);
            }, 93)
          }
        };
        // function to stop the currenty running timer (if any)
        var stopTimer = function() {
          if (app.bftimers.intervalId >= 0) {
            clearInterval(app.bftimers.intervalId);
            app.bftimers.intervalId = -1;
            var timerVal = updateTimer('#'+app.bftimers.selected+' input',
                app.bftimers[app.bftimers.selected+'Start']);
            var side = 'left';
            if ($('#addentry_timerSelectRight').hasClass('selected'))
              side = 'right'
            app.bftimers[side] = timerVal;
          }
        };
        // function to reset both timers to zero
        var clearTimers = function() {
          if (app.bftimers && app.bftimers.intervalId >= 0)
            clearInterval(app.bftimers.intervalId);
          app.bftimers = { 
            'left': 0, 'right': 0, 'intervalId': -1,
            'selected': (app.bftimers ? app.bftimers.selected : 'addentry_timerLeft')
          };
          $('#addentry_timerLeft input').val('0:00.00');
          $('#addentry_timerRight input').val('0:00.00');
          $('#addentry_timerDisplays input').blur(function() {
            $(this).val($(this).val().replace(/[^0-9:.]+/g, ''))
            var splitresult = $(this).val().split(':');
            var minutes = Number(splitresult[0]);
            if (splitresult.length > 1)
              var seconds = Number(splitresult[1]); 
            else 
              var seconds = 0;
            if ($(this).parent().attr('id').contains('Left'))
              app.bftimers.left = minutes * 60000 + seconds * 1000;
            else
              app.bftimers.right = minutes * 60000 + seconds * 1000;
          });
        };
        // initialize the timers
        clearTimers();
        // initialize the time entry field
        var time = new Date();
        var timeinput = $('#addentry_time').val(
            time.getHours().pad(2) + ':' + time.getMinutes().pad(2));
        // initialize the button bar at the bottom
        $('#addentry_buttonBar #addentry_nav_breastfeeding').addClass('active').
        click(function() {
          app.showPanel(0);
          app.setTitle('Breastfeeding Entry')
          $('#addentry_buttonBar > div').removeClass('active');
          $(this).addClass('active');
        });
        $('#addentry_buttonBar #addentry_nav_pumping').click(function() {
          app.showPanel(1);
          app.setTitle('Pumping Entry')
          $('#addentry_buttonBar > div').removeClass('active');
          $(this).addClass('active');
        });
        $('#addentry_buttonBar #addentry_nav_supplement').click(function() {
          app.showPanel(2);
          app.setTitle('Supplement Entry')
          $('#addentry_buttonBar > div').removeClass('active');
          $(this).addClass('active');
        });
        $('#addentry_buttonBar #addentry_nav_output').click(function() {
          app.showPanel(3);
          app.setTitle('Output Entry')
          $('#addentry_buttonBar > div').removeClass('active');
          $(this).addClass('active');
        });
        $('#addentry_buttonBar #addentry_nav_weight').click(function() {
          app.showPanel(4);
          app.setTitle('Infant Weight Entry')
          $('#addentry_buttonBar > div').removeClass('active');
          $(this).addClass('active');
        });
        $('#addentry_buttonBar #addentry_nav_health').click(function() {
          app.showPanel(5);
          app.setTitle('Health Entry')
          $('#addentry_buttonBar > div').removeClass('active');
          $(this).addClass('active');
        });
        $('#addentry_timerSelectLeft').addClass('selected');
        // set up the timer selection button
        $('.addentry_timerSelect').on(app.options.clickEvent, function() {
          var running = (app.bftimers.intervalId >= 0);
          stopTimer();
          $('#addentry_timerSelectLeft').removeClass('selected');
          $('#addentry_timerSelectRight').removeClass('selected');
          app.bftimers.selected = this.id.replace('Select', '')
          $(this).addClass('selected');
          if (running)
            startTimer();
        });
        // set up the timer controls
        $('#clockStartButton').on(app.options.clickEvent, function() {
          startTimer();
        });
        $('#clockPauseButton').on(app.options.clickEvent, function() {
          stopTimer();
        });
        $('#clockClearButton').on(app.options.clickEvent, function() {
          clearTimers();
        });
        // fix the time entry field for devices that don't support input 
        // type 'time'. This test seems to work for 'time' support.
        if ($('<input type="time" value=";)">').val() == ';)') {
          var now = new Date();
          var html = '<select id="addentry_time">';
          for (var i=0; i<24; i++) {
            for (var j=0; j < 60; j+=5) {
              html += '<option value="' + i + ':' + j.pad(2) + '"';
              var thisTime = new Date(String(new Date()).
                  replace(/[0-9]+:[0-9]+:[0-9]+/, i+':'+j.pad(2)+':00')).getTime();
              if (Math.abs(thisTime - now) < 150000)
                html += ' selected ';
              html += '>';
              html += (i % 12 == 0) ? '12' : (i % 12);
              html +=  ':' + j.pad(2) + ' ' + ((i<12) ? 'AM' : 'PM');
              html += '</option>'
            }
          }
          html += '</select>';
          $('#addentry_time').parent().html(html);
        }
        // initialize the save button
       $('#addentry_saveButton').on(app.options.clickEvent, function() {
          var time = $('#addentry_time').val();
          if (!time) {
            app.showMessage('Please select a valid time for this entry', 5000);
            return;
          }
          time = time.split(':');
          var hours = Number(time[0]) % 12;
          var minutes = time[1];
          var ampm = (Number(time[0]) > 12) ? '00' : '01';
         switch(app.panels.current) {
           case 0:
           // this calculation is temporary until the db is updated
            var duration_left = $('[name="timerLeft"]').val().split(':')[0];
            var duration_right = $('[name="timerRight"]').val().split(':')[0];
            var submitted_duration_left = 0;
            var submitted_duration_right = 0;
            if (duration_left >= 1) submitted_duration_left++;
            if (duration_left >= 3) submitted_duration_left++;
            if (duration_left >= 5) submitted_duration_left++;
            if (duration_left >= 10) submitted_duration_left++;
            if (duration_left >= 15) submitted_duration_left++;
            if (duration_right >= 1) submitted_duration_right++;
            if (duration_right >= 3) submitted_duration_right++;
            if (duration_right >= 5) submitted_duration_right++;
            if (duration_right >= 10) submitted_duration_right++;
            if (duration_right >= 15) submitted_duration_right++;
            app.request({ 
                url: "https://lactor.org/services/add_entry.php",
                data: { 
                  'which': $('#addentry_day').val(),
                  'entryhour': hours,
                  'entryminute': minutes,
                  'entryam': ampm,
                  'breast': 'true',
                  'duration_left': submitted_duration_left,
                  'duration_right': submitted_duration_right,
                  'latching': $('#latchingButton').val(),
                  'infant_state': $('#alertnessButton').val(),
                  'maternal_problems': $('#breastfeedingProblemsButton').val()
                },
                type: 'post',
                dataType: 'json'
            }).done(function(data) {
              if (data.message || data.error)
                app.showMessage(data.message || data.error);
            });
            break;
          case 1:
            app.request({ 
                url: "https://lactor.org/services/add_entry.php",
                data: { 
                  'which': $('#addentry_day').val(),
                  'entryhour': hours,
                  'entryminute': minutes,
                  'entryam': ampm,
                  'pump': 'true',
                  'pumping_method': $('[name="pumping_method"]').val(),
                  'pumping_duration': $('[name="pumping_duration"]').val(),
                  'pumping_amount': $('[name="pumping_amount"]').val()
                },
                type: 'post',
                dataType: 'json'
            }).done(function(data) {
              if (data.message || data.error)
                app.showMessage(data.message || data.error);
            });
            break;
          case 2:
            app.request({ 
                url: "https://lactor.org/services/add_entry.php",
                data: { 
                  'which': $('#addentry_day').val(),
                  'entryhour': hours,
                  'entryminute': minutes,
                  'entryam': ampm,
                  'sup': 'true',
                  'sup_type': $('[name="sup_type"]').val(),
                  'sup_method': $('[name="sup_method"]').val(),
                  'NumberTimes': $('[name="NumberTimes"]').val(),
                  'TotalAmount': $('[name="TotalAmount"]').val()
                },
                type: 'post',
                dataType: 'json'
            }).done(function(data) {
              if (data.message || data.error)
                app.showMessage(data.message || data.error);
            });
            break;
          case 3:
            app.request({ 
                url: "https://lactor.org/services/add_entry.php",
                data: { 
                  'which': $('#addentry_day').val(),
                  'entryhour': hours,
                  'entryminute': minutes,
                  'entryam': ampm,
                  'out': 'true',
                  'NumberDiapers': $('[name="NumberDiapers"]').val(),
                  'out_u_color': $('[name="out_u_color"]').val(),
                  'out_u_saturation': $('[name="out_u_saturation"]').val(),
                  'out_s_color': $('[name="out_s_color"]').val(),
                  'out_s_consistency': $('[name="out_s_consistency"]').val()
                },
                type: 'post',
                dataType: 'json'
            }).done(function(data) {
              if (data.message || data.error)
                app.showMessage(data.message || data.error);
            });
            break;
          case 4:
            app.request({ 
                url: "https://lactor.org/services/add_entry.php",
                data: { 
                  'which': $('#addentry_day').val(),
                  'entryhour': hours,
                  'entryminute': minutes,
                  'entryam': ampm,
                  'weight': 'true',
                  'weight-lbs': $('[name="pounds"]').val(),
                  'weight-oz': $('[name="ounces"]').val()
                },
                type: 'post',
                dataType: 'json'
            }).done(function(data) {
              if (data.message || data.error)
                app.showMessage(data.message || data.error);
            });
            break;
          case 5:
            app.request({ 
                url: "https://lactor.org/services/add_entry.php",
                data: { 
                  'which': $('#addentry_day').val(),
                  'entryhour': hours,
                  'entryminute': minutes,
                  'entryam': ampm,
                  'morb': 'true',
                  'morb_type': $('[name="morb_type"]').val()
                },
                type: 'post',
                dataType: 'json'
            }).done(function(data) {
              if (data.message || data.error)
                app.showMessage(data.message || data.error);
            });
            break;
          default:
            break;
         }
       });
      },
      resources: [ 'css/add_entry.css' ],
      actionBar: 'menu',
      title: "Breastfeeding Entry"
    },

    diary: {
      html: 'html/diary.html',
      init: function() {
        roundCircles();
        app.menu.hide();
        app.initPanels();
        app.actionBar.setColor('#862447');
        // initialize the button bar at the bottom
        $('#diary_buttonBar #diary_nav_breastfeeding').addClass('active').
        click(function() {
          app.showPanel(0);
          app.setTitle('Breastfeeding Entries')
          $('#diary_buttonBar > div').removeClass('active');
          $(this).addClass('active');
        });
        $('#diary_buttonBar #diary_nav_pumping').click(function() {
          app.showPanel(1);
          app.setTitle('Pumping Entries')
          $('#diary_buttonBar > div').removeClass('active');
           graph('pumping');
         $(this).addClass('active');
        });
        $('#diary_buttonBar #diary_nav_supplement').click(function() {
          app.showPanel(2);
          app.setTitle('Supplement Entries')
          $('#diary_buttonBar > div').removeClass('active');
          $(this).addClass('active');
          graph('supplement');
        });
        $('#diary_buttonBar #diary_nav_output').click(function() {
          app.showPanel(3);
          app.setTitle('Output Entries')
          $('#diary_buttonBar > div').removeClass('active');
          $(this).addClass('active');
          graph('output');
        });
        $('#diary_buttonBar #diary_nav_weight').click(function() {
          app.showPanel(4);
          app.setTitle('Infant Weight Entries')
          $('#diary_buttonBar > div').removeClass('active');
          $(this).addClass('active');
          graph('weight');
        });
        $('#diary_buttonBar #diary_nav_health').click(function() {
          app.showPanel(5);
          app.setTitle('Health Entries')
          $('#diary_buttonBar > div').removeClass('active');
          $(this).addClass('active');
          graph('health');
        });
        $('#diary_timerSelectLeft').addClass('selected');
        // set up the timer selection button
        $('.diary_timerSelect').on(app.options.clickEvent, function() {
          var running = (app.bftimers.intervalId >= 0);
          stopTimer();
          $('#diary_timerSelectLeft').removeClass('selected');
          $('#diary_timerSelectRight').removeClass('selected');
          app.bftimers.selected = this.id.replace('Select', '')
          $(this).addClass('selected');
//          if (running)
//            startTimer();
        });
        var from = '-1%20year';
        var to = 'today';
        var service_url = base_url + 'services/diary.php'
        // BREASTFEEDING
        app.request({ 
          url: service_url + '?from=' + from + '&to='+to+'&type=breastfeeding',
          type: 'get', 
          dataType: 'json'
        }).done(function(response) {
          for (var i in response) {
            response[i].side = ' LRB'.charAt(response[i].side);
            response[i].duration = diaryConstants.breastfeeding.duration[response[i].duration];
            response[i].latching = diaryConstants.breastfeeding.latching[response[i].latching];
            response[i].infantState = diaryConstants.breastfeeding.state[response[i].infantState];
            response[i].date = new Date(response[i].entryTime * 1000).smartFormat()
          }
          app.mustache('#diary_breastfeeding', {'breastfeeding': response})
          graph('breastfeeding', true);
        });
        // PUMPING
        app.request({ 
          url: service_url + '?from='+from+ '&to='+to+'&type=pumping',
          type: 'get', 
          dataType: 'json'
        }).done(function(response) {
          for (var i in response) {
              response[i].duration = diaryConstants.pumping.duration[response[i].duration];
              response[i].method = diaryConstants.pumping.method[response[i].method];
              response[i].date = new Date(response[i].entryTime * 1000).smartFormat();
          }
          app.mustache('#diary_pumping', {'pumping': response});
        });
        // SUPPLEMENT
        app.request({ 
          url: service_url + '?from='+from+ '&to='+to+'&type=supplement',
          type: 'get', 
          dataType: 'json'
        }).done(function(response) {
          for (var i in response) {
            response[i].supType = diaryConstants.supplement.type[response[i].supType];
            response[i].supMethod = diaryConstants.supplement.method[response[i].supMethod];
            response[i].date = new Date(response[i].entryTime * 1000).smartFormat();
          }
          app.mustache('#diary_supplement', {'supplement': response});
        });
        // OUTPUT
        app.request({ 
          url: service_url + '?from='+from+ '&to='+to+'&type=output',
          type: 'get', 
          dataType: 'json'
        }).done(function(response) {
          var html = '<div id="diary_output_chart"></div>';
          for (var i in response) {
            response[i].date = new Date(response[i].entryTime * 1000).smartFormat();
            if (Number(response[i].urineColor)) {
                response[i].urine = true;
                response[i].urineColor = diaryConstants.output.urineColor[response[i].urineColor];
                response[i].urineSaturation = diaryConstants.output.urineSaturation[response[i].urineSaturation];
            } else {
                response[i].stool = true;
                response[i].stoolColor = diaryConstants.output.stoolColor[response[i].stoolColor];
                response[i].stoolConsistency = diaryConstants.output.stoolConsistency[response[i].stoolConsistency];
            }
          }
          app.mustache('#diary_output', {'output': response});
        });
        // WEIGHT
        app.request({ 
          url: service_url + '?from='+from+ '&to='+to+'&type=weight',
          type: 'get', 
          dataType: 'json'
        }).done(function(response) {
          for (var i in response) {
            var weight = response[i].weight;
            response[i].lbs = ((weight - weight % 16) / 16);
            response[i].ounces = (weight % 16);
            response[i].date = new Date(response[i].entryTime * 1000).smartFormat();
          }
          app.mustache('#diary_weight', {'weight': response});
        });
        // HEALTH
        app.request({ 
          url: service_url + '?from='+from+ '&to='+to+'&type=health',
          type: 'get', 
          dataType: 'json'
        }).done(function(response) {
          for (var i in response) {
            response[i].date = new Date(response[i].entryTime * 1000).smartFormat();
            response[i].type = diaryConstants.health.type[response[i].type];
          }
          app.mustache('#diary_health', {'health': response});
        });

      },
      resources: [ 'css/diary.css', 'js/diary.js', 
                   'js/jquery.jqplot.min.js', 'css/jquery.jqplot.min.css' ],
      actionBar: 'menu',
      title: "Breastfeeding Entries"
    },

    notifications: {
      init: function() {
        app.menu.hide();
        app.actionBar.setColor('#f08d0d');
        var view = app.createItemDetailView();
        app.request({
          url:base_url + 'services/notifications.php',
          type: 'get',
          dataType: 'json'
        }).done(function(response) {
          for (var i in response) {
            (function(item) {
              if (item.status == 1) { //unread
                view.createItem(item.id,
                      "<div class='notification-icon'><img src='img/notifications.svg' /></div>  <div class='notification-link'>" + lactor.notificationText[item.type-1].title + "<span class='notificationDate'>"+new Date(item.issued * 1000).smartFormat()+ "</span></div><div style='clear:both;'></div>",
                  function(callback) {
                    var text = lactor.notificationText[item.type - 1];
                    var html = '<div class="ndetail-wrapper"><span class="ndetail-problem">' + 'Problem: ' + text.title + "</span><br>" + '<span class="ndetail-date">' + new Date(item.issued * 1000).smartFormat() +  '<br>' +
                        '</span>';
                    html += '<ol class="ndetail-steps">';
                    for (var j in text.steps) {
                      html += '<li>'+ text.steps[j] +'</li>';
                    }
                    html += '</ol></div>';
                      html = '<div data-id="'+item.id+'">' + html + 
                        '<button type="button" data-id="' + item.id +
                        '" class="mark dismiss-button">' + 'Dismiss' +
                        '</b></button><br><br></div>';
                    callback(html);
                    $('button.mark').click(function() {
                      var msgId = $(this).attr('data-id');
                      app.request({
                        url: base_url + 'services/notifications.php?markRead=' + msgId,
                        dataType: 'json',
                        type: 'get'
                      }).done(function(response) {
                          if (response.error) {
                            app.showyMessage(response.error);
                          } else {
                            app.showMessage(response.message);
                            history.go(-1);
                          }
                      }).error(function() {
                        app.displayMessage("There was a problem connecting to "+
                         "Lactor. Please check your internet connection.", 5000);
                      });
                    });
                  }
                );
              }
            })(response[i]);
          }
        }).error(function() {
        });
      },
      resources: [ 'css/notifications.css', 'js/notifications.js' ],
      actionBar: 'menu',
      title: "Notifications"
//    },
//    resources: {
//      html: 'html/resources.html',
//      init: function() {
//        app.menu.hide();
//        app.actionBar.setColor('#278e66');
//        if (navigator.app) {
//          $('a.external').click(function(evt) {
//            navigator.app.loadUrl($(this).attr('href'), { openExternal:true });
//            return false;
//          });
//        }
//      },
//      actionBar: 'menu',
//      title: "Resources",
//      resources: ['css/resources.css'],
    }
  });
  app.requireAuth(base_url + 'services/auth.php', 
                  base_url + 'services/logout.php');
  var success = 'add_entry';
  if (location.hash)
    success = location.hash.substring(1);
  app.isAuthenticated({'success':success, 'fail':'login'});
});
