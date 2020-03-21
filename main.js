($ => {
  'use strict';

  const tabPenSelector = $('.rsh-tab.active')
    .find('.rsh-tab-link')
    .attr('href');

  slideTabPen( tabPenSelector );

  function slideTabPen( tabPenSelector ) {
    $('.rsh-tab-pane.active').removeClass('active');
    $(tabPenSelector).addClass('active');
  }

  $('.rsh-tab-link').click(e => {
    e.preventDefault();

    const $tabPenLink = $(e.currentTarget);
    
    $('.rsh-tab.active').removeClass('active');
    $tabPenLink.closest('.rsh-tab').addClass('active');

    slideTabPen( $tabPenLink.attr('href') );
    $('.rsh-alert').remove();
  });

  $(document).on('click', '.rsh-alert-btn', e => {
    e.preventDefault();
    $(e.currentTarget).closest('.rsh-alert').remove();
  });

})(jQuery);

