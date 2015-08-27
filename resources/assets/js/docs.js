$(document).ready(function() {
    var toc  = $('.docs-toc'),
        docs = $('.docs-content');

    // Clone toc.
    var nav = $(toc.html());
    $('.docs-nav-toc').replaceWith(nav);
    nav.addClass('nav navbar-nav docs-nav-toc');

    nav.find('>li').each(function() {
        var li = $(this), p = li.find('p');

        if (p.length) {
            li.addClass('dropdown');
            li.find('ul').addClass('dropdown-menu');
            p.replaceWith('<a href="#" class="dropdown-toggle" data-toggle="dropdown">'+p.html()+' <span class="caret"></span></a>');
        } else {
            li.find('ul>li').each(function() {
                li.after($(this));
            });
            li.remove();
        }
    });

    // Bootstrap the tables.
    docs.find('table').addClass('table table-striped table-bordered table-hover table-condensed');

    // Bootstrap the images.
    docs.find('img').addClass('img-responsive img-thumbnail');

    // Bootstrap the iframes.
    docs.find('iframe').each(function() {
        $(this).wrap('<div class="embed-responsive embed-responsive-16by9"></div>')
                .addClass('embed-responsive-item');
    });

    // Dynamic callouts.
    docs.find('blockquote:contains(Attention)').addClass('callout callout-danger');
    docs.find('blockquote:contains(Danger)').addClass('callout callout-danger');

    docs.find('blockquote:contains(Warning)').addClass('callout callout-warning');
    docs.find('blockquote:contains(Notice)').addClass('callout callout-warning');

    docs.find('blockquote:contains(Info)').addClass('callout callout-info');
    docs.find('blockquote:contains(Note)').addClass('callout callout-info');

    docs.find('blockquote:contains(Hint)').addClass('callout callout-success');
    docs.find('blockquote:contains(Tip)').addClass('callout callout-success');

    // Set active page.
    toc.find('a[href$="/'+toc.data('current-page')+'"]').parent().addClass('active');

    // Change h1 to h4 for search results.
    $('#search-results h1').replaceWith(function() {
        return '<h4>' + $(this).text() + '</h4>';
    });

    // Create anchor tags on header spans within documentation.
    docs.find('h2, h3, h4, h5, h6').each(function() {
        // We want to ignore header spans within blockquotes
        if ($(this).parent().get(0).tagName !== 'BLOCKQUOTE') {
            var anchor = $(this).text().toLowerCase().trim(),
                hyphenNeedle = [/ /g],
                emptyNeedle = [/\[/g, /\]/g, /\(/g, /\)/g, /\:/g];

            hyphenNeedle.forEach(function(word) {
                anchor = anchor.replace(word, "-");
            });

            emptyNeedle.forEach(function(word) {
                anchor = anchor.replace(word, '');
            });

            anchor = anchor.replace(/\./g, '');

            $(this).append(" <a class=\"header-anchor\" id=\"" + anchor + "\" href=\"#" + anchor + "\"></a>");
        }
    });
});
