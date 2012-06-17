var names = Array();
var uids = Array();
/* upvote listens to an upvote request on a comment, so as to increase the
   vote count of the selected comment on the debate in question from the 
   current user, visible only if the user has not already logged in  */
function upvote() {
  var votes = $(this).parent().find('.votes').html();
  votes = parseInt(votes.split(' ')[0]) + 1;
  $(this).parent().find('.votes').first().html(votes + ' votes');
  $(this).hide();
  $(this).parent().find('.downvote').hide();
  /* send the information that this user has upvoted this comment */
  var comid = $(this).parent().attr('name');
  var request = $.ajax({
    url: 'post-vote.php',
    type: 'POST',
    data: {comid: comid, userid: user, upvote: 1}
  });
}
function downvote() {
  var votes = $(this).parent().find('.votes').html();
  votes = parseInt(votes.split(' ')[0]) - 1;
  $(this).parent().find('.votes').first().html(votes + ' votes');
  $(this).hide();
  $(this).parent().find('.upvote').hide();
  /* send the information that this user has upvoted this comment */
  var comid = $(this).parent().attr('name');
  $.ajax({
    url: 'post-vote.php',
    type: 'POST',
    data: {comid: comid, userid: user, upvote: 0}
  });
}
/* delete the comment that was added by me */
function deletePoint() {
  // send a delete query and remove this from view
  var comid = $(this).parent().attr('name');
  $(this).parent().slideUp({duration:'slow',easing: 'easeOutElastic'});
  var request = $.ajax({
    url: 'remove-comment.php',
    type: 'POST',
    data: {comid: comid}
  });
}
/* will look at the support point box, and if somethere will add it to the db
   and render it on the screen at the top even if there are higher votes above */
function post_yes() {
  var yes_comment = $('#comment-yes').val();
  if (yes_comment == '')
    return;
  $('#comment-yes').val('');
  $('#post-yes').attr('disabled', 'disabled');
  $('#comment-yes').css('height', '36px');
  /* show the just entered comment */
  var comment = '<div id="comment">';
  comment += '<span class="author"><img class="author-pic" src="https://graph.facebook.com/'+user+'/picture?type=small"/>' + myname + '</span>';
  comment += '<br/><span class="comment-data">' + yes_comment + '</span><br/>';
  comment += '<span class="delete-point votes" title="Delete this point">Delete</span>';
  comment += '</div>';
  $('#yes #comments').prepend(comment);
  // send an ajax request to db for this comment
  var request = $.ajax({
    url: 'post-comment.php',
    type: 'POST',
    data: {author: user, value: yes_comment, debid: debid, foragainst: 1},
    success: function(data) {
      $('#yes #comment').first().attr('name', data);
    }
  });
  $('#yes #comment').first().hide();
  $('#yes #comment').first().slideDown({duration:'slow',easing: 'easeOutElastic'});
  $("#yes #comment").first().effect("highlight", {}, 3000);
  $('.delete-point').click(deletePoint);
}
function post_no() {
  var no_comment = $('#comment-no').val();
  if (no_comment == '')
    return;
  $('#comment-no').val('');
  $('#post-no').attr('disabled', 'disabled');
  $('#comment-no').css('height', '36px');
  /* show the just entered comment */
  var comment = '<div id="comment">';
  comment += '<span class="author"><img class="author-pic" src="https://graph.facebook.com/'+user+'/picture?type=small"/>' + myname + '</span>';
  comment += '<br/><span class="comment-data">' + no_comment + '</span><br/>';
  comment += '<span class="delete-point votes" title="Delete this point">Delete</span>';
  comment += '</div>';
  $('#no #comments').prepend(comment);
  // send an ajax request to db for this comment
  var request = $.ajax({
    url: 'post-comment.php',
    type: 'POST',
    data: {author: user, value: no_comment, debid: debid, foragainst: 0},
    success: function(data) {
      $('#no #comment').first().attr('name', data);
    }
  });
  $('#no #comment').first().hide();
  $('#no #comment').first().slideDown({duration:'slow',easing: 'easeOutElastic'});
  $("#no #comment").first().effect("highlight", {}, 3000);
  $('.delete-point').click(deletePoint);
}
/* support_point operates on a particular point and helps to directly counter
  or support whatever the point was talking about. this helps in a more one-on-one
  interaction which can be more fruitful for the user */
function support_point() {
  $(this).parent().append('<textarea class="yes" id="support-comment" placeholder="Support this point" rows=2></textarea>');
}
function rebutt_point() {
  $(this).parent().append('<textarea class="no" id="rebutt-comment" placeholder="Rebutt this point" rows=2></textarea>');
}
function clearOverlay() {
  $('.window').hide();
  $('#mask').hide();
  $('#support-comment').hide();
  $('#rebutt-comment').hide();
}
/* view participants for this debate */
function view_participants() {
  /* render the layover and show the list of friends */
  var pnames = participantNames.split(',');
  var pids = participantIds.split(',');
  var code = '<p style="padding: 20px 20px 10px 20px;" class="emph">Participants</p><ul>';
  for (var i = 0; i < pnames.length; i++) {
    var s = pnames[i][0] == ' ' ? pnames[i].substr(1) : pnames[i];
    var id = pids[i][0] == ' ' ? pids[i].substr(1) : pids[i];
    if (i != pnames.length - 1)
      code += '<li id="' + id + '"><a target="_blank" href="https://www.facebook.com/profile.php?id=' + id + '"><img id="' + id + '" title="' + s + '" src="https://graph.facebook.com/' + id + '/picture"/></a></li>';
    else
      code += '<li id="' + id + '"><a target="_blank" href="https://www.facebook.com/profile.php?id=' + id + '"><img id="' + id + '" title="' + s + ' (Creator)" src="https://graph.facebook.com/' + id + '/picture"/></a></li>';
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

/* view followers for this debate */
function view_followers() {
  /* render the layover and show the list of friends */
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
  $('#invite-to-debate').popover({
    title: 'Invite friends to debate',
    content: 'Promote this debate among your friends allowing them to participate and contribute to the debate.',
    placement: 'left'
  });
  $('#follow-debate').popover({
    title: 'Follow Debate',
    content: 'Follow this debate to stay updates with who said what on this debate.',
    placement: 'left'
  });
  $('#view-participants').popover({
    title: 'View Participants',
    content: 'View the profiles of the people who have been invited to this debate.',
    placement: 'left'
  });
  $('#view-followers').popover({
    title: 'View Followers',
    content: 'View the followers of this debate',
    placement: 'left'
  });
}
/* follow debate */
function followDebate() {
  if ($(this).attr('class') == 'btn btn-primary engage-btn') {
    $(this).removeClass('btn-primary');
    $(this).addClass('btn-danger');
    $(this).addClass('disabled');
    $(this).html('Following');
    /* send follow AJAX request */
    $.ajax({
      url: 'follow-debate.php',
      type: 'POST',
      data: {follower: user, debid: debid}
    });
  }
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
  $('textarea').autosize();
  $('.upvote').click(upvote);
  $('.downvote').click(downvote);
  $('.support-point').click(support_point);
  $('.rebutt-point').click(rebutt_point);
  $('.delete-point').click(deletePoint);
  $('#post-yes').click(post_yes);
  $('#post-no').click(post_no);
  $('#view-participants').click(view_participants);
  $('#view-followers').click(view_followers);
  $('#follow-debate').click(followDebate);
  $('#mask').click(function () {
    clearOverlay();
	});
  $(document).keyup(function(e) {
    if(e.keyCode == 27 && $('#mask').css('display')!='none') {
      clearOverlay();
    }
  });
  $('textarea').keyup(function() {
    if ($(this).val().length > 0) {
      if ($(this).attr('class') == 'yes')
        $('#post-yes').removeAttr('disabled');
      else
        $('#post-no').removeAttr('disabled');
    }
    else {
      if ($(this).attr('class') == 'yes')
        $('#post-yes').attr('disabled', 'disabled');
      else
        $('#post-no').attr('disabled', 'disabled');
    }
  });
  popovers();
  searchSetup();
});
