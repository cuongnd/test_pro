EasySocial.module("site/repost/repost", function($){

	$(document)
		.on("click.es.repost.action", "[data-repost-action]", function(){

			var button = $(this),
				data = {
					id     : button.data('id'),
					element: button.data('element'),
					group  : button.data('group')
				},
				key = data.element + '-' + data.group + '-' + data.id;

			EasySocial.dialog({
				content: EasySocial.ajax("site/views/repost/form", data),
				bindings:
				{
					"{sendButton} click": function(sendButton)
					{
						var dialog = this.parent,
							content = $.trim(this.repostContent().val());

						// Add data content
						data.content = content;

						dialog.loading( true );

						EasySocial.ajax("site/controllers/repost/share", data )
							.done(function(content, isHidden, count, streamHTML)
							{
								var content = $.buildHTML(content);

								actionContent = 
									$('[data-repost-' + key + ']')
										.toggleClass("hide", isHidden)
										.toggle(!isHidden);

								actionContent.find("span.repost-counter")
									.html(content);

								button.trigger("create", [streamHTML]);
							})
							.fail(function(message)
							{
								dialog.clearMessage();
								dialog.setMessage( message );
							})
							.always(function()
							{
								dialog.loading( false );
								dialog.close();
							});
					}
				}
			});
		});

	EasySocial.module("repost/authors", function(){

		this.resolve(function(popbox){

			var repost = popbox.button.parents("[data-repost-content]")
				data = {
					id     : repost.data("id"),
					element: repost.data("element")
				};

			return {
				content: EasySocial.ajax('site/controllers/repost/getSharers', data),
				id: "es-wrap",
				type: "repost",
				position: "bottom-right"
			}
		});
	});

	this.resolve();
});
