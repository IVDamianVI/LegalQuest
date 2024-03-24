document.addEventListener("DOMContentLoaded", function () {
  var fileIcons = {
    pdf: ["bi-file-earmark-pdf-fill", "pdf-color"],

    doc: ["bi-file-earmark-word-fill", "word-color"],
    docx: ["bi-file-earmark-word-fill", "word-color"],
    odt: ["bi-file-earmark-word-fill", "word-color"],
    rtf: ["bi-file-earmark-word-fill", "word-color"],
    wpd: ["bi-file-earmark-word-fill", "word-color"],

    xls: ["bi-file-earmark-excel-fill", "excel-color"],
    xlsx: ["bi-file-earmark-excel-fill", "excel-color"],
    ods: ["bi-file-earmark-excel-fill", "excel-color"],
    csv: ["bi-file-earmark-excel-fill", "excel-color"],
    tsv: ["bi-file-earmark-excel-fill", "excel-color"],
    ics: ["bi-file-earmark-excel-fill", "excel-color"],
    vcf: ["bi-file-earmark-excel-fill", "excel-color"],

    ppt: ["bi-file-earmark-ppt-fill", "ppt-color"],
    pptx: ["bi-file-earmark-ppt-fill", "ppt-color"],
    odp: ["bi-file-earmark-ppt-fill", "ppt-color"],
    pps: ["bi-file-earmark-ppt-fill", "ppt-color"],

    txt: ["bi-file-earmark-text-fill", "txt-color"],
    in: ["bi-file-earmark-text-fill", "txt-color"],
    out: ["bi-file-earmark-text-fill", "txt-color"],
    md: ["bi-file-earmark-text-fill", "txt-color"],
    tex: ["bi-file-earmark-text-fill", "txt-color"],
    cfg: ["bi-file-earmark-text-fill", "txt-color"],
    log: ["bi-file-earmark-text-fill", "txt-color"],

    zip: ["bi-file-earmark-zip-fill", "zip-color"],
    rar: ["bi-file-earmark-zip-fill", "zip-color"],
    "7z": ["bi-file-earmark-zip-fill", "zip-color"],
    tar: ["bi-file-earmark-zip-fill", "zip-color"],
    gz: ["bi-file-earmark-zip-fill", "zip-color"],
    bz2: ["bi-file-earmark-zip-fill", "zip-color"],
    xz: ["bi-file-earmark-zip-fill", "zip-color"],

    jpg: ["bi-image-fill", "img-color"],
    jpeg: ["bi-image-fill", "img-color"],
    png: ["bi-image-fill", "img-color"],
    svg: ["bi-image-fill", "img-color"],
    bmp: ["bi-image-fill", "img-color"],
    gif: ["bi-image-fill", "img-color"],
    tiff: ["bi-image-fill", "img-color"],

    mp3: ["bi-file-earmark-music-fill", "music-color"],
    wav: ["bi-file-earmark-music-fill", "music-color"],
    ogg: ["bi-file-earmark-music-fill", "music-color"],
    aac: ["bi-file-earmark-music-fill", "music-color"],
    flac: ["bi-file-earmark-music-fill", "music-color"],

    mp4: ["bi-file-earmark-play-fill", "play-color"],
    mov: ["bi-file-earmark-play-fill", "play-color"],
    avi: ["bi-file-earmark-play-fill", "play-color"],
    mkv: ["bi-file-earmark-play-fill", "play-color"],
    mpg: ["bi-file-earmark-play-fill", "play-color"],
    wmv: ["bi-file-earmark-play-fill", "play-color"],
    webm: ["bi-file-earmark-play-fill", "play-color"],
    vob: ["bi-file-earmark-play-fill", "play-color"],
    flv: ["bi-file-earmark-play-fill", "play-color"],
    rm: ["bi-file-earmark-play-fill", "play-color"],
    
    html: ["bi-file-earmark-code-fill", "code-color"],
    css: ["bi-file-earmark-code-fill", "code-color"],
    js: ["bi-file-earmark-code-fill", "code-color"],
    php: ["bi-file-earmark-code-fill", "code-color"],
    json: ["bi-file-earmark-code-fill", "code-color"],
    sql: ["bi-file-earmark-code-fill", "code-color"],
    py: ["bi-file-earmark-code-fill", "code-color"],
    c: ["bi-file-earmark-code-fill", "code-color"],
    cpp: ["bi-file-earmark-code-fill", "code-color"],
    cs: ["bi-file-earmark-code-fill", "code-color"],
    java: ["bi-file-earmark-code-fill", "code-color"],
    rb: ["bi-file-earmark-code-fill", "code-color"],
    sh: ["bi-file-earmark-code-fill", "code-color"],
    vb: ["bi-file-earmark-code-fill", "code-color"],
    xml: ["bi-file-earmark-code-fill", "code-color"],
    h: ["bi-file-earmark-code-fill", "code-color"],
    hs: ["bi-file-earmark-code-fill", "code-color"],
    go: ["bi-file-earmark-code-fill", "code-color"],
    swift: ["bi-file-earmark-code-fill", "code-color"],
    rs: ["bi-file-earmark-code-fill", "code-color"],
    pl: ["bi-file-earmark-code-fill", "code-color"],
    cshtml: ["bi-file-earmark-code-fill", "code-color"],
    aspx: ["bi-file-earmark-code-fill", "code-color"],
  };

  var rows = document.querySelectorAll("#table tbody tr");

  rows.forEach(function (row) {
    var fileName = row.cells[0].querySelector(".file").innerText;
    var fileType = getFileExtension(fileName).toLowerCase();
    var iconSpan = row.querySelector(".icon");

    if (fileIcons[fileType]) {
      var iconClass = fileIcons[fileType][0];
      var colorClass = fileIcons[fileType][1];

      iconSpan.classList.add(iconClass);
      iconSpan.classList.add(colorClass);
    } else {
      iconSpan.classList.add("bi-file-earmark-fill");
    }
  });

  function getFileExtension(filename) {
    return filename.slice(((filename.lastIndexOf(".") - 1) >>> 0) + 2);
  }
});
