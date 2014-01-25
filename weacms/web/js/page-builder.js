function handleSaveLayout() {
    var e = $('#page-builder').html();
}

function handleJsIds() {
    handleModalIds();
    handleAccordionIds();
    handleCarouselIds();
    handleTabsIds()
}

function handleAccordionIds() {
    var e = $('#page-builder #myAccordion');
    var t = randomNumber();
    var n = 'panel-' + t;
    var r;
    e.attr('id', n);
    e.find('.panel').each(function (e, t) {
        r = 'panel-element-' + randomNumber();
        $(t).find('.panel-title').each(function (e, t) {
            $(t).attr('data-parent', '#' + n);
            $(t).attr('href', '#' + r)
        });
        $(t).find('.panel-collapse').each(function (e, t) {
            $(t).attr('id', r)
        })
    })
}

function handleCarouselIds() {
    var e = $('#page-builder #myCarousel');
    var t = randomNumber();
    var n = 'carousel-' + t;
    e.attr('id', n);
    e.find('.carousel-indicators li').each(function (e, t) {
        $(t).attr('data-target', '#' + n)
    });
    e.find('.left').attr('href', '#' + n);
    e.find('.right').attr('href', '#' + n)
}

function handleModalIds() {
    var e = $('#page-builder #myModalLink');
    var t = randomNumber();
    var n = 'modal-container-' + t;
    var r = 'modal-' + t;
    e.attr('id', r);
    e.attr('href', '#' + n);
    e.next().attr('id', n)
}

function handleTabsIds() {
    var e = $('#page-builder #myTabs');
    var t = randomNumber();
    var n = 'tabs-' + t;
    e.attr('id', n);
    e.find('.tab-pane').each(function (e, t) {
        var n = $(t).attr('id');
        var r = 'panel-' + randomNumber();
        $(t).attr('id', r);
        $(t).parent().parent().find('a[href=#' + n + ']').attr('href', '#' + r)
    })
}

function randomNumber() {
    return randomFromInterval(1, 1e6)
}

function randomFromInterval(e, t) {
    return Math.floor(Math.random() * (t - e + 1) + e)
}

function gridSystemGenerator() {
    $('.lyrow .preview input').bind('keyup', function () {
        var e = 0;
        var t = '';
        var n = false;
        var r = $(this).val().split(' ', 12);
        $.each(r, function (r, i) {
            if (!n) {
                if (parseInt(i) <= 0) n = true;
                e = e + parseInt(i);
                t += '<div class="span' + i + '"></div>';
            }
        });
        if (e == 12 && !n) {
            $(this).parent().next().children().html(t);
            $(this).parent().prev().show()
        } else {
            $(this).parent().prev().hide()
        }
    })
}

function configurationElm(e, t) {
    $('#page-builder').delegate('.configuration > a', 'click', function (e) {
        e.preventDefault();
        var t = $(this).parent().next().next().children();
        $(this).toggleClass('active');
        t.toggleClass($(this).attr('rel'))
    });
    $('#page-builder').delegate('.configuration .dropdown-menu a', 'click', function (e) {
        e.preventDefault();
        var t = $(this).parent().parent();
        var n = t.parent().parent().next().next().children();
        t.find('li').removeClass('active');
        $(this).parent().addClass('active');
        var r = '';
        t.find('a').each(function () {
            r += $(this).attr('rel') + ' '
        });
        t.parent().removeClass('open');
        n.removeClass(r);
        n.addClass($(this).attr('rel'))
    })
}

function removeElm() {
    $('#page-builder').delegate('.remove', 'click', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        if (!$('#page-builder .lyrow').length > 0) {
            clearDemo()
        }
    })
}

function clear() {
    $('#page-builder').empty()
}

function removeMenuClasses() {
    $('.btn-page-builder').removeClass('active')
}

function cleanHtml(e) {
    $(e).parent().append($(e).children().html())
}

function downloadLayoutSrc() {
    var e = '';
    $('#download-layout').children().html($('#page-builder').html());
    var t = $('#download-layout').children();
    t.find('.preview, .configuration, .drag, .remove').remove();
    t.find('.lyrow').addClass('removeClean');
    t.find('.box-element').addClass('removeClean');
    t.find('.lyrow .lyrow .lyrow .lyrow .lyrow .removeClean').each(function () {
        cleanHtml(this)
    });
    t.find('.lyrow .lyrow .lyrow .lyrow .removeClean').each(function () {
        cleanHtml(this)
    });
    t.find('.lyrow .lyrow .lyrow .removeClean').each(function () {
        cleanHtml(this)
    });
    t.find('.lyrow .lyrow .removeClean').each(function () {
        cleanHtml(this)
    });
    t.find('.lyrow .removeClean').each(function () {
        cleanHtml(this)
    });
    t.find('.removeClean').each(function () {
        cleanHtml(this)
    });
    t.find('.removeClean').remove();
    $('#download-layout .column').removeClass('ui-sortable');
    $('#download-layout .row-fluid').removeClass('clearfix').children().removeClass('column');
    if ($('#download-layout .container').length > 0) {
        changeStructure('row-fluid', 'row')
    }
    formatSrc = $.htmlClean($('#download-layout').html(), {
        format: true,
        allowedAttributes: [
            ['id'],
            ['class'],
            ['data-toggle'],
            ['data-target'],
            ['data-parent'],
            ['role'],
            ['data-dismiss'],
            ['aria-labelledby'],
            ['aria-hidden'],
            ['data-slide-to'],
            ['data-slide']
        ]
    });
    $('#download-layout').html(formatSrc);
    $('#downloadModal textarea').empty();
    $('#downloadModal textarea').val(formatSrc)
}
var currentDocument = null;
var timerSave = 2e3;
var demoHtml = $('#page-builder').html();

$(window).resize(function () {
    $('body').css('min-height', $(window).height() - 90);
    $('#page-builder').css('min-height', $(window).height() - 160)
});

$(document).ready(function () {
    $('body').css('min-height', $(window).height() - 90);
    
    // Get content ready for builder
    $('div[class*="span"]').addClass('column');

	CKEDITOR.disableAutoInline = true;
	
    editableBlocks = $('#page-builder').find('.row-fluid');
    for (var i = 0; i < editableBlocks.length; i++) {
        CKEDITOR.inline(editableBlocks[i]);
    }    
    
    $('#page-builder').css('min-height', $(window).height() - 160);
    $('#page-builder, #page-builder .column').sortable({
        connectWith: '.column',
        opacity: .35,
        handle: '.drag'
    });
    $('.sidebar-nav .lyrow').draggable({
        connectToSortable: '#page-builder',
        helper: 'clone',
        handle: '.drag',
        drag: function (e, t) {
            t.helper.width(400)
        },
        stop: function (e, t) {
            $('#page-builder .column').sortable({
                opacity: .35,
                connectWith: '.column'
            })
        }
    });
    $('.sidebar-nav .box').draggable({
        connectToSortable: '.column',
        helper: 'clone',
        handle: '.drag',
        drag: function (e, t) {
            t.helper.width(400)
        },
        stop: function (e, t) {
            handleJsIds();
            //CKEDITOR.inline(t.helper);
			t.helper.ckeditor();
        }
    });
    $('[data-target=#downloadModal]').click(function (e) {
        e.preventDefault();
        downloadLayoutSrc()
    });
    $('#download').click(function () {
        downloadLayout();
        return false
    });
    $('#downloadhtml').click(function () {
        downloadHtmlLayout();
        return false
    });
    $('#edit').click(function () {
        $('body').removeClass('preview');
        $('body').addClass('edit');
        removeMenuClasses();
        $(this).addClass('active');
        return false
    });
    $('#clear').click(function (e) {
        e.preventDefault();
        clear();
    });	
    $('#sourcepreview').click(function () {
        $('body').removeClass('edit');
        $('body').addClass('preview');
        removeMenuClasses();
        $(this).addClass('active');
        return false
    });
    $('.nav-header').click(function () {
        $('.sidebar-nav .boxes, .sidebar-nav .rows').hide();
        $(this).next().slideDown();
    });
    removeElm();
    configurationElm();
    gridSystemGenerator();
    setInterval(function () {
        handleSaveLayout()
    }, timerSave)
})