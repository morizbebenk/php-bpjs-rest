<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/json-viewer.css" />

    <title>VClaim & PCare Rest API Servis</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1>VClaim & PCare Rest API Servis</h1>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-7">
                <div class="row form-row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" id="selectJenisAPI">
                                <option value="0" selected disabled>>> Pilih Servis <<</option>
                                <option value="0" disabled>>> DEVELOPMENT <<</option>
                                <option value="vclaim-dev-v1">VClaim v1.1 DEV</option>
                                <option value="vclaim-dev-v2">VClaim v2.0 DEV</option>
                                <option value="antrean-rs-dev">Antrean RS DEV</option>
                                <option value="pcare-dev">PCare DEV [Klinik]</option>
                                <option value="0" disabled>>> PRODUCTION <<</option>
                                <option value="vclaim-v1">VClaim v1.1</option>
                                <option value="vclaim-v2">VClaim v2.0</option>
                                <option value="pcare">PCare [Klinik]</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="consid" placeholder="ConsID">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="secret" placeholder="Secret">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button id="btn_show_hide" class="btn btn-primary btn-block">Sembunyikan</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-row" id="inputPcare">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" id="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" id="password" placeholder="Password">
                        </div>
                    </div>
                </div>
                <div class="row form-row" id="inputUserKey">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" id="user_key" placeholder="User Key">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" id="selectIsEncrypt">
                                <option value="1">Dekripsi</option>
                                <option value="0">Non Dekripsi</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <select class="custom-select" id="method">
                                    <option value="GET" selected>GET</option>
                                    <option value="POST">POST</option>
                                    <option value="PUT">PUT</option>
                                    <option value="DELETE">DELETE</option>
                                </select>
                            </div>
                            <input type="text" class="form-control" id="url" value="https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/" style="background-color: white;">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary text-white">
                                    Endpoint API
                                </div>
                            </div>
                            <input type="text" class="form-control" id="endpoint" placeholder="Endpoint">
                            <div class="input-group-append">
                                <button type="button" id="send" class="btn btn-success">Kirim</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-primary text-white">
                                    <input type="checkbox" id="withParam" class="mr-2" value="1"> Parameter
                                </div>
                            </div>
                            <textarea class="form-control" id="params" placeholder="Format JSON" style="resize: vertical;" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success btn-block" id="btnSaveCredential">Simpan Data Lokal</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-danger btn-block" id="btnDeleteCredential">Hapus Data Lokal</button>
                    </div>
                    <div class="col-md-3">
                        <a href="https://dvlp.bpjs-kesehatan.go.id:8888/trust-mark/portal.html" target="_blank" class="btn btn-outline-primary btn-block">Dokumentasi VClaim</a>
                    </div>
                    <div class="col-md-3">
                        <a href="https://new-api.bpjs-kesehatan.go.id/pcare-rest-v3.0" target="_blank" class="btn btn-outline-dark btn-block">Dokumentasi PCare</a>
                    </div>
                    <div class="col-md-3 mt-2">
                        <a href="change_log.json" target="_blank" class="btn btn-outline-info btn-block">Catatan Perubahan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-success text-white">
                                    Response :
                                </div>
                            </div>
                            <select class="custom-select" id="collapse">
                                <option class="collapseResponse" value="collapse_default" selected>Tutup Semua Data
                                </option>
                                <option class="collapseResponse" value="collapse_all">Tampilkan Semua Data</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="jsonResponse"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row fixed-bottom">
        <div class="col-md-12 ml-3 mb-2">
            <a href="https://github.com/morizbebenk" target="_blank" class="text-dark" style="text-decoration: none;">Dibuat Oleh Moriz</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/json-viewer.js"></script>
    <script src="js/main.js"></script>
</body>

</html>