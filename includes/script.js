var friendNames = null;
var friendIds = null;
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
  var indexes = '';
  for (var i = 0; i < participants.length - 1; i++) {
    var s = participants[i];
    var searchFor = s[0] == ' ' ? s.substr(1) : s;
    var index = $.inArray(searchFor, friendNames);
    indexes += friendIds[index] + ', ';
  }
  $('#participant-ids2').val(indexes);
  $('.window').fadeOut();
  $('form.well').submit();
}
/* subset of defineDebate for a TARGETTED debate*/
function defineChallengeDebate() {
  defineDebate();
  $('#participants').val($('tr td.name').html());
}
/* Follow this user, toggling the state/css, to unfollow and follow */
function followUser () {
  if ($(this).attr('class') == 'btn btn-primary') {
    $(this).removeClass('btn-primary');
    $(this).attr('class', 'btn btn-danger');
    $(this).html('Unfollow');
  }
  else {
    $(this).removeClass('btn-danger');
    $(this).attr('class', 'btn btn-primary');
    $(this).html('Follow');
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
function modifyInterests() {
  var interests = $('.interest-elements').html().split(',');
  $('.interest-elements').html('');
  $(this).html('');
  $(this).append('<span title="Reject" class="interest-confirm icon-ok" style="padding:0 4px 0 4px;"></span>');
  $(this).append('<span title="Confirm" class="interest-reject icon-remove" style="padding:0 4px 0 4px;"></span>');
  $('.interest-elements').html('<input type="text" style="margin:0;">');
  $('.interest-elements input').val(interests[0]);
}
$(function() {
  clearDebateForm();
  $('#start').click(defineDebate);
  $('#mask').click(function () {
    clearDebateForm();
	});
  $(document).keyup(function(e) {
    if(e.keyCode == 27 && $('#mask').css('display')!='none') {
      clearDebateForm();
    }
  });
  $('#start-debate').click(submitDebateForm);
  $('#cancel-debate').click(clearDebateForm);
  $('#challenge').click(defineChallengeDebate);
  $('#follow').click(followUser);
  $('.add').click(modifyInterests);
  $('#radio').buttonset();
});
