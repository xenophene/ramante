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
    data: {comid: comid, userid: userid, upvote: 1}
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
    data: {comid: comid, userid: userid, upvote: 0}
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
  // send an ajax request to db for this comment
  var request = $.ajax({
    url: 'post-comment.php',
    type: 'POST',
    data: {author: userid, value: yes_comment, debid: debid, foragainst: 1}
  });
  /* show the just entered comment */
  var comment = '<div id="comment">';
  comment += '<span class="author"><img class="author-pic" src="https://graph.facebook.com/'+userid+'/picture?type=small"/>' + username + '</span>';
  comment += '<br/><span class="comment-data">' + yes_comment + '</span><br/>';
  comment += '</div>';
  $('#yes #comments').prepend(comment);
  $('#yes #comment').first().hide();
  $('#yes #comment').first().slideDown({duration:'slow',easing: 'easeOutElastic'});
  $("#yes #comment").first().effect("highlight", {}, 3000);
}
function post_no() {
  var no_comment = $('#comment-no').val();
  if (no_comment == '')
    return;
  $('#comment-no').val('');
  $('#post-no').attr('disabled', 'disabled');
  $('#comment-no').css('height', '36px');
  // send an ajax request to db for this comment
  var request = $.ajax({
    url: 'post-comment.php',
    type: 'POST',
    data: {author: userid, value: no_comment, debid: debid, foragainst: 0}
  });
  /* show the just entered comment */
  var comment = '<div id="comment">';
  comment += '<span class="author"><img class="author-pic" src="https://graph.facebook.com/'+userid+'/picture?type=small"/>' + username + '</span>';
  comment += '<br/><span class="comment-data">' + no_comment + '</span><br/>';
  comment += '</div>';
  $('#no #comments').prepend(comment);
  $('#no #comment').first().hide();
  $('#no #comment').first().slideDown({duration:'slow',easing: 'easeOutElastic'});
  $("#no #comment").first().effect("highlight", {}, 3000);
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
  console.log(pids);
  console.log(pnames);
  var code = '<ul>';
  for (var i = 0; i < pnames.length; i++) {
    var s = pnames[i][0] == ' ' ? pnames[i].substr(1) : pnames[i];
    var id = pids[i][0] == ' ' ? pids[i].substr(1) : pids[i];
    code += '<li><img src="https://graph.facebook.com/"' + id + '/picture?type=small" style="padding-right:10px;"/>' + s + '</li>';
  }
  code += '</ul>';
  var id = '#overlay';
  $(id).html(code);
  var winH = $(document).height();
  var winW = $(document).width();
  $('#mask').css({'width':winW,'height':winH});
  $('#mask').fadeTo("fast",0.3);
  $(id).css('top',  winH/2-$(id).height()/2);
  $(id).css('left', winW/2-$(id).width()/2);
  $(id).show();
}
$(function() {
  $('textarea').autogrow();
  $('.upvote').click(upvote);
  $('.downvote').click(downvote);
  $('.support-point').click(support_point);
  $('.rebutt-point').click(rebutt_point);
  $('.delete-point').click(deletePoint);
  $('#post-yes').click(post_yes);
  $('#post-no').click(post_no);
  $('#view-participants').click(view_participants);
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
});
