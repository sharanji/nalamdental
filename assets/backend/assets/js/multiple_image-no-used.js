var abc = 0;
$('#add_more').click(function ()
{
	$(this).before($("<div/>",{id: 'filediv'}).fadeIn('slow').append(
		$("<input/>",
		{
			name: 'files[]',
			type: 'file',
			id: 'file',
			class:'form-control'
		}),
		/* $("<img/>",
		{
			id: 'img',
			src: '../../uploads/delete.jpg', //the remove icon
			alt: 'delete',
			width: '15',
			height: '15',
			alt: 'delete'
		}) .click(function ()
		{
			$(this)
				.parent()
				.parent()
				.remove();
		}), */
		$("<br/><br/>")
	));
});

$('body').on('change', '#file', function ()
{
	if (this.files && this.files[0])
	{
		abc += 1; //increementing global variable by 1
		var z = abc - 1;
		var x = $(this).parent().find('#previewimg' + z).remove();
		$(this).before("<div id='abcd" + abc + "' class='abcd'><img width='50' height='50' id='previewimg" + abc + "' src=''/></div>");
		var reader = new FileReader();
		reader.onload = imageIsLoaded;
		reader.readAsDataURL(this.files[0]);
		$(this).hide();
		$("#abcd" + abc).append($("<img/>",{
			id: 'img',
			src: '../../uploads/delete.jpg', //the remove icon
			alt: 'delete',
			width: '15',
			height: '15',
			alt: 'delete'
		}) .click(function ()
		{
			$(this)
				.parent()
				.parent()
				.remove();
		}));
	}
});

//image preview
function imageIsLoaded(e)
{
	$('#previewimg' + abc).attr('src', e.target.result);
};