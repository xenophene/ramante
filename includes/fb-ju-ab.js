function showhash(hash) {
  $('#cnt-btns a[href="#' + hash + '"]').tab('show');
}
$(function () {
  $('#fb').click(function () {
    $('#cnt-btns a[href="#feedback"]').tab('show');
  });
  $('#ju').click(function () {
    $('#cnt-btns a[href="#join-us"]').tab('show');
  });
  $('#ab').click(function () {
    $('#cnt-btns a[href="#about"]').tab('show');
  });
  if(window.location.hash) {
      var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
      showhash(hash);
  } else {
  }
});
