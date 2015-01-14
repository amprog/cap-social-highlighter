jQuery(function ($) {
    // find the coordinates, contents, and node type of the selection
    function getSel() {
        var sel = document.getSelection();
        return {
            type: sel.anchorNode.nodeType,
            message: document.createTextNode(sel.toString().trim()).textContent,
            coords: sel.getRangeAt(0).getClientRects()[0]
        };
    }

    // place a popup at the top left corner of the selected text
    function positioning(newPopup, coords) {
        var newTop = coords.top - newPopup[0].offsetHeight + $(window).scrollTop(),
        newLeft = coords.left - newPopup[0].offsetWidth + $(window).scrollLeft();
        newTop = newTop < 0 ? 0 : newTop;
        newLeft = newLeft < 0 ? 0 : newLeft;

        newPopup.css({
            position: "absolute",
            top: newTop,
            left: newLeft
        });
    }

    // report the text content to google analytics
    function analytics(category, text) {
        if (typeof (_gaq) !== "undefined" && text !== "") {
            _gaq.push(['_trackEvent', category, window.location.pathname, text]);
        }
    }

    // after highlighting some text, create the popup and position it (using the above functions)
    function highlighter(handle) {
        var popup = $("#tweettext"),
        selection = getSel();
        popup.remove();

        // only trigger on text content, and only when there is visibly selected text
        if (selection.type === 3 && selection.message.length !== 0) {
            var newPopup = $("<a></a>", {
                id: "tweettext",
                target: "_blank",
                href: "https://twitter.com/intent/tweet?text=" + selection.message + " {$short_url}" + handle,
            }).on("mouseup", function () {
                analytics("TweetTextHightlightClick", selection.message);
            }).appendTo("body");

            $(window).on("scroll", positioning(newPopup, getSel().coords));
            positioning(newPopup, selection.coords);
        }
    }

    $.fn.tweettext = function (handle) {
        // sanitize the handle we're given--make sure it ends up with an @ symbol and no icky whitespace
        handle = handle.length !== 0 ? " via @" + handle.trim().replace("@", "") : "";

        $(this).each(function () {
            // span elements are used for predefined tweetable text.
            if (this.nodeName === "SPAN") {
                // make sure links inside the span still work and don't trigger the tweet when clicked
                $(this).children("a").on("mouseup", function (event) {
                    event.stopPropagation();
                });

                // on left click only, trigger the tweet
                $(this).on("mouseup", function (event) {
                    if (event.which === 1) {
                        analytics("TweetTextLinkClick", this.textContent);
                        window.open("https://twitter.com/intent/tweet?text=" + escape(this.textContent.trim()) + " url " + handle, "_blank");
                    }
                });
            } else {
                // any user selected text will get a bird icon
                $(this).on("mouseup", function () {
                    highlighter(handle);
                    analytics("TweetTextHighlight", getSel().message);
                });
            }

        });
    };
});
