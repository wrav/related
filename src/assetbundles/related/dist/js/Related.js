/**
 * Related plugin for Craft CMS
 *
 * Related JS
 *
 * @author    reganlawton
 * @copyright Copyright (c) 2019 reganlawton
 * @link      https://github.com/reganlawton
 * @package   Related
 * @since     1.0.0
 */
var id = $("input[name='entryId'],input[name='sourceId']").val();
var sectionId = $("input[name='sectionId']").val();

if (id != null) {
  $.ajax({
    type: "GET",
    url: "/actions/related/default?id=" + id + "&sectionId=" + sectionId,
    async: true
  }).done(function (res) {
    if (res) {
      $('#related-modal').remove();
      $('body').append(res.view);

      $modal = new Garnish.Modal($('#related-modal'), {
        autoShow: false,
        visible: false,
        resizable: true,
      });

      // Remove previous list in sidebar to fix bug in CraftCMS v3.4
      $('#related-widget').remove();

      // Add link to sidebar
      $("#settings").append(
        '<div id="related-widget" class="field" style="margin-top: 50px">' +
        '<div class="heading">' +
        '<label>Relations</label>' +
        '</div>' +
        '<div class="input ltr">' +
        '<a target="_blank" href="#">View (' + res.count + ')</a>' +
        '</div>' +
        '</div>'
      );

      $('#related-widget a').click(function(e) {
        e.preventDefault();
        $modal.show();

        $('#related-modal .footer .buttons .btn').click(function(e) {
          e.preventDefault();
          $modal.hide();
        });
      });
    }
  });
}

