'use strict';
$(function () {
    let _url = 'http://127.0.0.1:8000';
    $('document').ready(function () {
        commentPost(window.location.href.split('/').pop());
        modalUsers();
    });
    $('.comment-post-outside').on('click', function () {
        var idPost = $(this).data('id');
        $('.comment-outside-form-' + idPost).toggleClass('d-none');
    });
    $(".box-comments").niceScroll({
        cursorcolor: "black",
        cursorwidth: "5px",
        horizrailenabled: false
    });

    //posts full like
    $(function () {
        let _idLike = 0, _idPost = 0;
        $('.like-f-p').on('click', function () {
            _idPost = $(this).parent().data('id');
            _idLike = $(this).data('like');
            $.ajax({
                url: _url + '/post/likeFP',
                method: 'post',
                data: { _token: $('#csrf-token')[0].content, _like: _idLike, _post: _idPost },
                dataType: 'json',
                success: function (data) {
                    if (data.type == 1) {
                        if (jQuery.isArray(data.count)) {
                            $('.like-f-p[data-like=' + data.emot[0] + ']').children('p').text(data.count[0]);
                            $('.like-f-p[data-like=' + data.emot[1] + ']').children('p').text(data.count[1]);
                        } else {
                            $('.like-f-p[data-like=' + data.emot + ']').children('p').text(data.count);
                        }
                    } else {
                        serverError();
                    }
                }, error: function () {
                    internetConnection();
                }
            });
        });
    });

    //Post Users Option Comments
    $(document).on('click', '.postCommentsUsers', function () {
        $('.postUsersHeader').children().remove();
        $('.postUsersHeader').append('<h5 class="modal-title"><i class="fas fa-cog fa-fw"></i> Option</h5>');
        $('.postUsersBody').children().remove();
        let _on = ($(this).data('online') == 1) ? 'success' : 'secondary';
        if ($(this).data('op') == 1) {
            $('.postUsersBody').append('<div class="row justify-content-center"><div class="col-12 col-md-8 col-lg-6 text-center"><button class="btn btn-danger m-2 p-1 btn-block btn-round dComment" data-post="' + $(this).data('post') + '" data-id="' + $(this).data('id') + '" title="Delete your Comment"><i class="fas fa-trash fa-fw"></i> Delete</button></div></div>');
        } else {
            $('.postUsersBody').append('<div class="row justify-content-center"><div class="col-12 col-md-8 col-lg-6 text-center"><a href="' + _url + '/friends/' + $(this).data('user') + '" class="btn btn-light m-2 p-1 btn-block btn-round" title="See Detail about this User"><i class="fas fa-fw fa-user"></i> See User</a></div><div class="col-12 col-md-8 col-lg-6 text-center"><button class="btn btn-danger m-2 p-1 rComment btn-block btn-round" title="Report this Comment" data-post="' + $(this).data('post') + '" data-id="' + $(this).data('id') + '"><i class="fas fa-retweet fa-fw"></i> Report</button></div></div>');
        }
    });

    //send comments posts
    $('.sendComments').on('click', function () {
        let _idPost = $(this).data('id'), _comment = $('.comment-outside-form-' + _idPost).children().children().val();
        if (_comment.length != 0) {
            $.ajax({
                url: _url + '/comment',
                method: 'post',
                data: {
                    _token: $('#csrf-token')[0].content,
                    _id: _idPost,
                    _comment: _comment
                },
                dataType: 'json',
                beforeSend: function () {
                    $('.comment-outside-form-' + _idPost).children().children().attr('disabled', '');
                },
                success: function (data) {
                    $('.comment-outside-form-' + _idPost).children().children().val('');
                    $('.comment-outside-form-' + _idPost).children().children().removeAttr('disabled');
                    if (data.type == 1) {
                        $('.comment-post-outside[data-id=' + _idPost + ']').children().remove();
                        $('.comment-post-outside[data-id=' + _idPost + ']').append('<i class="fas fa-comment-dots"></i> <div class="d-inline-like"> ' + data.count + ' comments </div>');
                        commentPost(_idPost);
                    } else { errorSystem(); }
                },
                error: function () {
                    $('.comment-outside-form-' + _idPost).children().children().removeAttr('disabled');
                    internetConnection();
                }
            });
        }
    });
    //Posts Comments Get Ajax
    function commentPost(post) {
        $.ajax({
            url: _url + '/comment',
            method: 'get',
            data: { _token: $('#csrf-token')[0].content, _post: post },
            dataType: 'json',
            beforeSend: function () {
                $('.comments-post').append('<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
            },
            success: function (data) {
                $('.comment-post-outside').children().remove();
                $('.comment-post-outside').append('<i class="fas fa-comment-dots"></i> <div class="d-inline-like"> ' + data.length + ' Comments </div>');
                $('.comments-post').children().remove();
                $('.comments-post').append('<div class="row mt-2"><div class="col-12 col-md-12 col-lg-12"><ul class="list-unstyled box-comments"></ul></div></div>');
                $.each(data, function (i, val) {
                    $('.comments-post').children().children().children().append(`<li class="media mt-1"><img class="mr-3 img-responsive img-thumbnail shadow-sm" width="75" src="` + _url + `/img/profil/${val.image}"><div class="media-body"><div class="row"><div class="col-12 col-md-8 col-lg-8"><a href="javascript:void(0)" data-toggle="modal" data-target="#postUsers" class="comments postCommentsUsers" data-post="` + post + `" data-op="${val.op}" data-id="${val.ID}" data-user="${val.id_users}"><h6 class="mt-0 mb-1">${val.name}</h6></a></div><div class="col-12 col-md-4 col-lg-4 d-inline"><p>${val.time}</p></div></div><p class="mt-2m">${val.chat}</p></div></li>`);
                });
            },
            error: function () {
                $('.comments-post').children().remove();
                $('.comments-post').append('<p class="text-center">Make Sure you have Internet Connection</p>');
            }
        });
    }
    //Report Comment Full Post
    $(document).on('click', '.rComment', function () {
        $('.postUsersHeader').children().remove();
        $('.postUsersHeader').append('<h5 class="modal-title"><i class="fas fa-retweet fa-fw"></i> Report</h5>');
        $('.postUsersBody').children().remove();
        $('.postUsersBody').append('<form action="' + _url + '/report/comment/post" id="rComment" method="post"><div class="row p-1 m-1 justify-content-center"><input type="hidden" name="_token" value="' + $('#csrf-token')[0].content + '"> <input type="hidden" name="_method" value="post"><input type="hidden" name="_idPost" value="' + $(this).data('post') + '"><input type="hidden" name="_idComment" value="' + $(this).data('id') + '"><div class="col-12 col-md-9 col-lg-8"><div class="form-group"><label>Report\'s Detail</label><textarea class="form-control" placeholder="Tell me Why you report This Comment" style="height: 200px;" name="rComment"></textarea></div><button id="btnRC" class="btn btn-icon icon-left btn-danger btn-block" title="Report this Comment"><i class="fas fa-retweet"></i> Report</button></div></div></form>');
    });
    $(document).on('submit', '#rComment', function (e) {
        e.preventDefault(); let _f = $("#rComment");
        console.log($("#rComment").children('div').children('div').children('div').children('textarea').val());
        if (!jQuery.isEmptyObject($("#rComment").children('div').children('div').children('div').children('textarea').val())) {
            $.ajax({
                url: _f.attr('action'),
                method: _f.attr('method'),
                data: _f.serialize(),
                beforeSend: function () {
                    $("#rComment").children('div').children('div').children('button').attr('disabled', '');
                },
                success: function () {
                    $('.btn-close-model').trigger('click');
                    reportSuccess();
                },
                error: function () {
                    $('.btn-close-model').trigger('click');
                    internetConnection();
                }
            });
        }
    });
    //Ajax Delete Comment Full Post
    $(document).on('click', '.dComment', function () {
        let _post = ($(this).data('post'))
        $.ajax({
            url: _url + '/comment',
            method: 'post',
            data: { _token: $('#csrf-token')[0].content, _method: 'delete', id: $(this).data('id') },
            dataType: 'json',
            success: function (data) {
                if (data == 1) { delComment(); commentPost(_post); $('.btn-close-model').trigger('click'); } else { errorSystem() }
            }, error: function () { internetConnection(); }
        });
    });
});
