$(document).ready(function () {
    Xblog.init();

    $(document).on('submit','.comment-form',function (e) {
        e.preventDefault();
    });

    $(document).on('keypress','.submit-input-enter',function (e) {
        if (e.keyCode == 13 || e.which == 13) {
            initComment($(this).closest("form"));
            return false;
        }
    });
    

    $(document).on('click','.comment-operation-reply',function (e) {
            var commentContent = $(this).parents('.comment-main-wrappper').find(".comment-content");
            var username=$(this).attr('data-username');

            var oldContent = commentContent.val();
            prefix = "@" + username + " ";
            var newContent = '';
            if (oldContent.length > 0) {
                newContent = oldContent + "\n" + prefix;
            } else {
                newContent = prefix
            }
            commentContent.focus();
            commentContent.val(newContent);
            moveEnd(commentContent);
    });

    function initComment(submittedform) {
        var form='';
        form = $(submittedform);
        var commentContent = form.find('.comment-content');

        if ($.trim(commentContent.val()) == '') {
            commentContent.focus();
            return false;
        }

        $.ajax({
            method: 'post',
            url: $(form).attr('action'),
            headers: {
                'X-CSRF-TOKEN': Laravel.csrfToken
            },
            data:form.serialize(),
        }).done(function (data) {
            if (data.status === 200) {
                commentContent.val('');
                loadComments(true, true,form);
            } else {
                toastr.warning(data.message);                
            }
        });
    }

     function loadComments(shouldMoveEnd, force,formElement) {    

        var container,comment_ajax_url,form='';
        form = formElement; 

        var comments_counter = form.parents('.comment-main-wrappper').find('.home-comment-counter');
        container = form.parents('.comment-main-wrappper').find('.comments-container');
        comment_ajax_url=container.attr('data-api-url');

        if (force || container.children().length <= 0) {
            $.ajax({
                method: 'get',
                url: comment_ajax_url,
            }).done(function (data) {
                container.append(data.html_result);
                comments_counter.html(data.comments_count);

                var splitData=comment_ajax_url.split("last_comment_id=");
                container.attr('data-api-url',splitData[0]+'last_comment_id='+data.last_comment);

                highLightCodeOfChild(container);
                if (shouldMoveEnd) {
                    moveEnd($('#comment-submit'));
                }
            });
        }
    }
    
});

var Xblog = {
    init: function () {
        this.bootUp();
    },
    bootUp: function () {
        console.log('bootUp');
        //loadComments(false, false);
        //initComment();
        initMarkdownTarget();
        initTables();
        autoSize();
        //initDeleteTarget();
        highLightCode();
        imageLiquid();
    },
};
window.Xblog = Xblog;

 function initMarkdownTarget() {
    $('.markdown-target').each(function (i, element) {
        element.innerHTML =
            marked($(element).data("markdown"), {
                renderer: new marked.Renderer(),
                gfm: true,
                tables: true,
                breaks: false,
                pedantic: false,
                smartLists: true,
                smartypants: false,
            });
    });
}


function highLightCode() {
        $('pre code').each(function (i, block) {
            hljs.highlightBlock(block);
        });
    }

    function highLightCodeOfChild(parent) {
        $('pre code', parent).each(function (i, block) {
            console.log(block);
            hljs.highlightBlock(block);
        });
    }

    function initTables() {
        $('table').addClass('table table-bordered table-responsive');
    }

    function autoSize() {
        autosize($('.autosize-target'));
    }

    function imageLiquid() {
        $(".js-imgLiquid").imgLiquid({
            fill: true,
            horizontalAlign: "center",
            verticalAlign: "top"
        });
    }

var moveEnd = function (obj) {
    obj.focus();
    var len = obj.value === undefined ? 0 : obj.value.length;

    if (document.selection) {
        var sel = obj.createTextRange();
        sel.moveStart('character', len);
        sel.collapse();
        sel.select();
    } else if (typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number') {
        obj.selectionStart = obj.selectionEnd = len;
    }
};
