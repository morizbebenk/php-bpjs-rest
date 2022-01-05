var jsonViewer = new JSONViewer();
var global_json = 0;

$(document).ready(function () {
    $("#jsonResponse").html(jsonViewer.getContainer());

    if (window.localStorage.getItem("consid")) {
        $('#consid').val(window.localStorage.getItem("consid"));
    }

    if (window.localStorage.getItem("secret")) {
        $('#secret').val(window.localStorage.getItem("secret"));
    }

    if (window.localStorage.getItem("user_key")) {
        $('#user_key').val(window.localStorage.getItem("user_key"));
    }

    if (window.localStorage.getItem("username")) {
        $('#username').val(window.localStorage.getItem("username"));
    }

    if (window.localStorage.getItem("password")) {
        $('#password').val(window.localStorage.getItem("password"));
    }

    if (window.localStorage.getItem("show_hide")) {
        setInputText(window.localStorage.getItem("show_hide"));
    }

    if (window.localStorage.getItem("selectAPI")) {
        $("#selectAPI").val(window.localStorage.getItem("selectAPI"));
    }

    setFormREST();

    $("#selectAPI").change(function (e) {
        setFormREST();
    })

    $(".collapseResponse").click(function (e) {
        if ($("#collapse").val() == 'collapse_default') {
            jsonViewer.showJSON(JSON.parse(global_json), null, 2);
        } else {
            jsonViewer.showJSON(JSON.parse(global_json));
        }

        $("#collapse").blur();
    })

    $('#url, #endpoint').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            getResponse();
        }
    });

    $("#send").click(function (e) {
        getResponse();
    })

    $("#btnSaveCredential").click(function (e) {
        var r = ''
        r = confirm('Yakin Ingin Simpan Data Di Lokal ?');

        if (r == true) {
            window.localStorage.setItem("consid", $('#consid').val());
            window.localStorage.setItem("secret", $('#secret').val());
            window.localStorage.setItem("user_key", $('#user_key').val());
            window.localStorage.setItem("username", $('#username').val());
            window.localStorage.setItem("password", $('#password').val());
            window.localStorage.setItem("selectAPI", $('#selectAPI').val());
        }
    })

    $("#btnDeleteCredential").click(function (e) {
        var r = ''
        r = confirm('Yakin Ingin Hapus Data Dari Lokal ?');

        if (r == true) {
            window.localStorage.setItem("consid", '');
            window.localStorage.setItem("secret", '');
            window.localStorage.setItem("user_key", '');
            window.localStorage.setItem("username", '');
            window.localStorage.setItem("password", '');
            window.localStorage.setItem("show_hide", '');
            window.localStorage.setItem("selectAPI", '');

            $('#consid').val('');
            $('#secret').val('');
            $('#user_key').val('');
            $('#username').val('');
            $('#password').val('');
            alert('Berhasil Dihapus');
        }
    })

    $("#btn_show_hide").click(function (e) {
        var type = $("#consid").attr("type");
        if (type === 'password') {
            setInputText('text');
        } else {
            setInputText('password');
        }
    })

    $("#btnJSON").click(function (e) {
        if (global_json != 0) {
            $("#dataJSON").html(syntaxHighlight(JSON.stringify(JSON.parse(global_json), null, 4)));
            $("#modalJSON").modal('show');
        }
    })
});

function setInputText(set = 'text') {
    $("#consid").attr("type", set);
    $("#secret").attr("type", set);
    $("#user_key").attr("type", set);
    $("#username").attr("type", set);
    $("#password").attr("type", set);

    if (set == 'text') {
        $("#btn_show_hide").html('Sembunyikan');

    } else {
        $("#btn_show_hide").html('Tampilkan');
    }

    window.localStorage.setItem("show_hide", set);
}

function setFormREST() {
    if ($("#selectAPI").val() == 'vclaim-dev-v1') {
        $("#inputPcare").hide();
        $("#inputUserKey").hide();
        $("#url").val('https://dvlp.bpjs-kesehatan.go.id/vclaim-rest-1.1/');
    } else if ($("#selectAPI").val() == 'vclaim-dev-v2') {
        $("#inputPcare").hide();
        $("#inputUserKey").show();
        $("#url").val('https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/');
    } else if ($("#selectAPI").val() == 'antrean-rs-dev') {
        $("#inputPcare").hide();
        $("#inputUserKey").show();
        $("#url").val('https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev/');
    } else if ($("#selectAPI").val() == 'pcare-dev') {
        $("#inputPcare").show();
        $("#inputUserKey").hide();
        $("#url").val('https://dvlp.bpjs-kesehatan.go.id:9081/pcare-rest-v3.0/');
    } else if ($("#selectAPI").val() == 'vclaim-v1') {
        $("#inputPcare").hide();
        $("#inputUserKey").hide();
        $("#url").val('https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/');
    } else if ($("#selectAPI").val() == 'vclaim-v2') {
        $("#inputPcare").hide();
        $("#inputUserKey").show();
        $("#url").val('https://apijkn.bpjs-kesehatan.go.id/vclaim-rest/');
    } else if ($("#selectAPI").val() == 'antrean-rs') {
        $("#inputPcare").hide();
        $("#inputUserKey").show();
        $("#url").val('https://apijkn.bpjs-kesehatan.go.id/antreanrs/');
    } else if ($("#selectAPI").val() == 'pcare') {
        $("#inputPcare").show();
        $("#inputUserKey").hide();
        $("#url").val('https://new-api.bpjs-kesehatan.go.id/pcare-rest-v3.0/');
    }
}

function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

function getResponse() {
    var withParam = 0;
    if ($("#withParam").is(":checked")) {
        withParam = 1;
    }

    $.ajax({
        url: "getResponse.php",
        method: "GET",
        data: {
            "jenisAPI": $("#selectAPI").val(),
            "consid": $("#consid").val(),
            "secret": $("#secret").val(),
            "user_key": $("#user_key").val(),
            "is_encrypt": $("#selectIsEncrypt").val(),
            "username": $("#username").val(),
            "password": $("#password").val(),
            "method": $("#method").val(),
            "url": $("#url").val() + $("#endpoint").val(),
            "withParam": withParam,
            "params": $("#params").val(),
        },
        success: function (e) {
            global_json = e
            if (/^[\],:{}\s]*$/.test(global_json.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                jsonViewer.showJSON(JSON.parse(e), null, 2);
            } else {
                global_json = '{"status":"gagal", "pesan":"URL tidak ditemukan"}';
                jsonViewer.showJSON(JSON.parse(global_json));
            }

            $("#send").blur();
            $("#url").blur();
            $("#endpoint").blur();
        },
        beforeSend: function () {
            $("#send").html('Memuat..');
        },
        complete: function () {
            $("#send").html('Kirim');
        },
    })
}