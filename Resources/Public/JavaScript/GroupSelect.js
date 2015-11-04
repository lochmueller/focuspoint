define('TYPO3/CMS/Focuspoint/GroupSelect', ['jquery'], function ($) {
    $(document).ready(function () {

        $('a.group-focuspoint').click(function (e) {
            e.preventDefault();
            var selectBox = $(this).parentsUntil('.t3-form-field-item').find('select');
            var selection = selectBox.val();
            if (selection === null) {
                alert('You have to selection one single image in the list of images!');
                return;
            }

            if (parseInt(selectBox.attr('size'), 10) > 1) {
                // multiselect
                if (selection.length > 1) {
                    alert('You have to selection one single image in the list of images!');
                    return;
                }
                selection = selection.join();
            }

            document.location.href = $(this).attr('href') + '&P[file]=' + selection;
        });
    });
});