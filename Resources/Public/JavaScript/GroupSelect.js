define('TYPO3/CMS/Focuspoint/GroupSelect', ['jquery'], function ($) {
    $(document).ready(function () {

        $('a.group-focuspoint').click(function (e) {
            e.preventDefault();

            var selection = $(this).parentsUntil('.t3-form-field-item').find('select').val();
            if (selection === null || selection.length != 1) {
                alert('You have to selection one single image in the list of images!');
                return;
            }

            var image = selection.join();
            document.location.href = $(this).attr('href') + '&P[file]=' + image;
        });
    });
});