
"use strict";
$(function () {
    let _url = 'http://127.0.0.1:8000';

    $('document').ready(function () {
        modalUsers();
        $('input.currency').toArray().forEach(function(el) {
            new Cleave(el, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        });
    });

    $('#activityTable').DataTable({
        searching: true,
        lengthMenu: [5, 10, 25, 50],
        ordering:  true,
        "order" : [[0, 'desc']],
        language: {
          url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian-Alternative.json'
        }
    });
    //Read Upload Image Story
    function readImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
            $('.story-img-temp').attr('src', e.target.result).css('display', 'block');
            $('.story-img-temp').parent().css('display', 'block');
            $('.story-img-temp').siblings().css('display', 'block');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    //Stock
    $('.restock').submit(function(e){
        e.preventDefault();
        if($(this).children().children('div').children('input.form-control').val() > 0){
            $.ajax({
                url : $(this).attr('action'), method : $(this).attr('method'), data: {'data': $(this).serialize(), '_token': $('#csrf-token')[0].content}, dataType: 'json', success: function(data){ if(data == 1)window.location.href='';else error('Gagal', 'Pastikan Stok Memenuhi!') }, error: function(){error('Gagal', 'Stok Produk Gagal Diubah!');e.preventDefault();}
            });
        } else {
            error('Kesalahan', 'Angka harus Riil Positif');
        }
    });
    $('.materialProduct').selectric();
    //Read image My profil
    function imgProfile(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
            $('.postUsersBody').children().first().children().children().children('label').text(input.files[0].name);
            $('.postUsersBody').children('div').children('div.img').children('img').attr('src', e.target.result).css('display', 'block').css('width', '100px').css('height', '100px');
            $('.postUsersBody').children('div').children('div.text-center').css('display', 'inline-block');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    //Modal
    function modalUsers() {
        $('.modal-content').append('<div class="modal fade" tabindex="-1" role="dialog" id="postUsers"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><div class="postUsersHeader"><h5 class="modal-title"></h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body postUsersBody"></div><div class="modal-footer bg-whitesmoke br"><button type="button" class="btn btn-close-model btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button></div></div></div></div>');
    }
    //Messages
    function internetConnection() {
        iziToast.error({
            title: 'Sorry!',
            message: 'Check your internet connection!',
            position: 'topRight'
        });
    }
    function successIzi(title, mes) {
        iziToast.success({
            title: title,
            message: mes,
            position: 'topRight'
        });
    }
    function error(title, mes) {
        iziToast.error({
            title: title,
            message: mes,
            position: 'topRight'
        });
    }
    function serverError() {
        iziToast.error({
            title: 'Sorry!',
            message: 'Server Error, Try Again Later!',
            position: 'topRight'
        });
    }
    function errorSystem() {
        iziToast.error({
            title: 'Sorry!',
            message: 'System Busy!, Try Again',
            position: 'topRight'
        });
    }
});
