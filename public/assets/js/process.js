function create_cookie(name, value, days2expire, path) {
    var date = new Date();
    date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 * 1000));
    var expires = date.toUTCString();
    document.cookie = name + '=' + value + ';' + 'expires=' + expires + ';' + 'path=' + path + ';';
}
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
function getCookies() {
    var c = document.cookie,
        v = 0,
        cookies = {};
    if (document.cookie.match(/^\s*\$Version=(?:"1"|1);\s*(.*)/)) {
        c = RegExp.$1;
        v = 1;
    }
    if (v === 0) {
        c.split(/[,;]/).map(function (cookie) {
            var parts = cookie.split(/=/, 2),
                name = decodeURIComponent(parts[0].trimLeft()),
                value = parts.length > 1 ? decodeURIComponent(parts[1].trimRight()) : null;
            cookies[name] = value;
        });
    } else {
        c.match(/(?:^|\s+)([!#$%&'*+\-.0-9A-Z^`a-z|~]+)=([!#$%&'*+\-.0-9A-Z^`a-z|~]*|"(?:[\x20-\x7E\x80\xFF]|\\[\x00-\x7F])*")(?=\s*[,;]|$)/g).map(function ($0, $1) {
            var name = $0,
                value = $1.charAt(0) === '"' ?
                    $1.substr(1, -1).replace(/\\(.)/g, "$1") :
                    $1;
            cookies[name] = value;
        });
    }
    return cookies;
}

function get_cookie(name) {
    return getCookies()[name];
}

function readURL(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function scrollSmoothToBottom(id) {
    var div = document.getElementById(id);
    $('#' + id).animate({
        scrollTop: div.scrollHeight - div.clientHeight
    }, 200);
}
function check_link() {
    link = $('.link_seo').val();
    if (link.length < 2) {
        $('.check_link').removeClass('ok');
        $('.check_link').addClass('error');
        $('.check_link').html('<i class="fa fa-ban"></i> Đường dẫn không hợp lệ');
    } else {
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: "check_link",
                link: link
            },
            success: function (kq) {
                var info = JSON.parse(kq);
                $('.link_seo').val(info.link);
                if (info.ok == 1) {
                    $('.check_link').removeClass('error');
                    $('.check_link').addClass('ok');
                    $('.check_link').html('<i class="fa fa-check-circle-o"></i> Đường dẫn hợp lệ');
                } else {
                    if ($('#link_old').length > 0) {
                        link_old = $('#link_old').val();
                        if (link_old == info.link) {
                            $('.check_link').removeClass('error');
                            $('.check_link').addClass('ok');
                            $('.check_link').html('<i class="fa fa-check-circle-o"></i> Đường dẫn hợp lệ');
                        }
                    } else {
                        $('.check_link').removeClass('ok');
                        $('.check_link').addClass('error');
                        $('.check_link').html('<i class="fa fa-ban"></i> Đường dẫn đã tồn tại');
                    }
                }
            }
        });
    }
}
function check_blank() {
    var link = $('.tieude_seo').val();
    if (link.length < 2) {
        $('.check_link').removeClass('ok');
        $('.check_link').addClass('error');
        $('.check_link').html('<i class="fa fa-ban"></i> Đường dẫn không hợp lệ');
    } else {
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: "check_blank",
                link: link
            },
            success: function (kq) {
                var info = JSON.parse(kq);
                $('.link_seo').val(info.link);
                if (info.ok == 1) {
                    $('.check_link').removeClass('error');
                    $('.check_link').addClass('ok');
                    $('.check_link').html('<i class="fa fa-check-circle-o"></i> Đường dẫn hợp lệ');
                } else {
                    if ($('#link_old').length > 0) {
                        link_old = $('#link_old').val();
                        if (link_old == info.link) {
                            $('.check_link').removeClass('error');
                            $('.check_link').addClass('ok');
                            $('.check_link').html('<i class="fa fa-check-circle-o"></i> Đường dẫn hợp lệ');
                        }
                    } else {
                        $('.check_link').removeClass('ok');
                        $('.check_link').addClass('error');
                        $('.check_link').html('<i class="fa fa-ban"></i> Đường dẫn đã tồn tại');
                    }
                }
            }
        });
    }
}
function confirm_del(action, id) {
    var title = '';
    if (action == 'del_chap') {
        title = 'Xóa chap truyện';
    } else if (action == 'del_truyen') {
        title = 'Xóa truyện';
    } else {
        title = 'Xóa dữ liệu';
    }
    $('#title_confirm').html(title);
    $('#button_thuchien').attr('action', action);
    $('#button_thuchien').attr('post_id', id);
    $('#box_pop_confirm').show();
}
$(document).ready(function () {
    if ($('.list_chat').length > 0) {
        scrollSmoothToBottom('list_chat');
    }
    $('.change_avatar').click(function () {
        $('#minh_hoa').click();
    });
    $('#preview-minhhoa').click(function () {
        $('#minh_hoa').click();
    });
    $("#minh_hoa").change(function () {
        readURL(this, 'preview-minhhoa');
    });
    /////////////////////////////
    $('.loc_anh').on('click', function () {
        content = $('textarea[name=content]').val();
        $('.content_img').html(content);
        var list = '';
        var i = 0;
        $('.content_img img').each(function () {
            i++;
            if (i == 1) {
                list += $(this).attr('src');
            } else {
                list += "\n" + $(this).attr('src');
            }
        });
        if (i > 0) {
            $('textarea[name=content]').val(list);
        }
    });
    /////////////////////////////
    $('#add_premium').click(function () {
        var goi = $('select[name=goi]').val();
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: 'add_premium',
                goi: goi
            },
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        window.location.reload();
                    }
                }, 3000);
            }
        });
    });
    /////////////////////////////
    $('#button_thuchien').click(function () {
        var id = $('#button_thuchien').attr('post_id');
        var action = $('#button_thuchien').attr('action');
        $('.box_pop').hide();
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: action,
                id: id
            },
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        window.location.reload();
                    }
                }, 3000);
            }
        });
    });
    /////////////////////////////
    $('button[name=add_chap]').click(function () {
        var tieu_de = $('input[name=tieu_de]').val();
        var tieu_de_mo_rong = $('input[name=tieu_de_mo_rong]').val();
        var thu_tu = $('input[name=thu_tu]').val();
        var coin = $('input[name=coin]').val();
        var password = $('input[name=password]').val();
        var truyen = $('input[name=truyen]').val();
        var noidung = $('textarea[name=content]').val();
        var list = '"1":{"nguon":"","crawl":"1","noidung":"' + noidung + '"}';
        var list_server = '{' + list + '}';
        if (tieu_de == '') {
            $('input[name=tieu_de]').focus();
        } else {
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: "/process.php",
                type: "post",
                data: {
                    action: "add_chap",
                    tieu_de: tieu_de,
                    tieu_de_mo_rong: tieu_de_mo_rong,
                    thu_tu: thu_tu,
                    truyen: truyen,
                    password: password,
                    coin: coin,
                    crawl_all: 1,
                    list_server: list_server
                },
                success: function (kq) {
                    var info = JSON.parse(kq);
                    setTimeout(function () {
                        $('.load_note').html(info.thongbao);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                        if (info.ok == 1) {
                            window.location.reload();
                        }
                    }, 3000);
                }
            });
        }
    });
    /////////////////////////////
    $('body').on('click', '.ok_men', function () {
        var vi_tri = $(this).attr('vi_tri');
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: "click_ok",
                vi_tri: vi_tri
            },
            success: function (kq) {
                var info = JSON.parse(kq);
            }
        });
    });
    /////////////////////////////
    $('body').on('click', '.content_diemdanh button', function () {
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: "click_diemdanh",
            },
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        window.location.reload();
                    }
                }, 3000);
            }
        });
    });
    /////////////////////////////
    $('button[name=edit_chap]').click(function () {
        var tieu_de = $('input[name=tieu_de]').val();
        var tieu_de_mo_rong = $('input[name=tieu_de_mo_rong]').val();
        var thu_tu = $('input[name=thu_tu]').val();
        var coin = $('input[name=coin]').val();
        var truyen = $('input[name=truyen]').val();
        var password = $('input[name=password]').val();
        var noidung = $('textarea[name=content]').val();
        var crawl_all = 1;
        var id = $('input[name=id]').val();
        var list = '"1":{"nguon":"","crawl":"1","noidung":"' + noidung + '"}';
        var list_server = '{' + list + '}';
        if (tieu_de == '') {
            $('input[name=tieu_de]').focus();
        } else {
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: "/process.php",
                type: "post",
                data: {
                    action: "edit_chap",
                    tieu_de: tieu_de,
                    tieu_de_mo_rong: tieu_de_mo_rong,
                    thu_tu: thu_tu,
                    truyen: truyen,
                    password: password,
                    coin: coin,
                    crawl_all: crawl_all,
                    list_server: list_server,
                    id: id
                },
                success: function (kq) {
                    var info = JSON.parse(kq);
                    setTimeout(function () {
                        $('.load_note').html(info.thongbao);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                        if (info.ok == 1) {
                            window.location.reload();
                        }
                    }, 3000);
                }
            });
        }
    });
    /////////////////////////////
    $('button[name=edit_truyen').on('click', function () {
        var noidung = tinyMCE.activeEditor.getContent();
        var tieu_de = $('input[name=tieu_de]').val();
        var ten_khac = $('input[name=ten_khac]').val();
        var link = $('input[name=link]').val();
        var notice = $('input[name=notice]').val();
        var link_old = $('input[name=link_old]').val();
        var title = $('input[name=title]').val();
        var full = $('input[name=full]:checked').val();
        var description = $('textarea[name=description]').val();
        var list_cat = [];
        $('.li_input input:checked').each(function () {
            list_cat.push($(this).val());
        });
        list_cat = list_cat.toString();
        var id = $('input[name=id]').val();
        if (tieu_de.length < 2) {
            $('input[name=tieu_de]').focus();
        } else {
            var file_data = $('#minh_hoa').prop('files')[0];
            var form_data = new FormData();
            form_data.append('action', 'edit_truyen');
            form_data.append('file', file_data);
            form_data.append('tieu_de', tieu_de);
            form_data.append('ten_khac', ten_khac);
            form_data.append('category', list_cat);
            form_data.append('link', link);
            form_data.append('notice', notice);
            form_data.append('link_old', link_old);
            form_data.append('full', full);
            form_data.append('title', title);
            form_data.append('description', description);
            form_data.append('noidung', noidung);
            form_data.append('id', id);
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: '/process.php',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (kq) {
                    var info = JSON.parse(kq);
                    if (info.ok == 1) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    }
                    setTimeout(function () {
                        $('.load_note').html(info.thongbao);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                    }, 3000);
                }
            });
        }
    });
    /////////////////////////////
    $('button[name=add_truyen').on('click', function () {
        var noidung = tinyMCE.activeEditor.getContent();
        var tieu_de = $('input[name=tieu_de]').val();
        var ten_khac = $('input[name=ten_khac]').val();
        var link = $('input[name=link]').val();
        var notice = $('input[name=notice]').val();
        var title = $('input[name=title]').val();
        var full = $('input[name=full]:checked').val();
        var description = $('textarea[name=description]').val();
        var list_cat = [];
        $('.li_input input:checked').each(function () {
            list_cat.push($(this).val());
        });
        list_cat = list_cat.toString();
        if (tieu_de.length < 2) {
            $('input[name=tieu_de]').focus();
        } else {
            var file_data = $('#minh_hoa').prop('files')[0];
            var form_data = new FormData();
            form_data.append('action', 'add_truyen');
            form_data.append('file', file_data);
            form_data.append('tieu_de', tieu_de);
            form_data.append('ten_khac', ten_khac);
            form_data.append('category', list_cat);
            form_data.append('link', link);
            form_data.append('notice', notice);
            form_data.append('full', full);
            form_data.append('title', title);
            form_data.append('description', description);
            form_data.append('noidung', noidung);
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: '/process.php',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (kq) {
                    var info = JSON.parse(kq);
                    if (info.ok == 1) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    } else {

                    }
                    setTimeout(function () {
                        $('.load_note').html(info.thongbao);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                    }, 3000);
                }
            });
        }
    });
    //////////////////////////
    $('button[name=button_profile]').on('click', function () {
        var name = $('#tab_profile_content input[name=name]').val();
        if (name.length < 4) {
            $('#tab_profile_content input[name=name]').focus();
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            setTimeout(function () {
                $('.load_note').html('Tên hiển thị quá ngắn...');
            }, 1000);
            setTimeout(function () {
                $('.load_process').hide();
                $('.load_note').html('Hệ thống đang xử lý');
                $('.load_overlay').hide();
            }, 3000);
        } else {
            var file_data = $('#minh_hoa').prop('files')[0];
            var form_data = new FormData();
            form_data.append('action', 'edit_profile');
            form_data.append('file', file_data);
            form_data.append('name', name);
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: '/process.php',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (kq) {
                    var info = JSON.parse(kq);
                    if (info.ok == 1) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    } else {

                    }
                    setTimeout(function () {
                        $('.load_note').html(info.thongbao);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                    }, 3000);
                }

            });
        }

    });
    //////////////////////
    $('button[name=button_password]').on('click', function () {
        var password_old = $('#tab_password_content input[name=password_old]').val();
        var password_new = $('#tab_password_content input[name=password_new]').val();
        var re_password_new = $('#tab_password_content input[name=re_password_new]').val();
        if (password_old.length < 6) {
            $('#tab_password_content input[name=password_old]').focus();
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            setTimeout(function () {
                $('.load_note').html('Mật khẩu cũ quá ngắn...');
            }, 1000);
            setTimeout(function () {
                $('.load_process').hide();
                $('.load_note').html('Hệ thống đang xử lý');
                $('.load_overlay').hide();
            }, 3000);
        } else if (password_new.length < 6) {
            $('#tab_password_content input[name=password_new]').focus();
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            setTimeout(function () {
                $('.load_note').html('Mật khẩu mới phải dài từ 6 ký tự...');
            }, 1000);
            setTimeout(function () {
                $('.load_process').hide();
                $('.load_note').html('Hệ thống đang xử lý');
                $('.load_overlay').hide();
            }, 3000);
        } else if (password_new != re_password_new) {
            $('#tab_password_content input[name=re_password_new]').focus();
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            setTimeout(function () {
                $('.load_note').html('Nhập lại mật khẩu mới không khớp...');
            }, 1000);
            setTimeout(function () {
                $('.load_process').hide();
                $('.load_note').html('Hệ thống đang xử lý');
                $('.load_overlay').hide();
            }, 3000);
        } else {
            var form_data = new FormData();
            form_data.append('action', 'change_password');
            form_data.append('password_old', password_old);
            form_data.append('password_new', password_new);
            form_data.append('re_password_new', re_password_new);
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: '/process.php',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (kq) {
                    var info = JSON.parse(kq);
                    if (info.ok == 1) {
                        $('#tab_password_content input[name=password_old]').val('');
                        $('#tab_password_content input[name=password_new]').val('');
                        $('#tab_password_content input[name=re_password_new]').val('');
                    }
                    setTimeout(function () {
                        $('.load_note').html(info.thongbao);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                    }, 3000);
                }

            });
        }

    });

    //////////////////////
    $('.box_note .note_title i').on('click', function () {
        $('.box_note').hide();
        $('.box_note .note_content').html('');
    });
    //////////////////////
    $('.list_comment').on('click', '.show_reply', function () {
        var id = $(this).attr('id_comment');
        var div = $(this);
        var form_data = new FormData();
        form_data.append('action', 'load_reply');
        form_data.append('id', id);
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                div.parent().parent().parent().find('.list_sub_comment').html(info.list);
                div.remove();
            }
        });
    });
    //////////////////////
    $('.list_comment').on('click', '.del_sub_comment', function () {
        var id = $(this).attr('id_comment');
        var div = $(this);
        var form_data = new FormData();
        form_data.append('action', 'del_comment');
        form_data.append('id', id);
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    $('#li_sub_comment_' + id).fadeOut();
                } else {
                    $('.load_overlay').show();
                    $('.load_process').fadeIn();
                    $('.load_note').html(info.thongbao);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                    }, 3000);
                }
            }
        });
    });
    /////////////////////////////
    $('body').on('click', '.chon_anh', function () {
        $('#photo-add').click();
    });
    $('#photo-add').on('change', function () {
        var truyen = $('input[name=truyen]').val();
        var form_data = new FormData();
        form_data.append('action', 'upload_photo');
        form_data.append('truyen', truyen);
        $.each($("input[name=file]")[0].files, function (i, file) {
            form_data.append('file[]', file);
        });
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        $('textarea[name=content]').append(info.list);
                    }
                }, 3000);
            }
        });
    });
    //////////////////////
    $('.list_comment').on('click', '.del_comment', function () {
        var id = $(this).attr('id_comment');
        var div = $(this);
        var form_data = new FormData();
        form_data.append('action', 'del_comment');
        form_data.append('id', id);
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    $('#li_comment_' + id).fadeOut();
                } else {
                    $('.load_overlay').show();
                    $('.load_process').fadeIn();
                    $('.load_note').html(info.thongbao);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                    }, 3000);
                }
            }
        });
    });
    //////////////////////
    $('button[name=button_mxh]').on('click', function () {
        var facebook = $('#tab_mxh_content input[name=facebook]').val();
        var form_data = new FormData();
        form_data.append('action', 'change_mxh');
        form_data.append('facebook', facebook);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                }, 3000);
            }
        });
    });
    //////////////////////
    $('#add_nap').on('click', function () {
        var telco = $('select[name=telco]').val();
        var amount = $('select[name=amount]').val();
        var code = $('input[name=code]').val();
        var serial = $('input[name=serial]').val();
        var form_data = new FormData();
        form_data.append('action', 'napcoin');
        form_data.append('telco', telco);
        form_data.append('amount', amount);
        form_data.append('code', code);
        form_data.append('serial', serial);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        window.location.reload();
                    } else { }
                }, 3000);
            }
        });
    });
    //////////////////////
    $('#button_muachap').on('click', function () {
        var chap = $('input[name=chap]').val();
        var truyen = $('input[name=truyen]').val();
        var pass_chap = $('input[name=pass_chap]').val();
        var form_data = new FormData();
        form_data.append('action', 'muachap');
        form_data.append('chap', chap);
        form_data.append('truyen', truyen);
        form_data.append('pass_chap', pass_chap);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        window.location.reload();
                    } else { }
                }, 3000);
            }
        });
    });
    //////////////////////
    $('#button_donate').on('click', function () {
        var coin = $('#box_pop_donate input[name=coin]').val();
        var truyen = $('#show_donate').attr('truyen');
        var nhom = $('#show_donate').attr('nhom');
        var form_data = new FormData();
        form_data.append('action', 'donate');
        form_data.append('coin', coin);
        form_data.append('truyen', truyen);
        form_data.append('nhom', nhom);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    $('#box_pop_donate #text_note span').html(info.text);
                    $('#box_pop_donate #text_note').show();
                    $('#box_pop_donate input[name=coin]').val('');
                } else {
                    $('#box_pop_donate #text_note').hide();
                    $('#box_pop_donate #text_note span').html('');
                }
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                }, 3000);
            }
        });
    });
    //////////////////////
    $('#button_follow').on('click', function () {
        var form_data = new FormData();
        form_data.append('type', 'comic');
        form_data.append('id', id_item);
        form_data.append('_token', csrf_token);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: url_follow,
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (info) {
                setTimeout(function () {
                    $('.load_note').html(info.message);
                }, 500);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    $('#button_follow').html(info.text);
                }, 2000);
            }
        });
    });
    //////////////////////
    $('.del_history').on('click', function () {
        var id = $(this).attr('id');
        var form_data = new FormData();
        form_data.append('action', 'del_history');
        form_data.append('id', id);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 500);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        $('.follow_' + id).remove();
                    }
                }, 2000);
            }
        });
    });
    //////////////////////
    $('button[name=button_block]').on('click', function () {
        var form_data = new FormData();
        form_data.append('action', 'block_account');
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    setTimeout(function () {
                        window.location.href = '/';
                    }, 3000);
                } else {

                }
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                }, 3000);
            }
        });
    });
    //////////////////////
    $('#forgot_password').on('click', function () {
        var email = $('#box_pop_password input[name=email]').val();
        var form_data = new FormData();
        form_data.append('action', 'forgot_password');
        form_data.append('email', email);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                } else {

                }
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                }, 3000);
            }
        });
    });
    //////////////////////
    $('button[name=button_check_email]').on('click', function () {
        var email = $('#tab_email_content input[name=email]').val();
        var form_data = new FormData();
        form_data.append('action', 'check_email');
        form_data.append('email', email);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    setTimeout(function () {
                        $('#li_check_email').hide();
                        $('#tab_email_content input[name=email]').attr('disabled', 'disabled');
                        $('#li_text').show();
                        $('#li_text .text').html(info.text);
                        $('#li_code').show();
                        $('#li_update_email').show();
                    }, 3000);
                } else {

                }
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                }, 3000);
            }
        });
    });
    //////////////////////
    $('button[name=button_update_email]').on('click', function () {
        var email = $('#tab_email_content input[name=email]').val();
        var code = $('#tab_email_content input[name=code]').val();
        var form_data = new FormData();
        form_data.append('action', 'update_email');
        form_data.append('email', email);
        form_data.append('code', code);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    setTimeout(function () {
                        $('#li_del_email').show();
                        $('#tab_email_content input[name=email]').attr('disabled', 'disabled');
                        $('#li_text').hide();
                        $('#li_text .text').html(info.text);
                        $('#tab_email_content input[name=code]').val('');
                        $('#li_code').hide();
                        $('#li_update_email').hide();
                    }, 3000);
                } else {

                }
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                }, 3000);
            }
        });
    });
    //////////////////////
    $('button[name=button_del_email]').on('click', function () {
        var email = $('#tab_email_content input[name=email]').val();
        var code = $('#tab_email_content input[name=code]').val();
        var step = $(this).attr('step');
        var form_data = new FormData();
        form_data.append('action', 'del_email');
        form_data.append('email', email);
        form_data.append('code', code);
        form_data.append('step', step);
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                if (info.ok == 1) {
                    if (step == 1) {
                        setTimeout(function () {
                            $('button[name=button_del_email]').attr('step', '2');
                            $('#li_text').show();
                            $('#li_text .text').html(info.text);
                            $('#li_code').show();
                        }, 3000);
                    } else {
                        setTimeout(function () {
                            $('#li_del_email').hide();
                            $('button[name=button_del_email]').attr('step', '1');
                            $('#tab_email_content input[name=code]').val('');
                            $('#tab_email_content input[name=email]').removeAttr('disabled');
                            $('#tab_email_content input[name=email]').val('');
                            $('#li_text').hide();
                            $('#li_text .text').html('');
                            $('#li_code').hide();
                            $('#li_check_email').show();
                        }, 3000);
                    }
                }
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                }, 3000);
            }
        });
    });
    /////////////////
    $('.toggle_chat').on('click', function () {
        if ($('.toggle_chat .fa-plus-circle').length > 0) {
            $('.toggle_chat .fa').removeClass('fa-plus-circle');
            $('.toggle_chat .fa').addClass('fa-minus-circle');
            $('.content_box_chat').css('height', '500px');
        } else {
            $('.toggle_chat .fa').removeClass('fa-minus-circle');
            $('.toggle_chat .fa').addClass('fa-plus-circle');
            $('.content_box_chat').css('height', '267px');
        }
    });
    /////////////////
    $('.sound_chat').on('click', function () {
        if ($('.sound_chat .icon-volume-medium').length > 0) {
            $('.sound_chat .icon').removeClass('icon-volume-medium');
            $('.sound_chat .icon').addClass('icon-volume-mute2');
            setCookie('play_sound', 2, 30);
        } else {
            $('.sound_chat .icon').removeClass('icon-volume-mute2');
            $('.sound_chat .icon').addClass('icon-volume-medium');
            setCookie('play_sound', 1, 30);
        }
    });
    if ($('.sound_chat').length > 0) {
        if (get_cookie('play_sound') == 2) {
            $('.sound_chat .icon').removeClass('icon-volume-medium');
            $('.sound_chat .icon').addClass('icon-volume-mute2');
        } else {
            $('.sound_chat .icon').removeClass('icon-volume-mute2');
            $('.sound_chat .icon').addClass('icon-volume-medium');
        }
    }
    var lastScrollTop = 0;
    $('.list_chat').scroll(function () {
        var st = $(this).scrollTop();
        if (st > lastScrollTop) {
        } else {
            load = $('input[name=load_chat]').val();
            loaded = $('input[name=load_chat]').attr('loaded');
            chat_id = $('.li_chat').first().attr('chat_id');
            if (st < 50 && loaded == 1 && load == 1) {
                $('.list_chat').prepend('<div class="li_load_chat"><i class="fa fa-spinner fa-spin"></i> Đang tải dữ liệu...</div>');
                $('input[name=load_chat]').attr('loaded', '0');
                setTimeout(function () {
                    $.ajax({
                        url: "/process.php",
                        type: "post",
                        data: {
                            action: "load_chat_room",
                            chat_id: chat_id
                        },
                        success: function (kq) {
                            var info = JSON.parse(kq);
                            $('.list_chat .li_load_chat').remove();
                            $('input[name=load_chat]').val(info.load_chat);
                            $('input[name=load_chat]').attr('loaded', '1');
                            if (info.ok == 1) {
                                $('.list_chat').prepend(info.list);
                            }
                        }
                    });
                }, 1000);
            }
        }
        lastScrollTop = st;
    });
    /////////////////
    $('.widget_right .load_more').on('click', function () {
        $(this).parent().find('ul').addClass('active');
        $(this).hide();
    });
    $('.widget_right .list_tab .tab').on('click', function () {
        if ($(this).hasClass('active')) {

        } else {
            id = $(this).attr('id');
            $(this).parent().find('.tab').removeClass('active');
            $(this).addClass('active');
            if ($(this).parent().parent()) {
                $(this).parent().parent().find('li').hide();
                $(this).parent().parent().find('li.' + id).show();
            }
            if ($(this).parent().parent().parent()) {
                $(this).parent().parent().parent().find('li').hide();
                $(this).parent().parent().parent().find('li.' + id).show();
            }
        }
    });
    ////////////////////////
    $('#show_pop_register').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_register').show();
    });
    ////////////////////////
    $('#show_pop_login').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_login').show();
    });
    $('.button_cancel').on('click', function () {
        $('.box_pop').fadeOut();
        $(this).parent().parent().parent().find('input').val('');
    });
    ////////////////////////
    $('.button_show_login').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_login').show();
    });
    ////////////////////////
    $('.button_show_register').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_register').show();
    });
    ////////////////////////
    $('#show_donate').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_donate').show();
    });
    ////////////////////////
    $('.block_content span').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_muachap').show();
    });
    ////////////////////////
    $('#show_report').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_report').show();
    });
    ////////////////////////
    $('.button_show_password').on('click', function () {
        $('.box_pop').hide();
        $('#box_pop_password').show();
    });
    ////////////////////////
    $('.member_info').on('click', function () {
        $('.control_list').toggle();
    });
    ////////////////////////
    $('.list_tab_profile .li_tab').on('click', function () {
        $('.list_tab_profile .li_tab').removeClass('active');
        $(this).addClass('active');
        id = $(this).attr('id');
        $('.tab_profile_content .box_tab').removeClass('active');
        $('.tab_profile_content #' + id + '_content').addClass('active');
    });
    ////////////////////////
    $('#button_register').on('click', function () {
        var email = $('#box_pop_register input[name=email]').val();
        var name = $('#box_pop_register input[name=name]').val();
        var password = $('#box_pop_register input[name=password]').val();
        var re_password = $('#box_pop_register input[name=re_password]').val();
        if (email.length < 4) {
            $('#box_pop_register input[name=email]').focus();
        } else if (password.length < 6) {
            $('#box_pop_register input[name=password]').focus();
        } else if (password != re_password) {
            $('#box_pop_register input[name=re_password]').focus();
        } else {
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: urlRegister,
                type: "post",
                data: {
                    name: name,
                    email: email,
                    password: password,
                    _token: csrf_token
                },
                success: function (info) {
                    if (info.ok == 1) {
                        $('#box_pop_register input[name=name]').val('');
                        $('#box_pop_register input[name=email]').val('');
                        $('#box_pop_register input[name=password]').val('');
                        $('#box_pop_register input[name=re_password]').val('');
                    }
                    setTimeout(function () {
                        $('.load_note').html(info.message);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                        if (info.ok == 1) {
                            $('.button_show_login').click();
                        }
                    }, 3000);
                },
                error: function (error) {
                    // alert(error.responseJSON.message);
                    $('.load_note').html(error.responseJSON.message);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                    }, 3000);
                }
            });
        }
    });
    ////////////////////////
    $('.button_logout').on('click', function () {
        $('.load_overlay').show();
        $('.load_process').fadeIn();
        $.ajax({
            url: "/process_logout.php",
            type: "post",
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.load_note').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        window.location.href = '/';
                    }
                }, 3000);
            }
        });
    });
    ////////////////////////
    $('#button_login').on('click', function () {
        var username = $('#box_pop_login input[name=username]').val();
        var password = $('#box_pop_login input[name=password]').val();
        if (username.length < 4) {
            $('#box_pop_login input[name=username]').focus();
        } else if (password.length < 6) {
            $('#box_pop_login input[name=password]').focus();
        } else {
            $('.load_overlay').show();
            $('.load_process').fadeIn();
            $.ajax({
                url: urlLogin,
                type: "post",
                data: {
                    email: username,
                    password: password,
                    _token: csrf_token
                },
                success: function (info) {
                    setTimeout(function () {
                        $('.load_note').html(info.message);
                    }, 1000);
                    setTimeout(function () {
                        $('.load_process').hide();
                        $('.load_note').html('Hệ thống đang xử lý');
                        $('.load_overlay').hide();
                        if (info.ok == 1) {
                            window.location.reload();
                        }
                    }, 3000);
                }
            });
        }
    });
    ////////////////////////
    $('input[name=input_search]').on('keyup', function () {
        var key = $(this).val();
        if (key.length < 2) {
            $('.kq_search').hide();
        } else {
            $('.kq_search').show();
            $('.kq_search').html('<center><img src="/images/loading.gif"></center>');
            $.ajax({
                url: urlSearch,
                type: 'post',
                data: {
                    search: key,
                    _token: csrf_token
                },
                success: function (info) {
                    $('.kq_search').html(info.list);
                }
            });
        }
    });
    /////////////////////////////
    $('input[name=input_search]').keypress(function (e) {
        if (e.which == 13) {
            key = $('input[name=input_search]').val();
            link = '/tim-kiem-nang-cao?keyword=' + key;
            if (key.length < 2) {
                $('input[name=input_search]').focus();
            } else {
                window.location.href = link;
            }
            return false;
        }
    });
    /////////////////////////////
    $('.box_search button').click(function () {
        var key = $('input[name=input_search]').val();
        var link = '/tim-kiem.html?key=' + encodeURI(key).replace(/%20/g, '+');
        if (key.length < 2) {
            $('input[name=input_search]').focus();
        } else {
            window.location.href = link;
        }
    });
    $('.button_menu').click(function () {
        $('.box_search').hide();
        $('.box_logo_mobile').toggle();
        $('.box_menu').toggle();
    });
    /////////////////////
    $('.box_logo_mobile i').click(function () {
        $('.box_logo_mobile').toggle();
        $('.box_menu').toggle();
        $('.li_main i').addClass('fa-angle-down');
        $('.li_main i').removeClass('fa-angle-up');
        $('.sub_menu').hide();
    });
    /////////////////////
    $('.li_main i').click(function () {
        $(this).parent().find('.sub_menu').toggle();
        if ($(this).hasClass('fa-angle-down')) {
            $(this).removeClass('fa-angle-down');
            $(this).addClass('fa-angle-up');
        } else {
            $(this).addClass('fa-angle-down');
            $(this).removeClass('fa-angle-up');
        }

    });
    /////////////////////
    $('.button_search').click(function () {
        $('.box_search').toggle();
        $('.box_logo_mobile').hide();
        $('.box_menu').hide();
    });
    /////////////////////
    $('.select_chap').on('change', function () {
        value = $(this).val();
        window.location.href = value;
    });
    ////////////////////////
    $('.light-see').click(function () {
        $('body').toggleClass('chap_view');
        if ($('body').hasClass('chap_view')) {
            $('.light-see span').html('Tắt đèn');
        } else {
            $('.light-see span').html('Bật đèn');
        }
    });
    ////////////////////////
    $("body").keydown(function (e) {
        if ($('.content_view_chap').length > 0) {
            var link = '';
            if (e.keyCode == 37) {
                if ($('.link-prev-chap').length > 0) {
                    link = $('.link-prev-chap').attr('href');
                    window.location.href = link;

                }
            } else if (e.keyCode == 39) {
                if ($('.link-next-chap').length > 0) {
                    link = $('.link-next-chap').attr('href');
                    window.location.href = link;
                }
            }
        } else {

        }
    });
    ////////////////////////
    $('.list_server').on('click', 'a', function () {
        $('.list_server a').removeClass('bg_green');
        $(this).addClass('bg_green');
        var truyen = $(this).attr("truyen");
        var chap = $(this).attr("chap");
        var server = $(this).attr("server");
        $('.content_view_chap').html('<div class="load_content"></div>');
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: "load_img",
                truyen: truyen,
                server: server,
                chap: chap
            },
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.content_view_chap').html(info.list_img);
                }, 500);
            }
        });
    });
    /////////////////////
    $('.play-button').click(function () {
        var id = $('.video__channel').attr("id");
        var server = $('.video__channel').attr("server");
        var tap = $('.video__channel').attr("tap");
        $('#video-player-content').html('<div id="loading"> <div class="loading-container"> <div class="loading-ani"></div><div class="loading-text"> loading </div></div></div>');
        $.ajax({
            url: "/process.php",
            type: "post",
            data: {
                action: "load_media",
                id: id,
                server: server,
                tap: tap
            },
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('.video-container').html(info.media);
                }, 500);
            }
        });
    });
    /////////////////////
    $('button[name=transfer_truyen]').on('click', function () {
        var id_nhom_dich = $('#group_id').val();
        var id = $('input[name=truyen]').val();
        if (!id_nhom_dich || !id) {
            $('#transfer_message').html('Vui lòng chọn nhóm nhận!');
            return;
        }
        var form_data = new FormData();
        form_data.append('action', 'transfer-truyen');
        form_data.append('id_nhom_dich', id_nhom_dich);
        form_data.append('id', id);

        $('.load_overlay').show();
        $('.load_process').fadeIn();

        $.ajax({
            url: '/process.php',
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (kq) {
                var info = JSON.parse(kq);
                setTimeout(function () {
                    $('#transfer_message').html(info.thongbao);
                }, 1000);
                setTimeout(function () {
                    $('.load_process').hide();
                    $('.load_note').html('Hệ thống đang xử lý');
                    $('.load_overlay').hide();
                    if (info.ok == 1) {
                        $('#transfer_message').html(info.thongbao);
                    }
                }, 3000);
            }
        });
    });
});
