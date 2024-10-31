(function () {
  tinymce.PluginManager.add('fboptout', function (editor, url) {
    editor.addButton('fboptout', {
      title: 'Facebook Pixel Opt-Out',
      icon: 'fboptout',
      onclick: function () {
        editor.insertContent('[fb_optout]');
      }
    });
  });
})();