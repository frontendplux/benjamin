<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Image & Video Upload</title>
<style>
  * { box-sizing: border-box; }
  body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 40px 20px;
    color: #222;
  }
  .container {
    max-width: 700px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 30px;
  }
  h1 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 5px;
  }
  p.subtitle {
    text-align: center;
    color: #777;
    margin-top: 0;
    margin-bottom: 25px;
  }
  #dropzone {
    border: 3px dashed #9aa5b1;
    border-radius: 10px;
    padding: 50px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #fafbfc;
  }
  #dropzone.dragover {
    border-color: #4a90e2;
    background: #eaf2fd;
  }
  #dropzone p {
    margin: 10px 0 0;
    color: #555;
  }
  #dropzone .icon {
    font-size: 42px;
  }
  #fileInput { display: none; }
  .browse-link {
    color: #4a90e2;
    text-decoration: underline;
    cursor: pointer;
  }
  #fileList {
    margin-top: 25px;
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  .file-item {
    display: flex;
    align-items: center;
    gap: 15px;
    border: 1px solid #e3e6ea;
    border-radius: 8px;
    padding: 12px;
    background: #fcfcfd;
  }
  .file-item .thumb {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    background: #eee;
    flex-shrink: 0;
  }
  .file-item .info {
    flex: 1;
    min-width: 0;
  }
  .file-item .info .name {
    font-weight: 600;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .file-item .info .meta {
    font-size: 12px;
    color: #888;
    margin-top: 3px;
  }
  .progress-bar-bg {
    height: 6px;
    background: #e6e8eb;
    border-radius: 3px;
    margin-top: 8px;
    overflow: hidden;
  }
  .progress-bar-fill {
    height: 100%;
    width: 0%;
    background: #4a90e2;
    transition: width 0.2s ease;
  }
  .status {
    font-size: 12px;
    margin-top: 4px;
    font-weight: 600;
  }
  .status.success { color: #2ecc71; }
  .status.error { color: #e74c3c; }
  .status.uploading { color: #4a90e2; }
  #uploadAllBtn {
    margin-top: 20px;
    width: 100%;
    padding: 12px;
    background: #4a90e2;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    font-weight: 600;
  }
  #uploadAllBtn:disabled {
    background: #b7c6dd;
    cursor: not-allowed;
  }
  .remove-btn {
    background: none;
    border: none;
    color: #c0392b;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
  }
</style>
</head>
<body>

<div class="container">
  <h1>Upload Images & Videos</h1>
  <p class="subtitle">Drag & drop files below, or click to browse</p>

  <div id="dropzone">
    <div class="icon">📁</div>
    <p>Drag & drop images or videos here<br>or <span class="browse-link">click to browse</span></p>
    <input type="file" id="fileInput" multiple accept="image/*,video/*">
  </div>

  <div id="fileList"></div>

  <button id="uploadAllBtn" disabled>Upload All</button>
</div>

<script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const fileListEl = document.getElementById('fileList');
const uploadAllBtn = document.getElementById('uploadAllBtn');

// queue holds { file, id, status }
let queue = [];
let idCounter = 0;

// ---- Drag & Drop events ----
['dragenter', 'dragover'].forEach(evt => {
  dropzone.addEventListener(evt, (e) => {
    e.preventDefault();
    e.stopPropagation();
    dropzone.classList.add('dragover');
  });
});

['dragleave', 'drop'].forEach(evt => {
  dropzone.addEventListener(evt, (e) => {
    e.preventDefault();
    e.stopPropagation();
    dropzone.classList.remove('dragover');
  });
});

dropzone.addEventListener('drop', (e) => {
  const files = e.dataTransfer.files;
  handleFiles(files);
});

dropzone.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', () => {
  handleFiles(fileInput.files);
  fileInput.value = ''; // allow re-selecting same file
});

// ---- Handle newly added files ----
function handleFiles(fileListObj) {
  const files = Array.from(fileListObj);
  files.forEach(file => {
    const isImage = file.type.startsWith('image/');
    const isVideo = file.type.startsWith('video/');
    if (!isImage && !isVideo) {
      alert(`Skipped "${file.name}": only image or video files are allowed.`);
      return;
    }
    const item = {
      id: ++idCounter,
      file,
      status: 'pending' // pending, uploading, success, error
    };
    queue.push(item);
    renderFileItem(item);
  });
  uploadAllBtn.disabled = queue.length === 0;
}

// ---- Render a single file card ----
function renderFileItem(item) {
  const wrapper = document.createElement('div');
  wrapper.className = 'file-item';
  wrapper.id = 'file-' + item.id;

  const isImage = item.file.type.startsWith('image/');
  let thumbHtml = '';
  if (isImage) {
    const url = URL.createObjectURL(item.file);
    thumbHtml = `<img class="thumb" src="${url}" alt="preview">`;
  } else {
    thumbHtml = `<div class="thumb" style="display:flex;align-items:center;justify-content:center;font-size:28px;">🎬</div>`;
  }

  const sizeKb = (item.file.size / 1024).toFixed(1);
  const sizeLabel = sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb + ' KB';

  wrapper.innerHTML = `
    ${thumbHtml}
    <div class="info">
      <div class="name">${escapeHtml(item.file.name)}</div>
      <div class="meta">${sizeLabel} • ${item.file.type || 'unknown type'}</div>
      <div class="progress-bar-bg"><div class="progress-bar-fill" id="progress-${item.id}"></div></div>
      <div class="status" id="status-${item.id}">Ready to upload</div>
    </div>
    <button class="remove-btn" onclick="removeFile(${item.id})" title="Remove">✕</button>
  `;

  fileListEl.appendChild(wrapper);
}

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

// ---- Remove file from queue ----
function removeFile(id) {
  queue = queue.filter(item => item.id !== id);
  const el = document.getElementById('file-' + id);
  if (el) el.remove();
  uploadAllBtn.disabled = queue.length === 0;
}

// ---- Upload all pending files ----
uploadAllBtn.addEventListener('click', () => {
  const pending = queue.filter(item => item.status === 'pending' || item.status === 'error');
  if (pending.length === 0) return;
  uploadAllBtn.disabled = true;
  uploadAllBtn.textContent = 'Uploading...';

  let remaining = pending.length;

  pending.forEach(item => {
    uploadFile(item, () => {
      remaining--;
      if (remaining === 0) {
        uploadAllBtn.textContent = 'Upload All';
        uploadAllBtn.disabled = queue.filter(i => i.status !== 'success').length === 0;
      }
    });
  });
});

// ---- Upload a single file via XHR to upload.php ----
function uploadFile(item, onDone) {
  item.status = 'uploading';
  const statusEl = document.getElementById('status-' + item.id);
  const progressEl = document.getElementById('progress-' + item.id);
  statusEl.textContent = 'Uploading...';
  statusEl.className = 'status uploading';

  const formData = new FormData();
  formData.append('file', item.file);

  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'upload.php', true);

  xhr.upload.addEventListener('progress', (e) => {
    if (e.lengthComputable) {
      const percent = Math.round((e.loaded / e.total) * 100);
      progressEl.style.width = percent + '%';
    }
  });

  xhr.onload = () => {
    let response;
    try {
      response = JSON.parse(xhr.responseText);
    } catch (e) {
      response = { success: false, message: 'Invalid server response' };
    }

    if (xhr.status === 200 && response.success) {
      item.status = 'success';
      statusEl.textContent = '✔ Uploaded: ' + response.filename;
      statusEl.className = 'status success';
      progressEl.style.width = '100%';
    } else {
      item.status = 'error';
      statusEl.textContent = '✘ ' + (response.message || 'Upload failed');
      statusEl.className = 'status error';
    }
    onDone();
  };

  xhr.onerror = () => {
    item.status = 'error';
    statusEl.textContent = '✘ Network error during upload';
    statusEl.className = 'status error';
    onDone();
  };

  xhr.send(formData);
}
</script>

</body>
</html>