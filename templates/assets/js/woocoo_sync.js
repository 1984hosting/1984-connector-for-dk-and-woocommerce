//only page woo_bookeeping
(function ($) {
    const Minute = 30 * 1000
    setInterval(function() {
        updateProgress()
    }, Minute);
})(jQuery)