/**
 * Process "time ago" labels
 */
(function($) {
    $('time-ago').each(function() {
        var $this = $(this);

        $this.text(moment($this.attr('datetime')).fromNow());
    });
}(jQuery));
