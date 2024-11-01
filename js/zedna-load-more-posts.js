jQuery(document).ready(function ($) {

  //Posts parent element ID
  var container_id = lmp_var.posts_parent_container;
  //Pagination element ID
  var pagination_id = lmp_var.pagination_container;
  //Article element
  var article_contianer = lmp_var.article_contianer;
  //Load on scroll (boolean)
  var load_on_scroll = lmp_var.load_on_scroll;

  //Translations
  var text_button_default = lmp_var.lang_text_button_default;
  var text_button_loading = lmp_var.lang_text_button_loading;
  var text_button_nopost = lmp_var.lang_text_button_nopost;

  // The number of the next page to load (/page/x/).
  var pageNum = parseInt(lmp_var.startPage) + 1;

  // The maximum number of pages the current query can return.
  var max = parseInt(lmp_var.maxPages);

  // The link of the next page of posts.
  var nextLink = lmp_var.nextLink;

  /**
   * Replace the traditional navigation with our own,
   * but only if there is at least one page of new posts to load.
   */
  if (pageNum <= max) {
    // Insert the "More Posts" link.
    $(container_id)
      .append('<div class="lmp-var-placeholder-' + pageNum + '"></div>')
      .append('<p id="load-more-posts"><a href="#">' + text_button_default + '</a></p>');

    // Remove the traditional navigation.
    $(pagination_id).remove();
  }


  /**
   * Load new posts when the link is clicked.
   */
  $('#load-more-posts a').click(function () {

    // Are there more posts to load?
    if (pageNum <= max) {

      // Show that we're working.
      $(this).text(text_button_loading);

      $('.lmp-var-placeholder-' + pageNum).load(nextLink + ' ' + article_contianer,
        function () {
          // Update page number and nextLink.
          pageNum++;
          nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/' + pageNum);

          // Add a new placeholder, for when user clicks again.
          $('#load-more-posts')
            .before('<div class="lmp-var-placeholder-' + pageNum + '"></div>')

          // Update the button message.
          if (pageNum <= max) {
            $('#load-more-posts a').text(text_button_default);
          } else {
            $('#load-more-posts a').text(text_button_nopost);
          }
        }
      );
      // console.log('pageNum: '+pageNum);
      // console.log('nextLink: '+nextLink);
    } else {
      $('#load-more-posts a').append('.');
    }

    return false;
  });

  $(window).on('scroll', function () {
    if (load_on_scroll) {
      if (jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {

        window.setTimeout(function () {
          // Are there more posts to load?
          if (pageNum <= max) {

            // Show that we're working.
            $(this).text(text_button_loading);

            $('.lmp-var-placeholder-' + pageNum).load(nextLink + ' ' + article_contianer,
              function () {
                // Update page number and nextLink.
                pageNum++;
                nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/' + pageNum);

                // Add a new placeholder, for when user clicks again.
                $('#load-more-posts')
                  .before('<div class="lmp-var-placeholder-' + pageNum + '"></div>')

                // Update the button message.
                if (pageNum <= max) {
                  $('#load-more-posts a').text(text_button_default);
                } else {
                  $('#load-more-posts a').text(text_button_nopost);
                }
              }
            );
            // console.log('pageNum: '+pageNum);
            // console.log('nextLink: '+nextLink);
          } else {
            $('#load-more-posts a').append('.');
          }

          return false;
        }, 500);
      }
    }
  });
});
