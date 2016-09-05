$(function() {

	$(document).on('ui-btnclick', '#slider-images', imagesBtnClick);

	function imagesBtnClick(e, id, item)
	{
		if (id == 'settings')
			settingsClick($(this), item);
	};

	function settingsClick($uploadimage, item)
	{
		var $modal = $('<div class="modal fade" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"></div><div class="modal-body"><form></form></div><div class="modal-footer"></div></div></div></div>'),
			$footer = $modal.find('modal-footer'),
			$title = $('<input type="text" class="form-control" id="imageTitle">').attr('placeholder', $uploadimage.data('textTitle')).val(item.data('title')),
			$description = $('<textarea class="form-control" id="imageDscription" rows="5" style="resize: none;" />').attr('placeholder', $uploadimage.data('textDescription')).val(item.data('description'));
			$url = $('<input type="text" class="form-control" id="imageUrl">').attr('placeholder', $uploadimage.data('textUrl')).val(item.data('url')),

		$modal.find('.modal-header').append(
			$('<div class="h4 modal-title" />').text($uploadimage.data('textModal'))
		);

		$modal.find('form').append(
			$('<div class="form-group" />').append($title),
			$('<div class="form-group" />').append($description),
			$('<div class="form-group" />').append($url)
		);

		$modal.find('.modal-footer').append(
			$('<button class="btn btn-primary" />').text($uploadimage.data('textSave')).click(function() {
				item.data({
					'title': $title.val(),
					'description': $description.val(),
					'url': $url.val()
				});
				$modal.modal('hide');
			}),
			$('<button class="btn btn-default" data-dismiss="modal" />').text($uploadimage.data('textCancel'))
		);

		$modal.on('hidden.bs.modal', function() {
			$modal.remove();
		});

		$modal.modal();
	};

});
