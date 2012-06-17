/* Some Global data variables that are used throughout */
var friendNames = null;
var themes = Array('IIT Mess', 'IIT Politics', 'IIT Academics', 'IIT Hostels', 'IIT Cultural Events', 'IIT Sports Events', 'Nation & Economy');
var friendIds = null;
var names = Array();
var uids = Array();
/*****************************************/
function split( val ) {
  return val.split( /,\s*/ );
}
function extractLast( term ) {
  return split( term ).pop();
}
/**
  * Defines a debate on a user clicking start a new debate. This function
  * is the base function. The parameters which are requested:
  * Debate topic, Debate description, Debate themes, Context links/urls, Friends
  * who are challenged for or against this debate, the debate deadline
  */
function defineDebate() {
  var winH = $(document).height();
  var winW = $(document).width();
  var id = '#start-debate-form';
  $('#mask').css({'width':winW,'height':winH});
  $('#mask').fadeTo("fast",0.3);
  $(id).css('top',  winH/2-$(id).height()/2);
  $(id).css('left', winW/2-$(id).width()/2);
  $(id).show();
  $( "#debate-theme" ).autocomplete({
    minLength: 3,
    source: function( request, response ) {
	    // delegate back to autocomplete, but extract the last term
	    response( $.ui.autocomplete.filter(
		    themes, extractLast( request.term ) ) );
    },
    focus: function() {
	    // prevent value inserted on focus
	    return false;
    },
    select: function( event, ui ) {
	    var terms = split( this.value );
	    // remove the current input
	    terms.pop();
	    // add the selected item
	    terms.push( ui.item.value );
	    // add placeholder to get the comma-and-space at the end
	    terms.push( "" );
	    this.value = terms.join( ", " );
	    return false;
    },
    maxResults: 4
  });
  /* Here we query the user's fb friends for whom we also get the userids.
     We send the fb friends's userids. Incase a particular fb friend doesn't 
     exist in our db, we need to somehow intimate that person */
  if (friendNames == null) {
    $.ajax({
      url: 'get-my-friends.php',
      success: function(data) {
        var result = JSON.parse(data);
        var names = Array();
        var ids = Array();
        for (var i = 0; i < result.data.length; i++) {
          names.push(result.data[i].name);
          ids.push(result.data[i].id);
        }
        $( "#participants").autocomplete({
				  minLength: 3,
				  source: function( request, response ) {
					  // delegate back to autocomplete, but extract the last term
					  response( $.ui.autocomplete.filter(
						  names, extractLast( request.term ) ) );
				  },
				  focus: function() {
					  // prevent value inserted on focus
					  return false;
				  },
				  select: function( event, ui ) {
					  var terms = split( this.value );
					  // remove the current input
					  terms.pop();
					  // add the selected item
					  terms.push( ui.item.value );
					  // add placeholder to get the comma-and-space at the end
					  terms.push( "" );
					  this.value = terms.join( ", " );
					  return false;
				  },
				  maxResults: 4
			  });
        friendNames = names;
        friendIds = ids;
      }
    });
    // enable the participants file
    $('#participants').show();
    $('#cancel-debate').show();
  }
  else {
    $( "#participants" ).autocomplete({
	    minLength: 3,
	    source: function( request, response ) {
		    // delegate back to autocomplete, but extract the last term
		    response( $.ui.autocomplete.filter(
			    friendNames, extractLast( request.term ) ) );
	    },
	    focus: function() {
		    // prevent value inserted on focus
		    return false;
	    },
	    select: function( event, ui ) {
		    var terms = split( this.value );
		    // remove the current input
		    terms.pop();
		    // add the selected item
		    terms.push( ui.item.value );
		    // add placeholder to get the comma-and-space at the end
		    terms.push( "" );
		    this.value = terms.join( ", " );
		    return false;
	    },
	    maxResults: 4
    });
  }
}
/* submit the form entered so far */
function submitDebateForm() {
  /*get all the friend names entered, find in the array and convert to their fb ids*/
  var participants = $('#participants').val().split(',');
  var new_participants = '';
  var indexes = '';
  for (var i = 0; i < participants.length - 1; i++) {
    var s = participants[i];
    var searchFor = s[0] == ' ' ? s.substr(1) : s;
    var index = $.inArray(searchFor, friendNames);
    if (index != -1) {
      indexes += friendIds[index] + ',';
      new_participants += s + ',';
    }
  }
  $('#participants').val(new_participants);
  $('#participant-ids').val(indexes);
  $('.window').fadeOut();
  $('form.well').submit();
}
/* subset of defineDebate for a TARGETTED debate*/
function defineChallengeDebate() {
  defineDebate();
  $('#participants').val($('tr td.name').html() + ',');
}
/* Follow this user, toggling the state/css, to unfollow and follow */
function followUser () {
  if ($(this).attr('class') == 'btn btn-primary') {
    $(this).removeClass('btn-primary');
    $(this).attr('class', 'btn btn-danger');
    $(this).html('Unfollow');
    /* send follow AJAX request */
    $.ajax({
      url: 'follow.php',
      type: 'POST',
      data: {follower: user, followee: uid, follow: 1}
    });
  }
  else {
    $(this).removeClass('btn-danger');
    $(this).attr('class', 'btn btn-primary');
    $(this).html('Follow');
    $.ajax({
      url: 'follow.php',
      type: 'POST',
      data: {follower: user, followee: uid, follow: 0}
    });
  }
}
function clearDebateForm() {
  $('.window').hide();
  $('#mask').hide();
  $('#debate-topic').val('');
  $('#debate-desc').val('');
  $('#debate-theme').val('');
  $('#participants').val('');
}
/* modify the my debating interests using tag-it helper js */
function reShowAddButton(interests) {
  $('.interest-confirm').tooltip('hide');
  $('.interest-reject').tooltip('hide');
  $('.interest-confirm').unbind();
  $('.interest-reject').unbind();
  $('.interest-elements-p').html('<span class="add">+</span>');
  $('.interest-elements').html(interests);
  $('.interest-elements').effect("highlight", {}, 3000);
  $('span.add').click(modifyInterests);
  $('.add').tooltip({
    title: 'Modify/Add Debating interests'
  });
}
function modifyInterests() {
  var interests = $('.interest-elements').html();
  $(this).html('');
  $('.interest-elements').html('');
  $('.add').tooltip('hide');
  $('.add').unbind();
  $(this).append('<span title="Confirm" class="interest-confirm icon-ok" style="margin:4px 0 0 4px;padding:0 4px 0 4px;"></span>');
  $(this).append('<span title="Reject" class="interest-reject icon-remove" style="margin:4px 0;padding:0 4px 0 4px;"></span>');
  $('.interest-elements').prepend('<input type="text" style="margin:0;">');
  $('.interest-elements input').val(interests);
  $('.interest-confirm').tooltip({
    title: 'Accept'
  });
  $('.interest-reject').tooltip({
    title: 'Cancel'
  });
  $('.interest-confirm').click(function() {
    // take the text and enter in the db, also show the text
    var interests = $('.interest-elements input').val();
    $.ajax({
      url: 'change-interests.php',
      type: 'POST',
      data: {uid: userid, interests: interests}
    });
    reShowAddButton(interests);
  });
  $('.interest-reject').click(function() {
    reShowAddButton(interests);
  });
}
/* delete Debate will take the debate and remove myself from the participant list */
function debateDelete() {
  var debid = $(this).parent().parent().children('td.dname').attr('id');
  $.ajax({
    url: 'remove-debate.php',
    type: 'POST',
    data: {debid:debid, user:user}
  });
  $(this).parent().parent().fadeOut();
}
/* clear Overlay */
function clearOverlay() {
  $('.window').hide();
  $('#mask').hide();
}
/* show my followers */
function myFollowers() {
  var pnames = followerNames.split(',');
  var pids = followerIds.split(',');
  var code = '<p style="padding: 20px 20px 10px 20px;" class="emph">Followers</p><ul>';
  for (var i = 0; i < pnames.length; i++) {
    var s = pnames[i][0] == ' ' ? pnames[i].substr(1) : pnames[i];
    if (s == '')
      continue;
    var id = pids[i][0] == ' ' ? pids[i].substr(1) : pids[i];
    code += '<li id="' + id + '"><a target="_blank" href="https://www.facebook.com/profile.php?id=' + id + '"><img id="' + id + '" title="' + s + '" src="https://graph.facebook.com/' + id + '/picture"/></a></li>';
  }
  code += '</ul>';
  code += '<a href="#" id="cancel-overlay" class="close">&times;</a>';
  var id = '#overlay';
  $(id).html(code);
  if ($(id).height() < 200)
    $(id).css('height', '200px');
  if ($(id).width() < 200)
    $(id).css('width', '200px');
  $('li a img').each(function() {
    $(this).tooltip({
      title: $(this).attr('title')
    });
  });
  $('#cancel-overlay').click(clearOverlay);
  var winH = $(document).height();
  var winW = $(document).width();
  $('#mask').css({'width':winW,'height':winH});
  $('#mask').fadeTo("fast",0.3);
  $(id).css('top',  winH/2-$(id).height()/2);
  $(id).css('left', winW/2-$(id).width()/2);
  $(id).show();
}
function popovers() {
  $('.debate-table').popover({
    title: 'The Debate Table',
    content: 'View your performance on ongoing & past debates'
  });
  $('#interested-in').popover({
    title: 'Interested In',
    content: 'All the debating themes you are interested to debate in.'
  });
  $('#debating-points').popover({
    title: 'Debating Points',
    content: 'Debating Points accumulated over time by winning valuable debates. The points for a debate result from the popularity that the debate garners over time. When a debate gets over, the points it had get distributed among its participants. The more votes a comment got, the more points its author gets at the end.'
  });
  $('#debates-won').popover({
    title: 'Debates Won',
    content: 'Number of debates won over time.'
  });
  $('#modify-profile').popover({
    title: 'Modify Profile',
    content: 'Modify your profile to add debate themes and interests.',
    placement: 'bottom'
  });
  $('#start').popover({
    title: 'Start a new debate',
    content: 'Start a new debate by defining the topic giving description through relevant links & themes. Invite your friends to participate in the debate and set the time limit for the debate. Once the time limit expires, no participants will be able to add new comments.',
    placement: 'bottom'
  });
  $('#debate-topic').popover({
    content: 'Enter the debate topic'
  });
  $('#debate-desc').popover({
    content: 'Give some optional description to motivate the need to debate this topic and who all should care for the topic. Provide more context to the debate by providing external URLs giving it a defined direction.'
  });
  $('#debate-theme').popover({
    content: 'Enter one or more of the predefined categories that this debate falls under'
  });
  $('#participants').popover({
    content: 'Invite your friends who would be most interested to express their views on this topic'
  });
  $('#radio').popover({
    content: 'Set a time limit for this debate after which no participant will be able to make further comments.'
  });
  $('#radio2').popover({
    content: 'Any debater can participate & comment in a public debate. Private debates require invites from participants.'
  });
  $('#invite').popover({
    content: 'Invite this person to one my ongoing debates',
    placement: 'left'
  });
  $('#follow').popover({
    content: "Follow this person's debates and activity",
    placement: 'left'
  });
  $('#my-followers').popover({
    content: "View My Followers",
    placement: 'bottom'
  });
  $('#challenge').popover({
    content: "Challenge this person to a new debate",
    placement: 'left'
  });
  $('.add').tooltip({
    title: 'Modify/Add Debating interests'
  });
  $('#friend-search').tooltip({
    title: 'Search Debaters on IIT Debates',
    placement: 'left'
  });
  $('.delete-debate').tooltip({
    title: 'Remove myself as a participant',
    placement: 'left'
  });
}
/* Set up the user search functionality by querying through AJAX the user base */
function searchSetup() {
  $.ajax({
    url: 'get-users.php',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      for (var i = 0; i < data.length; i++) {
        names.push(data[i].name);
        uids.push(data[i].uid);
      }
      $('#friend-search').typeahead({
        source: names,
        items: 5
      });
    }
  });
  $('#friend-search').keypress(function(evt) {
    if (evt.which != 13)
      return true;
    else {
      var sname = $(this).val();
      var i = $.inArray(sname, names);
      if (i != -1)
        location.href = 'home.php?uid=' + uids[i];
    }
  });
  $('.icon-search').click(function() {
    var sname = $(this).parent().children('input').val();
    var i = $.inArray(sname, names);
    if (i != -1)
      location.href = 'home.php?uid=' + uids[i];
    else
      $(this).parent().children('input').val('');
  });
}
$(function() {
  clearDebateForm();
  $('#start').click(defineDebate);
  $(document).keyup(function(e) {
    if(e.keyCode == 27 && $('#mask').css('display')!='none') {
      clearDebateForm();
    }
  });
  $('#start-debate-form input').keyup(function() {
    if ($('#debate-topic').val().length > 5 && $('#participants').val().length > 3)
      $('#start-debate').removeAttr('disabled');
  });
  $('.debate-table td.dname').click(function() {
    if ($(this).attr('id') == 'nill')
      return;
    location.href = 'debate.php?debid=' + $(this).attr('id');
  });
  $('#start-debate').click(submitDebateForm);
  $('#cancel-debate').click(clearDebateForm);
  $('#challenge').click(defineChallengeDebate);
  $('#follow').click(followUser);
  $('#radio').buttonset();
  $('#radio2').buttonset();
  $('span.add').click(modifyInterests);
  $('.delete-debate').click(debateDelete);
  $('#my-followers').click(myFollowers);
  popovers();
  searchSetup();
});
