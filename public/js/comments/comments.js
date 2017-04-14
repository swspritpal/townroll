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

                initDeleteTarget();
                highLightCodeOfChild(container);
                if (shouldMoveEnd) {
                    moveEnd($('#comment-submit'));
                }
            });
        }
    }

    function initDeleteTarget() {
        $('.swal-dialog-target').append(function () {
            return "\n" +
                "<form action='" + $(this).attr('data-url') + "' method='post' style='display:none'>\n" +
                "   <input type='hidden' name='_method' value='" + ($(this).data('method') ? $(this).data('method') : 'delete') + "'>\n" +
                "   <input type='hidden' name='_token' value='" + Laravel.csrfToken + "'>\n" +
                "</form>\n"
        }).click(function () {
            var deleteForm = $(this).find("form");
            var method = ($(this).data('method') ? $(this).data('method') : 'delete');
            var url = $(this).attr('data-url');
            var data = $(this).data('request-data') ? $(this).data('request-data') : '';
            var title = $(this).data('dialog-title') ? $(this).data('dialog-title') : '删除';
            var message = $(this).data('dialog-msg');
            var type = $(this).data('dialog-type') ? $(this).data('dialog-type') : 'warning';
            var cancel_text = $(this).data('dialog-cancel-text') ? $(this).data('dialog-cancel-text') : '取消';
            var confirm_text = $(this).data('dialog-confirm-text') ? $(this).data('dialog-confirm-text') : '确定';
            var enable_html = $(this).data('dialog-enable-html') == '1';
            var enable_ajax = $(this).data('enable-ajax') == '1';
            console.log(data);
            if (enable_ajax) {
                swal({
                        title: title,
                        text: message,
                        type: type,
                        html: enable_html,
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: cancel_text,
                        confirmButtonText: confirm_text,
                        showLoaderOnConfirm: true,
                        closeOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': Laravel.csrfToken
                            },
                            url: url,
                            type: method,
                            data: data,
                            success: function (res) {
                                if (res.code == 200) {
                                    swal({
                                        title: 'Succeed',
                                        text: res.msg,
                                        type: "success",
                                        timer: 1000,
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    swal({
                                        title: 'Failed',
                                        text: "操作失败",
                                        type: "error",
                                        timer: 1000,
                                        confirmButtonText: "OK"
                                    });
                                }
                            },
                            error: function (res) {
                                swal({
                                    title: 'Failed',
                                    text: "操作失败",
                                    type: "error",
                                    timer: 1000,
                                    confirmButtonText: "OK"
                                });
                            }
                        })
                    });
            } else {
                swal({
                        title: title,
                        text: message,
                        type: type,
                        html: enable_html,
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: cancel_text,
                        confirmButtonText: confirm_text,
                        closeOnConfirm: true
                    },
                    function () {
                        deleteForm.submit();
                    });
            }
        });
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
