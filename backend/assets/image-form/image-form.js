$(function() {

	$(document).on('ui-imgload', '#slider-image', function(e) {
		var $uploadimage = $(this),
			$form = $uploadimage.closest('form'),
			thumb = $uploadimage.find('.uploadimage-item:not(:last-child)').last().find('input[name*="thumb"]').val();

		$.get($uploadimage.data('urlColor'), {'thumb': thumb}, function(data) {
			$form.find('#sliderimageform-background').val(data);
		}, 'json');
	});

});
