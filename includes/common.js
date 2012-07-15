// global variables useful across files
var names = Array();
var ids = Array();
var friendIds = null;
var friendNames = null;
var uids = Array();
var themes = Array('IIT Mess', 'IIT Politics', 'IIT Academics', 'IIT Hostels', 'IIT Cultural Events', 'IIT Sports Events', 'Nation & Economy');
/**
  * Library of common auxiliary functions that will be used by all the main
  * js files on the respective pages
  */
function split( val ) {
  return val.split( /,\s*/ );
}
function extractLast( term ) {
  return split( term ).pop();
}
// add the border to the taller of the left-right divs
function add_divider() {
  var h1 = $('#content .leftcol').css('height').split('px')[0];
  var h2 = $('#content .rightcol').css('height').split('px')[0];
  if (parseInt(h1) >= parseInt(h2)) {
    $('#content .leftcol').css('border-right', '1px solid #eee');
  } else {
    $('#content .rightcol').css('border-left', '1px solid #eee');
  }
}
function renderOverlay(id, heading, code) {
  $(id + ' .modal-header h1').html(heading);
  if (code == '<ul></ul>')
    $(id + ' .modal-body').html('<p>No users in this activity</p>');
  else
    $(id + ' .modal-body').html(code);
  $(id + ' li a img').each(function () {
    $(this).tooltip();
  });
  $(id).modal('show');
}
$(function () {
  add_divider();  
});
