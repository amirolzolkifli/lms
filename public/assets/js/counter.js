function visible(partial) {
    var $t = partial,
        $w = jQuery(window),
        viewTop = $w.scrollTop(),
        viewBottom = viewTop + $w.height();

    // Check if element exists and has offset
    if (!$t.length || !$t.offset()) {
        return false;
    }

    var _top = $t.offset().top,
        _bottom = _top + $t.height(),
        compareTop = partial === true ? _bottom : _top,
        compareBottom = partial === true ? _top : _bottom;

    return ((compareBottom <= viewBottom) && (compareTop >= viewTop) && $t.is(':visible'));

}

$(window).scroll(function(){
  var $countDigit = $('.count-digit');

  // Only proceed if element exists
  if (!$countDigit.length) return;

  if(visible($countDigit))
    {
      if($countDigit.hasClass('counter-loaded')) return;
      $countDigit.addClass('counter-loaded');

$countDigit.each(function () {
  var $this = $(this);
  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
    duration: 3000,
    easing: 'swing',
    step: function () {
      $this.text(Math.ceil(this.Counter));
    }
  });
});
    }
})