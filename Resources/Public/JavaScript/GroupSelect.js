define('TYPO3/CMS/Focuspoint/GroupSelect', ['jquery'], function ($, document) {
    $(document).ready(function () {

        $('a.group-focuspoint').click(function (e) {
            e.preventDefault();
            var selectItemClass = '.t3-form-field-item';
            if ($(selectItemClass).length === 0) {
                selectItemClass = '.t3js-formengine-field-item';
            }
            var selectBox = $(this).parentsUntil(selectItemClass).find('select');
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

            location.href = $(this).attr('href') + '&P[file]=' + selection;
        });
    });
});