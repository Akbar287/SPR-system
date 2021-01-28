'use strict';

$(function () {
    let _url = 'http://127.0.0.1:8000';
    //hover like post not full
    $('.hover-like-post').hover(function () {
        $('.emotLike-' + $(this).data('id')).toggleClass('d-inline-like');
        $('.emotLike-' + $(this).data('id')).toggleClass('d-none');
        $('.emotLike-' + $(this).data('id')).toggleClass('list-inline-item');
    });
    //post like not full
    $(function () {
        let _idLike = 0, _idPost = 0;
        $('.like').on('click', function () {
            _idPost = $(this).parent().data('id');
            _idLike = $(this).data('like');
            $.ajax({
                url: _url + '/post/like',
                method: 'post',
                data: { _token: $('#csrf-token')[0].content, _like: _idLike, _post: _idPost },
                dataType: 'json',
                success: function (data) {
                    if (data.type == 1) $('.likeDesc-' + _idPost).text(data.count + ' Like (' + data.emot + ')');
                    else $('.likeDesc-' + _idPost).text(data.count + ' Like');
                }, error: function () {
                    internetConnection();
                }
            });
        });
    });
    $("#story-users").owlCarousel({
        items: 4,
        margin: 20,
        autoplay: false,
        autoplayTimeout: 5000,
        loop: false,
        responsive: { 0: { items: 2 }, 578: { items: 4 }, 768: { items: 4 } }
    });

    $('.comment-post-outside').on('click', function () {
        var idPost = $(this).data('id');
        $('.comment-outside-form-' + idPost).toggleClass('d-none');
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
});