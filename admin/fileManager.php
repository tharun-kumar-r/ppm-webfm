<?php
require "fileManagerConfig.php";


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <title>PHP File Manager</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 0px;
        margin: auto;
    }

    h2 {
        text-align: center;
    }

    .toolbar {
        background: #f7f7f7;
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        padding: 10px;
        border-bottom: 1px solid #dfdfdf;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
    }

    .file-container {
        display: flex;
        flex-wrap: wrap;
        width: 68%;
        overflow-x: hidden;
        height: 89vh;
        overflow-y: scroll;
    }

    .file-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        border: 1px solid #ddd;
        width: 94%;
        cursor: pointer;
    }

    .icon {
        width: 30px;
        height: 30px;
        object-fit: cover;
    }

    .filename {
        flex-grow: 1;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .filesize {
        color: gray;
        font-size: 12px;
    }

    .actions button {
        margin-left: 5px;
        cursor: pointer;
    }

    .h-button {
        background: #f7f7f7;
        display: flex;
        align-items: center;
        cursor: pointer;
        overflow: none;
        border: none;
        font-size: 15px;
        padding: 3px 11px;
        margin-left: 3px;
    }

    .h-button:hover {
        background: #e8f3ff;
        color: #000000
    }

    /* Upload Progress */
    .upload-status {
        display: none;
        background: #f4f4f4;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
    }

    .upload-list {
        list-style: none;
        padding: 0;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .progress-container {
        width: 100%;
        background: #ddd;
        border-radius: 5px;
    }

    .progress-bar {
        height: 16px;
        background: #075b9b;
        text-align: center;
        color: white;
        font-weight: bold;
        font-size: 10px;
        display: grid;
        align-items: center;
    }

    .cancel-btn {
        cursor: pointer;
        padding: 10px;
    }

    ul {
        padding: 0;
        margin: 0;
        list-style-position: inside;
        /* Optional: keeps bullet inside */
    }

    li {
        margin: 10px;
        list-style: none;
        padding: 0;
    }

    .progress {
        display: flex;
        align-items: center;
    }

    .fname {
        display: inline-block;
        max-width: 80%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    .nocolor {
        background: white;
        padding: 0px;
        margin: 0px;
        border: none;
        cursor: pointer;
    }

    .loader {
        width: 32px;
        /* Reduced size */
        height: 32px;
        /* Reduced size */
        display: inline-block;
        position: relative;
        border-width: 2px 1.5px 2px 1.5px;
        /* Adjusted border width */
        border-style: solid dotted solid dotted;
        border-color: #006cbe rgba(255, 255, 255, 0.3) #fff rgba(151, 107, 93, 0.3);
        border-radius: 50%;
        box-sizing: border-box;
        animation: 1s rotate linear infinite;
        position: absolute;
        left: 35%;
        background: white;
    }

    .loader:before,
    .loader:after {
        content: '';
        top: 0;
        left: 0;
        position: absolute;
        border: 6px solid transparent;
        /* Adjusted border size */
        border-bottom-color: #fff;
        transform: translate(-6px, 12px) rotate(-35deg);
    }

    .h-button:disabled {
        background: #f7f7f7;
        color: gray;
        cursor: default;
        opacity: 0.5;
    }

    .loader:after {
        border-color: #006cbe #0000 #0000 #0000;
        transform: translate(20px, 2px) rotate(-35deg);
    }

    @keyframes rotate {
        100% {
            transform: rotate(360deg);
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="toolbar">
            <div><b>File Manager : <span id="sfolderSize"></span></b><br></div>
            <div style="display:flex">
                <label id="iupload" class="h-button"><input class="h-button" style="display:none" type="file"
                        id="uploadFile" multiple><img style="height:20px;margin-right:4px;"
                        src="<?php echo $upload_img;?>"> Upload
                    Files</label>
                <button class="h-button" id="refresh"><img style="height:20px;margin-right:4px;"
                        src="<?php echo $refresh;?>"> Refresh</button>
                <button class="h-button" id="createFolderBtn"><img style="height:20px;margin-right:4px;"
                        src="<?php echo $folder;?>"> New Folder</button>

                <label class="h-button"><input id="selectAll" type="checkbox" />Select All</label>
                <button class="h-button" id="deleteSelected"><img style="height:20px;margin-right:4px;"
                        src="<?php echo $del;?>"> Delete</button>
            </div>
        </div>

        <span id="load" class="loader"></span>
        <div style="display:flex;justify-content: space-between;padding:5px">
            <div class="file-container">


                <ul style="width:100%" id="fileList"></ul>
            </div>

            <!-- Upload Status -->
             <div style="    overflow: scroll;width:30%;height:88vh;border:1px solid #dddddd">
                <div><label class="h-button"><input id="convertToWebp" type="checkbox"><span style="padding-left: 9px;">Compress, convert, and enhance quality to Webp.</span></label></div>
            <div>
                <div style=" display:none;top: 50%; position: absolute;width:30%; text-align: center;" id="notask">✔️ No
                    Tasks Pending.</div>
                <ul style="width:100%" id="UfileList"></ul>
            </div>
            </div>
        </div>

    </div>

    <script>
    let currentPath = "";
    let xhr;

    function loadFiles() {
        $("#load").show();
        noTask();
        $.post("fileUpload.php", {
            action: "list",
            path: currentPath
        }, function(data) {
            $("#load").hide();
            let files = JSON.parse(data);
            let fileList = $("#fileList").empty();

            if (currentPath !== "" && currentPath !== "<?php echo $uploads; ?>") {
                fileList.append(
                    `<li class="file-item" onclick="goBack()"><img height="20px" src="<?php echo $back ?>"/> Back</li>`
                );
            }
            if (files.length > 0) {
                files.forEach(file => {
                    let preview = file.is_dir ?
                        `<img src="<?php echo $folder; ?>" loading="lazy"  class="icon">` :
                        `<img src="${file.path}" loading="lazy"  
               onerror="this.src='${file.name.toLowerCase().endsWith('.pdf') ? '<?php echo $pdf; ?>' : '<?php echo $file; ?>'}'" 
               class="icon">`;

                    let clickAction = file.is_dir ?
                        `onclick="navigateTo('${file.name}')"` :
                        "";

                    fileList.append(`
                       <li class="file-item">
                            <input type="checkbox" class="file-checkbox" value="${file.name}">
                            ${preview}
                            <span class="filename" ${clickAction} title="${file.name}">${file.name}</span>
                            ${!file.is_dir ? `<span class="filesize">(${file.size})</span>
                            <div class="actions">`: ''}
                                ${!file.is_dir ? `<button class="nocolor" onclick="download('${file.path}')"><img src="<?php echo $down; ?>"/></button>` : ''}
                                ${!file.is_dir ? `<button class="nocolor" onclick="copyPath('${file.path}')"><img src="<?php echo $copy; ?>"/></button>` : ''}
                                <button class="nocolor" onclick="deleteFile('${file.name}')"><img src="<?php echo $del; ?>"/></button>
                            </div>
                        </li>

                    `);
                });
            } else {
                $("#fileList").append(`<li class="file-item">No Files or Folders Found!.</i>`);
            }
            loadImg();
            $("#deleteSelected").prop("disabled", $(".file-checkbox:checked").length === 0);
            $("#selectAll").prop("checked", !$(".file-checkbox:checked").length === 0);
            getFolderSize();

        });
    }

    function navigateTo(folder) {
        currentPath += folder + "/";
        loadFiles();
    }

    function goBack() {
        let parts = currentPath.split("/").filter(Boolean);
        if (parts.length > 1) {
            parts.pop();
            currentPath = parts.join("/") + "/";
        } else {
            currentPath = "";
        }
        loadFiles();
    }

    function copyPath(path) {
        navigator.clipboard.writeText(path).then(() => alert("File path copied: " + path));
    }

    function deleteFile(filename, nocnfm = false) {
        if (!nocnfm) {
            if (confirm(`Are you sure you want to delete "${filename}"?`)) {
                $.post("fileUpload.php", {
                    action: "delete",
                    path: currentPath,
                    filename: filename
                }, function(res) {
                    if (res === "success") loadFiles();
                });
            }
        } else {
            $.post("fileUpload.php", {
                action: "delete",
                path: currentPath,
                filename: filename
            }, function(res) {
                if (res === "success") loadFiles();
            });
        }
        getFolderSize();
    }

    $("#uploadFile").change(function() {
        let files = this.files;

        for (let i = 0; i < files.length; i++) {
            uploadFile(files[i]);
        }
    });

    let uploadRequests = {}; // Store active uploads

    function uploadFile(file) {
        let formData = new FormData();
        formData.append("action", "upload");
        formData.append("path", currentPath);
        formData.append("files[]", file);
        formData.append("convert_webp", document.getElementById('convertToWebp').checked ? 1 : 0);

        let fileId = file.name.replace(/\W/g, ''); // Unique identifier for file

        let listItem = $(`
        <li style="padding:10px;background:whitesmoke" id="upload-item-${fileId}">
            <span class="fname">${file.name}</span>
            <div class="progress"><div class="progress-container">
                <div class="progress-bar" id="progress-${fileId}">0%</div>
            </div>
            <label class="cancel-btn" id="cancel-${fileId}">X</label></div>
        </li>
    `);

        $("#UfileList").append(listItem);
        noTask();

        let xhr = $.ajax({
            url: "fileUpload.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        let percent = (evt.loaded / evt.total) * 100;
                        $(`#progress-${fileId}`).css("width", percent + "%").text(Math.round(
                            percent) + "%");
                    }
                }, false);
                return xhr;
            },
            success: function(data) {
                if (data === "success") {
                    $(`#upload-item-${fileId}`).remove();
                    delete uploadRequests[fileId];
                    loadFiles();
                } else if (data === "error") {
                    $(`#progress-${fileId}`).css("background", "red").text("Upload failed!");
                } else {
                    $(`#progress-${fileId}`).css("background", "red").text(
                        "Error:File size < 5MB or Invalid file!.");
                }
            },
            error: function() {
                $(`#progress-${fileId}`).css("background", "red").text("Failed");
            }
        });

        uploadRequests[fileId] = xhr; // Store XHR request

        // Handle cancel upload
        $(`#cancel-${fileId}`).click(function() {
            if (uploadRequests[fileId]) {
                uploadRequests[fileId].abort();
                delete uploadRequests[fileId];
                $(`#upload-item-${fileId}`).remove();
                noTask();
            }
        });
    }



    $("#createFolderBtn").click(() => {
        let folderName = prompt("Enter folder name:");
        if (folderName) {
            $.post("fileUpload.php", {
                action: "create_folder",
                path: currentPath,
                folder_name: folderName
            }, function(res) {
                if (res === "success") loadFiles();
            });
        }
    });

    function noTask() {
        if ($("#UfileList").children().length === 0) {
            $("#notask").show();
        } else {
            $("#notask").hide();
        }
    }


    function loadImg() {
        $("img").lazyload({
            effect: "fadeIn"
        });
    }

    $(window).on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    $(window).on("drop", function(e) {
        e.preventDefault();
        e.stopPropagation();

        let files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            uploadFile(files[i]);
        }
    }

    $("#selectAll").click(() => {
        const checkboxes = $(".file-checkbox");
        const allChecked = checkboxes.length === checkboxes.filter(":checked").length;
        checkboxes.prop("checked", !allChecked);
        $("#deleteSelected").prop("disabled", $(".file-checkbox:checked").length === 0);
    });

    $(document).ready(function() {
        $(document).on("change", ".file-checkbox", function() {
            $("#deleteSelected").prop("disabled", $(".file-checkbox:checked").length === 0);
            $("#selectAll").prop("checked", ($(".file-checkbox:checked").length === $(".file-checkbox")
                .length));

        });
    });

    function download(file) {
        let link = document.createElement("a");
        link.href = file;
        link.download = file.split('/').pop(); // Extracts filename from path
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function formatSize(size) {
        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
        let i = 0;

        while (size >= 1024 && i < units.length - 1) {
            size /= 1024;
            i++;
        }

        return size.toFixed(1) + ' ' + units[i];
    }


    $("#deleteSelected").click(() => {
        let selectedFiles = $(".file-checkbox:checked").map(function() {
            return $(this).val();
        }).get();

        if (selectedFiles.length === 0) {
            alert("No files selected!");
            return;
        }

        if (confirm(`Are you sure you want to delete ${selectedFiles.length} file(s)?`)) {
            selectedFiles.forEach(filename => deleteFile(filename, true));
        }
    });

    function getFolderSize() {
        $.post("fileUpload.php", {
            action: "get_size"
        }, function(data) {
            let response = JSON.parse(data);

            if (response.size) {
                $("#sfolderSize").text(formatSize(response.size) + " / " +
                    formatSize("<?php echo $allowedsize;?>")); // Display folder size
                if (response.size > <?php echo $allowedsize;?>) {
                    $("#iupload").hide();
                }else{
                    $("#iupload").show();
                }
            } else {
                $("#sfolderSize").text("0 KB" + " / " +
                formatSize("<?php echo $allowedsize;?>"));
            }
        }).fail(function() {
            $("#sfolderSize").text("Error fetching size");
        });
    }


    $("#refresh").click(loadFiles);
    loadFiles();
    noTask();
    getFolderSize()
    </script>
</body>

</html>