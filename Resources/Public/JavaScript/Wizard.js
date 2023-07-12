jQuery(document).ready(function () {
    jQuery('#set-src').trigger('blur');
    var saveFocusButton = jQuery('#saveFocus');
    var reticle = jQuery('.reticle');
    reticle.css('top', saveFocusButton.data('currentLeft'));
    reticle.css('left', saveFocusButton.data('currentTop'));
    setTimeout(function () {
        jQuery('img.helper-tool-img').trigger('click');
    }, 1000);

    saveFocusButton.click(function (e) {
        var valueString = jQuery('#data-attr').val();

        var Ausdruck = /-y="([-0-9\.]*)"/;
        Ausdruck.exec(valueString);
        var yValue = (RegExp.$1);

        Ausdruck = /-x="([-0-9\.]*)"/;
        Ausdruck.exec(valueString);
        var xValue = (RegExp.$1);

        jQuery(this).attr('href', jQuery(this).attr('href') + "&yValue=" + yValue + "&xValue=" + xValue);
        return true;
    });
});
