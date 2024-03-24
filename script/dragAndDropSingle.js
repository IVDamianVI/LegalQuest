const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("file-input");
const fileList = document.getElementById("file-list");

function dragOverHandler(event) {
  event.preventDefault();
  dropArea.style.border = "2px dashed #c6c6c6";
}

function dragLeaveHandler() {
  dropArea.style.border = "2px dashed #ff9000";
}

function dropHandler(event) {
  event.preventDefault();
  dropArea.style.border = "2px dashed #ff9000";
  const files = event.dataTransfer.files;
  handleFile(files[0]); // Przetwarzanie tylko pierwszego pliku
}

function handleFile(file) {
  if (!file) return;

  fileList.innerHTML = "";
  const listItem = document.createElement("li");
  listItem.className = "file-item";

  const fileIcon = document.createElement("i");
  fileIcon.innerHTML = getFileIconClass(file.name);
  listItem.appendChild(fileIcon);

  const fileName = document.createTextNode(file.name);
  listItem.appendChild(fileName);

  fileList.appendChild(listItem);

  fileInput.files = [file]; // Ustawienie tylko jednego pliku
}

function getFileIconClass(fileName) {
  const fileExtension = getFileExtension(fileName);
  return (
    '<i class="bi bi-filetype-' +
    fileExtension +
    '" style="color: #ff9000"></i> '
  );
}

function getFileExtension(fileName) {
  return fileName.split(".").pop().toLowerCase();
}

function isAttachmentEmpty() {
  return fileInput.files.length === 0;
}

function updateSubmitButton() {
  if (!isAttachmentEmpty()) {
    $('button[type="submit"]').prop("disabled", false);
    $('button[type="submit"]').addClass("ready");
  } else {
    $('button[type="submit"]').prop("disabled", true);
    $('button[type="submit"]').removeClass("ready");
  }
}

// Reakcja na zmianę w input file
$("#file-input").on("change", function () {
  handleFile(this.files[0]); // Przetwarzanie tylko pierwszego pliku
  updateSubmitButton();
});
