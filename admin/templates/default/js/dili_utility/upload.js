var uploaderSwitcher, uploadedfile, uploaded, uploaderContainer, attachContainer, loading, clipboardContainer, clipboard, clipboardStatus, need_context_menu;

$(function () {
    $.ajaxSetup({
        cache: false
    });
    uploaderSwitcher = $('#uploaderSwitcher');
    uploadedfile = $('#uploadedfile');
    uploaderContainer = $('#uploaderContainer');
    attachContainer = $('#attachList');
    uploaded = uploadedfile.val();
    loading = $('#loading');
    loading.ajaxStart(function () {
        $(this).show();
    });
    loading.ajaxError(function () {
        $(this).show().text("操作失败");
    });
    loading.ajaxComplete(function () {
        $(this).hide();
    });
    if (uploaded != '0' && uploaded != '' && uploaded) {
        loading.text("更新附件列表中......");
        $.get(backend_url + 'attachment/list?ids=' + uploaded, function (data) {
                data = data.split(',');
                for (var v  in data) {
                    v = data[v].split('|');
                    attachContainer.append(insert_new_attachment(v));
                    toggleUploader(true);
                }
            }
        );
    }
    $(window).resize(function () {
        if (uploaderContainer.css('display') != 'none') {
            toggleUploader(true);
        }
    });
    init_contextmenu();
});

function thumbnail(url, ext)
{
    if (thumbDefaultSize) {
        return url + '.' + thumbDefaultSize + '.' + (ext == 'gif' ? 'gif' : 'jpg');
    }
    return url;
}

function init_contextmenu() {
    var editors = $('textarea[data-editor="kindeditor"]');
    if (editors.length === 0) {
        need_context_menu = false;
        $('a.contextMenu').remove();
    }
    else {
        need_context_menu = true;
        var items = {};
        editors.each(function (index, editor) {
            items[index] = {name: $.trim($(editor).parent().parent().children('th').text().replace('：', '')), icon: "paste"}
        });
        $.contextMenu({
            selector: 'a.contextMenu',
            trigger: 'left',
            callback: function (key, opt) {
                var trigger = opt.$trigger;
                var html = '';
                switch (trigger.attr('data-type')) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                    case 'bmp':
                        //以图片方式插入
                        html = '<img src="' + thumbnail(trigger.attr('data-url'), trigger.attr('data-type')) + '" alt="" />';
                        break;
                    case 'avi':
                    case 'wmv':
                        //视频插入
                        html = '<embed type="application/x-mplayer2" classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" src="' + trigger.attr('data-url') + '" enablecontextmenu="false" autostart="false" width="480" height="400" />';
                        break;
                    case 'swf':
                        //FLASH 插入
                        html = '<embed type="application/x-shockwave-flash" classid="clsid:d27cdb6e-ae6d-11cf-96b8-4445535400000" src="' + trigger.attr('data-url') + '" wmode="opaque" quality="high" menu="false" play="true" loop="true" allowfullscreen="true" width="480" height="400" />';
                        break;
                    default:
                        //附件方式插入
                        html = '<a href="' + trigger.attr('data-url') + '">' + trigger.attr('data-name') + '</a>';
                }
                KindEditor.instances[key].insertHtml(html);
            },
            items: items
        });
    }
}

function insert_new_attachment(v) {
    v_url = attachment_url + v[4] + '/' + v[2] + '.' + v[5];
    html = "<li id=\"attachment_" + v[0] + "\"><span class=\"title\"><input type=\"text\" class=\"normal\" value=\"" + v_url + "\" />(未保存)</span>";
    if (v[3] == 1) {
        html += "<a href=\"" + v_url + "\" target=\"_blank\">预览</a>";
    }
    if (need_context_menu) {
        html += '<a href="javascript:void(0);" data-url="' + v_url + '" data-image="' + v[3] + '" data-type="' + v[5] + '" data-name="' + v[1] + '" class="contextMenu" target="_blank">插入</a>';
    }
    html += "<a href=\"javascript:void(0);\" onclick=\"if(confirm('是否要删除该附件?')){delete_attachment('" + v[0] + "');}\">删除</a>";
    return html;
}

function delete_attachment(id) {
    loading.text("删除操作进行中......");
    $.get(backend_url + 'attachment/del?id=' + id, function (data) {
            if (data == 'ok') {
                attachContainer.find('#attachment_' + id).remove();
            }
            else {
                alert('删除失败!');
            }
        }
    );
}

function swfHandler(trigger, type, data) {
    if (trigger == 2) {
        if (data) {
            data = data.substr(0, data.length - 1);
            data = data.split(',');
            for (var v  in data) {
                v = data[v].split('|');
                uploaded += ',' + v[0];
                attachContainer.append(insert_new_attachment(v));
            }
            uploadedfile.val(uploaded);
        }
        toggleUploader();
    }
    else {
        //return ;
    }

}

function toggleUploader(force) {

    if (uploaderContainer.css('display') == 'none' || force == true) {
        if (force != true) {
            uploaderContainer.show();
            uploaderSwitcher.text("关闭上传控件");
        }
    }
    else {
        uploaderContainer.hide();
        uploaderSwitcher.text("打开上传控件");
    }
}

function after_editor_upload(data) {
    file = data.msg.split('|');
    uploaded += ',' + file[0];
    attachContainer.append(insert_new_attachment(file));
    uploadedfile.val(uploaded);
}

