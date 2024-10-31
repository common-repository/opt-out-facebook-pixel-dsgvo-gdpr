jQuery(function ($) {
  var $wp_privacy_page = $('#fboo-wp-privacy-page');

  if (typeof $wp_privacy_page !== 'undefined' && $wp_privacy_page.length) {
    var $privacy_page = $('#fboo-privacy-page');
    var old_page_id = 0;

    if ($wp_privacy_page.is(':checked')) {
      $privacy_page.prop('disabled', 'disabled');
    }

    $wp_privacy_page.click(function () {
      if ($(this).is(':checked')) {
        var page_id = $wp_privacy_page.data('id');

        $privacy_page.prop('disabled', 'disabled');

        if (page_id) {
          old_page_id = $('option:selected', $privacy_page).val();
          $('option[value="' + page_id + '"]', $privacy_page).attr("selected", "selected");
        }
      }
      else {
        $privacy_page.prop('disabled', false);

        if (old_page_id) {
          $('option[value="' + old_page_id + '"]', $privacy_page).attr("selected", "selected");
        }
      }
    });
  }

  $('.fboo-empty-popup').click(function () {
    var $this = $(this);

    $this.prev('input[type="text"').val('');
    $this.hide();
  });

  $('.fboo-clipboard').click(function () {
    var $this = $(this);

    if (copyTextToClipboard($this.data('copy'))) {
      $this.text(fboo.text.copied);
    }
    else {
      $this.text(fboo.text.notcopied);
    }

    // Delayed text unset
    setTimeout(function () {
      $this.text('');
    }, 600);
  });

  $('code').click(function () {
    $('.fboo-clipboard').trigger('click');
  });
});

function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Fallback: Copying text command was ' + msg);
    } catch (err) {
      console.error('Fallback: Oops, unable to copy', err);
      return false;
    }

    document.body.removeChild(textArea);
    return true;
  }

  var $successfull = true;

  navigator.clipboard.writeText(text).then(function () {
    $successfull = true;
  }, function (err) {
    $successfull = false;
  });

  return $successfull;
}