//only page woo_bookeeping
(function ($) {
    const Minute = 60 * 1000
    setInterval(function() {
        updateProgress()
    }, Minute);
})(jQuery)