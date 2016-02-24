function Docs () {
    this.init();
    this.pjax();
};

/**
 * Initialize.
 */
Docs.prototype.init = function () {
    this.toc = $('.docs-toc');
    this.content = $('.docs-content');

    this.navbar();
    this.elements();
    this.anchorTags();
    this.activePage();
    this.docsearch();
};

Docs.prototype.docsearch = function () {
    if (window.currentDoc) {
        docsearch({
            apiKey: window.docApiKey,
            indexName: window.currentDoc,
            inputSelector: '#search'
        });
    }
};

/**
 * Initialize pjax.
 */
Docs.prototype.pjax = function () {
    var doc = $(document), _this = this;

    doc.pjax('a', '#pjax-container');

    doc.on('pjax:success', function () {
        _this.init();
        Prism.highlightAll();
    });
};

/**
 * Initialize elements: tables, images, iframes, callouts.
 */
Docs.prototype.elements = function () {
    // Tables.
    this.content.find('table').addClass('table table-striped table-bordered table-hover table-condensed');

    // Images.
    this.content.find('img').addClass('img-responsive img-thumbnail');

    // Iframes.
    this.content.find('iframe').each(function () {
        $(this).wrap('<div class="embed-responsive embed-responsive-16by9"></div>')
                .addClass('embed-responsive-item');
    });

    // Callouts.
    this.content.find('blockquote:contains(Attention)').addClass('callout callout-danger');
    this.content.find('blockquote:contains(Danger)').addClass('callout callout-danger');

    this.content.find('blockquote:contains(Warning)').addClass('callout callout-warning');
    this.content.find('blockquote:contains(Notice)').addClass('callout callout-warning');

    this.content.find('blockquote:contains(Info)').addClass('callout callout-info');
    this.content.find('blockquote:contains(Note)').addClass('callout callout-info');

    this.content.find('blockquote:contains(Hint)').addClass('callout callout-success');
    this.content.find('blockquote:contains(Tip)').addClass('callout callout-success');
};

/**
 * Set the active page.
 */
Docs.prototype.activePage = function () {
    this.toc.find('a[href$="/'+ this.toc.data('current-page') +'"]')
            .parent().addClass('active');
};

/**
 * Create anchor tags on header spans within documentation.
 */
Docs.prototype.anchorTags = function () {
    this.content.find('h2, h3, h4, h5, h6').each(function () {
        // Ignore header spans within blockquotes.
        if ($(this).parent().get(0).tagName !== 'BLOCKQUOTE') {
            var anchor = $(this).text().toLowerCase().trim(),
                hyphenNeedle = [/ /g],
                emptyNeedle  = [/\[/g, /\]/g, /\(/g, /\)/g, /\:/g];

            hyphenNeedle.forEach(function (word) {
                anchor = anchor.replace(word, "-");
            });

            emptyNeedle.forEach(function (word) {
                anchor = anchor.replace(word, '');
            });

            anchor = anchor.replace(/\./g, '');

            $(this).append(' <a class="header-anchor" id="' + anchor + '" href="#' + anchor + '"></a>');
        }
    });
}

/**
 * Clone toc to navbar.
 */
Docs.prototype.navbar = function () {
    var nav = $(this.toc.html());

    $('.docs-nav-toc').replaceWith(nav);

    nav.addClass('nav navbar-nav docs-nav-toc');

    nav.find('>li').each(function () {
        var li = $(this),
            header = li.find('p');

        if (header.length) {
            li.addClass('dropdown');
            li.find('ul').addClass('dropdown-menu');

            header.replaceWith(
                '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'+ header.html() +
                ' <span class="caret"></span></a>'
            );
        } else {
            li.find('ul>li').each(function () {
                li.after($(this));
            });

            li.remove();
        }
    });
};

new Docs();
