EasySocial.module('site/likes/likes', function($){

	var module = this;

	$(document)
		.on("click.es.likes.action", "[data-likes-action]", function(){

			var button = $(this),
				data = {
					id   : button.data("id"),
					type : button.data("type"),
					group: button.data("group")
				},
				key = data.type + "-" + data.group + "-" + data.id;

			EasySocial.ajax("site/controllers/likes/toggle", data)
				.done(function(content, label, showOrHide, verb, count) {

					// Update like label
					button.text(label);

					// Update like content
					$("[data-likes-" + key + "]")
						.html(content)
						.toggleClass("hide", showOrHide)
						.toggle(!showOrHide);

					// Furnish data with like count
					data.uid   = data.id; // inconsistency
					data.count = count;

					// verb = like/unlike
					button.trigger((verb=="like") ? "onLiked" : "onUnliked", [data]);
				})
				.fail(function(message) {

					console.log(message);
				});
		})
		.on("click.es.likes.others", "[data-likes-others]", function(){

			var button = $(this),
				content = button.parents("[data-likes-content]"),
				data = {
					uid    : content.data("id"),
					type   : content.data("type"),
					exclude: button.data("authors")
				};

			EasySocial.dialog({
				content: EasySocial.ajax("site/controllers/likes/showOthers", data)
			});
		});

	module.resolve();
});
